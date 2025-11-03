<!-- ============================================ -->
<!-- FILE 1: resources/views/admin/nilai/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Nilai')
@section('page-title', 'Data Nilai Akademik')
@section('page-subtitle', 'Kelola nilai santri')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-journal-text me-2"></i>Daftar Nilai
                    </h5>
                    <a href="{{ route('admin.nilai.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Input Nilai
                    </a>
                </div>
                
                <!-- Filter -->
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <select name="semester" class="form-select">
                                <option value="">Semua Semester</option>
                                <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="Tahun Ajaran" value="{{ request('tahun_ajaran') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
                
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Santri</th>
                                <th width="20%">Mata Pelajaran</th>
                                <th width="10%">Semester</th>
                                <th width="10%">Tahun Ajaran</th>
                                <th width="10%">Nilai Akhir</th>
                                <th width="10%">Predikat</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $query = App\Models\Nilai::with(['santri', 'mataPelajaran']);
                                if(request('semester')) $query->where('semester', request('semester'));
                                if(request('tahun_ajaran')) $query->where('tahun_ajaran', request('tahun_ajaran'));
                                $nilai = $query->orderBy('created_at', 'desc')->paginate(20);
                            @endphp
                            
                            @forelse($nilai as $i => $n)
                            <tr>
                                <td>{{ $nilai->firstItem() + $i }}</td>
                                <td>
                                    <strong>{{ $n->santri->nama_lengkap }}</strong><br>
                                    <small class="text-muted">{{ $n->santri->nomor_induk }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $n->mataPelajaran->nama_mapel }}</span>
                                </td>
                                <td>{{ $n->semester }}</td>
                                <td>{{ $n->tahun_ajaran }}</td>
                                <td><strong>{{ number_format($n->nilai_akhir, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $n->predikat == 'A' ? 'success' : ($n->predikat == 'B' ? 'info' : ($n->predikat == 'C' ? 'warning' : 'danger')) }}">
                                        {{ $n->predikat }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.nilai.santri', $n->santri_id) }}" 
                                           class="btn btn-info"
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.nilai.destroy', $n->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus nilai ini?')">
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
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-muted"></i>
                                    <h5 class="text-muted">Belum ada data nilai</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between mt-3">
                    <div>
                        Menampilkan {{ $nilai->firstItem() ?? 0 }} - {{ $nilai->lastItem() ?? 0 }} dari {{ $nilai->total() }}
                    </div>
                    <div>{{ $nilai->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- ============================================ -->
<!-- FILE 2: resources/views/admin/nilai/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Input Nilai')
@section('page-title', 'Input Nilai')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.nilai.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Santri <span class="text-danger">*</span></label>
                        <select class="form-select @error('santri_id') is-invalid @enderror" 
                                name="santri_id" required>
                            <option value="">Pilih Santri</option>
                            @foreach($santri as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_lengkap }} - {{ $s->nomor_induk }}</option>
                            @endforeach
                        </select>
                        @error('santri_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('mata_pelajaran_id') is-invalid @enderror" 
                                name="mata_pelajaran_id" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select class="form-select @error('semester') is-invalid @enderror" 
                                    name="semester" required>
                                <option value="">Pilih Semester</option>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('tahun_ajaran') is-invalid @enderror" 
                                   name="tahun_ajaran"
                                   placeholder="Contoh: 2024/2025"
                                   value="{{ old('tahun_ajaran', '2024/2025') }}"
                                   required>
                            @error('tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nilai Tugas (30%)</label>
                            <input type="number" 
                                   class="form-control @error('nilai_tugas') is-invalid @enderror" 
                                   name="nilai_tugas"
                                   min="0" max="100"
                                   step="0.01"
                                   placeholder="0-100"
                                   id="nilaiTugas">
                            @error('nilai_tugas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nilai UTS (30%)</label>
                            <input type="number" 
                                   class="form-control @error('nilai_uts') is-invalid @enderror" 
                                   name="nilai_uts"
                                   min="0" max="100"
                                   step="0.01"
                                   placeholder="0-100"
                                   id="nilaiUTS">
                            @error('nilai_uts')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nilai UAS (40%)</label>
                            <input type="number" 
                                   class="form-control @error('nilai_uas') is-invalid @enderror" 
                                   name="nilai_uas"
                                   min="0" max="100"
                                   step="0.01"
                                   placeholder="0-100"
                                   id="nilaiUAS">
                            @error('nilai_uas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Preview Nilai Akhir -->
                    <div class="alert alert-info" id="previewNilai" style="display:none;">
                        <h6>Preview Nilai Akhir:</h6>
                        <h3 class="mb-0" id="nilaiAkhirText">0</h3>
                        <small>Predikat: <span id="predikatText" class="badge">-</span></small>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.nilai.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function hitungNilaiAkhir() {
    const tugas = parseFloat(document.getElementById('nilaiTugas').value) || 0;
    const uts = parseFloat(document.getElementById('nilaiUTS').value) || 0;
    const uas = parseFloat(document.getElementById('nilaiUAS').value) || 0;
    
    const nilaiAkhir = (tugas * 0.3) + (uts * 0.3) + (uas * 0.4);
    
    let predikat = '';
    let badgeClass = '';
    
    if (nilaiAkhir >= 90) {
        predikat = 'A';
        badgeClass = 'bg-success';
    } else if (nilaiAkhir >= 80) {
        predikat = 'B';
        badgeClass = 'bg-info';
    } else if (nilaiAkhir >= 70) {
        predikat = 'C';
        badgeClass = 'bg-warning';
    } else if (nilaiAkhir >= 60) {
        predikat = 'D';
        badgeClass = 'bg-danger';
    } else {
        predikat = 'E';
        badgeClass = 'bg-dark';
    }
    
    if (tugas > 0 || uts > 0 || uas > 0) {
        document.getElementById('previewNilai').style.display = 'block';
        document.getElementById('nilaiAkhirText').textContent = nilaiAkhir.toFixed(2);
        document.getElementById('predikatText').textContent = predikat;
        document.getElementById('predikatText').className = 'badge ' + badgeClass;
    }
}

document.getElementById('nilaiTugas').addEventListener('input', hitungNilaiAkhir);
document.getElementById('nilaiUTS').addEventListener('input', hitungNilaiAkhir);
document.getElementById('nilaiUAS').addEventListener('input', hitungNilaiAkhir);
</script>
@endsection

<!-- ============================================ -->
<!-- FILE 3: resources/views/admin/nilai/show.blade.php -->
@extends('layouts.admin')

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