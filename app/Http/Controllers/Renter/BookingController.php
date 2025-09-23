<?php

namespace App\Http\Controllers\Renter;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Motor;
use App\Models\Transaksi;
use App\Enums\BookingStatus;
use App\Enums\MotorStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of user's bookings.
     */
    public function index(): View
    {
        $bookings = Penyewaan::where('penyewa_id', Auth::id())
            ->with(['motor.owner', 'transaksi'])
            ->latest()
            ->paginate(10);

        return view('renter.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $motorId = $request->get('motor_id');
        $motor = Motor::with(['owner', 'tarif'])->findOrFail($motorId);

        if ($motor->status !== MotorStatus::AVAILABLE) {
            return redirect()->route('renter.motors.index')
                ->with('error', 'Motor tidak tersedia untuk disewa.');
        }

        return view('renter.bookings.create', compact('motor'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'catatan' => 'nullable|string|max:500',
        ]);

        $motor = Motor::with('tarif')->findOrFail($validated['motor_id']);

        if ($motor->status !== MotorStatus::AVAILABLE) {
            return redirect()->back()->with('error', 'Motor tidak tersedia untuk disewa.');
        }

        // Check for conflicting bookings
        $conflictingBooking = Penyewaan::where('motor_id', $motor->id)
            ->where(function($query) use ($validated) {
                $query->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                      ->orWhereBetween('tanggal_selesai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                            ->where('tanggal_selesai', '>=', $validated['tanggal_selesai']);
                      });
            })
            ->whereIn('status', [BookingStatus::PENDING, BookingStatus::CONFIRMED, BookingStatus::ACTIVE])
            ->exists();

        if ($conflictingBooking) {
            return redirect()->back()->with('error', 'Motor sudah dibooking untuk tanggal tersebut.');
        }

        // Calculate duration and price
        $startDate = Carbon::parse($validated['tanggal_mulai']);
        $endDate = Carbon::parse($validated['tanggal_selesai']);
        $duration = $startDate->diffInDays($endDate) + 1;

        // Calculate price based on duration
        $pricePerDay = $motor->tarif->harga_per_hari;
        $totalPrice = $pricePerDay * $duration;

        // Apply discounts for longer rentals
        if ($duration >= 7) {
            $weeks = floor($duration / 7);
            $remainingDays = $duration % 7;
            $totalPrice = ($weeks * $motor->tarif->harga_per_minggu) + ($remainingDays * $pricePerDay);
        }
        if ($duration >= 30) {
            $months = floor($duration / 30);
            $remainingDays = $duration % 30;
            $totalPrice = ($months * $motor->tarif->harga_per_bulan) + ($remainingDays * $pricePerDay);
        }

        DB::transaction(function() use ($validated, $motor, $duration, $totalPrice) {
            // Create booking
            $booking = Penyewaan::create([
                'penyewa_id' => Auth::id(),
                'motor_id' => $motor->id,
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'durasi_hari' => $duration,
                'harga' => $totalPrice,
                'status' => BookingStatus::PENDING,
                'catatan' => $validated['catatan'],
            ]);

            // Create transaction record
            Transaksi::create([
                'penyewaan_id' => $booking->id,
                'jumlah' => $totalPrice,
                'metode_pembayaran' => 'pending',
                'status_pembayaran' => 'pending',
                'tanggal_transaksi' => now(),
            ]);
        });

        return redirect()->route('renter.bookings.index')
            ->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Penyewaan $booking): View
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->penyewa_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['motor.owner', 'transaksi']);

        return view('renter.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Penyewaan $booking): View|RedirectResponse
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->penyewa_id !== Auth::id()) {
            abort(403);
        }

        // Only allow editing pending bookings
        if ($booking->status !== BookingStatus::PENDING) {
            return redirect()->route('renter.bookings.show', $booking)
                ->with('error', 'Booking tidak dapat diedit karena sudah dikonfirmasi.');
        }

        $booking->load('motor.tarif');

        return view('renter.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Penyewaan $booking): RedirectResponse
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->penyewa_id !== Auth::id()) {
            abort(403);
        }

        // Only allow editing pending bookings
        if ($booking->status !== BookingStatus::PENDING) {
            return redirect()->back()->with('error', 'Booking tidak dapat diedit karena sudah dikonfirmasi.');
        }

        $validated = $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Check for conflicting bookings (excluding current booking)
        $conflictingBooking = Penyewaan::where('motor_id', $booking->motor_id)
            ->where('id', '!=', $booking->id)
            ->where(function($query) use ($validated) {
                $query->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                      ->orWhereBetween('tanggal_selesai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                            ->where('tanggal_selesai', '>=', $validated['tanggal_selesai']);
                      });
            })
            ->whereIn('status', [BookingStatus::PENDING, BookingStatus::CONFIRMED, BookingStatus::ACTIVE])
            ->exists();

        if ($conflictingBooking) {
            return redirect()->back()->with('error', 'Motor sudah dibooking untuk tanggal tersebut.');
        }

        // Recalculate price
        $motor = $booking->motor()->with('tarif')->first();
        $startDate = Carbon::parse($validated['tanggal_mulai']);
        $endDate = Carbon::parse($validated['tanggal_selesai']);
        $duration = $startDate->diffInDays($endDate) + 1;

        $pricePerDay = $motor->tarif->harga_per_hari;
        $totalPrice = $pricePerDay * $duration;

        // Apply discounts for longer rentals
        if ($duration >= 7) {
            $weeks = floor($duration / 7);
            $remainingDays = $duration % 7;
            $totalPrice = ($weeks * $motor->tarif->harga_per_minggu) + ($remainingDays * $pricePerDay);
        }
        if ($duration >= 30) {
            $months = floor($duration / 30);
            $remainingDays = $duration % 30;
            $totalPrice = ($months * $motor->tarif->harga_per_bulan) + ($remainingDays * $pricePerDay);
        }

        DB::transaction(function() use ($booking, $validated, $duration, $totalPrice) {
            $booking->update([
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'durasi_hari' => $duration,
                'harga' => $totalPrice,
                'catatan' => $validated['catatan'],
            ]);

            // Update transaction amount
            $booking->transaksi()->update(['jumlah' => $totalPrice]);
        });

        return redirect()->route('renter.bookings.show', $booking)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Cancel the specified booking.
     */
    public function cancel(Penyewaan $booking): RedirectResponse
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->penyewa_id !== Auth::id()) {
            abort(403);
        }

        // Only allow canceling pending or confirmed bookings
        if (!in_array($booking->status, [BookingStatus::PENDING, BookingStatus::CONFIRMED])) {
            return redirect()->back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        $booking->update(['status' => BookingStatus::CANCELLED]);

        return redirect()->route('renter.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Penyewaan $booking): RedirectResponse
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->penyewa_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deleting cancelled bookings
        if ($booking->status !== BookingStatus::CANCELLED) {
            return redirect()->back()->with('error', 'Hanya booking yang dibatalkan yang dapat dihapus.');
        }

        $booking->delete();

        return redirect()->route('renter.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}