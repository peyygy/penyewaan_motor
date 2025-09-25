<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMotorRequest;
use App\Models\Motor;
use App\Models\TarifRental;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\MotorStatus;
use App\Enums\MotorType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class MotorController extends Controller
{
    /**
     * Display a listing of motors.
     */
    public function index(Request $request): View
    {
        $query = Motor::with(['pemilik', 'tarifRental']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('merk', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('no_plat', 'like', "%{$search}%")
                  ->orWhereHas('pemilik', function($ownerQuery) use ($search) {
                      $ownerQuery->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by merk
        if ($request->filled('merk')) {
            $query->where('merk', 'like', "%{$request->merk}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by tipe_cc
        if ($request->filled('tipe_cc')) {
            $query->where('tipe_cc', $request->tipe_cc);
        }

        // Filter by owner
        if ($request->filled('pemilik_id')) {
            $query->where('pemilik_id', $request->pemilik_id);
        }

        $motors = $query->latest()->paginate(15);
        $motors->appends($request->query());

        // Statistics
        $stats = [
            'total_motor' => Motor::count(),
            'motor_available' => Motor::where('status', 'available')->count(),
            'motor_rented' => Motor::where('status', 'rented')->count(),
            'motor_pending' => Motor::where('status', 'pending')->count(),
        ];

        return view('admin.motors.index', compact('motors', 'stats'));
    }

    /**
     * Show the form for creating a new motor.
     */
    public function create(): View
    {
        $owners = User::where('role', UserRole::PEMILIK)->pluck('nama', 'id');
        
        return view('admin.motors.create', compact('owners'));
    }

    /**
     * Store a newly created motor in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pemilik_id' => 'required|exists:users,id',
            'merk' => 'required|string|max:255',
            'tipe_cc' => 'required|integer|in:100,125,150',
            'no_plat' => 'required|string|max:20|unique:motors',
            'status' => 'required|string|in:pending,verified,available,maintenance',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'dokumen_kepemilikan' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'tarif_harian' => 'nullable|numeric|min:0',
            'tarif_mingguan' => 'nullable|numeric|min:0',
            'tarif_bulanan' => 'nullable|numeric|min:0',
        ]);

        // Upload foto if provided
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('motors', 'public');
        }

        // Upload dokumen kepemilikan if provided
        $dokumenPath = null;
        if ($request->hasFile('dokumen_kepemilikan')) {
            $dokumenPath = $request->file('dokumen_kepemilikan')->store('documents', 'public');
        }

        // Create motor
        $motor = Motor::create([
            'pemilik_id' => $request->pemilik_id,
            'merk' => $request->merk,
            'tipe_cc' => $request->tipe_cc,
            'no_plat' => $request->no_plat,
            'status' => $request->status,
            'foto' => $fotoPath,
            'dokumen_kepemilikan' => $dokumenPath,
        ]);

        // Create tarif rental if provided
        if ($request->filled('tarif_harian')) {
            TarifRental::create([
                'motor_id' => $motor->id,
                'tarif_harian' => $request->tarif_harian,
                'tarif_mingguan' => $request->tarif_mingguan ?? ($request->tarif_harian * 6),
                'tarif_bulanan' => $request->tarif_bulanan ?? ($request->tarif_harian * 25),
            ]);
        }

        return redirect()->route('admin.motors.index')
                        ->with('success', 'Motor berhasil ditambahkan.');
    }

    /**
     * Display the specified motor.
     */
    public function show(Motor $motor): View
    {
        $motor->load(['pemilik', 'tarifRental', 'penyewaans.penyewa']);
        
        return view('admin.motors.show', compact('motor'));
    }

    /**
     * Show the form for editing the specified motor.
     */
    public function edit(Motor $motor): View
    {
        $owners = User::where('role', UserRole::PEMILIK)->pluck('nama', 'id');
        
        return view('admin.motors.edit', compact('motor', 'owners'));
    }

    /**
     * Update the specified motor in storage.
     */
    public function update(Request $request, Motor $motor): RedirectResponse
    {
        $request->validate([
            'pemilik_id' => 'required|exists:users,id',
            'merk' => 'required|string|max:255',
            'tipe_cc' => 'required|integer|in:100,125,150',
            'no_plat' => 'required|string|max:20|unique:motors,no_plat,' . $motor->id,
            'status' => 'required|string|in:pending,verified,available,maintenance,rented',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'dokumen_kepemilikan' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'tarif_harian' => 'nullable|numeric|min:0',
            'tarif_mingguan' => 'nullable|numeric|min:0',
            'tarif_bulanan' => 'nullable|numeric|min:0',
        ]);

        // Handle foto upload
        $fotoPath = $motor->foto;
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('motors', 'public');
        }

        // Handle document upload
        $dokumenPath = $motor->dokumen_kepemilikan;
        if ($request->hasFile('dokumen_kepemilikan')) {
            // Delete old document if exists
            if ($dokumenPath) {
                Storage::disk('public')->delete($dokumenPath);
            }
            $dokumenPath = $request->file('dokumen_kepemilikan')->store('documents', 'public');
        }

        // Update motor data
        $motor->update([
            'pemilik_id' => $request->pemilik_id,
            'merk' => $request->merk,
            'tipe_cc' => $request->tipe_cc,
            'no_plat' => $request->no_plat,
            'status' => $request->status,
            'foto' => $fotoPath,
            'dokumen_kepemilikan' => $dokumenPath,
        ]);

        // Update or create tarif rental
        if ($request->filled(['tarif_harian', 'tarif_mingguan', 'tarif_bulanan'])) {
            $motor->tarifRental()->updateOrCreate(
                ['motor_id' => $motor->id],
                [
                    'tarif_harian' => $request->tarif_harian,
                    'tarif_mingguan' => $request->tarif_mingguan,
                    'tarif_bulanan' => $request->tarif_bulanan,
                ]
            );
        }

        return redirect()->route('admin.motors.index')
                        ->with('success', 'Motor berhasil diupdate.');
    }

    /**
     * Remove the specified motor from storage.
     */
    public function destroy(Motor $motor): RedirectResponse
    {
        // Delete associated files
        if ($motor->foto) {
            Storage::disk('public')->delete($motor->foto);
        }
        if ($motor->dokumen_kepemilikan) {
            Storage::disk('public')->delete($motor->dokumen_kepemilikan);
        }

        // Delete motor (tarif will be deleted by cascade)
        $motor->delete();

        return redirect()->route('admin.motors.index')
                        ->with('success', 'Motor berhasil dihapus.');
    }

    /**
     * Display motors pending verification.
     */
    public function verification(Request $request): View
    {
        $query = Motor::with(['pemilik', 'tarifRental'])
                     ->where('status', MotorStatus::PENDING);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('merk', 'like', "%{$search}%")
                  ->orWhere('no_plat', 'like', "%{$search}%")
                  ->orWhereHas('pemilik', function($ownerQuery) use ($search) {
                      $ownerQuery->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $motors = $query->latest()->paginate(10);
        
        return view('admin.motors-verification.index', compact('motors'));
    }

    /**
     * Show motor verification details.
     */
    public function showVerification(Motor $motor): View
    {
        return view('admin.motors-verification.show', compact('motor'));
    }

    /**
     * Verify and approve a motor.
     */
    public function verify(Motor $motor): RedirectResponse
    {
        $motor->update(['status' => MotorStatus::VERIFIED]);
        
        return redirect()->back()
                        ->with('success', 'Motor berhasil diverifikasi.');
    }

    /**
     * Reject a motor verification.
     */
    public function reject(Motor $motor): RedirectResponse
    {
        $motor->update(['status' => MotorStatus::REJECTED]);
        
        return redirect()->back()
                        ->with('error', 'Motor ditolak verifikasinya.');
    }

    /**
     * Activate a verified motor.
     */
    public function activate(Motor $motor): RedirectResponse
    {
        if ($motor->status === MotorStatus::VERIFIED) {
            $motor->update(['status' => MotorStatus::AVAILABLE]);
            
            return redirect()->back()
                            ->with('success', 'Motor berhasil diaktifkan dan tersedia untuk disewa.');
        }
        
        return redirect()->back()
                        ->with('error', 'Motor harus diverifikasi terlebih dahulu sebelum diaktifkan.');
    }
}