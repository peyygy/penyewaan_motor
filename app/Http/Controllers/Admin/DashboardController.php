<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\User;
use App\Models\BagiHasil;
use App\Enums\UserRole;
use App\Enums\MotorStatus;
use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Current month data (September 2025 for testing)
        $currentMonth = Carbon::create(2025, 9, 1); // September 2025
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Basic statistics
        $stats = [
            'total_users' => User::where('role', '!=', UserRole::ADMIN)->count(),
            'total_owners' => User::where('role', UserRole::PEMILIK)->count(),
            'total_renters' => User::where('role', UserRole::PENYEWA)->count(),
            'total_motors' => Motor::count(),
            'pending_motors' => Motor::where('status', MotorStatus::PENDING)->count(),
            'available_motors' => Motor::where('status', MotorStatus::AVAILABLE)->count(),
            'rented_motors' => Motor::where('status', MotorStatus::RENTED)->count(),
        ];

        // Booking statistics for current month
        $bookingStats = [
            'total_bookings' => Penyewaan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'active_bookings' => Penyewaan::where('status', BookingStatus::ACTIVE)->count(),
            'completed_bookings' => Penyewaan::where('status', BookingStatus::COMPLETED)
                ->whereBetween('tanggal_selesai', [$startOfMonth, $endOfMonth])
                ->count(),
            'pending_bookings' => Penyewaan::where('status', BookingStatus::PENDING)->count(),
        ];

        // Revenue statistics for current month
        $revenueStats = [
            'monthly_revenue' => BagiHasil::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('bagi_hasil_admin'),
            'total_revenue' => BagiHasil::sum('bagi_hasil_admin'),
            'owner_revenue' => BagiHasil::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('bagi_hasil_pemilik'),
        ];

        // Recent activities
        $recentMotors = Motor::with('owner')->latest()->take(5)->get();
        $recentBookings = Penyewaan::with(['penyewa', 'motor'])->latest()->take(10)->get();

        // Chart data for revenue trend (last 6 months)
        $revenueChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $revenueChartData[] = [
                'month' => $month->format('M Y'),
                'revenue' => BagiHasil::whereBetween('tanggal', [$monthStart, $monthEnd])->sum('bagi_hasil_admin'),
            ];
        }

        return view('admin.dashboard', compact(
            'stats',
            'bookingStats', 
            'revenueStats',
            'recentMotors',
            'recentBookings',
            'revenueChartData',
            'currentMonth'
        ));
    }
}