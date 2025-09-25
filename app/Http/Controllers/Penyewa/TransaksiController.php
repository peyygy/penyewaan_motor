<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of user's transactions.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['penyewaan.motor'])
                          ->whereHas('penyewaan', function($q) {
                              $q->where('penyewa_id', Auth::id());
                          });

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->has('metode_pembayaran') && $request->metode_pembayaran) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_transaksi' => $query->count(),
            'transaksi_sukses' => $query->where('status', 'success')->count(),
            'total_pengeluaran' => $query->where('status', 'success')->sum('jumlah'),
            'transaksi_pending' => $query->where('status', 'pending')->count(),
        ];

        return view('penyewa.transaksis.index', compact('transaksis', 'stats'));
    }

    /**
     * Store a newly created transaction (payment).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'metode_pembayaran' => 'required|in:cash,bank_transfer,e_wallet,credit_card',
        ]);

        try {
            DB::beginTransaction();

            // Pastikan penyewaan ini milik user yang login
            $penyewaan = Penyewaan::where('id', $validated['penyewaan_id'])
                                  ->where('penyewa_id', Auth::id())
                                  ->firstOrFail();

            // Pastikan penyewaan belum memiliki transaksi
            if ($penyewaan->transaksi) {
                return back()->withErrors(['error' => 'Penyewaan ini sudah memiliki transaksi.']);
            }

            // Pastikan status penyewaan memungkinkan untuk pembayaran
            if (!in_array($penyewaan->status, ['pending', 'confirmed'])) {
                return back()->withErrors(['error' => 'Status penyewaan tidak memungkinkan untuk pembayaran.']);
            }

            // Buat transaksi
            $transaksi = Transaksi::create([
                'penyewaan_id' => $penyewaan->id,
                'jumlah' => $penyewaan->harga,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status' => 'pending', // Default pending, akan diupdate setelah konfirmasi
                'tanggal' => now(),
            ]);

            // Update status penyewaan menjadi confirmed jika belum
            if ($penyewaan->status === 'pending') {
                $penyewaan->update(['status' => 'confirmed']);
            }

            DB::commit();

            return redirect()->route('penyewa.transaksis.show', $transaksi)
                           ->with('success', 'Transaksi berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat transaksi.']);
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaksi $transaksi)
    {
        // Pastikan transaksi ini milik user yang login
        if ($transaksi->penyewaan->penyewa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaksi->load(['penyewaan.motor.pemilik']);

        return view('penyewa.transaksis.show', compact('transaksi'));
    }
}