<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Santri
 * Model untuk data santri
 * Implementasi: Eloquent ORM & Relationships
 */
class Santri extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'santris';

    protected $fillable = [
        'user_id',
        'nomor_induk',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'kelas_id',
        'nama_wali',
        'no_telp_wali',
        'tanggal_masuk',
        'tanggal_keluar',
        'status',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // === BUSINESS METHODS ===

    /**
     * Get full name with nickname
     */
    public function getFullNameAttribute(): string
    {
        return $this->nama_lengkap . ($this->nama_panggilan ? " ({$this->nama_panggilan})" : '');
    }

    /**
     * Get age from birth date
     */
    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }

    /**
     * Check if santri is active
     */
    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Graduate santri
     */
    public function graduateSantri(): bool
    {
        $this->status = 'lulus';
        $this->tanggal_keluar = now();
        return $this->save();
    }

    /**
     * Get gender label
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'success',
            'cuti' => 'warning',
            'lulus' => 'info',
            'keluar' => 'danger',
            default => 'secondary'
        };
    }

    // === SCOPES ===

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByKelas($query, int $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByGender($query, string $gender)
    {
        return $query->where('jenis_kelamin', $gender);
    }

    public function scopeSearch($query, $keyword)
    {
        if (!$keyword) return $query;

        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_lengkap', 'like', "%{$keyword}%")
                ->orWhere('nomor_induk', 'like', "%{$keyword}%")
                ->orWhere('nama_panggilan', 'like', "%{$keyword}%")
                ->orWhere('status', 'like', "%{$keyword}%")
                ->orWhereHas('kelas', function ($k) use ($keyword) {
                    $k->where('nama_kelas', 'like', "%{$keyword}%");
                })
                ->orWhereHas('user', function ($u) use ($keyword) {
                    $u->where('username', 'like', "%{$keyword}%");
                });

            // Tambahkan match untuk gender tulisannya
            if (str_contains(strtolower($keyword), 'perempuan') || strtolower($keyword) === 'p') {
                $q->orWhere('jenis_kelamin', 'P');
            }

            if (str_contains(strtolower($keyword), 'laki') || strtolower($keyword) === 'l') {
                $q->orWhere('jenis_kelamin', 'L');
            }
        });
    }
}
