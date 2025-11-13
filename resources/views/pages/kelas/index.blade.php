{{-- resources/views/pages/kelas/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')
@section('page-subtitle', 'Kelola kelas pesantren')

@section('content')
<!-- Main Card -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
      <div>
        <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
          <i class="bi bi-building-fill text-primary"></i>
          Daftar Kelas
        </h5>
        <p class="text-muted mb-0 small">Kelola dan monitor data kelas</p>
      </div>

      @admin
      <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-2"></i>
        <span>Tambah Kelas</span>
      </a>
      @endadmin
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="alert alert-primary border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
          <i class="bi bi-building fs-2"></i>
          <div>
            <small class="d-block opacity-75">Total Kelas</small>
            <strong class="fs-5">{{ $kelas->total() }}</strong>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert alert-success border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
          <i class="bi bi-people fs-2"></i>
          <div>
            <small class="d-block opacity-75">Total Santri</small>
            <strong class="fs-5">{{ $kelas->sum('santris_count') }}</strong>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert alert-info border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
          <i class="bi bi-person-check fs-2"></i>
          <div>
            <small class="d-block opacity-75">Wali Kelas</small>
            <strong class="fs-5">{{ $kelas->whereNotNull('wali_kelas')->count() }}</strong>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert alert-warning border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
          <i class="bi bi-graph-up fs-2"></i>
          <div>
            <small class="d-block opacity-75">Rata-rata/Kelas</small>
            <strong class="fs-5">{{ $kelas->count() > 0 ? round($kelas->sum('santris_count') / $kelas->count()) : 0 }}</strong>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th style="width: 20%;">Nama Kelas</th>
            <th class="text-center" style="width: 10%;">Tingkat</th>
            <th style="width: 15%;">Tahun Ajaran</th>
            <th style="width: 15%;">Wali Kelas</th>
            <th class="text-center" style="width: 10%;">Jumlah</th>
            <th style="width: 15%;">Kapasitas</th>
            <th class="text-center" style="width: 10%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kelas as $i => $k)
          <tr>
            <td class="text-center fw-semibold">{{ $kelas->firstItem() + $i }}</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                     style="width: 40px; height: 40px;">
                  <i class="bi bi-building text-primary"></i>
                </div>
                <strong>{{ $k->nama_kelas }}</strong>
              </div>
            </td>
            <td class="text-center">
              <span class="badge bg-primary px-3 py-2">
                Tingkat {{ $k->tingkat }}
              </span>
            </td>
            <td>
              <small class="text-muted">
                <i class="bi bi-calendar3 me-1"></i>
                {{ $k->tahun_ajaran }}
              </small>
            </td>
            <td>
              @if($k->waliKelas)
              <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-check-fill text-success"></i>
                <span>{{ $k->waliKelas->username }}</span>
              </div>
              @else
              <span class="badge bg-secondary px-2 py-1">
                <i class="bi bi-dash-circle me-1"></i>
                Belum ada
              </span>
              @endif
            </td>
            <td class="text-center">
              <span class="badge bg-info text-dark fs-6 px-3 py-2">
                {{ $k->santris_count }}
              </span>
            </td>
            <td>
              @php
              $persentase = $k->kapasitas > 0 ? ($k->santris_count / $k->kapasitas) * 100 : 0;
              $colorClass = $persentase >= 100 ? 'bg-danger' : ($persentase >= 80 ? 'bg-warning' : 'bg-success');
              @endphp
              <div class="d-flex align-items-center gap-2">
                <div class="flex-grow-1">
                  <div class="progress" style="height: 24px;">
                    <div class="progress-bar {{ $colorClass }} fw-semibold" 
                         style="width: {{ min($persentase, 100) }}%">
                      {{ $k->santris_count }}/{{ $k->kapasitas }}
                    </div>
                  </div>
                </div>
              </div>
            </td>
            <td class="text-center">
              <div class="btn-group btn-group-sm shadow-sm" role="group">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.kelas.show', $k->id) : route('ustadz.kelas.show', $k->id) }}"
                   class="btn btn-info"
                   data-bs-toggle="tooltip"
                   title="Lihat Detail">
                  <i class="bi bi-eye"></i>
                </a>

                @admin
                <a href="{{ route('admin.kelas.edit', $k->id) }}"
                   class="btn btn-warning"
                   data-bs-toggle="tooltip"
                   title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.kelas.destroy', $k->id) }}" 
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
                <h5>Belum ada data kelas</h5>
                <p class="mb-3">Data kelas akan muncul di sini</p>
                @admin
                <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">
                  <i class="bi bi-plus-circle me-2"></i>
                  Tambah Kelas Pertama
                </a>
                @endadmin
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    {{ $kelas->links('components.pagination') }}
  </div>
</div>

{{-- Quick Info --}}
<div class="row g-3 mt-3">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-info-circle text-primary"></i>
          Informasi
        </h6>
        <ul class="mb-0 ps-3">
          <li class="mb-2">
            <small class="text-muted">
              <strong class="text-success">Hijau:</strong> Kapasitas < 80%
            </small>
          </li>
          <li class="mb-2">
            <small class="text-muted">
              <strong class="text-warning">Kuning:</strong> Kapasitas 80-99%
            </small>
          </li>
          <li>
            <small class="text-muted">
              <strong class="text-danger">Merah:</strong> Kapasitas penuh (≥100%)
            </small>
          </li>
        </ul>
      </div>
    </div>
  </div>

  @admin
  <div class="col-md-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-lightning-fill text-warning"></i>
          Aksi Cepat
        </h6>
        <div class="d-grid gap-2">
          <a href="{{ route('admin.santri.index') }}" 
             class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-between">
            <span>
              <i class="bi bi-people me-2"></i>
              Kelola Santri
            </span>
            <i class="bi bi-arrow-right"></i>
          </a>
          <a href="{{ route('admin.kehadiran.index') }}" 
             class="btn btn-outline-info btn-sm d-flex align-items-center justify-content-between">
            <span>
              <i class="bi bi-calendar-check me-2"></i>
              Input Kehadiran
            </span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
  @endadmin
</div>
@endsection