<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MotorVerificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TarifController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PenyewaanController as AdminPenyewaanController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboard;
use App\Http\Controllers\Owner\MotorController;
use App\Http\Controllers\Admin\MotorController as AdminMotorController;
use App\Http\Controllers\Owner\RevenueController;
use App\Http\Controllers\Renter\DashboardController as RenterDashboard;
use App\Http\Controllers\Renter\MotorSearchController;
use App\Http\Controllers\Renter\BookingController;
use App\Http\Controllers\Renter\PaymentController;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to appropriate dashboard based on role
Route::get('/', function () {
    if (Auth::check()) {
        return match(Auth::user()->role) {
            UserRole::ADMIN => redirect()->route('admin.dashboard'),
            UserRole::PEMILIK => redirect()->route('owner.dashboard'),
            UserRole::PENYEWA => redirect()->route('renter.dashboard'),
        };
    }
    return redirect()->route('login');
});

// Guest routes (login & register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout (authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Motor verification and management
    Route::get('/motors-verification', [MotorVerificationController::class, 'index'])->name('motors-verification.index');
    Route::get('/motors-verification/{motor}', [MotorVerificationController::class, 'show'])->name('motors-verification.show');
    Route::patch('/motors-verification/{motor}/verify', [MotorVerificationController::class, 'verify'])->name('motors-verification.verify');
    Route::patch('/motors-verification/{motor}/activate', [MotorVerificationController::class, 'activate'])->name('motors-verification.activate');
    Route::patch('/motors-verification/{motor}/reject', [MotorVerificationController::class, 'reject'])->name('motors-verification.reject');
    
    // Full motor CRUD
    Route::resource('motors', AdminMotorController::class);
    Route::patch('/motors/{motor}/suspend', [AdminMotorController::class, 'suspend'])->name('motors.suspend');
    Route::patch('/motors/{motor}/reactivate', [AdminMotorController::class, 'reactivate'])->name('motors.reactivate');
    
    // User management
    Route::resource('users', UserController::class)->only(['index', 'show', 'destroy']);
    
    // Rental rates management
    Route::resource('tarif', TarifController::class);
    
    // Penyewaan management
    Route::resource('penyewaans', AdminPenyewaanController::class);
    Route::patch('/penyewaans/{penyewaan}/confirm', [AdminPenyewaanController::class, 'confirm'])->name('penyewaans.confirm');
    Route::patch('/penyewaans/{penyewaan}/activate', [AdminPenyewaanController::class, 'activate'])->name('penyewaans.activate');
    Route::patch('/penyewaans/{penyewaan}/complete', [AdminPenyewaanController::class, 'complete'])->name('penyewaans.complete');
    
    // Reports and analytics
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/analytics', [ReportController::class, 'analytics'])->name('reports.analytics');
});

// Owner Routes
Route::middleware(['auth', 'role:pemilik'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboard::class, 'index'])->name('dashboard');
    
    // Motor management
    Route::resource('motors', MotorController::class);
    
    // Revenue reports
    Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/detail/{motor}', [RevenueController::class, 'detail'])->name('revenue.detail');
});

// Renter Routes
Route::middleware(['auth', 'role:penyewa'])->prefix('renter')->name('renter.')->group(function () {
    Route::get('/dashboard', [RenterDashboard::class, 'index'])->name('dashboard');
    
    // Motor search and browse
    Route::get('/motors', [MotorSearchController::class, 'index'])->name('motors.index');
    Route::get('/motors/{motor}', [MotorSearchController::class, 'show'])->name('motors.show');
    
    // Booking management
    Route::resource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Payment management
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::get('/payments/{transaksi}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');
});
