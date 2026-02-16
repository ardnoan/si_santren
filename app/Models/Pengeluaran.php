<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    protected $table = 'pengeluarans';
    
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
    
    // =====================
    // RELATIONSHIPS
    // =====================
    
    /**
     * Relasi ke User (bendahara yang mengajukan)
     * ✅ PERBAIKAN: Tambahkan relasi ini
     */
    public function bendahara(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }
    
    /**
     * Alias untuk bendahara (untuk backward compatibility)
     * ✅ PERBAIKAN: View menggunakan $pengeluaran->user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }
    
    /**
     * Relasi ke User (pemimpin yang approve/reject)
     * ✅ PERBAIKAN: Tambahkan relasi ini
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    /**
     * Alias untuk approver (untuk backward compatibility)
     * ✅ PERBAIKAN: View menggunakan $pengeluaran->approvedBy
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    /**
     * Relasi polymorphic ke Kas
     */
    public function kas()
    {
        return $this->morphMany(Kas::class, 'referensi');
    }
    
    // =====================
    // SCOPES
    // =====================
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
    }
    
    public function scopeTahunIni($query)
    {
        return $query->whereYear('tanggal', now()->year);
    }
    
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
    
    // =====================
    // ACCESSORS & MUTATORS
    // =====================
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
    
    public function getFormattedJumlahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }
    
    // =====================
    // METHODS
    // =====================
    
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    
    public function canEdit()
    {
        return $this->status === 'pending';
    }
    
    public function canDelete()
    {
        return $this->status === 'pending';
    }
}