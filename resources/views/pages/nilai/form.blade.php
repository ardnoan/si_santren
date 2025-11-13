{{-- resources/views/pages/nilai/form.blade.php --}}
@extends('layouts.dashboard')

@php
$isEdit = isset($nilai);
$title = $isEdit ? 'Edit Nilai' : 'Input Nilai';
$route = $isEdit ? route('admin.nilai.update', $nilai->id) : route('admin.nilai.store');
@endphp

@section('title', $title)
@section('page-title', $title)
@section('page-subtitle', $isEdit ? 'Edit data nilai' : 'Form input nilai santri')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <!-- Header Info -->
        <div class="alert alert-warning border-0 d-flex align-items-center gap-3 mb-4">
          <i class="bi bi-journal-text fs-3"></i>
          <div>
            <strong class="d-block mb-1">Input Nilai Akademik</strong>
            <small>Input nilai dengan teliti. Sistem akan menghitung nilai akhir secara otomatis</small>
          </div>
        </div>

        <form action="{{ $route }}" method="POST">
          @csrf
          @if($isEdit)
          @method('PUT')
          @endif

          <!-- Data Santri & Mapel Section -->
          <div class="bg-light rounded-3 p-4 mb-4 border">
            <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
              <i class="bi bi-person-badge text-primary fs-5"></i>
              <span>Data Santri & Mata Pelajaran</span>
            </h6>

            @if($isEdit)
            <div class="mb-3">
              <label class="form-label fw-semibold">Santri</label>
              <input type="text" class="form-control bg-white" value="{{ $nilai->santri->nama_lengkap }}" disabled>
              <small class="text-muted d-block mt-1">
                <i class="bi bi-info-circle me-1"></i>NIS: {{ $nilai->santri->nomor_induk }}
              </small>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Mata Pelajaran</label>
              <input type="text" class="form-control bg-white" value="{{ $nilai->mataPelajaran->nama_mapel }}" disabled>
            </div>
            @else
            <div class="mb-3">
              <label class="form-label fw-semibold">
                Santri <span class="text-danger">*</span>
              </label>
              <select class="form-select @error('santri_id') is-invalid @enderror"
                name="santri_id" 
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
                Mata Pelajaran <span class="text-danger">*</span>
              </label>
              <select class="form-select @error('mata_pelajaran_id') is-invalid @enderror"
                name="mata_pelajaran_id" 
                required>
                <option value="">Pilih Mata Pelajaran</option>
                @foreach($mapel as $m)
                <option value="{{ $m->id }}" {{ old('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>
                  {{ $m->nama_mapel }}
                </option>
                @endforeach
              </select>
              @error('mata_pelajaran_id')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            @endif

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                  Semester <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('semester') is-invalid @enderror"
                  name="semester"
                  {{ $isEdit ? 'disabled' : 'required' }}>
                  <option value="">Pilih Semester</option>
                  <option value="Ganjil" {{ old('semester', $nilai->semester ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                  <option value="Genap" {{ old('semester', $nilai->semester ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
                @error('semester')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-0">
                <label class="form-label fw-semibold">
                  Tahun Ajaran <span class="text-danger">*</span>
                </label>
                <input type="text"
                  class="form-control @error('tahun_ajaran') is-invalid @enderror"
                  name="tahun_ajaran"
                  placeholder="Contoh: 2024/2025"
                  value="{{ old('tahun_ajaran', $nilai->tahun_ajaran ?? '2024/2025') }}"
                  {{ $isEdit ? 'disabled' : 'required' }}>
                @error('tahun_ajaran')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Nilai Section -->
          <div class="bg-light rounded-3 p-4 mb-4 border">
            <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
              <i class="bi bi-calculator text-success fs-5"></i>
              <span>Komponen Nilai</span>
            </h6>

            <div class="alert alert-info border-0 mb-3">
              <small>
                <i class="bi bi-info-circle me-1"></i>
                <strong>Formula:</strong> Nilai Akhir = (Tugas × 30%) + (UTS × 30%) + (UAS × 40%)
              </small>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">
                  Nilai Tugas <span class="badge bg-primary">30%</span>
                </label>
                <div class="input-group shadow-sm">
                  <input type="number"
                    class="form-control @error('nilai_tugas') is-invalid @enderror"
                    name="nilai_tugas"
                    id="nilaiTugas"
                    min="0" 
                    max="100"
                    step="0.01"
                    placeholder="0-100"
                    value="{{ old('nilai_tugas', $nilai->nilai_tugas ?? '') }}">
                  <span class="input-group-text bg-light">
                    <i class="bi bi-file-text text-muted"></i>
                  </span>
                </div>
                @error('nilai_tugas')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">
                  Nilai UTS <span class="badge bg-warning">30%</span>
                </label>
                <div class="input-group shadow-sm">
                  <input type="number"
                    class="form-control @error('nilai_uts') is-invalid @enderror"
                    name="nilai_uts"
                    id="nilaiUTS"
                    min="0" 
                    max="100"
                    step="0.01"
                    placeholder="0-100"
                    value="{{ old('nilai_uts', $nilai->nilai_uts ?? '') }}">
                  <span class="input-group-text bg-light">
                    <i class="bi bi-clipboard-check text-muted"></i>
                  </span>
                </div>
                @error('nilai_uts')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4 mb-0">
                <label class="form-label fw-semibold">
                  Nilai UAS <span class="badge bg-danger">40%</span>
                </label>
                <div class="input-group shadow-sm">
                  <input type="number"
                    class="form-control @error('nilai_uas') is-invalid @enderror"
                    name="nilai_uas"
                    id="nilaiUAS"
                    min="0" 
                    max="100"
                    step="0.01"
                    placeholder="0-100"
                    value="{{ old('nilai_uas', $nilai->nilai_uas ?? '') }}">
                  <span class="input-group-text bg-light">
                    <i class="bi bi-file-earmark-check text-muted"></i>
                  </span>
                </div>
                @error('nilai_uas')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Preview Nilai Akhir -->
          <div class="alert alert-success border-0 shadow-sm d-none" id="previewNilai">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="fw-bold mb-1 d-flex align-items-center gap-2">
                  <i class="bi bi-trophy"></i>
                  <span>Nilai Akhir</span>
                </h6>
                <small class="text-muted">Hasil perhitungan otomatis</small>
              </div>
              <div class="text-end">
                <div class="display-4 fw-bold mb-1" id="nilaiAkhirText">0</div>
                <span class="badge fs-6 px-3 py-2" id="predikatBadge">-</span>
              </div>
            </div>
            <div class="progress mt-3" style="height: 8px;">
              <div class="progress-bar" id="nilaiProgress" style="width: 0%"></div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="bg-light rounded-3 p-4 border">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
              <a href="{{ auth()->user()->isAdmin() ? route('admin.nilai.index') : route('ustadz.nilai.index') }}"
                 class="btn btn-secondary w-100 w-md-auto">
                <i class="bi bi-arrow-left me-2"></i>Kembali
              </a>
              <button type="submit" class="btn btn-primary btn-lg w-100 w-md-auto shadow-sm">
                <i class="bi bi-{{ $isEdit ? 'pencil-square' : 'save' }} me-2"></i>
                {{ $isEdit ? 'Update' : 'Simpan Nilai' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function hitungNilaiAkhir() {
    const tugas = parseFloat(document.getElementById('nilaiTugas').value) || 0;
    const uts = parseFloat(document.getElementById('nilaiUTS').value) || 0;
    const uas = parseFloat(document.getElementById('nilaiUAS').value) || 0;

    const nilaiAkhir = (tugas * 0.3) + (uts * 0.3) + (uas * 0.4);

    let predikat = '';
    let badgeClass = '';
    let progressClass = '';

    if (nilaiAkhir >= 90) {
      predikat = 'A';
      badgeClass = 'bg-success';
      progressClass = 'bg-success';
    } else if (nilaiAkhir >= 80) {
      predikat = 'B';
      badgeClass = 'bg-info';
      progressClass = 'bg-info';
    } else if (nilaiAkhir >= 70) {
      predikat = 'C';
      badgeClass = 'bg-warning';
      progressClass = 'bg-warning';
    } else if (nilaiAkhir >= 60) {
      predikat = 'D';
      badgeClass = 'bg-danger';
      progressClass = 'bg-danger';
    } else {
      predikat = 'E';
      badgeClass = 'bg-dark';
      progressClass = 'bg-dark';
    }

    const preview = document.getElementById('previewNilai');
    const nilaiText = document.getElementById('nilaiAkhirText');
    const predikatBadge = document.getElementById('predikatBadge');
    const progressBar = document.getElementById('nilaiProgress');

    if (tugas > 0 || uts > 0 || uas > 0) {
      preview.classList.remove('d-none');
      nilaiText.textContent = nilaiAkhir.toFixed(2);
      predikatBadge.textContent = predikat;
      predikatBadge.className = 'badge fs-6 px-3 py-2 ' + badgeClass;
      progressBar.style.width = nilaiAkhir + '%';
      progressBar.className = 'progress-bar ' + progressClass;
    } else {
      preview.classList.add('d-none');
    }
  }

  document.getElementById('nilaiTugas').addEventListener('input', hitungNilaiAkhir);
  document.getElementById('nilaiUTS').addEventListener('input', hitungNilaiAkhir);
  document.getElementById('nilaiUAS').addEventListener('input', hitungNilaiAkhir);

  // Trigger on page load
  hitungNilaiAkhir();
</script>
@endsection