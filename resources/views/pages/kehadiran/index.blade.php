{{-- resources/views/pages/kehadiran/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Data Kehadiran')
@section('page-title', 'Data Kehadiran')
@section('page-subtitle', 'Monitoring kehadiran santri')

@section('content')
<!-- Main Card -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
      <div>
        <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
          <i class="bi bi-calendar-check-fill text-primary"></i>
          Kehadiran Santri
        </h5>
        <p class="text-muted mb-0 small">Monitor dan kelola kehadiran harian</p>
      </div>

      @adminOrUstadz
      <a href="{{ auth()->user()->isAdmin() ? route('admin.kehadiran.create') : route('ustadz.kehadiran.create') }}"
        class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-2"></i>
        <span>Input Kehadiran</span>
      </a>
      @endadminOrUstadz
    </div>

    <!-- Date Filter -->
    <form method="GET" class="mb-4">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-semibold small">Pilih Tanggal</label>
          <div class="input-group shadow-sm">
            <span class="input-group-text border-end-0">
              <i class="bi bi-calendar3 text-muted"></i>
            </span>
            <input type="date"
              name="tanggal"
              class="form-control border-start-0 ps-0"
              value="{{ request('tanggal', date('Y-m-d')) }}">
          </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search me-2"></i>Filter
          </button>
        </div>
        <div class="col-md-7 d-flex align-items-end justify-content-end">
          <div class="btn-group shadow-sm" role="group">
            <a href="?tanggal={{ date('Y-m-d', strtotime('-1 day', strtotime(request('tanggal', date('Y-m-d'))))) }}"
              class="btn btn-outline-secondary">
              <i class="bi bi-chevron-left"></i> Kemarin
            </a>
            <a href="?tanggal={{ date('Y-m-d') }}"
              class="btn btn-outline-primary">
              Hari Ini
            </a>
            <a href="?tanggal={{ date('Y-m-d', strtotime('+1 day', strtotime(request('tanggal', date('Y-m-d'))))) }}"
              class="btn btn-outline-secondary">
              Besok <i class="bi bi-chevron-right"></i>
            </a>
          </div>
        </div>
      </div>
    </form>

    <!-- Statistics -->
    @php
    $tanggal = request('tanggal', date('Y-m-d'));
    $stats = [
    'hadir' => \App\Models\Kehadiran::whereDate('tanggal', $tanggal)->where('status', 'hadir')->count(),
    'izin' => \App\Models\Kehadiran::whereDate('tanggal', $tanggal)->where('status', 'izin')->count(),
    'sakit' => \App\Models\Kehadiran::whereDate('tanggal', $tanggal)->where('status', 'sakit')->count(),
    'alpa' => \App\Models\Kehadiran::whereDate('tanggal', $tanggal)->where('status', 'alpa')->count(),
    ];
    $total = array_sum($stats);
    @endphp

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="alert-success border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">
                <i class="bi bi-check-circle me-1"></i>Hadir
              </small>
              <strong class="fs-3">{{ $stats['hadir'] }}</strong>
            </div>
            @if($total > 0)
            <div class="text-end">
              <small class="badge bg-success bg-opacity-25 text-success">
                {{ round(($stats['hadir'] / $total) * 100) }}%
              </small>
            </div>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert-info border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">
                <i class="bi bi-envelope me-1"></i>Izin
              </small>
              <strong class="fs-3">{{ $stats['izin'] }}</strong>
            </div>
            @if($total > 0)
            <div class="text-end">
              <small class="badge bg-info bg-opacity-25 text-info">
                {{ round(($stats['izin'] / $total) * 100) }}%
              </small>
            </div>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert-warning border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">
                <i class="bi bi-heart-pulse me-1"></i>Sakit
              </small>
              <strong class="fs-3">{{ $stats['sakit'] }}</strong>
            </div>
            @if($total > 0)
            <div class="text-end">
              <small class="badge bg-warning bg-opacity-25 text-warning">
                {{ round(($stats['sakit'] / $total) * 100) }}%
              </small>
            </div>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert-danger border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">
                <i class="bi bi-x-circle me-1"></i>Alpa
              </small>
              <strong class="fs-3">{{ $stats['alpa'] }}</strong>
            </div>
            @if($total > 0)
            <div class="text-end">
              <small class="badge bg-danger bg-opacity-25 text-danger">
                {{ round(($stats['alpa'] / $total) * 100) }}%
              </small>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Info Tanggal -->
    <div class="alert-light border mb-4 d-flex align-items-center gap-3">
      <i class="bi bi-calendar3 fs-3 text-primary"></i>
      <div>
        <strong class="d-block">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</strong>
        <small class="text-muted">Total {{ $total }} kehadiran tercatat</small>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th style="width: 20%;">Santri</th>
            <th class="text-center" style="width: 12%;">Kelas</th>
            <th class="text-center" style="width: 10%;">Status</th>
            <th style="width: 15%;">Waktu</th>
            <th style="width: 28%;">Keterangan</th>
            <th class="text-center" style="width: 10%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @php
          $kehadiranData = \App\Models\Kehadiran::with(['santri.kelas'])
          ->whereDate('tanggal', $tanggal)
          ->orderBy('created_at', 'desc')
          ->paginate(20);
          @endphp

          @forelse($kehadiranData as $index => $k)
          <tr>
            <td class="text-center fw-semibold">{{ $kehadiranData->firstItem() + $index }}</td>
            <td>
              <div class="fw-semibold text-dark">{{ $k->santri->nama_lengkap }}</div>
              <small class="text-muted">
                <i class="bi bi-card-text me-1"></i>{{ $k->santri->nomor_induk }}
              </small>
            </td>
            <td class="text-center">
              @if($k->santri->kelas)
              <span class="badge bg-info text-dark px-3 py-2">
                {{ $k->santri->kelas->nama_kelas }}
              </span>
              @else
              <span class="badge bg-secondary">-</span>
              @endif
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
            <td>
              @if($k->jam_masuk)
              <div class="small mb-1">
                <i class="bi bi-box-arrow-in-right text-success me-1"></i>
                {{ $k->jam_masuk }}
              </div>
              @endif
              @if($k->jam_keluar)
              <div class="small">
                <i class="bi bi-box-arrow-left text-danger me-1"></i>
                {{ $k->jam_keluar }}
              </div>
              @endif
              @if(!$k->jam_masuk && !$k->jam_keluar)
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
            <td class="text-center">
              <div class="btn-group btn-group-sm shadow-sm" role="group">
                @canEdit('kehadiran')
                <a href="{{ route('admin.kehadiran.edit', $k->id) }}"
                  class="btn btn-warning"
                  data-bs-toggle="tooltip"
                  title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                @endcanEdit

                @canDelete('kehadiran')
                <form action="{{ route('admin.kehadiran.destroy', $k->id) }}"
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
                @endcanDelete
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-5">
              <div class="text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <h5>Belum ada data kehadiran</h5>
                <p class="mb-3">Belum ada kehadiran tercatat untuk tanggal ini</p>
                @adminOrUstadz
                <a href="{{ auth()->user()->isAdmin() ? route('admin.kehadiran.create') : route('ustadz.kehadiran.create') }}"
                  class="btn btn-primary">
                  <i class="bi bi-plus-circle me-2"></i>
                  Input Kehadiran
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
    {{ $kehadiranData->links('components.pagination') }}

  </div>
</div>
@endsection