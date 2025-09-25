<?php

use App\Http\Controllers\API\Admin\UserController as AdminUserController;
use App\Http\Controllers\API\Admin\MotorController as AdminMotorController;
use App\Http\Controllers\API\Admin\PenyewaanController as AdminPenyewaanController;
use App\Http\Controllers\API\Owner\MotorController as OwnerMotorController;
use App\Http\Controllers\API\Owner\PenyewaanController as OwnerPenyewaanController;
use App\Http\Controllers\API\Renter\PenyewaanController as RenterPenyewaanController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // User Management
        Route::apiResource('users', AdminUserController::class);
        
        // Motor Management (All Motors)
        Route::apiResource('motors', AdminMotorController::class);
        Route::patch('motors/{motor}/verify', [AdminMotorController::class, 'verify']);
        Route::patch('motors/{motor}/reject', [AdminMotorController::class, 'reject']);
        
        // Penyewaan Management (All Rentals)
        Route::apiResource('penyewaans', AdminPenyewaanController::class);
        Route::patch('penyewaans/{penyewaan}/approve', [AdminPenyewaanController::class, 'approve']);
        Route::patch('penyewaans/{penyewaan}/reject', [AdminPenyewaanController::class, 'reject']);
        
        // Dashboard Stats
        Route::get('dashboard/stats', [AdminUserController::class, 'dashboardStats']);
    });

    // Owner Routes
    Route::middleware(['role:pemilik'])->prefix('owner')->group(function () {
        // Motor Management (Owner's Motors Only)
        Route::apiResource('motors', OwnerMotorController::class);
        Route::post('motors/{motor}/upload-photo', [OwnerMotorController::class, 'uploadPhoto']);
        Route::post('motors/{motor}/upload-document', [OwnerMotorController::class, 'uploadDocument']);
        Route::patch('motors/{motor}/status', [OwnerMotorController::class, 'updateStatus']);
        
        // Penyewaan Management (Owner's Motor Rentals)
        Route::get('penyewaans', [OwnerPenyewaanController::class, 'index']);
        Route::get('penyewaans/{penyewaan}', [OwnerPenyewaanController::class, 'show']);
        Route::patch('penyewaans/{penyewaan}/approve', [OwnerPenyewaanController::class, 'approve']);
        Route::patch('penyewaans/{penyewaan}/reject', [OwnerPenyewaanController::class, 'reject']);
        Route::patch('penyewaans/{penyewaan}/complete', [OwnerPenyewaanController::class, 'complete']);
        
        // Dashboard Stats
        Route::get('dashboard/stats', [OwnerMotorController::class, 'dashboardStats']);
    });

    // Renter Routes
    Route::middleware(['role:penyewa'])->prefix('renter')->group(function () {
        // Penyewaan Management (Renter's Bookings Only)
        Route::apiResource('penyewaans', RenterPenyewaanController::class);
        Route::patch('penyewaans/{penyewaan}/cancel', [RenterPenyewaanController::class, 'cancel']);
        
        // Available Motors
        Route::get('motors', [RenterPenyewaanController::class, 'availableMotors']);
        Route::get('motors/{motor}', [RenterPenyewaanController::class, 'showMotor']);
        
        // Dashboard Stats
        Route::get('dashboard/stats', [RenterPenyewaanController::class, 'dashboardStats']);
    });
});