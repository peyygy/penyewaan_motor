<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::with(['motors', 'penyewaans'])->where('role', '!=', UserRole::ADMIN);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_tlpn', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        // Load relationships based on user role
        if ($user->isOwner()) {
            $user->load(['motors.tarif', 'motors.penyewaans']);
        } elseif ($user->isRenter()) {
            $user->load(['penyewaans.motor.owner']);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting admin users
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Admin user tidak dapat dihapus.');
        }

        // Check if user has active bookings or motors
        if ($user->isOwner() && $user->motors()->count() > 0) {
            return redirect()->back()->with('error', 'User tidak dapat dihapus karena masih memiliki motor terdaftar.');
        }

        if ($user->isRenter() && $user->penyewaans()->whereIn('status', ['pending', 'confirmed', 'active'])->count() > 0) {
            return redirect()->back()->with('error', 'User tidak dapat dihapus karena masih memiliki booking aktif.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}