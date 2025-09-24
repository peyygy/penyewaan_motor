<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Motor;
use App\Models\User;
use App\Models\BagiHasil;
use App\Enums\UserRole;
use App\Enums\BookingStatus;
use App\Enums\MotorStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penyewaan::with(['motor', 'penyewa'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_mulai', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_selesai', '<=', $request->end_date);
        }

        // Search by motor merk or penyewa name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('motor', function ($mq) use ($search) {
                    $mq->where('merk', 'like', "%{$search}%");
                })->orWhereHas('penyewa', function ($pq) use ($search) {
                    $pq->where('nama', 'like', "%{$search}%");
                });
            });
        }

        $penyewaans = $query->paginate(10);

        return view('admin.penyewaans.index', compact('penyewaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $motors = Motor::where('status', 'available')->with('tarif')->get();
        $penyewas = User::where('role', UserRole::PENYEWA)->get();

        return view('admin.penyewaans.create', compact('motors', 'penyewas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penyewa_id' => 'required|exists:users,id',
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan',
        ]);

        DB::transaction(function () use ($request) {
            // Calculate days difference
            $startDate = \Carbon\Carbon::parse($request->tanggal_mulai);
            $endDate = \Carbon\Carbon::parse($request->tanggal_selesai);
            $days = $startDate->diffInDays($endDate) + 1;

            // Get motor with tariff
            $motor = Motor::with('tarif')->findOrFail($request->motor_id);
            
            if (!$motor->tarif) {
                throw new \Exception('Motor belum memiliki tarif rental');
            }

            // Calculate price based on duration type
            $harga = $motor->tarif->calculatePrice($request->tipe_durasi, $days);

            // Create penyewaan
            $penyewaan = Penyewaan::create([
                'penyewa_id' => $request->penyewa_id,
                'motor_id' => $request->motor_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tipe_durasi' => $request->tipe_durasi,
                'harga' => $harga,
                'status' => 'pending',
            ]);

            // Update motor status to rented
            $motor->update(['status' => 'rented']);
        });

        return redirect()->route('admin.penyewaans.index')
            ->with('success', 'Penyewaan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penyewaan $penyewaan)
    {
        $penyewaan->load(['motor.owner', 'penyewa', 'transaksi']);
        
        return view('admin.penyewaans.show', compact('penyewaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyewaan $penyewaan)
    {
        $motors = Motor::with('tarif')->where('status', MotorStatus::AVAILABLE)->get();
        $renters = User::where('role', UserRole::PENYEWA)->get();

        return view('admin.penyewaans.edit', compact('penyewaan', 'motors', 'renters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penyewaan $penyewaan)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:pending,confirmed,active,completed,cancelled',
            'catatan' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $penyewaan) {
            $oldStatus = $penyewaan->status;
            
            // Calculate new price if dates changed
            $startDate = \Carbon\Carbon::parse($request->tanggal_mulai);
            $endDate = \Carbon\Carbon::parse($request->tanggal_selesai);
            $days = $startDate->diffInDays($endDate) + 1;

            // Get motor with tariff for recalculation
            $motor = Motor::with('tarif')->findOrFail($request->motor_id);
            $harga = $motor->tarif->calculatePrice($penyewaan->tipe_durasi, $days);

            // Update penyewaan
            $penyewaan->update([
                'penyewa_id' => $request->user_id,
                'motor_id' => $request->motor_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $request->status,
                'harga' => $harga,
                'catatan' => $request->catatan,
            ]);

            // Update motor status based on penyewaan status changes
            if ($request->status === 'completed' && $oldStatus !== 'completed') {
                $penyewaan->motor->update(['status' => MotorStatus::AVAILABLE]);
            } elseif ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                $penyewaan->motor->update(['status' => MotorStatus::AVAILABLE]);
            } elseif ($request->status === 'active' && $oldStatus !== 'active') {
                $penyewaan->motor->update(['status' => MotorStatus::RENTED]);
            }
        });

        return redirect()->route('admin.penyewaans.index')
            ->with('success', 'Penyewaan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyewaan $penyewaan)
    {
        DB::transaction(function () use ($penyewaan) {
            // Return motor to available if penyewaan is active
            if (in_array($penyewaan->status, ['pending', 'confirmed', 'active'])) {
                $penyewaan->motor->update(['status' => 'available']);
            }

            $penyewaan->delete();
        });

        return redirect()->route('admin.penyewaans.index')
            ->with('success', 'Penyewaan berhasil dihapus');
    }

    /**
     * Confirm penyewaan
     */
    public function confirm(Penyewaan $penyewaan)
    {
        if ($penyewaan->status !== 'pending') {
            return back()->with('error', 'Hanya penyewaan dengan status pending yang dapat dikonfirmasi');
        }

        $penyewaan->update(['status' => 'confirmed']);

        return back()->with('success', 'Penyewaan berhasil dikonfirmasi');
    }

    /**
     * Activate penyewaan (start rental period)
     */
    public function activate(Penyewaan $penyewaan)
    {
        if ($penyewaan->status !== 'confirmed') {
            return back()->with('error', 'Hanya penyewaan yang sudah dikonfirmasi yang dapat diaktifkan');
        }

        $penyewaan->update(['status' => 'active']);
        $penyewaan->motor->update(['status' => 'rented']);

        return back()->with('success', 'Penyewaan berhasil diaktifkan');
    }

    /**
     * Complete penyewaan (end rental period)
     */
    public function complete(Penyewaan $penyewaan)
    {
        if ($penyewaan->status !== 'active') {
            return back()->with('error', 'Hanya penyewaan aktif yang dapat diselesaikan');
        }

        DB::transaction(function () use ($penyewaan) {
            $penyewaan->update(['status' => 'completed']);
            $penyewaan->motor->update(['status' => 'available']);

            // Auto generate bagi hasil if not exists
            if (!$penyewaan->bagiHasil()->exists()) {
                $this->generateBagiHasil($penyewaan);
            }
        });

        return back()->with('success', 'Penyewaan berhasil diselesaikan');
    }

    /**
     * Generate bagi hasil for completed penyewaan
     */
    private function generateBagiHasil(Penyewaan $penyewaan)
    {
        // Calculate profit sharing (70% to owner, 30% to admin)
        $totalHarga = $penyewaan->harga;
        $bagiHasilPemilik = $totalHarga * 0.7;
        $bagiHasilAdmin = $totalHarga * 0.3;

        $penyewaan->bagiHasil()->create([
            'bagi_hasil_pemilik' => $bagiHasilPemilik,
            'bagi_hasil_admin' => $bagiHasilAdmin,
            'settled_at' => now(),
            'tanggal' => now(),
        ]);
    }
}