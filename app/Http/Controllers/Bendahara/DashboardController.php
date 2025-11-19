<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Kas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Saldo Kas Terkini
        $saldoKas = Kas::getSaldoTerkini();
        
        // Pemasukan Bulan Ini
        $pemasukanBulanIni = Pembayaran::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
            ->sum('jumlah');
        
        // Pengeluaran Bulan Ini
        $pengeluaranBulanIni = Pengeluaran::where('status', 'approved')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');
        
        // Pending Approval
        $pendingApproval = Pengeluaran::pending()->count();
        
        // Transaksi Terbaru (5 terakhir)
        $transaksiTerbaru = Kas::with('user')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
        
        // Data untuk Chart (6 bulan terakhir)
        $chartData = $this->getChartData();
        
        return view('bendahara.dashboard', compact(
            'saldoKas',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'pendingApproval',
            'transaksiTerbaru',
            'chartData'
        ));
    }
    
    private function getChartData()
    {
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            
            $pemasukan = Pembayaran::where('status', 'lunas')
                ->whereMonth('tanggal_bayar', $bulan->month)
                ->whereYear('tanggal_bayar', $bulan->year)
                ->sum('jumlah');
            
            $pengeluaran = Pengeluaran::where('status', 'approved')
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