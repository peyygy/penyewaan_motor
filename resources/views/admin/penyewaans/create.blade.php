<x-layouts.admin-dashboard>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Buat Penyewaan Baru</h2>
                <p class="text-gray-600">Tambah penyewaan motor untuk penyewa</p>
            </div>
            <a href="{{ route('admin.penyewaans.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.penyewaans.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Penyewa Selection -->
                <div>
                    <label for="penyewa_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Penyewa <span class="text-red-500">*</span>
                    </label>
                    <select name="penyewa_id" id="penyewa_id" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Penyewa</option>
                        @foreach($penyewas as $penyewa)
                            <option value="{{ $penyewa->id }}" {{ old('penyewa_id') == $penyewa->id ? 'selected' : '' }}>
                                {{ $penyewa->nama }} - {{ $penyewa->no_tlpn }}
                            </option>
                        @endforeach
                    </select>
                    @error('penyewa_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Motor Selection -->
                <div>
                    <label for="motor_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Motor Tersedia <span class="text-red-500">*</span>
                    </label>
                    <select name="motor_id" id="motor_id" required onchange="updateTariff()"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Motor</option>
                        @foreach($motors as $motor)
                            <option value="{{ $motor->id }}" 
                                    data-tarif-harian="{{ $motor->tarif?->tarif_harian ?? 0 }}"
                                    data-tarif-mingguan="{{ $motor->tarif?->tarif_mingguan ?? 0 }}"
                                    data-tarif-bulanan="{{ $motor->tarif?->tarif_bulanan ?? 0 }}"
                                    {{ old('motor_id') == $motor->id ? 'selected' : '' }}>
                                {{ $motor->merk }} {{ $motor->model }} - {{ $motor->no_plat }}
                                @if($motor->tarif)
                                    (Rp {{ number_format($motor->tarif->tarif_harian, 0, ',', '.') }}/hari)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('motor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" required
                           value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}" onchange="calculatePrice()"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                           value="{{ old('tanggal_selesai') }}" onchange="calculatePrice()"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Durasi -->
                <div>
                    <label for="tipe_durasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Durasi <span class="text-red-500">*</span>
                    </label>
                    <select name="tipe_durasi" id="tipe_durasi" required onchange="calculatePrice()"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Tipe Durasi</option>
                        <option value="harian" {{ old('tipe_durasi') == 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="mingguan" {{ old('tipe_durasi') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                        <option value="bulanan" {{ old('tipe_durasi') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                    @error('tipe_durasi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi Harga</label>
                    <div class="w-full p-3 bg-gray-50 rounded-md border border-gray-300">
                        <div id="price-display" class="text-lg font-semibold text-gray-900">
                            Pilih motor dan tanggal untuk melihat harga
                        </div>
                        <div id="duration-display" class="text-sm text-gray-600 mt-1">
                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                <button type="button" onclick="window.history.back()" 
                        class="mr-3 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Buat Penyewaan
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin-dashboard>

<script>
function updateTariff() {
    calculatePrice();
}

function calculatePrice() {
    const motorSelect = document.getElementById('motor_id');
    const startDate = document.getElementById('tanggal_mulai').value;
    const endDate = document.getElementById('tanggal_selesai').value;
    const durationType = document.getElementById('tipe_durasi').value;
    const priceDisplay = document.getElementById('price-display');
    const durationDisplay = document.getElementById('duration-display');
    
    if (!motorSelect.value || !startDate || !endDate || !durationType) {
        priceDisplay.textContent = 'Pilih motor dan tanggal untuk melihat harga';
        durationDisplay.textContent = '';
        return;
    }
    
    const selectedOption = motorSelect.selectedOptions[0];
    const tarifHarian = parseInt(selectedOption.dataset.tarifHarian) || 0;
    const tarifMingguan = parseInt(selectedOption.dataset.tarifMingguan) || 0;
    const tarifBulanan = parseInt(selectedOption.dataset.tarifBulanan) || 0;
    
    if (tarifHarian === 0) {
        priceDisplay.textContent = 'Motor belum memiliki tarif';
        durationDisplay.textContent = '';
        return;
    }
    
    // Calculate days
    const start = new Date(startDate);
    const end = new Date(endDate);
    const timeDiff = end.getTime() - start.getTime();
    const days = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
    
    if (days <= 0) {
        priceDisplay.textContent = 'Tanggal tidak valid';
        durationDisplay.textContent = '';
        return;
    }
    
    let totalPrice = 0;
    let durationText = '';
    
    switch (durationType) {
        case 'harian':
            totalPrice = tarifHarian * days;
            durationText = `${days} hari`;
            break;
        case 'mingguan':
            const weeks = Math.ceil(days / 7);
            totalPrice = tarifMingguan * weeks;
            durationText = `${weeks} minggu (${days} hari)`;
            break;
        case 'bulanan':
            const months = Math.ceil(days / 30);
            totalPrice = tarifBulanan * months;
            durationText = `${months} bulan (${days} hari)`;
            break;
    }
    
    priceDisplay.textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
    durationDisplay.textContent = durationText;
}

// Update minimum end date when start date changes
document.getElementById('tanggal_mulai').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('tanggal_selesai');
    endDateInput.min = startDate;
    
    // If end date is before start date, reset it
    if (endDateInput.value && endDateInput.value < startDate) {
        endDateInput.value = startDate;
    }
    
    calculatePrice();
});
</script>