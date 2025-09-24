@extends('layouts.owner-dashboard')

@section('title', 'Daftarkan Motor Baru')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Daftarkan Motor Baru</h1>
            <p class="text-gray-600 mt-1">Lengkapi informasi motor Anda untuk didaftarkan ke sistem</p>
        </div>
        <a href="{{ route('owner.motors.index') }}" 
           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Main Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('owner.motors.store') }}" method="POST" enctype="multipart/form-data" id="motorForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Column 1 -->
                <div class="space-y-4">
                    <!-- Merk -->
                    <div class="form-group">
                        <label for="merk" class="block text-sm font-medium text-gray-700 mb-2">
                            Merk Motor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="merk" id="merk" value="{{ old('merk') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('merk') border-red-500 @enderror"
                               placeholder="Honda, Yamaha, Suzuki, dll..." required>
                        @error('merk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div class="form-group">
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                            Model Motor
                        </label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('model') border-red-500 @enderror"
                               placeholder="Vario, Beat, Mio, dll...">
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun -->
                    <div class="form-group">
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun Pembuatan
                        </label>
                        <input type="number" name="tahun" id="tahun" value="{{ old('tahun') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tahun') border-red-500 @enderror"
                               min="2000" max="{{ date('Y') + 1 }}" placeholder="2020">
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No Plat -->
                    <div class="form-group">
                        <label for="no_plat" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Plat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_plat" id="no_plat" value="{{ old('no_plat') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('no_plat') border-red-500 @enderror"
                               placeholder="B 1234 ABC" style="text-transform: uppercase;" required>
                        @error('no_plat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe CC -->
                    <div class="form-group">
                        <label for="tipe_cc" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe CC <span class="text-red-500">*</span>
                        </label>
                        <select name="tipe_cc" id="tipe_cc" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tipe_cc') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Tipe CC</option>
                            @foreach(\App\Enums\MotorType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('tipe_cc') == $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipe_cc')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warna -->
                    <div class="form-group">
                        <label for="warna" class="block text-sm font-medium text-gray-700 mb-2">
                            Warna Motor
                        </label>
                        <input type="text" name="warna" id="warna" value="{{ old('warna') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('warna') border-red-500 @enderror"
                               placeholder="Merah, Biru, Hitam, dll...">
                        @error('warna')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="space-y-4">
                    <!-- Harga Harian -->
                    <div class="form-group">
                        <label for="harga_per_hari" class="block text-sm font-medium text-gray-700 mb-2">
                            Tarif Harian <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp</span>
                            <input type="number" name="harga_per_hari" id="harga_per_hari" value="{{ old('harga_per_hari') }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('harga_per_hari') border-red-500 @enderror"
                                   min="10000" step="1000" placeholder="75000" required>
                        </div>
                        @error('harga_per_hari')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Mingguan -->
                    <div class="form-group">
                        <label for="harga_per_minggu" class="block text-sm font-medium text-gray-700 mb-2">
                            Tarif Mingguan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp</span>
                            <input type="number" name="harga_per_minggu" id="harga_per_minggu" value="{{ old('harga_per_minggu') }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('harga_per_minggu') border-red-500 @enderror"
                                   min="50000" step="5000" placeholder="500000" required>
                        </div>
                        @error('harga_per_minggu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Bulanan -->
                    <div class="form-group">
                        <label for="harga_per_bulan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tarif Bulanan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp</span>
                            <input type="number" name="harga_per_bulan" id="harga_per_bulan" value="{{ old('harga_per_bulan') }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('harga_per_bulan') border-red-500 @enderror"
                                   min="200000" step="10000" placeholder="1800000" required>
                        </div>
                        @error('harga_per_bulan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Photo Upload -->
                    <div class="form-group">
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Motor
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload foto motor</span>
                                        <input id="photo" name="photo" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG hingga 2MB</p>
                            </div>
                        </div>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Upload -->
                    <div class="form-group">
                        <label for="dokumen_kepemilikan" class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen Kepemilikan (STNK/BPKB)
                        </label>
                        <input type="file" name="dokumen_kepemilikan" id="dokumen_kepemilikan"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('dokumen_kepemilikan') border-red-500 @enderror"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <p class="mt-1 text-xs text-gray-500">Upload STNK atau BPKB (PDF/JPG maksimal 5MB)</p>
                        @error('dokumen_kepemilikan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Tambahan
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Kondisi motor, fitur khusus, dll...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Penting:</h4>
                <ul class="text-sm text-blue-600 space-y-1">
                    <li>• Motor akan memasuki status "Menunggu Verifikasi" setelah didaftarkan</li>
                    <li>• Admin akan memverifikasi dokumen dan data motor Anda</li>
                    <li>• Setelah diverifikasi, motor akan tersedia untuk disewa</li>
                    <li>• Pastikan dokumen dan foto yang diupload jelas dan valid</li>
                </ul>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('owner.motors.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium">
                    <i class="fas fa-save"></i> Daftarkan Motor
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hargaHarian = document.getElementById('harga_per_hari');
    const hargaMingguan = document.getElementById('harga_per_minggu');
    const hargaBulanan = document.getElementById('harga_per_bulan');

    // Auto calculate weekly and monthly rates based on daily rate
    hargaHarian.addEventListener('input', function() {
        const harian = parseInt(this.value) || 0;
        
        // Weekly: Daily rate * 6.5 days (10% discount)
        const mingguan = Math.round(harian * 6.5);
        hargaMingguan.value = mingguan;
        
        // Monthly: Daily rate * 25 days (17% discount) 
        const bulanan = Math.round(harian * 25);
        hargaBulanan.value = bulanan;
    });

    // Photo preview
    const photoInput = document.getElementById('photo');
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add preview functionality here
                console.log('Photo selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    document.getElementById('motorForm').addEventListener('submit', function(e) {
        const requiredFields = ['merk', 'no_plat', 'tipe_cc', 'harga_per_hari', 'harga_per_minggu', 'harga_per_bulan'];
        let valid = true;

        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                valid = false;
                input.classList.add('border-red-500');
            } else {
                input.classList.remove('border-red-500');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi!');
            return false;
        }

        // Validate pricing
        const harian = parseInt(hargaHarian.value) || 0;
        const mingguan = parseInt(hargaMingguan.value) || 0;
        const bulanan = parseInt(hargaBulanan.value) || 0;

        if (harian < 10000) {
            e.preventDefault();
            alert('Tarif harian minimal Rp 10.000');
            return false;
        }

        if (mingguan < harian * 5) {
            e.preventDefault();
            alert('Tarif mingguan harus lebih menguntungkan dari tarif harian x 7');
            return false;
        }

        if (bulanan < harian * 20) {
            e.preventDefault();
            alert('Tarif bulanan harus lebih menguntungkan dari tarif harian x 30');
            return false;
        }
    });
});
</script>
@endsection