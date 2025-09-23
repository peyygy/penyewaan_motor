<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Enums\MotorStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MotorVerificationController extends Controller
{
    /**
     * Display a listing of motors for verification.
     */
    public function index(Request $request): View
    {
        $query = Motor::with(['owner', 'tarif']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('merk', 'like', "%{$search}%")
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
        if ($request->filled('type')) {
            $query->where('tipe_cc', $request->type);
        }

        $motors = $query->paginate(10);

        return view('admin.motors.index', compact('motors'));
    }

    /**
     * Display the specified motor.
     */
    public function show(Motor $motor): View
    {
        $motor->load(['owner', 'tarif', 'penyewaans.penyewa']);
        
        return view('admin.motors.show', compact('motor'));
    }

    /**
     * Verify a motor.
     */
    public function verify(Motor $motor): RedirectResponse
    {
        if ($motor->status !== MotorStatus::PENDING) {
            return redirect()->back()->with('error', 'Motor hanya dapat diverifikasi jika statusnya pending.');
        }

        $motor->update(['status' => MotorStatus::VERIFIED]);

        return redirect()->back()->with('success', 'Motor berhasil diverifikasi.');
    }

    /**
     * Activate a verified motor.
     */
    public function activate(Motor $motor): RedirectResponse
    {
        if ($motor->status !== MotorStatus::VERIFIED) {
            return redirect()->back()->with('error', 'Motor hanya dapat diaktifkan jika sudah diverifikasi.');
        }

        $motor->update(['status' => MotorStatus::AVAILABLE]);

        return redirect()->back()->with('success', 'Motor berhasil diaktifkan dan siap disewakan.');
    }

    /**
     * Reject a motor.
     */
    public function reject(Motor $motor): RedirectResponse
    {
        if ($motor->status !== MotorStatus::PENDING) {
            return redirect()->back()->with('error', 'Motor hanya dapat ditolak jika statusnya pending.');
        }

        // You might want to add a reason field later
        $motor->update(['status' => MotorStatus::PENDING]); // Keep as pending with rejection reason
        
        return redirect()->back()->with('success', 'Motor ditolak. Owner akan diberitahu untuk melengkapi dokumen.');
    }
}