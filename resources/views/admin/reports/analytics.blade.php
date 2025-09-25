@extends('layouts.admin-dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h2>
                <p class="text-gray-600">Analisis mendalam tentang performa sistem</p>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('admin.reports.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    ← Kembali
                </a>
                <button id="exportPdf" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    📄 Export PDF
                </button>
            </div>
        </div>

        <!-- Period Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" class="flex items-center space-x-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Periode</label>
                    <select name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    </select>
                </div>
                <div class="pt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tren Pendapatan</h3>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" class="w-full" style="height: 300px;"></canvas>
            </div>
        </div>

        <!-- Daily Bookings Chart -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Booking Harian</h3>
            </div>
            <div class="p-6">
                <canvas id="bookingsChart" class="w-full" style="height: 300px;"></canvas>
            </div>
        </div>

        <!-- Conversion & Utilization -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Conversion Rate -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Tingkat Konversi</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2">
                            {{ number_format($metrics['conversion_rate'] ?? 0, 1) }}%
                        </div>
                        <p class="text-gray-600">conversion rate</p>
                        
                        <div class="mt-6 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rata-rata Nilai Booking</span>
                                <span class="font-medium">Rp {{ number_format($metrics['avg_booking_value'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Motor Utilization -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Utilisasi Motor</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if(isset($metrics['motor_utilization']))
                            @foreach($metrics['motor_utilization'] as $motor)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-sm">🏍️</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $motor->merk ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">{{ $motor->no_plat ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($motor->utilization_rate ?? 0, 1) }}%
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            utilization
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-center">Data utilisasi tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Status Analysis -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Analisis Status Booking</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $bookingStatusChart['pending'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Menunggu</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ (($bookingStatusChart['pending'] ?? 0) / max(array_sum($bookingStatusChart ?? []), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $bookingStatusChart['confirmed'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Dikonfirmasi</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ (($bookingStatusChart['confirmed'] ?? 0) / max(array_sum($bookingStatusChart ?? []), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $bookingStatusChart['active'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Aktif</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ (($bookingStatusChart['active'] ?? 0) / max(array_sum($bookingStatusChart ?? []), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-600">{{ $bookingStatusChart['completed'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Selesai</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-gray-600 h-2 rounded-full" style="width: {{ (($bookingStatusChart['completed'] ?? 0) / max(array_sum($bookingStatusChart ?? []), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $bookingStatusChart['cancelled'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Dibatalkan</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ (($bookingStatusChart['cancelled'] ?? 0) / max(array_sum($bookingStatusChart ?? []), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Motor Type Performance -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Performa Tipe Motor</h3>
            </div>
            <div class="p-6">
                <canvas id="motorTypeChart" class="w-full" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueChart['labels'] ?? []),
                    datasets: [{
                        label: 'Pendapatan Harian',
                        data: @json($revenueChart['data'] ?? []),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Bookings Chart
            const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
            new Chart(bookingsCtx, {
                type: 'bar',
                data: {
                    labels: @json($dailyBookingsChart['labels'] ?? []),
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: @json($dailyBookingsChart['data'] ?? []),
                        backgroundColor: '#10B981',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Motor Type Chart
            const motorTypeCtx = document.getElementById('motorTypeChart').getContext('2d');
            const motorTypeData = @json($motorTypeChart ?? []);
            new Chart(motorTypeCtx, {
                type: 'bar',
                data: {
                    labels: motorTypeData.map(item => item.type),
                    datasets: [{
                        label: 'Total Booking',
                        data: motorTypeData.map(item => item.bookings),
                        backgroundColor: '#F59E0B',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Export PDF
            document.getElementById('exportPdf').addEventListener('click', function() {
                window.location.href = '{{ route("admin.reports.export-pdf") }}?period={{ request("period", 30) }}';
            });
        });
    </script>
@endpush
@endsection