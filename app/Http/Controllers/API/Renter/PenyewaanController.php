<?php

namespace App\Http\Controllers\API\Renter;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of renter's bookings
     */
    public function index(Request $request): JsonResponse
    {
        $query = Penyewaan::with(['motor.pemilik'])->where('penyewa_id', Auth::id());

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
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
     * Store a newly created penyewaan
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan',
            'harga' => 'required|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if motor exists and is available for booking
        $motor = Motor::find($request->motor_id);
        if (!$motor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Motor not found'
            ], 404);
        }

        // Check if motor status allows booking (available only)
        if ($motor->status->value !== 'available') {
            return response()->json([
                'status' => 'error',
                'message' => 'Motor is not available for booking. Status: ' . $motor->status->value
            ], 422);
        }

        // Check for conflicting bookings
        $conflictingBooking = Penyewaan::where('motor_id', $request->motor_id)
            ->whereIn('status', ['pending', 'confirmed', 'active'])
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

        $data = $request->all();
        $data['penyewa_id'] = Auth::id();
        $data['status'] = 'pending';

        $penyewaan = Penyewaan::create($data);
        $penyewaan->load(['motor.pemilik']);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking created successfully',
            'data' => $penyewaan
        ], 201);
    }

    /**
     * Display the specified penyewaan
     */
    public function show(Penyewaan $penyewaan): JsonResponse
    {
        // Check if penyewaan belongs to authenticated renter
        if ($penyewaan->penyewa_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $penyewaan->load(['motor.pemilik']);

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
        // Check if penyewaan belongs to authenticated renter
        if ($penyewaan->penyewa_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Only allow updates if booking is still pending
        if ($penyewaan->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending bookings can be updated'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'date|after_or_equal:today',
            'tanggal_selesai' => 'date|after:tanggal_mulai',
            'tipe_durasi' => 'in:harian,mingguan,bulanan',
            'harga' => 'integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If dates are being updated, check for conflicts
        if ($request->has('tanggal_mulai') || $request->has('tanggal_selesai')) {
            $startDate = $request->get('tanggal_mulai', $penyewaan->tanggal_mulai);
            $endDate = $request->get('tanggal_selesai', $penyewaan->tanggal_selesai);

            $conflictingBooking = Penyewaan::where('motor_id', $penyewaan->motor_id)
                ->where('id', '!=', $penyewaan->id) // Exclude current booking
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                          ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('tanggal_mulai', '<=', $startDate)
                                ->where('tanggal_selesai', '>=', $endDate);
                          });
                })->exists();

            if ($conflictingBooking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Motor is already booked for the selected dates'
                ], 422);
            }
        }

        $penyewaan->update($request->only(['tanggal_mulai', 'tanggal_selesai', 'tipe_durasi', 'harga', 'catatan']));
        $penyewaan->load(['motor.pemilik']);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking updated successfully',
            'data' => $penyewaan
        ]);
    }

    /**
     * Remove the specified penyewaan
     */
    public function destroy(Penyewaan $penyewaan): JsonResponse
    {
        // Check if penyewaan belongs to authenticated renter
        if ($penyewaan->penyewa_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Only allow deletion if booking is pending or cancelled
        if (!in_array($penyewaan->status, ['pending', 'cancelled'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending or cancelled bookings can be deleted'
            ], 422);
        }

        $penyewaan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Booking deleted successfully'
        ]);
    }

    /**
     * Cancel penyewaan
     */
    public function cancel(Penyewaan $penyewaan): JsonResponse
    {
        // Check if penyewaan belongs to authenticated renter
        if ($penyewaan->penyewa_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Only allow cancellation if booking is pending or confirmed
        if (!in_array($penyewaan->status, ['pending', 'confirmed'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending or confirmed bookings can be cancelled'
            ], 422);
        }

        $penyewaan->update(['status' => 'cancelled']);

        // If motor was rented, make it available again
        if ($penyewaan->motor->status === 'rented') {
            $penyewaan->motor->update(['status' => 'available']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Booking cancelled successfully',
            'data' => $penyewaan
        ]);
    }

    /**
     * Get available motors for booking
     */
    public function availableMotors(Request $request): JsonResponse
    {
        $query = Motor::with(['pemilik', 'tarif'])->where('status', 'available');

        // Filter by tipe_cc
        if ($request->has('tipe_cc')) {
            $query->where('tipe_cc', $request->tipe_cc);
        }

        // Filter by merk
        if ($request->has('merk')) {
            $query->where('merk', 'LIKE', "%{$request->merk}%");
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('merk', 'LIKE', "%{$search}%")
                  ->orWhere('no_plat', 'LIKE', "%{$search}%");
            });
        }

        // Filter by availability for specific dates
        if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
            $query->whereDoesntHave('penyewaans', function ($q) use ($request) {
                $q->whereIn('status', ['pending', 'confirmed', 'active'])
                  ->where(function ($query) use ($request) {
                      $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                            ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai])
                            ->orWhere(function ($q) use ($request) {
                                $q->where('tanggal_mulai', '<=', $request->tanggal_mulai)
                                  ->where('tanggal_selesai', '>=', $request->tanggal_selesai);
                            });
                  });
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
     * Show specific motor for booking
     */
    public function showMotor(Motor $motor): JsonResponse
    {
        if ($motor->status !== 'available') {
            return response()->json([
                'status' => 'error',
                'message' => 'Motor is not available'
            ], 422);
        }

        $motor->load(['pemilik', 'tarif']);

        return response()->json([
            'status' => 'success',
            'data' => $motor
        ]);
    }

    /**
     * Get dashboard statistics for renter
     */
    public function dashboardStats(): JsonResponse
    {
        $renterId = Auth::id();
        
        $stats = [
            'total_bookings' => Penyewaan::where('penyewa_id', $renterId)->count(),
            'pending_bookings' => Penyewaan::where('penyewa_id', $renterId)->where('status', 'pending')->count(),
            'confirmed_bookings' => Penyewaan::where('penyewa_id', $renterId)->where('status', 'confirmed')->count(),
            'active_bookings' => Penyewaan::where('penyewa_id', $renterId)->where('status', 'active')->count(),
            'completed_bookings' => Penyewaan::where('penyewa_id', $renterId)->where('status', 'completed')->count(),
            'cancelled_bookings' => Penyewaan::where('penyewa_id', $renterId)->where('status', 'cancelled')->count(),
            'total_spent' => Penyewaan::where('penyewa_id', $renterId)
                                    ->where('status', 'completed')
                                    ->sum('harga'),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}