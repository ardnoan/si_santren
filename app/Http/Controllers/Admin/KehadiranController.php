<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Santri;
use App\Http\Requests\KehadiranRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));

        $kehadiran = Kehadiran::with(['santri.kelas'])
            ->whereDate('tanggal', $tanggal)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistik
        $stats = [
            'hadir' => Kehadiran::whereDate('tanggal', $tanggal)->where('status', 'hadir')->count(),
            'izin' => Kehadiran::whereDate('tanggal', $tanggal)->where('status', 'izin')->count(),
            'sakit' => Kehadiran::whereDate('tanggal', $tanggal)->where('status', 'sakit')->count(),
            'alpa' => Kehadiran::whereDate('tanggal', $tanggal)->where('status', 'alpa')->count(),
        ];

        return view('admin.kehadiran.index', compact('kehadiran', 'stats', 'tanggal'));
    }

    public function create()
    {
        $santri = Santri::aktif()->orderBy('nama_lengkap')->get();
        return view('admin.kehadiran.create', compact('santri'));
    }

    public function store(KehadiranRequest $request)
    {
        try {
            Kehadiran::create($request->validated());

            return redirect()->route('admin.kehadiran.index')
                ->with('success', 'Kehadiran berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan kehadiran: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $kehadiran = Kehadiran::findOrFail($id);
        $santri = Santri::aktif()->orderBy('nama_lengkap')->get();

        return view('admin.kehadiran.edit', compact('kehadiran', 'santri'));
    }

    public function update(KehadiranRequest $request, int $id)
    {
        try {
            $kehadiran = Kehadiran::findOrFail($id);
            $kehadiran->update($request->validated());

            return redirect()->route('admin.kehadiran.index')
                ->with('success', 'Kehadiran berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update kehadiran: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            Kehadiran::findOrFail($id)->delete();

            return redirect()->route('admin.kehadiran.index')
                ->with('success', 'Kehadiran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal hapus kehadiran: ' . $e->getMessage());
        }
    }

    /**
     * Bulk create kehadiran untuk satu kelas
     */
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'kehadiran' => 'required|array',
            'kehadiran.*.santri_id' => 'required|exists:santris,id',
            'kehadiran.*.status' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->kehadiran as $data) {
                Kehadiran::updateOrCreate(
                    [
                        'santri_id' => $data['santri_id'],
                        'tanggal' => $request->tanggal,
                    ],
                    [
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.kehadiran.index')
                ->with('success', 'Kehadiran berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyimpan kehadiran: ' . $e->getMessage());
        }
    }
}
