<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount('santris')
            ->with('waliKelas')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate(15);
        
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $ustadz = User::byRole('ustadz')->orderBy('username')->get();
        return view('admin.kelas.create', compact('ustadz'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:64',
            'tingkat' => 'required|integer|min:1',
            'tahun_ajaran' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'wali_kelas' => 'nullable|exists:users,id',
        ]);

        try {
            Kelas::create($request->all());
            
            return redirect()->route('admin.kelas.index')
                ->with('success', 'Kelas berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kelas: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $kelas = Kelas::with(['santris', 'waliKelas'])->findOrFail($id);
        return view('admin.kelas.show', compact('kelas'));
    }

    public function edit(int $id)
    {
        $kelas = Kelas::findOrFail($id);
        $ustadz = User::byRole('ustadz')->orderBy('username')->get();
        
        return view('admin.kelas.edit', compact('kelas', 'ustadz'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:64',
            'tingkat' => 'required|integer|min:1',
            'tahun_ajaran' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'wali_kelas' => 'nullable|exists:users,id',
        ]);

        try {
            $kelas = Kelas::findOrFail($id);
            $kelas->update($request->all());
            
            return redirect()->route('admin.kelas.index')
                ->with('success', 'Kelas berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update kelas: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            Kelas::findOrFail($id)->delete();
            
            return redirect()->route('admin.kelas.index')
                ->with('success', 'Kelas berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal hapus kelas: ' . $e->getMessage());
        }
    }
}