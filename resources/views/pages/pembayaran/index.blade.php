{{-- resources/views/pages/pembayaran/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Data Pembayaran')
@section('page-title', 'Data Pembayaran')
@section('page-subtitle', 'Kelola transaksi pembayaran santri')

@section('content')
<!-- Main Card -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
      <div>
        <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
          <i class="bi bi-cash-coin-fill text-primary"></i>
          Daftar Pembayaran
        </h5>
        <p class="text-muted mb-0 small">Monitor dan kelola transaksi pembayaran</p>
      </div>

      @canManagePembayaran
      <div class="d-flex gap-2">
        <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-primary shadow-sm">
          <i class="bi bi-plus-circle me-2"></i>
          <span>Input Pembayaran</span>
        </a>
        <a href="{{ route('admin.pembayaran.laporan') }}" class="btn btn-success shadow-sm">
          <i class="bi bi-file-earmark-text me-2"></i>
          <span>Laporan</span>
        </a>
      </div>
      @endcanManagePembayaran
    </div>

    <!-- Filter & Search -->
    <form method="GET" class="mb-4">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-semibold small">Cari Santri</label>
          <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-end-0">
              <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" 
                   class="form-control border-start-0 ps-0" 
                   name="search"
                   placeholder="Nama santri..."
                   value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold small">Jenis</label>
          <select class="form-select shadow-sm" name="jenis">
            <option value="">Semua Jenis</option>
            <option value="spp" {{ request('jenis') == 'spp' ? 'selected' : '' }}>SPP</option>
            <option value="pendaftaran" {{ request('jenis') == 'pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
            <option value="seragam" {{ request('jenis') == 'seragam' ? 'selected' : '' }}>Seragam</option>
            <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold small">Status</label>
          <select class="form-select shadow-sm" name="status">
            <option value="">Semua Status</option>
            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold small">Tanggal</label>
          <input type="date" 
                 class="form-control shadow-sm" 
                 name="tanggal"
                 value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
          <button type="submit" class="btn btn-primary flex-grow-1">
            <i class="bi bi-search me-2"></i>Filter
          </button>
          <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-clockwise"></i>
          </a>
        </div>
      </div>
    </form>

    <!-- Statistics -->
    @php
    $totalLunas = \App\Models\Pembayaran::where('status', 'lunas')->sum('jumlah');
    $lunasBulanIni = \App\Models\Pembayaran::where('status', 'lunas')->whereMonth('tanggal_bayar', date('m'))->count();
    $totalPending = \App\Models\Pembayaran::where('status', 'pending')->count();
    $totalBulanIni = \App\Models\Pembayaran::where('status', 'lunas')->whereMonth('tanggal_bayar', date('m'))->sum('jumlah');
    @endphp

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="alert alert-success border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">
                <i class="bi bi-cash-stack me-1"></i>Total Pemasukan
              </small>
              <strong class="fs-5">Rp {{ number_format($totalLunas / 1000000, 1) }}M</strong>
            </div>
            <i class="bi bi-graph-up-arrow fs-2 opacity-50"></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert alert-info border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">
                <i class="bi bi-check-circle me-1"></i>Lunas Bulan Ini
              </small>
              <strong class="fs-5">{{ $lunasBulanIni }}</strong>
            </div>
            <i class="bi bi-calendar-check fs-2 opacity-50"></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert alert-warning border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">
                <i class="bi bi-clock-history me-1"></i>Pending
              </small>
              <strong class="fs-5">{{ $totalPending }}</strong>
            </div>
            <i class="bi bi-hourglass-split fs-2 opacity-50"></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="alert alert-primary border-0 shadow-sm mb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="d-block opacity-75 mb-1">
                <i class="bi bi-calendar-month me-1"></i>Bulan Ini
              </small>
              <strong class="fs-5">Rp {{ number_format($totalBulanIni / 1000000, 1) }}M</strong>
            </div>
            <i class="bi bi-wallet2 fs-2 opacity-50"></i>
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
            <th style="width: 13%;">Tanggal</th>
            <th style="width: 20%;">Santri</th>
            <th style="width: 15%;">Jenis</th>
            <th class="text-end" style="width: 13%;">Jumlah</th>
            <th class="text-center" style="width: 10%;">Metode</th>
            <th class="text-center" style="width: 10%;">Status</th>
            <th class="text-center" style="width: 14%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @php
          $query = \App\Models\Pembayaran::with(['santri', 'admin']);
          if(request('search')) $query->whereHas('santri', function($q) {
            $q->where('nama_lengkap', 'like', '%' . request('search') . '%');
          });
          if(request('jenis')) $query->where('jenis_pembayaran', request('jenis'));
          if(request('status')) $query->where('status', request('status'));
          if(request('tanggal')) $query->whereDate('tanggal_bayar', request('tanggal'));
          
          $pembayaran = $query->orderBy('created_at', 'desc')->paginate(20);
          @endphp

          @forelse($pembayaran as $index => $p)
          <tr>
            <td class="text-center fw-semibold">{{ $pembayaran->firstItem() + $index }}</td>
            <td>
              <div class="fw-semibold">{{ $p->tanggal_bayar->format('d/m/Y') }}</div>
              <small class="text-muted">{{ $p->tanggal_bayar->format('H:i') }}</small>
            </td>
            <td>
              <div class="fw-semibold text-dark">{{ $p->santri->nama_lengkap }}</div>
              <small class="text-muted">
                <i class="bi bi-card-text me-1"></i>{{ $p->santri->nomor_induk }}
              </small>
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
              <span class="badge bg-{{ $color }} text-dark px-2 py-1">
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
              <strong class="text-success fs-6">{{ $p->jumlah_format }}</strong>
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
              <div class="btn-group btn-group-sm shadow-sm" role="group">
                @canManagePembayaran
                @if($p->bukti_transfer)
                <a href="{{ asset('storage/' . $p->bukti_transfer) }}" 
                   target="_blank" 
                   class="btn btn-info"
                   data-bs-toggle="tooltip"
                   title="Lihat Bukti">
                  <i class="bi bi-image"></i>
                </a>
                @endif
                <a href="{{ route('admin.pembayaran.edit', $p->id) }}" 
                   class="btn btn-warning"
                   data-bs-toggle="tooltip"
                   title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.pembayaran.destroy', $p->id) }}" 
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
                @endcanManagePembayaran
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-5">
              <div class="text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <h5>Belum ada data pembayaran</h5>
                <p class="mb-3">Data pembayaran akan muncul di sini</p>
                @canManagePembayaran
                <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-primary">
                  <i class="bi bi-plus-circle me-2"></i>
                  Input Pembayaran Pertama
                </a>
                @endcanManagePembayaran
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
  </div>
</div>
@endsection