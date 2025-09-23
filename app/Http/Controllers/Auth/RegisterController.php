<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the application's registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_tlpn' => $data['no_tlpn'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::from($data['role']),
        ]);

        Auth::login($user);

        // Redirect to appropriate dashboard based on role
        return match($user->role) {
            UserRole::PEMILIK => redirect()->route('owner.dashboard'),
            UserRole::PENYEWA => redirect()->route('renter.dashboard'),
            default => redirect()->route('login'), // This should not happen for registration
        };
    }
}