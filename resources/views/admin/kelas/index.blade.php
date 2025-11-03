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
                                        <div class="progress-bar {{ $persentase >= 100 ? 'bg-danger' : ($persentase >= 80 ? 'bg-warning' : 'bg-success') }}" 
                                             style="width:{{ min($persentase, 100) }}%">
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

<!-- ============================================ -->
<!-- FILE 2: resources/views/admin/kelas/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Tambah Kelas')
@section('page-title', 'Tambah Kelas')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.kelas.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_kelas') is-invalid @enderror" 
                               name="nama_kelas"
                               placeholder="Contoh: 1A"
                               value="{{ old('nama_kelas') }}"
                               required>
                        @error('nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                            <select class="form-select @error('tingkat') is-invalid @enderror" 
                                    name="tingkat" required>
                                <option value="">Pilih Tingkat</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ old('tingkat') == $i ? 'selected' : '' }}>
                                        Tingkat {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('tingkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('tahun_ajaran') is-invalid @enderror" 
                                   name="tahun_ajaran"
                                   placeholder="Contoh: 2024/2025"
                                   value="{{ old('tahun_ajaran', '2024/2025') }}"
                                   required>
                            @error('tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kapasitas <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('kapasitas') is-invalid @enderror" 
                                   name="kapasitas"
                                   min="1"
                                   value="{{ old('kapasitas', 30) }}"
                                   required>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Wali Kelas</label>
                            <select class="form-select @error('wali_kelas') is-invalid @enderror" 
                                    name="wali_kelas">
                                <option value="">Belum ada wali kelas</option>
                                @foreach($ustadz as $u)
                                    <option value="{{ $u->id }}" {{ old('wali_kelas') == $u->id ? 'selected' : '' }}>
                                        {{ $u->username }}
                                    </option>
                                @endforeach
                            </select>
                            @error('wali_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

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