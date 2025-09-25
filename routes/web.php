<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MotorController as AdminMotorController;
use App\Http\Controllers\Admin\PenyewaanController as AdminPenyewaanController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboard;
use App\Http\Controllers\Owner\MotorController;
use App\Http\Controllers\Owner\PenyewaanController as OwnerPenyewaanController;
use App\Http\Controllers\Owner\TransaksiController as OwnerTransaksiController;
use App\Http\Controllers\Penyewa\DashboardController as PenyewaDashboard;
use App\Http\Controllers\Penyewa\PenyewaanController as PenyewaPenyewaanController;
use App\Http\Controllers\Penyewa\TransaksiController as PenyewaTransaksiController;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing page - Show welcome page for guests, redirect authenticated users
Route::get('/', function () {
    if (Auth::check()) {
        return match(Auth::user()->role) {
            UserRole::ADMIN => redirect()->route('admin.dashboard'),
            UserRole::PEMILIK => redirect()->route('owner.dashboard'),
            UserRole::PENYEWA => redirect()->route('penyewa.dashboard'),
        };
    }
    return view('welcome');
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
Route::get('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout.get');

// Admin Routes - Full CRUD Access
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // CRUD Resources untuk Admin
    Route::resource('motors', AdminMotorController::class);
    Route::resource('penyewaans', AdminPenyewaanController::class);
    Route::resource('transaksis', AdminTransaksiController::class);
    
    // Motor Verification Routes
    Route::prefix('motors-verification')->name('motors-verification.')->group(function () {
        Route::get('/', [AdminMotorController::class, 'verification'])->name('index');
        Route::get('/{motor}', [AdminMotorController::class, 'showVerification'])->name('show');
        Route::post('/{motor}/verify', [AdminMotorController::class, 'verify'])->name('verify');
        Route::post('/{motor}/reject', [AdminMotorController::class, 'reject'])->name('reject');
        Route::post('/{motor}/activate', [AdminMotorController::class, 'activate'])->name('activate');
    });
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    
    // Laporan Admin
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/analytics', [ReportController::class, 'analytics'])->name('reports.analytics');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
});

// Owner/Pemilik Routes - Manage Own Motors
Route::middleware(['auth', 'role:pemilik'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboard::class, 'index'])->name('dashboard');
    
    // CRUD Resources untuk Owner (hanya motor milik sendiri)
    Route::resource('motors', MotorController::class);
    Route::resource('penyewaans', OwnerPenyewaanController::class)->only(['index', 'show', 'edit', 'update']);
    Route::resource('transaksis', OwnerTransaksiController::class)->only(['index', 'show']);
    
    // Laporan Owner
    Route::get('/reports', [MotorController::class, 'reports'])->name('reports.index');
});

// Penyewa Routes - Book Motors & Manage Bookings
Route::middleware(['auth', 'role:penyewa'])->prefix('penyewa')->name('penyewa.')->group(function () {
    Route::get('/dashboard', [PenyewaDashboard::class, 'index'])->name('dashboard');
    
    // Browse available motors
    Route::get('/motors', [PenyewaPenyewaanController::class, 'browseMotors'])->name('motors.index');
    Route::get('/motors/{motor}', [PenyewaPenyewaanController::class, 'showMotor'])->name('motors.show');
    
    // CRUD Penyewaan untuk Penyewa
    Route::resource('penyewaans', PenyewaPenyewaanController::class);
    Route::resource('transaksis', PenyewaTransaksiController::class)->only(['index', 'show', 'store']);
    
    // Laporan Penyewa
    Route::get('/history', [PenyewaPenyewaanController::class, 'history'])->name('history');
});
