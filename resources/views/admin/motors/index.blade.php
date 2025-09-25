@extends('layouts.app')

@section('content')
@php
    $pageTitle = 'Kelola Motor';
    $pageSubtitle = 'Manajemen motor premium LuxuryMoto';
    $pageActions = '<a href="' . route('admin.motors.create') . '" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Motor Premium
    </a>';
@endphp

<!-- === LUXURY MOTOR STATISTICS === -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card luxury-fade-in" style="animation-delay: 0.1s">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $motors->total() ?? '0' }}</div>
                        <div class="stat-label">Total Motor Premium</div>
                    </div>
                    <div class="bg-luxury-gradient rounded-circle p-3">
                        <i class="bi bi-motorcycle text-luxury-primary fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <small class="text-success me-2">
                        <i class="bi bi-arrow-up"></i> +15%
                    </small>
                    <small class="text-light">vs bulan lalu</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card success luxury-fade-in" style="animation-delay: 0.2s">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $motors->where('status', 'available')->count() ?? '0' }}</div>
                        <div class="stat-label">Motor Tersedia</div>
                    </div>
                    <div class="bg-success rounded-circle p-3">
                        <i class="bi bi-check-circle text-white fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <small class="text-success me-2">
                        <i class="bi bi-calendar-check"></i> Ready
                    </small>
                    <small class="text-light">siap disewa</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card warning luxury-fade-in" style="animation-delay: 0.3s">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $motors->where('status', 'rented')->count() ?? '0' }}</div>
                        <div class="stat-label">Sedang Disewa</div>
                    </div>
                    <div class="bg-warning rounded-circle p-3">
                        <i class="bi bi-clock text-white fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <small class="text-warning me-2">
                        <i class="bi bi-hourglass-split"></i> Active
                    </small>
                    <small class="text-light">dalam perjalanan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card info luxury-fade-in" style="animation-delay: 0.4s">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $motors->where('status', 'pending')->count() ?? '0' }}</div>
                        <div class="stat-label">Menunggu Verifikasi</div>
                    </div>
                    <div class="bg-info rounded-circle p-3">
                        <i class="bi bi-shield-exclamation text-white fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <small class="text-info me-2">
                        <i class="bi bi-hourglass"></i> Pending
                    </small>
                    <small class="text-light">review diperlukan</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- === LUXURY FILTER SECTION === -->
<div class="card luxury-fade-in mb-4" style="animation-delay: 0.5s">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="bi bi-funnel-fill text-luxury-gold me-2"></i>
                Filter Motor Premium
            </h5>
            <small class="text-muted">Cari motor sesuai kriteria</small>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.motors.index') }}" class="row g-3">
            <div class="col-lg-3 col-md-6">
                <label for="status" class="form-label">Status Motor</label>
                <select name="status" id="status" class="form-select">
                    <option value="">🎭 Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending Verifikasi</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>✅ Terverifikasi</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>🟢 Tersedia</option>
                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>🔄 Sedang Disewa</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="tipe_cc" class="form-label">Kapasitas Mesin</label>
                <select name="tipe_cc" id="tipe_cc" class="form-select">
                    <option value="">🏍️ Semua Kapasitas</option>
                    <option value="100" {{ request('tipe_cc') == '100' ? 'selected' : '' }}>100cc - Ekonomis</option>
                    <option value="125" {{ request('tipe_cc') == '125' ? 'selected' : '' }}>125cc - Standar</option>
                    <option value="150" {{ request('tipe_cc') == '150' ? 'selected' : '' }}>150cc - Premium</option>
                    <option value="160" {{ request('tipe_cc') == '160' ? 'selected' : '' }}>160cc+ - Luxury</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-8">
                <label for="merk" class="form-label">Pencarian Merk & Model</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search text-luxury-gold"></i>
                    </span>
                    <input type="text" name="merk" id="merk" class="form-control" value="{{ request('merk') }}" placeholder="Honda, Yamaha, Suzuki...">
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                    <a href="{{ route('admin.motors.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- === LUXURY MOTOR TABLE === -->
<div class="card luxury-fade-in" style="animation-delay: 0.6s">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="bi bi-list-ul text-luxury-gold me-2"></i>
                Koleksi Motor Premium
            </h5>
            <div class="d-flex align-items-center gap-3">
                <small class="text-muted">{{ $motors->total() }} motor terdaftar</small>
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-download me-2"></i>Export Excel</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print Report</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($motors->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th class="text-center" style="width: 80px;">Foto</th>
                            <th>Detail Motor</th>
                            <th>Pemilik</th>
                            <th class="text-center">Identitas</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                        <tbody>
                            @foreach($motors as $motor)
                            <tr>
                                <td>{{ ($motors->currentPage() - 1) * $motors->perPage() + $loop->iteration }}</td>
                                <td>
                                    @if($motor->photo)
                                        <img src="{{ asset('storage/' . $motor->photo) }}" alt="Motor" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $motor->merk }}</strong><br>
                                    <small class="text-muted">{{ ucfirst($motor->tipe_motor ?? 'N/A') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $motor->pemilik->nama }}</strong><br>
                                    <small class="text-muted">{{ $motor->pemilik->email }}</small>
                                </td>
                                <td><span class="badge bg-dark">{{ $motor->no_plat }}</span></td>
                                <td><span class="badge bg-info">{{ $motor->tipe_cc }}cc</span></td>
                                <td>
                                    @switch($motor->status->value)
                                        @case('pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @break
                                        @case('verified')
                                            <span class="badge bg-info">Verified</span>
                                            @break
                                        @case('available')
                                            <span class="badge bg-success">Available</span>
                                            @break
                                        @case('rented')
                                            <span class="badge bg-primary">Rented</span>
                                            @break
                                        @case('maintenance')
                                            <span class="badge bg-secondary">Maintenance</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($motor->status->value) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <small>{{ $motor->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <a href="{{ route('admin.motors.show', $motor) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.motors.edit', $motor) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.motors.destroy', $motor) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus motor ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                    </tbody>
                </table>
            </div>
            
            <!-- === LUXURY PAGINATION === -->
            <div class="d-flex justify-content-between align-items-center p-4 border-top" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(212, 175, 55, 0.02) 100%);">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle text-luxury-gold me-2"></i>
                    <small class="text-muted fw-medium">
                        Menampilkan <span class="text-luxury-primary fw-bold">{{ $motors->firstItem() ?? 0 }}</span> - 
                        <span class="text-luxury-primary fw-bold">{{ $motors->lastItem() ?? 0 }}</span> dari 
                        <span class="text-luxury-primary fw-bold">{{ $motors->total() }}</span> motor premium
                    </small>
                </div>
                <div>
                    {{ $motors->links() }}
                </div>
            </div>
        @else
            <!-- === LUXURY EMPTY STATE === -->
            <div class="empty-state text-center">
                <div class="mb-4">
                    <div class="bg-luxury-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-motorcycle text-luxury-primary" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h4 class="text-luxury-primary mb-3 gold-shimmer">Koleksi Motor Belum Tersedia</h4>
                <p class="text-muted mb-4 lead">
                    Mulai membangun koleksi motor premium LuxuryMoto dengan menambahkan motor pertama Anda.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('admin.motors.create') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Motor Premium Pertama
                    </a>
                    <a href="{{ route('admin.motors-verification.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-shield-check me-2"></i>
                        Verifikasi Motor
                    </a>
                </div>
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="bi bi-lightbulb me-1"></i>
                        Tip: Motor yang telah terverifikasi akan otomatis muncul di katalog premium
                    </small>
                </div>
            </div>
        @endif
        </div>
    </div>
    </div>
@endsection

@push('styles')
<style>
/* === LUXURY MOTOR INDEX CUSTOM STYLES === */

/* Empty State Luxury Design */
.empty-state {
    padding: 80px 40px;
    min-height: 400px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: linear-gradient(135deg, 
        rgba(26, 26, 29, 0.8) 0%, 
        rgba(10, 10, 11, 0.9) 50%, 
        rgba(26, 26, 29, 0.8) 100%);
    border-radius: 20px;
    border: 1px solid rgba(212, 175, 55, 0.2);
    position: relative;
    overflow: hidden;
}

.empty-state::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
    animation: luxuryPulse 4s ease-in-out infinite;
}

/* Luxury Filter Enhancements */
.luxury-filter-container {
    background: linear-gradient(135deg, 
        rgba(26, 26, 29, 0.95) 0%, 
        rgba(10, 10, 11, 0.98) 100%);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    backdrop-filter: blur(10px);
    box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.3),
        0 0 40px rgba(212, 175, 55, 0.1);
}

.luxury-search-group {
    position: relative;
    margin-bottom: 20px;
}

.luxury-search-group .form-control {
    background: rgba(26, 26, 29, 0.8);
    border: 2px solid rgba(212, 175, 55, 0.3);
    border-radius: 12px;
    color: var(--luxury-text-primary);
    padding: 12px 48px 12px 16px;
    font-size: 14px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.luxury-search-group .form-control:focus {
    background: rgba(26, 26, 29, 0.95);
    border-color: var(--luxury-gold);
    box-shadow: 
        0 0 0 3px rgba(212, 175, 55, 0.1),
        0 4px 12px rgba(212, 175, 55, 0.2);
    transform: translateY(-1px);
}

.luxury-search-group .search-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--luxury-gold);
    pointer-events: none;
    transition: all 0.3s ease;
}

/* Luxury Select Styling */
.form-select {
    background: rgba(26, 26, 29, 0.8);
    border: 2px solid rgba(212, 175, 55, 0.3);
    border-radius: 12px;
    color: var(--luxury-text-primary);
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-select:focus {
    background: rgba(26, 26, 29, 0.95);
    border-color: var(--luxury-gold);
    box-shadow: 
        0 0 0 3px rgba(212, 175, 55, 0.1),
        0 4px 12px rgba(212, 175, 55, 0.2);
}

/* Luxury Button Enhancements */
.btn-outline-secondary {
    border: 2px solid rgba(212, 175, 55, 0.4);
    color: var(--luxury-gold);
    background: transparent;
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-outline-secondary:hover {
    background: var(--luxury-gold);
    color: var(--luxury-dark);
    border-color: var(--luxury-gold);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
}

/* Luxury Table Enhancements */
.table-luxury {
    border-collapse: separate;
    border-spacing: 0;
    background: rgba(26, 26, 29, 0.6);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 
        0 10px 40px rgba(0, 0, 0, 0.3),
        0 0 20px rgba(212, 175, 55, 0.1);
}

.table-luxury thead th {
    background: linear-gradient(135deg, 
        rgba(212, 175, 55, 0.9) 0%, 
        rgba(244, 228, 188, 0.8) 100%);
    color: var(--luxury-dark);
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 20px 16px;
    border: none;
    position: relative;
}

.table-luxury thead th::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(26, 26, 29, 0.3) 50%, 
        transparent 100%);
}

.table-luxury tbody tr {
    background: rgba(26, 26, 29, 0.4);
    border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.table-luxury tbody tr:hover {
    background: rgba(212, 175, 55, 0.08);
    transform: scale(1.01);
    box-shadow: 
        0 4px 20px rgba(212, 175, 55, 0.15),
        inset 0 1px 0 rgba(212, 175, 55, 0.2);
}

.table-luxury tbody td {
    padding: 20px 16px;
    vertical-align: middle;
    border: none;
    color: var(--luxury-text-primary);
    font-size: 14px;
}

/* Motor Photo Styling */
.motor-photo {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid rgba(212, 175, 55, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.motor-photo:hover {
    transform: scale(1.1);
    border-color: var(--luxury-gold);
    box-shadow: 
        0 8px 25px rgba(212, 175, 55, 0.3),
        0 0 20px rgba(212, 175, 55, 0.2);
}

/* Status Badge Enhancements */
.badge {
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid transparent;
}

.badge.bg-success {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.badge.bg-warning {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%) !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.badge.bg-danger {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%) !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%) !important;
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
}

/* Action Button Enhancements */
.btn-sm {
    padding: 8px 16px;
    font-size: 12px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-primary {
    background: linear-gradient(135deg, var(--luxury-gold) 0%, #B8860B 100%);
    border: none;
    color: var(--luxury-dark);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #B8860B 0%, var(--luxury-gold) 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}

.btn-info {
    background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%);
    border: none;
}

.btn-info:hover {
    background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    border: none;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
}

/* Pagination Luxury Styling */
.pagination .page-link {
    background: rgba(26, 26, 29, 0.8);
    border: 1px solid rgba(212, 175, 55, 0.3);
    color: var(--luxury-text-primary);
    padding: 12px 16px;
    margin: 0 4px;
    border-radius: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.pagination .page-link:hover {
    background: var(--luxury-gold);
    color: var(--luxury-dark);
    border-color: var(--luxury-gold);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.3);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, var(--luxury-gold) 0%, #B8860B 100%);
    border-color: var(--luxury-gold);
    color: var(--luxury-dark);
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    .luxury-stats-container {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .table-luxury {
        font-size: 13px;
    }
    
    .table-luxury tbody td {
        padding: 12px 8px;
    }
    
    .motor-photo {
        width: 50px;
        height: 50px;
    }
    
    .empty-state {
        padding: 40px 20px;
        min-height: 300px;
    }
}

/* Animation Keyframes */
@keyframes luxuryPulse {
    0%, 100% { 
        opacity: 0.4; 
        transform: scale(1); 
    }
    50% { 
        opacity: 0.8; 
        transform: scale(1.1); 
    }
}

@keyframes luxuryShimmer {
    0% { background-position: -200% center; }
    100% { background-position: 200% center; }
}

@keyframes luxuryFadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading States */
.luxury-loading {
    position: relative;
    overflow: hidden;
}

.luxury-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(212, 175, 55, 0.4) 50%, 
        transparent 100%);
    animation: luxuryShimmer 2s infinite;
}
</style>
@endpush
            <a href="{{ route('admin.motors.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                ➕ Tambah Motor
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Motor</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Merk, plat, atau owner..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>Sedang Disewa</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe CC</label>
                <select name="tipe_cc" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="100" {{ request('tipe_cc') === '100' ? 'selected' : '' }}>100cc</option>
                    <option value="125" {{ request('tipe_cc') === '125' ? 'selected' : '' }}>125cc</option>
                    <option value="150" {{ request('tipe_cc') === '150' ? 'selected' : '' }}>150cc</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">
                    Cari
                </button>
                <a href="{{ route('admin.motors.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Motor Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Motor ({{ $motors->total() }})</h3>
                <div class="flex space-x-2">
                    @if(request('search') || request('status') || request('tipe_cc'))
                        <span class="text-sm text-gray-500">Filter aktif</span>
                    @endif
                </div>
            </div>
        </div>

        @if($motors->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe CC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarif</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($motors as $motor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($motor->photo)
                                                <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::url($motor->photo) }}" alt="Motor">
                                            @else
                                                <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-500 text-xs">🏍️</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $motor->merk }} {{ $motor->model ?? '' }}</div>
                                            <div class="text-sm text-gray-500">{{ $motor->no_plat }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $motor->owner->nama ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $motor->owner->no_tlpn ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($motor->tipe_cc)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ $motor->tipe_cc->label() }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($motor->tarif)
                                        <div class="text-sm text-gray-900">Rp {{ number_format($motor->tarif->tarif_harian, 0, ',', '.') }}/hari</div>
                                        <div class="text-sm text-gray-500">Rp {{ number_format($motor->tarif->tarif_mingguan, 0, ',', '.') }}/minggu</div>
                                        <div class="text-xs text-gray-400">Rp {{ number_format($motor->tarif->tarif_bulanan, 0, ',', '.') }}/bulan</div>
                                    @else
                                        <span class="text-sm text-gray-500">Belum ada tarif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($motor->status)
                                        @php
                                            $statusClass = match($motor->status->value) {
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'verified' => 'bg-blue-100 text-blue-800',
                                                'available' => 'bg-green-100 text-green-800',
                                                'rented' => 'bg-red-100 text-red-800',
                                                'maintenance' => 'bg-gray-100 text-gray-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $motor->status->label() }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $motor->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button type="button" 
                                                class="inline-flex items-center justify-center w-8 h-8 bg-white rounded-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                onclick="toggleDropdown({{ $motor->id }})">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                        
                                        <div id="dropdown-{{ $motor->id }}" 
                                             class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                            <div class="py-1">
                                                <a href="{{ route('admin.motors.show', $motor) }}" 
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Lihat Detail
                                                </a>
                                                
                                                <a href="{{ route('admin.motors.edit', $motor) }}" 
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit Motor
                                                </a>

                                                @if($motor->status?->value === 'pending')
                                                    <div class="border-t border-gray-100"></div>
                                                    <button type="button"
                                                            onclick="verifyMotor({{ $motor->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-green-700 hover:bg-green-50">
                                                        <svg class="w-4 h-4 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Verifikasi Motor
                                                    </button>
                                                    
                                                    <button type="button"
                                                            onclick="rejectMotor({{ $motor->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                        <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Tolak Motor
                                                    </button>
                                                @endif

                                                @if($motor->status?->value === 'verified')
                                                    <div class="border-t border-gray-100"></div>
                                                    <button type="button"
                                                            onclick="activateMotor({{ $motor->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-blue-700 hover:bg-blue-50">
                                                        <svg class="w-4 h-4 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                                                        </svg>
                                                        Aktifkan Motor
                                                    </button>
                                                @endif

                                                @if($motor->status?->value === 'available')
                                                    <div class="border-t border-gray-100"></div>
                                                    <button type="button"
                                                            onclick="setMaintenanceMotor({{ $motor->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-orange-700 hover:bg-orange-50">
                                                        <svg class="w-4 h-4 mr-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Set Maintenance
                                                    </button>
                                                @endif

                                                @if($motor->status?->value === 'maintenance')
                                                    <div class="border-t border-gray-100"></div>
                                                    <button type="button"
                                                            onclick="activateMotor({{ $motor->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-blue-700 hover:bg-blue-50">
                                                        <svg class="w-4 h-4 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                                                        </svg>
                                                        Aktifkan Motor
                                                    </button>
                                                @endif

                                                <div class="border-t border-gray-100"></div>
                                                <button type="button"
                                                        onclick="deleteMotor({{ $motor->id }}, '{{ $motor->merk }}')"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                    <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus Motor
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $motors->links() }}
            </div>
        @else
            <div class="p-6 text-center">
                <div class="text-gray-500">
                    <span class="text-4xl mb-4 block">🏍️</span>
                    <p class="text-lg font-medium">Tidak ada motor ditemukan</p>
                    <p class="text-sm">Coba ubah filter pencarian atau tunggu owner mendaftarkan motor baru</p>
                </div>
            </div>
        @endif
    </div>
</x-layouts.admin-dashboard>

<script>
// Toggle dropdown menu
function toggleDropdown(motorId) {
    const dropdown = document.getElementById(`dropdown-${motorId}`);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(dd => {
        if (dd.id !== `dropdown-${motorId}`) {
            dd.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick*="toggleDropdown"]')) {
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        allDropdowns.forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});

// Motor verification function
function verifyMotor(motorId) {
    if (confirm('Apakah Anda yakin ingin memverifikasi motor ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/motors-verification/${motorId}/verify`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Motor activation function  
function activateMotor(motorId) {
    if (confirm('Apakah Anda yakin ingin mengaktifkan motor ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/motors-verification/${motorId}/activate`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Motor maintenance function
function setMaintenanceMotor(motorId) {
    if (confirm('Apakah Anda yakin ingin mengatur motor ini ke status maintenance?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/motors/${motorId}/maintenance`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Motor rejection function
function rejectMotor(motorId) {
    if (confirm('Apakah Anda yakin ingin menolak motor ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/motors-verification/${motorId}/reject`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Luxury Delete Confirmation
function confirmDelete(motorId, motorName) {
    // Create luxury modal for delete confirmation
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Konfirmasi Penghapusan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-motorcycle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h6>Apakah Anda yakin ingin menghapus motor:</h6>
                    <div class="alert alert-warning mt-3">
                        <strong>${motorName}</strong>
                    </div>
                    <p class="text-muted">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" onclick="executeDelete('${motorId}')">
                        <i class="bi bi-trash3 me-1"></i> Ya, Hapus Motor
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Remove modal from DOM when hidden
    modal.addEventListener('hidden.bs.modal', () => {
        document.body.removeChild(modal);
    });
}

// Execute delete action
function executeDelete(motorId) {
    document.getElementById('delete-form-' + motorId).submit();
}

// Initialize luxury features
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Photo hover effects
    const photoContainers = document.querySelectorAll('.motor-photo-container');
    photoContainers.forEach(container => {
        const overlay = container.querySelector('.photo-overlay');
        container.addEventListener('mouseenter', () => {
            if (overlay) overlay.style.opacity = '1';
        });
        container.addEventListener('mouseleave', () => {
            if (overlay) overlay.style.opacity = '0';
        });
    });
    
    // Luxury table row hover effects
    const tableRows = document.querySelectorAll('.luxury-table-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.boxShadow = '0 4px 12px rgba(212, 175, 55, 0.15)';
        });
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Animate statistics cards
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach((element, index) => {
        const text = element.textContent;
        const number = parseInt(text.replace(/[^\d]/g, ''));
        if (!isNaN(number)) {
            element.textContent = '0';
            setTimeout(() => {
                animateNumber(element, 0, number, 1500);
            }, index * 200);
        }
    });
});

// Number animation function
function animateNumber(element, start, end, duration) {
    const range = end - start;
    const minTimer = 50;
    let stepTime = Math.abs(Math.floor(duration / range));
    stepTime = Math.max(stepTime, minTimer);
    
    const startTime = new Date().getTime();
    const endTime = startTime + duration;
    let timer;
    
    const run = () => {
        const now = new Date().getTime();
        const remaining = Math.max((endTime - now) / duration, 0);
        const value = Math.round(end - (remaining * range));
        element.textContent = value.toLocaleString('id-ID');
        
        if (value === end) {
            clearInterval(timer);
        }
    };
    
    timer = setInterval(run, stepTime);
    run();
}
</script>

@push('styles')
<style>
/* Additional luxury styles for motor index */
.luxury-table-row {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.luxury-plat-number .badge {
    border: 2px solid var(--luxmoto-gold-primary);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.luxury-date {
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(212, 175, 55, 0.05) 100%);
    border-radius: 0.5rem;
    padding: 0.25rem 0.5rem;
}

.motor-photo-container:hover img {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

.btn-group .btn {
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    z-index: 2;
}

/* Status badge animations */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Empty state luxury styling */
.empty-state {
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(212, 175, 55, 0.02) 100%);
    border: 2px dashed var(--luxmoto-gold-primary);
    border-radius: 1rem;
    padding: 3rem;
}
</style>
@endpush