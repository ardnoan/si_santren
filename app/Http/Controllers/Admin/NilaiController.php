<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Nilai::with(['santri', 'mataPelajaran']);

        if ($request->has('semester')) {
            $query->bySemester($request->semester);
        }

        if ($request->has('tahun_ajaran')) {
            $query->byTahunAjaran($request->tahun_ajaran);
        }

        $nilai = $query->paginate(20);

        return view('admin.nilai.index', compact('nilai'));
    }

    public function create()
    {
        $santri = Santri::aktif()->orderBy('nama_lengkap')->get();
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        return view('admin.nilai.create', compact('santri', 'mapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'semester' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $nilai = new Nilai($request->all());

            // Hitung nilai akhir
            $nilai->nilai_akhir = $nilai->hitungNilaiAkhir();

            // Tentukan predikat
            $nilai->predikat = $nilai->tentukanPredikat();

            $nilai->save();

            return redirect()->route('admin.nilai.index')
                ->with('success', 'Nilai berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Display nilai by santri
     */
    public function bySantri(int $santriId)
    {
        $santri = \App\Models\Santri::findOrFail($santriId);
        $nilai = \App\Models\Nilai::with('mataPelajaran')
            ->where('santri_id', $santriId)
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('admin.nilai.show', compact('santri', 'nilai'));
    }

    /**
     * Remove nilai
     */
    public function destroy(int $id)
    {
        try {
            \App\Models\Nilai::findOrFail($id)->delete();

            return redirect()->route('admin.nilai.index')
                ->with('success', 'Nilai berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal hapus nilai: ' . $e->getMessage());
        }
    }
    /**
     * Show edit form
     */
    public function edit(int $id)
    {
        $nilai = \App\Models\Nilai::with(['santri', 'mataPelajaran'])->findOrFail($id);
        $santri = \App\Models\Santri::aktif()->orderBy('nama_lengkap')->get();
        $mapel = \App\Models\MataPelajaran::orderBy('nama_mapel')->get();

        return view('admin.nilai.edit', compact('nilai', 'santri', 'mapel'));
    }

    /**
     * Update nilai
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'semester' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $nilai = \App\Models\Nilai::findOrFail($id);
            $nilai->fill($request->all());
            $nilai->nilai_akhir = $nilai->hitungNilaiAkhir();
            $nilai->predikat = $nilai->tentukanPredikat();
            $nilai->save();

            return redirect()->route('admin.nilai.index')
                ->with('success', 'Nilai berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update nilai: ' . $e->getMessage());
        }
    }
}
