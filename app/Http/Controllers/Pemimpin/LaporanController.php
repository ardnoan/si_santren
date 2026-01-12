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
        
        // FIX: Rincian pemasukan per jenis
        $rincianPemasukan = $pembayaran->groupBy('jenis_pembayaran')->map(function($items, $key) {
            return (object)[
                'kategori' => ucfirst(str_replace('_', ' ', $key)),
                'total' => $items->sum('jumlah'),
                'count' => $items->count(),
            ];
        });
        
        // FIX: Rincian pengeluaran per kategori (sesuaikan nama variable)
        $rincianPengeluaran = $pengeluaran->groupBy('kategori')->map(function($items, $key) {
            return (object)[
                'kategori' => ucfirst(str_replace('_', ' ', $key)),
                'total' => $items->sum('jumlah'),
                'count' => $items->count(),
            ];
        });
        
        // Gabungkan semua transaksi untuk timeline
        $transaksi = collect()
            ->merge($pembayaran->map(function($item) {
                return [
                    'tanggal' => $item->tanggal_bayar,
                    'jenis' => 'Pemasukan',
                    'kategori' => ucfirst(str_replace('_', ' ', $item->jenis_pembayaran)),
                    'keterangan' => $item->santri->nama_lengkap ?? '-',
                    'jumlah' => $item->jumlah,
                    'tipe' => 'masuk'
                ];
            }))
            ->merge($pengeluaran->map(function($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'jenis' => 'Pengeluaran',
                    'kategori' => ucfirst(str_replace('_', ' ', $item->kategori)),
                    'keterangan' => $item->keterangan,
                    'jumlah' => $item->jumlah,
                    'tipe' => 'keluar'
                ];
            }))
            ->sortByDesc('tanggal')
            ->values();
        
        return view('pages.laporan.index', compact(
            'dari',
            'sampai',
            'pembayaran',
            'pengeluaran',
            'totalPemasukan',
            'totalPengeluaran',
            'selisih',
            'saldoTerkini',
            'rincianPemasukan',
            'rincianPengeluaran', // FIX: variable name sesuai view
            'transaksi'
        ));
    }
    
    public function export(Request $request)
    {
        // TODO: Export to Excel/PDF
        return back()->with('info', 'Fitur export akan segera tersedia.');
    }
}