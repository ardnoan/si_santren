@extends('layouts.dashboard')

@section('title', 'Data Santri')
@section('page-title', 'Data Santri')
@section('page-subtitle', 'Kelola data santri pesantren')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="bi bi-people-fill me-2"></i>Daftar Santri
                        </h5>
                    </div>

                    {{-- ACTION BUTTONS - ADMIN ONLY --}}
                    @admin
                    <a href="{{ route('admin.santri.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Santri
                    </a>
                    @endadmin
                </div>

                <!-- Search & Filter -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <form action="{{ auth()->user()->isAdmin() ? route('admin.santri.index') : route('ustadz.santri.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text"
                                    class="form-control"
                                    name="search"
                                    placeholder="Cari nama atau NIS..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="cuti">Cuti</option>
                            <option value="lulus">Lulus</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterKelas">
                            <option value="">Semua Kelas</option>
                            @foreach(\App\Models\Kelas::all() as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Stats -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="alert alert-info mb-0">
                            <strong><i class="bi bi-people"></i> Total Santri:</strong> {{ $santri->total() }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-success mb-0">
                            <strong><i class="bi bi-check-circle"></i> Aktif:</strong>
                            {{ \App\Models\Santri::aktif()->count() }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-warning mb-0">
                            <strong><i class="bi bi-gender-male"></i> Laki-laki:</strong>
                            {{ \App\Models\Santri::aktif()->byGender('L')->count() }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-danger mb-0">
                            <strong><i class="bi bi-gender-female"></i> Perempuan:</strong>
                            {{ \App\Models\Santri::aktif()->byGender('P')->count() }}
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">NIS</th>
                                <th width="10%">Foto</th>
                                <th width="20%">Nama Lengkap</th>
                                <th width="10%">JK</th>
                                <th width="15%">Kelas</th>
                                <th width="10%">Status</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($santri as $index => $s)
                            <tr>
                                <td>{{ $santri->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $s->nomor_induk }}</strong>
                                </td>
                                <td>
                                    @if($s->foto)
                                    <img src="{{ asset('storage/' . $s->foto) }}"
                                        alt="{{ $s->nama_lengkap }}"
                                        class="rounded-circle"
                                        width="50" height="50"
                                        style="object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-person fs-4"></i>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $s->nama_lengkap }}</strong><br>
                                    @if($s->nama_panggilan)
                                    <small class="text-muted">({{ $s->nama_panggilan }})</small>
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $s->tempat_lahir }}, {{ $s->tanggal_lahir->format('d M Y') }}
                                        ({{ $s->umur }} tahun)
                                    </small>
                                </td>
                                <td>
                                    <span class="badge {{ $s->jenis_kelamin == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                        {{ $s->jenis_kelamin_label }}
                                    </span>
                                </td>
                                <td>
                                    @if($s->kelas)
                                    <span class="badge bg-info">
                                        <i class="bi bi-building"></i> {{ $s->kelas->nama_kelas }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-dash"></i> Belum ada kelas
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $s->status_badge }}">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        {{-- VIEW BUTTON - ALL ROLES --}}
                                        <a href="{{ auth()->user()->isAdmin() ? route('admin.santri.show', $s->id) : route('ustadz.santri.show', $s->id) }}"
                                            class="btn btn-info"
                                            title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- EDIT/DELETE BUTTONS - ADMIN ONLY --}}
                                        @admin
                                        <a href="{{ route('admin.santri.edit', $s->id) }}"
                                            class="btn btn-warning"
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
                                                title="Luluskan">
                                                <i class="bi bi-mortarboard"></i>
                                            </button>
                                        </form>
                                        @endif

                                        <form action="{{ route('admin.santri.destroy', $s->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin hapus data santri ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger"
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
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-muted"></i>
                                    <h5 class="text-muted">Belum ada data santri</h5>
                                    @admin
                                    <a href="{{ route('admin.santri.create') }}" class="btn btn-primary mt-3">
                                        <i class="bi bi-plus-circle me-2"></i>Tambah Santri Pertama
                                    </a>
                                    @endadmin
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Component -->
                @include('components.pagination', ['paginator' => $santri])
            </div>
        </div>
    </div>
</div>

{{-- QUICK ACTIONS - ADMIN ONLY --}}
@admin
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-lightning-fill me-2"></i>Aksi Cepat</h6>
                <div class="row g-2">
                    <div class="col-md-3">
                        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-cash"></i> Lihat Pembayaran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-calendar-check"></i> Lihat Kehadiran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.nilai.index') }}" class="btn btn-outline-warning w-100">
                            <i class="bi bi-journal-text"></i> Lihat Nilai
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-secondary w-100" onclick="exportData()">
                            <i class="bi bi-download"></i> Export Excel
                        </button>
                    </div>
                </div>
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

    function exportData() {
        alert('Fitur export akan segera tersedia!');
    }
</script>
@endsection