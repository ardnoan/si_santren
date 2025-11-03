<?php

namespace App\Repositories;

use App\Models\Santri;
use App\Models\User;

/**
 * Class SantriRepository
 * Repository untuk Santri data access
 */
class SantriRepository extends BaseRepository
{
    public function __construct(Santri $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all active santri with kelas relation
     */
    public function getAllAktifWithKelas()
    {
        return $this->model
            ->with(['kelas', 'user'])
            ->aktif()
            ->orderBy('nama_lengkap')
            ->paginate(15);
    }

    /**
     * Find santri with all relations
     */
    public function findWithRelations(int $id)
    {
        return $this->model
            ->with(['kelas', 'user', 'kehadiran', 'nilai', 'pembayaran'])
            ->findOrFail($id);
    }

    /**
     * Create santri with user account
     */
    public function createWithUser(array $santriData, array $userData): Santri
    {
        // Create user first
        $user = User::create($userData);
        
        // Create santri linked to user
        $santriData['user_id'] = $user->id;
        $santriData['nomor_induk'] = $this->generateNomorInduk();
        $santriData['tanggal_masuk'] = now();
        
        return $this->model->create($santriData);
    }

    /**
     * Generate nomor induk automatically
     */
    private function generateNomorInduk(): string
    {
        $year = date('Y');
        $lastSantri = $this->model
            ->where('nomor_induk', 'LIKE', $year . '%')
            ->orderBy('nomor_induk', 'desc')
            ->first();
        
        if ($lastSantri) {
            $lastNumber = intval(substr($lastSantri->nomor_induk, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $year . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Search santri by keyword
     */
    public function search(string $keyword)
    {
        return $this->model
            ->with(['kelas', 'user'])
            ->search($keyword)
            ->aktif()
            ->orderBy('nama_lengkap')
            ->paginate(15);
    }

    /**
     * Get santri by kelas
     */
    public function getByKelas(int $kelasId)
    {
        return $this->model
            ->with('user')
            ->byKelas($kelasId)
            ->aktif()
            ->orderBy('nama_lengkap')
            ->get();
    }

    /**
     * Count active santri
     */
    public function countAktif(): int
    {
        return $this->model->aktif()->count();
    }

    /**
     * Get new santri (registered this year)
     */
    public function getSantriBaru()
    {
        return $this->model
            ->aktif()
            ->whereYear('tanggal_masuk', date('Y'))
            ->get();
    }

    /**
     * Get santri who haven't paid SPP this month
     */
    public function getSantriBelumBayarSPP()
    {
        $currentMonth = date('Y-m');
        
        return $this->model
            ->aktif()
            ->whereDoesntHave('pembayaran', function($query) use ($currentMonth) {
                $query->where('jenis_pembayaran', 'spp')
                      ->where('bulan_bayar', $currentMonth)
                      ->where('status', 'lunas');
            })
            ->get();
    }

    /**
     * Get santri statistics by gender
     */
    public function getStatistikByGender(): array
    {
        return [
            'laki_laki' => $this->model->aktif()->byGender('L')->count(),
            'perempuan' => $this->model->aktif()->byGender('P')->count(),
        ];
    }
}