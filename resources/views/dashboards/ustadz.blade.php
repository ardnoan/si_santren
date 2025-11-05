{{-- FILE 1: resources/views/dashboards/ustadz.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Dashboard Ustadz')
@section('page-title', 'Dashboard Ustadz')
@section('page-subtitle', 'Selamat datang, ' . Auth::user()->username)

@section('content')
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

<!-- Quick Actions -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-3"><i class="bi bi-lightning-fill me-2"></i>Aksi Cepat</h6>
        <div class="row g-2">
          <div class="col-md-3">
            <a href="{{ route('ustadz.santri.index') }}" class="btn btn-outline-primary w-100">
              <i class="bi bi-people"></i> Lihat Data Santri
            </a>
          </div>
          <div class="col-md-3">
            <a href="{{ route('ustadz.kehadiran.create') }}" class="btn btn-outline-info w-100">
              <i class="bi bi-calendar-check"></i> Input Kehadiran
            </a>
          </div>
          <div class="col-md-3">
            <a href="{{ route('ustadz.nilai.create') }}" class="btn btn-outline-warning w-100">
              <i class="bi bi-journal-text"></i> Input Nilai
            </a>
          </div>
          <div class="col-md-3">
            <a href="{{ route('ustadz.kelas.index') }}" class="btn btn-outline-success w-100">
              <i class="bi bi-building"></i> Lihat Kelas
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
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
@endsection