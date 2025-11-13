{{-- resources/views/auth/login-custom.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI Santren</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS (minimal) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-primary min-vh-100 d-flex align-items-center justify-content-center p-3 p-lg-4 position-relative overflow-hidden animated-bg">

    <div class="container" style="max-width: 1000px; position: relative; z-index: 1;">
        <!-- Login Card -->
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden animate-fade-in">
            <div class="row g-0">

                <!-- Left Side - Branding -->
                <div class="col-lg-5 bg-gradient-dark text-white p-4 p-lg-5 d-flex flex-column justify-content-center position-relative">
                    <!-- Brand Icon -->
                    <div class="text-center mb-4">
                        <i class="bi bi-book fs-1 d-block mb-3 animate-pulse" style="font-size: 4rem !important; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));"></i>
                        <h2 class="fw-bold mb-2 ls-1">SI SANTREN</h2>
                        <p class="mb-0 opacity-90 fs-6">Sistem Informasi Manajemen Pesantren Modern</p>
                    </div>

                    <!-- Features -->
                    <div class="d-none d-lg-block mx-auto" style="max-width: 300px;">
                        <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 transition-all">
                            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                            <span class="fw-medium">Manajemen Data Santri</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 transition-all">
                            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                            <span class="fw-medium">Pembayaran Terintegrasi</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 transition-all">
                            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                            <span class="fw-medium">Monitoring Kehadiran</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 transition-all">
                            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                            <span class="fw-medium">Laporan Real-time</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Form -->
                <div class="col-lg-7 p-4 p-lg-5">
                    <!-- Header -->
                    <div class="mb-4">
                        <h3 class="fw-bold mb-2">Selamat Datang!</h3>
                        <p class="text-muted mb-0">Silakan login untuk melanjutkan</p>
                    </div>

                    <!-- Alerts -->
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <strong class="d-block mb-1">Login Gagal!</strong>
                                <small>{{ $errors->first() }}</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    @endif

                    @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill me-2 mt-1"></i>
                            <div class="flex-grow-1">{{ session('status') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Username -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username atau Email</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="text"
                                    class="form-control border-start-0 ps-0 @error('username') is-invalid @enderror"
                                    name="username"
                                    placeholder="Masukkan username atau email"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus>
                            </div>
                            @error('username')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password"
                                    class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="Masukkan password"
                                    required>
                            </div>
                            @error('password')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login ke Dashboard
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="position-relative my-4">
                        <hr class="text-muted">
                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small fw-medium">
                            Demo Akun
                        </span>
                    </div>

                    <!-- Demo Accounts -->
                    <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-0">
                        <small>
                            <div class="mb-2">
                                <i class="bi bi-shield-fill-check text-danger me-2"></i>
                                <strong>Admin:</strong>
                                <code class="text-danger">admin</code> /
                                <code class="text-danger">admin123</code>
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-person-fill-gear text-success me-2"></i>
                                <strong>Ustadz:</strong>
                                <code class="text-success">ustadz1</code> /
                                <code class="text-success">ustadz123</code>
                            </div>
                            <div>
                                <i class="bi bi-person-fill text-primary me-2"></i>
                                <strong>Santri:</strong>
                                <code class="text-primary">santri1</code> /
                                <code class="text-primary">santri123</code>
                            </div>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-white mt-4">
            <small class="opacity-90">
                Copyright © 2025 <span class="fw-semibold">SiSantren</span> - Sistem Informasi Pesantren Modern
            </small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>