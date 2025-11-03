<?php

namespace App\Http\Controllers;

use App\Services\SantriService;
use App\Services\PembayaranService;
use App\Models\Kehadiran;
use App\Models\Kelas;
use Illuminate\Http\Request;

/**
 * DashboardController
 * Menampilkan statistik dan dashboard utama
 */
class DashboardController extends Controller
{
    protected $santriService;
    protected $pembayaranService;

    public function __construct(
        SantriService $santriService,
        PembayaranService $pembayaranService
    ) {
        $this->middleware('auth');
        $this->santriService = $santriService;
        $this->pembayaranService = $pembayaranService;
    }

    public function index()
    {
        // Statistik Santri
        $santriStats = $this->santriService->getStatistics();
        
        // Statistik Pembayaran
        $pembayaranStats = $this->pembayaranService->getStatistics();
        
        // Statistik Kehadiran Hari Ini
        $kehadiranToday = Kehadiran::byTanggal(today())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Data Kelas
        $totalKelas = Kelas::count();
        
        // Chart Data - Santri per Kelas
        $santriPerKelas = Kelas::withCount(['santris' => function($query) {
            $query->aktif();
        }])->get()->map(function($kelas) {
            return [
                'kelas' => $kelas->nama_kelas,
                'jumlah' => $kelas->santris_count
            ];
        });
        
        // Recent Activities (5 pembayaran terakhir)
        $recentPembayaran = \App\Models\Pembayaran::with(['santri', 'admin'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact( 
            'santriStats',
            'pembayaranStats',
            'kehadiranToday',
            'totalKelas',
            'santriPerKelas',
            'recentPembayaran'
        ));
    }
}