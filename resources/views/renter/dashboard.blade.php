<x-layouts.renter-app>
    <!-- Welcome Section -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->nama }}!</h2>
        <p class="text-gray-600">Temukan motor yang tepat untuk perjalanan Anda</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Active Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-md">
                    <span class="text-2xl">🏍️</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Booking Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_bookings'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-md">
                    <span class="text-2xl">📅</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Booking</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_bookings'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-md">
                    <span class="text-2xl">💰</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Favorite Motor Type -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-md">
                    <span class="text-2xl">⭐</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tipe Favorit</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['favorite_type'] ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Active Booking -->
    @if($activeBooking)
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Booking Aktif</h3>
                    <p class="text-green-100">{{ $activeBooking->motor->merk }} - {{ $activeBooking->motor->no_plat }}</p>
                    <p class="text-green-100 text-sm">
                        {{ $activeBooking->tanggal_mulai->format('d M Y') }} - {{ $activeBooking->tanggal_selesai->format('d M Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold">{{ $activeBooking->tanggal_selesai->diffInDays(now()) }} hari</p>
                    <p class="text-green-100 text-sm">tersisa</p>
                    <a href="{{ route('renter.bookings.show', $activeBooking) }}" 
                        class="mt-2 inline-block bg-white text-green-600 px-4 py-2 rounded-md text-sm font-medium hover:bg-green-50">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Available Motors -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Motor Tersedia</h3>
                        <a href="{{ route('renter.motors.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            Lihat Semua →
                        </a>
                    </div>
                </div>

                @if($availableMotors->count() > 0)
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($availableMotors->take(4) as $motor)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-3">
                                        @if($motor->photo)
                                            <img class="h-12 w-12 rounded-lg object-cover mr-3" src="{{ Storage::url($motor->photo) }}" alt="Motor">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                                <span class="text-gray-500">🏍️</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $motor->merk }}</h4>
                                            <p class="text-sm text-gray-600">{{ $motor->tipe->label() }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($motor->tarif->harga_per_hari, 0, ',', '.') }}/hari</p>
                                        <p class="text-sm text-gray-600">{{ $motor->owner->nama }}</p>
                                    </div>
                                    
                                    <a href="{{ route('renter.motors.show', $motor) }}" 
                                        class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md hover:bg-blue-700 block text-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center">
                        <div class="text-gray-500">
                            <span class="text-4xl mb-4 block">🏍️</span>
                            <p class="text-lg font-medium">Belum ada motor tersedia</p>
                            <p class="text-sm">Coba cek lagi nanti atau hubungi admin</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Booking Terbaru</h3>
                </div>
                <div class="p-6">
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings->take(3) as $booking)
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <h4 class="font-medium text-gray-900">{{ $booking->motor->merk }}</h4>
                                    <p class="text-sm text-gray-600">{{ $booking->tanggal_mulai->format('d M Y') }}</p>
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
                        <div class="mt-4">
                            <a href="{{ route('renter.bookings.index') }}" class="text-sm text-blue-600 hover:text-blue-900">
                                Lihat semua booking →
                            </a>
                        </div>
                    @else
                        <div class="text-center text-gray-500">
                            <span class="text-2xl mb-2 block">📅</span>
                            <p class="text-sm">Belum ada booking</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Menu Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('renter.motors.index') }}" 
                        class="flex items-center p-3 rounded-md hover:bg-gray-50 border border-gray-200">
                        <span class="text-xl mr-3">🔍</span>
                        <span class="text-sm font-medium">Cari Motor</span>
                    </a>
                    
                    <a href="{{ route('renter.bookings.index') }}" 
                        class="flex items-center p-3 rounded-md hover:bg-gray-50 border border-gray-200">
                        <span class="text-xl mr-3">📋</span>
                        <span class="text-sm font-medium">Booking Saya</span>
                    </a>
                    
                    <a href="{{ route('renter.history.index') }}" 
                        class="flex items-center p-3 rounded-md hover:bg-gray-50 border border-gray-200">
                        <span class="text-xl mr-3">📈</span>
                        <span class="text-sm font-medium">Riwayat Sewa</span>
                    </a>
                    
                    <a href="{{ route('renter.profile.index') }}" 
                        class="flex items-center p-3 rounded-md hover:bg-gray-50 border border-gray-200">
                        <span class="text-xl mr-3">⚙️</span>
                        <span class="text-sm font-medium">Pengaturan</span>
                    </a>
                </div>
            </div>

            <!-- Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start">
                    <span class="text-xl mr-3">💡</span>
                    <div>
                        <h4 class="font-medium text-blue-900 mb-2">Tips Rental Motor</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Selalu cek kondisi motor sebelum booking</li>
                            <li>• Pastikan dokumen lengkap</li>
                            <li>• Ikuti aturan lalu lintas</li>
                            <li>• Kembalikan tepat waktu</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.renter-app>