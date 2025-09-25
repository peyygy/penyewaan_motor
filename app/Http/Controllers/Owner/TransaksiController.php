<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['penyewaan.penyewa', 'penyewaan.motor'])
                          ->whereHas('penyewaan.motor', function($q) {
                              $q->where('pemilik_id', Auth::id());
                          });

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->has('metode_pembayaran') && $request->metode_pembayaran) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        // Filter berdasarkan motor
        if ($request->has('motor_id') && $request->motor_id) {
            $query->whereHas('penyewaan', function($q) use ($request) {
                $q->where('motor_id', $request->motor_id);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(15);

        // Motor milik owner untuk filter
        $motors = \App\Models\Motor::where('pemilik_id', Auth::id())->get();

        // Statistics
        $stats = [
            'total_transaksi' => $query->count(),
            'transaksi_sukses' => $query->where('status', 'success')->count(),
            'total_pendapatan' => $query->where('status', 'success')->sum('jumlah'),
            'pendapatan_bulan_ini' => $query->where('status', 'success')
                                           ->whereMonth('tanggal', now()->month)
                                           ->whereYear('tanggal', now()->year)
                                           ->sum('jumlah'),
        ];

        return view('owner.transaksis.index', compact('transaksis', 'motors', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        // Pastikan transaksi ini adalah untuk motor milik owner
        if ($transaksi->penyewaan->motor->pemilik_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaksi->load(['penyewaan.penyewa', 'penyewaan.motor', 'penyewaan.bagiHasil']);

        return view('owner.transaksis.show', compact('transaksi'));
    }
}