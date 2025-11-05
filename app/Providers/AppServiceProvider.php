<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
/**
 * Class AppServiceProvider
 * Implementasi: Dependency Inversion Principle
 * Binding semua dependencies
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
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
    }
}