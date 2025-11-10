<?php
// FILE: routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\NilaiController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\DashboardController;

// Redirect root to appropriate dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->role . '.dashboard');
    }
    return redirect()->route('login');
});

// ============================================
// AUTHENTICATION
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login-custom');
    })->name('login');

    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ============================================
// ADMIN ROUTES
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Unified Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Santri Management - menggunakan form view untuk create dan edit
    Route::get('santri', [SantriController::class, 'index'])->name('santri.index');
    Route::get('santri/create', function () {
        $kelas = \App\Models\Kelas::all();
        return view('santri.form', compact('kelas'));
    })->name('santri.create');
    Route::post('santri', [SantriController::class, 'store'])->name('santri.store');
    Route::get('santri/{id}', [SantriController::class, 'show'])->name('santri.show');
    Route::get('santri/{id}/edit', function ($id) {
        $santri = \App\Models\Santri::findOrFail($id);
        $kelas = \App\Models\Kelas::all();
        return view('santri.form', compact('santri', 'kelas'));
    })->name('santri.edit');
    Route::put('santri/{id}', [SantriController::class, 'update'])->name('santri.update');
    Route::delete('santri/{id}', [SantriController::class, 'destroy'])->name('santri.destroy');
    Route::post('santri/{id}/graduate', [SantriController::class, 'graduate'])->name('santri.graduate');

    // Pembayaran Management
    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('pembayaran/{id}/edit', function ($id) {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        return view('pembayaran.form', compact('pembayaran'));
    })->name('pembayaran.edit');
    Route::put('pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::delete('pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    Route::get('pembayaran/santri/{santriId}', [PembayaranController::class, 'bySantri'])->name('pembayaran.santri');
    Route::get('laporan/pembayaran', [PembayaranController::class, 'laporan'])->name('pembayaran.laporan');
    Route::get('pembayaran/export', [PembayaranController::class, 'export'])->name('pembayaran.export');

    // Kehadiran Management
    Route::get('kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('kehadiran/create', function () {
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        return view('kehadiran.form', compact('santri'));
    })->name('kehadiran.create');
    Route::post('kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::get('kehadiran/{id}/edit', function ($id) {
        $kehadiran = \App\Models\Kehadiran::findOrFail($id);
        return view('kehadiran.form', compact('kehadiran'));
    })->name('kehadiran.edit');
    Route::put('kehadiran/{id}', [KehadiranController::class, 'update'])->name('kehadiran.update');
    Route::delete('kehadiran/{id}', [KehadiranController::class, 'destroy'])->name('kehadiran.destroy');
    Route::post('kehadiran/bulk-create', [KehadiranController::class, 'bulkCreate'])->name('kehadiran.bulk-create');

    // Nilai Management
    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/create', function () {
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        $mapel = \App\Models\MataPelajaran::orderBy('nama_mapel')->get();
        return view('nilai.form', compact('santri', 'mapel'));
    })->name('nilai.create');
    Route::post('nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('nilai/{id}/edit', function ($id) {
        $nilai = \App\Models\Nilai::findOrFail($id);
        return view('nilai.form', compact('nilai'));
    })->name('nilai.edit');
    Route::put('nilai/{id}', [NilaiController::class, 'update'])->name('nilai.update');
    Route::delete('nilai/{id}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
    Route::get('nilai/santri/{santriId}', [NilaiController::class, 'bySantri'])->name('nilai.santri');

    // Kelas Management
    Route::get('kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('kelas/create', function () {
        $ustadz = \App\Models\User::where('role', 'ustadz')->get();
        return view('kelas.form', compact('ustadz'));
    })->name('kelas.create');
    Route::post('kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::get('kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
    Route::get('kelas/{id}/edit', function ($id) {
        $kelas = \App\Models\Kelas::findOrFail($id);
        $ustadz = \App\Models\User::where('role', 'ustadz')->get();
        return view('kelas.form', compact('kelas', 'ustadz'));
    })->name('kelas.edit');
    Route::put('kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
});

// ============================================
// USTADZ ROUTES
// ============================================
Route::middleware(['auth', 'role:ustadz'])->prefix('ustadz')->name('ustadz.')->group(function () {

    // Unified Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Santri (Read Only)
    Route::get('santri', [SantriController::class, 'index'])->name('santri.index');
    Route::get('santri/{id}', [SantriController::class, 'show'])->name('santri.show');

    // Kehadiran (Create & Read)
    Route::get('kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('kehadiran/create', function () {
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        return view('kehadiran.form', compact('santri'));
    })->name('kehadiran.create');
    Route::post('kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::post('kehadiran/bulk-create', [KehadiranController::class, 'bulkCreate'])->name('kehadiran.bulk-create');

    // Nilai (Create & Read)
    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/create', function () {
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        $mapel = \App\Models\MataPelajaran::orderBy('nama_mapel')->get();
        return view('nilai.form', compact('santri', 'mapel'));
    })->name('nilai.create');
    Route::post('nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('nilai/santri/{santriId}', [NilaiController::class, 'bySantri'])->name('nilai.santri');

    // Kelas (Read Only)
    Route::get('kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
});

// ============================================
// SANTRI ROUTES
// ============================================
Route::middleware(['auth', 'role:santri'])->prefix('santri')->name('santri.')->group(function () {

    // Unified Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profil', function () {
        $santri = auth()->user()->santri;
        return view('santri.profile', compact('santri'));
    })->name('profile');

    // Kehadiran (View Own)
    Route::get('/kehadiran', function () {
        $santri = auth()->user()->santri;
        $kehadiran = $santri->kehadiran()->orderBy('tanggal', 'desc')->paginate(20);
        return view('santri.kehadiran', compact('santri', 'kehadiran'));
    })->name('kehadiran');

    // Nilai (View Own)
    Route::get('/nilai', function () {
        $santri = auth()->user()->santri;
        $nilai = $santri->nilai()->with('mataPelajaran')->get();
        return view('santri.nilai', compact('santri', 'nilai'));
    })->name('nilai');

    // Pembayaran (View Own)
    Route::get('/pembayaran', function () {
        $santri = auth()->user()->santri;
        $pembayaran = $santri->pembayaran()->orderBy('tanggal_bayar', 'desc')->paginate(20);
        return view('santri.pembayaran', compact('santri', 'pembayaran'));
    })->name('pembayaran');
});

// ============================================
// API ROUTES (for AJAX)
// ============================================
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('santri/search', [SantriController::class, 'search'])->name('santri.search');
    Route::get('santri/{id}/detail', [SantriController::class, 'detail'])->name('santri.detail');
});
