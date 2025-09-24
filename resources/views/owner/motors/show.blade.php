@extends('layouts.owner-dashboard')

@section('title', 'Detail Motor')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Motor</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap motor {{ $motor->merk }} {{ $motor->model }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('owner.motors.edit', $motor->id) }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('owner.motors.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Motor Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Motor</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Merk</label>
                    <p class="mt-1 text-gray-900">{{ $motor->merk }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Model</label>
                    <p class="mt-1 text-gray-900">{{ $motor->model }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun</label>
                    <p class="mt-1 text-gray-900">{{ $motor->tahun }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                    <p class="mt-1 text-gray-900">{{ $motor->tipe->label() }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Plat Nomor</label>
                    <p class="mt-1 text-gray-900">{{ $motor->plat_nomor }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Warna</label>
                    <p class="mt-1 text-gray-900">{{ $motor->warna }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        {{ $motor->status->color() === 'green' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $motor->status->color() === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $motor->status->color() === 'blue' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $motor->status->color() === 'red' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $motor->status->color() === 'gray' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ $motor->status->label() }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <p class="mt-1 text-gray-900">{{ $motor->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                </div>
            </div>
        </div>

        <!-- Rental Rates -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Tarif Rental</h2>
            
            @if($motor->tarifRental)
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tarif Harian</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tarif Mingguan</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tarif Bulanan</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">Tarif rental belum diatur</p>
            @endif
        </div>

        <!-- Motor Image -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Foto Motor</h2>
            
            @if($motor->foto)
                <div class="aspect-w-16 aspect-h-12">
                    <img src="{{ asset('storage/' . $motor->foto) }}" 
                         alt="{{ $motor->merk }} {{ $motor->model }}"
                         class="object-cover rounded-lg shadow-sm w-full h-64">
                </div>
            @else
                <div class="flex items-center justify-center h-64 bg-gray-100 rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Tidak ada foto</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Statistik</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $motor->penyewaans_count ?? 0 }}</div>
                    <div class="text-sm text-blue-600">Total Booking</div>
                </div>
                
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $motor->penyewaans()->where('status', \App\Enums\BookingStatus::COMPLETED)->count() }}
                    </div>
                    <div class="text-sm text-green-600">Booking Selesai</div>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Tanggal Dibuat</label>
                <p class="mt-1 text-gray-900">{{ $motor->created_at->format('d F Y H:i') }}</p>
            </div>
            
            <div class="mt-2">
                <label class="block text-sm font-medium text-gray-700">Terakhir Diperbarui</label>
                <p class="mt-1 text-gray-900">{{ $motor->updated_at->format('d F Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    @if($motor->penyewaans->count() > 0)
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Booking Terbaru</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($motor->penyewaans()->latest()->limit(5)->get() as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->penyewa->name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->penyewa->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $booking->tanggal_mulai->format('d/m/Y') }} - {{ $booking->tanggal_selesai->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $booking->getFormattedDuration() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $booking->status->color() === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $booking->status->color() === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $booking->status->color() === 'blue' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $booking->status->color() === 'red' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $booking->status->color() === 'gray' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $booking->status->label() }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection