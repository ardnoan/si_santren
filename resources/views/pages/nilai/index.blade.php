{{-- resources/views/pages/nilai/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Data Nilai')
@section('page-title', 'Data Nilai Akademik')
@section('page-subtitle', 'Kelola nilai santri')

@section('content')
<!-- Main Card -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
      <div>
        <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
          <i class="bi bi-journal-text-fill text-primary"></i>
          Daftar Nilai
        </h5>
        <p class="text-muted mb-0 small">Monitor dan kelola nilai akademik</p>
      </div>

      @adminOrUstadz
      <a href="{{ auth()->user()->isAdmin() ? route('admin.nilai.create') : route('ustadz.nilai.create') }}" 
         class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-2"></i>
        <span>Input Nilai</span>
      </a>
      @endadminOrUstadz
    </div>

    <!-- Filter -->
    <form method="GET" class="mb-4">
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label fw-semibold small">Semester</label>
          <select name="semester" class="form-select shadow-sm">
            <option value="">Semua Semester</option>
            <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold small">Tahun Ajaran</label>
          <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-end-0">
              <i class="bi bi-calendar3 text-muted"></i>
            </span>
            <input type="text" 
                   name="tahun_ajaran" 
                   class="form-control border-start-0 ps-0" 
                   placeholder="2024/2025" 
                   value="{{ request('tahun_ajaran') }}">
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold small">Mata Pelajaran</label>
          <select name="mapel" class="form-select shadow-sm">
            <option value="">Semua Mapel</option>
            @foreach(\App\Models\MataPelajaran::all() as $m)
            <option value="{{ $m->id }}" {{ request('mapel') == $m->id ? 'selected' : '' }}>
              {{ $m->nama_mapel }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold small">Predikat</label>
          <select name="predikat" class="form-select shadow-sm">
            <option value="">Semua</option>
            <option value="A" {{ request('predikat') == 'A' ? 'selected' : '' }}>A (≥90)</option>
            <option value="B" {{ request('predikat') == 'B' ? 'selected' : '' }}>B (80-89)</option>
            <option value="C" {{ request('predikat') == 'C' ? 'selected' : '' }}>C (70-79)</option>
            <option value="D" {{ request('predikat') == 'D' ? 'selected' : '' }}>D (60-69)</option>
            <option value="E" {{ request('predikat') == 'E' ? 'selected' : '' }}>E (<60)</option>
          </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search me-2"></i>Filter
          </button>
        </div>
      </div>
    </form>

    <!-- Statistics -->
    @php
    $query = \App\Models\Nilai::query();
    if(request('semester')) $query->where('semester', request('semester'));
    if(request('tahun_ajaran')) $query->where('tahun_ajaran', request('tahun_ajaran'));
    if(request('mapel')) $query->where('mata_pelajaran_id', request('mapel'));
    if(request('predikat')) $query->where('predikat', request('predikat'));
    
    $allNilai = $query->get();
    $avgNilai = $allNilai->avg('nilai_akhir');
    $statsPredikat = [
      'A' => $allNilai->where('predikat', 'A')->count(),
      'B' => $allNilai->where('predikat', 'B')->count(),
      'C' => $allNilai->where('predikat', 'C')->count(),
      'D' => $allNilai->where('predikat', 'D')->count(),
      'E' => $allNilai->where('predikat', 'E')->count(),
    ];
    @endphp

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="alert alert-primary border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">Total Nilai</small>
              <strong class="fs-3">{{ $allNilai->count() }}</strong>
            </div>
            <i class="bi bi-list-check fs-2 opacity-50"></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert alert-success border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">Rata-rata</small>
              <strong class="fs-3">{{ $avgNilai ? number_format($avgNilai, 1) : '-' }}</strong>
            </div>
            <i class="bi bi-graph-up fs-2 opacity-50"></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert alert-info border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">Predikat A</small>
              <strong class="fs-3">{{ $statsPredikat['A'] }}</strong>
            </div>
            <i class="bi bi-trophy fs-2 opacity-50"></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert alert-warning border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">Predikat < C</small>
              <strong class="fs-3">{{ $statsPredikat['D'] + $statsPredikat['E'] }}</strong>
            </div>
            <i class="bi bi-exclamation-triangle fs-2 opacity-50"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Predikat Distribution -->
    @if($allNilai->count() > 0)
    <div class="alert alert-light border mb-4">
      <div class="d-flex align-items-center gap-3 mb-2">
        <i class="bi bi-bar-chart fs-4 text-primary"></i>
        <strong>Distribusi Predikat</strong>
      </div>
      <div class="row g-2">
        @foreach(['A' => 'success', 'B' => 'info', 'C' => 'warning', 'D' => 'danger', 'E' => 'dark'] as $pred => $color)
        @php
        $count = $statsPredikat[$pred];
        $percent = $allNilai->count() > 0 ? round(($count / $allNilai->count()) * 100) : 0;
        @endphp
        <div class="col">
          <div class="text-center">
            <div class="fs-5 fw-bold text-{{ $color }}">{{ $count }}</div>
            <small class="text-muted">{{ $pred }}</small>
            <div class="progress mt-1" style="height: 4px;">
              <div class="progress-bar bg-{{ $color }}" style="width: {{ $percent }}%"></div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th style="width: 22%;">Santri</th>
            <th style="width: 18%;">Mata Pelajaran</th>
            <th class="text-center" style="width: 10%;">Semester</th>
            <th style="width: 12%;">Tahun</th>
            <th class="text-center" style="width: 10%;">Nilai Akhir</th>
            <th class="text-center" style="width: 10%;">Predikat</th>
            <th class="text-center" style="width: 13%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @php
          $nilai = $query->with(['santri', 'mataPelajaran'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
          @endphp

          @forelse($nilai as $i => $n)
          <tr>
            <td class="text-center fw-semibold">{{ $nilai->firstItem() + $i }}</td>
            <td>
              <div class="fw-semibold text-dark">{{ $n->santri->nama_lengkap }}</div>
              <small class="text-muted">
                <i class="bi bi-card-text me-1"></i>{{ $n->santri->nomor_induk }}
              </small>
            </td>
            <td>
              <span class="badge bg-info text-dark px-2 py-1">
                {{ $n->mataPelajaran->nama_mapel }}
              </span>
            </td>
            <td class="text-center">
              <small class="text-muted">{{ $n->semester }}</small>
            </td>
            <td>
              <small class="text-muted">{{ $n->tahun_ajaran }}</small>
            </td>
            <td class="text-center">
              <strong class="fs-5">{{ number_format($n->nilai_akhir, 2) }}</strong>
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
              <span class="badge bg-{{ $badgeColor }} px-3 py-2 fs-6">
                {{ $n->predikat }}
              </span>
            </td>
            <td class="text-center">
              <div class="btn-group btn-group-sm shadow-sm" role="group">
                <a href="{{ route('admin.nilai.santri', $n->santri_id) }}" 
                   class="btn btn-info"
                   data-bs-toggle="tooltip"
                   title="Lihat Detail">
                  <i class="bi bi-eye"></i>
                </a>

                @admin
                <a href="{{ route('admin.nilai.edit', $n->id) }}" 
                   class="btn btn-warning"
                   data-bs-toggle="tooltip"
                   title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.nilai.destroy', $n->id) }}" 
                      method="POST"
                      class="d-inline">
                  @csrf 
                  @method('DELETE')
                  <button type="submit" 
                          class="btn btn-danger"
                          data-bs-toggle="tooltip"
                          title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
                @endadmin
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-5">
              <div class="text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <h5>Belum ada data nilai</h5>
                <p class="mb-3">Data nilai akan muncul setelah diinput</p>
                @adminOrUstadz
                <a href="{{ auth()->user()->isAdmin() ? route('admin.nilai.create') : route('ustadz.nilai.create') }}" 
                   class="btn btn-primary">
                  <i class="bi bi-plus-circle me-2"></i>
                  Input Nilai
                </a>
                @endadminOrUstadz
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    {{ $nilai->links('components.pagination') }}
  </div>
</div>

<!-- Info Card -->
<div class="row g-3 mt-3">
  <div class="col-md-12">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-info-circle text-primary"></i>
          Keterangan Predikat
        </h6>
        <div class="row g-3">
          <div class="col-md">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-success px-3 py-2">A</span>
              <small class="text-muted">90 - 100 (Sangat Baik)</small>
            </div>
          </div>
          <div class="col-md">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-info px-3 py-2">B</span>
              <small class="text-muted">80 - 89 (Baik)</small>
            </div>
          </div>
          <div class="col-md">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-warning px-3 py-2">C</span>
              <small class="text-muted">70 - 79 (Cukup)</small>
            </div>
          </div>
          <div class="col-md">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-danger px-3 py-2">D</span>
              <small class="text-muted">60 - 69 (Kurang)</small>
            </div>
          </div>
          <div class="col-md">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-dark px-3 py-2">E</span>
              <small class="text-muted">< 60 (Sangat Kurang)</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection