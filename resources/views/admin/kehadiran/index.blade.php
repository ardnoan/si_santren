<!-- ============================================ -->
<!-- FILE 1: resources/views/admin/kehadiran/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Kehadiran')
@section('page-title', 'Data Kehadiran')
@section('page-subtitle', 'Monitoring kehadiran santri')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Kehadiran Santri
                    </h5>
                    <a href="{{ route('admin.kehadiran.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Input Kehadiran
                    </a>
                </div>
                
                <!-- Date Filter -->
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="date" 
                                   name="tanggal" 
                                   class="form-control" 
                                   value="{{ request('tanggal', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-7 text-end">
                            <div class="btn-group">
                                <a href="?tanggal={{ date('Y-m-d', strtotime('-1 day', strtotime(request('tanggal', date('Y-m-d'))))) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="bi bi-chevron-left"></i> Kemarin
                                </a>
                                <a href="?tanggal={{ date('Y-m-d') }}" class="btn btn-outline-primary">
                                    Hari Ini
                                </a>
                                <a href="?tanggal={{ date('Y-m-d', strtotime('+1 day', strtotime(request('tanggal', date('Y-m-d'))))) }}" 
                                   class="btn btn-outline-secondary">
                                    Besok <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Statistics -->
                @php
                    $tanggal = request('tanggal', date('Y-m-d'));
                    $kehadiran = App\Models\Kehadiran::whereDate('tanggal', $tanggal)->get();
                    $hadir = $kehadiran->where('status', 'hadir')->count();
                    $izin = $kehadiran->where('status', 'izin')->count();
                    $sakit = $kehadiran->where('status', 'sakit')->count();
                    $alpa = $kehadiran->where('status', 'alpa')->count();
                @endphp
                
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="alert alert-success mb-0">
                            <strong><i class="bi bi-check-circle"></i> Hadir:</strong>
                            <h4 class="mb-0">{{ $hadir }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-info mb-0">
                            <strong><i class="bi bi-envelope"></i> Izin:</strong>
                            <h4 class="mb-0">{{ $izin }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-warning mb-0">
                            <strong><i class="bi bi-heart-pulse"></i> Sakit:</strong>
                            <h4 class="mb-0">{{ $sakit }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-danger mb-0">
                            <strong><i class="bi bi-x-circle"></i> Alpa:</strong>
                            <h4 class="mb-0">{{ $alpa }}</h4>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Santri</th>
                                <th width="15%">Kelas</th>
                                <th width="10%">Status</th>
                                <th width="20%">Waktu</th>
                                <th width="20%">Keterangan</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $data = App\Models\Kehadiran::with(['santri.kelas'])
                                    ->whereDate('tanggal', $tanggal)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp
                            
                            @forelse($data as $i => $k)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <strong>{{ $k->santri->nama_lengkap }}</strong><br>
                                    <small class="text-muted">{{ $k->santri->nomor_induk }}</small>
                                </td>
                                <td>
                                    @if($k->santri->kelas)
                                        <span class="badge bg-info">{{ $k->santri->kelas->nama_kelas }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->status == 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($k->status == 'izin')
                                        <span class="badge bg-info">Izin</span>
                                    @elseif($k->status == 'sakit')
                                        <span class="badge bg-warning">Sakit</span>
                                    @else
                                        <span class="badge bg-danger">Alpa</span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->jam_masuk)
                                        <small><i class="bi bi-box-arrow-in-right text-success"></i> {{ $k->jam_masuk }}</small>
                                    @endif
                                    @if($k->jam_keluar)
                                        <br><small><i class="bi bi-box-arrow-left text-danger"></i> {{ $k->jam_keluar }}</small>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $k->keterangan ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.kehadiran.edit', $k->id) }}" 
                                           class="btn btn-warning"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.kehadiran.destroy', $k->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-muted"></i>
                                    <h5 class="text-muted">Belum ada data kehadiran untuk tanggal ini</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection