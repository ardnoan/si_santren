{{-- resources/views/pages/kas/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Detail Transaksi Kas')
@section('page-title', 'Detail Transaksi Kas')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <!-- Header dengan Jenis -->
        <div class="mb-4 p-3 rounded-3 {{ $kas->jenis == 'masuk' ? 'bg-success' : 'bg-danger' }} bg-opacity-10">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="fw-bold mb-1 {{ $kas->jenis == 'masuk' ? 'text-success' : 'text-danger' }}">
                @if($kas->jenis == 'masuk')
                <i class="bi bi-arrow-down-circle me-2"></i>Pemasukan
                @else
                <i class="bi bi-arrow-up-circle me-2"></i>Pengeluaran
                @endif
              </h6>
              <small class="text-muted">{{ $kas->tanggal->format('d F Y, H:i') }}</small>
            </div>
            <div class="text-end">
              <h3 class="fw-bold mb-0 {{ $kas->jenis == 'masuk' ? 'text-success' : 'text-danger' }}">
                Rp {{ number_format($kas->jumlah, 0, ',', '.') }}
              </h3>
            </div>
          </div>
        </div>

        <!-- Detail -->
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Kategori</small>
            <span class="badge bg-secondary px-3 py-2">{{ ucfirst($kas->kategori) }}</span>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Saldo Setelah Transaksi</small>
            <strong class="fs-5">Rp {{ number_format($kas->saldo, 0, ',', '.') }}</strong>
          </div>
          <div class="col-12">
            <small class="text-muted d-block mb-1">Keterangan</small>
            <p class="mb-0">{{ $kas->keterangan }}</p>
          </div>
          @if($kas->user)
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Dicatat Oleh</small>
            <strong>{{ $kas->user->username }}</strong>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Waktu Pencatatan</small>
            <strong>{{ $kas->created_at->format('d/m/Y H:i') }}</strong>
          </div>
          @endif
        </div>

        <!-- Navigation -->
        <div class="d-flex justify-content-between pt-3 border-top">
          <a href="{{ route('bendahara.kas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
          </a>
        </div>
      </div>
    </div>

    <!-- Saldo History -->
    <div class="card border-0 shadow-sm rounded-3 mt-4">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Riwayat Saldo</h6>
        <div class="alert alert-info border-0 mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="text-muted d-block">Saldo Sebelum</small>
              <strong class="fs-5">
                Rp {{ number_format($kas->saldo - ($kas->jenis == 'masuk' ? $kas->jumlah : -$kas->jumlah), 0, ',', '.') }}
              </strong>
            </div>
            <div class="px-3">
              <i class="bi bi-arrow-right fs-3 {{ $kas->jenis == 'masuk' ? 'text-success' : 'text-danger' }}"></i>
            </div>
            <div>
              <small class="text-muted d-block">Saldo Setelah</small>
              <strong class="fs-5">Rp {{ number_format($kas->saldo, 0, ',', '.') }}</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection