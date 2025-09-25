<x-layouts.renter-app>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('penyewa.motors.show', $motor) }}" 
               class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-900">
                ← Kembali ke Detail Motor
            </a>
        </div>

        <!-- Booking Form -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-blue-50 border-b">
                    <h2 class="text-xl font-semibold text-gray-900">Formulir Pemesanan Motor</h2>
                    <p class="text-sm text-gray-600">Lengkapi data berikut untuk menyewa motor</p>
                </div>

                <form action="{{ route('penyewa.bookings.store') }}" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="motor_id" value="{{ $motor->id }}">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column: Booking Details -->
                        <div class="space-y-6">
                            <!-- Motor Information -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Motor yang Disewa</h3>
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-2xl">🏍️</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">{{ $motor->merk }}</h4>
                                        <p class="text-sm text-gray-600">{{ $motor->no_plat }} • {{ $motor->tipe_cc }}cc</p>
                                        <p class="text-sm text-gray-600">Pemilik: {{ $motor->owner->nama }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rental Period -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Periode Sewa</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" required
                                               min="{{ date('Y-m-d') }}"
                                               value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_mulai') border-red-500 @enderror">
                                        @error('tanggal_mulai')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                                               min="{{ date('Y-m-d') }}"
                                               value="{{ old('tanggal_selesai') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_selesai') border-red-500 @enderror">
                                        @error('tanggal_selesai')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Rental Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Durasi</label>
                                <select name="tipe_durasi" id="tipe_durasi" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tipe_durasi') border-red-500 @enderror">
                                    <option value="">Pilih Tipe Durasi</option>
                                    <option value="harian" {{ old('tipe_durasi') == 'harian' ? 'selected' : '' }}>Harian</option>
                                    <option value="mingguan" {{ old('tipe_durasi') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                    <option value="bulanan" {{ old('tipe_durasi') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                </select>
                                @error('tipe_durasi')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                <textarea name="catatan" rows="3" placeholder="Tambahkan catatan atau permintaan khusus..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        <!-- Right Column: Price Calculation -->
                        <div class="space-y-6">
                            <!-- Price Calculation Card -->
                            <div class="bg-blue-50 rounded-lg p-6 sticky top-4">
                                <h3 class="font-semibold text-gray-900 mb-4">Ringkasan Biaya</h3>
                                
                                <!-- Pricing Table -->
                                <div class="space-y-2 mb-4 text-sm">
                                    <div class="flex justify-between">
                                        <span>Tarif Harian:</span>
                                        <span>Rp {{ number_format($motor->tarif->harga_per_hari, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Tarif Mingguan:</span>
                                        <span>Rp {{ number_format($motor->tarif->harga_per_minggu, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Tarif Bulanan:</span>
                                        <span>Rp {{ number_format($motor->tarif->harga_per_bulan, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="border-t border-blue-200 pt-4">
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span>Durasi Sewa:</span>
                                            <span id="duration-display">- hari</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span>Tarif per Unit:</span>
                                            <span id="rate-display">Rp 0</span>
                                        </div>
                                        <div class="border-t border-blue-200 pt-3">
                                            <div class="flex justify-between text-lg font-bold">
                                                <span>Total Biaya:</span>
                                                <span id="total-display" class="text-blue-600">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden input for calculated price -->
                                <input type="hidden" name="harga" id="calculated-price" value="0">
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <span class="text-yellow-400">⚠️</span>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Syarat & Ketentuan</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <ul class="space-y-1">
                                                <li>• Pembayaran dilakukan sebelum pengambilan motor</li>
                                                <li>• Motor harus dikembalikan dalam kondisi baik</li>
                                                <li>• Keterlambatan pengembalian dikenakan denda</li>
                                                <li>• Wajib membawa KTP dan SIM yang masih berlaku</li>
                                            </ul>
                                        </div>
                                        <div class="mt-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="agree_terms" required
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-yellow-800">Saya menyetujui syarat dan ketentuan</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('penyewa.motors.show', $motor) }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold">
                            Lanjut ke Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const motorTarif = {
            harian: {{ $motor->tarif->harga_per_hari }},
            mingguan: {{ $motor->tarif->harga_per_minggu }},
            bulanan: {{ $motor->tarif->harga_per_bulan }}
        };

        function calculatePrice() {
            const startDate = new Date(document.getElementById('tanggal_mulai').value);
            const endDate = new Date(document.getElementById('tanggal_selesai').value);
            const tipeDurasi = document.getElementById('tipe_durasi').value;

            if (startDate && endDate && endDate >= startDate && tipeDurasi) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 karena inklusif

                let rate = 0;
                let units = 0;
                let unitLabel = '';

                switch(tipeDurasi) {
                    case 'harian':
                        rate = motorTarif.harian;
                        units = diffDays;
                        unitLabel = diffDays + ' hari';
                        break;
                    case 'mingguan':
                        rate = motorTarif.mingguan;
                        units = Math.ceil(diffDays / 7);
                        unitLabel = units + ' minggu';
                        break;
                    case 'bulanan':
                        rate = motorTarif.bulanan;
                        units = Math.ceil(diffDays / 30);
                        unitLabel = units + ' bulan';
                        break;
                }

                const totalPrice = rate * units;

                // Update display
                document.getElementById('duration-display').textContent = unitLabel;
                document.getElementById('rate-display').textContent = 'Rp ' + rate.toLocaleString('id-ID');
                document.getElementById('total-display').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
                document.getElementById('calculated-price').value = totalPrice;
            } else {
                // Reset display
                document.getElementById('duration-display').textContent = '- hari';
                document.getElementById('rate-display').textContent = 'Rp 0';
                document.getElementById('total-display').textContent = 'Rp 0';
                document.getElementById('calculated-price').value = 0;
            }
        }

        // Event listeners
        document.getElementById('tanggal_mulai').addEventListener('change', function() {
            // Set minimum end date to start date
            document.getElementById('tanggal_selesai').min = this.value;
            calculatePrice();
        });

        document.getElementById('tanggal_selesai').addEventListener('change', calculatePrice);
        document.getElementById('tipe_durasi').addEventListener('change', calculatePrice);

        // Initial calculation
        document.addEventListener('DOMContentLoaded', function() {
            calculatePrice();
        });
    </script>
    @endpush
</x-layouts.renter-app>
