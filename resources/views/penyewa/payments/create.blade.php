<x-layouts.penyewa-app>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('penyewa.bookings.index') }}" 
               class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-900">
                ← Kembali ke Daftar Booking
            </a>
        </div>

        <!-- Payment Form -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-green-50 border-b">
                    <h2 class="text-xl font-semibold text-gray-900">Pembayaran Sewa Motor</h2>
                    <p class="text-sm text-gray-600">Pilih metode pembayaran dan selesaikan transaksi</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column: Booking Summary -->
                        <div class="space-y-6">
                            <!-- Booking Details -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="font-semibold text-gray-900 mb-4">Detail Penyewaan</h3>
                                
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        @if($booking->motor->photo)
                                            <img src="{{ Storage::url($booking->motor->photo) }}" 
                                                 alt="{{ $booking->motor->merk }}" 
                                                 class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <span class="text-2xl">🏍️</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-lg">{{ $booking->motor->merk }}</h4>
                                        <p class="text-gray-600">{{ $booking->motor->no_plat }} • {{ $booking->motor->tipe_cc }}cc</p>
                                        <p class="text-sm text-gray-500">Pemilik: {{ $booking->motor->owner->nama }}</p>
                                    </div>
                                </div>

                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Periode Sewa:</span>
                                        <span class="font-medium">
                                            {{ $booking->tanggal_mulai->format('d M Y') }} - {{ $booking->tanggal_selesai->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Durasi:</span>
                                        <span class="font-medium">{{ $booking->total_hari }} hari</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tipe Sewa:</span>
                                        <span class="font-medium">{{ ucfirst($booking->tipe_durasi) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Breakdown -->
                            <div class="bg-blue-50 rounded-lg p-6">
                                <h3 class="font-semibold text-gray-900 mb-4">Rincian Biaya</h3>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span>Tarif {{ $booking->tipe_durasi }}:</span>
                                        <span>Rp {{ number_format($booking->harga_per_hari, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>Jumlah {{ $booking->tipe_durasi == 'harian' ? 'hari' : ($booking->tipe_durasi == 'mingguan' ? 'minggu' : 'bulan') }}:</span>
                                        <span>{{ $booking->total_hari }} {{ $booking->tipe_durasi == 'harian' ? 'hari' : ($booking->tipe_durasi == 'mingguan' ? 'minggu' : 'bulan') }}</span>
                                    </div>
                                    <hr class="border-blue-200">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Total Pembayaran:</span>
                                        <span class="text-blue-600">Rp {{ number_format($booking->harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Payment Method -->
                        <div class="space-y-6">
                            <form action="{{ route('penyewa.payments.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                <!-- Payment Methods -->
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-4">Pilih Metode Pembayaran</h3>
                                    
                                    <div class="space-y-3">
                                        <!-- Bank Transfer -->
                                        <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50">
                                            <input type="radio" name="metode_pembayaran" value="bank_transfer" 
                                                   class="sr-only" onchange="togglePaymentDetails(this)">
                                            <div class="flex items-center space-x-3 w-full">
                                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    🏦
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-medium">Transfer Bank</div>
                                                    <div class="text-sm text-gray-500">BCA, BNI, BRI, Mandiri</div>
                                                </div>
                                                <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full"></div>
                                            </div>
                                        </label>

                                        <!-- E-Wallet -->
                                        <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50">
                                            <input type="radio" name="metode_pembayaran" value="e_wallet" 
                                                   class="sr-only" onchange="togglePaymentDetails(this)">
                                            <div class="flex items-center space-x-3 w-full">
                                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                    📱
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-medium">E-Wallet</div>
                                                    <div class="text-sm text-gray-500">GoPay, OVO, DANA, ShopeePay</div>
                                                </div>
                                                <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full"></div>
                                            </div>
                                        </label>

                                        <!-- Cash -->
                                        <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50">
                                            <input type="radio" name="metode_pembayaran" value="cash" 
                                                   class="sr-only" onchange="togglePaymentDetails(this)">
                                            <div class="flex items-center space-x-3 w-full">
                                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                    💰
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-medium">Bayar Tunai</div>
                                                    <div class="text-sm text-gray-500">Bayar langsung kepada pemilik motor</div>
                                                </div>
                                                <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full"></div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Payment Details -->
                                <div id="bank-transfer-details" class="hidden bg-blue-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3">Transfer ke Rekening:</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span>Bank:</span>
                                            <span class="font-medium">BCA</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>No. Rekening:</span>
                                            <span class="font-medium">1234567890</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Atas Nama:</span>
                                            <span class="font-medium">PT. Sewa Motor Indonesia</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Jumlah:</span>
                                            <span class="font-bold text-blue-600">Rp {{ number_format($booking->harga, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-sm">
                                        <p class="text-yellow-700">
                                            <strong>Catatan:</strong> Transfer tepat sesuai jumlah dan upload bukti pembayaran
                                        </p>
                                    </div>
                                </div>

                                <div id="e-wallet-details" class="hidden bg-green-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3">Pilih E-Wallet:</h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" class="p-3 border rounded-lg hover:bg-green-100">
                                            <img src="/images/gopay.png" alt="GoPay" class="h-6 mx-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <span style="display:none;">GoPay</span>
                                        </button>
                                        <button type="button" class="p-3 border rounded-lg hover:bg-green-100">
                                            <img src="/images/ovo.png" alt="OVO" class="h-6 mx-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <span style="display:none;">OVO</span>
                                        </button>
                                        <button type="button" class="p-3 border rounded-lg hover:bg-green-100">
                                            <img src="/images/dana.png" alt="DANA" class="h-6 mx-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <span style="display:none;">DANA</span>
                                        </button>
                                        <button type="button" class="p-3 border rounded-lg hover:bg-green-100">
                                            <img src="/images/shopeepay.png" alt="ShopeePay" class="h-6 mx-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <span style="display:none;">ShopeePay</span>
                                        </button>
                                    </div>
                                </div>

                                <div id="cash-details" class="hidden bg-yellow-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3">Pembayaran Tunai</h4>
                                    <div class="text-sm text-yellow-700">
                                        <p class="mb-2">Anda akan membayar langsung kepada pemilik motor saat pengambilan kendaraan.</p>
                                        <p><strong>Kontak Pemilik:</strong> {{ $booking->motor->owner->nama }} - {{ $booking->motor->owner->no_tlpn }}</p>
                                    </div>
                                </div>

                                <!-- Upload Proof (for non-cash payments) -->
                                <div id="upload-section" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran</label>
                                    <input type="file" name="bukti_pembayaran" accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                                </div>

                                <!-- Terms & Conditions -->
                                <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <span class="text-gray-400">ℹ️</span>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-800">Syarat Pembayaran</h3>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <ul class="space-y-1">
                                                    <li>• Pembayaran akan diverifikasi dalam 1-24 jam</li>
                                                    <li>• Motor dapat diambil setelah pembayaran dikonfirmasi</li>
                                                    <li>• Refund akan diproses jika pembayaran ditolak</li>
                                                    <li>• Hubungi customer service jika ada kendala</li>
                                                </ul>
                                            </div>
                                            <div class="mt-3">
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="agree_payment_terms" required
                                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-gray-800">Saya menyetujui syarat pembayaran</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex justify-end space-x-3 pt-4">
                                    <a href="{{ route('penyewa.bookings.index') }}" 
                                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                        Batal
                                    </a>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold">
                                        💳 Bayar Sekarang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        /* Custom radio button styling */
        input[type="radio"]:checked + div .radio-indicator {
            background-color: #3B82F6;
            border-color: #3B82F6;
        }
        input[type="radio"]:checked + div .radio-indicator::after {
            content: '';
            display: block;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            margin: 2px auto;
        }
        input[type="radio"]:checked + div {
            border-color: #3B82F6;
            background-color: #EFF6FF;
        }
    </style>

    <script>
        function togglePaymentDetails(radio) {
            // Hide all payment details
            document.getElementById('bank-transfer-details').classList.add('hidden');
            document.getElementById('e-wallet-details').classList.add('hidden');
            document.getElementById('cash-details').classList.add('hidden');
            document.getElementById('upload-section').classList.add('hidden');

            // Show selected payment details
            switch(radio.value) {
                case 'bank_transfer':
                    document.getElementById('bank-transfer-details').classList.remove('hidden');
                    document.getElementById('upload-section').classList.remove('hidden');
                    break;
                case 'e_wallet':
                    document.getElementById('e-wallet-details').classList.remove('hidden');
                    document.getElementById('upload-section').classList.remove('hidden');
                    break;
                case 'cash':
                    document.getElementById('cash-details').classList.remove('hidden');
                    break;
            }
        }

        // Auto-submit form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const paymentMethod = document.querySelector('input[name="metode_pembayaran"]:checked');
                if (!paymentMethod) {
                    e.preventDefault();
                    alert('Silakan pilih metode pembayaran');
                    return false;
                }

                const agreeTerms = document.querySelector('input[name="agree_payment_terms"]:checked');
                if (!agreeTerms) {
                    e.preventDefault();
                    alert('Silakan setujui syarat pembayaran');
                    return false;
                }

                return true;
            });
        });
    </script>
    @endpush
</x-layouts.penyewa-app>

