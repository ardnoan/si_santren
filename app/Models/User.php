<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * Base class untuk semua pengguna sistem
 * Implementasi: Inheritance & Polymorphism
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    // === RELATIONSHIPS (OOP: Association) ===
    
    public function santri()
    {
        return $this->hasOne(Santri::class);
    }

    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas');
    }

    // === METHODS (OOP: Encapsulation) ===
    
    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is ustadz
     */
    public function isUstadz(): bool
    {
        return $this->role === 'ustadz';
    }

    /**
     * Check if user is santri
     */
    public function isSantri(): bool
    {
        return $this->role === 'santri';
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->last_login = now();
        $this->save();
    }

    /**
     * Activate user account
     */
    public function activate(): bool
    {
        $this->is_active = true;
        return $this->save();
    }

    /**
     * Deactivate user account
     */
    public function deactivate(): bool
    {
        $this->is_active = false;
        return $this->save();
    }

    /**
     * Get full role name
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'ustadz' => 'Ustadz/Pengajar',
            'santri' => 'Santri',
            default => 'Unknown'
        };
    }

    // === SCOPES ===
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}