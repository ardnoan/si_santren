{{-- resources/views/pages/pembayaran/laporan.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran')
@section('page-subtitle', 'Rekap dan analisis pembayaran')

@section('content')
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <i class="bi bi-file-earmark-text-fill text-primary"></i>
        Laporan Pembayaran
      </h5>
      <button onclick="window.print()" class="btn btn-success shadow-sm d-print-none">
        <i class="bi bi-printer me-2"></i>Cetak Laporan
      </button>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="mb-4 d-print-none">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-semibold small">Dari Tanggal</label>
          <input type="date"
                 name="dari"
                 class="form-control shadow-sm"
                 value="{{ request('dari', date('Y-m-01')) }}">
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold small">Sampai Tanggal</label>
          <input type="date"
                 name="sampai"
                 class="form-control shadow-sm"
                 value="{{ request('sampai', date('Y-m-d')) }}">
        </div>

        <div class="col-md-2">
          <label class="form-label fw-semibold small">Jenis</label>
          <select name="jenis" class="form-select shadow-sm">
            <option value="">Semua Jenis</option>
            <option value="spp" {{ request('jenis') == 'spp' ? 'selected' : '' }}>SPP</option>
            <option value="pendaftaran" {{ request('jenis') == 'pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
            <option value="seragam" {{ request('jenis') == 'seragam' ? 'selected' : '' }}>Seragam</option>
            <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label fw-semibold small">Status</label>
          <select name="status" class="form-select shadow-sm">
            <option value="">Semua Status</option>
            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
          </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search me-2"></i>Filter
          </button>
        </div>
      </div>
    </form>

    @php
    $query = \App\Models\Pembayaran::with(['santri', 'admin']);

    if(request('dari')) {
      $query->whereDate('tanggal_bayar', '>=', request('dari'));
    }

    if(request('sampai')) {
      $query->whereDate('tanggal_bayar', '<=', request('sampai'));
    }

    if(request('jenis')) {
      $query->where('jenis_pembayaran', request('jenis'));
    }

    if(request('status')) {
      $query->where('status', request('status'));
    }

    $data = $query->orderBy('tanggal_bayar', 'desc')->get();
    $totalLunas = $data->where('status', 'lunas')->sum('jumlah');
    $totalPending = $data->where('status', 'pending')->sum('jumlah');
    $grandTotal = $totalLunas + $totalPending;
    @endphp

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

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card border-0 bg-primary bg-opacity-10 h-100">
          <div class="card-body text-center p-3">
            <i class="bi bi-list-check fs-2 text-primary mb-2"></i>
            <div class="display-6 fw-bold text-primary">{{ $data->count() }}</div>
            <small class="text-muted">Total Transaksi</small>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 bg-success bg-opacity-10 h-100">
          <div class="card-body text-center p-3">
            <i class="bi bi-check-circle fs-2 text-success mb-2"></i>
            <div class="display-6 fw-bold text-success">
              {{ number_format($totalLunas / 1000000, 1) }}M
            </div>
            <small class="text-muted">Total Lunas</small>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 bg-warning bg-opacity-10 h-100">
          <div class="card-body text-center p-3">
            <i class="bi bi-clock-history fs-2 text-warning mb-2"></i>
            <div class="display-6 fw-bold text-warning">
              {{ number_format($totalPending / 1000000, 1) }}M
            </div>
            <small class="text-muted">Total Pending</small>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 bg-info bg-opacity-10 h-100">
          <div class="card-body text-center p-3">
            <i class="bi bi-calculator fs-2 text-info mb-2"></i>
            <div class="display-6 fw-bold text-info">
              {{ number_format($grandTotal / 1000000, 1) }}M
            </div>
            <small class="text-muted">Grand Total</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th style="width: 10%;">Tanggal</th>
            <th style="width: 20%;">Santri</th>
            <th class="text-center" style="width: 10%;">NIS</th>
            <th style="width: 13%;">Jenis</th>
            <th class="text-end" style="width: 12%;">Jumlah</th>
            <th class="text-center" style="width: 10%;">Metode</th>
            <th class="text-center" style="width: 10%;">Status</th>
            <th class="text-center d-print-none" style="width: 10%;">Admin</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $i => $p)
          <tr>
            <td class="text-center fw-semibold">{{ $i + 1 }}</td>
            <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
            <td>{{ $p->santri->nama_lengkap }}</td>
            <td class="text-center">
              <code class="text-primary">{{ $p->santri->nomor_induk }}</code>
            </td>
            <td>
              <span class="badge bg-info text-dark px-2 py-1">
                {{ ucfirst($p->jenis_pembayaran) }}
              </span>
              @if($p->bulan_bayar)
              <div class="small text-muted mt-1">
                {{ \Carbon\Carbon::parse($p->bulan_bayar)->format('M Y') }}
              </div>
              @endif
            </td>
            <td class="text-end">
              <strong>{{ $p->jumlah_format }}</strong>
            </td>
            <td class="text-center">
              <small>{{ ucfirst($p->metode_pembayaran) }}</small>
            </td>
            <td class="text-center">
              @php
              $statusColors = [
                'lunas' => 'success',
                'pending' => 'warning',
                'dibatalkan' => 'danger'
              ];
              $color = $statusColors[$p->status] ?? 'secondary';
              @endphp
              <span class="badge bg-{{ $color }} px-2 py-1">
                {{ ucfirst($p->status) }}
              </span>
            </td>
            <td class="text-center d-print-none">
              <small class="text-muted">{{ $p->admin->username ?? '-' }}</small>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center py-5">
              <div class="text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <h6>Tidak ada data pembayaran</h6>
                <p class="small mb-0">Tidak ada transaksi untuk periode ini</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
        @if($data->count() > 0)
        <tfoot class="table-light">
          <tr>
            <th colspan="5" class="text-end">TOTAL LUNAS:</th>
            <th class="text-end">Rp {{ number_format($totalLunas, 0, ',', '.') }}</th>
            <th colspan="3"></th>
          </tr>
          <tr>
            <th colspan="5" class="text-end">TOTAL PENDING:</th>
            <th class="text-end">Rp {{ number_format($totalPending, 0, ',', '.') }}</th>
            <th colspan="3"></th>
          </tr>
          <tr class="table-primary">
            <th colspan="5" class="text-end">GRAND TOTAL:</th>
            <th class="text-end">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
            <th colspan="3"></th>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mt-4 d-print-none">
      <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
      </a>
      <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-success">
          <i class="bi bi-printer me-2"></i>Cetak
        </button>
        <a href="{{ route('admin.pembayaran.export', request()->all()) }}" 
           class="btn btn-primary">
          <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Print Styles -->
<style>
  @media print {
    .navbar,
    .sidebar,
    .d-print-none,
    button,
    .btn,
    form {
      display: none !important;
    }

    .main-content {
      margin-left: 0 !important;
    }

    .card {
      border: none !important;
      box-shadow: none !important;
    }

    table {
      font-size: 11px;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #000 !important;
    }

    .badge {
      border: 1px solid #000;
    }
    
    @page {
      margin: 1cm;
    }
    
    body {
      print-color-adjust: exact;
      -webkit-print-color-adjust: exact;
    }
  }
</style>
@endsection