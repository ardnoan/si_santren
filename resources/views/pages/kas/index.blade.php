{{-- resources/views/pages/kas/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Kas Pesantren')
@section('page-title', 'Kas Pesantren')
@section('page-subtitle', 'Kelola kas dan transaksi keuangan')

@section('content')
<!-- Saldo Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4 bg-gradient-primary text-white">
  <div class="card-body p-4">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h6 class="text-white opacity-90 mb-2">Saldo Kas Saat Ini</h6>
        <h1 class="display-4 fw-bold mb-0">Rp {{ number_format($saldoTerkini, 0, ',', '.') }}</h1>
        <small class="opacity-75">Per {{ now()->isoFormat('D MMMM Y') }}</small>
      </div>
      <div class="col-md-4 text-end">
        <i class="bi bi-wallet2 display-1 opacity-25"></i>
      </div>
    </div>
  </div>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-4">
    <form method="GET" class="row g-3">
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Dari Tanggal</label>
        <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Sampai Tanggal</label>
        <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small">Jenis</label>
        <select name="jenis" class="form-select">
          <option value="">Semua</option>
          <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
          <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small">Kategori</label>
        <select name="kategori" class="form-select">
          <option value="">Semua</option>
          <option value="spp">SPP</option>
          <option value="pendaftaran">Pendaftaran</option>
          <option value="operasional">Operasional</option>
          <option value="konsumsi">Konsumsi</option>
          <option value="lainnya">Lainnya</option>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-search me-2"></i>Filter
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Transactions Table -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-3">Riwayat Transaksi Kas</h6>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th class="text-end">Jumlah</th>
            <th class="text-end">Saldo</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transaksi as $t)
          <tr>
            <td>{{ $t->tanggal->format('d/m/Y H:i') }}</td>
            <td>
              @if($t->jenis == 'masuk')
              <span class="badge bg-success px-3 py-2">
                <i class="bi bi-arrow-down-circle me-1"></i>Masuk
              </span>
              @else
              <span class="badge bg-danger px-3 py-2">
                <i class="bi bi-arrow-up-circle me-1"></i>Keluar
              </span>
              @endif
            </td>
            <td>
              <span class="badge bg-secondary px-2 py-1">{{ ucfirst($t->kategori) }}</span>
            </td>
            <td>{{ $t->keterangan }}</td>
            <td class="text-end">
              <strong class="{{ $t->jenis == 'masuk' ? 'text-success' : 'text-danger' }}">
                Rp {{ number_format($t->jumlah, 0, ',', '.') }}
              </strong>
            </td>
            <td class="text-end">
              <strong>Rp {{ number_format($t->saldo, 0, ',', '.') }}</strong>
            </td>
            <td class="text-center">
              <a href="{{ route('bendahara.kas.show', $t->id) }}" class="btn btn-sm btn-info">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              Tidak ada transaksi
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $transaksi->links('components.pagination') }}
  </div>
</div>
@endsection