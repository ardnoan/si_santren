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
        $query = Kas::with(['user']);
        
        // Filter by jenis
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }
        
        // Filter by kategori
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        
        // Filter by date range
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
        
        // ✅ PERBAIKAN: Ganti variable 'kas' menjadi 'transaksi' sesuai dengan view
        $transaksi = $query->orderBy('tanggal', 'desc')
                          ->orderBy('id', 'desc')
                          ->paginate(50);
        
        // Get latest saldo (dari transaksi terakhir)
        $latestKas = Kas::orderBy('tanggal', 'desc')
                       ->orderBy('id', 'desc')
                       ->first();
        
        $saldoTerkini = $latestKas ? $latestKas->saldo : 0;
        
        // Statistics bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $pemasukanBulanIni = Kas::where('jenis', 'masuk')
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->sum('jumlah');
        
        $pengeluaranBulanIni = Kas::where('jenis', 'keluar')
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->sum('jumlah');
        
        return view('pages.kas.index', compact(
            'transaksi',
            'saldoTerkini',
            'pemasukanBulanIni',
            'pengeluaranBulanIni'
        ));
    }
    
    public function show($id)
    {
        $kas = Kas::with(['user'])->findOrFail($id);
        
        // Get related referensi if exists
        if ($kas->referensi_tipe && $kas->referensi_id) {
            $kas->load('referensi');
        }
        
        return view('pages.kas.show', compact('kas'));
    }
    
    /**
     * Manual entry kas (untuk donasi, infak, dll)
     */
    public function create()
    {
        return view('pages.kas.form');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'jenis' => 'required|in:masuk,keluar',
            'kategori' => 'required|in:saldo_awal,pembayaran_santri,infak,donasi,pengeluaran_operasional,gaji,lainnya',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:500',
        ]);
        
        DB::beginTransaction();
        try {
            // Get saldo terakhir
            $latestKas = Kas::orderBy('tanggal', 'desc')
                           ->orderBy('id', 'desc')
                           ->first();
            
            $saldoSebelumnya = $latestKas ? $latestKas->saldo : 0;
            
            // Hitung saldo baru
            if ($validated['jenis'] == 'masuk') {
                $saldoBaru = $saldoSebelumnya + $validated['jumlah'];
            } else {
                $saldoBaru = $saldoSebelumnya - $validated['jumlah'];
                
                // Validasi saldo tidak boleh minus
                if ($saldoBaru < 0) {
                    return back()
                        ->withInput()
                        ->with('error', 'Saldo tidak mencukupi untuk transaksi ini.');
                }
            }
            
            // Create kas entry
            $validated['saldo'] = $saldoBaru;
            $validated['user_id'] = auth()->id();
            
            Kas::create($validated);
            
            DB::commit();
            
            return redirect()
                ->route('bendahara.kas.index')
                ->with('success', 'Transaksi kas berhasil dicatat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
}