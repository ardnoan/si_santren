<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register Repositories
        $this->app->bind(
            \App\Repositories\SantriRepository::class,
            function ($app) {
                return new \App\Repositories\SantriRepository(
                    new \App\Models\Santri()
                );
            }
        );

        $this->app->bind(
            \App\Repositories\PembayaranRepository::class,
            function ($app) {
                return new \App\Repositories\PembayaranRepository(
                    new \App\Models\Pembayaran()
                );
            }
        );

        // Register Services
        $this->app->bind(
            \App\Services\SantriService::class,
            function ($app) {
                return new \App\Services\SantriService(
                    $app->make(\App\Repositories\SantriRepository::class)
                );
            }
        );

        $this->app->bind(
            \App\Services\PembayaranService::class,
            function ($app) {
                return new \App\Services\PembayaranService(
                    $app->make(\App\Repositories\PembayaranRepository::class),
                    $app->make(\App\Repositories\SantriRepository::class)
                );
            }
        );
    }

    public function boot(): void
    {
        // ================================================
        // SIMPLE ROLE-BASED BLADE DIRECTIVES
        // ================================================

        // @admin ... @endadmin
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->role === 'admin';
        });

        // @ustadz ... @endustadz
        Blade::if('ustadz', function () {
            return auth()->check() && auth()->user()->role === 'ustadz';
        });

        // @santri ... @endsantri
        Blade::if('santri', function () {
            return auth()->check() && auth()->user()->role === 'santri';
        });

        // @bendahara ... @endbendahara
        Blade::if('bendahara', function () {
            return auth()->check() && auth()->user()->role === 'bendahara';
        });

        // @pemimpin ... @endpemimpin
        Blade::if('pemimpin', function () {
            return auth()->check() && auth()->user()->role === 'pemimpin';
        });

        // ================================================
        // KOMBINASI ROLES (2 ROLES)
        // ================================================

        // @adminOrUstadz ... @endadminOrUstadz
        Blade::if('adminOrUstadz', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'ustadz']);
        });

        // @adminOrBendahara ... @endadminOrBendahara
        Blade::if('adminOrBendahara', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'bendahara']);
        });

        // @adminOrPemimpin ... @endadminOrPemimpin
        Blade::if('adminOrPemimpin', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'pemimpin']);
        });

        // @bendaharaOrPemimpin ... @endbendaharaOrPemimpin
        Blade::if('bendaharaOrPemimpin', function () {
            return auth()->check() && in_array(auth()->user()->role, ['bendahara', 'pemimpin']);
        });

        // ================================================
        // KOMBINASI ROLES (3+ ROLES)
        // ================================================

        // @staffPesantren (admin, ustadz, bendahara) ... @endstaffPesantren
        Blade::if('staffPesantren', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'ustadz', 'bendahara']);
        });

        // @management (admin, pemimpin) ... @endmanagement
        Blade::if('management', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'pemimpin']);
        });

        // @keuangan (admin, bendahara, pemimpin) ... @endkeuangan
        Blade::if('keuangan', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'bendahara', 'pemimpin']);
        });

        // ================================================
        // PERMISSION-BASED DIRECTIVES
        // ================================================

        // @canCreate('resource')
        Blade::if('canCreate', function ($resource) {
            if (!auth()->check()) {
                return false;
            }

            $user = auth()->user();

            // Admin bisa create semua
            if ($user->role === 'admin') {
                return true;
            }

            // Ustadz bisa create: kehadiran, nilai, jadwal_pelajaran
            if ($user->role === 'ustadz') {
                return in_array($resource, ['kehadiran', 'nilai', 'jadwal_pelajaran']);
            }

            // Bendahara bisa create: pembayaran, pengeluaran, kas
            if ($user->role === 'bendahara') {
                return in_array($resource, ['pembayaran', 'pengeluaran', 'kas']);
            }

            return false;
        });

        // @canEdit('resource')
        Blade::if('canEdit', function ($resource) {
            if (!auth()->check()) {
                return false;
            }

            $user = auth()->user();

            // Admin bisa edit semua
            if ($user->role === 'admin') {
                return true;
            }

            // Ustadz bisa edit: kehadiran, nilai, jadwal_pelajaran
            if ($user->role === 'ustadz') {
                return in_array($resource, ['kehadiran', 'nilai', 'jadwal_pelajaran']);
            }

            // Bendahara bisa edit: pembayaran, pengeluaran, kas
            if ($user->role === 'bendahara') {
                return in_array($resource, ['pembayaran', 'pengeluaran', 'kas']);
            }

            return false;
        });

        // @canDelete('resource')
        Blade::if('canDelete', function ($resource) {
            if (!auth()->check()) {
                return false;
            }

            $user = auth()->user();

            // Hanya admin yang bisa delete
            return $user->role === 'admin';
        });

        // @canView('resource')
        Blade::if('canView', function ($resource) {
            if (!auth()->check()) {
                return false;
            }

            $user = auth()->user();

            // Admin & Pemimpin bisa view semua
            if (in_array($user->role, ['admin', 'pemimpin'])) {
                return true;
            }

            // Ustadz bisa view: kehadiran, nilai, santri, kelas
            if ($user->role === 'ustadz') {
                return in_array($resource, ['kehadiran', 'nilai', 'santri', 'kelas', 'mata_pelajaran', 'jadwal_pelajaran']);
            }

            // Bendahara bisa view: pembayaran, pengeluaran, kas
            if ($user->role === 'bendahara') {
                return in_array($resource, ['pembayaran', 'pengeluaran', 'kas', 'santri']);
            }

            // Santri bisa view data mereka sendiri
            if ($user->role === 'santri') {
                return in_array($resource, ['nilai', 'pembayaran', 'kehadiran']);
            }

            return false;
        });

        // @canApprove (khusus untuk approval pengeluaran, dll)
        Blade::if('canApprove', function () {
            return auth()->check() && auth()->user()->role === 'pemimpin';
        });

        // ================================================
        // UTILITY DIRECTIVES
        // ================================================

        // @role('admin') ... @endrole
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });

        // @hasRole(['admin', 'ustadz']) ... @endhasRole
        Blade::if('hasRole', function ($roles) {
            if (!auth()->check()) {
                return false;
            }
            
            $roles = is_array($roles) ? $roles : [$roles];
            return in_array(auth()->user()->role, $roles);
        });
    }
}