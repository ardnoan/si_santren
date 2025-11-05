
<!-- ============================================ -->
<!-- FILE 3: resources/views/admin/nilai/show.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Detail Nilai Santri')
@section('page-title', 'Detail Nilai')
@section('page-subtitle', $santri->nama_lengkap)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h5>{{ $santri->nama_lengkap }}</h5>
                        <p class="text-muted mb-0">NIS: {{ $santri->nomor_induk }}</p>
                    </div>
                    <a href="{{ route('admin.nilai.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
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
                            @foreach($nilai as $n)
                            <tr>
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
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="5" class="text-end">Rata-rata:</th>
                                <th colspan="2">{{ number_format($nilai->avg('nilai_akhir'), 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection