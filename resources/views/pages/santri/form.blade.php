{{-- resources/views/pages/kelas/form.blade.php --}}
@extends('layouts.dashboard')

@php
$isEdit = isset($santri);
$title = $isEdit ? 'Edit Data Santri' : 'Tambah Santri';
$route = $isEdit ? route('admin.santri.update', $santri->id) : route('admin.santri.store');
@endphp

@section('title', $title)
@section('page-title', $title)
@section('page-subtitle', $isEdit ? $santri->nama_lengkap : 'Form pendaftaran santri baru')

@section('content')
<form action="{{ $route }}" method="POST" enctype="multipart/form-data">
  @csrf
  @if($isEdit)
  @method('PUT')
  @endif

  <div class="row">
    <!-- Left Column -->
    <div class="col-lg-6">
      <!-- Data Pribadi Section -->
      <div class="rounded-3 p-4 mb-4 border">
        <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
          <i class="bi bi-person-badge text-primary fs-5"></i>
          <span>Data Pribadi</span>
        </h6>

        @if($isEdit)
        <div class="mb-3">
          <label class="form-label fw-semibold">Nomor Induk</label>
          <input type="text" class="form-control" value="{{ $santri->nomor_induk }}" disabled>
          <small class="text-muted d-block mt-1">
            <i class="bi bi-info-circle me-1"></i>NIS tidak dapat diubah
          </small>
        </div>
        @endif

        <div class="mb-3">
          <label class="form-label fw-semibold">
            Nama Lengkap <span class="text-danger">*</span>
          </label>
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
          <label class="form-label fw-semibold">Nama Panggilan</label>
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
          <label class="form-label fw-semibold">
            Jenis Kelamin <span class="text-danger">*</span>
          </label>
          <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
            name="jenis_kelamin"
            required>
            <option value="">Pilih Jenis Kelamin</option>
            <option value="L" {{ old('jenis_kelamin', $santri->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>
              Laki-laki
            </option>
            <option value="P" {{ old('jenis_kelamin', $santri->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>
              Perempuan
            </option>
          </select>
          @error('jenis_kelamin')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              Tempat Lahir <span class="text-danger">*</span>
            </label>
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
            <label class="form-label fw-semibold">
              Tanggal Lahir <span class="text-danger">*</span>
            </label>
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
          <label class="form-label fw-semibold">
            Alamat Lengkap <span class="text-danger">*</span>
          </label>
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
          <label class="form-label fw-semibold">Kelas</label>
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
        <div class="mb-0">
          <label class="form-label fw-semibold">Status</label>
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
      <div class="rounded-3 p-4 mb-4 border">
        <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
          <i class="bi bi-people text-success fs-5"></i>
          <span>Data Wali</span>
        </h6>

        <div class="mb-3">
          <label class="form-label fw-semibold">
            Nama Wali <span class="text-danger">*</span>
          </label>
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

        <div class="mb-0">
          <label class="form-label fw-semibold">
            No. Telepon Wali <span class="text-danger">*</span>
          </label>
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
      <div class="rounded-3 p-4 mb-4 border">
        <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
          <i class="bi bi-shield-lock text-warning fs-5"></i>
          <span>Akun Login</span>
        </h6>

        <div class="mb-3">
          <label class="form-label fw-semibold">
            Username <span class="text-danger">*</span>
          </label>
          <input type="text"
            class="form-control @error('username') is-invalid @enderror"
            name="username"
            value="{{ old('username', $isEdit ? $santri->user->username : '') }}"
            placeholder="Username untuk login"
            {{ $isEdit ? '' : 'required' }}>
          @error('username')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted d-block mt-1">
            <i class="bi bi-info-circle me-1"></i>Username tidak boleh mengandung spasi
          </small>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input type="email"
            class="form-control @error('email') is-invalid @enderror"
            name="email"
            value="{{ old('email', $isEdit ? $santri->user->email : '') }}"
            placeholder="email@example.com">
          @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-0">
          <label class="form-label fw-semibold">
            Password
            @if($isEdit)
            <small class="text-muted fw-normal">(Kosongkan jika tidak ingin mengubah)</small>
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
      <div class="rounded-3 p-4 mb-4 border">
        <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
          <i class="bi bi-camera text-info fs-5"></i>
          <span>Foto Santri</span>
        </h6>

        @if($isEdit && $santri->foto)
        <div class="text-center mb-3">
          <div class="position-relative d-inline-block">
            <img src="{{ asset('storage/santri/' . $santri->foto) }}"
              alt="{{ $santri->nama_lengkap }}"
              class="rounded-3 shadow-sm border border-3"
              style="max-width: 200px; max-height: 200px; object-fit: cover;">
            <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-success">
              <i class="bi bi-check"></i>
            </span>
          </div>
        </div>
        @endif

        <div class="mb-0">
          <input type="file"
            class="form-control @error('r_foto') is-invalid @enderror"
            name="r_foto"
            accept="image/*">
          @error('r_foto')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted d-block mt-1">
            <i class="bi bi-info-circle me-1"></i>
            Format: JPG, PNG (Max: 2MB)
            @if($isEdit). Kosongkan jika tidak ingin mengubah foto.@endif
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="rounded-3 p-4 border">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
      <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary w-100 w-md-auto">
        <i class="bi bi-arrow-left me-2"></i>Kembali
      </a>
      <button type="submit" class="btn btn-primary btn-lg w-100 w-md-auto shadow-sm">
        <i class="bi bi-check-circle me-2"></i>
        {{ $isEdit ? 'Update Data Santri' : 'Simpan Data Santri' }}
      </button>
    </div>
  </div>
</form>
@endsection