<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of all penyewaans
     */
    public function index(Request $request): JsonResponse
    {
        $query = Penyewaan::with(['penyewa', 'motor.pemilik']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by penyewa_id
        if ($request->has('penyewa_id')) {
            $query->where('penyewa_id', $request->penyewa_id);
        }

        // Filter by motor_id
        if ($request->has('motor_id')) {
            $query->where('motor_id', $request->motor_id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('tanggal_mulai', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('tanggal_selesai', '<=', $request->date_to);
        }

        $penyewaans = $query->orderBy('created_at', 'desc')
                           ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $penyewaans
        ]);
    }

    /**
     * Store a newly created penyewaan (admin can create for any user)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'penyewa_id' => 'required|exists:users,id',
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan',
            'harga' => 'required|integer|min:0',
            'status' => 'in:pending,confirmed,active,completed,cancelled',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if motor is available
        $motor = \App\Models\Motor::find($request->motor_id);
        if ($motor->status !== 'available') {
            return response()->json([
                'status' => 'error',
                'message' => 'Motor is not available'
            ], 422);
        }

        // Check for conflicting bookings
        $conflictingBooking = Penyewaan::where('motor_id', $request->motor_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                      ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('tanggal_mulai', '<=', $request->tanggal_mulai)
                            ->where('tanggal_selesai', '>=', $request->tanggal_selesai);
                      });
            })->exists();

        if ($conflictingBooking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Motor is already booked for the selected dates'
            ], 422);
        }

        $penyewaan = Penyewaan::create($request->all());
        $penyewaan->load(['penyewa', 'motor.pemilik']);

        return response()->json([
            'status' => 'success',
            'message' => 'Penyewaan created successfully',
            'data' => $penyewaan
        ], 201);
    }

    /**
     * Display the specified penyewaan
     */
    public function show(Penyewaan $penyewaan): JsonResponse
    {
        $penyewaan->load(['penyewa', 'motor.pemilik']);

        return response()->json([
            'status' => 'success',
            'data' => $penyewaan
        ]);
    }

    /**
     * Update the specified penyewaan
     */
    public function update(Request $request, Penyewaan $penyewaan): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date|after:tanggal_mulai',
            'tipe_durasi' => 'in:harian,mingguan,bulanan',
            'harga' => 'integer|min:0',
            'status' => 'in:pending,confirmed,active,completed,cancelled',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $penyewaan->update($request->all());
        $penyewaan->load(['penyewa', 'motor.pemilik']);

        return response()->json([
            'status' => 'success',
            'message' => 'Penyewaan updated successfully',
            'data' => $penyewaan
        ]);
    }

    /**
     * Remove the specified penyewaan
     */
    public function destroy(Penyewaan $penyewaan): JsonResponse
    {
        $penyewaan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Penyewaan deleted successfully'
        ]);
    }

    /**
     * Approve penyewaan
     */
    public function approve(Penyewaan $penyewaan): JsonResponse
    {
        $penyewaan->update(['status' => 'confirmed']);

        return response()->json([
            'status' => 'success',
            'message' => 'Penyewaan approved successfully',
            'data' => $penyewaan
        ]);
    }

    /**
     * Reject penyewaan
     */
    public function reject(Penyewaan $penyewaan): JsonResponse
    {
        $penyewaan->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'Penyewaan rejected successfully',
            'data' => $penyewaan
        ]);
    }
}