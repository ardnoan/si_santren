<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SantriService;
use App\Http\Requests\SantriRequest;
use Illuminate\Http\Request;

/**
 * Class SantriController
 * Implementasi: Dependency Injection & Single Responsibility
 */
class SantriController extends Controller
{
    protected $santriService;

    /**
     * Constructor with Dependency Injection
     */
    public function __construct(SantriService $santriService)
    {
        $this->santriService = $santriService;
    }

    /**
     * Display a listing of santri
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $santri = $this->santriService->searchSantri($request->search);
        } else {
            $santri = $this->santriService->getAllSantriAktif();
        }

        return view('admin.santri.index', compact('santri'));
    }

    /**
     * Show the form for creating a new santri
     */
    public function create()
    {
        $kelas = \App\Models\Kelas::all();
        return view('admin.santri.create', compact('kelas'));
    }

    /**
     * Store a newly created santri
     */
    public function store(SantriRequest $request)
    {
        try {
            $this->santriService->createSantri($request->validated());
            
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

    /**
     * Display the specified santri
     */
    public function show(int $id)
    {
        $santri = $this->santriService->getSantriById($id);
        return view('admin.santri.show', compact('santri'));
    }

    /**
     * Show the form for editing the specified santri
     */
    public function edit(int $id)
    {
        $santri = $this->santriService->getSantriById($id);
        $kelas = \App\Models\Kelas::all();
        
        return view('admin.santri.edit', compact('santri', 'kelas'));
    }

    /**
     * Update the specified santri
     */
    public function update(SantriRequest $request, int $id)
    {
        try {
            $this->santriService->updateSantri($id, $request->validated());
            
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

    /**
     * Remove the specified santri
     */
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

    /**
     * Graduate santri
     */
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