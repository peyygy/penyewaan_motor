<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Motor;
use App\Models\TarifRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenyewaanController extends Controller
{
    /**
     * Browse available motors
     */
    public function browseMotors(Request $request)
    {
        $query = Motor::with(['pemilik', 'tarifRental'])
                     ->where('status', 'available');

        // Filter berdasarkan tipe CC
        if ($request->has('tipe_cc') && $request->tipe_cc) {
            $query->where('tipe_cc', $request->tipe_cc);
        }

        // Filter berdasarkan merk
        if ($request->has('merk') && $request->merk) {
            $query->where('merk', 'like', '%' . $request->merk . '%');
        }

        // Filter berdasarkan harga
        if ($request->has('harga_max') && $request->harga_max) {
            $query->whereHas('tarifRental', function($q) use ($request) {
                $q->where('tarif_harian', '<=', $request->harga_max);
            });
        }

        $motors = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('penyewa.motors.index', compact('motors'));
    }

    /**
     * Show motor details
     */
    public function showMotor(Motor $motor)
    {
        if ($motor->status !== 'available') {
            abort(404, 'Motor tidak tersedia.');
        }

        $motor->load(['pemilik', 'tarifRental']);

        return view('penyewa.motors.show', compact('motor'));
    }

    /**
     * Display a listing of user's bookings.
     */
    public function index(Request $request)
    {
        $query = Penyewaan::with(['motor.pemilik', 'transaksi'])
                          ->where('penyewa_id', Auth::id());

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $penyewaans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_penyewaan' => $query->count(),
            'penyewaan_aktif' => Penyewaan::where('penyewa_id', Auth::id())
                                         ->where('status', 'active')
                                         ->count(),
            'total_pengeluaran' => Penyewaan::where('penyewa_id', Auth::id())
                                           ->whereHas('transaksi', function($q) {
                                               $q->where('status', 'success');
                                           })
                                           ->sum('harga'),
        ];

        return view('penyewa.penyewaans.index', compact('penyewaans', 'stats'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request)
    {
        $motorId = $request->get('motor_id');
        $motor = null;

        if ($motorId) {
            $motor = Motor::with('tarifRental')->find($motorId);
            if (!$motor || $motor->status !== 'available') {
                return redirect()->route('penyewa.motors.index')
                               ->with('error', 'Motor tidak tersedia.');
            }
        }

        return view('penyewa.penyewaans.create', compact('motor'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan',
            'catatan' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Cek ketersediaan motor
            $motor = Motor::find($validated['motor_id']);
            if ($motor->status !== 'available') {
                return back()->withErrors(['motor_id' => 'Motor tidak tersedia.'])->withInput();
            }

            // Cek konflik tanggal dengan booking yang sudah ada
            $conflictingBooking = Penyewaan::where('motor_id', $validated['motor_id'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                          ->orWhereBetween('tanggal_selesai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                          ->orWhere(function ($q) use ($validated) {
                              $q->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                                ->where('tanggal_selesai', '>=', $validated['tanggal_selesai']);
                          });
                })
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->exists();

            if ($conflictingBooking) {
                return back()->withErrors(['tanggal_mulai' => 'Motor sudah dibooking pada tanggal tersebut.'])->withInput();
            }

            // Hitung harga berdasarkan durasi
            $harga = $this->calculatePrice($motor, $validated['tanggal_mulai'], $validated['tanggal_selesai'], $validated['tipe_durasi']);

            $penyewaan = Penyewaan::create([
                'penyewa_id' => Auth::id(),
                'motor_id' => $validated['motor_id'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'tipe_durasi' => $validated['tipe_durasi'],
                'harga' => $harga,
                'status' => 'pending',
                'catatan' => $validated['catatan'],
            ]);

            DB::commit();

            return redirect()->route('penyewa.penyewaans.show', $penyewaan)
                           ->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat booking.'])->withInput();
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Penyewaan $penyewaan)
    {
        // Pastikan ini adalah booking milik user yang login
        if ($penyewaan->penyewa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $penyewaan->load(['motor.pemilik.tarifRental', 'transaksi']);

        return view('penyewa.penyewaans.show', compact('penyewaan'));
    }

    /**
     * Show the form for editing the booking.
     */
    public function edit(Penyewaan $penyewaan)
    {
        // Pastikan ini adalah booking milik user yang login
        if ($penyewaan->penyewa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya bisa edit jika status masih pending
        if ($penyewaan->status !== 'pending') {
            return redirect()->route('penyewa.penyewaans.show', $penyewaan)
                           ->with('error', 'Booking ini tidak dapat diedit.');
        }

        return view('penyewa.penyewaans.edit', compact('penyewaan'));
    }

    /**
     * Update the specified booking.
     */
    public function update(Request $request, Penyewaan $penyewaan)
    {
        // Pastikan ini adalah booking milik user yang login
        if ($penyewaan->penyewa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya bisa edit jika status masih pending
        if ($penyewaan->status !== 'pending') {
            return redirect()->route('penyewa.penyewaans.show', $penyewaan)
                           ->with('error', 'Booking ini tidak dapat diedit.');
        }

        $validated = $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan',
            'catatan' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Cek konflik tanggal dengan booking lain
            $conflictingBooking = Penyewaan::where('motor_id', $penyewaan->motor_id)
                ->where('id', '!=', $penyewaan->id)
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                          ->orWhereBetween('tanggal_selesai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                          ->orWhere(function ($q) use ($validated) {
                              $q->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                                ->where('tanggal_selesai', '>=', $validated['tanggal_selesai']);
                          });
                })
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->exists();

            if ($conflictingBooking) {
                return back()->withErrors(['tanggal_mulai' => 'Motor sudah dibooking pada tanggal tersebut.'])->withInput();
            }

            // Hitung ulang harga
            $harga = $this->calculatePrice($penyewaan->motor, $validated['tanggal_mulai'], $validated['tanggal_selesai'], $validated['tipe_durasi']);

            $penyewaan->update([
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'tipe_durasi' => $validated['tipe_durasi'],
                'harga' => $harga,
                'catatan' => $validated['catatan'],
            ]);

            DB::commit();

            return redirect()->route('penyewa.penyewaans.show', $penyewaan)
                           ->with('success', 'Booking berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui booking.'])->withInput();
        }
    }

    /**
     * Remove the specified booking (Cancel).
     */
    public function destroy(Penyewaan $penyewaan)
    {
        // Pastikan ini adalah booking milik user yang login
        if ($penyewaan->penyewa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya bisa cancel jika status pending atau confirmed
        if (!in_array($penyewaan->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        try {
            DB::beginTransaction();

            $penyewaan->update(['status' => 'cancelled']);

            // Jika ada transaksi yang pending, ubah menjadi failed
            if ($penyewaan->transaksi && $penyewaan->transaksi->status === 'pending') {
                $penyewaan->transaksi->update(['status' => 'failed']);
            }

            DB::commit();

            return redirect()->route('penyewa.penyewaans.index')
                           ->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membatalkan booking.');
        }
    }

    /**
     * Show booking history
     */
    public function history(Request $request)
    {
        $query = Penyewaan::with(['motor.pemilik', 'transaksi'])
                          ->where('penyewa_id', Auth::id())
                          ->whereIn('status', ['completed', 'cancelled']);

        // Filter berdasarkan bulan/tahun
        if ($request->has('month') && $request->month) {
            $query->whereMonth('tanggal_mulai', $request->month);
        }

        if ($request->has('year') && $request->year) {
            $query->whereYear('tanggal_mulai', $request->year);
        }

        $penyewaans = $query->orderBy('tanggal_mulai', 'desc')->paginate(15);

        return view('penyewa.penyewaans.history', compact('penyewaans'));
    }

    /**
     * Calculate rental price based on duration
     */
    private function calculatePrice(Motor $motor, $tanggalMulai, $tanggalSelesai, $tipeDurasi)
    {
        $tarifRental = $motor->tarifRental;
        if (!$tarifRental) {
            throw new \Exception('Tarif rental tidak ditemukan untuk motor ini.');
        }

        $startDate = Carbon::parse($tanggalMulai);
        $endDate = Carbon::parse($tanggalSelesai);
        
        switch ($tipeDurasi) {
            case 'harian':
                $days = $startDate->diffInDays($endDate) + 1;
                return $days * $tarifRental->tarif_harian;
                
            case 'mingguan':
                $weeks = ceil($startDate->diffInDays($endDate) / 7);
                return $weeks * $tarifRental->tarif_mingguan;
                
            case 'bulanan':
                $months = $startDate->diffInMonths($endDate) + 1;
                return $months * $tarifRental->tarif_bulanan;
                
            default:
                throw new \Exception('Tipe durasi tidak valid.');
        }
    }
}