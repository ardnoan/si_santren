

{{-- ===== FILE 2: resources/views/pages/pengeluaran/form.blade.php ===== --}}
@extends('layouts.dashboard')

@php
$isEdit = isset($pengeluaran);
$title = $isEdit ? 'Edit Pengeluaran' : 'Tambah Pengeluaran';
$route = $isEdit ? route('bendahara.pengeluaran.update', $pengeluaran->id) : route('bendahara.pengeluaran.store');
@endphp

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <form action="{{ $route }}" 
              method="POST" 
              enctype="multipart/form-data">
          @csrf
          @if($isEdit) @method('PUT') @endif

          <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
            <input type="date" 
                   class="form-control @error('tanggal') is-invalid @enderror" 
                   name="tanggal" 
                   value="{{ old('tanggal', $isEdit ? $pengeluaran->tanggal->format('Y-m-d') : date('Y-m-d')) }}" 
                   required>
            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
            <select class="form-select @error('kategori') is-invalid @enderror" name="kategori" required>
              <option value="">Pilih Kategori</option>
              <option value="operasional" {{ old('kategori', $pengeluaran->kategori ?? '') == 'operasional' ? 'selected' : '' }}>Operasional</option>
              <option value="konsumsi" {{ old('kategori', $pengeluaran->kategori ?? '') == 'konsumsi' ? 'selected' : '' }}>Konsumsi</option>
              <option value="listrik" {{ old('kategori', $pengeluaran->kategori ?? '') == 'listrik' ? 'selected' : '' }}>Listrik</option>
              <option value="air" {{ old('kategori', $pengeluaran->kategori ?? '') == 'air' ? 'selected' : '' }}>Air</option>
              <option value="perawatan" {{ old('kategori', $pengeluaran->kategori ?? '') == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
              <option value="lainnya" {{ old('kategori', $pengeluaran->kategori ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Jumlah (Rp) <span class="text-danger">*</span></label>
            <input type="number" 
                   class="form-control @error('jumlah') is-invalid @enderror" 
                   name="jumlah" 
                   value="{{ old('jumlah', $pengeluaran->jumlah ?? '') }}" 
                   min="0" 
                   required>
            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Keterangan <span class="text-danger">*</span></label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                      name="keterangan" 
                      rows="3" 
                      required>{{ old('keterangan', $pengeluaran->keterangan ?? '') }}</textarea>
            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Bukti (Foto/Nota)</label>
            @if($isEdit && $pengeluaran->bukti)
            <div class="mb-2">
              <img src="{{ asset('storage/' . $pengeluaran->bukti) }}" class="img-thumbnail" style="max-width: 200px;">
            </div>
            @endif
            <input type="file" class="form-control" name="bukti" accept="image/*">
            <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('bendahara.pengeluaran.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save me-2"></i>Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection