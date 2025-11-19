<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Kehadiran;
use App\Models\Nilai;
use App\Models\Pembayaran;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index()
    {
        // Statistik Santri
        $statsSantri = [
            'total' => Santri::count(),
            'aktif' => Santri::where('status', 'aktif')->count(),
            'cuti' => Santri::where('status', 'cuti')->count(),
            'lulus' => Santri::where('status', 'lulus')->count(),
            'keluar' => Santri::where('status', 'keluar')->count(),
            'laki' => Santri::where('jenis_kelamin', 'L')->count(),
            'perempuan' => Santri::where('jenis_kelamin', 'P')->count(),
        ];
        
        // Statistik Kehadiran (Bulan Ini)
        $statsKehadiran = Kehadiran::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();
        
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
        
        // Statistik Pembayaran (12 Bulan Terakhir)
        $statsPembayaran = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $total = Pembayaran::where('status', 'lunas')
                ->whereMonth('tanggal_bayar', $bulan->month)
                ->whereYear('tanggal_bayar', $bulan->year)
                ->sum('jumlah');
            
            $statsPembayaran[] = [
                'bulan' => $bulan->format('M Y'),
                'total' => $total,
            ];
        }
        
        return view('pemimpin.statistik.index', compact(
            'statsSantri',
            'statsKehadiran',
            'statsNilai',
            'statsPembayaran'
        ));
    }
}