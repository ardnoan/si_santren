<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kas::with(['user', 'referensi']);
        
        // Filter by jenis
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }
        
        // Filter by tanggal
        if ($request->dari && $request->sampai) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        } elseif ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        
        // Filter by bulan
        if ($request->bulan) {
            $bulan = Carbon::parse($request->bulan);
            $query->whereMonth('tanggal', $bulan->month)
                  ->whereYear('tanggal', $bulan->year);
        }
        
        $kas = $query->orderBy('tanggal', 'desc')
                    ->orderBy('id', 'desc')
                    ->paginate(50);
        
        // Statistics
        $saldoTerkini = Kas::getSaldoTerkini();
        
        $pemasukanBulanIni = Kas::masuk()
            ->bulanIni()
            ->sum('jumlah');
        
        $pengeluaranBulanIni = Kas::keluar()
            ->bulanIni()
            ->sum('jumlah');
        
        return view('kas.index', compact(
            'kas',
            'saldoTerkini',
            'pemasukanBulanIni',
            'pengeluaranBulanIni'
        ));
    }
    
    public function show($id)
    {
        $kas = Kas::with(['user', 'referensi'])->findOrFail($id);
        return view('kas.show', compact('kas'));
    }
}