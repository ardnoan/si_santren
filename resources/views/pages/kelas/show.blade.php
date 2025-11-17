{{-- resources/views/pages/kelas/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Detail Kelas')
@section('page-title', 'Detail Kelas')
@section('page-subtitle', $kelas->nama_kelas)

@section('content')
<!-- Header Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
      <div>
        <div class="d-flex align-items-center gap-3 mb-2">
          <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center"
            style="width: 60px; height: 60px;">
            <i class="bi bi-building fs-1 text-primary"></i>
          </div>
          <div>
            <h4 class="fw-bold mb-1">{{ $kelas->nama_kelas }}</h4>
            <div class="d-flex gap-2">
              <span class="badge bg-primary px-3 py-2">Tingkat {{ $kelas->tingkat }}</span>
              <span class="badge bg-info text-dark px-3 py-2">{{ $kelas->tahun_ajaran }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ auth()->user()->isAdmin() ? route('admin.kelas.index') : route('ustadz.kelas.index') }}"
          class="btn btn-secondary">
          <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        @admin
        <a href="{{ route('admin.kelas.edit', $kelas->id) }}"
          class="btn btn-warning">
          <i class="bi bi-pencil me-2"></i>Edit
        </a>
        @endadmin
      </div>
    </div>
  </div>
</div>

<!-- Info & Stats -->
<div class="row g-4 mb-4">
  <!-- Info Kelas -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-info-circle text-primary"></i>
          Informasi Kelas
        </h6>

        <div class="row g-3">
          <div class="col-6">
            <small class="text-muted d-block mb-1">Nama Kelas</small>
            <strong>{{ $kelas->nama_kelas }}</strong>
          </div>
          <div class="col-6">
            <small class="text-muted d-block mb-1">Tingkat</small>
            <strong>{{ $kelas->tingkat }}</strong>
          </div>
          <div class="col-6">
            <small class="text-muted d-block mb-1">Tahun Ajaran</small>
            <strong>{{ $kelas->tahun_ajaran }}</strong>
          </div>
          <div class="col-6">
            <small class="text-muted d-block mb-1">Wali Kelas</small>
            @if($kelas->waliKelas)
            <span class="badge bg-success px-2 py-1">
              <i class="bi bi-person-check me-1"></i>
              {{ $kelas->waliKelas->username }}
            </span>
            @else
            <span class="badge bg-secondary">Belum ditentukan</span>
            @endif
          </div>
          <div class="col-12">
            <small class="text-muted d-block mb-2">Kapasitas Kelas</small>
            <div class="d-flex align-items-center gap-2 mb-2">
              <strong class="fs-5">{{ $kelas->santris->count() }}</strong>
              <span class="text-muted">dari {{ $kelas->kapasitas }} santri</span>
            </div>
            @php
            $persentase = $kelas->kapasitas > 0 ? ($kelas->santris->count() / $kelas->kapasitas) * 100 : 0;
            $colorClass = $persentase >= 100 ? 'bg-danger' : ($persentase >= 80 ? 'bg-warning' : 'bg-success');
            @endphp
            <div class="progress" style="height: 24px;">
              <div class="progress-bar {{ $colorClass }} fw-semibold"
                style="width: {{ min($persentase, 100) }}%">
                {{ number_format($persentase, 0) }}%
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistik -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-graph-up text-success"></i>
          Statistik Kelas
        </h6>

        <div class="row g-3">
          <div class="col-6">
            <div class="text-center p-3 bg-primary bg-opacity-10 rounded-3">
              <div class="fs-2 fw-bold text-primary mb-1">{{ $kelas->santris->count() }}</div>
              <small class="text-muted">Total Santri</small>
            </div>
          </div>
          <div class="col-6">
            <div class="text-center p-3 bg-info bg-opacity-10 rounded-3">
              <div class="fs-2 fw-bold text-info mb-1">{{ $kelas->santris->where('jenis_kelamin', 'L')->count() }}</div>
              <small class="text-muted">Laki-laki</small>
            </div>
          </div>
          <div class="col-6">
            <div class="text-center p-3 bg-danger bg-opacity-10 rounded-3">
              <div class="fs-2 fw-bold text-danger mb-1">{{ $kelas->santris->where('jenis_kelamin', 'P')->count() }}</div>
              <small class="text-muted">Perempuan</small>
            </div>
          </div>
          <div class="col-6">
            <div class="text-center p-3 bg-success bg-opacity-10 rounded-3">
              <div class="fs-2 fw-bold text-success mb-1">{{ $kelas->kapasitas - $kelas->santris->count() }}</div>
              <small class="text-muted">Sisa Kursi</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Daftar Santri -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <i class="bi bi-people-fill text-primary"></i>
        Daftar Santri ({{ $kelas->santris->count() }})
      </h6>
      <div class="input-group" style="max-width: 300px;">
        <span class="input-group-text border-end-0">
          <i class="bi bi-search text-muted"></i>
        </span>
        <input type="text"
          class="form-control border-start-0 ps-0"
          id="searchSantri"
          placeholder=" Searching...">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle" id="santriTable">
        <thead>
          <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th style="width: 12%;">NIS</th>
            <th class="text-center" style="width: 8%;">Foto</th>
            <th style="width: 25%;">Nama Lengkap</th>
            <th class="text-center" style="width: 8%;">JK</th>
            <th style="width: 20%;">Tempat, Tgl Lahir</th>
            <th class="text-center" style="width: 12%;">Status</th>
            <th class="text-center" style="width: 10%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kelas->santris as $index => $s)
          <tr>
            <td class="text-center fw-semibold">{{ $index + 1 }}</td>
            <td>{{ $s->nomor_induk }}</td>
            <td class="text-center">
              @if($s->foto)
              <img src="{{ asset('storage/' . $s->foto) }}"
                alt="{{ $s->nama_lengkap }}"
                class="rounded-circle shadow-sm"
                width="45" height="45"
                style="object-fit: cover;">
              @else
              <div class="rounded-circle bg-secondary bg-opacity-25 d-inline-flex align-items-center justify-content-center"
                style="width: 45px; height: 45px;">
                <i class="bi bi-person text-secondary"></i>
              </div>
              @endif
            </td>
            <td>
              {{ $s->nama_lengkap }}
              @if($s->nama_panggilan)
              <small class="text-muted">({{ $s->nama_panggilan }})</small>
              @endif
            </td>
            <td class="text-center">
                {{ $s->jenis_kelamin == 'L' ? 'L' : 'P' }}
            </td>
            <td>
              <small class="d-block">{{ $s->tempat_lahir }}</small>
              <small class="text-muted">{{ $s->tanggal_lahir->format('d M Y') }}</small>
              <span class="badge bg-light text-dark ms-2">{{ $s->umur }} th</span>
            </td>
            <td class="text-center">
              @php
              $statusColors = [
              'aktif' => 'success',
              'cuti' => 'warning',
              'lulus' => 'info',
              'keluar' => 'secondary'
              ];
              $badgeColor = $statusColors[$s->status] ?? 'secondary';
              @endphp
              {{ ucfirst($s->status) }}
            </td>
            <td class="text-center">
              <a href="{{ auth()->user()->isAdmin() ? route('admin.santri.show', $s->id) : route('ustadz.santri.show', $s->id) }}"
                class="btn btn-sm btn-info shadow-sm"
                data-bs-toggle="tooltip"
                title="Lihat Detail">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-5">
              <div class="text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <h6>Belum ada santri di kelas ini</h6>
                <p class="small mb-0">Santri akan muncul setelah didaftarkan ke kelas ini</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Simple search functionality
  document.getElementById('searchSantri').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#santriTable tbody tr');

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchValue) ? '' : 'none';
    });
  });
</script>
@endsection