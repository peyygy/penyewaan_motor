<x-layouts.owner-dashboard>
    <!-- Welcome Section -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Selamat Datang, {{ auth()->user()->nama }}!</h2>
        <p class="text-gray-600">Kelola motor dan lihat performa rental Anda untuk bulan {{ $currentMonth->format('F Y') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Motors -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-md">
                    <span class="text-2xl">🏍️</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Motor Saya</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_motors'] }}</p>
                </div>
            </div>
        </div>

        <!-- Available Motors -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-md">
                    <span class="text-2xl">✅</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Motor Tersedia</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['available_motors'] }}</p>
                </div>
            </div>
        </div>

        <!-- Rented Motors -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-md">
                    <span class="text-2xl">🔄</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Motor Disewa</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['rented_motors'] }}</p>
                </div>
            </div>
        </div>

        <!-- Monthly Earnings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-md">
                    <span class="text-2xl">💰</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendapatan Bulan Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($revenueStats['monthly_earnings'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & Booking Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Statistics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pendapatan</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pendapatan Bulan Ini ({{ $currentMonth->format('M Y') }})</span>
                    <span class="font-semibold text-green-600">Rp {{ number_format($revenueStats['monthly_earnings'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Pendapatan</span>
                    <span class="font-semibold text-blue-600">Rp {{ number_format($revenueStats['total_earnings'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Menunggu Settlement</span>
                    <span class="font-semibold text-orange-600">Rp {{ number_format($revenueStats['pending_settlements'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Rata-rata per Motor</span>
                    <span class="font-semibold">Rp {{ number_format($stats['total_motors'] > 0 ? ($revenueStats['total_earnings'] ?? 0) / $stats['total_motors'] : 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Booking Statistics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Booking</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Booking ({{ $currentMonth->format('M Y') }})</span>
                    <span class="font-semibold">{{ $bookingStats['total_bookings'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Booking Aktif</span>
                    <span class="font-semibold text-green-600">{{ $bookingStats['active_bookings'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Booking Selesai</span>
                    <span class="font-semibold text-blue-600">{{ $bookingStats['completed_bookings'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Tingkat Occupancy</span>
                    <span class="font-semibold">{{ $occupancyRate }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Motor Performance & Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Motor Performance -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Performa Motor Terbaik</h3>
            </div>
            <div class="p-6">
                @if(isset($topMotors) && $topMotors->count() > 0)
                    <div class="space-y-4">
                        @foreach($topMotors as $motor)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if(isset($motor->photo) && $motor->photo)
                                        <img class="h-10 w-10 rounded-lg object-cover mr-3" src="{{ Storage::url($motor->photo) }}" alt="Motor">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                            <span class="text-gray-500 text-xs">🏍️</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $motor->merk ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600">{{ $motor->no_plat ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-blue-600">
                                        {{ $motor->completed_rentals ?? 0 }} rental{{ ($motor->completed_rentals ?? 0) > 1 ? 's' : '' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        @if(isset($motor->tarifRental) && $motor->tarifRental)
                                            Rp {{ number_format($motor->tarifRental->harga_per_hari ?? 0, 0, ',', '.') }}/hari
                                        @else
                                            <span class="text-gray-400">Belum ada tarif</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-4">🏍️</div>
                        <p class="text-gray-500 font-medium">Belum ada data performa motor</p>
                        <p class="text-gray-400 text-sm">Motor yang pernah disewa akan ditampilkan di sini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Booking Terbaru</h3>
                    <a href="{{ route('owner.bookings.index') }}" class="text-sm text-blue-600 hover:text-blue-900">Lihat Semua</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentBookings->take(5) as $booking)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $booking->motor->merk }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->penyewa->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->tanggal_mulai->format('d M') }} - {{ $booking->tanggal_selesai->format('d M Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">Rp {{ number_format($booking->harga, 0, ',', '.') }}</p>
                                    <span class="px-2 py-1 text-xs rounded-full {{ 
                                        $booking->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($booking->status->value === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                        ($booking->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) 
                                    }}">
                                        {{ $booking->status->label() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500">
                        <span class="text-4xl mb-4 block">📅</span>
                        <p class="text-lg font-medium">Belum ada booking</p>
                        <p class="text-sm">Motor Anda belum mendapat booking</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    @if($stats['total_motors'] === 0 || $stats['pending_motors'] > 0)
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-2xl">💡</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-blue-900">Tips untuk Memulai</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        @if($stats['total_motors'] === 0)
                            <p>Mulai dengan mendaftarkan motor pertama Anda untuk mulai menghasilkan pendapatan dari sewa motor.</p>
                            <a href="{{ route('owner.motors.create') }}" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Daftarkan Motor Pertama
                            </a>
                        @elseif($stats['pending_motors'] > 0)
                            <p>Anda memiliki {{ $stats['pending_motors'] }} motor yang sedang menunggu verifikasi admin. Proses verifikasi biasanya memakan waktu 1-2 hari kerja.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-layouts.owner-dashboard>