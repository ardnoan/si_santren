{{-- resources/views/pages/kelas/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Detail Kelas')
@section('page-title', 'Detail Kelas')
@section('page-subtitle', $kelas->nama_kelas)

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">{{ $kelas->nama_kelas }}</h4>
                        <p class="text-muted mb-0">
                            <span class="badge bg-primary">Tingkat {{ $kelas->tingkat }}</span>
                            <span class="badge bg-info">{{ $kelas->tahun_ajaran }}</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.kelas.index') : route('ustadz.kelas.index') }}"
                            class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        @admin
                        <a href="{{ route('admin.kelas.edit', $kelas->id) }}"
                            class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        @endadmin
                    </div>
                </div>

                <!-- Informasi Kelas -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nama Kelas</th>
                                <td>{{ $kelas->nama_kelas }}</td>
                            </tr>
                            <tr>
                                <th>Tingkat</th>
                                <td>{{ $kelas->tingkat }}</td>
                            </tr>
                            <tr>
                                <th>Tahun Ajaran</th>
                                <td>{{ $kelas->tahun_ajaran }}</td>
                            </tr>
                            <tr>
                                <th>Kapasitas</th>
                                <td>
                                    {{ $kelas->santris->count() }} / {{ $kelas->kapasitas }} santri
                                    <div class="progress mt-1" style="height: 20px;">
                                        @php
                                        $persentase = $kelas->kapasitas > 0 ? ($kelas->santris->count() / $kelas->kapasitas) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar {{ $persentase >= 100 ? 'bg-danger' : ($persentase >= 80 ? 'bg-warning' : 'bg-success') }}"
                                            style="width: {{ min($persentase, 100) }}%">
                                            {{ number_format($persentase, 0) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Wali Kelas</th>
                                <td>
                                    @if($kelas->waliKelas)
                                    <span class="badge bg-success">
                                        <i class="bi bi-person-check"></i> {{ $kelas->waliKelas->username }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">Belum ditentukan</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle me-2"></i>Statistik Kelas</h6>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Santri:</span>
                                <strong>{{ $kelas->santris->count() }} orang</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Laki-laki:</span>
                                <strong>{{ $kelas->santris->where('jenis_kelamin', 'L')->count() }} orang</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Perempuan:</span>
                                <strong>{{ $kelas->santris->where('jenis_kelamin', 'P')->count() }} orang</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Santri -->
                <h5 class="mb-3">
                    <i class="bi bi-people me-2"></i>Daftar Santri
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">NIS</th>
                                <th width="10%">Foto</th>
                                <th width="30%">Nama Lengkap</th>
                                <th width="10%">JK</th>
                                <th width="15%">Tempat, Tgl Lahir</th>
                                <th width="10%">Status</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelas->santris as $index => $s)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $s->nomor_induk }}</strong></td>
                                <td>
                                    @if($s->foto)
                                    <img src="{{ asset('storage/' . $s->foto) }}"
                                        alt="{{ $s->nama_lengkap }}"
                                        class="rounded-circle"
                                        width="40" height="40"
                                        style="object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $s->nama_lengkap }}</strong><br>
                                    @if($s->nama_panggilan)
                                    <small class="text-muted">({{ $s->nama_panggilan }})</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $s->jenis_kelamin == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                        {{ $s->jenis_kelamin_label }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        {{ $s->tempat_lahir }},<br>
                                        {{ $s->tanggal_lahir->format('d M Y') }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $s->status_badge }}">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ auth()->user()->isAdmin() ? route('admin.santri.show', $s->id) : route('ustadz.santri.show', $s->id) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada santri di kelas ini
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