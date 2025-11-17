{{-- resources/views/pages/kelas/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')
@section('page-subtitle', 'Kelola kelas pesantren')

@section('content')
<!-- Main Card -->
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

<!-- Table -->
<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th>Nama Kelas</th>
        <th>Tingkat</th>
        <th>Tahun Ajaran</th>
        <th>Wali Kelas</th>
        <th>Jumlah</th>
        <th>Kapasitas</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($kelas as $i => $k)
      <tr>
        <td>{{ $k->nama_kelas }}</td>
        <td>Tingkat {{ $k->tingkat }}</td>
        <td>{{ $k->tahun_ajaran }}</td>
        <td>@if($k->waliKelas) {{ $k->waliKelas->username }}@else
          <span class="badge bg-secondary px-2 py-1">
            <i class="bi bi-dash-circle me-1"></i>
            Belum ada
          </span>
          @endif
        </td>
        <td>{{ $k->santris_count }}</td>
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
          <a href="{{ auth()->user()->isAdmin() ? route('admin.kelas.show', $k->id) : route('ustadz.kelas.show', $k->id) }}"
            class="btn btn-sm btn-info"
            data-bs-toggle="tooltip"
            title="Lihat Detail">
            <i class="bi bi-eye"></i>
          </a>

          @admin
          <a href="{{ route('admin.kelas.edit', $k->id) }}"
            class="btn btn-sm btn-warning"
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
              class="btn btn-sm btn-danger"
              data-bs-toggle="tooltip"
              title="Hapus">
              <i class="bi bi-trash"></i>
            </button>
          </form>
          @endadmin
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
@endsection