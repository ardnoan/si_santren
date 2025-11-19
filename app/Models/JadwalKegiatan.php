<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'jenis',
        'frekuensi',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'penanggung_jawab',
        'deskripsi',
        'is_wajib',
        'is_active',
    ];

    protected $casts = [
        'is_wajib' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================
    
    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab');
    }

    // ==================== ACCESSORS ====================
    
    public function getJenisLabelAttribute()
    {
        $labels = [
            'ibadah' => 'Ibadah',
            'akademik' => 'Akademik',
            'kebersihan' => 'Kebersihan',
            'olahraga' => 'Olahraga',
            'lainnya' => 'Lainnya',
        ];
        
        return $labels[$this->jenis] ?? $this->jenis;
    }

    // ==================== SCOPES ====================
    
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWajib($query)
    {
        return $query->where('is_wajib', true);
    }

    public function scopeHariIni($query)
    {
        $hari = now()->locale('id')->dayName; // Senin, Selasa, etc.
        return $query->where(function($q) use ($hari) {
            $q->where('hari', $hari)
              ->orWhere('hari', 'Setiap Hari')
              ->orWhere('frekuensi', 'harian');
        });
    }
}