{{-- resources/views/components/navbar.blade.php --}}
<div class="navbar-custom border-bottom shadow-sm sticky-top" style="z-index: 100;">
    <div class="d-flex justify-content-between align-items-center p-3 p-lg-4">
        <!-- Page Title -->
        <div>
            <h5 class="fw-bold mb-1">@yield('page-title', 'Dashboard')</h5>
            <small class="text-muted">@yield('page-subtitle', 'Selamat datang')</small>
        </div>

        <!-- Right Actions -->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <!-- Theme Toggle -->
            <button class="btn btn-outline-secondary border-2" 
                    onclick="toggleTheme()" 
                    title="Toggle Dark Mode"
                    style="width: 40px; height: 40px; padding: 0;">
                <i class="bi bi-moon-fill" id="theme-icon"></i>
            </button>

            <!-- Date Badge -->
            <span class="badge bg-primary px-3 py-2 d-none d-md-inline-flex align-items-center gap-2">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::now()->isoFormat('D MMM Y') }}
            </span>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle border-2 d-flex align-items-center gap-2" 
                        type="button" 
                        data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-lg-inline">{{ Auth::user()->username }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="min-width: 200px;">
                    <li class="px-3 py-2 border-bottom">
                        <small class="text-muted d-block mb-1">Logged in as</small>
                        <strong class="d-block">{{ Auth::user()->role_name }}</strong>
                    </li>
                    @if(auth()->user()->isSantri())
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" 
                           href="{{ route('santri.profile') }}">
                            <i class="bi bi-person"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                    @endif
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item text-danger d-flex align-items-center gap-2 py-2" 
                           href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="fw-semibold">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>