<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kategori',
        'jumlah',
        'keterangan',
        'bukti',
        'bendahara_id',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================
    
    public function bendahara()
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function kas()
    {
        return $this->morphOne(Kas::class, 'referensi');
    }

    // ==================== ACCESSORS ====================
    
    public function getJumlahFormatAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getKategoriLabelAttribute()
    {
        $labels = [
            'listrik' => 'Listrik',
            'air' => 'Air',
            'konsumsi' => 'Konsumsi',
            'gaji' => 'Gaji',
            'perawatan' => 'Perawatan',
            'transportasi' => 'Transportasi',
            'alat_tulis' => 'Alat Tulis',
            'kebersihan' => 'Kebersihan',
            'lainnya' => 'Lainnya',
        ];
        
        return $labels[$this->kategori] ?? $this->kategori;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];
        
        return $badges[$this->status] ?? 'secondary';
    }

    // ==================== SCOPES ====================
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', Carbon::now()->month)
                     ->whereYear('tanggal', Carbon::now()->year);
    }
}