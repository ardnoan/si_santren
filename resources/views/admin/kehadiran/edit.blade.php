
<!-- ============================================ -->
<!-- FILE 3: resources/views/admin/kehadiran/edit.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Edit Kehadiran')
@section('page-title', 'Edit Kehadiran')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.kehadiran.update', $kehadiran->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Santri</label>
                        <input type="text" class="form-control" value="{{ $kehadiran->santri->nama_lengkap }}" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" value="{{ $kehadiran->tanggal->format('Y-m-d') }}" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="hadir" {{ $kehadiran->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin" {{ $kehadiran->status == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ $kehadiran->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alpa" {{ $kehadiran->status == 'alpa' ? 'selected' : '' }}>Alpa</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3">{{ $kehadiran->keterangan }}</textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection