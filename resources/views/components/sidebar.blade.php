{{-- resources/views/components/sidebar.blade.php - FIXED VERSION --}}
<div class="sidebar position-fixed top-0 start-0 vh-100 bg-gradient-primary overflow-auto shadow-lg d-flex flex-column" 
     style="z-index: 1000;">
    
    <!-- Brand -->
    <div class="p-4 border-bottom border-white border-opacity-10 text-center">
        <i class="bi bi-book fs-1 d-block mb-2 sidebar-link"></i>
        <h5 class="fw-bold mb-1 sidebar-link">SI SANTREN</h5>
        <small class="opacity-90 d-block mb-2 sidebar-link">Sistem Informasi Pesantren</small>
        
        @auth
        <span class="badge 
            @if(auth()->user()->isAdmin()) bg-danger
            @elseif(auth()->user()->isUstadz()) bg-success
            @elseif(auth()->user()->isBendahara()) bg-warning
            @elseif(auth()->user()->isPemimpin()) bg-dark
            @else bg-info
            @endif px-3 py-2">
            {{ auth()->user()->role_name }}
        </span>
        @endauth
    </div>

    <!-- Navigation -->
    <nav class="flex-grow-1 p-3">
        
        {{-- ==================== ADMIN MENU ==================== --}}
        @admin
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('admin.dashboard') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-speedometer2 fs-5"></i>
            <span>Dashboard</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold ls-1 sidebar-link" style="font-size: 0.7rem;">
                Manajemen Data
            </small>
        </div>

        <a href="{{ route('admin.santri.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('admin.santri.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-people fs-5"></i>
            <span>Data Santri</span>
        </a>

        <a href="{{ route('admin.kelas.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('admin.kelas.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-building fs-5"></i>
            <span>Data Kelas</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Akademik
            </small>
        </div>

        <a href="{{ route('admin.kehadiran.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('admin.kehadiran.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-calendar-check fs-5"></i>
            <span>Kehadiran</span>
        </a>

        <a href="{{ route('admin.nilai.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('admin.nilai.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-journal-text fs-5"></i>
            <span>Nilai Santri</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Keuangan
            </small>
        </div>

        <a href="{{ route('admin.pembayaran.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('admin.pembayaran.index') || request()->routeIs('admin.pembayaran.create') || request()->routeIs('admin.pembayaran.edit') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-cash-coin fs-5"></i>
            <span>Pembayaran</span>
        </a>

        <a href="{{ route('admin.pembayaran.laporan') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('admin.pembayaran.laporan') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-file-earmark-text fs-5"></i>
            <span>Laporan Keuangan</span>
        </a>
        @endadmin

        {{-- ==================== USTADZ MENU ==================== --}}
        @ustadz
        <a href="{{ route('ustadz.dashboard') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('ustadz.dashboard') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-speedometer2 fs-5"></i>
            <span>Dashboard</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Data
            </small>
        </div>

        <a href="{{ route('ustadz.santri.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('ustadz.santri.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-people fs-5"></i>
            <span>Data Santri</span>
        </a>

        <a href="{{ route('ustadz.kelas.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('ustadz.kelas.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-building fs-5"></i>
            <span>Data Kelas</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Input Data
            </small>
        </div>

        <a href="{{ route('ustadz.kehadiran.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('ustadz.kehadiran.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-calendar-check fs-5"></i>
            <span>Input Kehadiran</span>
        </a>

        <a href="{{ route('ustadz.nilai.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('ustadz.nilai.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-journal-text fs-5"></i>
            <span>Input Nilai</span>
        </a>
        @endustadz

        {{-- ==================== SANTRI MENU ==================== --}}
        @santri
        <a href="{{ route('santri.dashboard') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('santri.dashboard') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-speedometer2 fs-5"></i>
            <span>Dashboard</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Profil Saya
            </small>
        </div>

        <a href="{{ route('santri.profile') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('santri.profile') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-person-circle fs-5"></i>
            <span>Profil Saya</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Akademik
            </small>
        </div>

        <a href="{{ route('santri.kehadiran') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('santri.kehadiran') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-calendar-check fs-5"></i>
            <span>Kehadiran Saya</span>
        </a>

        <a href="{{ route('santri.nilai') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('santri.nilai') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-journal-text fs-5"></i>
            <span>Nilai Saya</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Keuangan
            </small>
        </div>

        <a href="{{ route('santri.pembayaran') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('santri.pembayaran') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-cash-coin fs-5"></i>
            <span>Riwayat Pembayaran</span>
        </a>
        @endsantri

        {{-- ==================== BENDAHARA MENU ==================== --}}
        @bendahara
        <a href="{{ route('bendahara.dashboard') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('bendahara.dashboard') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-speedometer2 fs-5"></i>
            <span>Dashboard</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Keuangan
            </small>
        </div>

        <a href="{{ route('bendahara.pembayaran.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('bendahara.pembayaran.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-cash-coin fs-5"></i>
            <span>Pembayaran</span>
        </a>

        <a href="{{ route('bendahara.pengeluaran.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('bendahara.pengeluaran.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-wallet2 fs-5"></i>
            <span>Pengeluaran</span>
        </a>

        <a href="{{ route('bendahara.kas.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('bendahara.kas.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-piggy-bank fs-5"></i>
            <span>Kas Pesantren</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Data Santri
            </small>
        </div>

        <a href="{{ route('bendahara.santri.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('bendahara.santri.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-people fs-5"></i>
            <span>Data Santri</span>
        </a>
        @endbendahara

        {{-- ==================== PEMIMPIN MENU ==================== --}}
        @pemimpin
        <a href="{{ route('pemimpin.dashboard') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('pemimpin.dashboard') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-speedometer2 fs-5"></i>
            <span>Dashboard</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Laporan & Statistik
            </small>
        </div>

        <a href="{{ route('pemimpin.laporan.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('pemimpin.laporan.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-file-earmark-text fs-5"></i>
            <span>Laporan</span>
        </a>

        <a href="{{ route('pemimpin.statistik.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('pemimpin.statistik.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-graph-up fs-5"></i>
            <span>Statistik</span>
        </a>

        <div class="px-3 pt-3 pb-2">
            <small class="text-uppercase opacity-75 fw-semibold sidebar-link" style="font-size: 0.7rem;">
                Lihat Data
            </small>
        </div>

        <a href="{{ route('pemimpin.santri.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('pemimpin.santri.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-people fs-5"></i>
            <span>Data Santri</span>
        </a>

        <a href="{{ route('pemimpin.pembayaran.index') }}"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 mb-2 transition-all
                  {{ request()->routeIs('pemimpin.pembayaran.*') ? 'active fw-semibold shadow-sm' : '' }}">
            <i class="bi bi-cash-coin fs-5"></i>
            <span>Data Pembayaran</span>
        </a>
        @endpemimpin

        <!-- Divider -->
        <hr class="border-white border-opacity-20 my-3">

        <!-- Logout -->
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="sidebar-link d-flex align-items-center gap-3 text-decoration-none p-3 rounded-3 bg-danger bg-opacity-10 transition-all">
            <i class="bi bi-box-arrow-right fs-5"></i>
            <span class="fw-semibold">Logout</span>
        </a>
    </nav>
</div>