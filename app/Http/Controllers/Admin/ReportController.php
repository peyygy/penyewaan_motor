<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Motor;
use App\Models\User;
use App\Models\BagiHasil;
use App\Models\Transaksi;
use App\Enums\BookingStatus;
use App\Enums\UserRole;
use App\Enums\MotorStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

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
            'platform_commission' => BagiHasil::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('bagi_hasil_admin'),
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
            'active_motors' => Motor::where('status', MotorStatus::AVAILABLE)->count(),
            'pending_verification' => Motor::where('status', MotorStatus::PENDING)->count(),
            'rented_motors' => Motor::where('status', MotorStatus::RENTED)->count(),
        ];

        // Recent activities
        $recentBookings = Penyewaan::with(['motor', 'penyewa'])
            ->latest()
            ->take(5)
            ->get();

        // Top performing motors
        $topMotors = Motor::withCount(['penyewaans as completed_bookings' => function ($query) {
                $query->where('status', BookingStatus::COMPLETED);
            }])
            ->withSum(['penyewaans as total_revenue' => function ($query) {
                $query->where('status', BookingStatus::COMPLETED);
            }], 'harga')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'monthlyStats', 
            'userGrowth', 
            'motorStats', 
            'recentBookings', 
            'topMotors'
        ));
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
        $revenue = [
            'total_revenue' => Penyewaan::where('status', BookingStatus::COMPLETED)
                ->whereBetween('created_at', [$start, $end])
                ->sum('harga'),
            'total_bookings' => Penyewaan::where('status', BookingStatus::COMPLETED)
                ->whereBetween('created_at', [$start, $end])
                ->count(),
            'average_booking_value' => Penyewaan::where('status', BookingStatus::COMPLETED)
                ->whereBetween('created_at', [$start, $end])
                ->avg('harga'),
            'platform_commission' => BagiHasil::whereBetween('created_at', [$start, $end])
                ->sum('bagi_hasil_admin'),
            'owner_payouts' => BagiHasil::whereBetween('created_at', [$start, $end])
                ->sum('bagi_hasil_pemilik'),
        ];

        // Revenue by motor type
        $revenueByType = Motor::join('penyewaans', 'motors.id', '=', 'penyewaans.motor_id')
            ->where('penyewaans.status', BookingStatus::COMPLETED)
            ->whereBetween('penyewaans.created_at', [$start, $end])
            ->selectRaw('motors.tipe_cc, COUNT(*) as bookings, SUM(penyewaans.harga) as revenue')
            ->groupBy('motors.tipe_cc')
            ->get();

        // Top performing owners
        $topOwners = User::where('role', UserRole::PEMILIK)
            ->whereHas('motors.penyewaans', function ($query) use ($start, $end) {
                $query->where('status', BookingStatus::COMPLETED)
                      ->whereBetween('created_at', [$start, $end]);
            })
            ->withSum(['motors.penyewaans as total_revenue' => function ($query) use ($start, $end) {
                $query->where('status', BookingStatus::COMPLETED)
                      ->whereBetween('created_at', [$start, $end]);
            }], 'harga')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();

        return view('admin.reports.revenue', compact('revenue', 'revenueByType', 'topOwners', 'startDate', 'endDate'));
    }

    /**
     * Display analytics reports with charts.
     */
    public function analytics(): View
    {
        // Monthly revenue trend (6 months)
        $revenueChart = $this->getRevenueChartData();
        
        // Booking status distribution
        $bookingStatusChart = [
            'pending' => Penyewaan::where('status', BookingStatus::PENDING)->count(),
            'confirmed' => Penyewaan::where('status', BookingStatus::CONFIRMED)->count(), 
            'active' => Penyewaan::where('status', BookingStatus::ACTIVE)->count(),
            'completed' => Penyewaan::where('status', BookingStatus::COMPLETED)->count(),
            'cancelled' => Penyewaan::where('status', BookingStatus::CANCELLED)->count(),
        ];

        // Motor type popularity
        $motorTypeChart = Motor::join('penyewaans', 'motors.id', '=', 'penyewaans.motor_id')
            ->where('penyewaans.status', BookingStatus::COMPLETED)
            ->selectRaw('motors.tipe_cc, COUNT(*) as total_bookings, SUM(penyewaans.harga) as total_revenue')
            ->groupBy('motors.tipe_cc')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->tipe_cc . 'cc',
                    'bookings' => $item->total_bookings,
                    'revenue' => $item->total_revenue
                ];
            });

        // Daily booking trend (last 30 days)
        $dailyBookingsChart = $this->getDailyBookingsChart();

        // Performance metrics
        $metrics = [
            'conversion_rate' => $this->calculateConversionRate(),
            'avg_booking_value' => Penyewaan::where('status', BookingStatus::COMPLETED)->avg('harga'),
            'top_performing_owners' => $this->getTopPerformingOwners(),
            'motor_utilization' => $this->getMotorUtilization(),
        ];

        return view('admin.reports.analytics', compact(
            'revenueChart', 
            'bookingStatusChart', 
            'motorTypeChart', 
            'dailyBookingsChart', 
            'metrics'
        ));
    }

    /**
     * Get revenue chart data (6 months)
     */
    private function getRevenueChartData(): array
    {
        $months = collect();
        $startDate = Carbon::create(2025, 4, 1); // 6 months back from September

        for ($i = 0; $i < 6; $i++) {
            $monthStart = $startDate->copy()->addMonths($i)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            
            $revenue = Penyewaan::where('status', BookingStatus::COMPLETED)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('harga');
                
            $months->push([
                'month' => $monthStart->format('M Y'),
                'revenue' => $revenue
            ]);
        }

        return $months->toArray();
    }

    /**
     * Get daily bookings chart (last 30 days)
     */
    private function getDailyBookingsChart(): array
    {
        $endDate = Carbon::create(2025, 9, 25); // Current date
        $startDate = $endDate->copy()->subDays(29);
        
        $dailyBookings = collect();
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $count = Penyewaan::whereDate('created_at', $date->format('Y-m-d'))->count();
            
            $dailyBookings->push([
                'date' => $date->format('M d'),
                'bookings' => $count
            ]);
        }

        return $dailyBookings->toArray();
    }

    /**
     * Calculate conversion rate (bookings completed vs created)
     */
    private function calculateConversionRate(): float
    {
        $totalBookings = Penyewaan::count();
        $completedBookings = Penyewaan::where('status', BookingStatus::COMPLETED)->count();
        
        if ($totalBookings === 0) return 0;
        
        return round(($completedBookings / $totalBookings) * 100, 2);
    }

    /**
     * Get top performing owners
     */
    private function getTopPerformingOwners(): array
    {
        return User::where('role', UserRole::PEMILIK)
            ->withCount(['motors as completed_bookings' => function ($query) {
                $query->join('penyewaans', 'motors.id', '=', 'penyewaans.motor_id')
                      ->where('penyewaans.status', BookingStatus::COMPLETED);
            }])
            ->withSum(['motors as total_revenue' => function ($query) {
                $query->join('penyewaans', 'motors.id', '=', 'penyewaans.motor_id')
                      ->where('penyewaans.status', BookingStatus::COMPLETED);
            }], 'penyewaans.harga')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get()
            ->toArray();
    }

    /**
     * Get motor utilization rate
     */
    private function getMotorUtilization(): array
    {
        $totalMotors = Motor::where('status', MotorStatus::AVAILABLE)->count();
        $rentedMotors = Motor::where('status', MotorStatus::RENTED)->count();
        
        $utilizationRate = $totalMotors > 0 ? round(($rentedMotors / $totalMotors) * 100, 2) : 0;
        
        return [
            'total_motors' => $totalMotors,
            'rented_motors' => $rentedMotors,
            'utilization_rate' => $utilizationRate
        ];
    }

    /**
     * Get chart data via AJAX
     */
    public function getChartData(Request $request): JsonResponse
    {
        $type = $request->get('type');
        
        switch ($type) {
            case 'revenue':
                return response()->json($this->getRevenueChartData());
            case 'daily_bookings':
                return response()->json($this->getDailyBookingsChart());
            case 'motor_types':
                return response()->json($this->getMotorTypeChart());
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    private function getMotorTypeChart(): array
    {
        return Motor::join('penyewaans', 'motors.id', '=', 'penyewaans.motor_id')
            ->where('penyewaans.status', BookingStatus::COMPLETED)
            ->selectRaw('motors.tipe_cc, COUNT(*) as total_bookings')
            ->groupBy('motors.tipe_cc')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->tipe_cc . 'cc',
                    'bookings' => $item->total_bookings
                ];
            })
            ->toArray();
    }

    /**
     * Export reports to PDF.
     */
    public function exportPdf(Request $request)
    {
        // Implementation for PDF export
        return response()->json([
            'message' => 'PDF export feature coming soon',
            'period' => $request->get('period', 30)
        ]);
    }

    /**
     * Export reports to Excel.
     */
    public function exportExcel(Request $request)
    {
        // Implementation for Excel export
        return response()->json([
            'message' => 'Excel export feature coming soon',
            'data' => 'This will contain the exported data'
        ]);
    }
}