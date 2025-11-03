@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan informasi sistem')

@section('content')
<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Santri</p>
                        <h3 class="mb-0">{{ $santriStats['total_aktif'] ?? 0 }}</h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> {{ $santriStats['santri_baru'] ?? 0 }} santri baru
                        </small>
                    </div>
                    <div class="icon bg-primary rounded">
                        <i class="bi bi-people text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Pemasukan</p>
                        <h3 class="mb-0">Rp {{ number_format($pembayaranStats['total_pembayaran'] ?? 0, 0, ',', '.') }}</h3>
                        <small class="text-info">
                            {{ $pembayaranStats['lunas_bulan_ini'] ?? 0 }} transaksi bulan ini
                        </small>
                    </div>
                    <div class="icon bg-success rounded">
                        <i class="bi bi-cash-stack text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Hadir Hari Ini</p>
                        <h3 class="mb-0">{{ $kehadiranToday['hadir'] ?? 0 }}</h3>
                        <small class="text-warning">
                            {{ ($kehadiranToday['izin'] ?? 0) + ($kehadiranToday['sakit'] ?? 0) }} izin/sakit
                        </small>
                    </div>
                    <div class="icon bg-info rounded">
                        <i class="bi bi-calendar-check text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Kelas</p>
                        <h3 class="mb-0">{{ $totalKelas ?? 0 }}</h3>
                        <small class="text-muted">
                            Tahun ajaran aktif
                        </small>
                    </div>
                    <div class="icon bg-warning rounded">
                        <i class="bi bi-building text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Santri per Kelas</h6>
            </div>
            <div class="card-body">
                <canvas id="santriPerKelasChart" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Kehadiran Hari Ini</h6>
            </div>
            <div class="card-body">
                <canvas id="kehadiranChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities & Alerts -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Pembayaran Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Santri</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPembayaran as $p)
                            <tr>
                                <td>{{ $p->tanggal_bayar->format('d/m/Y H:i') }}</td>
                                <td>{{ $p->santri->nama_lengkap }}</td>
                                <td><span class="badge bg-info">{{ $p->jenis_pembayaran_label }}</span></td>
                                <td><strong>{{ $p->jumlah_format }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $p->status == 'lunas' ? 'success' : 'warning' }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data pembayaran</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua Pembayaran <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Notifikasi</h6>
            </div>
            <div class="card-body">
                @php
                    $belumBayarSPP = $santriStats['belum_bayar_spp'] ?? 0;
                    $pendingPembayaran = $pembayaranStats['pending'] ?? 0;
                @endphp
                
                @if($belumBayarSPP > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-circle"></i>
                    <strong>{{ $belumBayarSPP }}</strong> santri belum bayar SPP bulan ini
                    <a href="{{ route('admin.pembayaran.index') }}" class="alert-link">Lihat</a>
                </div>
                @endif
                
                @if($pendingPembayaran > 0)
                <div class="alert alert-info">
                    <i class="bi bi-clock"></i>
                    <strong>{{ $pendingPembayaran }}</strong> pembayaran pending
                    <a href="{{ route('admin.pembayaran.index') }}" class="alert-link">Lihat</a>
                </div>
                @endif
                
                @if($belumBayarSPP == 0 && $pendingPembayaran == 0)
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    Semua pembayaran lancar!
                </div>
                @endif
                
                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('admin.santri.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-people"></i> Kelola Santri
                    </a>
                    <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-calendar-check"></i> Input Kehadiran
                    </a>
                    <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-cash"></i> Input Pembayaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Chart: Santri per Kelas
const santriData = {!! json_encode($santriPerKelas) !!};
const kelasLabels = santriData.map(item => item.kelas);
const kelasValues = santriData.map(item => item.jumlah);

new Chart(document.getElementById('santriPerKelasChart'), {
    type: 'bar',
    data: {
        labels: kelasLabels,
        datasets: [{
            label: 'Jumlah Santri',
            data: kelasValues,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Chart: Kehadiran Pie
const kehadiranData = {!! json_encode($kehadiranToday) !!};
new Chart(document.getElementById('kehadiranChart'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
        datasets: [{
            data: [
                kehadiranData.hadir || 0,
                kehadiranData.izin || 0,
                kehadiranData.sakit || 0,
                kehadiranData.alpa || 0
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(255, 99, 132, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});
</script>
@endsection

@section('styles')
<style>
.stat-card .icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
</style>
@endsection