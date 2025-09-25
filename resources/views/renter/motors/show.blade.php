<x-layouts.renter-app>
    <div class="space-y-6">
        <!-- Back Button -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('penyewa.motors.index') }}" 
               class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-900">
                ← Kembali ke Daftar Motor
            </a>
        </div>

        <!-- Motor Detail Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="md:flex">
                <!-- Motor Image -->
                <div class="md:w-1/2">
                    <div class="h-96 bg-gray-200 relative">
                        @if($motor->photo)
                            <img src="{{ Storage::url($motor->photo) }}" 
                                 alt="{{ $motor->merk }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <span class="text-8xl">🏍️</span>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $motor->status->label() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Motor Information -->
                <div class="md:w-1/2 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $motor->merk }}</h1>
                            <p class="text-lg text-gray-600">{{ $motor->no_plat }}</p>
                        </div>
                        <span class="px-3 py-1 text-sm font-medium rounded bg-blue-100 text-blue-800">
                            {{ $motor->tipe_cc }}cc
                        </span>
                    </div>

                    <!-- Owner Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Informasi Pemilik</h3>
                        <div class="space-y-1 text-sm">
                            <p><span class="font-medium">Nama:</span> {{ $motor->owner->nama }}</p>
                            <p><span class="font-medium">Email:</span> {{ $motor->owner->email }}</p>
                            <p><span class="font-medium">Telepon:</span> {{ $motor->owner->no_tlpn }}</p>
                        </div>
                    </div>

                    <!-- Pricing Table -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Tarif Sewa</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                <span class="font-medium text-gray-700">Harian</span>
                                <span class="text-lg font-bold text-blue-600">
                                    Rp {{ number_format($motor->tarif->harga_per_hari, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                <span class="font-medium text-gray-700">Mingguan (7 hari)</span>
                                <span class="text-lg font-bold text-blue-600">
                                    Rp {{ number_format($motor->tarif->harga_per_minggu, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-gray-700">Bulanan (30 hari)</span>
                                <span class="text-lg font-bold text-blue-600">
                                    Rp {{ number_format($motor->tarif->harga_per_bulan, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <a href="{{ route('penyewa.bookings.create', ['motor_id' => $motor->id]) }}" 
                           class="flex-1 px-6 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 font-semibold">
                            🏍️ Sewa Motor Ini
                        </a>
                        <button onclick="shareMotor()" 
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            📤 Share
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Specifications -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Spesifikasi Motor</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Merk</span>
                        <span class="font-medium">{{ $motor->merk }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tipe CC</span>
                        <span class="font-medium">{{ $motor->tipe_cc }}cc</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Plat Nomor</span>
                        <span class="font-medium">{{ $motor->no_plat }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $motor->status->label() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Rental History/Statistics (if available) -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Motor</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Penyewaan</span>
                        <span class="font-medium">{{ $motor->penyewaans->count() }} kali</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rating Motor</span>
                        <span class="font-medium">
                            @if($motor->penyewaans->count() > 0)
                                <span class="text-yellow-500">★★★★★</span> 
                                <span class="text-sm text-gray-500">({{ $motor->penyewaans->count() }} ulasan)</span>
                            @else
                                <span class="text-gray-400">Belum ada ulasan</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Terakhir Disewa</span>
                        <span class="font-medium">
                            @if($motor->penyewaans->where('status', 'completed')->count() > 0)
                                {{ $motor->penyewaans->where('status', 'completed')->latest()->first()->tanggal_selesai->diffForHumans() }}
                            @else
                                <span class="text-gray-400">Belum pernah</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Motors -->
        @if($similarMotors && $similarMotors->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Motor Serupa</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($similarMotors as $similar)
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="text-2xl">🏍️</span>
                            <div>
                                <h4 class="font-semibold">{{ $similar->merk }}</h4>
                                <p class="text-sm text-gray-600">{{ $similar->no_plat }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-blue-600">
                                Rp {{ number_format($similar->tarif->harga_per_hari, 0, ',', '.') }}/hari
                            </span>
                            <a href="{{ route('penyewa.motors.show', $similar) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function shareMotor() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $motor->merk }} - {{ $motor->no_plat }}',
                    text: 'Motor {{ $motor->merk }} tersedia untuk disewa!',
                    url: window.location.href
                });
            } else {
                // Fallback - copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('Link telah disalin ke clipboard!');
                });
            }
        }
    </script>
    @endpush
</x-layouts.renter-app>
