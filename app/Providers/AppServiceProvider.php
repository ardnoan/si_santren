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
        // KOMBINASI ROLES
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
        
        // @staffPesantren (admin, ustadz, bendahara) ... @endstaffPesantren
        Blade::if('staffPesantren', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'ustadz', 'bendahara']);
        });
        
        // @management (admin, pemimpin) ... @endmanagement
        Blade::if('management', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'pemimpin']);
        });
    }
}