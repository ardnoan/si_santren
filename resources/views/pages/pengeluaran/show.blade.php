{{-- resources/views/pages/pengeluaran/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Detail Pengeluaran')
@section('page-title', 'Detail Pengeluaran')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-4">Informasi Pengeluaran</h6>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Tanggal</small>
            <strong>{{ $pengeluaran->tanggal->format('d F Y') }}</strong>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Kategori</small>
            <span class="badge bg-secondary px-3 py-2">{{ ucfirst($pengeluaran->kategori) }}</span>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Jumlah</small>
            <strong class="text-danger fs-5">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</strong>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Status</small>
            @if($pengeluaran->status == 'pending')
            <span class="badge bg-warning px-3 py-2">Pending Approval</span>
            @elseif($pengeluaran->status == 'approved')
            <span class="badge bg-success px-3 py-2">Approved</span>
            @else
            <span class="badge bg-danger px-3 py-2">Rejected</span>
            @endif
          </div>
          
          {{-- ✅ PERBAIKAN: Tambahkan pengecekan null --}}
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Diajukan Oleh</small>
            <strong>
              @if($pengeluaran->bendahara)
                {{ $pengeluaran->bendahara->username }}
              @else
                <span class="text-muted">-</span>
              @endif
            </strong>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Tanggal Pengajuan</small>
            <strong>{{ $pengeluaran->created_at->format('d/m/Y H:i') }}</strong>
          </div>
          
          {{-- ✅ PERBAIKAN: Pengecekan null untuk approver --}}
          @if($pengeluaran->approved_by && $pengeluaran->approver)
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">
              Di-{{ $pengeluaran->status == 'approved' ? 'approve' : 'reject' }} Oleh
            </small>
            <strong>{{ $pengeluaran->approver->username }}</strong>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-block mb-1">Tanggal Approval</small>
            <strong>{{ $pengeluaran->approved_at->format('d/m/Y H:i') }}</strong>
          </div>
          @endif
          
          <div class="col-12">
            <small class="text-muted d-block mb-1">Keterangan</small>
            <p class="mb-0">{{ $pengeluaran->keterangan ?? '-' }}</p>
          </div>
          
          @if($pengeluaran->bukti)
          <div class="col-12">
            <small class="text-muted d-block mb-2">Bukti/Nota</small>
            <a href="{{ asset('storage/' . $pengeluaran->bukti) }}" target="_blank">
              <img src="{{ asset('storage/' . $pengeluaran->bukti) }}" 
                   class="img-thumbnail shadow-sm" 
                   style="max-width: 400px; cursor: pointer;"
                   alt="Bukti Pengeluaran">
            </a>
          </div>
          @endif
        </div>

        <div class="d-flex justify-content-between">
          <a href="{{ auth()->user()->isBendahara() ? route('bendahara.pengeluaran.index') : route('pemimpin.pengeluaran.index') }}" 
             class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
          </a>
          
          @if(auth()->user()->isBendahara() && $pengeluaran->status == 'pending')
          <div>
            <a href="{{ route('bendahara.pengeluaran.edit', $pengeluaran->id) }}" class="btn btn-warning">
              <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <form action="{{ route('bendahara.pengeluaran.destroy', $pengeluaran->id) }}" 
                  method="POST" 
                  class="d-inline"
                  onsubmit="return confirm('Hapus pengeluaran ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash me-2"></i>Hapus
              </button>
            </form>
          </div>
          @endif
          
          @if(auth()->user()->isPemimpin() && $pengeluaran->status == 'pending')
          <div>
            <form action="{{ route('pemimpin.pengeluaran.approve', $pengeluaran->id) }}" 
                  method="POST" 
                  class="d-inline">
              @csrf
              <button type="submit" 
                      class="btn btn-success" 
                      onclick="return confirm('Approve pengeluaran ini?')">
                <i class="bi bi-check-circle me-2"></i>Approve
              </button>
            </form>
            <form action="{{ route('pemimpin.pengeluaran.reject', $pengeluaran->id) }}" 
                  method="POST" 
                  class="d-inline">
              @csrf
              <button type="submit" 
                      class="btn btn-danger" 
                      onclick="return confirm('Reject pengeluaran ini?')">
                <i class="bi bi-x-circle me-2"></i>Reject
              </button>
            </form>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection