
@extends('layouts.admin-dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Laporan & Analytics</h2>
                <p class="text-gray-600">Dashboard laporan dan analisis data sistem penyewaan motor</p>
            </div>
            
            <!-- Export Buttons -->
            <div class="flex space-x-2">
                <a href="{{ route('admin.reports.revenue') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    📊 Laporan Pendapatan
                </a>
                <a href="{{ route('admin.reports.analytics') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    📈 Analytics
                </a>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Bookings -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <span class="text-2xl">📅</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Booking Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($monthlyStats['total_bookings']) }}</p>
                        <p class="text-sm text-gray-500">{{ number_format($monthlyStats['completed_bookings']) }} selesai</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pendapatan Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($monthlyStats['total_revenue'], 0, ',', '.') }}</p>
                        <p class="text-sm text-green-600">+12% dari bulan lalu</p>
                    </div>
                </div>
            </div>

            <!-- Platform Commission -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <span class="text-2xl">🏦</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Komisi Platform</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($monthlyStats['platform_commission'], 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500">Bagi hasil admin</p>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($userGrowth['total_users']) }}</p>
                        <p class="text-sm text-yellow-600">+{{ $userGrowth['new_users_this_month'] }} bulan ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- User Distribution Chart -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Distribusi Pengguna</h3>
                </div>
                <div class="p-6">
                    <canvas id="userDistributionChart" class="w-full h-64"></canvas>
                </div>
            </div>

            <!-- Motor Status Chart -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status Motor</h3>
                </div>
                <div class="p-6">
                    <canvas id="motorStatusChart" class="w-full h-64"></canvas>
                </div>
            </div>
        </div>

        <!-- Motor Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Motor Stats -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistik Motor</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Motor</span>
                        <span class="font-semibold text-gray-900">{{ $motorStats['total_motors'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Motor Tersedia</span>
                        <span class="font-semibold text-green-600">{{ $motorStats['active_motors'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Sedang Disewa</span>
                        <span class="font-semibold text-blue-600">{{ $motorStats['rented_motors'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Menunggu Verifikasi</span>
                        <span class="font-semibold text-yellow-600">{{ $motorStats['pending_verification'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow lg:col-span-2">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Booking Terbaru</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentBookings as $booking)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-lg">🏍️</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $booking->motor->merk }}</p>
                                        <p class="text-sm text-gray-500">{{ $booking->penyewa->nama }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($booking->harga, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500">Tidak ada booking terbaru</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Motors -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Motor Performing</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Booking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg per Booking</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topMotors as $motor)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-sm">🏍️</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $motor->merk }}</div>
                                            <div class="text-sm text-gray-500">{{ $motor->no_plat }} • {{ $motor->tipe_cc }}cc</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $motor->completed_bookings }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-green-600">Rp {{ number_format($motor->total_revenue ?: 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500">
                                        Rp {{ $motor->completed_bookings > 0 ? number_format(($motor->total_revenue ?: 0) / $motor->completed_bookings, 0, ',', '.') : 0 }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada data motor
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User Distribution Chart
            const userCtx = document.getElementById('userDistributionChart').getContext('2d');
            new Chart(userCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'Pemilik Motor', 'Penyewa'],
                    datasets: [{
                        data: [1, {{ $userGrowth['owners'] }}, {{ $userGrowth['renters'] }}],
                        backgroundColor: [
                            '#EF4444',
                            '#10B981', 
                            '#3B82F6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Motor Status Chart
            const motorCtx = document.getElementById('motorStatusChart').getContext('2d');
            new Chart(motorCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Disewa', 'Menunggu Verifikasi'],
                    datasets: [{
                        data: [
                            {{ $motorStats['active_motors'] }}, 
                            {{ $motorStats['rented_motors'] }}, 
                            {{ $motorStats['pending_verification'] }}
                        ],
                        backgroundColor: [
                            '#10B981',
                            '#3B82F6',
                            '#F59E0B'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
