@extends('layouts.admin')

@section('title', 'Tambah Santri')
@section('page-title', 'Tambah Santri')
@section('page-subtitle', 'Form pendaftaran santri baru')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.santri.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="bi bi-person-badge"></i> Data Pribadi</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                       name="nama_lengkap" 
                                       value="{{ old('nama_lengkap') }}"
                                       placeholder="Contoh: Ahmad Zaki"
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
                                       value="{{ old('nama_panggilan') }}"
                                       placeholder="Contoh: Zaki">
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
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
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
                                           value="{{ old('tempat_lahir') }}"
                                           placeholder="Contoh: Bandung"
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
                                           value="{{ old('tanggal_lahir') }}"
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
                                          placeholder="Contoh: Jl. Raya No. 123, Kec. ABC, Kota XYZ"
                                          required>{{ old('alamat') }}</textarea>
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
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                       value="{{ old('nama_wali') }}"
                                       placeholder="Nama Orang Tua/Wali"
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
                                       value="{{ old('no_telp_wali') }}"
                                       placeholder="08XXXXXXXXXX"
                                       required>
                                @error('no_telp_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-3"><i class="bi bi-shield-lock"></i> Akun Login</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       name="username" 
                                       value="{{ old('username') }}"
                                       placeholder="Username untuk login"
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Username tidak boleh mengandung spasi</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email (Opsional)</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="email@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       placeholder="Minimal 6 karakter"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Foto Santri</label>
                                <input type="file" 
                                       class="form-control @error('foto') is-invalid @enderror" 
                                       name="foto"
                                       accept="image/*">
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Data Santri
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview foto sebelum upload
    document.querySelector('input[name="foto"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                // Buat preview jika mau ditambahkan
                console.log('Foto dipilih:', file.name);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection