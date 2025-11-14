{{-- resources/views/pages/santri/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Data Santri')
@section('page-title', 'Data Santri')
@section('page-subtitle', 'Kelola data santri pesantren')

@section('content')
<!-- Main Card -->
<!-- Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-2">
    <h5 class="fw-bold d-flex align-items-center gap-2">
        <i class="bi bi-people-fill text-primary"></i>
        Daftar Santri
    </h5>
</div>

<!-- Search & Filter -->
<div class="row mb-4">
    <div class="col-lg-4">
        <form action="{{ auth()->user()->isAdmin() ? route('admin.santri.index') : route('ustadz.santri.index') }}" method="GET">
            <div class="input-group shadow-sm">
                <span class="input-group-text border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                    class="form-control border-start-0 ps-0"
                    name="search"
                    placeholder=" Searching..."
                    value="{{ request('search') }}"
                    id="search">
                <button class="btn btn-primary" type="submit">
                    Search
                </button>
            </div>
        </form>
    </div>
    <div class="col-lg-2 offset-lg-6 d-flex justify-content-end align-items-center gap-2">
        <button class="btn btn-outline-secondary shadow-sm" onclick="resetFilter()">
            <i class="bi bi-arrow-clockwise"></i>
        </button>

        @admin
        <a href="{{ route('admin.santri.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i>
        </a>
        @endadmin
    </div>

</div>

<!-- Table -->
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>NIS</th>
                <th>Foto</th>
                <th>Nama Lengkap</th>
                <th>Jenis Kelamin</th>
                <th>Kelas</th>
                <th>Status</th;>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($santri as $index => $s)
            <tr>
                <td>{{ $s->nomor_induk }}</td>
                <td>
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
                    <div class="fw-semibold">{{ $s->nama_lengkap }}</div>
                    @if($s->nama_panggilan)
                    <small class="text-muted">({{ $s->nama_panggilan }})</small>
                    @endif
                </td>
                <td>{{ $s->jenis_kelamin_label }}</td>
                <td>
                    @if($s->kelas)
                    {{ $s->kelas->nama_kelas }}
                    @else
                    <span class="badge bg-secondary">
                        Belum ada kelas
                    </span>
                    @endif
                </td>
                <td>{{ ucfirst($s->status) }}</td>
                <td class="text-center">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.santri.show', $s->id) : route('ustadz.santri.show', $s->id) }}"
                        class="btn btn-sm btn-info"
                        data-bs-toggle="tooltip"
                        title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                    </a>

                    @admin
                    <a href="{{ route('admin.santri.edit', $s->id) }}"
                        class="btn btn-sm btn-warning"
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
                            class="btn btn-sm btn-success"
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
                            class="btn btn-sm btn-danger"
                            data-bs-toggle="tooltip"
                            title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endadmin
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
    {{ $santri->links('components.pagination') }}
</div>

@endsection

@section('scripts')
<script>
    function resetFilter() {
        document.getElementById('search').value = '';
        window.location.href = "{{ auth()->user()->isAdmin() ? route('admin.santri.index') : route('ustadz.santri.index') }}";
    }
</script>
@endsection