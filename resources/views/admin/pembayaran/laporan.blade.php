@extends('layouts.dashboard')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran')
@section('page-subtitle', 'Rekap dan analisis pembayaran')

@section('content')
<div class="row mb-3">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h5 class="mb-3">
          <i class="bi bi-file-earmark-text me-2"></i>Laporan Pembayaran
        </h5>

        <!-- Filter Form -->
        <form method="GET" class="mb-4">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Dari Tanggal</label>
              <input type="date"
                name="dari"
                class="form-control"
                value="{{ request('dari', date('Y-m-01')) }}">
            </div>

            <div class="col-md-3">
              <label class="form-label">Sampai Tanggal</label>
              <input type="date"
                name="sampai"
                class="form-control"
                value="{{ request('sampai', date('Y-m-d')) }}">
            </div>

            <div class="col-md-2">
              <label class="form-label">Jenis</label>
              <select name="jenis" class="form-select">
                <option value="">Semua Jenis</option>
                <option value="spp" {{ request('jenis') == 'spp' ? 'selected' : '' }}>SPP</option>
                <option value="pendaftaran" {{ request('jenis') == 'pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                <option value="seragam" {{ request('jenis') == 'seragam' ? 'selected' : '' }}>Seragam</option>
                <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
              </select>
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
              <button type="submit" class="btn btn-primary flex-fill">
                <i class="bi bi-search"></i> Filter
              </button>
              <button type="button" class="btn btn-success" onclick="window.print()">
                <i class="bi bi-printer"></i>
              </button>
            </div>
          </div>
        </form>

        @php
        $query = App\Models\Pembayaran::with(['santri', 'admin']);

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
          @endphp

          <!-- Summary Cards -->
          <div class="row mb-4">
            <div class="col-md-3">
              <div class="alert alert-info mb-0">
                <strong>Total Transaksi:</strong><br>
                <h4 class="mb-0">{{ $data->count() }}</h4>
              </div>
            </div>
            <div class="col-md-3">
              <div class="alert alert-success mb-0">
                <strong>Total Lunas:</strong><br>
                <h4 class="mb-0">Rp {{ number_format($totalLunas, 0, ',', '.') }}</h4>
              </div>
            </div>
            <div class="col-md-3">
              <div class="alert alert-warning mb-0">
                <strong>Total Pending:</strong><br>
                <h4 class="mb-0">Rp {{ number_format($totalPending, 0, ',', '.') }}</h4>
              </div>
            </div>
            <div class="col-md-3">
              <div class="alert alert-primary mb-0">
                <strong>Grand Total:</strong><br>
                <h4 class="mb-0">Rp {{ number_format($totalLunas + $totalPending, 0, ',', '.') }}</h4>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="table-light">
                <tr>
                  <th width="5%">No</th>
                  <th width="12%">Tanggal</th>
                  <th width="20%">Santri</th>
                  <th width="10%">NIS</th>
                  <th width="15%">Jenis</th>
                  <th width="13%">Jumlah</th>
                  <th width="10%">Metode</th>
                  <th width="10%">Status</th>
                  <th width="5%">Admin</th>
                </tr>
              </thead>
              <tbody>
                @forelse($data as $i => $p)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                  <td>{{ $p->santri->nama_lengkap }}</td>
                  <td>{{ $p->santri->nomor_induk }}</td>
                  <td>
                    {{ $p->jenis_pembayaran_label }}
                    @if($p->bulan_bayar)
                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($p->bulan_bayar)->format('M Y') }}</small>
                    @endif
                  </td>
                  <td class="text-end"><strong>{{ $p->jumlah_format }}</strong></td>
                  <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                  <td>
                    <span class="badge bg-{{ $p->status == 'lunas' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                      {{ ucfirst($p->status) }}
                    </span>
                  </td>
                  <td>
                    <small>{{ $p->admin->username ?? '-' }}</small>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="9" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    Tidak ada data pembayaran untuk periode ini
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
                  <th class="text-end">Rp {{ number_format($totalLunas + $totalPending, 0, ',', '.') }}</th>
                  <th colspan="3"></th>
                </tr>
              </tfoot>
              @endif
            </table>
          </div>

          <!-- Back Button -->
          <div class="mt-3">
            <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">
              <i class="bi bi-arrow-left"></i> Kembali ke Data Pembayaran
            </a>
            <a href="{{ route('admin.pembayaran.export', request()->all()) }}" class="btn btn-success">
              <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
          </div>
      </div>
    </div>
  </div>
</div>

<!-- Print Styles -->
<style>
  @media print {

    .navbar-top,
    .sidebar,
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
      font-size: 12px;
    }
  }
</style>
@endsection

@section('scripts')
<script>
  function exportExcel() {
    alert('Fitur export Excel akan segera tersedia!\n\nUntuk saat ini, silakan gunakan tombol Print untuk mencetak laporan.');
    // TODO: Implementasi export ke Excel menggunakan library seperti PhpSpreadsheet
  }
</script>
@endsection