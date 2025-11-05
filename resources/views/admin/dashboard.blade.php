<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SI Santren</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
        }
        
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid var(--bs-border-color);
        }
        
        [data-bs-theme="light"] .sidebar {
            background-color: #fff;
        }
        
        [data-bs-theme="dark"] .sidebar {
            background-color: var(--bs-dark);
            border-right-color: var(--bs-gray-dark);
        }
        
        .sidebar .brand {
            padding: 1.5rem;
            border-bottom: 1px solid var(--bs-border-color);
        }
        
        .sidebar .nav-link {
            padding: 0.75rem 1.25rem;
            margin: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            color: var(--bs-body-color);
            transition: all 0.2s;
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--bs-secondary-bg);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--bs-primary);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 1.25rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: 100vh;
            background-color: var(--bs-body-bg);
        }
        
        /* Top Navbar */
        .navbar-top {
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--bs-border-color);
            background-color: var(--bs-body-bg);
        }
        
        /* Cards */
        .card {
            border: 1px solid var(--bs-border-color);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        /* Table */
        .table {
            --bs-table-bg: var(--bs-body-bg);
        }
        
        /* Theme Toggle Button */
        .theme-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        
        .theme-toggle:hover {
            background-color: var(--bs-secondary-bg);
        }
        
        /* Badge Role */
        .role-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand text-center">
            <i class="bi bi-book fs-1 text-primary"></i>
            <h5 class="mb-0 mt-2">SI SANTREN</h5>
            <small class="text-muted">Sistem Informasi Pesantren</small>
            
            @auth
                <div class="mt-2">
                    <span class="badge role-badge 
                        @if(auth()->user()->isAdmin()) bg-danger
                        @elseif(auth()->user()->isUstadz()) bg-success
                        @else bg-info
                        @endif">
                        {{ auth()->user()->role_name }}
                    </span>
                </div>
            @endauth
        </div>
        
        <nav class="nav flex-column py-3">
            <!-- Dashboard - All Roles -->
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isUstadz() ? route('ustadz.dashboard') : route('santri.dashboard')) }}" 
               class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            
            <!-- Data Santri - Admin & Ustadz -->
            @if(auth()->user()->isAdmin() || auth()->user()->isUstadz())
                <a href="{{ auth()->user()->isAdmin() ? route('admin.santri.index') : route('ustadz.santri.index') }}" 
                   class="nav-link {{ request()->routeIs('*.santri.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Data Santri
                </a>
            @endif
            
            <!-- Pembayaran - Admin Only -->
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.pembayaran.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-coin"></i> Pembayaran
                </a>
            @endif
            
            <!-- Kehadiran - Admin & Ustadz -->
            @if(auth()->user()->isAdmin() || auth()->user()->isUstadz())
                <a href="{{ auth()->user()->isAdmin() ? route('admin.kehadiran.index') : route('ustadz.kehadiran.index') }}" 
                   class="nav-link {{ request()->routeIs('*.kehadiran.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Kehadiran
                </a>
            @endif
            
            <!-- Nilai - Admin & Ustadz -->
            @if(auth()->user()->isAdmin() || auth()->user()->isUstadz())
                <a href="{{ auth()->user()->isAdmin() ? route('admin.nilai.index') : route('ustadz.nilai.index') }}" 
                   class="nav-link {{ request()->routeIs('*.nilai.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Nilai
                </a>
            @endif
            
            <!-- Kelas - Admin & Ustadz -->
            @if(auth()->user()->isAdmin() || auth()->user()->isUstadz())
                <a href="{{ auth()->user()->isAdmin() ? route('admin.kelas.index') : route('ustadz.kelas.index') }}" 
                   class="nav-link {{ request()->routeIs('*.kelas.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Kelas
                </a>
            @endif
            
            <hr class="mx-3">
            
            <!-- Menu Santri -->
            @if(auth()->user()->isSantri())
                <a href="{{ route('santri.profile') }}" 
                   class="nav-link {{ request()->routeIs('santri.profile') ? 'active' : '' }}">
                    <i class="bi bi-person"></i> Profil Saya
                </a>
                <a href="{{ route('santri.kehadiran') }}" 
                   class="nav-link {{ request()->routeIs('santri.kehadiran') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Kehadiran Saya
                </a>
                <a href="{{ route('santri.nilai') }}" 
                   class="nav-link {{ request()->routeIs('santri.nilai') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Nilai Saya
                </a>
                <a href="{{ route('santri.pembayaran') }}" 
                   class="nav-link {{ request()->routeIs('santri.pembayaran') ? 'active' : '' }}">
                    <i class="bi bi-cash-coin"></i> Pembayaran Saya
                </a>
                <hr class="mx-3">
            @endif
            
            <!-- Pengaturan - Admin Only -->
            @if(auth()->user()->isAdmin())
                <a href="#" class="nav-link">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
            @endif
            
            <!-- Logout - All -->
            <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="navbar-top d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                <small class="text-muted">@yield('page-subtitle', 'Selamat datang')</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Theme Toggle -->
                <div class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">
                    <i class="bi bi-moon-fill" id="theme-icon"></i>
                </div>
                
                <span class="badge bg-primary">
                    <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->isoFormat('D MMM Y') }}
                </span>
                
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->username }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2 border-bottom">
                            <small class="text-muted d-block">Logged in as</small>
                            <strong>{{ Auth::user()->role_name }}</strong>
                        </li>
                        @if(auth()->user()->isSantri())
                            <li><a class="dropdown-item" href="{{ route('santri.profile') }}"><i class="bi bi-person"></i> Profil</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <!-- Page Content -->
        @yield('content')
    </div>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Theme Toggle Script -->
    <script>
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(savedTheme);
        
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }
        
        function updateThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            if (icon) {
                icon.className = theme === 'light' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>