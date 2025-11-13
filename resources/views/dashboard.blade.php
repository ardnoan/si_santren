{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard ' . auth()->user()->role_name)
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->username)

@section('content')

{{-- ==================== ADMIN DASHBOARD ==================== --}}
@if(auth()->user()->isAdmin())

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100 bg-gradient-primary text-white">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Total Santri</h6>
            <h2 class="fw-bold mb-1">{{ $totalSantriAktif }}</h2>
            <small class="opacity-75">dari {{ $totalSantri }} total</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-people fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
      <div class="card-body p-4 text-white">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Total Pemasukan</h6>
            <h2 class="fw-bold mb-1">{{ number_format($totalPemasukan / 1000000, 1) }}M</h2>
            <small class="opacity-75">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-cash-stack fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
      <div class="card-body p-4 text-white">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Bulan Ini</h6>
            <h2 class="fw-bold mb-1">{{ number_format($pemasukaBulanIni / 1000000, 1) }}M</h2>
            <small class="opacity-75">Rp {{ number_format($pemasukaBulanIni, 0, ',', '.') }}</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-calendar-check fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
      <div class="card-body p-4 text-white">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Total Kelas</h6>
            <h2 class="fw-bold mb-1">{{ $totalKelas }}</h2>
            <small class="opacity-75">kelas aktif</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-building fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-bar-chart text-primary"></i>
          Santri Per Kelas
        </h6>
        <div class="chart-container">
          <canvas id="santriPerKelasChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-pie-chart text-success"></i>
          Kehadiran Hari Ini
        </h6>
        <div class="chart-container">
          <canvas id="kehadiranChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Additional Info Row -->
<div class="row g-4 mb-4">
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-gender-ambiguous text-info"></i>
          Distribusi Gender
        </h6>
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted">Laki-laki</span>
            <strong class="text-primary">{{ $santriLaki }} ({{ $totalSantriAktif > 0 ? round($santriLaki/$totalSantriAktif*100) : 0 }}%)</strong>
          </div>
          <div class="progress" style="height: 12px;">
            <div class="progress-bar bg-primary" 
                 style="width: {{ $totalSantriAktif > 0 ? ($santriLaki/$totalSantriAktif*100) : 0 }}%">
            </div>
          </div>
        </div>

        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted">Perempuan</span>
            <strong class="text-danger">{{ $santriPerempuan }} ({{ $totalSantriAktif > 0 ? round($santriPerempuan/$totalSantriAktif*100) : 0 }}%)</strong>
          </div>
          <div class="progress" style="height: 12px;">
            <div class="progress-bar bg-danger" 
                 style="width: {{ $totalSantriAktif > 0 ? ($santriPerempuan/$totalSantriAktif*100) : 0 }}%">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-clock-history text-warning"></i>
          Pembayaran Pending
        </h6>
        <div class="text-center py-4">
          <h1 class="display-4 text-warning mb-2">{{ $pembayaranPending }}</h1>
          <p class="text-muted mb-3">transaksi menunggu konfirmasi</p>
          <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-outline-warning w-100">
            <i class="bi bi-eye me-2"></i>Lihat Detail
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-lightning-fill text-primary"></i>
          Aksi Cepat
        </h6>
        <div class="d-grid gap-2">
          <a href="{{ route('admin.santri.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Santri
          </a>
          <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-success">
            <i class="bi bi-cash me-2"></i>Input Pembayaran
          </a>
          <a href="{{ route('admin.kehadiran.create') }}" class="btn btn-info text-white">
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

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100 bg-gradient-primary text-white">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Total Santri</h6>
            <h2 class="fw-bold mb-1">{{ $totalSantri }}</h2>
            <small class="opacity-75">santri aktif</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-people fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
      <div class="card-body p-4 text-white">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Total Kelas</h6>
            <h2 class="fw-bold mb-1">{{ $totalKelas }}</h2>
            <small class="opacity-75">kelas aktif</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-building fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
      <div class="card-body p-4 text-white">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Kelas Diampu</h6>
            <h2 class="fw-bold mb-1">{{ $kelasDiampu }}</h2>
            <small class="opacity-75">sebagai wali kelas</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-bookmark-check fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
      <div class="card-body p-4 text-white">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Hadir Hari Ini</h6>
            <h2 class="fw-bold mb-1">{{ $kehadiranToday['hadir'] ?? 0 }}</h2>
            <small class="opacity-75">dari total santri</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-calendar-check fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-bar-chart text-primary"></i>
          Santri Per Kelas
        </h6>
        <div class="chart-container">
          <canvas id="santriPerKelasChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-pie-chart text-success"></i>
          Kehadiran Hari Ini
        </h6>
        <div class="chart-container">
          <canvas id="kehadiranChart"></canvas>
        </div>
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
<div class="card border-0 shadow-sm rounded-3 mb-4 bg-gradient-primary text-white">
  <div class="card-body p-4">
    <div class="row align-items-center g-3">
      <div class="col-md-2 text-center">
        @if($santri->foto)
        <img src="{{ asset('storage/' . $santri->foto) }}"
          alt="{{ $santri->nama_lengkap }}"
          class="rounded-circle border border-4 border-white shadow"
          style="width: 120px; height: 120px; object-fit: cover;">
        @else
        <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center border border-4 border-white shadow"
          style="width: 120px; height: 120px;">
          <i class="bi bi-person fs-1 text-primary"></i>
        </div>
        @endif
      </div>
      <div class="col-md-10">
        <h2 class="fw-bold mb-3">{{ $santri->nama_lengkap }}</h2>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-card-text fs-5"></i>
              <div>
                <small class="opacity-75 d-block">NIS</small>
                <strong>{{ $santri->nomor_induk }}</strong>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-building fs-5"></i>
              <div>
                <small class="opacity-75 d-block">Kelas</small>
                <strong>{{ $santri->kelas ? $santri->kelas->nama_kelas : 'Belum ada kelas' }}</strong>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-check-circle fs-5"></i>
              <div>
                <small class="opacity-75 d-block">Status</small>
                <span class="badge bg-light text-dark px-3 py-2">{{ ucfirst($santri->status) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
  <div class="col-lg-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100 bg-gradient-primary text-white">
      <div class="card-body p-4">
        <h6 class="text-white opacity-90 mb-2">Kehadiran Bulan Ini</h6>
        <h2 class="fw-bold mb-1">{{ $kehadiranHadir }}</h2>
        <small class="opacity-75">dari {{ $kehadiranBulanIni }} hari</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
      <div class="card-body p-4 text-white">
        <h6 class="text-white opacity-90 mb-2">Total Pembayaran</h6>
        <h2 class="fw-bold mb-1">{{ number_format($totalPembayaran / 1000000, 1) }}M</h2>
        <small class="opacity-75">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
      <div class="card-body p-4 text-white">
        <h6 class="text-white opacity-90 mb-2">Rata-rata Nilai</h6>
        <h2 class="fw-bold mb-1">{{ $nilaiRataRata ? number_format($nilaiRataRata, 1) : '-' }}</h2>
        <small class="opacity-75">dari semua mata pelajaran</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
      <div class="card-body p-4 text-white">
        <h6 class="text-white opacity-90 mb-2">Pembayaran Pending</h6>
        <h2 class="fw-bold mb-1">{{ $pembayaranPending }}</h2>
        <small class="opacity-75">transaksi pending</small>
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
          backgroundColor: 'rgba(52, 152, 219, 0.8)',
          borderColor: 'rgba(41, 128, 185, 1)',
          borderWidth: 2,
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
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
            'rgba(39, 174, 96, 0.9)',
            'rgba(52, 152, 219, 0.9)',
            'rgba(243, 156, 18, 0.9)',
            'rgba(231, 76, 60, 0.9)'
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 15,
              font: {
                size: 12
              }
            }
          }
        }
      }
    });
  }
</script>
@endif
@endsection