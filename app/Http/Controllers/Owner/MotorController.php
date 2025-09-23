<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\TarifRental;
use App\Enums\MotorStatus;
use App\Enums\MotorType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MotorController extends Controller
{
    /**
     * Display a listing of the owner's motors.
     */
    public function index(): View
    {
        $motors = Motor::where('pemilik_id', Auth::id())->with('tarif')->latest()->paginate(10);

        return view('owner.motors.index', compact('motors'));
    }

    /**
     * Show the form for creating a new motor.
     */
    public function create(): View
    {
        return view('owner.motors.create');
    }

    /**
     * Store a newly created motor in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:255',
            'tipe_cc' => 'required|in:100,125,150',
            'no_plat' => 'required|string|unique:motors,no_plat|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_kepemilikan' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'harga_per_hari' => 'required|numeric|min:0',
            'harga_per_minggu' => 'required|numeric|min:0',
            'harga_per_bulan' => 'required|numeric|min:0',
        ]);

        // Handle file uploads
        $photoPath = null;
        $documentPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('motors/photos', 'public');
        }

        if ($request->hasFile('dokumen_kepemilikan')) {
            $documentPath = $request->file('dokumen_kepemilikan')->store('motors/documents', 'public');
        }

        // Create motor
        $motor = Motor::create([
            'pemilik_id' => Auth::id(),
            'merk' => $validated['merk'],
            'tipe_cc' => MotorType::from($validated['tipe_cc']),
            'no_plat' => $validated['no_plat'],
            'status' => MotorStatus::PENDING,
            'photo' => $photoPath,
            'dokumen_kepemilikan' => $documentPath,
        ]);

        // Create tarif
        $motor->tarif()->create([
            'harga_per_hari' => $validated['harga_per_hari'],
            'harga_per_minggu' => $validated['harga_per_minggu'],
            'harga_per_bulan' => $validated['harga_per_bulan'],
        ]);

        return redirect()->route('owner.motors.index')->with('success', 'Motor berhasil didaftarkan dan menunggu verifikasi admin.');
    }

    /**
     * Display the specified motor.
     */
    public function show(Motor $motor): View
    {
        // Ensure the motor belongs to the authenticated user
        if ($motor->pemilik_id !== Auth::id()) {
            abort(403);
        }

        $motor->load(['tarif', 'penyewaans.penyewa']);

        return view('owner.motors.show', compact('motor'));
    }

    /**
     * Show the form for editing the specified motor.
     */
    public function edit(Motor $motor): View
    {
        // Ensure the motor belongs to the authenticated user
        if ($motor->pemilik_id !== Auth::id()) {
            abort(403);
        }

        $motor->load('tarif');

        return view('owner.motors.edit', compact('motor'));
    }

    /**
     * Update the specified motor in storage.
     */
    public function update(Request $request, Motor $motor): RedirectResponse
    {
        // Ensure the motor belongs to the authenticated user
        if ($motor->pemilik_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'merk' => 'required|string|max:255',
            'tipe_cc' => 'required|in:matic,manual,sport',
            'no_plat' => 'required|string|max:20|unique:motors,no_plat,' . $motor->id,
            'foto_motor' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_kepemilikan' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'harga_per_hari' => 'required|numeric|min:0',
            'harga_per_minggu' => 'required|numeric|min:0',
            'harga_per_bulan' => 'required|numeric|min:0',
        ]);

        // Handle file uploads
        if ($request->hasFile('foto_motor')) {
            // Delete old photo if exists
            if ($motor->foto_motor) {
                Storage::disk('public')->delete($motor->foto_motor);
            }
            $validated['foto_motor'] = $request->file('foto_motor')->store('motors/photos', 'public');
        }

        if ($request->hasFile('dokumen_kepemilikan')) {
            // Delete old document if exists
            if ($motor->dokumen_kepemilikan) {
                Storage::disk('public')->delete($motor->dokumen_kepemilikan);
            }
            $validated['dokumen_kepemilikan'] = $request->file('dokumen_kepemilikan')->store('motors/documents', 'public');
        }

        // Update motor
        $motor->update([
            'merk' => $validated['merk'],
            'tipe_cc' => MotorType::from($validated['tipe_cc']),
            'no_plat' => $validated['no_plat'],
            'foto_motor' => $validated['foto_motor'] ?? $motor->foto_motor,
            'dokumen_kepemilikan' => $validated['dokumen_kepemilikan'] ?? $motor->dokumen_kepemilikan,
        ]);

        // Update tarif
        $motor->tarif()->updateOrCreate([], [
            'harga_per_hari' => $validated['harga_per_hari'],
            'harga_per_minggu' => $validated['harga_per_minggu'],
            'harga_per_bulan' => $validated['harga_per_bulan'],
        ]);

        return redirect()->route('owner.motors.index')->with('success', 'Motor berhasil diperbarui.');
    }

    /**
     * Remove the specified motor from storage.
     */
    public function destroy(Motor $motor): RedirectResponse
    {
        // Ensure the motor belongs to the authenticated user
        if ($motor->pemilik_id !== Auth::id()) {
            abort(403);
        }

        // Check if motor has active bookings
        if ($motor->penyewaans()->whereIn('status', ['pending', 'confirmed', 'active'])->count() > 0) {
            return redirect()->back()->with('error', 'Motor tidak dapat dihapus karena masih memiliki booking aktif.');
        }

        // Delete files
        if ($motor->foto_motor) {
            Storage::disk('public')->delete($motor->foto_motor);
        }
        if ($motor->dokumen_kepemilikan) {
            Storage::disk('public')->delete($motor->dokumen_kepemilikan);
        }

        $motor->delete();

        return redirect()->route('owner.motors.index')->with('success', 'Motor berhasil dihapus.');
    }
}