{{-- resources/views/pages/pembayaran/form.blade.php --}}
@extends('layouts.dashboard')

@php
$isEdit = isset($pembayaran);
$title = $isEdit ? 'Edit Pembayaran' : 'Input Pembayaran';
$route = $isEdit ? route('admin.pembayaran.update', $pembayaran->id) : route('admin.pembayaran.store');
@endphp

@section('title', $title)
@section('page-title', $title)
@section('page-subtitle', $isEdit ? 'Edit data pembayaran' : 'Form input pembayaran santri')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <!-- Header Info -->
        <div class="alert alert-success border-0 d-flex align-items-center gap-3 mb-4">
          <i class="bi bi-cash-coin fs-3"></i>
          <div>
            <strong class="d-block mb-1">Input Transaksi Pembayaran</strong>
            <small>Catat pembayaran santri dengan lengkap dan teliti</small>
          </div>
        </div>

        <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
          @csrf
          @if($isEdit)
          @method('PUT')
          @endif

          <div class="row">
            <!-- Left Column -->
            <div class="col-lg-6">
              <!-- Data Santri Section -->
              <div class="bg-light rounded-3 p-4 mb-4 border">
                <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                  <i class="bi bi-person-badge text-primary fs-5"></i>
                  <span>Data Santri</span>
                </h6>

                @if($isEdit)
                <div class="mb-0">
                  <label class="form-label fw-semibold">Santri</label>
                  <input type="text" class="form-control bg-white" value="{{ $pembayaran->santri->nama_lengkap }}" disabled>
                  <small class="text-muted d-block mt-1">
                    <i class="bi bi-info-circle me-1"></i>NIS: {{ $pembayaran->santri->nomor_induk }}
                  </small>
                </div>
                @else
                <div class="mb-0">
                  <label class="form-label fw-semibold">
                    Santri <span class="text-danger">*</span>
                  </label>
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
                @endif
              </div>

              <!-- Jenis Pembayaran Section -->
              <div class="bg-light rounded-3 p-4 mb-4 border">
                <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                  <i class="bi bi-tag text-info fs-5"></i>
                  <span>Jenis Pembayaran</span>
                </h6>

                <div class="mb-3">
                  <label class="form-label fw-semibold">
                    Jenis Pembayaran <span class="text-danger">*</span>
                  </label>
                  <div class="row g-2">
                    <div class="col-6">
                      <input type="radio"
                        class="btn-check"
                        name="jenis_pembayaran"
                        id="jenisSPP"
                        value="spp"
                        {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'spp' ? 'checked' : '' }}
                        required>
                      <label class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-3" for="jenisSPP">
                        <i class="bi bi-calendar-month fs-3 mb-2"></i>
                        <span>SPP Bulanan</span>
                      </label>
                    </div>
                    <div class="col-6">
                      <input type="radio"
                        class="btn-check"
                        name="jenis_pembayaran"
                        id="jenisPendaftaran"
                        value="pendaftaran"
                        {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'pendaftaran' ? 'checked' : '' }}>
                      <label class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3" for="jenisPendaftaran">
                        <i class="bi bi-person-plus fs-3 mb-2"></i>
                        <span>Pendaftaran</span>
                      </label>
                    </div>
                    <div class="col-6">
                      <input type="radio"
                        class="btn-check"
                        name="jenis_pembayaran"
                        id="jenisSeragam"
                        value="seragam"
                        {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'seragam' ? 'checked' : '' }}>
                      <label class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3" for="jenisSeragam">
                        <i class="bi bi-bag fs-3 mb-2"></i>
                        <span>Seragam</span>
                      </label>
                    </div>
                    <div class="col-6">
                      <input type="radio"
                        class="btn-check"
                        name="jenis_pembayaran"
                        id="jenisLainnya"
                        value="lainnya"
                        {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'lainnya' ? 'checked' : '' }}>
                      <label class="btn btn-outline-secondary w-100 d-flex flex-column align-items-center py-3" for="jenisLainnya">
                        <i class="bi bi-three-dots fs-3 mb-2"></i>
                        <span>Lainnya</span>
                      </label>
                    </div>
                  </div>
                  @error('jenis_pembayaran')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-0 d-none" id="bulanBayarDiv">
                  <label class="form-label fw-semibold">Bulan Bayar</label>
                  <input type="month"
                    class="form-control @error('bulan_bayar') is-invalid @enderror"
                    name="bulan_bayar"
                    value="{{ old('bulan_bayar', $pembayaran->bulan_bayar ?? date('Y-m')) }}">
                  @error('bulan_bayar')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="text-muted d-block mt-1">
                    <i class="bi bi-info-circle me-1"></i>Pilih bulan yang dibayarkan
                  </small>
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-6">
              <!-- Nominal & Tanggal Section -->
              <div class="bg-light rounded-3 p-4 mb-4 border">
                <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                  <i class="bi bi-cash-stack text-success fs-5"></i>
                  <span>Detail Pembayaran</span>
                </h6>

                <div class="mb-3">
                  <label class="form-label fw-semibold">
                    Jumlah Pembayaran (Rp) <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-light border-end-0">
                      <strong class="text-success">Rp</strong>
                    </span>
                    <input type="number"
                      class="form-control border-start-0 ps-0 @error('jumlah') is-invalid @enderror"
                      name="jumlah"
                      id="jumlahInput"
                      placeholder="500000"
                      value="{{ old('jumlah', $pembayaran->jumlah ?? '') }}"
                      min="0"
                      required>
                  </div>
                  @error('jumlah')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                  <small class="text-muted d-block mt-1">
                    <i class="bi bi-lightbulb me-1"></i>Masukkan nominal tanpa titik/koma
                  </small>
                </div>

                <div class="mb-0">
                  <label class="form-label fw-semibold">
                    Tanggal Bayar <span class="text-danger">*</span>
                  </label>
                  <div class="input-group shadow-sm">
                    <span class="input-group-text bg-light border-end-0">
                      <i class="bi bi-calendar3 text-muted"></i>
                    </span>
                    <input type="date"
                      class="form-control border-start-0 ps-0 @error('tanggal_bayar') is-invalid @enderror"
                      name="tanggal_bayar"
                      value="{{ old('tanggal_bayar', $isEdit ? $pembayaran->tanggal_bayar->format('Y-m-d') : date('Y-m-d')) }}"
                      max="{{ date('Y-m-d') }}"
                      required>
                  </div>
                  @error('tanggal_bayar')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Metode & Status Section -->
              <div class="bg-light rounded-3 p-4 mb-4 border">
                <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                  <i class="bi bi-credit-card text-warning fs-5"></i>
                  <span>Metode & Status</span>
                </h6>

                <div class="mb-3">
                  <label class="form-label fw-semibold">
                    Metode Pembayaran <span class="text-danger">*</span>
                  </label>
                  <div class="row g-2">
                    <div class="col-4">
                      <input type="radio"
                        class="btn-check"
                        name="metode_pembayaran"
                        id="metodeTunai"
                        value="tunai"
                        {{ old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '') == 'tunai' ? 'checked' : '' }}
                        required>
                      <label class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-2" for="metodeTunai">
                        <i class="bi bi-cash fs-4 mb-1"></i>
                        <small>Tunai</small>
                      </label>
                    </div>
                    <div class="col-4">
                      <input type="radio"
                        class="btn-check"
                        name="metode_pembayaran"
                        id="metodeTransfer"
                        value="transfer"
                        {{ old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '') == 'transfer' ? 'checked' : '' }}>
                      <label class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-2" for="metodeTransfer">
                        <i class="bi bi-bank fs-4 mb-1"></i>
                        <small>Transfer</small>
                      </label>
                    </div>
                    <div class="col-4">
                      <input type="radio"
                        class="btn-check"
                        name="metode_pembayaran"
                        id="metodeQRIS"
                        value="qris"
                        {{ old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '') == 'qris' ? 'checked' : '' }}>
                      <label class="btn btn-outline-info w-100 d-flex flex-column align-items-center py-2" for="metodeQRIS">
                        <i class="bi bi-qr-code fs-4 mb-1"></i>
                        <small>QRIS</small>
                      </label>
                    </div>
                  </div>
                  @error('metode_pembayaran')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-0">
                  <label class="form-label fw-semibold">
                    Status <span class="text-danger">*</span>
                  </label>
                  <select class="form-select @error('status') is-invalid @enderror"
                    name="status"
                    required>
                    <option value="lunas" {{ old('status', $pembayaran->status ?? 'lunas') == 'lunas' ? 'selected' : '' }}>
                      ✅ Lunas
                    </option>
                    <option value="pending" {{ old('status', $pembayaran->status ?? '') == 'pending' ? 'selected' : '' }}>
                      ⏳ Pending
                    </option>
                    @if($isEdit)
                    <option value="dibatalkan" {{ $pembayaran->status == 'dibatalkan' ? 'selected' : '' }}>
                      ❌ Dibatalkan
                    </option>
                    @endif
                  </select>
                  @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          <!-- Bukti Transfer Section -->
          <div class="bg-light rounded-3 p-4 mb-4 border d-none" id="buktiTransferDiv">
            <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
              <i class="bi bi-image text-info fs-5"></i>
              <span>Bukti Transfer</span>
            </h6>

            @if($isEdit && $pembayaran->bukti_transfer)
            <div class="text-center mb-3">
              <div class="position-relative d-inline-block">
                <img src="{{ asset('storage/' . $pembayaran->bukti_transfer) }}"
                  class="rounded-3 shadow-sm border border-3"
                  style="max-width: 300px; max-height: 300px; object-fit: cover;">
                <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-success">
                  <i class="bi bi-check"></i>
                </span>
              </div>
            </div>
            @endif

            <div class="mb-0">
              <input type="file"
                class="form-control @error('bukti_transfer') is-invalid @enderror"
                name="bukti_transfer"
                accept="image/*">
              @error('bukti_transfer')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="text-muted d-block mt-1">
                <i class="bi bi-info-circle me-1"></i>
                Format: JPG, PNG (Max: 2MB)
                @if($isEdit). Kosongkan jika tidak ingin mengubah.@endif
              </small>
            </div>
          </div>

          <!-- Keterangan Section -->
          <div class="bg-light rounded-3 p-4 mb-4 border">
            <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
              <i class="bi bi-pencil text-secondary fs-5"></i>
              <span>Keterangan</span>
            </h6>

            <textarea class="form-control @error('keterangan') is-invalid @enderror"
              name="keterangan"
              rows="3"
              placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $pembayaran->keterangan ?? '') }}</textarea>
            @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Preview Total -->
          @if(!$isEdit)
          <div class="alert alert-primary border-0 shadow-sm d-none" id="previewTotal">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="fw-bold mb-1">Total yang harus dibayar</h6>
                <small class="text-muted">Nominal yang diinput</small>
              </div>
              <div class="display-5 fw-bold" id="totalText">Rp 0</div>
            </div>
          </div>
          @endif

          <!-- Action Buttons -->
          <div class="bg-light rounded-3 p-4 border">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
              <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary w-100 w-md-auto">
                <i class="bi bi-arrow-left me-2"></i>Kembali
              </a>
              <button type="submit" class="btn btn-success btn-lg w-100 w-md-auto shadow-sm">
                <i class="bi bi-check-circle me-2"></i>
                {{ $isEdit ? 'Update Pembayaran' : 'Simpan Pembayaran' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Show bulan bayar for SPP
  const jenisPembayaran = document.querySelectorAll('input[name="jenis_pembayaran"]');
  const bulanDiv = document.getElementById('bulanBayarDiv');

  jenisPembayaran.forEach(radio => {
    radio.addEventListener('change', function() {
      bulanDiv.classList.toggle('d-none', this.value !== 'spp');
    });
  });

  // Check on load
  const checkedJenis = document.querySelector('input[name="jenis_pembayaran"]:checked');
  if (checkedJenis && checkedJenis.value === 'spp') {
    bulanDiv.classList.remove('d-none');
  }

  // Show bukti transfer for non-cash
  const metodePembayaran = document.querySelectorAll('input[name="metode_pembayaran"]');
  const buktiDiv = document.getElementById('buktiTransferDiv');

  metodePembayaran.forEach(radio => {
    radio.addEventListener('change', function() {
      buktiDiv.classList.toggle('d-none', this.value === 'tunai');
    });
  });

  // Check on load
  const checkedMetode = document.querySelector('input[name="metode_pembayaran"]:checked');
  if (checkedMetode && checkedMetode.value !== 'tunai') {
    buktiDiv.classList.remove('d-none');
  }

  if (!$isEdit) {
    // Preview total
    const jumlahInput = document.getElementById('jumlahInput');
    const preview = document.getElementById('previewTotal');
    const totalText = document.getElementById('totalText');

    jumlahInput.addEventListener('input', function() {
      const value = parseFloat(this.value) || 0;

      if (value > 0) {
        const formatted = new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR',
          minimumFractionDigits: 0
        }).format(value);

        totalText.textContent = formatted;
        preview.classList.remove('d-none');
      } else {
        preview.classList.add('d-none');
      }
    });
  }
</script>
@endsection