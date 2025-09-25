<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of all bookings for admin.
     */
    public function index()
    {
        // Get all bookings with motor and user relations eager loaded
        $bookings = Penyewaan::with(['motor.pemilik', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        // Admin can create bookings if needed
        return view('admin.bookings.create');
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        // Implementation for admin creating bookings
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Penyewaan $booking)
    {
        $booking->load(['motor', 'user', 'transaksi']);
        
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Penyewaan $booking)
    {
        return view('admin.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Penyewaan $booking)
    {
        // Admin can update any booking
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Penyewaan $booking)
    {
        // Admin can delete bookings if needed
        $booking->delete();
        
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}