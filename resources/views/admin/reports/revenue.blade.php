@extends('layouts.admin-dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Laporan Pendapatan</h2>
                <p class="text-gray-600">Ringkasan pendapatan dan bagi hasil platform</p>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('admin.reports.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    ← Kembali
                </a>
                <button onclick="exportToExcel()" 
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    📊 Export Excel
                </button>
                <button onclick="printReport()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    🖨️ Print
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Revenue -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow text-white p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-blue-100">Total Pendapatan</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($revenue['total_revenue'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Platform Commission -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow text-white p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <span class="text-2xl">🏦</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-green-100">Komisi Platform</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($revenue['platform_commission'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Owner Share -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow text-white p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-purple-100">Bagi Hasil Owner</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($revenue['owner_payouts'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow text-white p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <span class="text-2xl">📊</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-orange-100">Total Transaksi</p>
                        <p class="text-2xl font-bold">{{ number_format($revenue['total_bookings'] ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Breakdown Summary -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Ringkasan Periode {{ $startDate }} - {{ $endDate }}</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($revenue['total_bookings'] ?? 0) }}</div>
                        <p class="text-gray-600">Total Booking</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">Rp {{ number_format($revenue['average_booking_value'] ?? 0, 0, ',', '.') }}</div>
                        <p class="text-gray-600">Rata-rata per Booking</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($revenueByType->count()) }}</div>
                        <p class="text-gray-600">Variasi Tipe Motor</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue by Motor Type -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pendapatan per Tipe Motor</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($revenueByType as $type)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span class="text-lg">
                                            @if(str_contains($type->tipe_cc, 'matic') || $type->tipe_cc <= 150)
                                                🛴 
                                            @elseif($type->tipe_cc > 250)
                                                🏍️ 
                                            @else
                                                🚲 
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $type->tipe_cc }}cc</p>
                                        <p class="text-sm text-gray-500">{{ $type->bookings }} booking</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($type->revenue ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ number_format(($type->revenue ?? 0) / max(($revenue['total_revenue'] ?? 1), 1) * 100, 1) }}%
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500">Tidak ada data pendapatan berdasarkan tipe</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Earning Owners -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Owner Berpenghasilan Tertinggi</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($topOwners as $index => $owner)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold text-green-600">#{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $owner->nama }}</p>
                                        <p class="text-sm text-gray-500">{{ $owner->motors_count ?? 0 }} motor</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($owner->total_revenue ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500">earnings</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500">Tidak ada data owner</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        function exportToExcel() {
            window.location.href = '{{ route("admin.reports.export-excel") }}';
        }

        function printReport() {
            window.print();
        }
    </script>

    <!-- Print styles -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
@endpush
@endsection