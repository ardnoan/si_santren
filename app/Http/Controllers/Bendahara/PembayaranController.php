<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['santri', 'admin']);
        
        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            // Default: show pending first
            $query->orderByRaw("FIELD(status, 'pending', 'lunas', 'dibatalkan')");
        }
        
        // Filter by jenis
        if ($request->jenis) {
            $query->where('jenis_pembayaran', $request->jenis);
        }
        
        // Filter by tanggal
        if ($request->tanggal) {
            $query->whereDate('tanggal_bayar', $request->tanggal);
        }
        
        // Search santri
        if ($request->search) {
            $query->whereHas('santri', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_induk', 'like', '%' . $request->search . '%');
            });
        }
        
        $pembayaran = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistics
        $totalPending = Pembayaran::where('status', 'pending')->count();
        $totalPendingNominal = Pembayaran::where('status', 'pending')->sum('jumlah');
        
        return view('pembayaran.index', compact(
            'pembayaran',
            'totalPending',
            'totalPendingNominal'
        ));
    }
    
    public function verifikasi(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        if ($pembayaran->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diverifikasi.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:lunas,dibatalkan',
            'catatan' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        try {
            // Update status
            $pembayaran->update([
                'status' => $validated['status'],
                'admin_id' => auth()->id(), // Update verifikator
            ]);
            
            // Jika disetujui (lunas), catat ke kas
            if ($validated['status'] === 'lunas') {
                $saldoSebelum = Kas::getSaldoTerkini();
                
                Kas::create([
                    'tanggal' => now(),
                    'jenis' => 'masuk',
                    'kategori' => 'pembayaran_santri',
                    'jumlah' => $pembayaran->jumlah,
                    'saldo' => $saldoSebelum + $pembayaran->jumlah,
                    'keterangan' => 'Pembayaran ' . $pembayaran->jenis_pembayaran . ' - ' . $pembayaran->santri->nama_lengkap . ($validated['catatan'] ? ' | ' . $validated['catatan'] : ''),
                    'referensi_tipe' => Pembayaran::class,
                    'referensi_id' => $pembayaran->id,
                    'user_id' => auth()->id(),
                ]);
            }
            
            DB::commit();
            
            $message = $validated['status'] === 'lunas' 
                ? 'Pembayaran berhasil diverifikasi dan dicatat ke kas.'
                : 'Pembayaran berhasil dibatalkan.';
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi: ' . $e->getMessage());
        }
    }
}