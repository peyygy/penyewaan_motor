@extends('layouts.app')

@section('title', 'Tambah Motor')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle"></i> Tambah Motor Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.motors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Informasi Pemilik -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-person-circle"></i> Informasi Pemilik
                            </h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="pemilik_id" class="form-label">Pemilik Motor <span class="text-danger">*</span></label>
                                    <select name="pemilik_id" id="pemilik_id" class="form-select @error('pemilik_id') is-invalid @enderror" required>
                                        <option value="">Pilih Pemilik Motor</option>
                                        @foreach($owners as $id => $nama)
                                            <option value="{{ $id }}" {{ old('pemilik_id') == $id ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pemilik_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Motor -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-motorcycle"></i> Informasi Motor
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="merk" class="form-label">Merk Motor <span class="text-danger">*</span></label>
                                    <input type="text" name="merk" id="merk" class="form-control @error('merk') is-invalid @enderror" 
                                           value="{{ old('merk') }}" placeholder="Contoh: Honda, Yamaha" required>
                                    @error('merk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="tipe_cc" class="form-label">Tipe CC <span class="text-danger">*</span></label>
                                    <select name="tipe_cc" id="tipe_cc" class="form-select @error('tipe_cc') is-invalid @enderror" required>
                                        <option value="">Pilih Tipe CC</option>
                                        <option value="100" {{ old('tipe_cc') == '100' ? 'selected' : '' }}>100cc</option>
                                        <option value="125" {{ old('tipe_cc') == '125' ? 'selected' : '' }}>125cc</option>
                                        <option value="150" {{ old('tipe_cc') == '150' ? 'selected' : '' }}>150cc</option>
                                    </select>
                                    @error('tipe_cc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="no_plat" class="form-label">Nomor Plat <span class="text-danger">*</span></label>
                                    <input type="text" name="no_plat" id="no_plat" class="form-control @error('no_plat') is-invalid @enderror" 
                                           value="{{ old('no_plat') }}" placeholder="Contoh: B 1234 ABC" required>
                                    @error('no_plat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status Motor <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="">Pilih Status</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Upload File -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-cloud-upload"></i> Upload Dokumen
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="photo" class="form-label">Foto Motor</label>
                                    <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                                    <small class="form-text text-muted">Format: JPG, PNG, JPEG. Maksimal 5MB</small>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="dokumen_kepemilikan" class="form-label">Dokumen Kepemilikan</label>
                                    <input type="file" name="dokumen_kepemilikan" id="dokumen_kepemilikan" 
                                           class="form-control @error('dokumen_kepemilikan') is-invalid @enderror" accept="image/*,application/pdf">
                                    <small class="form-text text-muted">Format: PDF, JPG, PNG. Maksimal 5MB</small>
                                    @error('dokumen_kepemilikan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tarif (Optional) -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-currency-dollar"></i> Tarif Rental (Opsional)
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="tarif_harian" class="form-label">Tarif Harian</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="tarif_harian" id="tarif_harian" 
                                               class="form-control @error('tarif_harian') is-invalid @enderror" 
                                               value="{{ old('tarif_harian') }}" placeholder="0">
                                    </div>
                                    @error('tarif_harian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="tarif_mingguan" class="form-label">Tarif Mingguan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="tarif_mingguan" id="tarif_mingguan" 
                                               class="form-control @error('tarif_mingguan') is-invalid @enderror" 
                                               value="{{ old('tarif_mingguan') }}" placeholder="0">
                                    </div>
                                    @error('tarif_mingguan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="tarif_bulanan" class="form-label">Tarif Bulanan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="tarif_bulanan" id="tarif_bulanan" 
                                               class="form-control @error('tarif_bulanan') is-invalid @enderror" 
                                               value="{{ old('tarif_bulanan') }}" placeholder="0">
                                    </div>
                                    @error('tarif_bulanan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i> Tarif dapat diatur nanti jika dikosongkan
                            </small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.motors.index') }}" class="btn btn-secondary me-md-2">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Motor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image saat dipilih
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validasi ukuran file (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                e.target.value = '';
                return;
            }
        }
    });

    // Auto calculate tarif mingguan dan bulanan berdasarkan tarif harian
    document.getElementById('tarif_harian').addEventListener('input', function() {
        const harianValue = parseFloat(this.value) || 0;
        if (harianValue > 0) {
            // Tarif mingguan = harian x 6 (diskon 1 hari)
            document.getElementById('tarif_mingguan').value = Math.round(harianValue * 6);
            // Tarif bulanan = harian x 25 (diskon 5 hari)
            document.getElementById('tarif_bulanan').value = Math.round(harianValue * 25);
        }
    });
</script>
@endpush
@endsection

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