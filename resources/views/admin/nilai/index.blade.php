<!-- ============================================ -->
<!-- FILE 1: resources/views/admin/nilai/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Nilai')
@section('page-title', 'Data Nilai Akademik')
@section('page-subtitle', 'Kelola nilai santri')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-journal-text me-2"></i>Daftar Nilai
                    </h5>
                    <a href="{{ route('admin.nilai.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Input Nilai
                    </a>
                </div>
                
                <!-- Filter -->
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <select name="semester" class="form-select">
                                <option value="">Semua Semester</option>
                                <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="Tahun Ajaran" value="{{ request('tahun_ajaran') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
                
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Santri</th>
                                <th width="20%">Mata Pelajaran</th>
                                <th width="10%">Semester</th>
                                <th width="10%">Tahun Ajaran</th>
                                <th width="10%">Nilai Akhir</th>
                                <th width="10%">Predikat</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $query = App\Models\Nilai::with(['santri', 'mataPelajaran']);
                                if(request('semester')) $query->where('semester', request('semester'));
                                if(request('tahun_ajaran')) $query->where('tahun_ajaran', request('tahun_ajaran'));
                                $nilai = $query->orderBy('created_at', 'desc')->paginate(20);
                            @endphp
                            
                            @forelse($nilai as $i => $n)
                            <tr>
                                <td>{{ $nilai->firstItem() + $i }}</td>
                                <td>
                                    <strong>{{ $n->santri->nama_lengkap }}</strong><br>
                                    <small class="text-muted">{{ $n->santri->nomor_induk }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $n->mataPelajaran->nama_mapel }}</span>
                                </td>
                                <td>{{ $n->semester }}</td>
                                <td>{{ $n->tahun_ajaran }}</td>
                                <td><strong>{{ number_format($n->nilai_akhir, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $n->predikat == 'A' ? 'success' : ($n->predikat == 'B' ? 'info' : ($n->predikat == 'C' ? 'warning' : 'danger')) }}">
                                        {{ $n->predikat }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.nilai.santri', $n->santri_id) }}" 
                                           class="btn btn-info"
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.nilai.destroy', $n->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus nilai ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
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
                                    <h5 class="text-muted">Belum ada data nilai</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between mt-3">
                    <div>
                        Menampilkan {{ $nilai->firstItem() ?? 0 }} - {{ $nilai->lastItem() ?? 0 }} dari {{ $nilai->total() }}
                    </div>
                    <div>{{ $nilai->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection