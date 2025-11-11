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
        // ROLE-BASED BLADE DIRECTIVES
        // ================================================
        
        // @admin ... @endadmin
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });
        
        // @ustadz ... @endust adz
        Blade::if('ustadz', function () {
            return auth()->check() && auth()->user()->isUstadz();
        });
        
        // @santri ... @endsantri
        Blade::if('santri', function () {
            return auth()->check() && auth()->user()->isSantri();
        });
        
        // @adminOrUstadz ... @endadminOrUstadz
        Blade::if('adminOrUstadz', function () {
            return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isUstadz());
        });
        
        // @role('admin') ... @endrole
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });

        // ================================================
        // PERMISSION-BASED BLADE DIRECTIVES
        // ================================================
        
        // @canCreate ... @endcanCreate
        Blade::if('canCreate', function ($module = null) {
            if (!auth()->check()) return false;
            
            $user = auth()->user();
            
            // Admin can create everything
            if ($user->isAdmin()) return true;
            
            // Ustadz can create kehadiran and nilai only
            if ($user->isUstadz()) {
                return in_array($module, ['kehadiran', 'nilai']);
            }
            
            // Santri cannot create anything
            return false;
        });
        
        // @canEdit ... @endcanEdit
        Blade::if('canEdit', function ($module = null) {
            if (!auth()->check()) return false;
            
            $user = auth()->user();
            
            // Only admin can edit
            return $user->isAdmin();
        });
        
        // @canDelete ... @endcanDelete
        Blade::if('canDelete', function ($module = null) {
            if (!auth()->check()) return false;
            
            $user = auth()->user();
            
            // Only admin can delete
            return $user->isAdmin();
        });
        
        // @canView ... @endcanView
        Blade::if('canView', function ($module = null) {
            if (!auth()->check()) return false;
            
            // All authenticated users can view
            return true;
        });

        // ================================================
        // MODULE-SPECIFIC PERMISSIONS
        // ================================================
        
        // @canManageSantri ... @endcanManageSantri
        Blade::if('canManageSantri', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });
        
        // @canManagePembayaran ... @endcanManagePembayaran
        Blade::if('canManagePembayaran', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });
        
        // @canInputKehadiran ... @endcanInputKehadiran
        Blade::if('canInputKehadiran', function () {
            return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isUstadz());
        });
        
        // @canInputNilai ... @endcanInputNilai
        Blade::if('canInputNilai', function () {
            return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isUstadz());
        });
        
        // @canViewLaporan ... @endcanViewLaporan
        Blade::if('canViewLaporan', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });
    }
}