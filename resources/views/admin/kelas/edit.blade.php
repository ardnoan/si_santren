
<!-- ============================================ -->
<!-- FILE 3: resources/views/admin/kelas/edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit Kelas')
@section('page-title', 'Edit Kelas')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               name="nama_kelas"
                               value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                               required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tingkat</label>
                            <select class="form-select" name="tingkat" required>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ $kelas->tingkat == $i ? 'selected' : '' }}>
                                        Tingkat {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="tahun_ajaran"
                                   value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}"
                                   required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" 
                                   class="form-control" 
                                   name="kapasitas"
                                   value="{{ old('kapasitas', $kelas->kapasitas) }}"
                                   required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Wali Kelas</label>
                            <select class="form-select" name="wali_kelas">
                                <option value="">Belum ada wali kelas</option>
                                @foreach($ustadz as $u)
                                    <option value="{{ $u->id }}" {{ $kelas->wali_kelas == $u->id ? 'selected' : '' }}>
                                        {{ $u->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- ============================================ -->
<!-- FILE 4: resources/views/admin/kelas/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Detail Kelas')
@section('page-title', 'Detail Kelas')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h2>{{ $kelas->nama_kelas }}</h2>
                <p class="text-muted">Tingkat {{ $kelas->tingkat }}</p>
                <p>{{ $kelas->tahun_ajaran }}</p>
                
                <hr>
                
                <div class="mb-3">
                    <strong>Wali Kelas:</strong><br>
                    {{ $kelas->waliKelas ? $kelas->waliKelas->username : 'Belum ada' }}
                </div>
                
                <div class="mb-3">
                    <strong>Kapasitas:</strong><br>
                    {{ $kelas->santris->count() }} / {{ $kelas->kapasitas }} santri
                </div>
                
                <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Kelas
                </a>
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3"><i class="bi bi-people"></i> Daftar Santri</h5>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>JK</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelas->santris as $i => $s)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $s->nomor_induk }}</td>
                                <td>{{ $s->nama_lengkap }}</td>
                                <td>
                                    <span class="badge bg-{{ $s->jenis_kelamin == 'L' ? 'primary' : 'danger' }}">
                                        {{ $s->jenis_kelamin }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $s->status_badge }}">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada santri di kelas ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection