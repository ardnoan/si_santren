@extends('layouts.admin')

@section('title', 'Input Pembayaran')
@section('page-title', 'Input Pembayaran')
@section('page-subtitle', 'Form input pembayaran santri')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Santri <span class="text-danger">*</span></label>
                        <select class="form-select @error('santri_id') is-invalid @enderror" 
                                name="santri_id" 
                                id="santriSelect"
                                required>
                            <option value="">Pilih Santri</option>
                            @foreach(App\Models\Santri::aktif()->with('kelas')->orderBy('nama_lengkap')->get() as $s)
                                <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_lengkap }} - {{ $s->nomor_induk }}
                                    @if($s->kelas) ({{ $s->kelas->nama_kelas }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('santri_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_pembayaran') is-invalid @enderror" 
                                    name="jenis_pembayaran"
                                    id="jenisPembayaran"
                                    required>
                                <option value="">Pilih Jenis</option>
                                <option value="spp" {{ old('jenis_pembayaran') == 'spp' ? 'selected' : '' }}>SPP Bulanan</option>
                                <option value="pendaftaran" {{ old('jenis_pembayaran') == 'pendaftaran' ? 'selected' : '' }}>Biaya Pendaftaran</option>
                                <option value="seragam" {{ old('jenis_pembayaran') == 'seragam' ? 'selected' : '' }}>Seragam</option>
                                <option value="lainnya" {{ old('jenis_pembayaran') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3" id="bulanBayarDiv" style="display:none;">
                            <label class="form-label">Bulan Bayar</label>
                            <input type="month" 
                                   class="form-control @error('bulan_bayar') is-invalid @enderror" 
                                   name="bulan_bayar"
                                   value="{{ old('bulan_bayar', date('Y-m')) }}">
                            @error('bulan_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Pembayaran (Rp) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('jumlah') is-invalid @enderror" 
                                   name="jumlah"
                                   id="jumlahInput"
                                   placeholder="Contoh: 500000"
                                   value="{{ old('jumlah') }}"
                                   min="0"
                                   required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Masukkan nominal tanpa titik/koma</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                                   name="tanggal_bayar"
                                   value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                                   required>
                            @error('tanggal_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('metode_pembayaran') is-invalid @enderror" 
                                    name="metode_pembayaran"
                                    id="metodePembayaran"
                                    required>
                                <option value="">Pilih Metode</option>
                                <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                            @error('metode_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    name="status"
                                    required>
                                <option value="lunas" {{ old('status', 'lunas') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3" id="buktiTransferDiv" style="display:none;">
                        <label class="form-label">Bukti Transfer</label>
                        <input type="file" 
                               class="form-control @error('bukti_transfer') is-invalid @enderror" 
                               name="bukti_transfer"
                               accept="image/*">
                        @error('bukti_transfer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  name="keterangan" 
                                  rows="3"
                                  placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Preview Total -->
                    <div class="alert alert-info" id="previewTotal" style="display:none;">
                        <strong>Total yang harus dibayar:</strong>
                        <h3 class="mb-0 mt-2" id="totalText">Rp 0</h3>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Simpan Pembayaran
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
// Show bulan bayar field for SPP
document.getElementById('jenisPembayaran').addEventListener('change', function() {
    const bulanDiv = document.getElementById('bulanBayarDiv');
    bulanDiv.style.display = this.value === 'spp' ? 'block' : 'none';
});

// Show bukti transfer field for non-cash payments
document.getElementById('metodePembayaran').addEventListener('change', function() {
    const buktiDiv = document.getElementById('buktiTransferDiv');
    buktiDiv.style.display = (this.value === 'transfer' || this.value === 'qris') ? 'block' : 'none';
});

// Preview total
document.getElementById('jumlahInput').addEventListener('input', function() {
    const preview = document.getElementById('previewTotal');
    const totalText = document.getElementById('totalText');
    
    if (this.value > 0) {
        const formatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(this.value);
        
        totalText.textContent = formatted;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});

// Trigger on page load if old value exists
if (document.getElementById('jenisPembayaran').value === 'spp') {
    document.getElementById('bulanBayarDiv').style.display = 'block';
}

const metode = document.getElementById('metodePembayaran').value;
if (metode === 'transfer' || metode === 'qris') {
    document.getElementById('buktiTransferDiv').style.display = 'block';
}
</script>
@endsection