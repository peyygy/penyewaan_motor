<?php

namespace App\Http\Controllers\API\Owner;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of owner's motor bookings
     */
    public function index(Request $request): JsonResponse
    {
        $query = Penyewaan::with(['penyewa', 'motor'])
                          ->whereHas('motor', function ($q) {
                              $q->where('pemilik_id', Auth::id());
                          });

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by motor_id (owner's motors only)
        if ($request->has('motor_id')) {
            $query->where('motor_id', $request->motor_id)
                  ->whereHas('motor', function ($q) {
                      $q->where('pemilik_id', Auth::id());
                  });
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
     * Display the specified penyewaan
     */
    public function show(Penyewaan $penyewaan): JsonResponse
    {
        // Check if penyewaan belongs to owner's motor
        if ($penyewaan->motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $penyewaan->load(['penyewa', 'motor']);

        return response()->json([
            'status' => 'success',
            'data' => $penyewaan
        ]);
    }

    /**
     * Approve penyewaan
     */
    public function approve(Penyewaan $penyewaan): JsonResponse
    {
        // Check if penyewaan belongs to owner's motor
        if ($penyewaan->motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        if ($penyewaan->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending bookings can be approved'
            ], 422);
        }

        $penyewaan->update(['status' => 'confirmed']);

        // Update motor status to rented if booking is confirmed
        $penyewaan->motor->update(['status' => 'rented']);

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
        // Check if penyewaan belongs to owner's motor
        if ($penyewaan->motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        if ($penyewaan->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending bookings can be rejected'
            ], 422);
        }

        $penyewaan->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'Penyewaan rejected successfully',
            'data' => $penyewaan
        ]);
    }

    /**
     * Complete penyewaan
     */
    public function complete(Penyewaan $penyewaan): JsonResponse
    {
        // Check if penyewaan belongs to owner's motor
        if ($penyewaan->motor->pemilik_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        if ($penyewaan->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only active bookings can be completed'
            ], 422);
        }

        $penyewaan->update(['status' => 'completed']);

        // Update motor status back to available
        $penyewaan->motor->update(['status' => 'available']);

        return response()->json([
            'status' => 'success',
            'message' => 'Penyewaan completed successfully',
            'data' => $penyewaan
        ]);
    }
}