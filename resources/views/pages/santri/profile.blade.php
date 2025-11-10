@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Santri')
@section('page-subtitle', $santri->nama_lengkap)

@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body text-center">
        @if($santri->foto)
        <img src="{{ asset('storage/' . $santri->foto) }}"
          class="rounded-circle mb-3"
          style="width: 150px; height: 150px; object-fit: cover;">
        @else
        <div class="rounded-circle bg-secondary mx-auto mb-3 d-flex align-items-center justify-content-center"
          style="width: 150px; height: 150px;">
          <i class="bi bi-person fs-1 text-white"></i>
        </div>
        @endif

        <h4>{{ $santri->nama_lengkap }}</h4>
        <p class="text-muted">{{ $santri->nomor_induk }}</p>
        <span class="badge bg-{{ $santri->status_badge }}">{{ ucfirst($santri->status) }}</span>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <h5 class="mb-3"><i class="bi bi-person-badge me-2"></i>Data Pribadi</h5>
        <table class="table table-borderless">
          <tr>
            <th width="30%">Nama Lengkap</th>
            <td>{{ $santri->nama_lengkap }}</td>
          </tr>
          <tr>
            <th>Jenis Kelamin</th>
            <td>{{ $santri->jenis_kelamin_label }}</td>
          </tr>
          <tr>
            <th>Tempat, Tgl Lahir</th>
            <td>{{ $santri->tempat_lahir }}, {{ $santri->tanggal_lahir->format('d F Y') }}</td>
          </tr>
          <tr>
            <th>Umur</th>
            <td>{{ $santri->umur }} tahun</td>
          </tr>
          <tr>
            <th>Alamat</th>
            <td>{{ $santri->alamat }}</td>
          </tr>
          <tr>
            <th>Kelas</th>
            <td>
              @if($santri->kelas)
              <span class="badge bg-info">{{ $santri->kelas->nama_kelas }}</span>
              @else
              <span class="badge bg-secondary">Belum ada kelas</span>
              @endif
            </td>
          </tr>
          <tr>
            <th>Nama Wali</th>
            <td>{{ $santri->nama_wali }}</td>
          </tr>
          <tr>
            <th>No. Telp Wali</th>
            <td>{{ $santri->no_telp_wali }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection