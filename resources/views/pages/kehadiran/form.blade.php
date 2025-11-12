{{-- resources/views/pages/kehadiran/form.blade.php --}}
@extends('layouts.dashboard')

@php
$isEdit = isset($kehadiran);
$title = $isEdit ? 'Edit Kehadiran' : 'Input Kehadiran';
$route = $isEdit ? route('admin.kehadiran.update', $kehadiran->id) : route('admin.kehadiran.store');
@endphp

@section('title', $title)
@section('page-title', $title)
@section('page-subtitle', $isEdit ? 'Edit data kehadiran' : 'Form input kehadiran santri')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <!-- Header Info -->
        <div class="alert alert-primary border-0 d-flex align-items-center gap-3 mb-4">
          <i class="bi bi-calendar-check fs-3"></i>
          <div>
            <strong class="d-block mb-1">Input Kehadiran</strong>
            <small>Catat kehadiran santri dengan lengkap dan akurat</small>
          </div>
        </div>

        <form action="{{ $route }}" method="POST">
          @csrf
          @if($isEdit)
          @method('PUT')
          @endif

          <!-- Data Kehadiran Section -->
          <div class="bg-light rounded-3 p-4 mb-4 border">
            <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
              <i class="bi bi-person-check text-primary fs-5"></i>
              <span>Data Kehadiran</span>
            </h6>

            @if($isEdit)
            <div class="mb-3">
              <label class="form-label fw-semibold">Santri</label>
              <input type="text" class="form-control bg-white" value="{{ $kehadiran->santri->nama_lengkap }}" disabled>
              <small class="text-muted d-block mt-1">
                <i class="bi bi-info-circle me-1"></i>NIS: {{ $kehadiran->santri->nomor_induk }}
              </small>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Tanggal</label>
              <input type="date" class="form-control bg-white" value="{{ $kehadiran->tanggal->format('Y-m-d') }}" disabled>
            </div>
            @else
            <div class="mb-3">
              <label class="form-label fw-semibold">
                Santri <span class="text-danger">*</span>
              </label>
              <select class="form-select @error('santri_id') is-invalid @enderror"
                name="santri_id" 
                id="santriSelect"
                required>
                <option value="">Pilih Santri</option>
                @foreach($santri as $s)
                <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                  {{ $s->nama_lengkap }} - {{ $s->nomor_induk }}
                  @if($s->kelas) ({{ $s->kelas->nama_kelas }}) @endif
                </option>
                @endforeach
              </select>
              @error('santri_id')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">
                Tanggal <span class="text-danger">*</span>
              </label>
              <input type="date"
                class="form-control @error('tanggal') is-invalid @enderror"
                name="tanggal"
                value="{{ old('tanggal', date('Y-m-d')) }}"
                max="{{ date('Y-m-d') }}"
                required>
              @error('tanggal')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="text-muted d-block mt-1">
                <i class="bi bi-calendar3 me-1"></i>Maksimal hari ini
              </small>
            </div>
            @endif

            <div class="mb-3">
              <label class="form-label fw-semibold">
                Status Kehadiran <span class="text-danger">*</span>
              </label>
              <div class="row g-2">
                <div class="col-6 col-md-3">
                  <input type="radio" 
                    class="btn-check" 
                    name="status" 
                    id="statusHadir" 
                    value="hadir"
                    {{ old('status', $kehadiran->status ?? '') == 'hadir' ? 'checked' : '' }}
                    required>
                  <label class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3" for="statusHadir">
                    <i class="bi bi-check-circle fs-3 mb-2"></i>
                    <span>Hadir</span>
                  </label>
                </div>
                <div class="col-6 col-md-3">
                  <input type="radio" 
                    class="btn-check" 
                    name="status" 
                    id="statusIzin" 
                    value="izin"
                    {{ old('status', $kehadiran->status ?? '') == 'izin' ? 'checked' : '' }}>
                  <label class="btn btn-outline-info w-100 d-flex flex-column align-items-center py-3" for="statusIzin">
                    <i class="bi bi-envelope fs-3 mb-2"></i>
                    <span>Izin</span>
                  </label>
                </div>
                <div class="col-6 col-md-3">
                  <input type="radio" 
                    class="btn-check" 
                    name="status" 
                    id="statusSakit" 
                    value="sakit"
                    {{ old('status', $kehadiran->status ?? '') == 'sakit' ? 'checked' : '' }}>
                  <label class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3" for="statusSakit">
                    <i class="bi bi-heart-pulse fs-3 mb-2"></i>
                    <span>Sakit</span>
                  </label>
                </div>
                <div class="col-6 col-md-3">
                  <input type="radio" 
                    class="btn-check" 
                    name="status" 
                    id="statusAlpa" 
                    value="alpa"
                    {{ old('status', $kehadiran->status ?? '') == 'alpa' ? 'checked' : '' }}>
                  <label class="btn btn-outline-danger w-100 d-flex flex-column align-items-center py-3" for="statusAlpa">
                    <i class="bi bi-x-circle fs-3 mb-2"></i>
                    <span>Alpa</span>
                  </label>
                </div>
              </div>
              @error('status')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            @if(!$isEdit)
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Jam Masuk</label>
                <div class="input-group shadow-sm">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-clock text-muted"></i>
                  </span>
                  <input type="time"
                    class="form-control border-start-0 ps-0"
                    name="jam_masuk"
                    value="{{ old('jam_masuk') }}">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Jam Keluar</label>
                <div class="input-group shadow-sm">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-clock-history text-muted"></i>
                  </span>
                  <input type="time"
                    class="form-control border-start-0 ps-0"
                    name="jam_keluar"
                    value="{{ old('jam_keluar') }}">
                </div>
              </div>
            </div>
            @endif

            <div class="mb-0">
              <label class="form-label fw-semibold">Keterangan</label>
              <textarea class="form-control"
                name="keterangan"
                rows="3"
                placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $kehadiran->keterangan ?? '') }}</textarea>
              <small class="text-muted d-block mt-1">
                <i class="bi bi-pencil me-1"></i>Tambahkan catatan jika diperlukan
              </small>
            </div>
          </div>

          <!-- Quick Stats (Hanya untuk create) -->
          @if(!$isEdit)
          <div class="alert alert-info border-0 mb-4">
            <h6 class="fw-bold mb-2 d-flex align-items-center gap-2">
              <i class="bi bi-graph-up"></i>
              <span>Statistik Hari Ini</span>
            </h6>
            <div class="row g-2 text-center">
              <div class="col-3">
                <div class="fs-4 fw-bold text-success">{{ \App\Models\Kehadiran::whereDate('tanggal', date('Y-m-d'))->where('status', 'hadir')->count() }}</div>
                <small class="text-muted">Hadir</small>
              </div>
              <div class="col-3">
                <div class="fs-4 fw-bold text-info">{{ \App\Models\Kehadiran::whereDate('tanggal', date('Y-m-d'))->where('status', 'izin')->count() }}</div>
                <small class="text-muted">Izin</small>
              </div>
              <div class="col-3">
                <div class="fs-4 fw-bold text-warning">{{ \App\Models\Kehadiran::whereDate('tanggal', date('Y-m-d'))->where('status', 'sakit')->count() }}</div>
                <small class="text-muted">Sakit</small>
              </div>
              <div class="col-3">
                <div class="fs-4 fw-bold text-danger">{{ \App\Models\Kehadiran::whereDate('tanggal', date('Y-m-d'))->where('status', 'alpa')->count() }}</div>
                <small class="text-muted">Alpa</small>
              </div>
            </div>
          </div>
          @endif

          <!-- Action Buttons -->
          <div class="bg-light rounded-3 p-4 border">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
              <a href="{{ auth()->user()->isAdmin() ? route('admin.kehadiran.index') : route('ustadz.kehadiran.index') }}" 
                 class="btn btn-secondary w-100 w-md-auto">
                <i class="bi bi-arrow-left me-2"></i>Kembali
              </a>
              <button type="submit" class="btn btn-primary btn-lg w-100 w-md-auto shadow-sm">
                <i class="bi bi-{{ $isEdit ? 'pencil-square' : 'save' }} me-2"></i>
                {{ $isEdit ? 'Update' : 'Simpan Kehadiran' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection