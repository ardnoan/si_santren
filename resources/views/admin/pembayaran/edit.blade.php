<!-- FILE 1: resources/views/admin/pembayaran/edit.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Edit Pembayaran')
@section('page-title', 'Edit Pembayaran')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Santri</label>
                        <input type="text" class="form-control" value="{{ $pembayaran->santri->nama_lengkap }}" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jenis Pembayaran</label>
                        <select class="form-select" name="jenis_pembayaran" required>
                            <option value="spp" {{ $pembayaran->jenis_pembayaran == 'spp' ? 'selected' : '' }}>SPP Bulanan</option>
                            <option value="pendaftaran" {{ $pembayaran->jenis_pembayaran == 'pendaftaran' ? 'selected' : '' }}>Biaya Pendaftaran</option>
                            <option value="seragam" {{ $pembayaran->jenis_pembayaran == 'seragam' ? 'selected' : '' }}>Seragam</option>
                            <option value="lainnya" {{ $pembayaran->jenis_pembayaran == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input type="number" class="form-control" name="jumlah" value="{{ $pembayaran->jumlah }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="lunas" {{ $pembayaran->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="pending" {{ $pembayaran->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dibatalkan" {{ $pembayaran->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- ============================================ -->
<!-- FILE 2: resources/views/admin/pembayaran/laporan.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" name="dari" class="form-control" placeholder="Dari Tanggal" value="{{ request('dari') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="sampai" class="form-control" placeholder="Sampai Tanggal" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-2">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="spp">SPP</option>
                        <option value="pendaftaran">Pendaftaran</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-success w-100" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Santri</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $query = App\Models\Pembayaran::with('santri');
                        if(request('dari')) $query->whereDate('tanggal_bayar', '>=', request('dari'));
                        if(request('sampai')) $query->whereDate('tanggal_bayar', '<=', request('sampai'));
                        if(request('jenis')) $query->where('jenis_pembayaran', request('jenis'));
                        $data = $query->get();
                    @endphp
                    
                    @foreach($data as $i => $p)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                        <td>{{ $p->santri->nama_lengkap }}</td>
                        <td>{{ $p->jenis_pembayaran_label }}</td>
                        <td>{{ $p->jumlah_format }}</td>
                        <td><span class="badge bg-{{ $p->status == 'lunas' ? 'success' : 'warning' }}">{{ $p->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">TOTAL:</th>
                        <th colspan="2">Rp {{ number_format($data->where('status', 'lunas')->sum('jumlah'), 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection