{{-- resources/views/pages/santri/kehadiran.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Kehadiran Saya')
@section('page-title', 'Kehadiran Saya')
@section('page-subtitle', 'Riwayat kehadiran bulanan')

@section('content')
<!-- Statistics -->
@php
$santri = auth()->user()->santri;
$bulanIni = \Carbon\Carbon::now();
$kehadiranBulanIni = $santri->kehadiran()
->whereMonth('tanggal', $bulanIni->month)
->whereYear('tanggal', $bulanIni->year)
->get();

$stats = [
'hadir' => $kehadiranBulanIni->where('status', 'hadir')->count(),
'izin' => $kehadiranBulanIni->where('status', 'izin')->count(),
'sakit' => $kehadiranBulanIni->where('status', 'sakit')->count(),
'alpa' => $kehadiranBulanIni->where('status', 'alpa')->count(),
];
$totalHari = $kehadiranBulanIni->count();
$persentaseHadir = $totalHari > 0 ? round(($stats['hadir'] / $totalHari) * 100) : 0;
@endphp

<div class="row g-4 mb-4">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-check-circle-fill fs-1 text-success mb-2"></i>
        <div class="display-5 fw-bold text-success">{{ $stats['hadir'] }}</div>
        <small class="text-muted">Hadir</small>
        <div class="progress mt-2" style="height: 6px;">
          <div class="progress-bar bg-success" style="width: {{ $persentaseHadir }}%"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-envelope-fill fs-1 text-info mb-2"></i>
        <div class="display-5 fw-bold text-info">{{ $stats['izin'] }}</div>
        <small class="text-muted">Izin</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-heart-pulse-fill fs-1 text-warning mb-2"></i>
        <div class="display-5 fw-bold text-warning">{{ $stats['sakit'] }}</div>
        <small class="text-muted">Sakit</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-x-circle-fill fs-1 text-danger mb-2"></i>
        <div class="display-5 fw-bold text-danger">{{ $stats['alpa'] }}</div>
        <small class="text-muted">Alpa</small>
      </div>
    </div>
  </div>
</div>

<!-- Summary Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
      <i class="bi bi-graph-up text-primary"></i>
      Ringkasan Bulan Ini
    </h6>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="p-3 bg-primary bg-opacity-10 rounded-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">Total Hari</small>
            <i class="bi bi-calendar3 text-primary"></i>
          </div>
          <div class="fs-3 fw-bold text-primary">{{ $totalHari }}</div>
          <small class="text-muted">{{ $bulanIni->isoFormat('MMMM Y') }}</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-success bg-opacity-10 rounded-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">Persentase Hadir</small>
            <i class="bi bi-percent text-success"></i>
          </div>
          <div class="fs-3 fw-bold text-success">{{ $persentaseHadir }}%</div>
          <div class="progress mt-2" style="height: 8px;">
            <div class="progress-bar bg-success" style="width: {{ $persentaseHadir }}%"></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-warning bg-opacity-10 rounded-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">Total Absen</small>
            <i class="bi bi-exclamation-triangle text-warning"></i>
          </div>
          <div class="fs-3 fw-bold text-warning">{{ $stats['izin'] + $stats['sakit'] + $stats['alpa'] }}</div>
          <small class="text-muted">Izin, Sakit & Alpa</small>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Riwayat Kehadiran -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <i class="bi bi-calendar-check text-primary"></i>
        Riwayat Kehadiran
      </h6>
      <span class="badge bg-primary px-3 py-2">
        {{ $kehadiran->total() }} record
      </span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 8%;">No</th>
            <th style="width: 20%;">Tanggal</th>
            <th class="text-center" style="width: 15%;">Status</th>
            <th class="text-center" style="width: 15%;">Jam Masuk</th>
            <th class="text-center" style="width: 15%;">Jam Keluar</th>
            <th style="width: 27%;">Keterangan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kehadiran as $i => $k)
          <tr>
            <td class="text-center fw-semibold">{{ $kehadiran->firstItem() + $i }}</td>
            <td>
              <div class="fw-semibold">{{ $k->tanggal->isoFormat('dddd') }}</div>
              <small class="text-muted">{{ $k->tanggal->format('d/m/Y') }}</small>
            </td>
            <td class="text-center">
              @php
              $statusConfig = [
              'hadir' => ['color' => 'success', 'icon' => 'check-circle'],
              'izin' => ['color' => 'info', 'icon' => 'envelope'],
              'sakit' => ['color' => 'warning', 'icon' => 'heart-pulse'],
              'alpa' => ['color' => 'danger', 'icon' => 'x-circle'],
              ];
              $config = $statusConfig[$k->status] ?? ['color' => 'secondary', 'icon' => 'dash'];
              @endphp
              <span class="badge bg-{{ $config['color'] }} px-3 py-2">
                <i class="bi bi-{{ $config['icon'] }} me-1"></i>
                {{ ucfirst($k->status) }}
              </span>
            </td>
            <td class="text-center">
              @if($k->jam_masuk)
              <div class="badge bg-light text-dark px-3 py-2">
                <i class="bi bi-clock me-1"></i>
                {{ $k->jam_masuk }}
              </div>
              @else
              <small class="text-muted">-</small>
              @endif
            </td>
            <td class="text-center">
              @if($k->jam_keluar)
              <div class="badge bg-light text-dark px-3 py-2">
                <i class="bi bi-clock-history me-1"></i>
                {{ $k->jam_keluar }}
              </div>
              @else
              <small class="text-muted">-</small>
              @endif
            </td>
            <td>
              @if($k->keterangan)
              <small class="text-muted">{{ $k->keterangan }}</small>
              @else
              <small class="text-muted opacity-50">Tidak ada keterangan</small>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-5">
              <div class="text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <h6>Belum ada data kehadiran</h6>
                <p class="small mb-0">Riwayat kehadiran akan muncul di sini</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    {{ $kehadiran->links('components.pagination') }}
  </div>
</div>

<!-- Info Card -->
<div class="row g-3 mt-3">
  <div class="col-md-12">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-info-circle text-primary"></i>
          Keterangan Status
        </h6>
        <div class="row g-3">
          <div class="col-md-3">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-success px-3 py-2">
                <i class="bi bi-check-circle me-1"></i>Hadir
              </span>
              <small class="text-muted">Hadir di kelas</small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-info px-3 py-2">
                <i class="bi bi-envelope me-1"></i>Izin
              </span>
              <small class="text-muted">Izin tidak hadir</small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-warning px-3 py-2">
                <i class="bi bi-heart-pulse me-1"></i>Sakit
              </span>
              <small class="text-muted">Sakit</small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-danger px-3 py-2">
                <i class="bi bi-x-circle me-1"></i>Alpa
              </span>
              <small class="text-muted">Tidak hadir tanpa keterangan</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection