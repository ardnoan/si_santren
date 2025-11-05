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
        <li>
          <hr class="dropdown-divider">
        </li>
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