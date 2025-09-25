<x-layouts.renter-app>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Riwayat Penyewaan</h2>
                <p class="text-gray-600">Kelola dan pantau status penyewaan motor Anda</p>
            </div>
            <a href="{{ route('penyewa.motors.index') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                🏍️ Sewa Motor Baru
            </a>
        </div>

        <!-- Filter & Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-md">
                        <span class="text-xl">⏳</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Menunggu</p>
                        <p class="text-xl font-semibold">{{ $bookings->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-md">
                        <span class="text-xl">✅</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Aktif</p>
                        <p class="text-xl font-semibold">{{ $bookings->where('status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-md">
                        <span class="text-xl">✔️</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Selesai</p>
                        <p class="text-xl font-semibold">{{ $bookings->where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 rounded-md">
                        <span class="text-xl">📊</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-xl font-semibold">{{ $bookings->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($bookings->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <!-- Booking Info -->
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        @if($booking->motor->photo)
                                            <img src="{{ Storage::url($booking->motor->photo) }}" 
                                                 alt="{{ $booking->motor->merk }}" 
                                                 class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <span class="text-2xl">🏍️</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $booking->motor->merk }}</h3>
                                            <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-600">
                                                {{ $booking->motor->no_plat }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-sm text-gray-600 space-y-1">
                                            <p>
                                                <span class="font-medium">Periode:</span>
                                                {{ $booking->tanggal_mulai->format('d M Y') }} - {{ $booking->tanggal_selesai->format('d M Y') }}
                                                ({{ $booking->total_hari }} hari)
                                            </p>
                                            <p>
                                                <span class="font-medium">Pemilik:</span>
                                                {{ $booking->motor->owner->nama }}
                                            </p>
                                            <p>
                                                <span class="font-medium">Tipe Durasi:</span>
                                                {{ ucfirst($booking->tipe_durasi) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Price -->
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">
                                            Rp {{ number_format($booking->harga, 0, ',', '.') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Rp {{ number_format($booking->harga_per_hari, 0, ',', '.') }}/hari
                                        </p>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="flex flex-col items-center space-y-2">
                                        @switch($booking->status)
                                            @case('pending')
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu Konfirmasi
                                                </span>
                                                @break
                                            @case('confirmed')
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Dikonfirmasi
                                                </span>
                                                @break
                                            @case('active')
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                    Sedang Berlangsung
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Selesai
                                                </span>
                                                @break
                                            @case('cancelled')
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                                @break
                                        @endswitch

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-2">
                                            <a href="{{ route('penyewa.bookings.show', $booking) }}" 
                                               class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                                Detail
                                            </a>
                                            
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('penyewa.bookings.cancel', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            onclick="return confirm('Yakin ingin membatalkan booking ini?')"
                                                            class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @endif

                                            @if($booking->status === 'confirmed' && !$booking->transaksi)
                                                <a href="{{ route('penyewa.payments.create', ['booking_id' => $booking->id]) }}" 
                                                   class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                                                    Bayar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            @if($booking->transaksi)
                                <div class="mt-4 p-3 bg-green-50 rounded-lg">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-green-700">
                                            💳 <strong>Pembayaran:</strong> 
                                            {{ ucfirst($booking->transaksi->status) }} • 
                                            {{ ucfirst($booking->transaksi->metode_pembayaran) }}
                                        </span>
                                        <span class="text-green-600 font-medium">
                                            Rp {{ number_format($booking->transaksi->jumlah, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <span class="text-6xl">🏍️</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Penyewaan</h3>
                    <p class="text-gray-600 mb-4">Anda belum pernah menyewa motor. Mulai cari motor yang tepat untuk perjalanan Anda!</p>
                    <a href="{{ route('penyewa.motors.index') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Cari Motor Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.renter-app>
