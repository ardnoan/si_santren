<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Kas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default: bulan ini
        $dari = $request->dari ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? Carbon::now()->format('Y-m-d');
        
        // Pembayaran (Pemasukan)
        $pembayaran = Pembayaran::with('santri')
            ->where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$dari, $sampai])
            ->orderBy('tanggal_bayar', 'desc')
            ->get();
        
        $totalPemasukan = $pembayaran->sum('jumlah');
        
        // Pengeluaran
        $pengeluaran = Pengeluaran::with('bendahara')
            ->where('status', 'approved')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        
        // Summary
        $selisih = $totalPemasukan - $totalPengeluaran;
        $saldoTerkini = Kas::getSaldoTerkini();
        
        // Breakdown per kategori pengeluaran
        $pengeluaranPerKategori = $pengeluaran->groupBy('kategori')->map(function($items) {
            return [
                'jumlah' => $items->sum('jumlah'),
                'count' => $items->count(),
            ];
        });
        
        return view('pemimpin.laporan.index', compact(
            'dari',
            'sampai',
            'pembayaran',
            'pengeluaran',
            'totalPemasukan',
            'totalPengeluaran',
            'selisih',
            'saldoTerkini',
            'pengeluaranPerKategori'
        ));
    }
    
    public function export(Request $request)
    {
        // TODO: Export to Excel/PDF
        return back()->with('info', 'Fitur export akan segera tersedia.');
    }
}