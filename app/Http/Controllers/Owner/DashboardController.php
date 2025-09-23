<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\BagiHasil;
use App\Enums\MotorStatus;
use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the owner dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Current month data (September 2025 for testing)
        $currentMonth = Carbon::create(2025, 9, 1); // September 2025
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Owner's motor statistics
        $motorStats = [
            'total_motors' => Motor::where('pemilik_id', $user->id)->count(),
            'pending_motors' => Motor::where('pemilik_id', $user->id)->where('status', MotorStatus::PENDING)->count(),
            'verified_motors' => Motor::where('pemilik_id', $user->id)->where('status', MotorStatus::VERIFIED)->count(),
            'available_motors' => Motor::where('pemilik_id', $user->id)->where('status', MotorStatus::AVAILABLE)->count(),
            'rented_motors' => Motor::where('pemilik_id', $user->id)->where('status', MotorStatus::RENTED)->count(),
        ];

        // Booking statistics for owner's motors
        $motorIds = Motor::where('pemilik_id', $user->id)->pluck('id');
        $bookingStats = [
            'total_bookings' => Penyewaan::whereIn('motor_id', $motorIds)->count(),
            'active_bookings' => Penyewaan::whereIn('motor_id', $motorIds)
                ->where('status', BookingStatus::ACTIVE)->count(),
            'completed_bookings' => Penyewaan::whereIn('motor_id', $motorIds)
                ->where('status', BookingStatus::COMPLETED)
                ->whereBetween('tanggal_selesai', [$startOfMonth, $endOfMonth])
                ->count(),
            'monthly_bookings' => Penyewaan::whereIn('motor_id', $motorIds)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
        ];

        // Revenue statistics
        $revenueStats = [
            'monthly_earnings' => BagiHasil::whereHas('penyewaan', function($query) use ($motorIds) {
                    $query->whereIn('motor_id', $motorIds);
                })
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->sum('bagi_hasil_pemilik'),
            'total_earnings' => BagiHasil::whereHas('penyewaan', function($query) use ($motorIds) {
                    $query->whereIn('motor_id', $motorIds);
                })
                ->sum('bagi_hasil_pemilik'),
            'pending_settlements' => BagiHasil::whereHas('penyewaan', function($query) use ($motorIds) {
                    $query->whereIn('motor_id', $motorIds);
                })
                ->whereNull('settled_at')
                ->sum('bagi_hasil_pemilik'),
        ];

        // Recent activities
        $recentBookings = Penyewaan::with(['penyewa', 'motor'])
            ->whereIn('motor_id', $motorIds)
            ->latest()
            ->take(10)
            ->get();

        $recentMotors = Motor::where('pemilik_id', $user->id)->with('tarifRental')->latest()->take(5)->get();

        // Earnings chart data (last 6 months)
        $earningsChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $earningsChartData[] = [
                'month' => $month->format('M Y'),
                'earnings' => BagiHasil::whereHas('penyewaan', function($query) use ($motorIds) {
                        $query->whereIn('motor_id', $motorIds);
                    })
                    ->whereBetween('tanggal', [$monthStart, $monthEnd])
                    ->sum('bagi_hasil_pemilik'),
            ];
        }

        return view('owner.dashboard', compact(
            'motorStats',
            'bookingStats',
            'revenueStats',
            'recentBookings',
            'recentMotors',
            'earningsChartData',
            'currentMonth'
        ));
    }
}