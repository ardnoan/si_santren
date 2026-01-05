{{-- resources/views/dashboard.blade.php - REFACTORED --}}
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

{{-- ==================== BENDAHARA DASHBOARD ==================== --}}
@elseif(auth()->user()->isBendahara())

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100 bg-gradient-primary text-white">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Saldo Kas</h6>
            <h2 class="fw-bold mb-1">{{ number_format($saldoKas / 1000000, 1) }}M</h2>
            <small class="opacity-75">Rp {{ number_format($saldoKas, 0, ',', '.') }}</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-wallet2 fs-2"></i>
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
            <h6 class="text-white opacity-90 mb-2">Pemasukan Bulan Ini</h6>
            <h2 class="fw-bold mb-1">{{ number_format($pemasukanBulanIni / 1000000, 1) }}M</h2>
            <small class="opacity-75">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-arrow-down-circle fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
      <div class="card-body p-4 text-white">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Pengeluaran Bulan Ini</h6>
            <h2 class="fw-bold mb-1">{{ number_format($pengeluaranBulanIni / 1000000, 1) }}M</h2>
            <small class="opacity-75">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-arrow-up-circle fs-2"></i>
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
            <h6 class="text-white opacity-90 mb-2">Pending Verifikasi</h6>
            <h2 class="fw-bold mb-1">{{ $pembayaranPending }}</h2>
            <small class="opacity-75">transaksi menunggu</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-clock-history fs-2"></i>
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
        <h6 class="fw-bold mb-3">Pemasukan vs Pengeluaran (7 Hari)</h6>
        <canvas id="cashFlowChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Kategori Pengeluaran Bulan Ini</h6>
        <canvas id="pengeluaranChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-3">Aksi Cepat</h6>
    <div class="row g-2">
      <div class="col-md-3">
        <a href="{{ route('bendahara.pembayaran.index') }}" class="btn btn-outline-primary w-100">
          <i class="bi bi-check-circle me-2"></i>Verifikasi Pembayaran
        </a>
      </div>
      <div class="col-md-3">
        <a href="{{ route('bendahara.pengeluaran.create') }}" class="btn btn-outline-danger w-100">
          <i class="bi bi-plus-circle me-2"></i>Input Pengeluaran
        </a>
      </div>
      <div class="col-md-3">
        <a href="{{ route('bendahara.kas.index') }}" class="btn btn-outline-success w-100">
          <i class="bi bi-wallet2 me-2"></i>Lihat Kas
        </a>
      </div>
      <div class="col-md-3">
        <a href="{{ route('bendahara.santri.index') }}" class="btn btn-outline-info w-100">
          <i class="bi bi-people me-2"></i>Data Santri
        </a>
      </div>
    </div>
  </div>
</div>

{{-- ==================== PEMIMPIN DASHBOARD ==================== --}}
@elseif(auth()->user()->isPemimpin())

<!-- Key Metrics -->
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
            <h6 class="text-white opacity-90 mb-2">Saldo Kas</h6>
            <h2 class="fw-bold mb-1">{{ number_format($saldoKas / 1000000, 1) }}M</h2>
            <small class="opacity-75">Rp {{ number_format($saldoKas, 0, ',', '.') }}</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-wallet2 fs-2"></i>
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
            <h6 class="text-white opacity-90 mb-2">Pemasukan Bulan Ini</h6>
            <h2 class="fw-bold mb-1">{{ number_format($pemasukanBulanIni / 1000000, 1) }}M</h2>
            <small class="opacity-75">total pemasukan</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-arrow-down-circle fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-3 h-100" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
      <div class="card-body p-4 text-white">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-white opacity-90 mb-2">Pending Approval</h6>
            <h2 class="fw-bold mb-1">{{ $pendingApproval }}</h2>
            <small class="opacity-75">pengeluaran menunggu</small>
          </div>
          <div class="bg-white bg-opacity-25 rounded-3 p-3">
            <i class="bi bi-clock-history fs-2"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Trend Keuangan (6 Bulan Terakhir)</h6>
        <canvas id="trendKeuanganChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Kategori Pengeluaran</h6>
        <canvas id="kategoriPengeluaranChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-3">Aksi Cepat</h6>
    <div class="d-grid gap-2">
      <a href="{{ route('pemimpin.laporan.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-file-earmark-text me-2"></i>Lihat Laporan
      </a>
      <a href="{{ route('pemimpin.statistik.index') }}" class="btn btn-outline-success">
        <i class="bi bi-graph-up me-2"></i>Analisis Statistik
      </a>
      @if($pendingApproval > 0)
      <a href="{{ route('pemimpin.pengeluaran.index') }}" class="btn btn-outline-warning">
        <i class="bi bi-exclamation-circle me-2"></i>Approval Pengeluaran ({{ $pendingApproval }})
      </a>
      @endif
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
        <img src="{{ asset('storage/santri/' . $santri->foto) }}"
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
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
          x: { grid: { display: false } }
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
        plugins: { legend: { position: 'bottom', labels: { padding: 15, font: { size: 12 } } } }
      }
    });
  }
</script>
@endif

@if(auth()->user()->isBendahara())
<script>
// Cash Flow Chart
const cashFlowData = @json($cashFlowData);
new Chart(document.getElementById('cashFlowChart'), {
  type: 'line',
  data: {
    labels: cashFlowData.labels,
    datasets: [{
      label: 'Pemasukan',
      data: cashFlowData.pemasukan,
      borderColor: 'rgb(39, 174, 96)',
      backgroundColor: 'rgba(39, 174, 96, 0.1)',
      tension: 0.4
    }, {
      label: 'Pengeluaran',
      data: cashFlowData.pengeluaran,
      borderColor: 'rgb(231, 76, 60)',
      backgroundColor: 'rgba(231, 76, 60, 0.1)',
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } },
    scales: { y: { beginAtZero: true } }
  }
});

// Pengeluaran Chart
const pengeluaranData = @json($pengeluaranData);
if (pengeluaranData.total > 0) {
  new Chart(document.getElementById('pengeluaranChart'), {
    type: 'doughnut',
    data: {
      labels: pengeluaranData.labels,
      datasets: [{
        data: pengeluaranData.values,
        backgroundColor: [
          'rgba(52, 152, 219, 0.8)',
          'rgba(46, 204, 113, 0.8)',
          'rgba(241, 196, 15, 0.8)',
          'rgba(230, 126, 34, 0.8)',
          'rgba(155, 89, 182, 0.8)'
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } }
    }
  });
}
</script>
@endif

@if(auth()->user()->isPemimpin())
<script>
// Trend Keuangan
const trendData = @json($trendKeuangan);
new Chart(document.getElementById('trendKeuanganChart'), {
  type: 'line',
  data: {
    labels: trendData.labels,
    datasets: [{
      label: 'Pemasukan',
      data: trendData.pemasukan,
      borderColor: 'rgb(39, 174, 96)',
      backgroundColor: 'rgba(39, 174, 96, 0.1)',
      tension: 0.4
    }, {
      label: 'Pengeluaran',
      data: trendData.pengeluaran,
      borderColor: 'rgb(231, 76, 60)',
      backgroundColor: 'rgba(231, 76, 60, 0.1)',
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } }
  }
});

// Kategori Pengeluaran
const kategoriData = @json($kategoriPengeluaran);
new Chart(document.getElementById('kategoriPengeluaranChart'), {
  type: 'doughnut',
  data: {
    labels: kategoriData.labels,
    datasets: [{
      data: kategoriData.values,
      backgroundColor: [
        'rgba(52, 152, 219, 0.8)',
        'rgba(46, 204, 113, 0.8)',
        'rgba(241, 196, 15, 0.8)',
        'rgba(230, 126, 34, 0.8)',
        'rgba(155, 89, 182, 0.8)'
      ]
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } }
  }
});
</script>
@endif
@endsection