<x-layouts.admin-dashboard>
    <!-- Welcome Section -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Selamat Datang, {{ auth()->user()->nama }}!</h2>
        <p class="text-gray-600">Berikut ringkasan aktivitas sistem RentMotorcycle untuk bulan {{ $currentMonth->format('F Y') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-md">
                    <span class="text-2xl">👥</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Motors -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-md">
                    <span class="text-2xl">🏍️</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Motor</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_motors'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Motors -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-md">
                    <span class="text-2xl">⏳</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Motor Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_motors'] }}</p>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-md">
                    <span class="text-2xl">💰</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Revenue Bulanan</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($revenueStats['monthly_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
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
                    <span class="text-sm text-gray-600">Pending Payment</span>
                    <span class="font-semibold text-yellow-600">{{ $bookingStats['pending_bookings'] }}</span>
                </div>
            </div>
        </div>

        <!-- Motor Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Motor</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Motor Tersedia</span>
                    <span class="font-semibold text-green-600">{{ $stats['available_motors'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Motor Disewa</span>
                    <span class="font-semibold text-red-600">{{ $stats['rented_motors'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pending Verifikasi</span>
                    <span class="font-semibold text-yellow-600">{{ $stats['pending_motors'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Motors -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Motor Terbaru</h3>
            </div>
            <div class="p-6">
                @if($recentMotors->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentMotors as $motor)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $motor->merk }}</p>
                                    <p class="text-sm text-gray-600">{{ $motor->no_plat }} • {{ $motor->owner->nama }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $motor->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($motor->status->value === 'verified' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') 
                                }}">
                                    {{ $motor->status->label() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Belum ada motor terdaftar</p>
                @endif
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Booking Terbaru</h3>
            </div>
            <div class="p-6">
                @if($recentBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentBookings->take(5) as $booking)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $booking->motor->merk }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->penyewa->nama }} • Rp {{ number_format($booking->harga, 0, ',', '.') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $booking->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($booking->status->value === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                    ($booking->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) 
                                }}">
                                    {{ $booking->status->label() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Belum ada booking</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin-dashboard>