@extends('layouts.app')

@section('title', 'Detail Motor')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="bi bi-motorcycle"></i> Detail Motor
                            </h5>
                            <small class="text-muted">{{ $motor->merk }} - {{ $motor->no_plat }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.motors.edit', $motor->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('admin.motors.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Motor Information -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Informasi Motor
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="40%">Merk:</td>
                                    <td>{{ $motor->merk }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tipe CC:</td>
                                    <td>{{ $motor->tipe_cc }}cc</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nomor Plat:</td>
                                    <td><span class="badge bg-primary">{{ $motor->no_plat }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        @switch($motor->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('verified')
                                                <span class="badge bg-success">Verified</span>
                                                @break
                                            @case('available')
                                                <span class="badge bg-info">Available</span>
                                                @break
                                            @case('rented')
                                                <span class="badge bg-danger">Rented</span>
                                                @break
                                            @case('maintenance')
                                                <span class="badge bg-secondary">Maintenance</span>
                                                @break
                                            @default
                                                <span class="badge bg-dark">{{ ucfirst($motor->status) }}</span>
                                        @endswitch
                                    </td>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="40%">Pemilik:</td>
                                    <td>
                                        <a href="#" class="text-decoration-none">
                                            {{ $motor->pemilik->nama ?? 'Tidak diketahui' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email Pemilik:</td>
                                    <td>{{ $motor->pemilik->email ?? 'Tidak diketahui' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Telepon:</td>
                                    <td>{{ $motor->pemilik->telepon ?? 'Tidak diketahui' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Daftar:</td>
                                    <td>{{ $motor->created_at?->format('d/m/Y H:i') ?? 'Tidak diketahui' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            @if($motor->tarifRental)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-currency-dollar"></i> Tarif Rental
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h6 class="text-primary">Harian</h6>
                                <h4 class="text-success mb-0">
                                    Rp{{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">per hari</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h6 class="text-primary">Mingguan</h6>
                                <h4 class="text-success mb-0">
                                    Rp{{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">per minggu</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h6 class="text-primary">Bulanan</h6>
                                <h4 class="text-success mb-0">
                                    Rp{{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">per bulan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-currency-dollar"></i> Tarif Rental
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Tarif rental belum ditetapkan. 
                        <a href="{{ route('admin.motors.edit', $motor->id) }}" class="alert-link">Edit motor</a> 
                        untuk menambahkan tarif.
                    </div>
                </div>
            </div>
            @endif

            <!-- Rental History -->
            @if($motor->penyewaans->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-clock-history"></i> Riwayat Penyewaan ({{ $motor->penyewaans->count() }} kali)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Penyewa</th>
                                    <th>Durasi</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($motor->penyewaans->take(5) as $penyewaan)
                                <tr>
                                    <td>{{ $penyewaan->tgl_mulai->format('d/m/Y') }}</td>
                                    <td>{{ $penyewaan->penyewa->nama ?? 'N/A' }}</td>
                                    <td>{{ $penyewaan->tgl_mulai->diffInDays($penyewaan->tgl_selesai) }} hari</td>
                                    <td>Rp{{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</td>
                                    <td>
                                        @switch($penyewaan->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge bg-info">Confirmed</span>
                                                @break
                                            @case('active')
                                                <span class="badge bg-success">Active</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-primary">Completed</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($penyewaan->status) }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($motor->penyewaans->count() > 5)
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                Lihat Semua Riwayat ({{ $motor->penyewaans->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Media & Actions -->
        <div class="col-lg-4">
            <!-- Motor Photo -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-image"></i> Foto Motor
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if($motor->foto)
                        <img src="{{ asset('storage/' . $motor->foto) }}" 
                             class="img-fluid rounded mb-3" 
                             alt="Foto Motor {{ $motor->merk }}"
                             style="max-height: 300px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <div class="text-center text-muted">
                                <i class="bi bi-image display-1"></i>
                                <p class="mb-0">Tidak ada foto</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-graph-up"></i> Statistik
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $motor->penyewaans->count() }}</h4>
                                <small class="text-muted">Total Sewa</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">
                                Rp{{ number_format($motor->penyewaans->sum('total_biaya'), 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">Total Pendapatan</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <p class="text-muted mb-1">Status Availability</p>
                            @if($motor->status === 'available')
                                <span class="badge bg-success">Tersedia untuk Disewa</span>
                            @elseif($motor->status === 'rented')
                                <span class="badge bg-danger">Sedang Disewa</span>
                            @elseif($motor->status === 'maintenance')
                                <span class="badge bg-warning">Dalam Perbaikan</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($motor->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-gear"></i> Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.motors.edit', $motor->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Motor
                        </a>
                        
                        @if($motor->status === 'pending')
                            <form action="{{ route('admin.motors.update', $motor->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="verified">
                                <button type="submit" class="btn btn-success w-100" 
                                        onclick="return confirm('Verifikasi motor ini?')">
                                    <i class="bi bi-check-circle"></i> Verifikasi
                                </button>
                            </form>
                        @endif
                        
                        @if(in_array($motor->status, ['available', 'verified']))
                            <form action="{{ route('admin.motors.update', $motor->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="maintenance">
                                <button type="submit" class="btn btn-warning w-100" 
                                        onclick="return confirm('Set motor ke maintenance?')">
                                    <i class="bi bi-tools"></i> Set Maintenance
                                </button>
                            </form>
                        @endif
                        
                        @if($motor->status === 'maintenance')
                            <form action="{{ route('admin.motors.update', $motor->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="available">
                                <button type="submit" class="btn btn-info w-100" 
                                        onclick="return confirm('Set motor ke available?')">
                                    <i class="bi bi-check-circle"></i> Set Available
                                </button>
                            </form>
                        @endif
                        
                        <button class="btn btn-danger" 
                                onclick="confirmDelete({{ $motor->id }}, '{{ $motor->merk }} - {{ $motor->no_plat }}')">
                            <i class="bi bi-trash"></i> Hapus Motor
                        </button>
                        
                        <form id="delete-form-{{ $motor->id }}" 
                             action="{{ route('admin.motors.destroy', $motor->id) }}"
                                  method="POST" style="display: none;"> 
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(motorId, motorName) {
        if (confirm(`Yakin ingin menghapus motor ${motorName}?\nData ini tidak dapat dikembalikan!`)) {
            document.getElementById(`delete-form-${motorId}`).submit();
        }
    }
</script>
@endpush
@endsection
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->nama }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->alamat ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bergabung Sejak</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->created_at->format('d F Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Motor</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->motors()->count() }} motor</p>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            @if($motor->tarif)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tarif</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga per Hari</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($motor->tarif->harga_per_hari, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga per Minggu</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($motor->tarif->harga_per_minggu, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga per Bulan</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($motor->tarif->harga_per_bulan, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deposito</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($motor->tarif->deposito, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Booking History -->
            @if($motor->bookings()->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Booking</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penyewa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($motor->bookings()->latest()->take(5)->get() as $booking)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->penyewa->nama }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $booking->tanggal_mulai->format('d M Y') }} - {{ $booking->tanggal_selesai->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($booking->harga, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                                $booking->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($booking->status->value === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                                ($booking->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) 
                                            }}">
                                                {{ $booking->status->label() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Motor Photo -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Motor</h3>
                @if($motor->foto_motor)
                    <img src="{{ Storage::url($motor->foto_motor) }}" alt="Motor {{ $motor->merk }}" 
                        class="w-full h-48 object-cover rounded-lg">
                @else
                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-4xl text-gray-400">🏍️</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Foto motor belum diupload</p>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Booking</span>
                        <span class="text-sm font-medium">{{ $motor->bookings()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Booking Aktif</span>
                        <span class="text-sm font-medium">{{ $motor->bookings()->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Pendapatan</span>
                        <span class="text-sm font-medium">Rp {{ number_format($motor->bookings()->where('status', 'completed')->sum('harga'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mr-3"></div>
                        <div class="text-sm">
                            <p class="text-gray-900">Terdaftar</p>
                            <p class="text-gray-500">{{ $motor->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @if($motor->verified_at)
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-green-500 mr-3"></div>
                            <div class="text-sm">
                                <p class="text-gray-900">Diverifikasi</p>
                                <p class="text-gray-500">{{ $motor->verified_at?->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-dashboard>