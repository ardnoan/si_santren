{{-- resources/views/pages/nilai/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Detail Nilai Santri')
@section('page-title', 'Detail Nilai Santri')
@section('page-subtitle', $santri->nama_lengkap)

@section('content')
<!-- Header Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
      <div class="d-flex align-items-center gap-3">
        @if($santri->foto)
        <img src="{{ asset('storage/' . $santri->foto) }}"
             alt="{{ $santri->nama_lengkap }}"
             class="rounded-circle shadow-sm"
             width="70" height="70"
             style="object-fit: cover;">
        @else
        <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center"
             style="width: 70px; height: 70px;">
          <i class="bi bi-person fs-2 text-secondary"></i>
        </div>
        @endif
        <div>
          <h4 class="fw-bold mb-1">{{ $santri->nama_lengkap }}</h4>
          <div class="d-flex gap-2 mb-2">
            <span class="badge bg-primary px-3 py-2">
              <i class="bi bi-card-text me-1"></i>
              {{ $santri->nomor_induk }}
            </span>
            @if($santri->kelas)
            <span class="badge bg-info text-dark px-3 py-2">
              <i class="bi bi-building me-1"></i>
              {{ $santri->kelas->nama_kelas }}
            </span>
            @endif
          </div>
        </div>
      </div>
      <a href="{{ route('admin.nilai.index') }}" 
         class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
      </a>
    </div>
  </div>
</div>

<!-- Statistics -->
@php
$nilaiSantri = $santri->nilai;
$avgNilai = $nilaiSantri->avg('nilai_akhir');
$statsPredikat = [
  'A' => $nilaiSantri->where('predikat', 'A')->count(),
  'B' => $nilaiSantri->where('predikat', 'B')->count(),
  'C' => $nilaiSantri->where('predikat', 'C')->count(),
  'D' => $nilaiSantri->where('predikat', 'D')->count(),
  'E' => $nilaiSantri->where('predikat', 'E')->count(),
];
@endphp

<div class="row g-4 mb-4">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-list-check fs-1 text-primary mb-2"></i>
        <div class="display-6 fw-bold text-primary">{{ $nilaiSantri->count() }}</div>
        <small class="text-muted">Total Nilai</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-graph-up fs-1 text-success mb-2"></i>
        <div class="display-6 fw-bold text-success">{{ $avgNilai ? number_format($avgNilai, 1) : '-' }}</div>
        <small class="text-muted">Rata-rata</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-trophy fs-1 text-warning mb-2"></i>
        <div class="display-6 fw-bold text-warning">{{ $statsPredikat['A'] }}</div>
        <small class="text-muted">Predikat A</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        @php
        $terendah = $nilaiSantri->min('nilai_akhir');
        @endphp
        <i class="bi bi-arrow-down-circle fs-1 text-danger mb-2"></i>
        <div class="display-6 fw-bold text-danger">{{ $terendah ? number_format($terendah, 1) : '-' }}</div>
        <small class="text-muted">Nilai Terendah</small>
      </div>
    </div>
  </div>
</div>

<!-- Predikat Distribution -->
@if($nilaiSantri->count() > 0)
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
      $percent = $nilaiSantri->count() > 0 ? round(($count / $nilaiSantri->count()) * 100) : 0;
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
        <select name="semester" class="form-select">
          <option value="">Semua Semester</option>
          <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
          <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Tahun Ajaran</label>
        <input type="text" 
               name="tahun_ajaran" 
               class="form-control" 
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

<!-- Tabel Nilai -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
      <i class="bi bi-journal-text text-primary"></i>
      Daftar Nilai
    </h6>

    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th style="width: 25%;">Mata Pelajaran</th>
            <th class="text-center" style="width: 10%;">Semester</th>
            <th class="text-center" style="width: 12%;">Tahun</th>
            <th class="text-center" style="width: 10%;">Tugas</th>
            <th class="text-center" style="width: 10%;">UTS</th>
            <th class="text-center" style="width: 10%;">UAS</th>
            <th class="text-center" style="width: 10%;">Akhir</th>
            <th class="text-center" style="width: 8%;">Predikat</th>
          </tr>
        </thead>
        <tbody>
          @php
          $query = $santri->nilai()->with('mataPelajaran');
          if(request('semester')) $query->where('semester', request('semester'));
          if(request('tahun_ajaran')) $query->where('tahun_ajaran', request('tahun_ajaran'));
          $nilaiFiltered = $query->get();
          @endphp

          @forelse($nilaiFiltered as $i => $n)
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
              <strong class="fs-5 text-primary">{{ number_format($n->nilai_akhir, 2) }}</strong>
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
        @if($nilaiFiltered->count() > 0)
        <tfoot class="table-light">
          <tr>
            <th colspan="7" class="text-end">Rata-rata:</th>
            <th class="text-center">
              <strong class="fs-5 text-success">{{ number_format($nilaiFiltered->avg('nilai_akhir'), 2) }}</strong>
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
  <div class="col-md-12">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-info-circle text-primary"></i>
          Keterangan
        </h6>
        <div class="row g-3">
          <div class="col-md-6">
            <strong class="d-block mb-2">Formula Perhitungan:</strong>
            <small class="text-muted">
              Nilai Akhir = (Tugas × 30%) + (UTS × 30%) + (UAS × 40%)
            </small>
          </div>
          <div class="col-md-6">
            <strong class="d-block mb-2">Rentang Predikat:</strong>
            <div class="d-flex flex-wrap gap-2">
              <span class="badge bg-success">A: 90-100</span>
              <span class="badge bg-info">B: 80-89</span>
              <span class="badge bg-warning">C: 70-79</span>
              <span class="badge bg-danger">D: 60-69</span>
              <span class="badge bg-dark">E: 60 </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection