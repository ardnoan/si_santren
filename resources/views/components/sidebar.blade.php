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
    {{-- ========================================== --}}
    {{-- ADMIN MENU --}}
    {{-- ========================================== --}}
    @admin
      <!-- Dashboard -->
      <a href="{{ route('admin.dashboard') }}"
        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>

      <div class="nav-section-title">
        <small class="text-muted px-3">MANAJEMEN DATA</small>
      </div>

      <!-- Data Santri -->
      <a href="{{ route('admin.santri.index') }}"
        class="nav-link {{ request()->routeIs('admin.santri.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Data Santri
      </a>

      <!-- Kelas -->
      <a href="{{ route('admin.kelas.index') }}"
        class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
        <i class="bi bi-building"></i> Data Kelas
      </a>

      <div class="nav-section-title">
        <small class="text-muted px-3">AKADEMIK</small>
      </div>

      <!-- Kehadiran -->
      <a href="{{ route('admin.kehadiran.index') }}"
        class="nav-link {{ request()->routeIs('admin.kehadiran.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> Kehadiran
      </a>

      <!-- Nilai -->
      <a href="{{ route('admin.nilai.index') }}"
        class="nav-link {{ request()->routeIs('admin.nilai.*') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i> Nilai Santri
      </a>

      <div class="nav-section-title">
        <small class="text-muted px-3">KEUANGAN</small>
      </div>

      <!-- Pembayaran -->
      <a href="{{ route('admin.pembayaran.index') }}"
        class="nav-link {{ request()->routeIs('admin.pembayaran.index') || request()->routeIs('admin.pembayaran.create') || request()->routeIs('admin.pembayaran.edit') ? 'active' : '' }}">
        <i class="bi bi-cash-coin"></i> Pembayaran
      </a>

      <!-- Laporan -->
      <a href="{{ route('admin.pembayaran.laporan') }}"
        class="nav-link {{ request()->routeIs('admin.pembayaran.laporan') || request()->routeIs('admin.pembayaran.export') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i> Laporan Keuangan
      </a>
    @endadmin

    {{-- ========================================== --}}
    {{-- USTADZ MENU --}}
    {{-- ========================================== --}}
    @ustadz
      <!-- Dashboard -->
      <a href="{{ route('ustadz.dashboard') }}"
        class="nav-link {{ request()->routeIs('ustadz.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>

      <div class="nav-section-title">
        <small class="text-muted px-3">DATA</small>
      </div>

      <!-- Data Santri (Read Only) -->
      <a href="{{ route('ustadz.santri.index') }}"
        class="nav-link {{ request()->routeIs('ustadz.santri.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Data Santri
      </a>

      <!-- Kelas (Read Only) -->
      <a href="{{ route('ustadz.kelas.index') }}"
        class="nav-link {{ request()->routeIs('ustadz.kelas.*') ? 'active' : '' }}">
        <i class="bi bi-building"></i> Data Kelas
      </a>

      <div class="nav-section-title">
        <small class="text-muted px-3">INPUT DATA</small>
      </div>

      <!-- Kehadiran -->
      <a href="{{ route('ustadz.kehadiran.index') }}"
        class="nav-link {{ request()->routeIs('ustadz.kehadiran.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> Input Kehadiran
      </a>

      <!-- Nilai -->
      <a href="{{ route('ustadz.nilai.index') }}"
        class="nav-link {{ request()->routeIs('ustadz.nilai.*') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i> Input Nilai
      </a>
    @endustadz

    {{-- ========================================== --}}
    {{-- SANTRI MENU --}}
    {{-- ========================================== --}}
    @santri
      <!-- Dashboard -->
      <a href="{{ route('santri.dashboard') }}"
        class="nav-link {{ request()->routeIs('santri.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>

      <div class="nav-section-title">
        <small class="text-muted px-3">PROFIL SAYA</small>
      </div>

      <!-- Profil -->
      <a href="{{ route('santri.profile') }}"
        class="nav-link {{ request()->routeIs('santri.profile') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i> Profil Saya
      </a>

      <div class="nav-section-title">
        <small class="text-muted px-3">AKADEMIK</small>
      </div>

      <!-- Kehadiran -->
      <a href="{{ route('santri.kehadiran') }}"
        class="nav-link {{ request()->routeIs('santri.kehadiran') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> Kehadiran Saya
      </a>

      <!-- Nilai -->
      <a href="{{ route('santri.nilai') }}"
        class="nav-link {{ request()->routeIs('santri.nilai') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i> Nilai Saya
      </a>

      <div class="nav-section-title">
        <small class="text-muted px-3">KEUANGAN</small>
      </div>

      <!-- Pembayaran -->
      <a href="{{ route('santri.pembayaran') }}"
        class="nav-link {{ request()->routeIs('santri.pembayaran') ? 'active' : '' }}">
        <i class="bi bi-cash-coin"></i> Riwayat Pembayaran
      </a>
    @endsantri

    {{-- ========================================== --}}
    {{-- COMMON MENU (ALL ROLES) --}}
    {{-- ========================================== --}}
    <hr class="mx-3 my-2">

    <!-- Logout -->
    <a href="#" 
       class="nav-link text-danger" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </nav>
</div>

<style>
.nav-section-title {
  padding: 0.75rem 0 0.25rem;
  margin-top: 0.5rem;
}

.nav-section-title small {
  font-size: 0.7rem;
  font-weight: 600;
  letter-spacing: 0.5px;
}
</style>