<x-layouts.admin-dashboard>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-900">Tambah Tarif Baru</h2>
            <a href="{{ route('admin.tarif.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Kembali ke Daftar Tarif
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.tarif.store') }}">
            @csrf

            <!-- Motor Selection -->
            <div class="mb-6">
                <label for="motor_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Motor *
                </label>
                <select name="motor_id" id="motor_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Pilih Motor --</option>
                    @foreach($motors ?? [] as $motor)
                    <option value="{{ $motor->id }}" {{ old('motor_id') == $motor->id ? 'selected' : '' }}>
                        {{ $motor->merk ?? 'N/A' }} {{ $motor->tipe ?? '' }} - {{ $motor->no_plat ?? 'N/A' }}
                    </option>
                    @endforeach
                </select>
                @error('motor_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga per Hari -->
            <div class="mb-6">
                <label for="harga_per_hari" class="block text-sm font-medium text-gray-700 mb-2">
                    Harga per Hari (Rp) *
                </label>
                <input type="number" name="harga_per_hari" id="harga_per_hari" min="0" step="1000"
                       value="{{ old('harga_per_hari') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       placeholder="50000" required>
                @error('harga_per_hari')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga per Minggu -->
            <div class="mb-6">
                <label for="harga_per_minggu" class="block text-sm font-medium text-gray-700 mb-2">
                    Harga per Minggu (Rp) *
                </label>
                <input type="number" name="harga_per_minggu" id="harga_per_minggu" min="0" step="1000"
                       value="{{ old('harga_per_minggu') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       placeholder="300000" required>
                @error('harga_per_minggu')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga per Bulan -->
            <div class="mb-6">
                <label for="harga_per_bulan" class="block text-sm font-medium text-gray-700 mb-2">
                    Harga per Bulan (Rp) *
                </label>
                <input type="number" name="harga_per_bulan" id="harga_per_bulan" min="0" step="1000"
                       value="{{ old('harga_per_bulan') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       placeholder="1000000" required>
                @error('harga_per_bulan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.tarif.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                    Simpan Tarif
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin-dashboard>