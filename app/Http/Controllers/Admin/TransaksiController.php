<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['penyewaan.penyewa', 'penyewaan.motor']);

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

        // Statistics untuk dashboard
        $stats = [
            'total_transaksi' => Transaksi::count(),
            'transaksi_sukses' => Transaksi::where('status', 'success')->count(),
            'transaksi_pending' => Transaksi::where('status', 'pending')->count(),
            'total_pendapatan' => Transaksi::where('status', 'success')->sum('jumlah'),
        ];

        return view('admin.transaksis.index', compact('transaksis', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penyewaans = Penyewaan::with(['penyewa', 'motor'])
                              ->where('status', 'confirmed')
                              ->whereDoesntHave('transaksi')
                              ->get();

        return view('admin.transaksis.create', compact('penyewaans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'jumlah' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,bank_transfer,e_wallet,credit_card',
            'status' => 'required|in:pending,success,failed',
            'tanggal' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah penyewaan sudah memiliki transaksi
            $existingTransaksi = Transaksi::where('penyewaan_id', $validated['penyewaan_id'])->first();
            if ($existingTransaksi) {
                return back()->withErrors(['penyewaan_id' => 'Penyewaan ini sudah memiliki transaksi.'])->withInput();
            }

            $transaksi = Transaksi::create($validated);

            // Update status penyewaan jika transaksi berhasil
            if ($validated['status'] === 'success') {
                $transaksi->penyewaan->update(['status' => 'active']);
                
                // Update status motor menjadi rented
                $transaksi->penyewaan->motor->update(['status' => 'rented']);
            }

            DB::commit();

            return redirect()->route('admin.transaksis.index')
                           ->with('success', 'Transaksi berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat transaksi.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['penyewaan.penyewa', 'penyewaan.motor.pemilik']);
        
        return view('admin.transaksis.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        return view('admin.transaksis.edit', compact('transaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,bank_transfer,e_wallet,credit_card',
            'status' => 'required|in:pending,success,failed',
            'tanggal' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $transaksi->status;
            $transaksi->update($validated);

            // Update status penyewaan dan motor berdasarkan status transaksi
            if ($oldStatus !== 'success' && $validated['status'] === 'success') {
                $transaksi->penyewaan->update(['status' => 'active']);
                $transaksi->penyewaan->motor->update(['status' => 'rented']);
            } elseif ($oldStatus === 'success' && $validated['status'] !== 'success') {
                $transaksi->penyewaan->update(['status' => 'confirmed']);
                $transaksi->penyewaan->motor->update(['status' => 'available']);
            }

            DB::commit();

            return redirect()->route('admin.transaksis.index')
                           ->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui transaksi.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        try {
            DB::beginTransaction();

            // Reset status penyewaan dan motor
            if ($transaksi->status === 'success') {
                $transaksi->penyewaan->update(['status' => 'confirmed']);
                $transaksi->penyewaan->motor->update(['status' => 'available']);
            }

            $transaksi->delete();

            DB::commit();

            return redirect()->route('admin.transaksis.index')
                           ->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus transaksi.']);
        }
    }
}