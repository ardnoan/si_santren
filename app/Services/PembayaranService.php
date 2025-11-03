<?php

namespace App\Services;

use App\Repositories\PembayaranRepository;
use App\Repositories\SantriRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class PembayaranService
 * Business logic untuk manajemen pembayaran
 */
class PembayaranService
{
    protected $pembayaranRepo;
    protected $santriRepo;

    public function __construct(
        PembayaranRepository $pembayaranRepo,
        SantriRepository $santriRepo
    ) {
        $this->pembayaranRepo = $pembayaranRepo;
        $this->santriRepo = $santriRepo;
    }

    /**
     * Get all pembayaran with pagination
     */
    public function getAllPembayaran()
    {
        return $this->pembayaranRepo->paginate(15);
    }

    /**
     * Get pembayaran by santri
     */
    public function getPembayaranBySantri(int $santriId)
    {
        return $this->pembayaranRepo->getBySantri($santriId);
    }

    /**
     * Create new pembayaran
     */
    public function createPembayaran(array $data)
    {
        DB::beginTransaction();
        
        try {
            // Handle file upload if exists
            if (isset($data['bukti_transfer']) && $data['bukti_transfer']) {
                $data['bukti_transfer'] = $data['bukti_transfer']
                    ->store('pembayaran/bukti', 'public');
            }
            
            // Set admin_id from authenticated user
            $data['admin_id'] = auth()->id();
            
            // Set default status if not provided
            if (!isset($data['status'])) {
                $data['status'] = 'lunas';
            }
            
            $pembayaran = $this->pembayaranRepo->create($data);
            
            DB::commit();
            return $pembayaran;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update pembayaran
     */
    public function updatePembayaran(int $id, array $data)
    {
        DB::beginTransaction();
        
        try {
            $pembayaran = $this->pembayaranRepo->findById($id);
            
            // Handle file upload if exists
            if (isset($data['bukti_transfer']) && $data['bukti_transfer']) {
                // Delete old file
                if ($pembayaran->bukti_transfer) {
                    Storage::disk('public')->delete($pembayaran->bukti_transfer);
                }
                
                $data['bukti_transfer'] = $data['bukti_transfer']
                    ->store('pembayaran/bukti', 'public');
            }
            
            $pembayaran = $this->pembayaranRepo->update($id, $data);
            
            DB::commit();
            return $pembayaran;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete pembayaran
     */
    public function deletePembayaran(int $id): bool
    {
        DB::beginTransaction();
        
        try {
            $pembayaran = $this->pembayaranRepo->findById($id);
            
            // Delete bukti file if exists
            if ($pembayaran->bukti_transfer) {
                Storage::disk('public')->delete($pembayaran->bukti_transfer);
            }
            
            $result = $this->pembayaranRepo->delete($id);
            
            DB::commit();
            return $result;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mark pembayaran as lunas
     */
    public function markAsLunas(int $id): bool
    {
        $pembayaran = $this->pembayaranRepo->findById($id);
        return $pembayaran->markAsLunas();
    }

    /**
     * Get pembayaran statistics
     */
    public function getStatistics(): array
    {
        return $this->pembayaranRepo->getStatistics();
    }

    /**
     * Generate SPP otomatis untuk semua santri aktif
     */
    public function generateSPPBulanIni(): array
    {
        $bulanIni = date('Y-m');
        $santriAktif = $this->santriRepo->getAll();
        $generated = 0;
        $skipped = 0;
        
        DB::beginTransaction();
        
        try {
            foreach ($santriAktif as $santri) {
                // Check if already paid this month
                if (!$this->pembayaranRepo->cekSPPBulan($santri->id, $bulanIni)) {
                    $this->pembayaranRepo->create([
                        'santri_id' => $santri->id,
                        'jenis_pembayaran' => 'spp',
                        'jumlah' => 500000, // Default SPP amount
                        'tanggal_bayar' => date('Y-m-d'),
                        'bulan_bayar' => $bulanIni,
                        'metode_pembayaran' => 'tunai',
                        'status' => 'pending',
                        'admin_id' => auth()->id(),
                    ]);
                    $generated++;
                } else {
                    $skipped++;
                }
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'generated' => $generated,
                'skipped' => $skipped,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get laporan pembayaran
     */
    public function getLaporan(array $filters = [])
    {
        return $this->pembayaranRepo->getLaporan($filters);
    }
}