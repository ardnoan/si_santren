{{-- ===== FILE 1: resources/views/pages/laporan/index.blade.php ===== --}}
@extends('layouts.dashboard')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')
@section('page-subtitle', 'Analisis & Rekap Keuangan Pesantren')

@section('content')

<!-- Filter -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-4">
    <form method="GET" class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Dari Tanggal</label>
        <input type="date" name="dari" class="form-control" value="{{ request('dari', date('Y-m-01')) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Sampai Tanggal</label>
        <input type="date" name="sampai" class="form-control" value="{{ request('sampai', date('Y-m-d')) }}">
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-search me-2"></i>Tampilkan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm bg-success bg-opacity-10 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-arrow-down-circle fs-1 text-success mb-2"></i>
        <h4 class="fw-bold text-success mb-2">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h4>
        <small class="text-muted">Total Pemasukan</small>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm bg-danger bg-opacity-10 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-arrow-up-circle fs-1 text-danger mb-2"></i>
        <h4 class="fw-bold text-danger mb-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
        <small class="text-muted">Total Pengeluaran</small>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm bg-primary bg-opacity-10 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-calculator fs-1 text-primary mb-2"></i>
        <h4 class="fw-bold text-primary mb-2">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</h4>
        <small class="text-muted">Saldo Bersih</small>
      </div>
    </div>
  </div>
</div>

<!-- Period Info -->
<div class="alert alert-light border mb-4">
  <div class="d-flex align-items-center gap-3">
    <i class="bi bi-calendar-range fs-3 text-primary"></i>
    <div>
      <strong class="d-block">Periode Laporan</strong>
      <small class="text-muted">
        {{ \Carbon\Carbon::parse(request('dari', date('Y-m-01')))->isoFormat('D MMMM Y') }}
        s/d
        {{ \Carbon\Carbon::parse(request('sampai', date('Y-m-d')))->isoFormat('D MMMM Y') }}
      </small>
    </div>
  </div>
</div>

<!-- Detail Tables -->
<div class="row g-4">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 text-success">Rincian Pemasukan</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead class="table-light">
              <tr>
                <th>Kategori</th>
                <th class="text-end">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rincianPemasukan as $r)
              <tr>
                <td>{{ ucfirst($r->kategori) }}</td>
                <td class="text-end fw-semibold">Rp {{ number_format($r->total, 0, ',', '.') }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="2" class="text-center text-muted">Tidak ada pemasukan</td>
              </tr>
              @endforelse
              <tr class="table-success fw-bold">
                <td>TOTAL</td>
                <td class="text-end">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 text-danger">Rincian Pengeluaran</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead class="table-light">
              <tr>
                <th>Kategori</th>
                <th class="text-end">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rincianPengeluaran as $r)
              <tr>
                <td>{{ ucfirst($r->kategori) }}</td>
                <td class="text-end fw-semibold">Rp {{ number_format($r->total, 0, ',', '.') }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="2" class="text-center text-muted">Tidak ada pengeluaran</td>
              </tr>
              @endforelse
              <tr class="table-danger fw-bold">
                <td>TOTAL</td>
                <td class="text-end">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Export -->
<div class="text-center mt-4 d-print-none">
  <button onclick="window.print()" class="btn btn-primary">
    <i class="bi bi-printer me-2"></i>Cetak Laporan
  </button>
</div>

@endsection

<style>
@media print {
  .navbar, .sidebar, .d-print-none, button, .btn { display: none !important; }
  .main-content { margin-left: 0 !important; }
  .card { border: none !important; box-shadow: none !important; }
}
</style>