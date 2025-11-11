<div class="navbar-top">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h5>@yield('page-title', 'Dashboard')</h5>
      <small>@yield('page-subtitle', 'Selamat datang')</small>
    </div>
    <div class="d-flex align-items-center gap-3">
      <!-- Theme Toggle -->
      <button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">
        <i class="bi bi-moon-fill" id="theme-icon"></i>
      </button>

      <!-- Date Badge -->
      <span class="badge bg-primary px-3 py-2">
        <i class="bi bi-calendar3 me-1"></i>
        {{ \Carbon\Carbon::now()->isoFormat('D MMM Y') }}
      </span>

      <!-- User Dropdown -->
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle me-1"></i>
          {{ Auth::user()->username }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li class="px-3 py-2 border-bottom">
            <small class="text-muted d-block">Logged in as</small>
            <strong>{{ Auth::user()->role_name }}</strong>
          </li>
          @if(auth()->user()->isSantri())
          <li>
            <a class="dropdown-item" href="{{ route('santri.profile') }}">
              <i class="bi bi-person me-2"></i>Profil
            </a>
          </li>
          @endif
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item text-danger" href="#"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>