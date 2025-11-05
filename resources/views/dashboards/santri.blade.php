{{-- ========================================== --}}
{{-- FILE 2: resources/views/dashboards/santri.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Dashboard Santri')
@section('page-title', 'Dashboard Santri')
@section('page-subtitle', 'Selamat datang, ' . $santri->nama_lengkap)

@section('content')
<!-- Profile Card -->
<div class="row mb-4">
  <div class="col-md-12">
    <div class="card bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
      <div class="card-body text-white">
        <div class="row align-items-center">
          <div class="col-md-2 text-center">
            @if($santri->foto)
            <img src="{{ asset('storage/' . $santri->foto) }}"
              alt="{{ $santri->nama_lengkap }}"
              class="rounded-circle border border-3 border-white"
              style="width: 120px; height: 120px; object-fit: cover;">
            @else
            <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center"
              style="width: 120px; height: 120px;">
              <i class="bi bi-person fs-1 text-primary"></i>
            </div>
            @endif
          </div>
          <div class="col-md-10">
            <h2 class="mb-2">{{ $santri->nama_lengkap }}</h2>
            <p class="mb-1"><strong>NIS:</strong> {{ $santri->nomor_induk }}</p>
            <p class="mb-1"><strong>Kelas:</strong> {{ $santri->kelas ? $santri->kelas->nama_kelas : 'Belum ada kelas' }}</p>
            <p class="mb-0"><strong>Status:</strong> <span class="badge bg-light text-dark">{{ ucfirst($santri->status) }}</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <h6 class="mb-1">Kehadiran Bulan Ini</h6>
        <h2 class="mb-0">{{ $kehadiranHadir }}</h2>
        <small>dari {{ $kehadiranBulanIni }} hari</small>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-success text-white">
      <div class="card-body">
        <h6 class="mb-1">Total Pembayaran</h6>
        <h2 class="mb-0">{{ number_format($totalPembayaran / 1000000, 1) }}M</h2>
        <small>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</small>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-warning text-white">
      <div class="card-body">
        <h6 class="mb-1">Rata-rata Nilai</h6>
        <h2 class="mb-0">{{ $nilaiRataRata ? number_format($nilaiRataRata, 1) : '-' }}</h2>
        <small>dari semua mata pelajaran</small>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-danger text-white">
      <div class="card-body">
        <h6 class="mb-1">Pembayaran Pending</h6>
        <h2 class="mb-0">{{ $pembayaranPending }}</h2>
        <small>transaksi pending</small>
      </div>
    </div>
  </div>
</div>

<!-- Quick Access -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-3"><i class="bi bi-grid-3x3 me-2"></i>Menu Cepat</h6>
        <div class="row g-3">
          <div class="col-md-3">
            <a href="{{ route('santri.profile') }}" class="btn btn-outline-primary w-100 py-3">
              <i class="bi bi-person fs-1 d-block mb-2"></i>
              <strong>Profil Saya</strong>
            </a>
          </div>
          <div class="col-md-3">
            <a href="{{ route('santri.kehadiran') }}" class="btn btn-outline-info w-100 py-3">
              <i class="bi bi-calendar-check fs-1 d-block mb-2"></i>
              <strong>Kehadiran</strong>
            </a>
          </div>
          <div class="col-md-3">
            <a href="{{ route('santri.nilai') }}" class="btn btn-outline-warning w-100 py-3">
              <i class="bi bi-journal-text fs-1 d-block mb-2"></i>
              <strong>Nilai</strong>
            </a>
          </div>
          <div class="col-md-3">
            <a href="{{ route('santri.pembayaran') }}" class="btn btn-outline-success w-100 py-3">
              <i class="bi bi-cash-coin fs-1 d-block mb-2"></i>
              <strong>Pembayaran</strong>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection