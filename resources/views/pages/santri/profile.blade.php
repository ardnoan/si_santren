{{-- resources/views/pages/santri/profile.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Santri')
@section('page-subtitle', $santri->nama_lengkap)

@section('content')
<div class="row g-4">
  <!-- Left Column - Profile Card -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4 text-center">
        <!-- Photo -->
        <div class="mb-4">
          @if($santri->foto)
          <img src="{{ asset('storage/' . $santri->foto) }}"
               class="rounded-circle shadow-sm border border-4 border-primary"
               style="width: 150px; height: 150px; object-fit: cover;">
          @else
          <div class="rounded-circle bg-secondary bg-opacity-25 d-inline-flex align-items-center justify-content-center border border-4 border-secondary"
               style="width: 150px; height: 150px;">
            <i class="bi bi-person fs-1 text-secondary"></i>
          </div>
          @endif
        </div>

        <!-- Name & Status -->
        <h4 class="fw-bold mb-2">{{ $santri->nama_lengkap }}</h4>
        <p class="text-muted mb-3">
          <i class="bi bi-card-text me-1"></i>
          {{ $santri->nomor_induk }}
        </p>
        
        @php
        $statusColors = [
          'aktif' => 'success',
          'cuti' => 'warning',
          'lulus' => 'info',
          'keluar' => 'secondary'
        ];
        $badgeColor = $statusColors[$santri->status] ?? 'secondary';
        @endphp
        <span class="badge bg-{{ $badgeColor }} px-4 py-2 fs-6">
          {{ ucfirst($santri->status) }}
        </span>

        <!-- Quick Stats -->
        <div class="row g-3 mt-4">
          <div class="col-6">
            <div class="p-3 bg-primary bg-opacity-10 rounded-3">
              <div class="fs-4 fw-bold text-primary">{{ $santri->umur }}</div>
              <small class="text-muted">Tahun</small>
            </div>
          </div>
          <div class="col-6">
            <div class="p-3 bg-info bg-opacity-10 rounded-3">
              <div class="fs-4 fw-bold text-info">{{ $santri->jenis_kelamin }}</div>
              <small class="text-muted">Jenis Kelamin</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Card -->
    <div class="card border-0 shadow-sm rounded-3 mt-4">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-telephone text-primary"></i>
          Kontak Wali
        </h6>
        <div class="mb-3">
          <small class="text-muted d-block mb-1">Nama Wali</small>
          <strong>{{ $santri->nama_wali }}</strong>
        </div>
        <div class="mb-0">
          <small class="text-muted d-block mb-1">No. Telepon</small>
          <a href="tel:{{ $santri->no_telp_wali }}" class="btn btn-outline-primary btn-sm w-100">
            <i class="bi bi-telephone me-2"></i>
            {{ $santri->no_telp_wali }}
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column - Details -->
  <div class="col-lg-8">
    <!-- Data Pribadi -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
          <i class="bi bi-person-badge text-primary fs-5"></i>
          Data Pribadi
        </h6>

        <div class="row g-4">
          <div class="col-md-6">
            <div class="d-flex gap-3">
              <div class="bg-primary bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center"
                   style="width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-person text-primary"></i>
              </div>
              <div class="flex-grow-1">
                <small class="text-muted d-block mb-1">Nama Lengkap</small>
                <strong>{{ $santri->nama_lengkap }}</strong>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="d-flex gap-3">
              <div class="bg-info bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center"
                   style="width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-card-text text-info"></i>
              </div>
              <div class="flex-grow-1">
                <small class="text-muted d-block mb-1">Nomor Induk</small>
                <strong>{{ $santri->nomor_induk }}</strong>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="d-flex gap-3">
              <div class="bg-success bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center"
                   style="width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-gender-ambiguous text-success"></i>
              </div>
              <div class="flex-grow-1">
                <small class="text-muted d-block mb-1">Jenis Kelamin</small>
                <strong>{{ $santri->jenis_kelamin_label }}</strong>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="d-flex gap-3">
              <div class="bg-warning bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center"
                   style="width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-calendar3 text-warning"></i>
              </div>
              <div class="flex-grow-1">
                <small class="text-muted d-block mb-1">Tempat, Tgl Lahir</small>
                <strong>{{ $santri->tempat_lahir }}, {{ $santri->tanggal_lahir->format('d F Y') }}</strong>
                <div class="badge bg-light text-dark mt-1">{{ $santri->umur }} tahun</div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="d-flex gap-3">
              <div class="bg-danger bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center"
                   style="width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-geo-alt text-danger"></i>
              </div>
              <div class="flex-grow-1">
                <small class="text-muted d-block mb-1">Alamat</small>
                <strong>{{ $santri->alamat }}</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Kelas -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
          <i class="bi bi-building text-success fs-5"></i>
          Informasi Kelas
        </h6>

        @if($santri->kelas)
        <div class="row g-4">
          <div class="col-md-6">
            <div class="d-flex gap-3">
              <div class="bg-primary bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center"
                   style="width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-building text-primary"></i>
              </div>
              <div class="flex-grow-1">
                <small class="text-muted d-block mb-1">Nama Kelas</small>
                <span class="badge bg-info text-dark px-3 py-2">{{ $santri->kelas->nama_kelas }}</span>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="d-flex gap-3">
              <div class="bg-success bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center"
                   style="width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-bar-chart-steps text-success"></i>
              </div>
              <div class="flex-grow-1">
                <small class="text-muted d-block mb-1">Tingkat</small>
                <strong>Tingkat {{ $santri->kelas->tingkat }}</strong>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="d-flex gap-3">
              <div class="bg-info bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center"
                   style="width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-calendar3 text-info"></i>
              </div>
              <div class="flex-grow-1">
                <small class="text-muted d-block mb-1">Tahun Ajaran</small>
                <strong>{{ $santri->kelas->tahun_ajaran }}</strong>
              </div>
            </div>
          </div>

          @if($santri->kelas->waliKelas)
          <div class="col-md-6">
            <div class="d-flex gap-3">
              <div class="bg-warning bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center"
                   style="width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-person-check text-warning"></i>
              </div>
              <div class="flex-grow-1">
                <small class="text-muted d-block mb-1">Wali Kelas</small>
                <strong>{{ $santri->kelas->waliKelas->username }}</strong>
              </div>
            </div>
          </div>
          @endif
        </div>
        @else
        <div class="alert alert-warning border-0 mb-0">
          <i class="bi bi-exclamation-triangle me-2"></i>
          Anda belum terdaftar di kelas manapun
        </div>
        @endif
      </div>
    </div>

    <!-- Quick Links -->
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-link-45deg text-warning"></i>
          Akses Cepat
        </h6>
        <div class="row g-2">
          <div class="col-md-4">
            <a href="{{ route('santri.kehadiran') }}" 
               class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-between">
              <span>
                <i class="bi bi-calendar-check me-2"></i>
                Kehadiran
              </span>
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>
          <div class="col-md-4">
            <a href="{{ route('santri.nilai') }}" 
               class="btn btn-outline-success w-100 d-flex align-items-center justify-content-between">
              <span>
                <i class="bi bi-journal-text me-2"></i>
                Nilai
              </span>
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>
          <div class="col-md-4">
            <a href="{{ route('santri.pembayaran') }}" 
               class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-between">
              <span>
                <i class="bi bi-cash-coin me-2"></i>
                Pembayaran
              </span>
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection