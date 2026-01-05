{{-- ===== FILE 1: resources/views/pages/pengeluaran/index.blade.php ===== --}}
@extends('layouts.dashboard')

@section('title', 'Pengeluaran')
@section('page-title', 'Pengeluaran Pesantren')
@section('page-subtitle', 'Kelola pengeluaran operasional')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="fw-bold">Daftar Pengeluaran</h5>
  @if(auth()->user()->isBendahara())
  <a href="{{ route('bendahara.pengeluaran.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-2"></i>Tambah Pengeluaran
  </a>
  @endif
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="alert alert-danger border-0 mb-0">
      <small class="opacity-75">Total Pengeluaran</small>
      <div class="fs-4 fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="alert alert-warning border-0 mb-0">
      <small class="opacity-75">Pending Approval</small>
      <div class="fs-4 fw-bold">{{ $countPending }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="alert alert-success border-0 mb-0">
      <small class="opacity-75">Approved</small>
      <div class="fs-4 fw-bold">{{ $countApproved }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="alert alert-secondary border-0 mb-0">
      <small class="opacity-75">Rejected</small>
      <div class="fs-4 fw-bold">{{ $countRejected }}</div>
    </div>
  </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th class="text-end">Jumlah</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pengeluaran as $p)
          <tr>
            <td>{{ $p->tanggal->format('d/m/Y') }}</td>
            <td>
              <span class="badge bg-secondary px-2 py-1">{{ ucfirst($p->kategori) }}</span>
            </td>
            <td>{{ Str::limit($p->keterangan, 50) }}</td>
            <td class="text-end">
              <strong class="text-danger">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</strong>
            </td>
            <td>
              @if($p->status == 'pending')
              <span class="badge bg-warning px-3 py-2">Pending</span>
              @elseif($p->status == 'approved')
              <span class="badge bg-success px-3 py-2">Approved</span>
              @else
              <span class="badge bg-danger px-3 py-2">Rejected</span>
              @endif
            </td>
            <td class="text-center">
              <div class="btn-group btn-group-sm">
                <a href="{{ auth()->user()->isBendahara() ? route('bendahara.pengeluaran.show', $p->id) : route('pemimpin.pengeluaran.show', $p->id) }}" 
                   class="btn btn-info">
                  <i class="bi bi-eye"></i>
                </a>
                @if(auth()->user()->isBendahara() && $p->status == 'pending')
                <a href="{{ route('bendahara.pengeluaran.edit', $p->id) }}" class="btn btn-warning">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('bendahara.pengeluaran.destroy', $p->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus pengeluaran ini?')">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
                @endif
                @if(auth()->user()->isPemimpin() && $p->status == 'pending')
                <form action="{{ route('pemimpin.pengeluaran.approve', $p->id) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-success" onclick="return confirm('Approve pengeluaran ini?')">
                    <i class="bi bi-check-circle"></i>
                  </button>
                </form>
                <form action="{{ route('pemimpin.pengeluaran.reject', $p->id) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-danger" onclick="return confirm('Reject pengeluaran ini?')">
                    <i class="bi bi-x-circle"></i>
                  </button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">
              Belum ada pengeluaran
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $pengeluaran->links('components.pagination') }}
  </div>
</div>
@endsection