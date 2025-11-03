@extends('layouts.admin')

@section('title', 'Detail Santri')
@section('page-title', 'Detail Data Santri')
@section('page-subtitle', $santri->nama_lengkap)

@section('content')
<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($santri->foto)
                    <img src="{{ asset('storage/' . $santri->foto) }}" 
                         alt="{{ $santri->nama_lengkap }}" 
                         class="rounded-circle mb-3"
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-secondary mx-auto mb-3 d-flex align-items-center justify-content-center text-white" 
                         style="width: 150px; height: 150px;">
                        <i class="bi bi-person fs-1"></i>
                    </div>
                @endif
                
                <h4 class="mb-1">{{ $santri->nama_lengkap }}</h4>
                
                @if($santri->nama_panggilan)
                    <p class="text-muted mb-2">({{ $santri->nama_panggilan }})</p>
                @endif
                
                <span class="badge bg-{{ $santri->status_badge }} mb-3">
                    {{ ucfirst($santri->status) }}
                </span>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.santri.edit', $santri->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Data
                    </a>
                    
                    @if($santri->status === 'aktif')
                        <form action="{{ route('admin.santri.graduate', $santri->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin meluluskan santri ini?')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-mortarboard"></i> Luluskan
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.santri.destroy', $santri->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin hapus data santri ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Hapus Data
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-graph-up"></i> Statistik Cepat</h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Pembayaran:</span>
                    <strong class="text-success">
                        Rp {{ number_format($santri->pembayaran->where('status', 'lunas')->sum('jumlah'), 0, ',', '.') }}
                    </strong>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Kehadiran Bulan Ini:</span>
                    <strong class="text-info">
                        {{ $santri->kehadiran()->whereMonth('tanggal', date('m'))->count() }} hari
                    </strong>
                </div>
                
                <div class="d-flex justify-content-between">
                    <span>Rata-rata Nilai:</span>
                    <strong class="text-warning">
                        {{ $santri->nilai->avg('nilai_akhir') ? number_format($santri->nilai->avg('nilai_akhir'), 2) : '-' }}
                    </strong>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Details & Tabs -->
    <div class="col-md-8">
        <!-- Personal Info -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">NIS:</th>
                                <td><strong>{{ $santri->nomor_induk }}</strong></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin:</th>
                                <td>{{ $santri->jenis_kelamin_label }}</td>
                            </tr>
                            <tr>
                                <th>Tempat, Tgl Lahir:</th>
                                <td>{{ $santri->tempat_lahir }}, {{ $santri->tanggal_lahir->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Umur:</th>
                                <td>{{ $santri->umur }} tahun</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Kelas:</th>
                                <td>
                                    @if($santri->kelas)
                                        <span class="badge bg-info">{{ $santri->kelas->nama_kelas }}</span>
                                    @else
                                        <span class="badge bg-secondary">Belum ada kelas</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Masuk:</th>
                                <td>{{ $santri->tanggal_masuk ? $santri->tanggal_masuk->format('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat:</th>
                                <td>{{ $santri->alamat }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Parent Info -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Data Wali</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Nama Wali:</strong></p>
                        <p>{{ $santri->nama_wali }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>No. Telepon:</strong></p>
                        <p>
                            <a href="tel:{{ $santri->no_telp_wali }}">{{ $santri->no_telp_wali }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#pembayaran">
                            <i class="bi bi-cash"></i> Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kehadiran">
                            <i class="bi bi-calendar-check"></i> Kehadiran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#nilai">
                            <i class="bi bi-journal-text"></i> Nilai
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Pembayaran Tab -->
                    <div class="tab-pane fade show active" id="pembayaran">
                        @if($santri->pembayaran->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($santri->pembayaran->take(5) as $p)
                                        <tr>
                                            <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                                            <td>{{ $p->jenis_pembayaran_label }}</td>
                                            <td>{{ $p->jumlah_format }}</td>
                                            <td>
                                                <span class="badge bg-{{ $p->status == 'lunas' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($p->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('admin.pembayaran.index', ['santri' => $santri->id]) }}" class="btn btn-sm btn-primary">
                                Lihat Semua Pembayaran
                            </a>
                        @else
                            <p class="text-muted text-center py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data pembayaran
                            </p>
                        @endif
                    </div>
                    
                    <!-- Kehadiran Tab -->
                    <div class="tab-pane fade" id="kehadiran">
                        @if($santri->kehadiran->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($santri->kehadiran->take(10) as $k)
                                        <tr>
                                            <td>{{ $k->tanggal->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $k->status == 'hadir' ? 'success' : ($k->status == 'izin' ? 'info' : ($k->status == 'sakit' ? 'warning' : 'danger')) }}">
                                                    {{ ucfirst($k->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $k->keterangan ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data kehadiran
                            </p>
                        @endif
                    </div>
                    
                    <!-- Nilai Tab -->
                    <div class="tab-pane fade" id="nilai">
                        @if($santri->nilai->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Mata Pelajaran</th>
                                            <th>Semester</th>
                                            <th>Nilai Akhir</th>
                                            <th>Predikat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($santri->nilai as $n)
                                        <tr>
                                            <td>{{ $n->mataPelajaran->nama_mapel }}</td>
                                            <td>{{ $n->semester }} {{ $n->tahun_ajaran }}</td>
                                            <td><strong>{{ number_format($n->nilai_akhir, 2) }}</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $n->predikat == 'A' ? 'success' : ($n->predikat == 'B' ? 'info' : 'warning') }}">
                                                    {{ $n->predikat }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data nilai
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection