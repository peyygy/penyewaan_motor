@extends('layouts.owner-dashboard')

@section('title', 'Motor Saya')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Motor Saya</h1>
            <p class="text-gray-600 mt-1">Kelola motor yang Anda miliki</p>
        </div>
        <a href="{{ route('owner.motors.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus"></i> Daftarkan Motor Baru
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Motor</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Merk atau plat nomor..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>Sedang Disewa</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe CC</label>
                <select name="tipe_cc" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="100" {{ request('tipe_cc') === '100' ? 'selected' : '' }}>100cc</option>
                    <option value="125" {{ request('tipe_cc') === '125' ? 'selected' : '' }}>125cc</option>
                    <option value="150" {{ request('tipe_cc') === '150' ? 'selected' : '' }}>150cc</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">
                    Cari
                </button>
                <a href="{{ route('owner.motors.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Motor Grid -->
    @if($motors->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($motors as $motor)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Motor Image -->
                    <div class="h-48 bg-gray-200 relative">
                        @if($motor->photo)
                            <img src="{{ asset('storage/' . $motor->photo) }}" 
                                 alt="{{ $motor->merk }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <span class="text-6xl">🏍️</span>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @php
                                $statusClass = match($motor->status->value) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'verified' => 'bg-blue-100 text-blue-800',
                                    'available' => 'bg-green-100 text-green-800',
                                    'rented' => 'bg-red-100 text-red-800',
                                    'maintenance' => 'bg-gray-100 text-gray-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                {{ $motor->status->label() }}
                            </span>
                        </div>
                    </div>

                    <!-- Motor Info -->
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $motor->merk }}</h3>
                            <span class="text-sm text-gray-500">{{ $motor->tipe_cc->label() }}</span>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-2">{{ $motor->no_plat }}</p>
                        
                        <!-- Pricing -->
                        @if($motor->tarif)
                            <div class="mb-3">
                                <div class="text-sm text-gray-900 font-medium">
                                    Rp {{ number_format($motor->tarif->tarif_harian, 0, ',', '.') }}/hari
                                </div>
                                <div class="text-xs text-gray-500">
                                    Mingguan: Rp {{ number_format($motor->tarif->tarif_mingguan, 0, ',', '.') }} | 
                                    Bulanan: Rp {{ number_format($motor->tarif->tarif_bulanan, 0, ',', '.') }}
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <span class="text-sm text-red-600 font-medium">Tarif belum diset</span>
                            </div>
                        @endif

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-4 text-xs text-gray-600">
                            <div>
                                <span class="font-medium">Total Sewa:</span>
                                <div class="text-lg font-bold text-gray-900">{{ $motor->total_bookings ?? 0 }}</div>
                            </div>
                            <div>
                                <span class="font-medium">Pendapatan:</span>
                                <div class="text-lg font-bold text-green-600">
                                    Rp {{ number_format($motor->total_revenue ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <a href="{{ route('owner.motors.show', $motor->id) }}" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded text-sm text-center">
                                Detail
                            </a>
                            <a href="{{ route('owner.motors.edit', $motor->id) }}" 
                               class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-3 rounded text-sm text-center">
                                Edit
                            </a>
                            @if($motor->status === \App\Enums\MotorStatus::AVAILABLE && !$motor->penyewaans()->whereIn('status', ['confirmed', 'active'])->exists())
                                <button onclick="deleteMotor({{ $motor->id }}, '{{ $motor->merk }} - {{ $motor->no_plat }}')" 
                                        class="bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded text-sm">
                                    Hapus
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-lg shadow p-4">
            {{ $motors->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="text-gray-500">
                <span class="text-6xl mb-4 block">🏍️</span>
                <h3 class="text-xl font-semibold mb-2">Belum ada motor terdaftar</h3>
                <p class="text-gray-600 mb-4">Daftarkan motor pertama Anda untuk mulai menerima pesanan sewa</p>
                <a href="{{ route('owner.motors.create') }}" 
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                    Daftarkan Motor Sekarang
                </a>
            </div>
        </div>
    @endif
</div>

<script>
// Motor deletion function
function deleteMotor(motorId, motorName) {
    if (confirm(`Apakah Anda yakin ingin menghapus motor ${motorName}? Data ini tidak dapat dikembalikan.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/owner/motors/${motorId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection