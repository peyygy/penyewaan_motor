<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MotorController extends Controller
{
    /**
     * Display a listing of all motors
     */
    public function index(Request $request): JsonResponse
    {
        $query = Motor::with(['pemilik', 'tarif']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by pemilik_id
        if ($request->has('pemilik_id')) {
            $query->where('pemilik_id', $request->pemilik_id);
        }

        // Filter by merk
        if ($request->has('merk')) {
            $query->where('merk', 'LIKE', "%{$request->merk}%");
        }

        // Filter by tipe_cc
        if ($request->has('tipe_cc')) {
            $query->where('tipe_cc', $request->tipe_cc);
        }

        // Search
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
     * Store a newly created motor (admin can create for any pemilik)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pemilik_id' => 'required|exists:users,id',
            'merk' => 'required|string|max:50',
            'tipe_cc' => 'required|in:100,125,150',
            'no_plat' => 'required|string|max:20|unique:motors,no_plat',
            'status' => 'in:pending,verified,available,rented,maintenance',
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

        $data = $request->only(['pemilik_id', 'merk', 'tipe_cc', 'no_plat', 'status']);
        
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
        $motor->load(['pemilik', 'tarif']);

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
        $motor->load(['pemilik', 'tarif', 'penyewaans.penyewa']);

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
        $validator = Validator::make($request->all(), [
            'pemilik_id' => 'exists:users,id',
            'merk' => 'string|max:50',
            'tipe_cc' => 'in:100,125,150',
            'no_plat' => 'string|max:20|unique:motors,no_plat,' . $motor->id,
            'status' => 'in:pending,verified,available,rented,maintenance',
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

        $data = $request->only(['pemilik_id', 'merk', 'tipe_cc', 'no_plat', 'status']);
        
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

        $motor->update($data);
        $motor->load(['pemilik', 'tarif']);

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
        // Check if motor has active bookings
        if ($motor->penyewaans()->whereIn('status', ['pending', 'confirmed', 'active'])->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete motor with active bookings'
            ], 422);
        }

        $motor->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Motor deleted successfully'
        ]);
    }

    /**
     * Verify motor
     */
    public function verify(Motor $motor): JsonResponse
    {
        $motor->update(['status' => 'verified']);

        return response()->json([
            'status' => 'success',
            'message' => 'Motor verified successfully',
            'data' => $motor
        ]);
    }

    /**
     * Reject motor
     */
    public function reject(Motor $motor): JsonResponse
    {
        $motor->update(['status' => 'pending']);

        return response()->json([
            'status' => 'success',
            'message' => 'Motor verification rejected',
            'data' => $motor
        ]);
    }
}