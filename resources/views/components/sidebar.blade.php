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
    <!-- Dashboard -->
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

    <!-- Logout -->
    <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </nav>
</div>