@extends('layouts.dashboard')

@php
$isEdit = isset($santri);
$title = $isEdit ? 'Edit Data Santri' : 'Tambah Santri';
$route = $isEdit ? route('admin.santri.update', $santri->id) : route('admin.santri.store');
@endphp

@section('title', $title)
@section('page-title', $title)
@section('page-subtitle', $isEdit ? $santri->nama_lengkap : 'Form pendaftaran santri baru')

@section('styles')
<style>
  .form-section {
    background: var(--surface-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-color);
  }

  .form-section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .form-section-title i {
    color: var(--secondary-color);
    font-size: 1.3rem;
  }

  .form-label {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
  }

  .form-label .text-danger {
    color: #e74c3c;
  }

  .form-control,
  .form-select {
    padding: 0.75rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.95rem;
    transition: var(--transition);
    background: var(--surface-color);
    color: var(--text-primary);
  }

  .form-control:focus,
  .form-select:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    background: var(--surface-color);
  }

  .form-control::placeholder {
    color: var(--text-muted);
    opacity: 0.7;
  }

  .form-text {
    color: var(--text-muted);
    font-size: 0.85rem;
    margin-top: 0.25rem;
  }

  .input-group-text {
    background: var(--bg-color);
    border: 2px solid var(--border-color);
    border-right: none;
    color: var(--text-secondary);
  }

  .input-group .form-control {
    border-left: none;
  }

  .input-group:focus-within .input-group-text {
    border-color: var(--secondary-color);
  }

  .invalid-feedback {
    display: block;
    color: #e74c3c;
    font-size: 0.85rem;
    margin-top: 0.25rem;
  }

  .is-invalid {
    border-color: #e74c3c !important;
  }

  .photo-preview {
    position: relative;
    display: inline-block;
    margin-bottom: 1rem;
  }

  .photo-preview img {
    border-radius: 12px;
    border: 3px solid var(--border-color);
    box-shadow: var(--shadow-md);
  }

  .photo-preview-badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background: var(--secondary-color);
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-md);
  }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
          @csrf
          @if($isEdit)
          @method('PUT')
          @endif

          <div class="row">
            <!-- Left Column -->
            <div class="col-lg-6">
              <!-- Data Pribadi Section -->
              <div class="form-section">
                <div class="form-section-title">
                  <i class="bi bi-person-badge"></i>
                  <span>Data Pribadi</span>
                </div>

                @if($isEdit)
                <div class="mb-3">
                  <label class="form-label">Nomor Induk</label>
                  <input type="text" class="form-control" value="{{ $santri->nomor_induk }}" disabled>
                  <small class="form-text">NIS tidak dapat diubah</small>
                </div>
                @endif

                <div class="mb-3">
                  <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text"
                    class="form-control @error('nama_lengkap') is-invalid @enderror"
                    name="nama_lengkap"
                    value="{{ old('nama_lengkap', $santri->nama_lengkap ?? '') }}"
                    placeholder="Masukkan nama lengkap"
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
                    value="{{ old('nama_panggilan', $santri->nama_panggilan ?? '') }}"
                    placeholder="Nama panggilan (opsional)">
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
                    <option value="L" {{ old('jenis_kelamin', $santri->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $santri->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
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
                      value="{{ old('tempat_lahir', $santri->tempat_lahir ?? '') }}"
                      placeholder="Kota kelahiran"
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
                      value="{{ old('tanggal_lahir', $isEdit ? $santri->tanggal_lahir->format('Y-m-d') : '') }}"
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
                    placeholder="Alamat lengkap termasuk RT/RW, Kelurahan, Kecamatan"
                    required>{{ old('alamat', $santri->alamat ?? '') }}</textarea>
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
                      {{ old('kelas_id', $santri->kelas_id ?? '') == $k->id ? 'selected' : '' }}>
                      {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                    </option>
                    @endforeach
                  </select>
                  @error('kelas_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                @if($isEdit)
                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select class="form-select" name="status">
                    <option value="aktif" {{ $santri->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="cuti" {{ $santri->status == 'cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="lulus" {{ $santri->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="keluar" {{ $santri->status == 'keluar' ? 'selected' : '' }}>Keluar</option>
                  </select>
                </div>
                @endif
              </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-6">
              <!-- Data Wali Section -->
              <div class="form-section">
                <div class="form-section-title">
                  <i class="bi bi-people"></i>
                  <span>Data Wali</span>
                </div>

                <div class="mb-3">
                  <label class="form-label">Nama Wali <span class="text-danger">*</span></label>
                  <input type="text"
                    class="form-control @error('nama_wali') is-invalid @enderror"
                    name="nama_wali"
                    value="{{ old('nama_wali', $santri->nama_wali ?? '') }}"
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
                    value="{{ old('no_telp_wali', $santri->no_telp_wali ?? '') }}"
                    placeholder="08XXXXXXXXXX"
                    required>
                  @error('no_telp_wali')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Akun Login Section -->
              <div class="form-section">
                <div class="form-section-title">
                  <i class="bi bi-shield-lock"></i>
                  <span>Akun Login</span>
                </div>

                <div class="mb-3">
                  <label class="form-label">Username <span class="text-danger">*</span></label>
                  <input type="text"
                    class="form-control @error('username') is-invalid @enderror"
                    name="username"
                    value="{{ old('username', $isEdit ? $santri->user->username : '') }}"
                    placeholder="Username untuk login"
                    {{ $isEdit ? '' : 'required' }}>
                  @error('username')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="form-text">Username tidak boleh mengandung spasi</small>
                </div>

                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email', $isEdit ? $santri->user->email : '') }}"
                    placeholder="email@example.com">
                  @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label">
                    Password
                    @if($isEdit)
                    <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>
                    @else
                    <span class="text-danger">*</span>
                    @endif
                  </label>
                  <input type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    placeholder="Minimal 6 karakter"
                    {{ $isEdit ? '' : 'required' }}>
                  @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Foto Section -->
              <div class="form-section">
                <div class="form-section-title">
                  <i class="bi bi-camera"></i>
                  <span>Foto Santri</span>
                </div>

                @if($isEdit && $santri->foto)
                <div class="photo-preview">
                  <img src="{{ asset('storage/' . $santri->foto) }}"
                    alt="{{ $santri->nama_lengkap }}"
                    style="max-width: 200px; max-height: 200px; object-fit: cover;">
                  <span class="photo-preview-badge">
                    <i class="bi bi-check"></i>
                  </span>
                </div>
                @endif

                <div class="mb-0">
                  <input type="file"
                    class="form-control @error('foto') is-invalid @enderror"
                    name="foto"
                    accept="image/*">
                  @error('foto')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    Format: JPG, PNG (Max: 2MB)
                    @if($isEdit). Kosongkan jika tidak ingin mengubah foto.@endif
                  </small>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="form-section">
            <div class="d-flex justify-content-between align-items-center">
              <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-2"></i>
                {{ $isEdit ? 'Update Data Santri' : 'Simpan Data Santri' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection