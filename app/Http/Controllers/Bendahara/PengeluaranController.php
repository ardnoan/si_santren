<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with(['bendahara', 'approver']);
        
        // Filter by kategori
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        
        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by tanggal
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        
        // Filter by bulan
        if ($request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        $pengeluaran = $query->orderBy('tanggal', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate(20);
        
        return view('bendahara.pengeluaran.index', compact('pengeluaran'));
    }
    
    public function create()
    {
        return view('bendahara.pengeluaran.form');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'kategori' => 'required|in:listrik,air,konsumsi,gaji,perawatan,transportasi,alat_tulis,kebersihan,lainnya',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        DB::beginTransaction();
        try {
            // Upload bukti if exists
            if ($request->hasFile('bukti')) {
                $validated['bukti'] = $request->file('bukti')->store('pengeluaran', 'public');
            }
            
            // Create pengeluaran
            $validated['bendahara_id'] = auth()->id();
            $validated['status'] = 'pending';
            
            $pengeluaran = Pengeluaran::create($validated);
            
            DB::commit();
            
            return redirect()
                ->route('bendahara.pengeluaran.index')
                ->with('success', 'Data pengeluaran berhasil dicatat. Menunggu approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded file if exists
            if (isset($validated['bukti'])) {
                Storage::disk('public')->delete($validated['bukti']);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
    
    public function show($id)
    {
        $pengeluaran = Pengeluaran::with(['bendahara', 'approver'])->findOrFail($id);
        return view('bendahara.pengeluaran.show', compact('pengeluaran'));
    }
    
    public function edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        // Only can edit if pending
        if ($pengeluaran->status !== 'pending') {
            return back()->with('error', 'Tidak dapat mengedit pengeluaran yang sudah di-approve/reject.');
        }
        
        return view('bendahara.pengeluaran.form', compact('pengeluaran'));
    }
    
    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        // Only can update if pending
        if ($pengeluaran->status !== 'pending') {
            return back()->with('error', 'Tidak dapat mengubah pengeluaran yang sudah di-approve/reject.');
        }
        
        $validated = $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'kategori' => 'required|in:listrik,air,konsumsi,gaji,perawatan,transportasi,alat_tulis,kebersihan,lainnya',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        DB::beginTransaction();
        try {
            // Upload new bukti if exists
            if ($request->hasFile('bukti')) {
                // Delete old bukti
                if ($pengeluaran->bukti) {
                    Storage::disk('public')->delete($pengeluaran->bukti);
                }
                
                $validated['bukti'] = $request->file('bukti')->store('pengeluaran', 'public');
            }
            
            $pengeluaran->update($validated);
            
            DB::commit();
            
            return redirect()
                ->route('bendahara.pengeluaran.index')
                ->with('success', 'Data pengeluaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        // Only can delete if pending
        if ($pengeluaran->status !== 'pending') {
            return back()->with('error', 'Tidak dapat menghapus pengeluaran yang sudah di-approve/reject.');
        }
        
        DB::beginTransaction();
        try {
            // Delete bukti file
            if ($pengeluaran->bukti) {
                Storage::disk('public')->delete($pengeluaran->bukti);
            }
            
            $pengeluaran->delete();
            
            DB::commit();
            
            return redirect()
                ->route('bendahara.pengeluaran.index')
                ->with('success', 'Data pengeluaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}