<x-layouts.penyewa-app>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Cari Motor</h2>
                <p class="text-gray-600">Temukan motor yang tepat untuk perjalanan Anda</p>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('penyewa.motors.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Motor</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Merk, plat nomor..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Motor Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Motor</label>
                        <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Tipe</option>
                            <option value="100" {{ request('type') == '100' ? 'selected' : '' }}>100cc</option>
                            <option value="125" {{ request('type') == '125' ? 'selected' : '' }}>125cc</option>
                            <option value="150" {{ request('type') == '150' ? 'selected' : '' }}>150cc</option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Minimal</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                               placeholder="Rp 0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Maksimal</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                               placeholder="Rp 999,999"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        🔍 Cari
                    </button>
                    @if(request()->filled(['search', 'type', 'min_price', 'max_price']))
                        <a href="{{ route('penyewa.motors.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Motors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($motors as $motor)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Motor Image -->
                    <div class="h-48 bg-gray-200 relative">
                        @if($motor->photo)
                            <img src="{{ Storage::url($motor->photo) }}" 
                                 alt="{{ $motor->merk }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <span class="text-6xl">🏍️</span>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Tersedia
                            </span>
                        </div>
                    </div>

                    <!-- Motor Info -->
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $motor->merk }}</h3>
                            <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">
                                {{ $motor->tipe_cc }}cc
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-3">{{ $motor->no_plat }}</p>
                        
                        <div class="text-sm text-gray-600 mb-3">
                            <p><span class="font-medium">Pemilik:</span> {{ $motor->owner->nama }}</p>
                        </div>

                        <!-- Pricing -->
                        <div class="border-t pt-3">
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Harian</span>
                                    <span class="font-semibold text-gray-900">Rp {{ number_format($motor->tarif->harga_per_hari, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Mingguan</span>
                                    <span class="font-semibold text-gray-900">Rp {{ number_format($motor->tarif->harga_per_minggu, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Bulanan</span>
                                    <span class="font-semibold text-gray-900">Rp {{ number_format($motor->tarif->harga_per_bulan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('penyewa.motors.show', $motor) }}" 
                               class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 text-center rounded-md hover:bg-gray-200 text-sm">
                                Detail
                            </a>
                            <a href="{{ route('penyewa.bookings.create', ['motor_id' => $motor->id]) }}" 
                               class="flex-1 px-3 py-2 bg-blue-600 text-white text-center rounded-md hover:bg-blue-700 text-sm">
                                Sewa Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <div class="text-gray-400 mb-4">
                            <span class="text-6xl">🔍</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Motor tidak ditemukan</h3>
                        <p class="text-gray-600 mb-4">Tidak ada motor yang sesuai dengan kriteria pencarian Anda.</p>
                        <a href="{{ route('penyewa.motors.index') }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Lihat Semua Motor
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($motors->hasPages())
            <div class="mt-6">
                {{ $motors->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Auto submit form on select change
        document.addEventListener('DOMContentLoaded', function() {
            const selectElements = document.querySelectorAll('select[name="type"]');
            selectElements.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
        });
    </script>
    @endpush
</x-layouts.penyewa-app>
