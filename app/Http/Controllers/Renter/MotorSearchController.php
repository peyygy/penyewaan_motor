<?php

namespace App\Http\Controllers\Renter;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Enums\MotorStatus;
use App\Enums\MotorType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MotorSearchController extends Controller
{
    /**
     * Display available motors for rent.
     */
    public function index(Request $request): View
    {
        $query = Motor::with(['owner', 'tarif'])
            ->where('status', MotorStatus::AVAILABLE);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('merk', 'like', "%{$search}%")
                  ->orWhere('no_plat', 'like', "%{$search}%")
                  ->orWhereHas('owner', function($ownerQuery) use ($search) {
                      $ownerQuery->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by motor type
        if ($request->filled('type')) {
            $query->where('tipe_cc', $request->type);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->whereHas('tarif', function($tarifQuery) use ($request) {
                $tarifQuery->where('harga_per_hari', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('tarif', function($tarifQuery) use ($request) {
                $tarifQuery->where('harga_per_hari', '<=', $request->max_price);
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->join('tarif_rentals', 'motors.id', '=', 'tarif_rentals.motor_id')
                      ->orderBy('tarif_rentals.harga_per_hari', 'asc');
                break;
            case 'price_high':
                $query->join('tarif_rentals', 'motors.id', '=', 'tarif_rentals.motor_id')
                      ->orderBy('tarif_rentals.harga_per_hari', 'desc');
                break;
            case 'popular':
                $query->withCount('penyewaans')->orderByDesc('penyewaans_count');
                break;
            default:
                $query->latest();
        }

        $motors = $query->paginate(12);

        // Get filter options
        $motorTypes = MotorType::cases();
        $priceRanges = [
            ['min' => 0, 'max' => 50000, 'label' => 'Under 50k'],
            ['min' => 50000, 'max' => 100000, 'label' => '50k - 100k'],
            ['min' => 100000, 'max' => 200000, 'label' => '100k - 200k'],
            ['min' => 200000, 'max' => null, 'label' => 'Above 200k'],
        ];

        return view('renter.motors.index', compact('motors', 'motorTypes', 'priceRanges'));
    }

    /**
     * Display the specified motor details.
     */
    public function show(Motor $motor): View
    {
        // Only show available motors
        if ($motor->status !== MotorStatus::AVAILABLE) {
            abort(404, 'Motor tidak tersedia untuk disewa.');
        }

        $motor->load(['owner', 'tarif', 'penyewaans' => function($query) {
            $query->where('status', 'completed')->latest()->take(5);
        }]);

        // Calculate average rating if you have a review system
        // For now, we'll use a dummy calculation
        $averageRating = 4.5; // You can implement this later with reviews
        $totalReviews = $motor->penyewaans->count();

        // Get similar motors
        $similarMotors = Motor::with(['owner', 'tarif'])
            ->where('status', MotorStatus::AVAILABLE)
            ->where('tipe_cc', $motor->tipe_cc)
            ->where('id', '!=', $motor->id)
            ->take(4)
            ->get();

        return view('renter.motors.show', compact(
            'motor', 
            'averageRating', 
            'totalReviews', 
            'similarMotors'
        ));
    }
}