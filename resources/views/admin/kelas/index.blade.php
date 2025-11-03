<!-- ============================================ -->
<!-- FILE 1: resources/views/admin/kelas/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')
@section('page-subtitle', 'Kelola kelas pesantren')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5><i class="bi bi-building"></i> Daftar Kelas</h5>
                    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Kelas
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Tingkat</th>
                                <th>Tahun Ajaran</th>
                                <th>Wali Kelas</th>
                                <th>Jumlah Santri</th>
                                <th>Kapasitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelas as $i => $k)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $k->nama_kelas }}</strong></td>
                                <td><span class="badge bg-primary">Tingkat {{ $k->tingkat }}</span></td>
                                <td>{{ $k->tahun_ajaran }}</td>
                                <td>
                                    @if($k->waliKelas)
                                        {{ $k->waliKelas->username }}
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                                <td>{{ $k->santris_count }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        @php
                                            $persentase = $k->kapasitas > 0 ? ($k->santris_count / $k->kapasitas) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar {{ $persentase >= 100 ? 'bg-danger' : ($persentase >= 80 ? 'bg-warning' : 'bg-success') }}">
                                            {{ $k->santris_count }}/{{ $k->kapasitas }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.kelas.show', $k->id) }}" class="btn btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.kelas.edit', $k->id) }}" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.kelas.destroy', $k->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin hapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $kelas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection