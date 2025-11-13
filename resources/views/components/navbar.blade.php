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

            <!-- User Button (Opens Logout Modal) -->
            <button class="btn btn-outline-secondary border-2 d-flex align-items-center gap-2"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#logoutModal">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-lg-inline">{{ Auth::user()->username }}</span>
            </button>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-box-arrow-right fs-1 text-danger mb-3"></i>
                <p class="mb-0">Apakah kamu yakin ingin keluar dari akun ini?</p>
                <small class="text-muted d-block mt-2">Kamu masih bisa login kembali nanti.</small>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center gap-2 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-danger px-4"
                        onclick="document.getElementById('logout-form').submit();">
                    Logout
                </button>
            </div>
        </div>
    </div>
</div>
