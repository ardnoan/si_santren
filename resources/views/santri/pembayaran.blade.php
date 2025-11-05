@extends('layouts.dashboard')

@section('title', 'Pembayaran Saya')
@section('page-title', 'Pembayaran Saya')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="mb-3"><i class="bi bi-cash-coin me-2"></i>Riwayat Pembayaran</h5>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis Pembayaran</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $i => $p)
                    <tr>
                        <td>{{ $pembayaran->firstItem() + $i }}</td>
                        <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                        <td>{{ $p->jenis_pembayaran_label }}</td>
                        <td><strong>{{ $p->jumlah_format }}</strong></td>
                        <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'lunas' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $pembayaran->links() }}
    </div>
</div>
@endsection