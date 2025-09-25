<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TarifRental;
use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TarifController extends Controller
{
    /**
     * Display a listing of tarifs.
     */
    public function index(Request $request): View
    {
        $query = TarifRental::with('motor.owner');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('motor', function($motorQuery) use ($search) {
                $motorQuery->where('merk', 'like', "%{$search}%")
                           ->orWhere('no_plat', 'like', "%{$search}%");
            });
        }

        $tarifs = $query->paginate(15);

        return view('admin.tarif.index', compact('tarifs'));
    }

    /**
     * Show the form for creating a new tarif.
     */
    public function create(): View
    {
        $motors = Motor::whereDoesntHave('tarif')->get();
        return view('admin.tarif.create', compact('motors'));
    }

    /**
     * Store a newly created tarif in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'motor_id' => 'required|exists:motors,id|unique:tarif_rentals,motor_id',
            'harga_per_hari' => 'required|numeric|min:0',
            'harga_per_minggu' => 'required|numeric|min:0',
            'harga_per_bulan' => 'required|numeric|min:0',
        ]);

        TarifRental::create($validated);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil ditambahkan.');
    }

    /**
     * Display the specified tarif.
     */
    public function show(TarifRental $tarif): View
    {
        $tarif->load('motor.owner');
        return view('admin.tarif.show', compact('tarif'));
    }

    /**
     * Show the form for editing the specified tarif.
     */
    public function edit(TarifRental $tarif): View
    {
        $tarif->load('motor');
        return view('admin.tarif.edit', compact('tarif'));
    }

    /**
     * Update the specified tarif in storage.
     */
    public function update(Request $request, TarifRental $tarif): RedirectResponse
    {
        $validated = $request->validate([
            'harga_per_hari' => 'required|numeric|min:0',
            'harga_per_minggu' => 'required|numeric|min:0',
            'harga_per_bulan' => 'required|numeric|min:0',
        ]);

        $tarif->update($validated);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    /**
     * Remove the specified tarif from storage.
     */
    public function destroy(TarifRental $tarif): RedirectResponse
    {
        $tarif->delete();

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil dihapus.');
    }
}