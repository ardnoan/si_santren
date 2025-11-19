<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::with('kelas');
        
        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_induk', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by kelas
        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        $santri = $query->orderBy('nama_lengkap')->paginate(20);
        
        return view('pemimpin.santri.index', compact('santri'));
    }
    
    public function show($id)
    {
        $santri = Santri::with(['kelas', 'user'])->findOrFail($id);
        return view('pemimpin.santri.show', compact('santri'));
    }
}