<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'tahun_ajaran',
        'kapasitas',
        'wali_kelas',
    ];

    // Relationships
    public function santris()
    {
        return $this->hasMany(Santri::class, 'kelas_id');
    }

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas');
    }

    // Scopes
    public function scopeByTahunAjaran($query, $tahun)
    {
        return $query->where('tahun_ajaran', $tahun);
    }
}