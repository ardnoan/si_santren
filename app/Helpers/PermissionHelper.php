<?php
// =====================================================
// FILE: app/Providers/AppServiceProvider.php (Updated)
// =====================================================

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

// =====================================================
// FILE: app/Helpers/PermissionHelper.php (New)
// =====================================================

namespace App\Helpers;

class PermissionHelper
{
    /**
     * Check if user can access a specific module
     */
    public static function canAccess(string $module): bool
    {
        if (!auth()->check()) return false;
        
        $user = auth()->user();
        
        $permissions = [
            'admin' => [
                'santri', 'kelas', 'kehadiran', 'nilai', 
                'pembayaran', 'laporan', 'dashboard'
            ],
            'ustadz' => [
                'santri' => 'read', // read only
                'kelas' => 'read',  // read only
                'kehadiran' => 'create', // can create
                'nilai' => 'create', // can create
                'dashboard'
            ],
            'santri' => [
                'profile', 'kehadiran' => 'read', 
                'nilai' => 'read', 'pembayaran' => 'read',
                'dashboard'
            ]
        ];
        
        $userPermissions = $permissions[$user->role] ?? [];
        
        return in_array($module, $userPermissions) || 
               array_key_exists($module, $userPermissions);
    }
    
    /**
     * Check if user can perform an action on a module
     */
    public static function can(string $action, string $module): bool
    {
        if (!auth()->check()) return false;
        
        $user = auth()->user();
        
        // Admin can do everything
        if ($user->isAdmin()) return true;
        
        // Define specific permissions
        $permissions = [
            'ustadz' => [
                'create' => ['kehadiran', 'nilai'],
                'read' => ['santri', 'kelas', 'kehadiran', 'nilai'],
                'update' => [],
                'delete' => []
            ],
            'santri' => [
                'read' => ['profile', 'kehadiran', 'nilai', 'pembayaran'],
                'create' => [],
                'update' => [],
                'delete' => []
            ]
        ];
        
        $rolePermissions = $permissions[$user->role][$action] ?? [];
        
        return in_array($module, $rolePermissions);
    }
    
    /**
     * Get available actions for a module based on user role
     */
    public static function getAvailableActions(string $module): array
    {
        if (!auth()->check()) return [];
        
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return ['create', 'read', 'update', 'delete'];
        }
        
        if ($user->isUstadz()) {
            if (in_array($module, ['kehadiran', 'nilai'])) {
                return ['create', 'read'];
            }
            if (in_array($module, ['santri', 'kelas'])) {
                return ['read'];
            }
        }
        
        if ($user->isSantri()) {
            return ['read'];
        }
        
        return [];
    }
}

// =====================================================
// USAGE EXAMPLES IN VIEWS
// =====================================================

/*
<!-- Example 1: Simple role check -->
@admin
    <a href="{{ route('admin.santri.create') }}" class="btn btn-primary">
        Tambah Santri
    </a>
@endadmin

<!-- Example 2: Combined role check -->
@adminOrUstadz
    <a href="{{ route('admin.kehadiran.create') }}" class="btn btn-info">
        Input Kehadiran
    </a>
@endadminOrUstadz

<!-- Example 3: Permission-based check -->
@canCreate('kehadiran')
    <button class="btn btn-primary">Input Kehadiran</button>
@endcanCreate

<!-- Example 4: Module-specific permission -->
@canManageSantri
    <div class="admin-only-content">
        <!-- Admin-only santri management -->
    </div>
@endcanManageSantri

<!-- Example 5: Action buttons with multiple checks -->
<div class="btn-group">
    @canView('santri')
        <a href="{{ route('admin.santri.show', $id) }}" class="btn btn-info">
            <i class="bi bi-eye"></i>
        </a>
    @endcanView
    
    @canEdit('santri')
        <a href="{{ route('admin.santri.edit', $id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i>
        </a>
    @endcanEdit
    
    @canDelete('santri')
        <form action="{{ route('admin.santri.destroy', $id) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    @endcanDelete
</div>

<!-- Example 6: Using Helper Class in PHP -->
@if(\App\Helpers\PermissionHelper::can('create', 'kehadiran'))
    <!-- Show create button -->
@endif
*/