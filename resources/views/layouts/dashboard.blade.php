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
            /* Colors */
            --primary-color: #2c3e50;
            --primary-light: #34495e;
            --primary-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            --secondary-color: #3498db;
            --accent-color: #4ade80;

            /* Neutrals */
            --bg-color: #f8fafc;
            --surface-color: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.12);

            /* Sizes */
            --sidebar-width: 280px;
            --navbar-height: 70px;
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Dark Theme Variables */
        [data-bs-theme="dark"] {
            --bg-color: #0f172a;
            --surface-color: #1e293b;
            --border-color: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--primary-gradient);
            color: white;
            z-index: 1000;
            overflow-y: auto;
            transition: var(--transition);
            box-shadow: var(--shadow-lg);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar .brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar .brand i {
            font-size: 2.5rem;
            color: var(--accent-color);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .sidebar .brand h5 {
            margin: 0.75rem 0 0.25rem;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar .brand small {
            opacity: 0.9;
            font-size: 0.8rem;
        }

        .sidebar .role-badge {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Sidebar Navigation */
        .sidebar .nav {
            padding: 1rem 0;
        }

        .nav-section-title {
            padding: 1rem 1.5rem 0.5rem;
            margin-top: 0.5rem;
        }

        .nav-section-title small {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            opacity: 0.7;
            text-transform: uppercase;
        }

        .sidebar .nav-link {
            padding: 0.875rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: var(--border-radius);
            color: rgba(255, 255, 255, 0.9);
            transition: var(--transition);
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .sidebar .nav-link i {
            margin-right: 0.875rem;
            width: 1.25rem;
            font-size: 1.1rem;
        }

        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition);
        }

        /* Top Navbar */
        .navbar-top {
            background: var(--surface-color);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .navbar-top h5 {
            color: var(--text-primary);
            font-weight: 700;
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }

        .navbar-top small {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .theme-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            color: var(--text-secondary);
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle:hover {
            background: var(--border-color);
            color: var(--text-primary);
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* ==================== CARDS ==================== */
        .card {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            background: var(--surface-color);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stats-card {
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            position: relative;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: currentColor;
        }

        .stats-card .card-body {
            padding: 1.5rem;
        }

        .stats-card h6 {
            font-size: 0.875rem;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-card h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stats-card small {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .stats-card i {
            font-size: 2.5rem;
            opacity: 0.3;
        }

        /* ==================== TABLES ==================== */
        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: var(--bg-color);
            border-bottom: 2px solid var(--border-color);
        }

        .table thead th {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            padding: 1rem;
            border: none;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background: var(--bg-color);
        }

        /* ==================== BADGES ==================== */
        .badge {
            padding: 0.4rem 0.75rem;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: 6px;
        }

        /* ==================== BUTTONS ==================== */
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            border: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(44, 62, 80, 0.3);
        }

        .btn-sm {
            padding: 0.4rem 0.875rem;
            font-size: 0.875rem;
        }

        .btn-group-sm>.btn {
            padding: 0.375rem 0.75rem;
        }

        /* ==================== ALERTS ==================== */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.25rem;
            border-left: 4px solid;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left-color: #22c55e;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left-color: #ef4444;
        }

        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border-left-color: #f59e0b;
        }

        .alert-info {
            background: #f0f9ff;
            color: #075985;
            border-left-color: #0ea5e9;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            :root {
                --sidebar-width: 100%;
            }

            .navbar-top {
                padding: 1rem;
            }

            .navbar-top h5 {
                font-size: 1.25rem;
            }

            .content-area {
                padding: 1rem;
            }

            .stats-card h2 {
                font-size: 1.5rem;
            }
        }

        /* ==================== UTILITIES ==================== */
        .text-gradient {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-gradient {
            background: var(--primary-gradient);
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.show {
            display: flex;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Sidebar Component -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar Component -->
        @include('components.navbar')

        <!-- Content Area -->
        <div class="content-area">
            <!-- Alert Messages Component -->
            @include('components.alerts')

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Scripts -->
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

        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Delete confirmation
            const deleteForms = document.querySelectorAll('form[method="POST"]');
            deleteForms.forEach(form => {
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput && methodInput.value === 'DELETE') {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        if (confirm('⚠️ Apakah Anda yakin ingin menghapus data ini?\n\nData yang dihapus tidak dapat dikembalikan!')) {
                            form.submit();
                        }
                    });
                }
            });

            // Focus first invalid input
            const inputsWithErrors = document.querySelectorAll('.is-invalid');
            if (inputsWithErrors.length > 0) {
                inputsWithErrors[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                inputsWithErrors[0].focus();
            }

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    @yield('scripts')
</body>

</html>