<?php

namespace App\Repositories;

use App\Models\Pembayaran;

class PembayaranRepository extends BaseRepository
{
    public function __construct(Pembayaran $model)
    {
        parent::__construct($model);
    }

    public function getBySantri(int $santriId)
    {
        return $this->model
            ->with(['santri', 'admin'])
            ->bySantri($santriId)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();
    }

    public function getStatistics(): array
    {
        return [
            'total_pembayaran' => $this->model->lunas()->sum('jumlah'),
            'pending' => $this->model->where('status', 'pending')->count(),
            'lunas_bulan_ini' => $this->model->lunas()->bulanIni()->count(),
        ];
    }

    public function cekSPPBulan(int $santriId, string $bulan): bool
    {
        return $this->model
            ->where('santri_id', $santriId)
            ->where('jenis_pembayaran', 'spp')
            ->where('bulan_bayar', $bulan)
            ->where('status', 'lunas')
            ->exists();
    }

    public function getLaporan(array $filters = [])
    {
        $query = $this->model->with(['santri', 'admin']);

        if (isset($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_bayar', '>=', $filters['tanggal_mulai']);
        }

        if (isset($filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_bayar', '<=', $filters['tanggal_selesai']);
        }

        if (isset($filters['jenis'])) {
            $query->byJenis($filters['jenis']);
        }

        return $query->orderBy('tanggal_bayar', 'desc')->get();
    }
}