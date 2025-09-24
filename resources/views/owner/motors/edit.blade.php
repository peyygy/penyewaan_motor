@extends('layouts.owner-dashboard')

@section('title', 'Edit Motor')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Motor</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi motor {{ $motor->merk }} {{ $motor->model }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('owner.motors.show', $motor->id) }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-eye"></i> Lihat
            </a>
            <a href="{{ route('owner.motors.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Main Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('owner.motors.update', $motor->id) }}" method="POST" enctype="multipart/form-data" id="motorForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Column 1 -->
                <div class="space-y-4">
                    <!-- Merk -->
                    <div class="form-group">
                        <label for="merk" class="block text-sm font-medium text-gray-700 mb-2">
                            Merk Motor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="merk" 
                               id="merk" 
                               value="{{ old('merk', $motor->merk) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('merk') border-red-500 @enderror" 
                               placeholder="Contoh: Honda, Yamaha, Suzuki"
                               required>
                        @error('merk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div class="form-group">
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                            Model Motor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="model" 
                               id="model" 
                               value="{{ old('model', $motor->model) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('model') border-red-500 @enderror" 
                               placeholder="Contoh: Beat, Vario, Address"
                               required>
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun -->
                    <div class="form-group">
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun Pembuatan <span class="text-red-500">*</span>
                        </label>
                        <select name="tahun" 
                                id="tahun" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tahun') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Tahun</option>
                            @for($i = date('Y'); $i >= 2010; $i--)
                                <option value="{{ $i }}" {{ old('tahun', $motor->tahun) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe -->
                    <div class="form-group">
                        <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Motor <span class="text-red-500">*</span>
                        </label>
                        <select name="tipe" 
                                id="tipe" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tipe') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Tipe</option>
                            @foreach(\App\Enums\MotorType::cases() as $tipe)
                                <option value="{{ $tipe->value }}" {{ old('tipe', $motor->tipe->value) == $tipe->value ? 'selected' : '' }}>
                                    {{ $tipe->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipe')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Plat Nomor -->
                    <div class="form-group">
                        <label for="plat_nomor" class="block text-sm font-medium text-gray-700 mb-2">
                            Plat Nomor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="plat_nomor" 
                               id="plat_nomor" 
                               value="{{ old('plat_nomor', $motor->plat_nomor) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('plat_nomor') border-red-500 @enderror" 
                               placeholder="Contoh: B 1234 XYZ"
                               required>
                        @error('plat_nomor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warna -->
                    <div class="form-group">
                        <label for="warna" class="block text-sm font-medium text-gray-700 mb-2">
                            Warna Motor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="warna" 
                               id="warna" 
                               value="{{ old('warna', $motor->warna) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('warna') border-red-500 @enderror" 
                               placeholder="Contoh: Merah, Hitam, Putih"
                               required>
                        @error('warna')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="space-y-4">
                    <!-- Foto Motor -->
                    <div class="form-group">
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Motor
                        </label>
                        
                        @if($motor->foto)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $motor->foto) }}" 
                                     alt="Foto saat ini"
                                     class="w-48 h-32 object-cover rounded-lg border shadow-sm">
                                <p class="text-sm text-gray-600 mt-1">Foto saat ini</p>
                            </div>
                        @endif
                        
                        <input type="file" 
                               name="foto" 
                               id="foto" 
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('foto') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-600">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</p>
                        @if($motor->foto)
                            <p class="text-sm text-gray-600">Biarkan kosong jika tidak ingin mengubah foto.</p>
                        @endif
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Motor
                        </label>
                        <textarea name="deskripsi" 
                                  id="deskripsi" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('deskripsi') border-red-500 @enderror" 
                                  placeholder="Deskripsikan kondisi, fitur, atau informasi tambahan motor">{{ old('deskripsi', $motor->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tarif Rental Section -->
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tarif Rental</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Tarif Harian -->
                    <div class="form-group">
                        <label for="tarif_harian" class="block text-sm font-medium text-gray-700 mb-2">
                            Tarif Harian (per hari) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" 
                                   name="tarif_harian" 
                                   id="tarif_harian" 
                                   value="{{ old('tarif_harian', $motor->tarifRental?->tarif_harian) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tarif_harian') border-red-500 @enderror" 
                                   placeholder="100000"
                                   min="0"
                                   step="1000"
                                   required>
                        </div>
                        @error('tarif_harian')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tarif Mingguan -->
                    <div class="form-group">
                        <label for="tarif_mingguan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tarif Mingguan (per minggu) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" 
                                   name="tarif_mingguan" 
                                   id="tarif_mingguan" 
                                   value="{{ old('tarif_mingguan', $motor->tarifRental?->tarif_mingguan) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tarif_mingguan') border-red-500 @enderror" 
                                   placeholder="600000"
                                   min="0"
                                   step="1000"
                                   required>
                        </div>
                        @error('tarif_mingguan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tarif Bulanan -->
                    <div class="form-group">
                        <label for="tarif_bulanan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tarif Bulanan (per bulan) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" 
                                   name="tarif_bulanan" 
                                   id="tarif_bulanan" 
                                   value="{{ old('tarif_bulanan', $motor->tarifRental?->tarif_bulanan) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tarif_bulanan') border-red-500 @enderror" 
                                   placeholder="2000000"
                                   min="0"
                                   step="1000"
                                   required>
                        </div>
                        @error('tarif_bulanan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" 
                        onclick="window.history.back()" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    <i class="fas fa-save"></i> Perbarui Motor
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto calculate weekly and monthly rates based on daily rate
    const dailyRate = document.getElementById('tarif_harian');
    const weeklyRate = document.getElementById('tarif_mingguan');
    const monthlyRate = document.getElementById('tarif_bulanan');

    dailyRate.addEventListener('input', function() {
        const daily = parseInt(this.value) || 0;
        if (daily > 0) {
            // Weekly rate: daily * 6 (1 day discount)
            weeklyRate.value = daily * 6;
            // Monthly rate: daily * 25 (5 days discount)
            monthlyRate.value = daily * 25;
        }
    });

    // Form validation
    document.getElementById('motorForm').addEventListener('submit', function(e) {
        const requiredFields = ['merk', 'model', 'tahun', 'tipe', 'plat_nomor', 'warna', 'tarif_harian', 'tarif_mingguan', 'tarif_bulanan'];
        let isValid = true;

        requiredFields.forEach(function(fieldName) {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
        }
    });
});
</script>
@endsection