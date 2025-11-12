{{-- resources/views/pages/santri/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Data Santri')
@section('page-title', 'Data Santri')
@section('page-subtitle', 'Kelola data santri pesantren')

@section('content')
<!-- Main Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-primary"></i>
                    Daftar Santri
                </h5>
                <p class="text-muted mb-0 small">Kelola dan monitor data santri</p>
            </div>

            @admin
            <a href="{{ route('admin.santri.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>
                <span>Tambah Santri</span>
            </a>
            @endadmin
        </div>

        <!-- Search & Filter -->
        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <form action="{{ auth()->user()->isAdmin() ? route('admin.santri.index') : route('ustadz.santri.index') }}" method="GET">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 ps-0"
                               name="search"
                               placeholder="Cari nama atau NIS..."
                               value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-lg-2">
                <select class="form-select shadow-sm" id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="cuti">Cuti</option>
                    <option value="lulus">Lulus</option>
                    <option value="keluar">Keluar</option>
                </select>
            </div>
            <div class="col-lg-3">
                <select class="form-select shadow-sm" id="filterKelas">
                    <option value="">Semua Kelas</option>
                    @foreach(\App\Models\Kelas::all() as $kelas)
                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <button class="btn btn-outline-secondary w-100 shadow-sm" onclick="resetFilter()">
                    <i class="bi bi-arrow-clockwise me-2"></i>
                    Reset Filter
                </button>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="alert alert-primary border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
                    <i class="bi bi-people fs-2"></i>
                    <div>
                        <small class="d-block opacity-75">Total Santri</small>
                        <strong class="fs-5">{{ $santri->total() }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-success border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle fs-2"></i>
                    <div>
                        <small class="d-block opacity-75">Aktif</small>
                        <strong class="fs-5">{{ \App\Models\Santri::aktif()->count() }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-info border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
                    <i class="bi bi-gender-male fs-2"></i>
                    <div>
                        <small class="d-block opacity-75">Laki-laki</small>
                        <strong class="fs-5">{{ \App\Models\Santri::aktif()->byGender('L')->count() }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-danger border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
                    <i class="bi bi-gender-female fs-2"></i>
                    <div>
                        <small class="d-block opacity-75">Perempuan</small>
                        <strong class="fs-5">{{ \App\Models\Santri::aktif()->byGender('P')->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 10%;">NIS</th>
                        <th class="text-center" style="width: 8%;">Foto</th>
                        <th style="width: 25%;">Nama Lengkap</th>
                        <th class="text-center" style="width: 8%;">JK</th>
                        <th style="width: 12%;">Kelas</th>
                        <th class="text-center" style="width: 10%;">Status</th>
                        <th class="text-center" style="width: 12%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($santri as $index => $s)
                    <tr>
                        <td class="text-center fw-semibold">{{ $santri->firstItem() + $index }}</td>
                        <td>
                            <code class="text-primary">{{ $s->nomor_induk }}</code>
                        </td>
                        <td class="text-center">
                            @if($s->foto)
                            <img src="{{ asset('storage/' . $s->foto) }}"
                                 alt="{{ $s->nama_lengkap }}"
                                 class="rounded-circle shadow-sm"
                                 width="45" height="45"
                                 style="object-fit: cover;">
                            @else
                            <div class="rounded-circle bg-secondary bg-opacity-25 d-inline-flex align-items-center justify-content-center"
                                 style="width: 45px; height: 45px;">
                                <i class="bi bi-person text-secondary"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $s->nama_lengkap }}</div>
                            @if($s->nama_panggilan)
                            <small class="text-muted">({{ $s->nama_panggilan }})</small>
                            @endif
                            <div class="small text-muted mt-1">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $s->tempat_lahir }}, {{ $s->tanggal_lahir->format('d M Y') }}
                                <span class="badge bg-light text-dark ms-2">{{ $s->umur }} th</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $s->jenis_kelamin == 'L' ? 'bg-primary' : 'bg-danger' }} px-3 py-2">
                                {{ $s->jenis_kelamin_label }}
                            </span>
                        </td>
                        <td>
                            @if($s->kelas)
                            <span class="badge bg-info text-dark px-3 py-2">
                                <i class="bi bi-building me-1"></i>
                                {{ $s->kelas->nama_kelas }}
                            </span>
                            @else
                            <span class="badge bg-secondary px-3 py-2">
                                Belum ada kelas
                            </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $s->status_badge }} px-3 py-2">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm shadow-sm" role="group">
                                <a href="{{ auth()->user()->isAdmin() ? route('admin.santri.show', $s->id) : route('ustadz.santri.show', $s->id) }}"
                                   class="btn btn-info"
                                   data-bs-toggle="tooltip"
                                   title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @admin
                                <a href="{{ route('admin.santri.edit', $s->id) }}"
                                   class="btn btn-warning"
                                   data-bs-toggle="tooltip"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                @if($s->status === 'aktif')
                                <form action="{{ route('admin.santri.graduate', $s->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin meluluskan santri ini?')">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-success"
                                            data-bs-toggle="tooltip"
                                            title="Luluskan">
                                        <i class="bi bi-mortarboard"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.santri.destroy', $s->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger"
                                            data-bs-toggle="tooltip"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endadmin
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                <h5>Belum ada data santri</h5>
                                <p class="mb-3">Data santri akan muncul di sini</p>
                                @admin
                                <a href="{{ route('admin.santri.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Tambah Santri Pertama
                                </a>
                                @endadmin
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @include('components.pagination', ['paginator' => $santri])
    </div>
</div>

{{-- Quick Actions - Admin Only --}}
@admin
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-lightning-fill text-warning"></i>
            Aksi Cepat
        </h6>
        <div class="row g-2">
            <div class="col-md-3">
                <a href="{{ route('admin.pembayaran.index') }}" 
                   class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-cash"></i>
                    <span>Pembayaran</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.kehadiran.index') }}" 
                   class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-calendar-check"></i>
                    <span>Kehadiran</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.nilai.index') }}" 
                   class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-journal-text"></i>
                    <span>Nilai</span>
                </a>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" 
                        onclick="alert('Fitur export akan segera tersedia!')">
                    <i class="bi bi-download"></i>
                    <span>Export</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endadmin
@endsection

@section('scripts')
<script>
    function resetFilter() {
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterKelas').value = '';
        window.location.href = '{{ auth()->user()->isAdmin() ? route("admin.santri.index") : route("ustadz.santri.index") }}';
    }
</script>
@endsection