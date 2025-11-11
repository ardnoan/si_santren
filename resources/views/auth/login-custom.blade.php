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
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
            --border-radius: 32px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--dark-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 1rem;
        }
        
        .login-container {
            max-width: 1000px;
            width: 100%;
        }
        
        .login-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Left Side - Branding */
        .login-left {
            background: var(--dark-gradient);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(30px, 30px); }
        }
        
        .brand-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
            position: relative;
            z-index: 1;
        }
        
        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .brand-subtitle {
            opacity: 0.9;
            font-size: 0.95rem;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }
        
        .features {
            width: 100%;
            max-width: 300px;
            text-align: left;
            position: relative;
            z-index: 1;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            transition: var(--transition);
        }
        
        .feature-item i {
            font-size: 1.5rem;
            color: #4ade80;
        }
        
        /* Right Side - Form */
        .login-right {
            padding: 3rem;
        }
        
        .login-header {
            margin-bottom: 2rem;
        }
        
        .login-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: #64748b;
            font-size: 0.95rem;
        }
        
        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .input-group {
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        
        .input-group-text {
            background: #f8fafc;
            border: none;
            border-right: none;
            color: #64748b;
            padding: 0.75rem 1rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            /* border: 2px solid #e2e8f0; */
            border-left: none;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            border: 2px solid gray;
            box-shadow: none;
        }
        
        .form-check {
            padding-left: 1.75rem;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        /* Button */
        .btn-login {
            background: var(--dark-gradient);
            border: none;
            padding: 0.875rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        /* Divider */
        .divider {
            position: relative;
            text-align: center;
            margin: 2rem 0;
        }
        
        .divider::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        /* Demo Account Alert */
        .alert-info {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            color: #0c4a6e;
        }
        
        .alert-info strong {
            color: #0369a1;
        }
        
        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: white;
            font-size: 0.875rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .login-footer a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }
        
        /* Alert Animations */
        .alert {
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .login-left {
                padding: 2rem;
            }
            
            .brand-icon {
                font-size: 3rem;
            }
            
            .brand-title {
                font-size: 1.5rem;
            }
            
            .features {
                display: none;
            }
            
            .login-right {
                padding: 2rem;
            }
            
            .login-header h3 {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 0;
            }
            
            .login-card {
                border-radius: 0;
            }
            
            .login-left {
                padding: 1.5rem;
            }
            
            .login-right {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="row g-0">
                <!-- Left Side - Branding -->
                <div class="col-lg-5 login-left">
                    <i class="bi bi-book brand-icon"></i>
                    <h2 class="brand-title">SI SANTREN</h2>
                    <p class="brand-subtitle">Sistem Informasi Manajemen Pesantren Modern</p>
                    
                    <div class="features">
                        <div class="feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Manajemen Data Santri</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Pembayaran Terintegrasi</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Monitoring Kehadiran</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Laporan Real-time</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="col-lg-7 login-right">
                    <div class="login-header">
                        <h3>Selamat Datang!</h3>
                    </div>
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div>
                                    <strong>Login Gagal!</strong>
                                    <div>{{ $errors->first() }}</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <div>{{ session('status') }}</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <!-- Username/Email -->
                        <div class="mb-3">
                            <label class="form-label">Username atau Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       name="username" 
                                       placeholder="Masukkan username atau email"
                                       value="{{ old('username') }}"
                                       required 
                                       autofocus>
                            </div>
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       placeholder="Masukkan password"
                                       required>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-login w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login
                        </button>
                    </form>
                    
                    <div class="divider">
                        <span>Demo Akun</span>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <small>
                            <strong>Admin:</strong> admin / admin123<br>
                            <strong>Ustadz:</strong> ustadz1 / ustadz123<br>
                            <strong>Santri:</strong> santri1 / santri123
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="login-footer">
            <small>
                Copyright ©2025 SiSantren</a>
            </small>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>