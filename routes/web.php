<?php
// FILE: routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\NilaiController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes - With Role-Based Access Control
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'ustadz':
                return redirect()->route('ustadz.dashboard');
            case 'santri':
                return redirect()->route('santri.dashboard');
        }
    }
    return redirect()->route('login');
});

// ============================================
// AUTHENTICATION ROUTES
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
// ADMIN ROUTES (Only Admin)
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Santri Management (Full CRUD)
    Route::resource('santri', SantriController::class);
    Route::post('santri/{id}/graduate', [SantriController::class, 'graduate'])->name('santri.graduate');

    // Pembayaran Management (Full CRUD)
    Route::resource('pembayaran', PembayaranController::class);
    Route::get('pembayaran/santri/{santriId}', [PembayaranController::class, 'bySantri'])->name('pembayaran.santri');
    Route::get('laporan/pembayaran', [PembayaranController::class, 'laporan'])->name('pembayaran.laporan');

    // Kehadiran Management (Full CRUD)
    Route::resource('kehadiran', KehadiranController::class);
    Route::post('kehadiran/bulk-create', [KehadiranController::class, 'bulkCreate'])->name('kehadiran.bulk-create');

    // Nilai Management (Full CRUD)
    Route::resource('nilai', NilaiController::class);
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('nilai', NilaiController::class);
        Route::get('nilai/santri/{santriId}', [NilaiController::class, 'bySantri'])
            ->name('nilai.by-santri');
    });
    Route::get('nilai/santri/{santriId}', [NilaiController::class, 'bySantri'])
        ->name('nilai.santri');

    Route::get('pembayaran/export', [PembayaranController::class, 'export'])
        ->name('pembayaran.export');

    // Kelas Management (Full CRUD)
    Route::resource('kelas', KelasController::class);
});

// ============================================
// USTADZ ROUTES (Read Only + Input Nilai & Kehadiran)
// ============================================
Route::middleware(['auth', 'role:ustadz'])->prefix('ustadz')->name('ustadz.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Santri (Read Only)
    Route::get('santri', [SantriController::class, 'index'])->name('santri.index');
    Route::get('santri/{id}', [SantriController::class, 'show'])->name('santri.show');

    // Kehadiran (Create & Read)
    Route::get('kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('kehadiran/create', [KehadiranController::class, 'create'])->name('kehadiran.create');
    Route::post('kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::post('kehadiran/bulk-create', [KehadiranController::class, 'bulkCreate'])->name('kehadiran.bulk-create');

    // Nilai (Create & Read)
    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/create', [NilaiController::class, 'create'])->name('nilai.create');
    Route::post('nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::middleware(['auth', 'role:ustadz'])->prefix('ustadz')->name('ustadz.')->group(function () {
        Route::get('nilai/santri/{santriId}', [NilaiController::class, 'bySantri'])
            ->name('nilai.by-santri'); // ✅ Sama, tapi di namespace ustadz
    });

    Route::get('nilai/santri/{santriId}', [NilaiController::class, 'bySantri'])
        ->name('nilai.santri');

    // Kelas (Read Only)
    Route::get('kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
});

// ============================================
// SANTRI ROUTES (View Own Data Only)
// ============================================
Route::middleware(['auth', 'role:santri'])->prefix('santri')->name('santri.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profil', function () {
        $santri = auth()->user()->santri;
        return view('santri.profil', compact('santri'));
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
