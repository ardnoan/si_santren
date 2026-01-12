<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Kehadiran;
use App\Models\Nilai;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Kelas;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index()
    {
        // FIX: Kirim variable langsung (bukan dalam array)
        $totalSantri = Santri::count();
        $santriAktif = Santri::where('status', 'aktif')->count();
        $santriLaki = Santri::where('jenis_kelamin', 'L')->count();
        $santriPerempuan = Santri::where('jenis_kelamin', 'P')->count();
        $santriCuti = Santri::where('status', 'cuti')->count();
        $santriLulus = Santri::where('status', 'lulus')->count();
        $santriKeluar = Santri::where('status', 'keluar')->count();
        
        // FIX: Santri per kelas untuk chart
        $santriPerKelas = Kelas::withCount('santris')
            ->orderBy('nama_kelas')
            ->get()
            ->map(function($kelas) {
                return [
                    'kelas' => $kelas->nama_kelas,
                    'jumlah' => $kelas->santris_count
                ];
            });
        
        // Ubah format untuk Chart.js
        $santriPerKelasChart = [
            'labels' => $santriPerKelas->pluck('kelas')->toArray(),
            'values' => $santriPerKelas->pluck('jumlah')->toArray(),
        ];
        
        // FIX: Statistik Kehadiran (Bulan Ini)
        $kehadiranStats = Kehadiran::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();
        
        // FIX: Trend Pemasukan (6 Bulan)
        $pemasukanTrend = $this->getPemasukanTrend();
        
        // FIX: Trend Pengeluaran (6 Bulan)
        $pengeluaranTrend = $this->getPengeluaranTrend();
        
        // Statistik Nilai
        $statsNilai = [
            'rata_rata' => Nilai::avg('nilai_akhir'),
            'tertinggi' => Nilai::max('nilai_akhir'),
            'terendah' => Nilai::min('nilai_akhir'),
            'predikat_A' => Nilai::where('predikat', 'A')->count(),
            'predikat_B' => Nilai::where('predikat', 'B')->count(),
            'predikat_C' => Nilai::where('predikat', 'C')->count(),
            'predikat_D' => Nilai::where('predikat', 'D')->count(),
            'predikat_E' => Nilai::where('predikat', 'E')->count(),
        ];
        
        return view('pages.statistik.index', compact(
            'totalSantri',
            'santriAktif',
            'santriLaki',
            'santriPerempuan',
            'santriCuti',
            'santriLulus',
            'santriKeluar',
            'santriPerKelas',
            'kehadiranStats',
            'pemasukanTrend',
            'pengeluaranTrend',
            'statsNilai'
        ));
    }
    
    private function getPemasukanTrend()
    {
        $labels = [];
        $values = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $labels[] = $bulan->format('M Y');
            
            $values[] = Pembayaran::where('status', 'lunas')
                ->whereMonth('tanggal_bayar', $bulan->month)
                ->whereYear('tanggal_bayar', $bulan->year)
                ->sum('jumlah');
        }
        
        return compact('labels', 'values');
    }
    
    private function getPengeluaranTrend()
    {
        $labels = [];
        $values = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $labels[] = $bulan->format('M Y');
            
            $values[] = Pengeluaran::where('status', 'approved')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');
        }
        
        return compact('labels', 'values');
    }
}