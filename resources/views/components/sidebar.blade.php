<div class="sidebar">
  <div class="brand">
    <i class="bi bi-book"></i>
    <h5>SI SANTREN</h5>
    <small>Sistem Informasi Pesantren</small>

    @auth
    <div class="mt-2">
      <span class="role-badge 
                        @if(auth()->user()->isAdmin()) bg-danger
                        @elseif(auth()->user()->isUstadz()) bg-success
                        @else bg-info
                        @endif">
        {{ auth()->user()->role_name }}
      </span>
    </div>
    @endauth
  </div>

  <nav class="nav flex-column">
    {{-- ========================================== --}}
    {{-- ADMIN MENU --}}
    {{-- ========================================== --}}
    @admin
    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}"
      class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <i class="bi bi-speedometer2"></i>
      <span>Dashboard</span>
    </a>

    <div class="nav-section-title">
      <small>MANAJEMEN DATA</small>
    </div>

    <!-- Data Santri -->
    <a href="{{ route('admin.santri.index') }}"
      class="nav-link {{ request()->routeIs('admin.santri.*') ? 'active' : '' }}">
      <i class="bi bi-people"></i>
      <span>Data Santri</span>
    </a>

    <!-- Kelas -->
    <a href="{{ route('admin.kelas.index') }}"
      class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
      <i class="bi bi-building"></i>
      <span>Data Kelas</span>
    </a>

    <div class="nav-section-title">
      <small>AKADEMIK</small>
    </div>

    <!-- Kehadiran -->
    <a href="{{ route('admin.kehadiran.index') }}"
      class="nav-link {{ request()->routeIs('admin.kehadiran.*') ? 'active' : '' }}">
      <i class="bi bi-calendar-check"></i>
      <span>Kehadiran</span>
    </a>

    <!-- Nilai -->
    <a href="{{ route('admin.nilai.index') }}"
      class="nav-link {{ request()->routeIs('admin.nilai.*') ? 'active' : '' }}">
      <i class="bi bi-journal-text"></i>
      <span>Nilai Santri</span>
    </a>

    <div class="nav-section-title">
      <small>KEUANGAN</small>
    </div>

    <!-- Pembayaran -->
    <a href="{{ route('admin.pembayaran.index') }}"
      class="nav-link {{ request()->routeIs('admin.pembayaran.index') || request()->routeIs('admin.pembayaran.create') || request()->routeIs('admin.pembayaran.edit') ? 'active' : '' }}">
      <i class="bi bi-cash-coin"></i>
      <span>Pembayaran</span>
    </a>

    <!-- Laporan -->
    <a href="{{ route('admin.pembayaran.laporan') }}"
      class="nav-link {{ request()->routeIs('admin.pembayaran.laporan') || request()->routeIs('admin.pembayaran.export') ? 'active' : '' }}">
      <i class="bi bi-file-earmark-text"></i>
      <span>Laporan Keuangan</span>
    </a>
    @endadmin

    {{-- ========================================== --}}
    {{-- USTADZ MENU --}}
    {{-- ========================================== --}}
    @ustadz
    <!-- Dashboard -->
    <a href="{{ route('ustadz.dashboard') }}"
      class="nav-link {{ request()->routeIs('ustadz.dashboard') ? 'active' : '' }}">
      <i class="bi bi-speedometer2"></i>
      <span>Dashboard</span>
    </a>

    <div class="nav-section-title">
      <small>DATA</small>
    </div>

    <!-- Data Santri -->
    <a href="{{ route('ustadz.santri.index') }}"
      class="nav-link {{ request()->routeIs('ustadz.santri.*') ? 'active' : '' }}">
      <i class="bi bi-people"></i>
      <span>Data Santri</span>
    </a>

    <!-- Kelas -->
    <a href="{{ route('ustadz.kelas.index') }}"
      class="nav-link {{ request()->routeIs('ustadz.kelas.*') ? 'active' : '' }}">
      <i class="bi bi-building"></i>
      <span>Data Kelas</span>
    </a>

    <div class="nav-section-title">
      <small>INPUT DATA</small>
    </div>

    <!-- Kehadiran -->
    <a href="{{ route('ustadz.kehadiran.index') }}"
      class="nav-link {{ request()->routeIs('ustadz.kehadiran.*') ? 'active' : '' }}">
      <i class="bi bi-calendar-check"></i>
      <span>Input Kehadiran</span>
    </a>

    <!-- Nilai -->
    <a href="{{ route('ustadz.nilai.index') }}"
      class="nav-link {{ request()->routeIs('ustadz.nilai.*') ? 'active' : '' }}">
      <i class="bi bi-journal-text"></i>
      <span>Input Nilai</span>
    </a>
    @endustadz

    {{-- ========================================== --}}
    {{-- SANTRI MENU --}}
    {{-- ========================================== --}}
    @santri
    <!-- Dashboard -->
    <a href="{{ route('santri.dashboard') }}"
      class="nav-link {{ request()->routeIs('santri.dashboard') ? 'active' : '' }}">
      <i class="bi bi-speedometer2"></i>
      <span>Dashboard</span>
    </a>

    <div class="nav-section-title">
      <small>PROFIL SAYA</small>
    </div>

    <!-- Profil -->
    <a href="{{ route('santri.profile') }}"
      class="nav-link {{ request()->routeIs('santri.profile') ? 'active' : '' }}">
      <i class="bi bi-person-circle"></i>
      <span>Profil Saya</span>
    </a>

    <div class="nav-section-title">
      <small>AKADEMIK</small>
    </div>

    <!-- Kehadiran -->
    <a href="{{ route('santri.kehadiran') }}"
      class="nav-link {{ request()->routeIs('santri.kehadiran') ? 'active' : '' }}">
      <i class="bi bi-calendar-check"></i>
      <span>Kehadiran Saya</span>
    </a>

    <!-- Nilai -->
    <a href="{{ route('santri.nilai') }}"
      class="nav-link {{ request()->routeIs('santri.nilai') ? 'active' : '' }}">
      <i class="bi bi-journal-text"></i>
      <span>Nilai Saya</span>
    </a>

    <div class="nav-section-title">
      <small>KEUANGAN</small>
    </div>

    <!-- Pembayaran -->
    <a href="{{ route('santri.pembayaran') }}"
      class="nav-link {{ request()->routeIs('santri.pembayaran') ? 'active' : '' }}">
      <i class="bi bi-cash-coin"></i>
      <span>Riwayat Pembayaran</span>
    </a>
    @endsantri

    {{-- ========================================== --}}
    {{-- COMMON MENU --}}
    {{-- ========================================== --}}
    <hr class="mx-3 my-2" style="border-color: rgba(255,255,255,0.2);">

    <!-- Logout -->
    <a href="#"
      class="nav-link text-danger"
      onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
      style="background: rgba(231, 76, 60, 0.1);">
      <i class="bi bi-box-arrow-right"></i>
      <span>Logout</span>
    </a>
  </nav>
</div>