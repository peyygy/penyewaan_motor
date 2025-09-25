<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penyewaan::with(['penyewa', 'motor', 'transaksi'])
                          ->whereHas('motor', function($q) {
                              $q->where('pemilik_id', Auth::id());
                          });

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan motor
        if ($request->has('motor_id') && $request->motor_id) {
            $query->where('motor_id', $request->motor_id);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_sampai);
        }

        $penyewaans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Motor milik owner untuk filter
        $motors = Motor::where('pemilik_id', Auth::id())->get();

        // Statistics
        $stats = [
            'total_penyewaan' => $query->count(),
            'penyewaan_aktif' => Penyewaan::whereHas('motor', function($q) {
                                     $q->where('pemilik_id', Auth::id());
                                 })->where('status', 'active')->count(),
            'total_pendapatan' => Penyewaan::whereHas('motor', function($q) {
                                      $q->where('pemilik_id', Auth::id());
                                  })
                                  ->whereHas('transaksi', function($q) {
                                      $q->where('status', 'success');
                                  })
                                  ->sum('harga'),
        ];

        return view('owner.penyewaans.index', compact('penyewaans', 'motors', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Penyewaan $penyewaan)
    {
        // Pastikan penyewaan ini adalah untuk motor milik owner
        if ($penyewaan->motor->pemilik_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $penyewaan->load(['penyewa', 'motor', 'transaksi', 'bagiHasil']);

        return view('owner.penyewaans.show', compact('penyewaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyewaan $penyewaan)
    {
        // Pastikan penyewaan ini adalah untuk motor milik owner
        if ($penyewaan->motor->pemilik_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Owner hanya bisa edit status tertentu
        $allowedStatuses = ['pending', 'confirmed', 'active', 'completed'];

        return view('owner.penyewaans.edit', compact('penyewaan', 'allowedStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penyewaan $penyewaan)
    {
        // Pastikan penyewaan ini adalah untuk motor milik owner
        if ($penyewaan->motor->pemilik_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,active,completed,cancelled',
            'catatan_pemilik' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $penyewaan->status;
            $penyewaan->update($validated);

            // Update status motor berdasarkan status penyewaan
            if ($validated['status'] === 'completed') {
                $penyewaan->motor->update(['status' => 'available']);
                
                // Generate bagi hasil jika belum ada
                if (!$penyewaan->bagiHasil && $penyewaan->transaksi?->status === 'success') {
                    $this->generateBagiHasil($penyewaan);
                }
            } elseif ($validated['status'] === 'cancelled') {
                $penyewaan->motor->update(['status' => 'available']);
            } elseif ($validated['status'] === 'active' && $oldStatus !== 'active') {
                $penyewaan->motor->update(['status' => 'rented']);
            }

            DB::commit();

            return redirect()->route('owner.penyewaans.index')
                           ->with('success', 'Status penyewaan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui penyewaan.'])->withInput();
        }
    }

    /**
     * Generate bagi hasil untuk penyewaan yang completed
     */
    private function generateBagiHasil(Penyewaan $penyewaan)
    {
        $totalHarga = $penyewaan->harga;
        $persentasePemilik = 70; // 70% untuk pemilik
        $persentaseAdmin = 30; // 30% untuk admin

        $bagiHasilPemilik = ($totalHarga * $persentasePemilik) / 100;
        $bagiHasilAdmin = ($totalHarga * $persentaseAdmin) / 100;

        $penyewaan->bagiHasil()->create([
            'bagi_hasil_pemilik' => $bagiHasilPemilik,
            'bagi_hasil_admin' => $bagiHasilAdmin,
            'tanggal' => now(),
            'settled_at' => null, // Will be set when payment is processed
        ]);
    }
}