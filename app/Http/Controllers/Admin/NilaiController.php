<?php
// =============================================
// FILE 4: app/Http/Controllers/Admin/NilaiController.php (Updated)
// =============================================

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

        return view('pages.nilai.index', compact('nilai'));
    }

    public function create()
    {
        $santri = Santri::aktif()->orderBy('nama_lengkap')->get();
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        return view('pages.nilai.form', compact('santri', 'mapel'));
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
            $nilai->nilai_akhir = $nilai->hitungNilaiAkhir();
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

    public function edit(int $id)
    {
        $nilai = Nilai::with(['santri', 'mataPelajaran'])->findOrFail($id);
        $santri = Santri::aktif()->orderBy('nama_lengkap')->get();
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        return view('pages.nilai.form', compact('nilai', 'santri', 'mapel'));
    }

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
            $nilai = Nilai::findOrFail($id);
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

    public function bySantri(int $santriId)
    {
        $santri = Santri::findOrFail($santriId);
        $nilai = Nilai::with('mataPelajaran')
            ->where('santri_id', $santriId)
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('pages.nilai.show', compact('santri', 'nilai'));
    }

    public function destroy(int $id)
    {
        try {
            Nilai::findOrFail($id)->delete();

            return redirect()->route('admin.nilai.index')
                ->with('success', 'Nilai berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal hapus nilai: ' . $e->getMessage());
        }
    }
}

