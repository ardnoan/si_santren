@extends('layouts.dashboard')

@section('title', 'Nilai Saya')
@section('page-title', 'Nilai Saya')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="mb-3"><i class="bi bi-journal-text me-2"></i>Daftar Nilai</h5>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th>Semester</th>
            <th>Tugas</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>Nilai Akhir</th>
            <th>Predikat</th>
          </tr>
        </thead>
        <tbody>
          @forelse($nilai as $i => $n)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $n->mataPelajaran->nama_mapel }}</td>
            <td>{{ $n->semester }} {{ $n->tahun_ajaran }}</td>
            <td>{{ number_format($n->nilai_tugas, 2) }}</td>
            <td>{{ number_format($n->nilai_uts, 2) }}</td>
            <td>{{ number_format($n->nilai_uas, 2) }}</td>
            <td><strong>{{ number_format($n->nilai_akhir, 2) }}</strong></td>
            <td>
              <span class="badge bg-{{ $n->predikat == 'A' ? 'success' : ($n->predikat == 'B' ? 'info' : 'warning') }}">
                {{ $n->predikat }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">Belum ada data nilai</td>
          </tr>
          @endforelse
        </tbody>
        @if($nilai->count() > 0)
        <tfoot class="table-light">
          <tr>
            <th colspan="6" class="text-end">Rata-rata:</th>
            <th colspan="2">{{ number_format($nilai->avg('nilai_akhir'), 2) }}</th>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>
</div>
@endsection