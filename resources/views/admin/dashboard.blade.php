@extends('layouts.app')

@section('content')
@php
    $pageTitle = 'Dashboard Admin';
    $pageSubtitle = 'Selamat datang di panel administrasi LuxuryMoto';
@endphp

<div class="row g-4">
    <!-- === LUXURY STATISTICS CARDS === -->
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card luxury-fade-in" style="animation-delay: 0.1s">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $stats['total_motors'] ?? '0' }}</div>
                        <div class="stat-label">Total Motor</div>
                    </div>
                    <div class="bg-luxury-gradient rounded-circle p-3">
                        <i class="bi bi-motorcycle text-luxury-primary fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <small class="text-success me-2">
                        <i class="bi bi-arrow-up"></i> +12%
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
                        <div class="stat-number">{{ $stats['total_users'] ?? '0' }}</div>
                        <div class="stat-label">Total Pengguna</div>
                    </div>
                    <div class="bg-success rounded-circle p-3">
                        <i class="bi bi-people text-white fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <small class="text-success me-2">
                        <i class="bi bi-arrow-up"></i> +8%
                    </small>
                    <small class="text-light">pengguna baru</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card warning luxury-fade-in" style="animation-delay: 0.3s">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $bookingStats['active_bookings'] ?? '0' }}</div>
                        <div class="stat-label">Penyewaan Aktif</div>
                    </div>
                    <div class="bg-warning rounded-circle p-3">
                        <i class="bi bi-calendar-check text-white fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <small class="text-warning me-2">
                        <i class="bi bi-clock"></i> {{ $bookingStats['pending_bookings'] ?? '0' }}
                    </small>
                    <small class="text-light">menunggu konfirmasi</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card info luxury-fade-in" style="animation-delay: 0.4s">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">Rp {{ number_format($revenueStats['monthly_revenue'] ?? 0, 0, ',', '.') }}</div>
                        <div class="stat-label">Pendapatan Bulan Ini</div>
                    </div>
                    <div class="bg-info rounded-circle p-3">
                        <i class="bi bi-currency-dollar text-white fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <small class="text-info me-2">
                        <i class="bi bi-arrow-up"></i> +25%
                    </small>
                    <small class="text-light">dari target</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- === QUICK ACTIONS === -->
    <div class="col-lg-8">
        <div class="card luxury-fade-in" style="animation-delay: 0.5s">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning-fill text-luxury-gold me-2"></i>
                        Aksi Cepat
                    </h5>
                    <small class="text-muted">Kelola sistem dengan mudah</small>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('admin.motors.create') }}" class="btn btn-primary w-100 py-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-plus-circle me-2 fs-5"></i>
                            <div>
                                <div class="fw-semibold">Tambah Motor</div>
                                <small class="opacity-75">Daftarkan motor baru</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-success w-100 py-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-people me-2 fs-5"></i>
                            <div>
                                <div class="fw-semibold">Kelola Pengguna</div>
                                <small class="opacity-75">Manajemen pengguna sistem</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.motors-verification.index') }}" class="btn btn-warning w-100 py-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-shield-check me-2 fs-5"></i>
                            <div>
                                <div class="fw-semibold">Verifikasi Motor</div>
                                <small class="opacity-75">{{ $stats['pending_motors'] ?? '0' }} motor pending</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.penyewaans.index') }}" class="btn btn-info w-100 py-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar-event me-2 fs-5"></i>
                            <div>
                                <div class="fw-semibold">Kelola Penyewaan</div>
                                <small class="opacity-75">Monitor semua transaksi</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- === SYSTEM STATUS === -->
    <div class="col-lg-4">
        <div class="card luxury-fade-in" style="animation-delay: 0.6s">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear-fill text-luxury-gold me-2"></i>
                    Status Sistem
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="fw-semibold">Server Status</div>
                        <small class="text-success">Online & Optimal</small>
                    </div>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Aktif
                    </span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="fw-semibold">Database</div>
                        <small class="text-success">Koneksi Stabil</small>
                    </div>
                    <span class="badge bg-success">
                        <i class="bi bi-database"></i> Normal
                    </span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="fw-semibold">Storage</div>
                        <small class="text-info">85% Tersedia</small>
                    </div>
                    <span class="badge bg-info">
                        <i class="bi bi-hdd"></i> Baik
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Backup Terakhir</div>
                        <small class="text-warning">2 jam lalu</small>
                    </div>
                    <span class="badge bg-warning">
                        <i class="bi bi-clock"></i> Terjadwal
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- === RECENT ACTIVITY === -->
<div class="row g-4 mt-2">
    <div class="col-lg-8">
        <div class="card luxury-fade-in" style="animation-delay: 0.7s">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-activity text-luxury-gold me-2"></i>
                        Aktivitas Terbaru
                    </h5>
                    <a href="#" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                @if(isset($recentMotors) && $recentMotors->count() > 0)
                    @foreach($recentMotors->take(5) as $motor)
                    <div class="d-flex align-items-start mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-motorcycle text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Motor baru ditambahkan: {{ $motor->merk }}</div>
                            <div class="text-muted small">{{ $motor->no_plat }} oleh {{ $motor->owner->nama ?? 'Admin' }}</div>
                        </div>
                        <small class="text-muted">{{ $motor->created_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                @else
                    @php
                        $activities = [
                            ['icon' => 'bi-motorcycle', 'action' => 'Motor Honda Vario 150 ditambahkan', 'user' => 'Ahmad Pemilik', 'time' => '5 menit lalu', 'type' => 'success'],
                            ['icon' => 'bi-person-check', 'action' => 'Pengguna baru terdaftar', 'user' => 'Siti Penyewa', 'time' => '15 menit lalu', 'type' => 'info'],
                            ['icon' => 'bi-calendar-check', 'action' => 'Penyewaan dikonfirmasi', 'user' => 'Budi Penyewa', 'time' => '30 menit lalu', 'type' => 'warning'],
                            ['icon' => 'bi-shield-check', 'action' => 'Motor Yamaha NMAX diverifikasi', 'user' => 'System Admin', 'time' => '1 jam lalu', 'type' => 'primary'],
                            ['icon' => 'bi-cash-stack', 'action' => 'Pembayaran Rp 300,000 diterima', 'user' => 'Andi Penyewa', 'time' => '2 jam lalu', 'type' => 'success']
                        ];
                    @endphp

                    @foreach($activities as $activity)
                    <div class="d-flex align-items-start mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <div class="bg-{{ $activity['type'] }} bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi {{ $activity['icon'] }} text-{{ $activity['type'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $activity['action'] }}</div>
                            <div class="text-muted small">oleh {{ $activity['user'] }}</div>
                        </div>
                        <small class="text-muted">{{ $activity['time'] }}</small>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- === MOTOR STATUS CHART === -->
    <div class="col-lg-4">
        <div class="card luxury-fade-in" style="animation-delay: 0.8s">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart-fill text-luxury-gold me-2"></i>
                    Status Motor
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="position-relative mb-4">
                    <canvas id="motorStatusChart" width="200" height="200"></canvas>
                </div>
                
                <div class="row g-2">
                    <div class="col-6">
                        <div class="border rounded-3 p-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <small class="fw-semibold">Tersedia</small>
                            </div>
                            <div class="fs-5 fw-bold text-success">{{ $stats['available_motors'] ?? '45' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded-3 p-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <small class="fw-semibold">Disewa</small>
                            </div>
                            <div class="fs-5 fw-bold text-warning">{{ $stats['rented_motors'] ?? '25' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded-3 p-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <small class="fw-semibold">Maintenance</small>
                            </div>
                            <div class="fs-5 fw-bold text-danger">5</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded-3 p-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <small class="fw-semibold">Pending</small>
                            </div>
                            <div class="fs-5 fw-bold text-info">{{ $stats['pending_motors'] ?? '8' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- === RECENT BOOKINGS === -->
@if(isset($recentBookings) && $recentBookings->count() > 0)
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card luxury-fade-in" style="animation-delay: 0.9s">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history text-luxury-gold me-2"></i>
                        Booking Terbaru
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Motor</th>
                                <th>Penyewa</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings->take(5) as $booking)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-luxury-gradient rounded me-3 p-2">
                                            <i class="bi bi-motorcycle text-luxury-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $booking->motor->merk ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $booking->motor->no_plat ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $booking->penyewa->nama ?? 'N/A' }}</td>
                                <td class="fw-semibold text-success">
                                    Rp {{ number_format($booking->harga ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'secondary';
                                        if(isset($booking->status)) {
                                            switch($booking->status->value ?? 'pending') {
                                                case 'confirmed': $statusClass = 'success'; break;
                                                case 'active': $statusClass = 'primary'; break;
                                                case 'completed': $statusClass = 'info'; break;
                                                case 'cancelled': $statusClass = 'danger'; break;
                                                default: $statusClass = 'warning'; break;
                                            }
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $booking->status->label() ?? 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $booking->created_at->format('d M Y') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Set up data variables
const statsData = {
    availableMotors: {{ $stats['available motors'] ?? 45 }},
    rentedMotors: {{ $stats['rented motors'] ?? 25 }},
    pendingMotors: {{ $stats['pending motors'] ?? 8 }},
};

// Motor Status Doughnut Chart
const ctx = document.getElementById('motorStatusChart').getContext('2d');
const motorStatusChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Tersedia', 'Disewa', 'Maintenance', 'Pending'],
        datasets: [{
            data: [
                statsData.availableMotors, 
                statsData.rentedMotors, 
                5, 
                statsData.pendingMotors
            ],
            backgroundColor: [
                '#059669', // Success
                '#D97706', // Warning  
                '#DC2626', // Danger
                '#2563EB'  // Info
            ],
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverBorderWidth: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#D4AF37',
                bodyColor: '#ffffff',
                borderColor: '#D4AF37',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((context.parsed / total) * 100);
                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                    }
                }
            }
        },
        cutout: '60%',
        animation: {
            animateRotate: true,
            animateScale: true,
            duration: 1500,
            easing: 'easeOutQuart'
        }
    }
});

// Add luxury hover effects to chart
motorStatusChart.options.onHover = function(event, elements) {
    if (elements.length > 0) {
        event.native.target.style.cursor = 'pointer';
    } else {
        event.native.target.style.cursor = 'default';
    }
};

// Animate numbers on page load
document.addEventListener('DOMContentLoaded', function() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const animateNumber = (element, start, end, duration) => {
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
            
            if (element.textContent.includes('Rp')) {
                element.textContent = 'Rp ' + value.toLocaleString('id-ID');
            } else {
                element.textContent = value.toLocaleString('id-ID');
            }
            
            if (value === end) {
                clearInterval(timer);
            }
        };
        
        timer = setInterval(run, stepTime);
        run();
    };
    
    statNumbers.forEach((element, index) => {
        const text = element.textContent;
        const number = parseInt(text.replace(/[^\d]/g, ''));
        if (!isNaN(number)) {
            element.textContent = '0';
            setTimeout(() => {
                animateNumber(element, 0, number, 2000);
            }, index * 200);
        }
    });
});
</script>
@endpush