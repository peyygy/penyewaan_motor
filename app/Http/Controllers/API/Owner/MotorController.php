<?php

namespace App\Http\Controllers\API\Owner;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MotorController extends Controller
{
    /**
     * Display a listing of owner's motors
     */
    public function index(Request $request): JsonResponse
    {
        $query = Motor::with(['tarif'])->where('pemilik_id', Auth::id());

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by merk or no_plat
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('merk', 'LIKE', "%{$search}%")
                  ->orWhere('no_plat', 'LIKE', "%{$search}%");
            });
        }

        $motors = $query->orderBy('created_at', 'desc')
                       ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $motors
        ]);
    }

    /**
     * Store a newly created motor
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'merk' => 'required|string|max:50',
            'tipe_cc' => 'required|in:100,125,150',
            'no_plat' => 'required|string|max:20|unique:motors,no_plat',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_kepemilikan' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['merk', 'tipe_cc', 'no_plat']);
        $data['pemilik_id'] = Auth::id();
        $data['status'] = 'pending'; // New motors need verification

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('motors/photos', 'public');
            $data['photo'] = $photoPath;
        }

        // Handle document upload
        if ($request->hasFile('dokumen_kepemilikan')) {
            $docPath = $request->file('dokumen_kepemilikan')->store('motors/documents', 'public');
            $data['dokumen_kepemilikan'] = $docPath;
        }

        $motor = Motor::create($data);
        $motor->load(['tarif']);

        return response()->json([
            'status' => 'success',
            'message' => 'Motor created successfully',
            'data' => $motor
        ], 201);
    }

    /**
     * Display the specified motor
     */
    public function show(Motor $motor): JsonResponse
    {
        // Check if motor belongs to authenticated owner
        if ($motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $motor->load(['tarif', 'penyewaans.penyewa']);

        return response()->json([
            'status' => 'success',
            'data' => $motor
        ]);
    }

    /**
     * Update the specified motor
     */
    public function update(Request $request, Motor $motor): JsonResponse
    {
        // Check if motor belongs to authenticated owner
        if ($motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'merk' => 'string|max:50',
            'tipe_cc' => 'in:100,125,150',
            'no_plat' => 'string|max:20|unique:motors,no_plat,' . $motor->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_kepemilikan' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['merk', 'tipe_cc', 'no_plat']);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($motor->photo) {
                Storage::disk('public')->delete($motor->photo);
            }
            $photoPath = $request->file('photo')->store('motors/photos', 'public');
            $data['photo'] = $photoPath;
        }

        // Handle document upload
        if ($request->hasFile('dokumen_kepemilikan')) {
            // Delete old document if exists
            if ($motor->dokumen_kepemilikan) {
                Storage::disk('public')->delete($motor->dokumen_kepemilikan);
            }
            $docPath = $request->file('dokumen_kepemilikan')->store('motors/documents', 'public');
            $data['dokumen_kepemilikan'] = $docPath;
        }

        $motor->update($data);
        $motor->load(['tarif']);

        return response()->json([
            'status' => 'success',
            'message' => 'Motor updated successfully',
            'data' => $motor
        ]);
    }

    /**
     * Remove the specified motor
     */
    public function destroy(Motor $motor): JsonResponse
    {
        // Check if motor belongs to authenticated owner
        if ($motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Check if motor has active bookings
        if ($motor->penyewaans()->whereIn('status', ['pending', 'confirmed', 'active'])->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete motor with active bookings'
            ], 422);
        }

        // Delete associated files
        if ($motor->photo) {
            Storage::disk('public')->delete($motor->photo);
        }
        if ($motor->dokumen_kepemilikan) {
            Storage::disk('public')->delete($motor->dokumen_kepemilikan);
        }

        $motor->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Motor deleted successfully'
        ]);
    }

    /**
     * Upload motor photo
     */
    public function uploadPhoto(Request $request, Motor $motor): JsonResponse
    {
        // Check if motor belongs to authenticated owner
        if ($motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Delete old photo if exists
        if ($motor->photo) {
            Storage::disk('public')->delete($motor->photo);
        }

        $photoPath = $request->file('photo')->store('motors/photos', 'public');
        $motor->update(['photo' => $photoPath]);

        return response()->json([
            'status' => 'success',
            'message' => 'Photo uploaded successfully',
            'data' => [
                'photo_url' => Storage::url($photoPath)
            ]
        ]);
    }

    /**
     * Upload motor document
     */
    public function uploadDocument(Request $request, Motor $motor): JsonResponse
    {
        // Check if motor belongs to authenticated owner
        if ($motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'dokumen_kepemilikan' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Delete old document if exists
        if ($motor->dokumen_kepemilikan) {
            Storage::disk('public')->delete($motor->dokumen_kepemilikan);
        }

        $docPath = $request->file('dokumen_kepemilikan')->store('motors/documents', 'public');
        $motor->update(['dokumen_kepemilikan' => $docPath]);

        return response()->json([
            'status' => 'success',
            'message' => 'Document uploaded successfully',
            'data' => [
                'document_url' => Storage::url($docPath)
            ]
        ]);
    }

    /**
     * Update motor status
     */
    public function updateStatus(Request $request, Motor $motor): JsonResponse
    {
        // Check if motor belongs to authenticated owner
        if ($motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:available,maintenance',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $motor->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Motor status updated successfully',
            'data' => $motor
        ]);
    }

    /**
     * Get dashboard statistics for owner
     */
    public function dashboardStats(): JsonResponse
    {
        $ownerId = Auth::id();
        
        $stats = [
            'total_motors' => Motor::where('pemilik_id', $ownerId)->count(),
            'verified_motors' => Motor::where('pemilik_id', $ownerId)->where('status', 'verified')->count(),
            'available_motors' => Motor::where('pemilik_id', $ownerId)->where('status', 'available')->count(),
            'rented_motors' => Motor::where('pemilik_id', $ownerId)->where('status', 'rented')->count(),
            'total_bookings' => Penyewaan::whereHas('motor', function($query) use ($ownerId) {
                $query->where('pemilik_id', $ownerId);
            })->count(),
            'active_bookings' => Penyewaan::whereHas('motor', function($query) use ($ownerId) {
                $query->where('pemilik_id', $ownerId);
            })->where('status', 'active')->count(),
            'pending_bookings' => Penyewaan::whereHas('motor', function($query) use ($ownerId) {
                $query->where('pemilik_id', $ownerId);
            })->where('status', 'pending')->count(),
            'completed_bookings' => Penyewaan::whereHas('motor', function($query) use ($ownerId) {
                $query->where('pemilik_id', $ownerId);
            })->where('status', 'completed')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}