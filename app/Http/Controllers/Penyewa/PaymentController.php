<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of user's payments.
     */
    public function index(): View
    {
        $payments = Transaksi::whereHas('penyewaan', function($query) {
                $query->where('penyewa_id', Auth::id());
            })
            ->with(['penyewaan.motor'])
            ->latest()
            ->paginate(10);

        return view('penyewa.payments.index', compact('payments'));
    }

    /**
     * Create a new payment for booking.
     */
    public function create(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:penyewaans,id',
            'metode_pembayaran' => 'required|in:bank_transfer,e_wallet,cash',
        ]);

        $booking = Penyewaan::where('id', $validated['booking_id'])
            ->where('penyewa_id', Auth::id())
            ->firstOrFail();

        // Update transaction with payment method
        $booking->transaksi()->update([
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'processing',
        ]);

        // In a real app, you would integrate with payment gateway here
        // For demo purposes, we'll mark it as paid immediately
        $booking->transaksi()->update(['status_pembayaran' => 'paid']);
        $booking->update(['status' => 'confirmed']);

        return redirect()->route('penyewa.payments.show', $booking->transaksi)
            ->with('success', 'Pembayaran berhasil diproses.');
    }

    /**
     * Display the specified payment.
     */
    public function show(Transaksi $transaksi): View
    {
        // Ensure the payment belongs to the authenticated user
        if ($transaksi->penyewaan->penyewa_id !== Auth::id()) {
            abort(403);
        }

        $transaksi->load(['penyewaan.motor.owner']);

        return view('penyewa.payments.show', compact('transaksi'));
    }

    /**
     * Handle payment gateway callback.
     */
    public function callback(Request $request): RedirectResponse
    {
        // This would handle payment gateway callbacks
        // For demo purposes, we'll just redirect
        
        return redirect()->route('penyewa.payments.index')
            ->with('success', 'Payment callback processed.');
    }
}
