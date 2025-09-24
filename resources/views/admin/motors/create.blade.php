<x-layouts.admin-dashboard>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tambah Motor</h2>
                <p class="text-gray-600">Tambah motor baru ke dalam sistem</p>
            </div>
            <a href="{{ route('admin.motors.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Motor Form -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form method="POST" action="{{ route('admin.motors.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Owner -->
                <div>
                    <label for="pemilik_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pemilik Motor <span class="text-red-500">*</span>
                    </label>
                    <select id="pemilik_id" name="pemilik_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pemilik_id') border-red-500 @enderror">
                        <option value="">Pilih Pemilik</option>
                        @foreach($owners as $id => $name)
                            <option value="{{ $id }}" {{ old('pemilik_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('pemilik_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Brand -->
                <div>
                    <label for="merk" class="block text-sm font-medium text-gray-700 mb-2">
                        Merk Motor <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="merk" name="merk" value="{{ old('merk') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('merk') border-red-500 @enderror">
                    @error('merk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                        Model Motor <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="model" name="model" value="{{ old('model') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('model') border-red-500 @enderror">
                    @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year -->
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="tahun" name="tahun" value="{{ old('tahun') }}" min="1990" max="2025" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tahun') border-red-500 @enderror">
                    @error('tahun')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License Plate -->
                <div>
                    <label for="plat_nomor" class="block text-sm font-medium text-gray-700 mb-2">
                        Plat Nomor <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="plat_nomor" name="plat_nomor" value="{{ old('plat_nomor') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plat_nomor') border-red-500 @enderror">
                    @error('plat_nomor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Motor <span class="text-red-500">*</span>
                    </label>
                    <select id="tipe" name="tipe" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tipe') border-red-500 @enderror">
                        <option value="">Pilih Tipe</option>
                        <option value="matic" {{ old('tipe') == 'matic' ? 'selected' : '' }}>Matic</option>
                        <option value="manual" {{ old('tipe') == 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="sport" {{ old('tipe') == 'sport' ? 'selected' : '' }}>Sport</option>
                    </select>
                    @error('tipe')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Engine CC -->
                <div>
                    <label for="cc_mesin" class="block text-sm font-medium text-gray-700 mb-2">
                        CC Mesin
                    </label>
                    <input type="number" id="cc_mesin" name="cc_mesin" value="{{ old('cc_mesin') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cc_mesin') border-red-500 @enderror">
                    @error('cc_mesin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label for="warna" class="block text-sm font-medium text-gray-700 mb-2">
                        Warna
                    </label>
                    <input type="text" id="warna" name="warna" value="{{ old('warna') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('warna') border-red-500 @enderror">
                    @error('warna')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Daily Rate -->
                <div>
                    <label for="harga_per_hari" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga per Hari (Rp)
                    </label>
                    <input type="number" id="harga_per_hari" name="harga_per_hari" value="{{ old('harga_per_hari') }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('harga_per_hari') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Opsional - akan dibuat tarif otomatis jika diisi</p>
                    @error('harga_per_hari')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Motor
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.motors.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Simpan Motor
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin-dashboard>