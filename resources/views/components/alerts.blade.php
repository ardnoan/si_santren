{{-- resources/views/components/alerts.blade.php --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3 animate-slide-in" role="alert">
    <div class="d-flex align-items-start">
        <i class="bi bi-check-circle-fill fs-5 me-2 mt-1"></i>
        <div class="flex-grow-1">
            <strong class="d-block mb-1">Berhasil!</strong>
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3 animate-slide-in" role="alert">
    <div class="d-flex align-items-start">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2 mt-1"></i>
        <div class="flex-grow-1">
            <strong class="d-block mb-1">Gagal!</strong>
            {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-3 animate-slide-in" role="alert">
    <div class="d-flex align-items-start">
        <i class="bi bi-exclamation-circle-fill fs-5 me-2 mt-1"></i>
        <div class="flex-grow-1">
            <strong class="d-block mb-1">Perhatian!</strong>
            {{ session('warning') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-3 animate-slide-in" role="alert">
    <div class="d-flex align-items-start">
        <i class="bi bi-info-circle-fill fs-5 me-2 mt-1"></i>
        <div class="flex-grow-1">
            <strong class="d-block mb-1">Info:</strong>
            {{ session('info') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3 animate-slide-in" role="alert">
    <div class="d-flex align-items-start">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2 mt-1"></i>
        <div class="flex-grow-1">
            <strong class="d-block mb-2">Terjadi kesalahan:</strong>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif