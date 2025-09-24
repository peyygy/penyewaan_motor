@extends('layouts.admin-dashboard')

@section('title', 'Verifikasi Motor')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Verifikasi Motor</h1>
            <p class="text-gray-600 mt-1">Kelola verifikasi dan persetujuan motor yang didaftarkan pemilik</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-600">
                Total motor menunggu: <span class="font-semibold text-orange-600">{{ $motors->where('status', \App\Enums\MotorStatus::PENDING)->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="flex flex-col md:flex-row gap-4 mb-6 p-4 bg-amber-50 rounded-lg border border-amber-200">
        <div class="flex-1">
            <form method="GET" class="flex gap-3">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari berdasarkan merek, plat nomor, pemilik..."
                           class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                </div>
                <select name="status" class="px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Sudah Diverifikasi</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Aktif/Tersedia</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <select name="type" class="px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">Semua Tipe</option>
                    @foreach(\App\Enums\MotorType::cases() as $type)
                        <option value="{{ $type->value }}" {{ request('type') === $type->value ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.motors-verification.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Menunggu Verifikasi</p>
                    <p class="text-2xl font-bold text-yellow-800">{{ $motors->where('status', \App\Enums\MotorStatus::PENDING)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Sudah Diverifikasi</p>
                    <p class="text-2xl font-bold text-green-800">{{ $motors->where('status', \App\Enums\MotorStatus::VERIFIED)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Aktif/Tersedia</p>
                    <p class="text-2xl font-bold text-blue-800">{{ $motors->where('status', \App\Enums\MotorStatus::AVAILABLE)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">Ditolak</p>
                    <p class="text-2xl font-bold text-red-800">{{ $motors->where('status', \App\Enums\MotorStatus::REJECTED)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-200 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-amber-50 border-b border-amber-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">Motor</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">Pemilik</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">Tanggal Daftar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($motors as $motor)
                <tr class="hover:bg-amber-25 transition-colors">
                    <td class="px-4 py-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-12 h-12 bg-gray-300 rounded-lg flex-shrink-0 flex items-center justify-center">
                                @if($motor->foto)
                                    <img src="{{ Storage::url($motor->foto) }}" alt="Motor photo" class="w-12 h-12 rounded-lg object-cover">
                                @else
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $motor->merk }} {{ $motor->model ?? '' }}</p>
                                <p class="text-sm text-gray-600">{{ $motor->no_plat }}</p>
                                <p class="text-xs text-gray-500">{{ $motor->tipe?->name ?? 'N/A' }} • {{ $motor->year ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $motor->owner->name ?? 'Tidak ada pemilik' }}</p>
                            <p class="text-sm text-gray-600">{{ $motor->owner->email ?? '' }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        @php
                            $statusColor = match($motor->status) {
                                \App\Enums\MotorStatus::AVAILABLE => 'bg-green-100 text-green-800 border-green-200',
                                \App\Enums\MotorStatus::PENDING => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                \App\Enums\MotorStatus::VERIFIED => 'bg-blue-100 text-blue-800 border-blue-200',
                                \App\Enums\MotorStatus::RENTED => 'bg-purple-100 text-purple-800 border-purple-200',
                                \App\Enums\MotorStatus::MAINTENANCE => 'bg-orange-100 text-orange-800 border-orange-200',
                                \App\Enums\MotorStatus::REJECTED => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $statusColor }}">
                            {{ $motor->status?->name ?? 'Unknown' }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="text-sm text-gray-900">{{ $motor->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $motor->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.motors-verification.show', $motor->id) }}" 
                               class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                               title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            @if($motor->status == \App\Enums\MotorStatus::PENDING)
                                <form method="POST" action="{{ route('admin.motors-verification.verify', $motor->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-50 transition-colors"
                                            onclick="return confirm('Verifikasi dan setujui motor ini?')"
                                            title="Verifikasi Motor">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('admin.motors-verification.reject', $motor->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                            onclick="return confirm('Tolak pendaftaran motor ini?')"
                                            title="Tolak Motor">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif

                            @if($motor->status == \App\Enums\MotorStatus::VERIFIED)
                                <form method="POST" action="{{ route('admin.motors-verification.activate', $motor->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                            onclick="return confirm('Aktifkan motor ini untuk disewakan?')"
                                            title="Aktifkan Motor">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center space-y-3">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada motor untuk diverifikasi</p>
                            <p class="text-sm">Motor yang didaftarkan pemilik akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($motors->hasPages())
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Menampilkan {{ $motors->firstItem() }} hingga {{ $motors->lastItem() }} dari {{ $motors->total() }} motor
        </div>
        <div>
            {{ $motors->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>
@endsection