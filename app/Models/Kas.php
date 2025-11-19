<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kas extends Model
{
    use HasFactory;

    protected $table = 'kas';

    protected $fillable = [
        'tanggal',
        'jenis',
        'kategori',
        'jumlah',
        'saldo',
        'keterangan',
        'referensi_tipe',
        'referensi_id',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'saldo' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referensi()
    {
        return $this->morphTo();
    }

    // ==================== ACCESSORS ====================
    
    public function getJumlahFormatAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getSaldoFormatAttribute()
    {
        return 'Rp ' . number_format($this->saldo, 0, ',', '.');
    }

    // ==================== SCOPES ====================
    
    public function scopeMasuk($query)
    {
        return $query->where('jenis', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('jenis', 'keluar');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', Carbon::now()->month)
                     ->whereYear('tanggal', Carbon::now()->year);
    }

    // ==================== STATIC METHODS ====================
    
    public static function getSaldoTerkini()
    {
        $lastKas = self::orderBy('tanggal', 'desc')
                       ->orderBy('id', 'desc')
                       ->first();
        
        return $lastKas ? $lastKas->saldo : 0;
    }

    public static function hitungSaldo($tanggal = null)
    {
        $query = self::query();
        
        if ($tanggal) {
            $query->where('tanggal', '<=', $tanggal);
        }
        
        $pemasukan = $query->clone()->where('jenis', 'masuk')->sum('jumlah');
        $pengeluaran = $query->clone()->where('jenis', 'keluar')->sum('jumlah');
        
        return $pemasukan - $pengeluaran;
    }
}