@extends('layouts.admin-dashboard')

@section('title', 'Edit Penyewaan')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Penyewaan</h1>
            <p class="text-gray-600 mt-1">Edit detail penyewaan motor</p>
        </div>
        <a href="{{ route('admin.penyewaans.index') }}" 
           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Main Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.penyewaans.update', $penyewaan->id) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Column 1 -->
                <div class="space-y-4">
                    <!-- Renter -->
                    <div class="form-group">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Penyewa <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id" id="user_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('user_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Penyewa</option>
                            @foreach($renters as $renter)
                                <option value="{{ $renter->id }}" 
                                        {{ old('user_id', $penyewaan->user_id) == $renter->id ? 'selected' : '' }}>
                                    {{ $renter->nama }} ({{ $renter->no_tlpn }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Motor -->
                    <div class="form-group">
                        <label for="motor_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Motor <span class="text-red-500">*</span>
                        </label>
                        <select name="motor_id" id="motor_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('motor_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Motor</option>
                            @foreach($motors as $motor)
                                <option value="{{ $motor->id }}" 
                                        data-harga="{{ $motor->tarifRental->harga_per_hari ?? 0 }}"
                                        {{ old('motor_id', $penyewaan->motor_id) == $motor->id ? 'selected' : '' }}>
                                    {{ $motor->nama_motor }} - {{ $motor->no_plat }}
                                    ({{ $motor->tipe_cc->label() }})
                                </option>
                            @endforeach
                        </select>
                        @error('motor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="form-group">
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                               value="{{ old('tanggal_mulai', $penyewaan->tanggal_mulai->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_mulai') border-red-500 @enderror"
                               required>
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="form-group">
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                               value="{{ old('tanggal_selesai', $penyewaan->tanggal_selesai->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_selesai') border-red-500 @enderror"
                               required>
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror"
                                required>
                            @foreach(\App\Enums\BookingStatus::cases() as $status)
                                <option value="{{ $status->value }}" 
                                        {{ old('status', $penyewaan->status->value) == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="space-y-4">
                    <!-- Total Hari -->
                    <div class="form-group">
                        <label for="total_hari" class="block text-sm font-medium text-gray-700 mb-2">
                            Total Hari
                        </label>
                        <input type="number" name="total_hari" id="total_hari" 
                               value="{{ old('total_hari', $penyewaan->total_hari) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                               readonly>
                    </div>

                    <!-- Harga per Hari -->
                    <div class="form-group">
                        <label for="harga_per_hari" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga per Hari
                        </label>
                        <input type="text" name="harga_per_hari_display" id="harga_per_hari_display" 
                               value="Rp {{ number_format($penyewaan->harga_per_hari, 0, ',', '.') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                               readonly>
                        <input type="hidden" name="harga_per_hari" id="harga_per_hari" value="{{ $penyewaan->harga_per_hari }}">
                    </div>

                    <!-- Total Harga -->
                    <div class="form-group">
                        <label for="total_harga" class="block text-sm font-medium text-gray-700 mb-2">
                            Total Harga <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="total_harga_display" id="total_harga_display" 
                               value="Rp {{ number_format($penyewaan->total_harga, 0, ',', '.') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                               readonly>
                        <input type="hidden" name="total_harga" id="total_harga" value="{{ $penyewaan->total_harga }}">
                    </div>

                    <!-- Catatan -->
                    <div class="form-group">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea name="catatan" id="catatan" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('catatan') border-red-500 @enderror"
                                  placeholder="Catatan tambahan...">{{ old('catatan', $penyewaan->catatan) }}</textarea>
                        @error('catatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi:</h4>
                        <ul class="text-xs text-blue-600 space-y-1">
                            <li>• Total hari dan harga akan dihitung otomatis</li>
                            <li>• Status dapat diubah sesuai kondisi penyewaan</li>
                            <li>• Pastikan tanggal selesai lebih besar dari tanggal mulai</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.penyewaans.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium">
                    <i class="fas fa-save"></i> Update Penyewaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const motorSelect = document.getElementById('motor_id');
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    const totalHari = document.getElementById('total_hari');
    const hargaPerHari = document.getElementById('harga_per_hari');
    const hargaPerHariDisplay = document.getElementById('harga_per_hari_display');
    const totalHarga = document.getElementById('total_harga');
    const totalHargaDisplay = document.getElementById('total_harga_display');

    // Function to calculate days
    function hitungHari() {
        if (tanggalMulai.value && tanggalSelesai.value) {
            const mulai = new Date(tanggalMulai.value);
            const selesai = new Date(tanggalSelesai.value);
            const diffTime = selesai - mulai;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                totalHari.value = diffDays;
                hitungTotalHarga();
            } else {
                totalHari.value = 0;
                alert('Tanggal selesai harus lebih besar dari tanggal mulai!');
            }
        }
    }

    // Function to calculate total price
    function hitungTotalHarga() {
        const hari = parseInt(totalHari.value) || 0;
        const harga = parseInt(hargaPerHari.value) || 0;
        const total = hari * harga;
        
        totalHarga.value = total;
        totalHargaDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    // Motor selection change
    motorSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = selectedOption.dataset.harga || 0;
        
        hargaPerHari.value = harga;
        hargaPerHariDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
        
        hitungTotalHarga();
    });

    // Date change events
    tanggalMulai.addEventListener('change', hitungHari);
    tanggalSelesai.addEventListener('change', hitungHari);

    // Form validation
    document.getElementById('editForm').addEventListener('submit', function(e) {
        const userId = document.getElementById('user_id').value;
        const motorId = document.getElementById('motor_id').value;
        const mulai = document.getElementById('tanggal_mulai').value;
        const selesai = document.getElementById('tanggal_selesai').value;
        const status = document.getElementById('status').value;

        if (!userId || !motorId || !mulai || !selesai || !status) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi!');
            return false;
        }

        if (new Date(selesai) <= new Date(mulai)) {
            e.preventDefault();
            alert('Tanggal selesai harus lebih besar dari tanggal mulai!');
            return false;
        }

        // Confirm update
        if (!confirm('Apakah Anda yakin ingin mengupdate data penyewaan ini?')) {
            e.preventDefault();
            return false;
        }
    });

    // Initialize calculations on load
    hitungHari();
});
</script>
@endsection