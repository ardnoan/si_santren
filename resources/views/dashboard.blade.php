{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard ' . auth()->user()->role_name)
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->username)

@section('content')

{{-- ==================== ADMIN DASHBOARD ==================== --}}
@if(auth()->user()->isAdmin())
<!-- Statistik Cards -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">Total Santri</h6>
            <h2 class="mb-0">{{ $totalSantriAktif }}</h2>
            <small>dari {{ $totalSantri }} total</small>
          </div>
          <i class="bi bi-people fs-1 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-success text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">Total Pemasukan</h6>
            <h2 class="mb-0">{{ number_format($totalPemasukan / 1000000, 1) }}M</h2>
            <small>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</small>
          </div>
          <i class="bi bi-cash-stack fs-1 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-warning text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">Bulan Ini</h6>
            <h2 class="mb-0">{{ number_format($pemasukaBulanIni / 1000000, 1) }}M</h2>
            <small>Rp {{ number_format($pemasukaBulanIni, 0, ',', '.') }}</small>
          </div>
          <i class="bi bi-calendar-check fs-1 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-info text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">Total Kelas</h6>
            <h2 class="mb-0">{{ $totalKelas }}</h2>
            <small>kelas aktif</small>
          </div>
          <i class="bi bi-building fs-1 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Santri Per Kelas</h5>
      </div>
      <div class="card-body">
        <canvas id="santriPerKelasChart" height="250"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Kehadiran Hari Ini</h5>
      </div>
      <div class="card-body">
        <canvas id="kehadiranChart" height="250"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Additional Stats -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-3"><i class="bi bi-gender-ambiguous me-2"></i>Distribusi Gender</h6>
        <div class="d-flex justify-content-between mb-2">
          <span>Laki-laki:</span>
          <strong>{{ $santriLaki }} ({{ $totalSantriAktif > 0 ? round($santriLaki/$totalSantriAktif*100) : 0 }}%)</strong>
        </div>
        <div class="progress mb-3" style="height: 20px;">
          <div class="progress-bar bg-primary" style="width: {{ $totalSantriAktif > 0 ? ($santriLaki/$totalSantriAktif*100) : 0 }}%">
            {{ $santriLaki }}
          </div>
        </div>

        <div class="d-flex justify-content-between mb-2">
          <span>Perempuan:</span>
          <strong>{{ $santriPerempuan }} ({{ $totalSantriAktif > 0 ? round($santriPerempuan/$totalSantriAktif*100) : 0 }}%)</strong>
        </div>
        <div class="progress" style="height: 20px;">
          <div class="progress-bar bg-danger" style="width: {{ $totalSantriAktif > 0 ? ($santriPerempuan/$totalSantriAktif*100) : 0 }}%">
            {{ $santriPerempuan }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-3"><i class="bi bi-clock-history me-2"></i>Pembayaran Pending</h6>
        <div class="text-center py-3">
          <h1 class="display-4 text-warning">{{ $pembayaranPending }}</h1>
          <p class="text-muted mb-0">transaksi menunggu konfirmasi</p>
        </div>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-outline-warning w-100 mt-3">
          <i class="bi bi-eye me-2"></i>Lihat Detail
        </a>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-3"><i class="bi bi-lightning-fill me-2"></i>Aksi Cepat</h6>
        <div class="d-grid gap-2">
          <a href="{{ route('admin.santri.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Santri
          </a>
          <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-success">
            <i class="bi bi-cash me-2"></i>Input Pembayaran
          </a>
          <a href="{{ route('admin.kehadiran.create') }}" class="btn btn-info">
            <i class="bi bi-calendar-check me-2"></i>Input Kehadiran
          </a>
          <a href="{{ route('admin.nilai.create') }}" class="btn btn-warning">
            <i class="bi bi-journal-text me-2"></i>Input Nilai
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ==================== USTADZ DASHBOARD ==================== --}}
@elseif(auth()->user()->isUstadz())
<!-- Statistik Cards -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">Total Santri</h6>
            <h2 class="mb-0">{{ $totalSantri }}</h2>
            <small>santri aktif</small>
          </div>
          <i class="bi bi-people fs-1 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-success text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">Total Kelas</h6>
            <h2 class="mb-0">{{ $totalKelas }}</h2>
            <small>kelas aktif</small>
          </div>
          <i class="bi bi-building fs-1 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-info text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">Kelas Diampu</h6>
            <h2 class="mb-0">{{ $kelasDiampu }}</h2>
            <small>sebagai wali kelas</small>
          </div>
          <i class="bi bi-bookmark-check fs-1 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-warning text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">Hadir Hari Ini</h6>
            <h2 class="mb-0">{{ $kehadiranToday['hadir'] ?? 0 }}</h2>
            <small>dari total santri</small>
          </div>
          <i class="bi bi-calendar-check fs-1 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Santri Per Kelas</h5>
      </div>
      <div class="card-body">
        <canvas id="santriPerKelasChart" height="250"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Kehadiran Hari Ini</h5>
      </div>
      <div class="card-body">
        <canvas id="kehadiranChart" height="250"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- ==================== SANTRI DASHBOARD ==================== --}}
@else
@php
$santri = auth()->user()->santri;
@endphp

<!-- Profile Card -->
<div class="row mb-4">
  <div class="col-md-12">
    <div class="card bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
      <div class="card-body text-white">
        <div class="row align-items-center">
          <div class="col-md-2 text-center">
            @if($santri->foto)
            <img src="{{ asset('storage/' . $santri->foto) }}"
              alt="{{ $santri->nama_lengkap }}"
              class="rounded-circle border border-3 border-white"
              style="width: 120px; height: 120px; object-fit: cover;">
            @else
            <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center"
              style="width: 120px; height: 120px;">
              <i class="bi bi-person fs-1 text-primary"></i>
            </div>
            @endif
          </div>
          <div class="col-md-10">
            <h2 class="mb-2">{{ $santri->nama_lengkap }}</h2>
            <p class="mb-1"><strong>NIS:</strong> {{ $santri->nomor_induk }}</p>
            <p class="mb-1"><strong>Kelas:</strong> {{ $santri->kelas ? $santri->kelas->nama_kelas : 'Belum ada kelas' }}</p>
            <p class="mb-0"><strong>Status:</strong> <span class="badge bg-light text-dark">{{ ucfirst($santri->status) }}</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <h6 class="mb-1">Kehadiran Bulan Ini</h6>
        <h2 class="mb-0">{{ $kehadiranHadir }}</h2>
        <small>dari {{ $kehadiranBulanIni }} hari</small>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-success text-white">
      <div class="card-body">
        <h6 class="mb-1">Total Pembayaran</h6>
        <h2 class="mb-0">{{ number_format($totalPembayaran / 1000000, 1) }}M</h2>
        <small>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</small>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-warning text-white">
      <div class="card-body">
        <h6 class="mb-1">Rata-rata Nilai</h6>
        <h2 class="mb-0">{{ $nilaiRataRata ? number_format($nilaiRataRata, 1) : '-' }}</h2>
        <small>dari semua mata pelajaran</small>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-danger text-white">
      <div class="card-body">
        <h6 class="mb-1">Pembayaran Pending</h6>
        <h2 class="mb-0">{{ $pembayaranPending }}</h2>
        <small>transaksi pending</small>
      </div>
    </div>
  </div>
</div>

@endif

@endsection

@section('scripts')
@if(auth()->user()->isAdmin() || auth()->user()->isUstadz())
<script>
  const santriPerKelas = @json($santriPerKelas);
  const kehadiranToday = @json($kehadiranToday);

  // Chart: Santri Per Kelas
  if (santriPerKelas.length > 0) {
    const ctx1 = document.getElementById('santriPerKelasChart').getContext('2d');
    new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: santriPerKelas.map(item => item.kelas),
        datasets: [{
          label: 'Jumlah Santri',
          data: santriPerKelas.map(item => item.jumlah),
          backgroundColor: 'rgba(54, 162, 235, 0.5)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }

  // Chart: Kehadiran
  const totalKehadiran = (kehadiranToday.hadir || 0) + (kehadiranToday.izin || 0) +
    (kehadiranToday.sakit || 0) + (kehadiranToday.alpa || 0);

  if (totalKehadiran > 0) {
    const ctx2 = document.getElementById('kehadiranChart').getContext('2d');
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
        datasets: [{
          data: [
            kehadiranToday.hadir || 0,
            kehadiranToday.izin || 0,
            kehadiranToday.sakit || 0,
            kehadiranToday.alpa || 0
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
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  }
</script>
@endif
@endsection