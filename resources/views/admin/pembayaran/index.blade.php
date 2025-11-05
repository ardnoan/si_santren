@extends('layouts.dashboard')

@section('title', 'Data Pembayaran')
@section('page-title', 'Data Pembayaran')
@section('page-subtitle', 'Kelola transaksi pembayaran santri')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-cash-coin me-2"></i>Daftar Pembayaran
                    </h5>
                    <div>
                        <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Input Pembayaran
                        </a>
                        <a href="{{ route('admin.pembayaran.laporan') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-text me-2"></i>Laporan
                        </a>
                    </div>
                </div>
                
                <!-- Filter & Search -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="searchSantri" placeholder="Cari nama santri...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="filterJenis">
                            <option value="">Semua Jenis</option>
                            <option value="spp">SPP</option>
                            <option value="pendaftaran">Pendaftaran</option>
                            <option value="seragam">Seragam</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="lunas">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="filterTanggal" placeholder="Tanggal">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-secondary" onclick="resetFilter()">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filter
                        </button>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="alert alert-success mb-0">
                            <strong><i class="bi bi-cash-stack"></i> Total Pemasukan:</strong><br>
                            <h4 class="mb-0">Rp {{ number_format(App\Models\Pembayaran::where('status', 'lunas')->sum('jumlah'), 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-info mb-0">
                            <strong><i class="bi bi-check-circle"></i> Lunas Bulan Ini:</strong><br>
                            <h4 class="mb-0">{{ App\Models\Pembayaran::where('status', 'lunas')->whereMonth('tanggal_bayar', date('m'))->count() }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-warning mb-0">
                            <strong><i class="bi bi-clock-history"></i> Pending:</strong><br>
                            <h4 class="mb-0">{{ App\Models\Pembayaran::where('status', 'pending')->count() }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-primary mb-0">
                            <strong><i class="bi bi-calendar-month"></i> Bulan Ini:</strong><br>
                            <h4 class="mb-0">Rp {{ number_format(App\Models\Pembayaran::where('status', 'lunas')->whereMonth('tanggal_bayar', date('m'))->sum('jumlah'), 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Tanggal</th>
                                <th width="20%">Santri</th>
                                <th width="15%">Jenis</th>
                                <th width="15%">Jumlah</th>
                                <th width="10%">Metode</th>
                                <th width="10%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $pembayaran = App\Models\Pembayaran::with(['santri', 'admin'])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(20);
                            @endphp
                            
                            @forelse($pembayaran as $index => $p)
                            <tr>
                                <td>{{ $pembayaran->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $p->tanggal_bayar->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $p->tanggal_bayar->format('H:i') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $p->santri->nama_lengkap }}</strong><br>
                                    <small class="text-muted">NIS: {{ $p->santri->nomor_induk }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $p->jenis_pembayaran_label }}</span>
                                    @if($p->bulan_bayar)
                                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($p->bulan_bayar)->format('M Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-success">{{ $p->jumlah_format }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($p->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td>
                                    @if($p->status == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($p->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($p->bukti_transfer)
                                            <a href="{{ asset('storage/' . $p->bukti_transfer) }}" 
                                               target="_blank"
                                               class="btn btn-info"
                                               title="Lihat Bukti">
                                                <i class="bi bi-image"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.pembayaran.edit', $p->id) }}" 
                                           class="btn btn-warning"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.pembayaran.destroy', $p->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus pembayaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-muted"></i>
                                    <h5 class="text-muted">Belum ada data pembayaran</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $pembayaran->firstItem() ?? 0 }} - {{ $pembayaran->lastItem() ?? 0 }} 
                        dari {{ $pembayaran->total() }} data
                    </div>
                    <div>
                        {{ $pembayaran->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function resetFilter() {
    document.getElementById('searchSantri').value = '';
    document.getElementById('filterJenis').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterTanggal').value = '';
}
</script>
@endsection