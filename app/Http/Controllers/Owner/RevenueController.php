<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\BagiHasil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RevenueController extends Controller
{
    /**
     * Display revenue overview for owner.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $currentMonth = Carbon::create(2025, 9, 1); // September 2025
        
        // Date range for filtering
        $startDate = $request->get('start_date', $currentMonth->copy()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', $currentMonth->copy()->endOfMonth()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get owner's motor IDs
        $motorIds = Motor::where('pemilik_id', $user->id)->pluck('id');

        // Revenue statistics
        $revenueStats = [
            'total_gross_revenue' => Penyewaan::whereIn('motor_id', $motorIds)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->sum('harga'),
            
            'platform_commission' => BagiHasil::whereHas('penyewaan', function($query) use ($motorIds) {
                $query->whereIn('motor_id', $motorIds);
            })
            ->whereBetween('created_at', [$start, $end])
            ->sum('komisi_platform'),
            
            'net_revenue' => BagiHasil::whereHas('penyewaan', function($query) use ($motorIds) {
                $query->whereIn('motor_id', $motorIds);
            })
            ->whereBetween('created_at', [$start, $end])
            ->sum('pendapatan_owner'),
        ];

        // Motor performance
        $motorPerformance = Motor::where('pemilik_id', $user->id)
            ->withSum(['penyewaans' => function($query) use ($start, $end) {
                $query->where('status', 'completed')
                      ->whereBetween('created_at', [$start, $end]);
            }], 'harga')
            ->withCount(['penyewaans' => function($query) use ($start, $end) {
                $query->where('status', 'completed')
                      ->whereBetween('created_at', [$start, $end]);
            }])
            ->orderByDesc('penyewaans_sum_harga')
            ->get();

        // Daily revenue for chart
        $dailyRevenue = [];
        $current = $start->copy();
        while ($current <= $end) {
            $dailyRevenue[$current->format('Y-m-d')] = Penyewaan::whereIn('motor_id', $motorIds)
                ->where('status', 'completed')
                ->whereDate('created_at', $current)
                ->sum('harga');
            $current->addDay();
        }

        return view('owner.revenue.index', compact(
            'revenueStats', 
            'motorPerformance', 
            'dailyRevenue', 
            'startDate', 
            'endDate'
        ));
    }

    /**
     * Display detailed revenue for a specific motor.
     */
    public function detail(Motor $motor): View
    {
        // Ensure the motor belongs to the authenticated user
        if ($motor->pemilik_id !== Auth::id()) {
            abort(403);
        }

        $currentMonth = Carbon::create(2025, 9, 1);
        
        // Motor revenue stats
        $motorStats = [
            'total_bookings' => $motor->penyewaans()->count(),
            'completed_bookings' => $motor->penyewaans()->where('status', 'completed')->count(),
            'total_revenue' => $motor->penyewaans()->where('status', 'completed')->sum('harga'),
            'average_booking_value' => $motor->penyewaans()->where('status', 'completed')->avg('harga'),
        ];

        // Monthly breakdown for the last 6 months
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i);
            $monthlyRevenue[$month->format('M Y')] = $motor->penyewaans()
                ->where('status', 'completed')
                ->whereBetween('created_at', [
                    $month->copy()->startOfMonth(),
                    $month->copy()->endOfMonth()
                ])
                ->sum('harga');
        }

        // Recent bookings
        $recentBookings = $motor->penyewaans()
            ->with('penyewa')
            ->latest()
            ->take(10)
            ->get();

        return view('owner.revenue.detail', compact(
            'motor', 
            'motorStats', 
            'monthlyRevenue', 
            'recentBookings'
        ));
    }
}