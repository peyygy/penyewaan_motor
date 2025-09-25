@extends('layouts.app')

@section('title', 'Edit Motor')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil"></i> Edit Motor: {{ $motor->merk }} - {{ $motor->no_plat }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.motors.update', $motor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
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
                                            <option value="{{ $id }}" {{ (old('pemilik_id', $motor->pemilik_id) == $id) ? 'selected' : '' }}>
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
                                           value="{{ old('merk', $motor->merk) }}" placeholder="Contoh: Honda, Yamaha" required>
                                    @error('merk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="tipe_cc" class="form-label">Tipe CC <span class="text-danger">*</span></label>
                                    <select name="tipe_cc" id="tipe_cc" class="form-select @error('tipe_cc') is-invalid @enderror" required>
                                        <option value="">Pilih Tipe CC</option>
                                        <option value="100" {{ old('tipe_cc', $motor->tipe_cc) == '100' ? 'selected' : '' }}>100cc</option>
                                        <option value="125" {{ old('tipe_cc', $motor->tipe_cc) == '125' ? 'selected' : '' }}>125cc</option>
                                        <option value="150" {{ old('tipe_cc', $motor->tipe_cc) == '150' ? 'selected' : '' }}>150cc</option>
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
                                           value="{{ old('no_plat', $motor->no_plat) }}" placeholder="Contoh: B 1234 ABC" required>
                                    @error('no_plat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status Motor <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="">Pilih Status</option>
                                        <option value="pending" {{ old('status', $motor->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ old('status', $motor->status) == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="available" {{ old('status', $motor->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="maintenance" {{ old('status', $motor->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="rented" {{ old('status', $motor->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Foto Saat Ini -->
                        @if($motor->foto)
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-image"></i> Foto Motor Saat Ini
                            </h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="{{ asset('storage/' . $motor->foto) }}" class="img-thumbnail" alt="Foto Motor">
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted mb-2">Foto motor yang sedang digunakan.</p>
                                    <small class="text-info">Upload foto baru jika ingin menggantinya.</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Upload File -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-cloud-upload"></i> Upload Dokumen Baru (Opsional)
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="foto" class="form-label">Foto Motor</label>
                                    <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                    <small class="form-text text-muted">Format: JPG, PNG, JPEG. Maksimal 5MB</small>
                                    @error('foto')
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

                        <!-- Informasi Tarif -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-currency-dollar"></i> Tarif Rental Motor
                            </h6>
                            @if($motor->tarifRental)
                            <div class="alert alert-info mb-3">
                                <i class="bi bi-info-circle"></i> 
                                Tarif saat ini: Harian Rp{{ number_format($motor->tarifRental->tarif_harian) }}, 
                                Mingguan Rp{{ number_format($motor->tarifRental->tarif_mingguan) }}, 
                                Bulanan Rp{{ number_format($motor->tarifRental->tarif_bulanan) }}
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="tarif_harian" class="form-label">Tarif Harian</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="tarif_harian" id="tarif_harian" 
                                               class="form-control @error('tarif_harian') is-invalid @enderror" 
                                               value="{{ old('tarif_harian', $motor->tarifRental->tarif_harian ?? '') }}" placeholder="0">
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
                                               value="{{ old('tarif_mingguan', $motor->tarifRental->tarif_mingguan ?? '') }}" placeholder="0">
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
                                               value="{{ old('tarif_bulanan', $motor->tarifRental->tarif_bulanan ?? '') }}" placeholder="0">
                                    </div>
                                    @error('tarif_bulanan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i> Kosongkan jika tidak ingin mengubah tarif
                            </small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.motors.show', $motor->id) }}" class="btn btn-info">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('admin.motors.index') }}" class="btn btn-secondary me-2">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Motor
                                </button>
                            </div>
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
    document.getElementById('foto').addEventListener('change', function(e) {
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
            const mingguanField = document.getElementById('tarif_mingguan');
            const bulananField = document.getElementById('tarif_bulanan');
            
            // Jika field kosong, hitung otomatis
            if (!mingguanField.value) {
                mingguanField.value = Math.round(harianValue * 6);
            }
            if (!bulananField.value) {
                bulananField.value = Math.round(harianValue * 25);
            }
        }
    });

    // Konfirmasi perubahan status
    document.getElementById('status').addEventListener('change', function() {
        const newStatus = this.value;
        const currentStatus = '{{ $motor->status }}';
        
        if (newStatus !== currentStatus && newStatus === 'maintenance') {
            if (!confirm('Motor akan diubah ke status maintenance. Lanjutkan?')) {
                this.value = currentStatus;
            }
        }
    });
</script>
@endpush
@endsection
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