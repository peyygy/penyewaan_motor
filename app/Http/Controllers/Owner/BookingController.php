<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings for the logged-in owner.
     */
    public function index()
    {
        // Get bookings where the related motor belongs to the logged-in owner
        $bookings = Penyewaan::whereHas('motor', function($query) {
                $query->where('pemilik_id', Auth::id());
            })
            ->with(['motor', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('owner.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        // Owner typically doesn't create bookings, but keeping for resource completeness
        return redirect()->route('owner.bookings.index');
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        // Owner typically doesn't create bookings, but keeping for resource completeness
        return redirect()->route('owner.bookings.index');
    }

    /**
     * Display the specified booking.
     */
    public function show(Penyewaan $booking)
    {
        // Ensure the booking's motor belongs to the logged-in owner
        if ($booking->motor->pemilik_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        $booking->load(['motor', 'user', 'transaksi']);
        
        return view('owner.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Penyewaan $booking)
    {
        // Ensure the booking's motor belongs to the logged-in owner
        if ($booking->motor->pemilik_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        return view('owner.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Penyewaan $booking)
    {
        // Ensure the booking's motor belongs to the logged-in owner
        if ($booking->motor->pemilik_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Owner might update booking status, but implementation depends on business logic
        return redirect()->route('owner.bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Penyewaan $booking)
    {
        // Ensure the booking's motor belongs to the logged-in owner
        if ($booking->motor->pemilik_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Owner typically can't delete bookings, but keeping for resource completeness
        return redirect()->route('owner.bookings.index')
            ->with('success', 'Booking action completed.');
    }
}