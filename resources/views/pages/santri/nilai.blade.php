{{-- resources/views/pages/santri/nilai.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Nilai Saya')
@section('page-title', 'Nilai Saya')
@section('page-subtitle', 'Daftar nilai akademik')

@section('content')
<!-- Statistics -->
@php
$santri = auth()->user()->santri;
$avgNilai = $nilai->avg('nilai_akhir');
$statsPredikat = [
  'A' => $nilai->where('predikat', 'A')->count(),
  'B' => $nilai->where('predikat', 'B')->count(),
  'C' => $nilai->where('predikat', 'C')->count(),
  'D' => $nilai->where('predikat', 'D')->count(),
  'E' => $nilai->where('predikat', 'E')->count(),
];
$nilaiTertinggi = $nilai->max('nilai_akhir');
$nilaiTerendah = $nilai->min('nilai_akhir');
@endphp

<div class="row g-4 mb-4">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-list-check fs-1 text-primary mb-2"></i>
        <div class="display-5 fw-bold text-primary">{{ $nilai->count() }}</div>
        <small class="text-muted">Total Nilai</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-graph-up fs-1 text-success mb-2"></i>
        <div class="display-5 fw-bold text-success">{{ $avgNilai ? number_format($avgNilai, 1) : '-' }}</div>
        <small class="text-muted">Rata-rata</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-arrow-up-circle fs-1 text-info mb-2"></i>
        <div class="display-5 fw-bold text-info">{{ $nilaiTertinggi ? number_format($nilaiTertinggi, 1) : '-' }}</div>
        <small class="text-muted">Tertinggi</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-arrow-down-circle fs-1 text-warning mb-2"></i>
        <div class="display-5 fw-bold text-warning">{{ $nilaiTerendah ? number_format($nilaiTerendah, 1) : '-' }}</div>
        <small class="text-muted">Terendah</small>
      </div>
    </div>
  </div>
</div>

<!-- Predikat Distribution -->
@if($nilai->count() > 0)
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
      <i class="bi bi-bar-chart text-primary"></i>
      Distribusi Predikat
    </h6>
    <div class="row g-3">
      @foreach(['A' => 'success', 'B' => 'info', 'C' => 'warning', 'D' => 'danger', 'E' => 'dark'] as $pred => $color)
      @php
      $count = $statsPredikat[$pred];
      $percent = $nilai->count() > 0 ? round(($count / $nilai->count()) * 100) : 0;
      @endphp
      <div class="col">
        <div class="text-center">
          <div class="fs-3 fw-bold text-{{ $color }} mb-1">{{ $count }}</div>
          <span class="badge bg-{{ $color }} px-3 py-2 mb-2">{{ $pred }}</span>
          <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-{{ $color }}" style="width: {{ $percent }}%"></div>
          </div>
          <small class="text-muted d-block mt-1">{{ $percent }}%</small>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endif

<!-- Filter -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-4">
    <form method="GET" class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Semester</label>
        <select name="semester" class="form-select shadow-sm">
          <option value="">Semua Semester</option>
          <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
          <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Tahun Ajaran</label>
        <input type="text" 
               name="tahun_ajaran" 
               class="form-control shadow-sm" 
               placeholder="2024/2025"
               value="{{ request('tahun_ajaran') }}">
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-search me-2"></i>Filter
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Daftar Nilai -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <i class="bi bi-journal-text text-primary"></i>
        Daftar Nilai
      </h6>
      <span class="badge bg-primary px-3 py-2">
        {{ $nilai->count() }} nilai
      </span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th style="width: 22%;">Mata Pelajaran</th>
            <th class="text-center" style="width: 10%;">Semester</th>
            <th class="text-center" style="width: 12%;">Tahun</th>
            <th class="text-center" style="width: 10%;">Tugas</th>
            <th class="text-center" style="width: 10%;">UTS</th>
            <th class="text-center" style="width: 10%;">UAS</th>
            <th class="text-center" style="width: 11%;">Akhir</th>
            <th class="text-center" style="width: 10%;">Predikat</th>
          </tr>
        </thead>
        <tbody>
          @forelse($nilai as $i => $n)
          <tr>
            <td class="text-center fw-semibold">{{ $i + 1 }}</td>
            <td>
              <span class="badge bg-info text-dark px-2 py-1">
                {{ $n->mataPelajaran->nama_mapel }}
              </span>
            </td>
            <td class="text-center">
              <small>{{ $n->semester }}</small>
            </td>
            <td class="text-center">
              <small>{{ $n->tahun_ajaran }}</small>
            </td>
            <td class="text-center">
              <strong>{{ number_format($n->nilai_tugas, 1) }}</strong>
            </td>
            <td class="text-center">
              <strong>{{ number_format($n->nilai_uts, 1) }}</strong>
            </td>
            <td class="text-center">
              <strong>{{ number_format($n->nilai_uas, 1) }}</strong>
            </td>
            <td class="text-center">
              <strong class="fs-6 text-primary">{{ number_format($n->nilai_akhir, 2) }}</strong>
            </td>
            <td class="text-center">
              @php
              $predikatColors = [
                'A' => 'success',
                'B' => 'info',
                'C' => 'warning',
                'D' => 'danger',
                'E' => 'dark'
              ];
              $badgeColor = $predikatColors[$n->predikat] ?? 'secondary';
              @endphp
              <span class="badge bg-{{ $badgeColor }} px-3 py-2">
                {{ $n->predikat }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center py-5">
              <div class="text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <h6>Belum ada data nilai</h6>
                <p class="small mb-0">Nilai akan muncul setelah diinput oleh pengajar</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
        @if($nilai->count() > 0)
        <tfoot class="table-light">
          <tr>
            <th colspan="7" class="text-end">Rata-rata:</th>
            <th class="text-center">
              <strong class="fs-5 text-success">{{ number_format($nilai->avg('nilai_akhir'), 2) }}</strong>
            </th>
            <th></th>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>
</div>

<!-- Info Card -->
<div class="row g-3 mt-3">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-calculator text-primary"></i>
          Formula Perhitungan
        </h6>
        <div class="alert alert-light border mb-0">
          <small class="d-block mb-2">
            <strong>Nilai Akhir =</strong>
          </small>
          <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-primary px-3 py-2">(Tugas × 30%)</span>
            <span class="badge bg-secondary px-2 py-2">+</span>
            <span class="badge bg-warning px-3 py-2">(UTS × 30%)</span>
            <span class="badge bg-secondary px-2 py-2">+</span>
            <span class="badge bg-danger px-3 py-2">(UAS × 40%)</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-award text-warning"></i>
          Rentang Predikat
        </h6>
        <div class="d-flex flex-wrap gap-2">
          <span class="badge bg-success px-3 py-2">A: 90-100</span>
          <span class="badge bg-info px-3 py-2">B: 80-89</span>
          <span class="badge bg-warning px-3 py-2">C: 70-79</span>
          <span class="badge bg-danger px-3 py-2">D: 60-69</span>
          <span class="badge bg-dark px-3 py-2">E: <60</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection