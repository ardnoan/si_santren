{{-- resources/views/pages/kelas/form.blade.php --}}
@extends('layouts.dashboard')

@php
$isEdit = isset($kelas);
$title = $isEdit ? 'Edit Kelas' : 'Tambah Kelas';
$route = $isEdit ? route('admin.kelas.update', $kelas->id) : route('admin.kelas.store');
@endphp

@section('title', $title)
@section('page-title', $title)
@section('page-subtitle', $isEdit ? 'Edit data kelas' : 'Form tambah kelas baru')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">

        <!-- Header Info -->
        <div class="alert alert-info border-0 d-flex align-items-center gap-3 mb-4">
          <i class="bi bi-info-circle fs-3"></i>
          <div>
            <strong class="d-block mb-1">Informasi</strong>
            <small>Form untuk {{ $isEdit ? 'mengubah' : 'menambahkan' }} data kelas.
              Pastikan semua data terisi dengan benar.</small>
          </div>
        </div>

        <form action="{{ $route }}" method="POST">
          @csrf
          @if($isEdit)
            @method('PUT')
          @endif

          <!-- Informasi Kelas Section -->
          <div class="bg-light rounded-3 p-4 mb-4 border">
            <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
              <i class="bi bi-building text-primary fs-5"></i>
              <span>Informasi Kelas</span>
            </h6>

            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror"
                name="nama_kelas" placeholder="Contoh: 1A, 2B, 3C"
                value="{{ old('nama_kelas', $kelas->nama_kelas ?? '') }}" required>
              @error('nama_kelas')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Tingkat <span class="text-danger">*</span></label>
                <select class="form-select @error('tingkat') is-invalid @enderror" name="tingkat" required>
                  <option value="">Pilih Tingkat</option>
                  @for($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ old('tingkat', $kelas->tingkat ?? '') == $i ? 'selected' : '' }}>
                      Tingkat {{ $i }}
                    </option>
                  @endfor
                </select>
                @error('tingkat')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror"
                  name="tahun_ajaran" placeholder="Contoh: 2024/2025"
                  value="{{ old('tahun_ajaran', $kelas->tahun_ajaran ?? '2024/2025') }}" required>
                @error('tahun_ajaran')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-0">
                <label class="form-label fw-semibold">Kapasitas <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('kapasitas') is-invalid @enderror"
                  name="kapasitas" min="1" max="50"
                  value="{{ old('kapasitas', $kelas->kapasitas ?? 30) }}" required>
                @error('kapasitas')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-0">
                <label class="form-label fw-semibold">Wali Kelas</label>
                <select class="form-select @error('wali_kelas') is-invalid @enderror" name="wali_kelas">
                  <option value="">Belum ada wali kelas</option>
                  @foreach($ustadz as $u)
                    <option value="{{ $u->id }}" {{ old('wali_kelas', $kelas->wali_kelas ?? '') == $u->id ? 'selected' : '' }}>
                      {{ $u->username }}
                    </option>
                  @endforeach
                </select>
                @error('wali_kelas')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Preview Section untuk Create -->
          @unless($isEdit)
          <div class="alert alert-success border-0 mb-4">
            <h6 class="fw-bold mb-2 d-flex align-items-center gap-2">
              <i class="bi bi-eye"></i> <span>Preview Kelas</span>
            </h6>
            <div class="row g-2">
              <div class="col-6">
                <small class="text-muted d-block">Nama Kelas:</small>
                <strong>-</strong>
              </div>
              <div class="col-6">
                <small class="text-muted d-block">Tingkat:</small>
                <strong>-</strong>
              </div>
              <div class="col-6">
                <small class="text-muted d-block">Tahun Ajaran:</small>
                <strong>2024/2025</strong>
              </div>
              <div class="col-6">
                <small class="text-muted d-block">Kapasitas:</small>
                <strong>30 siswa</strong>
              </div>
            </div>
          </div>
          @endunless

          <!-- Statistik Section untuk Edit -->
          @if($isEdit && $kelas->santris)
          <div class="alert alert-info border-0 mb-4">
            <h6 class="fw-bold mb-2 d-flex align-items-center gap-2">
              <i class="bi bi-graph-up"></i>
              <span>Statistik Kelas</span>
            </h6>
            <div class="row g-3 text-center">
              <div class="col-6 col-md-3">
                <div class="fs-3 fw-bold text-primary">{{ $kelas->santris->count() }}</div>
                <small class="text-muted">Total Santri</small>
              </div>
              <div class="col-6 col-md-3">
                <div class="fs-3 fw-bold text-info">{{ $kelas->santris->where('jenis_kelamin', 'L')->count() }}</div>
                <small class="text-muted">Laki-laki</small>
              </div>
              <div class="col-6 col-md-3">
                <div class="fs-3 fw-bold text-danger">{{ $kelas->santris->where('jenis_kelamin', 'P')->count() }}</div>
                <small class="text-muted">Perempuan</small>
              </div>
              <div class="col-6 col-md-3">
                <div class="fs-3 fw-bold text-success">{{ $kelas->kapasitas - $kelas->santris->count() }}</div>
                <small class="text-muted">Sisa Kursi</small>
              </div>
            </div>
          </div>
          @endif

          <!-- Action Buttons -->
          <div class="bg-light rounded-3 p-4 border">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
              <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary w-100 w-md-auto">
                <i class="bi bi-arrow-left me-2"></i>Kembali
              </a>
              <button type="submit" class="btn btn-primary btn-lg w-100 w-md-auto shadow-sm">
                <i class="bi bi-{{ $isEdit ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                {{ $isEdit ? 'Update Kelas' : 'Simpan Kelas' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection