<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\NilaiController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Admin Routes (dengan middleware auth)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Santri Management
    Route::resource('santri', SantriController::class);
    Route::post('santri/{id}/graduate', [SantriController::class, 'graduate'])->name('santri.graduate');
    
    // Pembayaran Management
    Route::resource('pembayaran', PembayaranController::class);
    Route::get('pembayaran/santri/{santriId}', [PembayaranController::class, 'bySantri'])->name('pembayaran.santri');
    Route::get('laporan/pembayaran', [PembayaranController::class, 'laporan'])->name('pembayaran.laporan');
    
    // Kehadiran Management
    Route::resource('kehadiran', KehadiranController::class);
    Route::get('kehadiran/tanggal/{date}', [KehadiranController::class, 'byDate'])->name('kehadiran.by-date');
    Route::post('kehadiran/bulk-create', [KehadiranController::class, 'bulkCreate'])->name('kehadiran.bulk-create');
    
    // Nilai Management
    Route::resource('nilai', NilaiController::class);
    Route::get('nilai/santri/{santriId}', [NilaiController::class, 'bySantri'])->name('nilai.santri');
    
    // Reports
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/kehadiran', [App\Http\Controllers\LaporanController::class, 'kehadiran'])->name('kehadiran');
        Route::get('/pembayaran', [App\Http\Controllers\LaporanController::class, 'pembayaran'])->name('pembayaran');
        Route::get('/nilai', [App\Http\Controllers\LaporanController::class, 'nilai'])->name('nilai');
    });
});

// API Routes (untuk AJAX calls)
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('santri/search', [SantriController::class, 'search'])->name('santri.search');
    Route::get('santri/{id}/detail', [SantriController::class, 'detail'])->name('santri.detail');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
