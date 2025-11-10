{{-- resources/views/kehadiran/form.blade.php --}}
@extends('layouts.dashboard')

@php
$isEdit = isset($kehadiran);
$title = $isEdit ? 'Edit Kehadiran' : 'Input Kehadiran';
$route = $isEdit ? route('admin.kehadiran.update', $kehadiran->id) : route('admin.kehadiran.store');
@endphp

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card">
      <div class="card-body">
        <form action="{{ $route }}" method="POST">
          @csrf
          @if($isEdit)
          @method('PUT')
          @endif

          @if($isEdit)
          <div class="mb-3">
            <label class="form-label">Santri</label>
            <input type="text" class="form-control" value="{{ $kehadiran->santri->nama_lengkap }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" value="{{ $kehadiran->tanggal->format('Y-m-d') }}" disabled>
          </div>
          @else
          <div class="mb-3">
            <label class="form-label">Santri <span class="text-danger">*</span></label>
            <select class="form-select @error('santri_id') is-invalid @enderror"
              name="santri_id" required>
              <option value="">Pilih Santri</option>
              @foreach($santri as $s)
              <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                {{ $s->nama_lengkap }} - {{ $s->nomor_induk }}
              </option>
              @endforeach
            </select>
            @error('santri_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
            <input type="date"
              class="form-control @error('tanggal') is-invalid @enderror"
              name="tanggal"
              value="{{ old('tanggal', date('Y-m-d')) }}"
              required>
            @error('tanggal')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          @endif

          <div class="mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror"
              name="status" required>
              <option value="">Pilih Status</option>
              <option value="hadir" {{ old('status', $kehadiran->status ?? '') == 'hadir' ? 'selected' : '' }}>Hadir</option>
              <option value="izin" {{ old('status', $kehadiran->status ?? '') == 'izin' ? 'selected' : '' }}>Izin</option>
              <option value="sakit" {{ old('status', $kehadiran->status ?? '') == 'sakit' ? 'selected' : '' }}>Sakit</option>
              <option value="alpa" {{ old('status', $kehadiran->status ?? '') == 'alpa' ? 'selected' : '' }}>Alpa</option>
            </select>
            @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          @if(!$isEdit)
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Masuk</label>
              <input type="time"
                class="form-control"
                name="jam_masuk"
                value="{{ old('jam_masuk') }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Keluar</label>
              <input type="time"
                class="form-control"
                name="jam_keluar"
                value="{{ old('jam_keluar') }}">
            </div>
          </div>
          @endif

          <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control"
              name="keterangan"
              rows="3">{{ old('keterangan', $kehadiran->keterangan ?? '') }}</textarea>
          </div>

          <hr class="my-4">

          <div class="d-flex justify-content-between">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.kehadiran.index') : route('ustadz.kehadiran.index') }}"
              class="btn btn-secondary">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save"></i> {{ $isEdit ? 'Update' : 'Simpan Kehadiran' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection