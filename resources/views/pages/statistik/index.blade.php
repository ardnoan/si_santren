

{{-- ===== FILE 2: resources/views/pages/statistik/index.blade.php ===== --}}
@extends('layouts.dashboard')

@section('title', 'Statistik')
@section('page-title', 'Analisis Statistik')
@section('page-subtitle', 'Dashboard analitik pesantren')

@section('content')

<!-- Santri Stats -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-4">Statistik Santri</h6>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="text-center p-3 bg-primary bg-opacity-10 rounded-3">
          <i class="bi bi-people fs-1 text-primary mb-2"></i>
          <div class="display-6 fw-bold text-primary">{{ $totalSantri }}</div>
          <small class="text-muted">Total Santri</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="text-center p-3 bg-info bg-opacity-10 rounded-3">
          <i class="bi bi-gender-male fs-1 text-info mb-2"></i>
          <div class="display-6 fw-bold text-info">{{ $santriLaki }}</div>
          <small class="text-muted">Laki-laki</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="text-center p-3 bg-danger bg-opacity-10 rounded-3">
          <i class="bi bi-gender-female fs-1 text-danger mb-2"></i>
          <div class="display-6 fw-bold text-danger">{{ $santriPerempuan }}</div>
          <small class="text-muted">Perempuan</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="text-center p-3 bg-success bg-opacity-10 rounded-3">
          <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
          <div class="display-6 fw-bold text-success">{{ $santriAktif }}</div>
          <small class="text-muted">Aktif</small>
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
        <h6 class="fw-bold mb-3">Santri Per Kelas</h6>
        <canvas id="santriPerKelasChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Distribusi Gender</h6>
        <canvas id="genderChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Keuangan Stats -->
<div class="row g-4 mb-4">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Trend Pemasukan (6 Bulan)</h6>
        <canvas id="pemasukanTrendChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Trend Pengeluaran (6 Bulan)</h6>
        <canvas id="pengeluaranTrendChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Kehadiran Stats -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-4">Statistik Kehadiran Bulan Ini</h6>
    <div class="row g-3">
      <div class="col-md-3">
        <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center">
          <div class="fs-3 fw-bold text-success">{{ $kehadiranStats['hadir'] ?? 0 }}</div>
          <small class="text-muted">Hadir</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-3 bg-info bg-opacity-10 rounded-3 text-center">
          <div class="fs-3 fw-bold text-info">{{ $kehadiranStats['izin'] ?? 0 }}</div>
          <small class="text-muted">Izin</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-center">
          <div class="fs-3 fw-bold text-warning">{{ $kehadiranStats['sakit'] ?? 0 }}</div>
          <small class="text-muted">Sakit</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-3 bg-danger bg-opacity-10 rounded-3 text-center">
          <div class="fs-3 fw-bold text-danger">{{ $kehadiranStats['alpa'] ?? 0 }}</div>
          <small class="text-muted">Alpa</small>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
// Santri Per Kelas
const kelasData = @json($santriPerKelas);
new Chart(document.getElementById('santriPerKelasChart'), {
  type: 'bar',
  data: {
    labels: kelasData.labels,
    datasets: [{
      label: 'Jumlah Santri',
      data: kelasData.values,
      backgroundColor: 'rgba(52, 152, 219, 0.8)'
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } }
  }
});

// Gender Chart
new Chart(document.getElementById('genderChart'), {
  type: 'doughnut',
  data: {
    labels: ['Laki-laki', 'Perempuan'],
    datasets: [{
      data: [{{ $santriLaki }}, {{ $santriPerempuan }}],
      backgroundColor: ['rgba(52, 152, 219, 0.8)', 'rgba(231, 76, 60, 0.8)']
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } }
  }
});

// Pemasukan Trend
const pemasukanTrend = @json($pemasukanTrend);
new Chart(document.getElementById('pemasukanTrendChart'), {
  type: 'line',
  data: {
    labels: pemasukanTrend.labels,
    datasets: [{
      label: 'Pemasukan',
      data: pemasukanTrend.values,
      borderColor: 'rgb(39, 174, 96)',
      backgroundColor: 'rgba(39, 174, 96, 0.1)',
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } }
  }
});

// Pengeluaran Trend
const pengeluaranTrend = @json($pengeluaranTrend);
new Chart(document.getElementById('pengeluaranTrendChart'), {
  type: 'line',
  data: {
    labels: pengeluaranTrend.labels,
    datasets: [{
      label: 'Pengeluaran',
      data: pengeluaranTrend.values,
      borderColor: 'rgb(231, 76, 60)',
      backgroundColor: 'rgba(231, 76, 60, 0.1)',
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } }
  }
});
</script>
@endsection