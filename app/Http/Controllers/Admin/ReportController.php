<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Motor;
use App\Models\User;
use App\Models\BagiHasil;
use App\Enums\BookingStatus;
use App\Enums\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display admin reports dashboard.
     */
    public function index(): View
    {
        $currentMonth = Carbon::create(2025, 9, 1); // September 2025
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Monthly stats
        $monthlyStats = [
            'total_bookings' => Penyewaan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'completed_bookings' => Penyewaan::where('status', BookingStatus::COMPLETED)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'total_revenue' => Penyewaan::where('status', BookingStatus::COMPLETED)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('harga'),
            'platform_commission' => BagiHasil::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('komisi_platform'),
        ];

        // User growth
        $userGrowth = [
            'total_users' => User::count(),
            'new_users_this_month' => User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'owners' => User::where('role', UserRole::PEMILIK)->count(),
            'renters' => User::where('role', UserRole::PENYEWA)->count(),
        ];

        // Motor stats
        $motorStats = [
            'total_motors' => Motor::count(),
            'active_motors' => Motor::where('status', 'available')->count(),
            'pending_verification' => Motor::where('status', 'pending')->count(),
        ];

        return view('admin.reports.index', compact('monthlyStats', 'userGrowth', 'motorStats', 'currentMonth'));
    }

    /**
     * Display revenue reports.
     */
    public function revenue(Request $request): View
    {
        $startDate = $request->get('start_date', Carbon::create(2025, 9, 1)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::create(2025, 9, 30)->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Revenue breakdown
        $revenueData = [
            'total_gross_revenue' => Penyewaan::where('status', BookingStatus::COMPLETED)
                ->whereBetween('created_at', [$start, $end])->sum('harga'),
            'platform_commission' => BagiHasil::whereBetween('created_at', [$start, $end])->sum('komisi_platform'),
            'owner_earnings' => BagiHasil::whereBetween('created_at', [$start, $end])->sum('pendapatan_owner'),
        ];

        // Top performing motors
        $topMotors = Motor::withSum(['penyewaans' => function($query) use ($start, $end) {
                $query->where('status', BookingStatus::COMPLETED)
                      ->whereBetween('created_at', [$start, $end]);
            }], 'harga')
            ->having('penyewaans_sum_harga', '>', 0)
            ->orderByDesc('penyewaans_sum_harga')
            ->take(10)
            ->get();

        // Daily revenue chart data
        $dailyRevenue = [];
        $current = $start->copy();
        while ($current <= $end) {
            $dailyRevenue[$current->format('Y-m-d')] = Penyewaan::where('status', BookingStatus::COMPLETED)
                ->whereDate('created_at', $current)->sum('harga');
            $current->addDay();
        }

        return view('admin.reports.revenue', compact('revenueData', 'topMotors', 'dailyRevenue', 'startDate', 'endDate'));
    }

    /**
     * Display analytics reports.
     */
    public function analytics(): View
    {
        $currentMonth = Carbon::create(2025, 9, 1);
        
        // Booking trends
        $bookingTrends = [
            'avg_booking_duration' => Penyewaan::where('status', BookingStatus::COMPLETED)->avg('durasi_hari'),
            'popular_booking_duration' => Penyewaan::selectRaw('durasi_hari, COUNT(*) as count')
                ->where('status', BookingStatus::COMPLETED)
                ->groupBy('durasi_hari')
                ->orderByDesc('count')
                ->first(),
            'peak_booking_day' => Penyewaan::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
                ->groupBy('day')
                ->orderByDesc('count')
                ->first(),
        ];

        // User behavior
        $userBehavior = [
            'repeat_customers' => User::whereHas('penyewaans', function($query) {
                $query->where('status', BookingStatus::COMPLETED);
            }, '>', 1)->count(),
            'avg_bookings_per_user' => User::withCount(['penyewaans' => function($query) {
                $query->where('status', BookingStatus::COMPLETED);
            }])->avg('penyewaans_count'),
        ];

        return view('admin.reports.analytics', compact('bookingTrends', 'userBehavior', 'currentMonth'));
    }
}