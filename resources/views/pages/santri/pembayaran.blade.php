{{-- resources/views/pages/santri/pembayaran.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Pembayaran Saya')
@section('page-title', 'Pembayaran Saya')
@section('page-subtitle', 'Riwayat pembayaran')

@section('content')
<!-- Statistics -->
@php
$santri = auth()->user()->santri;
$totalLunas = $pembayaran->where('status', 'lunas')->sum('jumlah');
$totalPending = $pembayaran->where('status', 'pending')->sum('jumlah');
$bulanIni = $pembayaran->filter(function($p) {
  return $p->tanggal_bayar->month == date('m') && $p->status == 'lunas';
})->sum('jumlah');
$countLunas = $pembayaran->where('status', 'lunas')->count();
@endphp

<div class="row g-4 mb-4">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-cash-stack fs-1 text-success mb-2"></i>
        <div class="display-6 fw-bold text-success">{{ number_format($totalLunas / 1000000, 1)}}K</div>
        <small class="text-muted">Total Lunas</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-clock-history fs-1 text-warning mb-2"></i>
        <div class="display-6 fw-bold text-warning">{{ number_format($totalPending / 1000000, 1) }}M</div>
        <small class="text-muted">Total Pending</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-calendar-check fs-1 text-primary mb-2"></i>
        <div class="display-6 fw-bold text-primary">{{ number_format($bulanIni / 1000000, 1) }}M</div>
        <small class="text-muted">Bulan Ini</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-body p-4 text-center">
        <i class="bi bi-check-circle fs-1 text-info mb-2"></i>
        <div class="display-6 fw-bold text-info">{{ $countLunas }}</div>
        <small class="text-muted">Transaksi Lunas</small>
      </div>
    </div>
  </div>
</div>

<!-- Summary Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
      <i class="bi bi-graph-up text-primary"></i>
      Ringkasan Pembayaran
    </h6>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="p-3 bg-success bg-opacity-10 rounded-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">Total Dibayar</small>
            <i class="bi bi-check-circle text-success"></i>
          </div>
          <div class="fs-5 fw-bold text-success">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
          <div class="progress mt-2" style="height: 6px;">
            <div class="progress-bar bg-success" style="width: 100%"></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-warning bg-opacity-10 rounded-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">Menunggu Konfirmasi</small>
            <i class="bi bi-hourglass-split text-warning"></i>
          </div>
          <div class="fs-5 fw-bold text-warning">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
          <small class="text-muted">{{ $pembayaran->where('status', 'pending')->count() }} transaksi</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-info bg-opacity-10 rounded-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">Total Transaksi</small>
            <i class="bi bi-list-check text-info"></i>
          </div>
          <div class="fs-5 fw-bold text-info">{{ $pembayaran->count() }}</div>
          <small class="text-muted">Semua transaksi</small>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Riwayat Pembayaran -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <i class="bi bi-receipt text-primary"></i>
        Riwayat Pembayaran
      </h6>
      <span class="badge bg-primary px-3 py-2">
        {{ $pembayaran->total() }} transaksi
      </span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th style="width: 18%;">Tanggal</th>
            <th style="width: 22%;">Jenis Pembayaran</th>
            <th class="text-end" style="width: 18%;">Jumlah</th>
            <th class="text-center" style="width: 15%;">Metode</th>
            <th class="text-center" style="width: 12%;">Status</th>
            <th class="text-center" style="width: 10%;">Bukti</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pembayaran as $i => $p)
          <tr>
            <td class="text-center fw-semibold">{{ $pembayaran->firstItem() + $i }}</td>
            <td>
              <div class="fw-semibold">{{ $p->tanggal_bayar->isoFormat('dddd') }}</div>
              <small class="text-muted">{{ $p->tanggal_bayar->format('d/m/Y H:i') }}</small>
            </td>
            <td>
              @php
              $jenisColors = [
                'spp' => 'primary',
                'pendaftaran' => 'success',
                'seragam' => 'warning',
                'lainnya' => 'secondary'
              ];
              $jenisIcons = [
                'spp' => 'calendar-month',
                'pendaftaran' => 'person-plus',
                'seragam' => 'bag',
                'lainnya' => 'three-dots'
              ];
              $color = $jenisColors[$p->jenis_pembayaran] ?? 'secondary';
              $icon = $jenisIcons[$p->jenis_pembayaran] ?? 'circle';
              @endphp
              <span class="badge bg-{{ $color }} px-3 py-2">
                <i class="bi bi-{{ $icon }} me-1"></i>
                {{ ucfirst($p->jenis_pembayaran) }}
              </span>
              @if($p->bulan_bayar)
              <div class="small text-muted mt-1">
                <i class="bi bi-calendar3 me-1"></i>
                {{ \Carbon\Carbon::parse($p->bulan_bayar)->format('M Y') }}
              </div>
              @endif
            </td>
            <td class="text-end">
              <strong class="fs-6 text-success">{{ $p->jumlah_format }}</strong>
            </td>
            <td class="text-center">
              @php
              $metodeIcons = [
                'tunai' => 'cash',
                'transfer' => 'bank',
                'qris' => 'qr-code'
              ];
              $icon = $metodeIcons[$p->metode_pembayaran] ?? 'circle';
              @endphp
              <span class="badge bg-secondary px-2 py-1">
                <i class="bi bi-{{ $icon }} me-1"></i>
                {{ ucfirst($p->metode_pembayaran) }}
              </span>
            </td>
            <td class="text-center">
              @php
              $statusColors = [
                'lunas' => 'success',
                'pending' => 'warning',
                'dibatalkan' => 'danger'
              ];
              $statusIcons = [
                'lunas' => 'check-circle',
                'pending' => 'clock-history',
                'dibatalkan' => 'x-circle'
              ];
              $statusColor = $statusColors[$p->status] ?? 'secondary';
              $statusIcon = $statusIcons[$p->status] ?? 'circle';
              @endphp
              <span class="badge bg-{{ $statusColor }} px-3 py-2">
                <i class="bi bi-{{ $statusIcon }} me-1"></i>
                {{ ucfirst($p->status) }}
              </span>
            </td>
            <td class="text-center">
              @if($p->bukti_transfer)
              <a href="{{ asset('storage/' . $p->bukti_transfer) }}" 
                 target="_blank"
                 class="btn btn-sm btn-info shadow-sm"
                 data-bs-toggle="tooltip"
                 title="Lihat Bukti">
                <i class="bi bi-image"></i>
              </a>
              @else
              <small class="text-muted">-</small>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-5">
              <div class="text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <h6>Belum ada data pembayaran</h6>
                <p class="small mb-0">Riwayat pembayaran akan muncul di sini</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    {{ $pembayaran->links('components.pagination') }}
  </div>
</div>

<!-- Info Card -->
<div class="row g-3 mt-3">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-info-circle text-primary"></i>
          Keterangan Status
        </h6>
        <div class="d-flex flex-column gap-2">
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success px-3 py-2">
              <i class="bi bi-check-circle me-1"></i>Lunas
            </span>
            <small class="text-muted">Pembayaran telah dikonfirmasi</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning px-3 py-2">
              <i class="bi bi-clock-history me-1"></i>Pending
            </span>
            <small class="text-muted">Menunggu konfirmasi admin</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger px-3 py-2">
              <i class="bi bi-x-circle me-1"></i>Dibatalkan
            </span>
            <small class="text-muted">Pembayaran dibatalkan</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-credit-card text-success"></i>
          Metode Pembayaran
        </h6>
        <div class="d-flex flex-column gap-2">
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary px-3 py-2">
              <i class="bi bi-cash me-1"></i>Tunai
            </span>
            <small class="text-muted">Pembayaran langsung di kasir</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary px-3 py-2">
              <i class="bi bi-bank me-1"></i>Transfer
            </span>
            <small class="text-muted">Transfer bank dengan bukti</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary px-3 py-2">
              <i class="bi bi-qr-code me-1"></i>QRIS
            </span>
            <small class="text-muted">Pembayaran via QRIS</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection