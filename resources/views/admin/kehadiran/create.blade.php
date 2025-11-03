
<!-- ============================================ -->
<!-- FILE 2: resources/views/admin/kehadiran/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Input Kehadiran')
@section('page-title', 'Input Kehadiran')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.kehadiran.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Santri <span class="text-danger">*</span></label>
                        <select class="form-select @error('santri_id') is-invalid @enderror" 
                                name="santri_id" required>
                            <option value="">Pilih Santri</option>
                            @foreach($santri as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_lengkap }} - {{ $s->nomor_induk }}</option>
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
                    
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpa">Alpa</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
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
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" 
                                  name="keterangan" 
                                  rows="3">{{ old('keterangan') }}</textarea>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Kehadiran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
