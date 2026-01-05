<?php
// FILE: routes/web.php (COMPLETE FIXED VERSION)

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\NilaiController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\DashboardController;

// Bendahara Controllers
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboardController;
use App\Http\Controllers\Bendahara\KasController;
use App\Http\Controllers\Bendahara\PembayaranController as BendaharaPembayaranController;
use App\Http\Controllers\Bendahara\PengeluaranController;

// Pemimpin Controllers
use App\Http\Controllers\Pemimpin\DashboardController as PemimpinDashboardController;
use App\Http\Controllers\Pemimpin\LaporanController;
use App\Http\Controllers\Pemimpin\PembayaranController as PemimpinPembayaranController;
use App\Http\Controllers\Pemimpin\SantriController as PemimpinSantriController;
use App\Http\Controllers\Pemimpin\StatistikController;
use App\Http\Controllers\Pemimpin\PengeluaranController as PemimpinPengeluaranController;

// ============================================
// ROOT REDIRECT
// ============================================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isUstadz()) return redirect()->route('ustadz.dashboard');
        if ($user->isSantri()) return redirect()->route('santri.dashboard');
        if ($user->isBendahara()) return redirect()->route('bendahara.dashboard');
        if ($user->isPemimpin()) return redirect()->route('pemimpin.dashboard');
    }
    return redirect()->route('login');
})->name('home');

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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Santri Management
    Route::get('santri', [SantriController::class, 'index'])->name('santri.index');
    Route::get('santri/create', function () {
        $kelas = \App\Models\Kelas::all();
        return view('pages.santri.form', compact('kelas'));
    })->name('santri.create');
    Route::post('santri', [SantriController::class, 'store'])->name('santri.store');
    Route::get('santri/{id}', [SantriController::class, 'show'])->name('santri.show');
    Route::get('santri/{id}/edit', function ($id) {
        $santri = \App\Models\Santri::with('user')->findOrFail($id);
        $kelas = \App\Models\Kelas::all();
        return view('pages.santri.form', compact('santri', 'kelas'));
    })->name('santri.edit');
    Route::put('santri/{id}', [SantriController::class, 'update'])->name('santri.update');
    Route::delete('santri/{id}', [SantriController::class, 'destroy'])->name('santri.destroy');
    Route::post('santri/{id}/graduate', [SantriController::class, 'graduate'])->name('santri.graduate');

    // Pembayaran Management
    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('pembayaran/{id}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
    Route::put('pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::delete('pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    Route::get('pembayaran/laporan', [PembayaranController::class, 'laporan'])->name('pembayaran.laporan');
    Route::get('pembayaran/export', [PembayaranController::class, 'export'])->name('pembayaran.export');

    // Kehadiran Management
    Route::get('kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('kehadiran/create', function () {
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        return view('pages.kehadiran.form', compact('santri'));
    })->name('kehadiran.create');
    Route::post('kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::get('kehadiran/{id}/edit', function ($id) {
        $kehadiran = \App\Models\Kehadiran::with('santri')->findOrFail($id);
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        return view('pages.kehadiran.form', compact('kehadiran', 'santri'));
    })->name('kehadiran.edit');
    Route::put('kehadiran/{id}', [KehadiranController::class, 'update'])->name('kehadiran.update');
    Route::delete('kehadiran/{id}', [KehadiranController::class, 'destroy'])->name('kehadiran.destroy');
    Route::post('kehadiran/bulk-create', [KehadiranController::class, 'bulkCreate'])->name('kehadiran.bulk-create');

    // Nilai Management
    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/create', function () {
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        $mapel = \App\Models\MataPelajaran::orderBy('nama_mapel')->get();
        return view('pages.nilai.form', compact('santri', 'mapel'));
    })->name('nilai.create');
    Route::post('nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('nilai/{id}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
    Route::put('nilai/{id}', [NilaiController::class, 'update'])->name('nilai.update');
    Route::delete('nilai/{id}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
    Route::get('nilai/santri/{santriId}', [NilaiController::class, 'bySantri'])->name('nilai.santri');

    // Kelas Management
    Route::get('kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('kelas/create', function () {
        $ustadz = \App\Models\User::where('role', 'ustadz')->orderBy('username')->get();
        return view('pages.kelas.form', compact('ustadz'));
    })->name('kelas.create');
    Route::post('kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::get('kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
    Route::get('kelas/{id}/edit', function ($id) {
        $kelas = \App\Models\Kelas::findOrFail($id);
        $ustadz = \App\Models\User::where('role', 'ustadz')->orderBy('username')->get();
        return view('pages.kelas.form', compact('kelas', 'ustadz'));
    })->name('kelas.edit');
    Route::put('kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
});

// ============================================
// USTADZ ROUTES
// ============================================
Route::middleware(['auth', 'role:ustadz'])->prefix('ustadz')->name('ustadz.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Santri (Read Only)
    Route::get('santri', [SantriController::class, 'index'])->name('santri.index');
    Route::get('santri/{id}', [SantriController::class, 'show'])->name('santri.show');

    // Kehadiran (Create & Read)
    Route::get('kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('kehadiran/create', function () {
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        return view('pages.kehadiran.form', compact('santri'));
    })->name('kehadiran.create');
    Route::post('kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::post('kehadiran/bulk-create', [KehadiranController::class, 'bulkCreate'])->name('kehadiran.bulk-create');

    // Nilai (Create & Read)
    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/create', function () {
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        $mapel = \App\Models\MataPelajaran::orderBy('nama_mapel')->get();
        return view('pages.nilai.form', compact('santri', 'mapel'));
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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profil', function () {
        $santri = auth()->user()->santri;
        if (!$santri) {
            return redirect()->route('login')->with('error', 'Data santri tidak ditemukan');
        }
        return view('pages.santri.profile', compact('santri'));
    })->name('profile');

    // Kehadiran (View Own)
    Route::get('/kehadiran', function () {
        $santri = auth()->user()->santri;
        if (!$santri) {
            return redirect()->route('login')->with('error', 'Data santri tidak ditemukan');
        }
        $kehadiran = $santri->kehadiran()->orderBy('tanggal', 'desc')->paginate(20);
        return view('pages.santri.kehadiran', compact('santri', 'kehadiran'));
    })->name('kehadiran');

    // Nilai (View Own)
    Route::get('/nilai', function () {
        $santri = auth()->user()->santri;
        if (!$santri) {
            return redirect()->route('login')->with('error', 'Data santri tidak ditemukan');
        }
        $nilai = $santri->nilai()->with('mataPelajaran')->orderBy('tahun_ajaran', 'desc')->get();
        return view('pages.santri.nilai', compact('santri', 'nilai'));
    })->name('nilai');

    // Pembayaran (View Own)
    Route::get('/pembayaran', function () {
        $santri = auth()->user()->santri;
        if (!$santri) {
            return redirect()->route('login')->with('error', 'Data santri tidak ditemukan');
        }
        $pembayaran = $santri->pembayaran()->orderBy('tanggal_bayar', 'desc')->paginate(20);
        return view('pages.santri.pembayaran', compact('santri', 'pembayaran'));
    })->name('pembayaran');
});

// ============================================
// BENDAHARA ROUTES
// ============================================
Route::middleware(['auth', 'role:bendahara'])->prefix('bendahara')->name('bendahara.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [BendaharaDashboardController::class, 'index'])->name('dashboard');

    // Kas Management
    Route::get('kas', [KasController::class, 'index'])->name('kas.index');
    Route::get('kas/{id}', [KasController::class, 'show'])->name('kas.show');

    // Pembayaran - Verifikasi
    Route::get('pembayaran', [BendaharaPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('pembayaran/{id}/verifikasi', [BendaharaPembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');

    // Pengeluaran - CRUD
    Route::get('pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('pengeluaran/{id}', [PengeluaranController::class, 'show'])->name('pengeluaran.show');
    Route::get('pengeluaran/{id}/edit', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
    Route::put('pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::delete('pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

    // Santri (Read Only) - NEW!
    Route::get('santri', [SantriController::class, 'index'])->name('santri.index');
    Route::get('santri/{id}', [SantriController::class, 'show'])->name('santri.show');
});

// ============================================
// PEMIMPIN ROUTES
// ============================================
Route::middleware(['auth', 'role:pemimpin'])->prefix('pemimpin')->name('pemimpin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [PemimpinDashboardController::class, 'index'])->name('dashboard');

    // Laporan Keuangan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // Statistik
    Route::get('statistik', [StatistikController::class, 'index'])->name('statistik.index');

    // Santri (Read Only)
    Route::get('santri', [PemimpinSantriController::class, 'index'])->name('santri.index');
    Route::get('santri/{id}', [PemimpinSantriController::class, 'show'])->name('santri.show');

    // Pembayaran (Read Only)
    Route::get('pembayaran', [PemimpinPembayaranController::class, 'index'])->name('pembayaran.index');

    // Pengeluaran - Approval
    Route::get('pengeluaran', [PemimpinPengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('pengeluaran/{id}', [PemimpinPengeluaranController::class, 'show'])->name('pengeluaran.show');
    Route::post('pengeluaran/{id}/approve', [PemimpinPengeluaranController::class, 'approve'])->name('pengeluaran.approve');
    Route::post('pengeluaran/{id}/reject', [PemimpinPengeluaranController::class, 'reject'])->name('pengeluaran.reject');
});

// ============================================
// API ROUTES (for AJAX)
// ============================================
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('santri/search', function (\Illuminate\Http\Request $request) {
        $keyword = $request->get('q');
        $santri = \App\Models\Santri::aktif()
            ->where(function ($query) use ($keyword) {
                $query->where('nama_lengkap', 'LIKE', "%{$keyword}%")
                    ->orWhere('nomor_induk', 'LIKE', "%{$keyword}%");
            })
            ->limit(10)
            ->get(['id', 'nama_lengkap', 'nomor_induk']);

        return response()->json($santri);
    })->name('santri.search');
});