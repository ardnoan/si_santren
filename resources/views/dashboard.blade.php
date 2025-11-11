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
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white">Total Santri</h6>
            <h2 class="text-white">{{ $totalSantriAktif }}</h2>
            <small class="text-white">dari {{ $totalSantri }} total</small>
          </div>
          <i class="bi bi-people"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white">Total Pemasukan</h6>
            <h2 class="text-white">{{ number_format($totalPemasukan / 1000000, 1) }}M</h2>
            <small class="text-white">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</small>
          </div>
          <i class="bi bi-cash-stack"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white">Bulan Ini</h6>
            <h2 class="text-white">{{ number_format($pemasukaBulanIni / 1000000, 1) }}M</h2>
            <small class="text-white">Rp {{ number_format($pemasukaBulanIni, 0, ',', '.') }}</small>
          </div>
          <i class="bi bi-calendar-check"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white">Total Kelas</h6>
            <h2 class="text-white">{{ $totalKelas }}</h2>
            <small class="text-white">kelas aktif</small>
          </div>
          <i class="bi bi-building"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Santri Per Kelas</h6>
      </div>
      <div class="card-body">
        <canvas id="santriPerKelasChart" height="280"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Kehadiran Hari Ini</h6>
      </div>
      <div class="card-body">
        <canvas id="kehadiranChart" height="280"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Additional Info Row -->
<div class="row g-4 mb-4">
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-gender-ambiguous me-2"></i>Distribusi Gender</h6>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Laki-laki</span>
            <strong>{{ $santriLaki }} ({{ $totalSantriAktif > 0 ? round($santriLaki/$totalSantriAktif*100) : 0 }}%)</strong>
          </div>
          <div class="progress" style="height: 12px; border-radius: 6px;">
            <div class="progress-bar" style="width: {{ $totalSantriAktif > 0 ? ($santriLaki/$totalSantriAktif*100) : 0 }}%; background: linear-gradient(90deg, #3498db, #2980b9);">
            </div>
          </div>
        </div>

        <div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Perempuan</span>
            <strong>{{ $santriPerempuan }} ({{ $totalSantriAktif > 0 ? round($santriPerempuan/$totalSantriAktif*100) : 0 }}%)</strong>
          </div>
          <div class="progress" style="height: 12px; border-radius: 6px;">
            <div class="progress-bar" style="width: {{ $totalSantriAktif > 0 ? ($santriPerempuan/$totalSantriAktif*100) : 0 }}%; background: linear-gradient(90deg, #e74c3c, #c0392b);">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Pembayaran Pending</h6>
      </div>
      <div class="card-body text-center py-4">
        <h1 class="display-4 text-warning mb-2">{{ $pembayaranPending }}</h1>
        <p class="text-muted mb-3">transaksi menunggu konfirmasi</p>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-outline-warning">
          <i class="bi bi-eye me-2"></i>Lihat Detail
        </a>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-lightning-fill me-2"></i>Aksi Cepat</h6>
      </div>
      <div class="card-body">
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
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white">Total Santri</h6>
            <h2 class="text-white">{{ $totalSantri }}</h2>
            <small class="text-white">santri aktif</small>
          </div>
          <i class="bi bi-people"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white">Total Kelas</h6>
            <h2 class="text-white">{{ $totalKelas }}</h2>
            <small class="text-white">kelas aktif</small>
          </div>
          <i class="bi bi-building"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white">Kelas Diampu</h6>
            <h2 class="text-white">{{ $kelasDiampu }}</h2>
            <small class="text-white">sebagai wali kelas</small>
          </div>
          <i class="bi bi-bookmark-check"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card stats-card text-white" style="background:linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white">Hadir Hari Ini</h6>
            <h2 class="text-white">{{ $kehadiranToday['hadir'] ?? 0 }}</h2>
            <small class="text-white">dari total santri</small>
          </div>
          <i class="bi bi-calendar-check"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Santri Per Kelas</h6>
      </div>
      <div class="card-body">
        <canvas id="santriPerKelasChart" height="280"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Kehadiran Hari Ini</h6>
      </div>
      <div class="card-body">
        <canvas id="kehadiranChart" height="280"></canvas>
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
  <div class="col-12">
    <div class="card" style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); border: none;">
      <div class="card-body text-white p-4">
        <div class="row align-items-center">
          <div class="col-md-2 text-center mb-3 mb-md-0">
            @if($santri->foto)
            <img src="{{ asset('storage/' . $santri->foto) }}"
              alt="{{ $santri->nama_lengkap }}"
              class="rounded-circle border border-4 border-white"
              style="width: 120px; height: 120px; object-fit: cover; box-shadow: 0 8px 16px rgba(0,0,0,0.2);">
            @else
            <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center border border-4 border-white"
              style="width: 120px; height: 120px; box-shadow: 0 8px 16px rgba(0,0,0,0.2);">
              <i class="bi bi-person fs-1 text-primary"></i>
            </div>
            @endif
          </div>
          <div class="col-md-10">
            <h2 class="mb-3 fw-bold">{{ $santri->nama_lengkap }}</h2>
            <div class="row g-3">
              <div class="col-md-4">
                <div class="d-flex align-items-center">
                  <i class="bi bi-card-text me-2"></i>
                  <div>
                    <small class="opacity-75 d-block">NIS</small>
                    <strong>{{ $santri->nomor_induk }}</strong>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-center">
                  <i class="bi bi-building me-2"></i>
                  <div>
                    <small class="opacity-75 d-block">Kelas</small>
                    <strong>{{ $santri->kelas ? $santri->kelas->nama_kelas : 'Belum ada kelas' }}</strong>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-center">
                  <i class="bi bi-check-circle me-2"></i>
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
  </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
  <div class="col-lg-3 col-md-6">
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
      <div class="card-body">
        <h6 class="text-white">Kehadiran Bulan Ini</h6>
        <h2 class="text-white">{{ $kehadiranHadir }}</h2>
        <small class="text-white">dari {{ $kehadiranBulanIni }} hari</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6">
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
      <div class="card-body">
        <h6 class="text-white">Total Pembayaran</h6>
        <h2 class="text-white">{{ number_format($totalPembayaran / 1000000, 1) }}M</h2>
        <small class="text-white">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6">
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
      <div class="card-body">
        <h6 class="text-white">Rata-rata Nilai</h6>
        <h2 class="text-white">{{ $nilaiRataRata ? number_format($nilaiRataRata, 1) : '-' }}</h2>
        <small class="text-white">dari semua mata pelajaran</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6">
    <div class="card stats-card text-white" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
      <div class="card-body">
        <h6 class="text-white">Pembayaran Pending</h6>
        <h2 class="text-white">{{ $pembayaranPending }}</h2>
        <small class="text-white">transaksi pending</small>
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
        maintainAspectRatio: false,
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
        maintainAspectRatio: false,
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