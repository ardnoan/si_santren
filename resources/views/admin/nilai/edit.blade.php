@extends('layouts.dashboard')

@section('title', 'Edit Nilai')
@section('page-title', 'Edit Nilai')

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('admin.nilai.update', $nilai->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Santri</label>
            <input type="text" class="form-control" value="{{ $nilai->santri->nama_lengkap }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            <input type="text" class="form-control" value="{{ $nilai->mataPelajaran->nama_mapel }}" disabled>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Semester</label>
              <input type="text" class="form-control" value="{{ $nilai->semester }}" disabled>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Tahun Ajaran</label>
              <input type="text" class="form-control" value="{{ $nilai->tahun_ajaran }}" disabled>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Nilai Tugas (30%)</label>
              <input type="number" class="form-control" name="nilai_tugas"
                value="{{ old('nilai_tugas', $nilai->nilai_tugas) }}"
                min="0" max="100" step="0.01">
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Nilai UTS (30%)</label>
              <input type="number" class="form-control" name="nilai_uts"
                value="{{ old('nilai_uts', $nilai->nilai_uts) }}"
                min="0" max="100" step="0.01">
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Nilai UAS (40%)</label>
              <input type="number" class="form-control" name="nilai_uas"
                value="{{ old('nilai_uas', $nilai->nilai_uas) }}"
                min="0" max="100" step="0.01">
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.nilai.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection