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

    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    public function isPemimpin(): bool
    {
        return $this->role === 'pemimpin';
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
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'bendahara_id');
    }

    public function pengeluaranApproved()
    {
        return $this->hasMany(Pengeluaran::class, 'approved_by');
    }

    public function kas()
    {
        return $this->hasMany(Kas::class);
    }

    public function jadwalKegiatanDiampu()
    {
        return $this->hasMany(JadwalKegiatan::class, 'penanggung_jawab');
    }

    /**
     * Get full role name
     */
    public function getRoleNameAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'ustadz' => 'Ustadz/Pengajar',
            'santri' => 'Santri',
            'bendahara' => 'Bendahara',
            'pemimpin' => 'Pemimpin',
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
