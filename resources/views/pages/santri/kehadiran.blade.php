@extends('layouts.dashboard')

@section('title', 'Kehadiran Saya')
@section('page-title', 'Kehadiran Saya')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="mb-3"><i class="bi bi-calendar-check me-2"></i>Riwayat Kehadiran</h5>

    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kehadiran as $i => $k)
          <tr>
            <td>{{ $kehadiran->firstItem() + $i }}</td>
            <td>{{ $k->tanggal->format('d/m/Y') }}</td>
            <td>
              <span class="badge bg-{{ $k->status == 'hadir' ? 'success' : ($k->status == 'izin' ? 'info' : ($k->status == 'sakit' ? 'warning' : 'danger')) }}">
                {{ ucfirst($k->status) }}
              </span>
            </td>
            <td>{{ $k->jam_masuk ?? '-' }}</td>
            <td>{{ $k->jam_keluar ?? '-' }}</td>
            <td>{{ $k->keterangan ?? '-' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">Belum ada data kehadiran</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $kehadiran->links() }}
  </div>
</div>
@endsection