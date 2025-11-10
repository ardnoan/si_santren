{{-- resources/views/nilai/form.blade.php --}}
@extends('layouts.dashboard')

@php
$isEdit = isset($nilai);
$title = $isEdit ? 'Edit Nilai' : 'Input Nilai';
$route = $isEdit ? route('admin.nilai.update', $nilai->id) : route('admin.nilai.store');
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
            <input type="text" class="form-control" value="{{ $nilai->santri->nama_lengkap }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            <input type="text" class="form-control" value="{{ $nilai->mataPelajaran->nama_mapel }}" disabled>
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
            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
            <select class="form-select @error('mata_pelajaran_id') is-invalid @enderror"
              name="mata_pelajaran_id" required>
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
              <label class="form-label">Semester <span class="text-danger">*</span></label>
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

            <div class="col-md-6 mb-3">
              <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
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

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Nilai Tugas (30%)</label>
              <input type="number"
                class="form-control @error('nilai_tugas') is-invalid @enderror"
                name="nilai_tugas"
                min="0" max="100"
                step="0.01"
                placeholder="0-100"
                id="nilaiTugas"
                value="{{ old('nilai_tugas', $nilai->nilai_tugas ?? '') }}">
              @error('nilai_tugas')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Nilai UTS (30%)</label>
              <input type="number"
                class="form-control @error('nilai_uts') is-invalid @enderror"
                name="nilai_uts"
                min="0" max="100"
                step="0.01"
                placeholder="0-100"
                id="nilaiUTS"
                value="{{ old('nilai_uts', $nilai->nilai_uts ?? '') }}">
              @error('nilai_uts')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Nilai UAS (40%)</label>
              <input type="number"
                class="form-control @error('nilai_uas') is-invalid @enderror"
                name="nilai_uas"
                min="0" max="100"
                step="0.01"
                placeholder="0-100"
                id="nilaiUAS"
                value="{{ old('nilai_uas', $nilai->nilai_uas ?? '') }}">
              @error('nilai_uas')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Preview Nilai Akhir -->
          <div class="alert alert-info" id="previewNilai" style="display:none;">
            <h6>Preview Nilai Akhir:</h6>
            <h3 class="mb-0" id="nilaiAkhirText">0</h3>
            <small>Predikat: <span id="predikatText" class="badge">-</span></small>
          </div>

          <hr class="my-4">

          <div class="d-flex justify-content-between">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.nilai.index') : route('ustadz.nilai.index') }}"
              class="btn btn-secondary">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save"></i> {{ $isEdit ? 'Update' : 'Simpan Nilai' }}
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
  function hitungNilaiAkhir() {
    const tugas = parseFloat(document.getElementById('nilaiTugas').value) || 0;
    const uts = parseFloat(document.getElementById('nilaiUTS').value) || 0;
    const uas = parseFloat(document.getElementById('nilaiUAS').value) || 0;

    const nilaiAkhir = (tugas * 0.3) + (uts * 0.3) + (uas * 0.4);

    let predikat = '';
    let badgeClass = '';

    if (nilaiAkhir >= 90) {
      predikat = 'A';
      badgeClass = 'bg-success';
    } else if (nilaiAkhir >= 80) {
      predikat = 'B';
      badgeClass = 'bg-info';
    } else if (nilaiAkhir >= 70) {
      predikat = 'C';
      badgeClass = 'bg-warning';
    } else if (nilaiAkhir >= 60) {
      predikat = 'D';
      badgeClass = 'bg-danger';
    } else {
      predikat = 'E';
      badgeClass = 'bg-dark';
    }

    if (tugas > 0 || uts > 0 || uas > 0) {
      document.getElementById('previewNilai').style.display = 'block';
      document.getElementById('nilaiAkhirText').textContent = nilaiAkhir.toFixed(2);
      document.getElementById('predikatText').textContent = predikat;
      document.getElementById('predikatText').className = 'badge ' + badgeClass;
    } else {
      document.getElementById('previewNilai').style.display = 'none';
    }
  }

  document.getElementById('nilaiTugas').addEventListener('input', hitungNilaiAkhir);
  document.getElementById('nilaiUTS').addEventListener('input', hitungNilaiAkhir);
  document.getElementById('nilaiUAS').addEventListener('input', hitungNilaiAkhir);

  // Trigger on page load
  hitungNilaiAkhir();
</script>
@endsection