@extends('layouts.admin-dashboard')

@section('title', 'Edit Motor')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Motor</h1>
            <p class="text-gray-600 mt-1">Ubah informasi motor dan tarif</p>
        </div>
        <a href="{{ route('admin.motors.show', $motor->id) }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.motors.update', $motor->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column - Motor Information -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Motor</h3>
                    
                    <!-- Brand -->
                    <div class="mb-4">
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Merek Motor *</label>
                        <input type="text" id="brand" name="brand" 
                               value="{{ old('brand', $motor->merk) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('brand') border-red-500 @enderror"
                               required>
                        @error('brand')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div class="mb-4">
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model Motor *</label>
                        <input type="text" id="model" name="model" 
                               value="{{ old('model', $motor->model) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('model') border-red-500 @enderror"
                               required>
                        @error('model')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- License Plate -->
                    <div class="mb-4">
                        <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-2">Plat Nomor *</label>
                        <input type="text" id="license_plate" name="license_plate" 
                               value="{{ old('license_plate', $motor->no_plat) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('license_plate') border-red-500 @enderror"
                               required>
                        @error('license_plate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Motor *</label>
                        <select id="type" name="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Tipe Motor</option>
                            @foreach(\App\Enums\MotorType::cases() as $type)
                                <option value="{{ $type->value }}" 
                                        {{ old('type', $motor->tipe?->value) === $type->value ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year -->
                    <div class="mb-4">
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <input type="number" id="year" name="year" 
                               value="{{ old('year', $motor->year) }}"
                               min="1990" max="{{ date('Y') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('year') border-red-500 @enderror">
                        @error('year')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Owner -->
                    <div class="mb-4">
                        <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-2">Pemilik Motor *</label>
                        <select id="owner_id" name="owner_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('owner_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Pemilik</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" 
                                        {{ old('owner_id', $motor->owner_id) == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }} ({{ $owner->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('owner_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Motor *</label>
                        <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                                required>
                            @foreach(\App\Enums\MotorStatus::cases() as $status)
                                <option value="{{ $status->value }}" 
                                        {{ old('status', $motor->status?->value) === $status->value ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Deskripsi tambahan tentang motor...">{{ old('description', $motor->deskripsi) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column - Tarif & Photo -->
            <div class="space-y-6">
                <!-- Tarif Section -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tarif Sewa</h3>
                    
                    <!-- Harga Per Hari -->
                    <div class="mb-4">
                        <label for="harga_per_hari" class="block text-sm font-medium text-gray-700 mb-2">Harga Per Hari *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" id="harga_per_hari" name="harga_per_hari" 
                                   value="{{ old('harga_per_hari', $motor->tarif?->harga_per_hari) }}"
                                   min="0" step="1000"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('harga_per_hari') border-red-500 @enderror"
                                   required>
                        </div>
                        @error('harga_per_hari')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Per Minggu -->
                    <div class="mb-4">
                        <label for="harga_per_minggu" class="block text-sm font-medium text-gray-700 mb-2">Harga Per Minggu</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" id="harga_per_minggu" name="harga_per_minggu" 
                                   value="{{ old('harga_per_minggu', $motor->tarif?->harga_per_minggu) }}"
                                   min="0" step="1000"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('harga_per_minggu') border-red-500 @enderror">
                        </div>
                        @error('harga_per_minggu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Per Bulan -->
                    <div class="mb-4">
                        <label for="harga_per_bulan" class="block text-sm font-medium text-gray-700 mb-2">Harga Per Bulan</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" id="harga_per_bulan" name="harga_per_bulan" 
                                   value="{{ old('harga_per_bulan', $motor->tarif?->harga_per_bulan) }}"
                                   min="0" step="1000"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('harga_per_bulan') border-red-500 @enderror">
                        </div>
                        @error('harga_per_bulan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deposit -->
                    <div class="mb-4">
                        <label for="deposit" class="block text-sm font-medium text-gray-700 mb-2">Deposit</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" id="deposit" name="deposit" 
                                   value="{{ old('deposit', $motor->tarif?->deposit) }}"
                                   min="0" step="10000"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deposit') border-red-500 @enderror">
                        </div>
                        @error('deposit')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Photo Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Motor</h3>
                    
                    <!-- Current Photo -->
                    @if($motor->foto)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                        <div class="w-full h-48 bg-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ Storage::url($motor->foto) }}" alt="Current motor photo" class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endif

                    <!-- New Photo Upload -->
                    <div class="mb-4">
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $motor->foto ? 'Ganti Foto' : 'Upload Foto' }}
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('photo') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                        @error('photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.motors.show', $motor->id) }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
// Auto calculate weekly and monthly prices based on daily price
document.getElementById('harga_per_hari').addEventListener('input', function() {
    const dailyPrice = parseFloat(this.value) || 0;
    const weeklyInput = document.getElementById('harga_per_minggu');
    const monthlyInput = document.getElementById('harga_per_bulan');
    
    if (dailyPrice > 0) {
        // Calculate with discount (weekly: 5% discount, monthly: 15% discount)
        if (!weeklyInput.value) {
            weeklyInput.value = Math.round(dailyPrice * 7 * 0.95);
        }
        if (!monthlyInput.value) {
            monthlyInput.value = Math.round(dailyPrice * 30 * 0.85);
        }
    }
});
</script>
@endsection