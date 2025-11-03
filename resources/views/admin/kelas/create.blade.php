
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
