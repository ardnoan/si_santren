<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['santri', 'admin']);
        
        // Search
        if ($request->search) {
            $query->whereHas('santri', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_induk', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by jenis
        if ($request->jenis) {
            $query->where('jenis_pembayaran', $request->jenis);
        }
        
        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by tanggal
        if ($request->tanggal) {
            $query->whereDate('tanggal_bayar', $request->tanggal);
        }
        
        $pembayaran = $query->orderBy('tanggal_bayar', 'desc')->paginate(20);
        
        return view('pemimpin.pembayaran.index', compact('pembayaran'));
    }
}