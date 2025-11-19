<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Kas;
use App\Models\Nilai;
use App\Models\Kehadiran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Santri
        $totalSantri = Santri::aktif()->count();
        
        // Saldo Kas
        $saldoKas = Kas::getSaldoTerkini();
        
        // Rata-rata Nilai
        $rataRataNilai = Nilai::avg('nilai_akhir');
        
        // Kehadiran Hari Ini
        $kehadiranHariIni = Kehadiran::whereDate('tanggal', Carbon::today())
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();
        
        $totalKehadiranHariIni = array_sum($kehadiranHariIni);
        $persentaseKehadiran = $totalSantri > 0 
            ? round((($kehadiranHariIni['hadir'] ?? 0) / $totalSantri) * 100)
            : 0;
        
        // Chart Data: Keuangan 6 Bulan
        $chartKeuangan = $this->getChartKeuangan();
        
        // Chart Data: Status Santri
        $statusSantri = Santri::selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();
        
        return view('pemimpin.dashboard', compact(
            'totalSantri',
            'saldoKas',
            'rataRataNilai',
            'kehadiranHariIni',
            'totalKehadiranHariIni',
            'persentaseKehadiran',
            'chartKeuangan',
            'statusSantri'
        ));
    }
    
    private function getChartKeuangan()
    {
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            
            $pemasukan = Kas::masuk()
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');
            
            $pengeluaran = Kas::keluar()
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');
            
            $data[] = [
                'bulan' => $bulan->format('M Y'),
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
            ];
        }
        
        return $data;
    }
}