{{-- FILE: resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SI Santren</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
        }
        
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        
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
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: 100vh;
            background-color: var(--bs-body-bg);
        }
        
        .navbar-top {
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--bs-border-color);
            background-color: var(--bs-body-bg);
        }
        
        .card {
            border: 1px solid var(--bs-border-color);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .theme-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        
        .theme-toggle:hover {
            background-color: var(--bs-secondary-bg);
        }
        
        .role-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
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
    <!-- Sidebar Component -->
    @include('components.sidebar')
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar Component -->
        @include('components.navbar')
        
        <!-- Alert Messages Component -->
        @include('components.alerts')
        
        <!-- Page Content -->
        @yield('content')
    </div>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Theme Toggle
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