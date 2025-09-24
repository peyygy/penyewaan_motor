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
        $query = Motor::with(['owner']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('merk', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('no_plat', 'like', "%{$search}%")
                  ->orWhereHas('owner', function($ownerQuery) use ($search) {
                      $ownerQuery->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        } elseif ($request->filled('tipe_cc')) {
            $query->where('tipe_cc', $request->tipe_cc);
        }

        // Filter by owner
        if ($request->filled('owner_id')) {
            $query->where('pemilik_id', $request->owner_id);
        }

        $motors = $query->latest()->paginate(15);

        // Get all owners for filter dropdown
        $owners = User::where('role', UserRole::PEMILIK)->pluck('nama', 'id');

        return view('admin.motors.index', compact('motors', 'owners'));
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
    public function store(StoreMotorRequest $request): RedirectResponse
    {
        $motor = Motor::create($request->validated());

        // Create default tarif if provided
        if ($request->filled('harga_per_hari')) {
            TarifRental::create([
                'motor_id' => $motor->id,
                'harga_per_hari' => $request->harga_per_hari,
                'harga_per_minggu' => $request->harga_per_hari * 6, // 6 days discount
                'harga_per_bulan' => $request->harga_per_hari * 25, // 25 days discount
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
        $motor->load(['owner', 'penyewaans.penyewa', 'penyewaans.transaksi']);
        
        return view('admin.motors.show', compact('motor'));
    }

    /**
     * Show the form for editing the specified motor.
     */
    public function edit(Motor $motor): View
    {
        $owners = User::where('role', UserRole::PEMILIK)->get();
        
        return view('admin.motors.edit', compact('motor', 'owners'));
    }

    /**
     * Update the specified motor in storage.
     */
    public function update(StoreMotorRequest $request, Motor $motor): RedirectResponse
    {
        // Get validated data but map to correct field names
        $validatedData = $request->validated();
        
        // Map form fields to database fields if needed
        $motorData = [];
        if (isset($validatedData['brand'])) {
            $motorData['merk'] = $validatedData['brand'];
        }
        if (isset($validatedData['model'])) {
            $motorData['model'] = $validatedData['model'];
        }
        if (isset($validatedData['license_plate'])) {
            $motorData['no_plat'] = $validatedData['license_plate'];
        }
        if (isset($validatedData['type'])) {
            $motorData['tipe'] = $validatedData['type'];
        }
        if (isset($validatedData['year'])) {
            $motorData['year'] = $validatedData['year'];
        }
        if (isset($validatedData['owner_id'])) {
            $motorData['owner_id'] = $validatedData['owner_id'];
        }
        if (isset($validatedData['status'])) {
            $motorData['status'] = $validatedData['status'];
        }
        if (isset($validatedData['description'])) {
            $motorData['deskripsi'] = $validatedData['description'];
        }
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($motor->foto && Storage::disk('public')->exists($motor->foto)) {
                Storage::disk('public')->delete($motor->foto);
            }
            
            $photoPath = $request->file('photo')->store('motors', 'public');
            $motorData['foto'] = $photoPath;
        }
        
        $motor->update($motorData);

        // Update tarif if provided
        if ($request->filled('harga_per_hari')) {
            TarifRental::updateOrCreate(
                ['motor_id' => $motor->id],
                [
                    'harga_per_hari' => $request->harga_per_hari,
                    'harga_per_minggu' => $request->harga_per_minggu ?: $request->harga_per_hari * 6,
                    'harga_per_bulan' => $request->harga_per_bulan ?: $request->harga_per_hari * 25,
                    'deposit' => $request->deposit ?: 0,
                ]
            );
        }

        return redirect()->route('admin.motors.show', $motor)
                        ->with('success', 'Motor berhasil diperbarui.');
    }

    /**
     * Remove the specified motor from storage.
     */
    public function destroy(Motor $motor): RedirectResponse
    {
        // Check if motor has active bookings
        if ($motor->penyewaans()->whereIn('status', ['pending', 'confirmed', 'active'])->count() > 0) {
            return redirect()->back()
                           ->with('error', 'Motor tidak dapat dihapus karena masih ada booking aktif.');
        }

        $motor->delete();

        return redirect()->route('admin.motors.index')
                        ->with('success', 'Motor berhasil dihapus.');
    }

    /**
     * Verify a motor.
     */
    public function verify(Motor $motor): RedirectResponse
    {
        if ($motor->status !== MotorStatus::PENDING) {
            return redirect()->back()->with('error', 'Motor hanya dapat diverifikasi jika statusnya pending.');
        }

        $motor->update(['status' => MotorStatus::AVAILABLE]);

        return redirect()->back()->with('success', 'Motor berhasil diverifikasi dan tersedia untuk disewa.');
    }

    /**
     * Reject a motor.
     */
    public function reject(Motor $motor): RedirectResponse
    {
        $motor->update(['status' => MotorStatus::PENDING]); // Keep as pending for re-review
        
        return redirect()->back()->with('success', 'Motor ditolak. Owner diminta untuk melengkapi dokumen.');
    }

    /**
     * Suspend a motor.
     */
    public function suspend(Motor $motor): RedirectResponse
    {
        if ($motor->status === MotorStatus::RENTED) {
            return redirect()->back()->with('error', 'Motor yang sedang disewa tidak dapat di-maintenance.');
        }

        $motor->update(['status' => MotorStatus::MAINTENANCE]);
        
        return redirect()->back()->with('success', 'Motor berhasil diset ke status maintenance.');
    }

    /**
     * Reactivate a motor.
     */
    public function reactivate(Motor $motor): RedirectResponse
    {
        if ($motor->status === MotorStatus::MAINTENANCE || $motor->status === MotorStatus::PENDING) {
            $motor->update(['status' => MotorStatus::AVAILABLE]);
            return redirect()->back()->with('success', 'Motor berhasil diaktifkan kembali.');
        }

        return redirect()->back()->with('error', 'Motor tidak dapat diaktifkan dari status saat ini.');
    }
}