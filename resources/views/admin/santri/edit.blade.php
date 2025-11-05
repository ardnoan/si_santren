@extends('layouts.dashboard')

@section('title', 'Edit Santri')
@section('page-title', 'Edit Data Santri')
@section('page-subtitle', 'Update informasi santri')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.santri.update', $santri->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="bi bi-person-badge"></i> Data Pribadi</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Nomor Induk</label>
                                <input type="text" class="form-control" value="{{ $santri->nomor_induk }}" disabled>
                                <small class="text-muted">NIS tidak dapat diubah</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                       name="nama_lengkap" 
                                       value="{{ old('nama_lengkap', $santri->nama_lengkap) }}"
                                       required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Panggilan</label>
                                <input type="text" 
                                       class="form-control @error('nama_panggilan') is-invalid @enderror" 
                                       name="nama_panggilan" 
                                       value="{{ old('nama_panggilan', $santri->nama_panggilan) }}">
                                @error('nama_panggilan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                        name="jenis_kelamin" 
                                        required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                           name="tempat_lahir" 
                                           value="{{ old('tempat_lahir', $santri->tempat_lahir) }}"
                                           required>
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                           name="tanggal_lahir" 
                                           value="{{ old('tanggal_lahir', $santri->tanggal_lahir->format('Y-m-d')) }}"
                                           required>
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          name="alamat" 
                                          rows="3"
                                          required>{{ old('alamat', $santri->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <select class="form-select @error('kelas_id') is-invalid @enderror" 
                                        name="kelas_id">
                                    <option value="">Belum Ada Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" 
                                            {{ old('kelas_id', $santri->kelas_id) == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="aktif" {{ $santri->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="cuti" {{ $santri->status == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                    <option value="lulus" {{ $santri->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                    <option value="keluar" {{ $santri->status == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="bi bi-people"></i> Data Wali</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Wali <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_wali') is-invalid @enderror" 
                                       name="nama_wali" 
                                       value="{{ old('nama_wali', $santri->nama_wali) }}"
                                       required>
                                @error('nama_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">No. Telepon Wali <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('no_telp_wali') is-invalid @enderror" 
                                       name="no_telp_wali" 
                                       value="{{ old('no_telp_wali', $santri->no_telp_wali) }}"
                                       required>
                                @error('no_telp_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-3"><i class="bi bi-shield-lock"></i> Akun Login</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       name="username" 
                                       value="{{ old('username', $santri->user->username) }}">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email', $santri->user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       placeholder="Kosongkan jika tidak ingin mengubah password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="mb-3">
                                <label class="form-label">Foto Santri</label>
                                
                                @if($santri->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $santri->foto) }}" 
                                             alt="{{ $santri->nama_lengkap }}" 
                                             class="img-thumbnail"
                                             style="max-width: 200px;">
                                    </div>
                                @endif
                                
                                <input type="file" 
                                       class="form-control @error('foto') is-invalid @enderror" 
                                       name="foto"
                                       accept="image/*">
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: JPG, PNG (Max: 2MB). Kosongkan jika tidak ingin mengubah foto.</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Data Santri
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection