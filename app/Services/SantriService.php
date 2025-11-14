<?php

namespace App\Services;

use App\Repositories\SantriRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class SantriService
 * Implementasi: Single Responsibility Principle
 * Business logic untuk manajemen santri
 */
class SantriService
{
    protected $santriRepository;

    public function __construct(SantriRepository $santriRepository)
    {
        $this->santriRepository = $santriRepository;
    }

    /**
     * Get all santri aktif
     */
    public function getAllSantriAktif()
    {
        return $this->santriRepository->getAllAktifWithKelas();
    }

    /**
     * Get santri by ID
     */
    public function getSantriById(int $id)
    {
        return $this->santriRepository->findWithRelations($id);
    }

    /**
     * Create new santri with user account
     */
    public function createSantri(array $data)
    {
        DB::beginTransaction();

        try {
            // Prepare user data
            $userData = [
                'username' => $data['username'],
                'email' => $data['email'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => 'santri',
                'is_active' => true,
            ];

            // Prepare santri data
            $santriData = [
                'nama_lengkap' => $data['nama_lengkap'],
                'nama_panggilan' => $data['nama_panggilan'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'alamat' => $data['alamat'],
                'kelas_id' => $data['kelas_id'] ?? null,
                'nama_wali' => $data['nama_wali'],
                'no_telp_wali' => $data['no_telp_wali'],
                'status' => 'aktif',
            ];

            // Create santri with user
            $santri = $this->santriRepository->createWithUser($santriData, $userData);

            DB::commit();
            return $santri;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update santri
     */
    public function updateSantri(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $santri = $this->santriRepository->findById($id);

            // Update santri data
            $santriData = [
                'nama_lengkap' => $data['nama_lengkap'],
                'nama_panggilan' => $data['nama_panggilan'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'alamat' => $data['alamat'],
                'kelas_id' => $data['kelas_id'] ?? null,
                'nama_wali' => $data['nama_wali'],
                'no_telp_wali' => $data['no_telp_wali'],
            ];

            $santri = $this->santriRepository->update($id, $santriData);

            // Update user if needed
            if (isset($data['username']) || isset($data['email'])) {
                $user = $santri->user;
                if (isset($data['username'])) $user->username = $data['username'];
                if (isset($data['email'])) $user->email = $data['email'];
                if (isset($data['password'])) $user->password = Hash::make($data['password']);
                $user->save();
            }

            DB::commit();
            return $santri;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete santri
     */
    public function deleteSantri(int $id): bool
    {
        DB::beginTransaction();

        try {
            $santri = $this->santriRepository->findById($id);

            // Delete user (cascade will delete santri)
            $santri->user->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Graduate santri (mark as alumni)
     */
    public function luluskanSantri(int $id): bool
    {
        $santri = $this->santriRepository->findById($id);
        return $santri->graduateSantri();
    }

    /**
     * Get santri statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_aktif' => $this->santriRepository->countAktif(),
            'santri_baru' => $this->santriRepository->getSantriBaru()->count(),
            'belum_bayar_spp' => $this->santriRepository->getSantriBelumBayarSPP()->count(),
        ];
    }

    /**
     * Search santri
     */
    public function searchSantri(?string $keyword)
    {
        if (!$keyword) {
            return $this->santriRepository->getAllAktifWithKelas();
        }

        return $this->santriRepository->search($keyword);
    }


    /**
     * Get santri by kelas
     */
    public function getSantriByKelas(int $kelasId)
    {
        return $this->santriRepository->getByKelas($kelasId);
    }
}
