<?php

namespace App\Http\Controllers\Renter;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use App\Enums\MotorStatus;
use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the renter dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Current month data (September 2025 for testing)
        $currentMonth = Carbon::create(2025, 9, 1); // September 2025
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Available motors statistics
        $motorStats = [
            'total_available' => Motor::where('status', MotorStatus::AVAILABLE)->count(),
            'honda_available' => Motor::where('status', MotorStatus::AVAILABLE)->where('merk', 'like', '%Honda%')->count(),
            'yamaha_available' => Motor::where('status', MotorStatus::AVAILABLE)->where('merk', 'like', '%Yamaha%')->count(),
            'suzuki_available' => Motor::where('status', MotorStatus::AVAILABLE)->where('merk', 'like', '%Suzuki%')->count(),
        ];

        // User's booking statistics
        $bookingStats = [
            'total_bookings' => Penyewaan::where('penyewa_id', $user->id)->count(),
            'active_bookings' => Penyewaan::where('penyewa_id', $user->id)->where('status', BookingStatus::ACTIVE)->count(),
            'completed_bookings' => Penyewaan::where('penyewa_id', $user->id)->where('status', BookingStatus::COMPLETED)->count(),
            'pending_bookings' => Penyewaan::where('penyewa_id', $user->id)->where('status', BookingStatus::PENDING)->count(),
            'monthly_bookings' => Penyewaan::where('penyewa_id', $user->id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
        ];

        // Payment statistics
        $paymentStats = [
            'total_spent' => Penyewaan::where('penyewa_id', $user->id)->where('status', '!=', BookingStatus::CANCELLED)->sum('harga'),
            'monthly_spent' => Penyewaan::where('penyewa_id', $user->id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('harga'),
            'pending_payments' => Penyewaan::where('penyewa_id', $user->id)->where('status', BookingStatus::PENDING)->sum('harga'),
        ];

        // Recent bookings
        $recentBookings = Penyewaan::where('penyewa_id', $user->id)
            ->with(['motor', 'transaksis'])
            ->latest()
            ->take(5)
            ->get();

        // Recent available motors
        $featuredMotors = Motor::with(['tarifRental', 'owner'])
            ->where('status', MotorStatus::AVAILABLE)
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Booking activity chart data (last 6 months)
        $activityChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $activityChartData[] = [
                'month' => $month->format('M Y'),
                'bookings' => Penyewaan::where('penyewa_id', $user->id)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count(),
                'spent' => Penyewaan::where('penyewa_id', $user->id)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('harga'),
            ];
        }

        return view('renter.dashboard', compact(
            'motorStats',
            'bookingStats',
            'paymentStats',
            'recentBookings',
            'featuredMotors',
            'activityChartData',
            'currentMonth'
        ));
    }
}