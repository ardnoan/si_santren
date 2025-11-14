<?php
// =============================================
// FILE 1: app/Http/Controllers/Admin/SantriController.php (Updated show method)
// =============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SantriService;
use App\Http\Requests\SantriRequest;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    protected $santriService;

    public function __construct(SantriService $santriService)
    {
        $this->santriService = $santriService;
    }

    public function index(Request $request)
    {
        if ($request->has('search')) {
            $santri = $this->santriService
                ->searchSantri($request->search)
                ->appends($request->query());
        } else {
            $santri = $this->santriService
                ->getAllSantriAktif()
                ->appends($request->query());
        }

        return view('pages.santri.index', compact('santri'));
    }


    public function create()
    {
        $kelas = \App\Models\Kelas::all();
        return view('pages.santri.form', compact('kelas'));
    }

    public function store(SantriRequest $request)
    {
        try {
            $data = $request->validated();

            // HANDLE FOTO
            if ($request->hasFile('r_foto')) {
                $foto = $request->file('r_foto');
                $namaFile = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('foto_santri', $namaFile, 'public');
                $data['foto'] = $namaFile; // kirim ke service
            }

            $this->santriService->createSantri($data);

            return redirect()
                ->route('admin.santri.index')
                ->with('success', 'Data santri berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan santri: ' . $e->getMessage());
        }
    }


    public function show(int $id)
    {
        $santri = $this->santriService->getSantriById($id);
        return view('pages.santri.profile', compact('santri'));
    }

    public function edit(int $id)
    {
        $santri = $this->santriService->getSantriById($id);
        $kelas = \App\Models\Kelas::all();

        return view('pages.santri.form', compact('santri', 'kelas'));
    }

    public function update(SantriRequest $request, int $id)
    {
        try {
            $data = $request->validated();

            // HANDLE FOTO (jika ada upload baru)
            if ($request->hasFile('r_foto')) {
                $foto = $request->file('r_foto');
                $namaFile = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('santri', $namaFile, 'public');

                $data['foto'] = $namaFile;
            }

            $this->santriService->updateSantri($id, $data);

            return redirect()
                ->route('admin.santri.index')
                ->with('success', 'Data santri berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui santri: ' . $e->getMessage());
        }
    }


    public function destroy(int $id)
    {
        try {
            $this->santriService->deleteSantri($id);

            return redirect()
                ->route('admin.santri.index')
                ->with('success', 'Data santri berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus santri: ' . $e->getMessage());
        }
    }

    public function graduate(int $id)
    {
        try {
            $this->santriService->luluskanSantri($id);

            return redirect()
                ->route('admin.santri.index')
                ->with('success', 'Santri berhasil diluluskan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal meluluskan santri: ' . $e->getMessage());
        }
    }
}
