<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pembayaran
 * Business Logic untuk transaksi keuangan santri
 */
class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'santri_id',
        'jenis_pembayaran',
        'jumlah',
        'tanggal_bayar',
        'bulan_bayar',
        'metode_pembayaran',
        'status',
        'bukti_transfer',
        'keterangan',
        'admin_id',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah' => 'decimal:2',
    ];

    // === RELATIONSHIPS ===
    
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // === BUSINESS METHODS ===
    
    /**
     * Mark payment as lunas
     */
    public function markAsLunas(): bool
    {
        $this->status = 'lunas';
        return $this->save();
    }

    /**
     * Check if payment is completed
     */
    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    /**
     * Get formatted amount
     */
    public function getJumlahFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Get jenis pembayaran label
     */
    public function getJenisPembayaranLabelAttribute(): string
    {
        return match($this->jenis_pembayaran) {
            'spp' => 'SPP Bulanan',
            'pendaftaran' => 'Biaya Pendaftaran',
            'seragam' => 'Seragam',
            'lainnya' => 'Lainnya',
            default => $this->jenis_pembayaran
        };
    }

    // === SCOPES ===
    
    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeBySantri($query, int $santriId)
    {
        return $query->where('santri_id', $santriId);
    }

    public function scopeBulanIni($query)
    {
        return $query->where('bulan_bayar', date('Y-m'));
    }

    public function scopeByJenis($query, string $jenis)
    {
        return $query->where('jenis_pembayaran', $jenis);
    }
}