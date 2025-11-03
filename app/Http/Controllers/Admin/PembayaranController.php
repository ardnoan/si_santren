<?php
// FILE: app/Http/Controllers/Admin/PembayaranController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PembayaranService;
use App\Http\Requests\PembayaranRequest;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    protected $pembayaranService;

    public function __construct(PembayaranService $pembayaranService)
    {
        $this->pembayaranService = $pembayaranService;
    }

    public function index()
    {
        return view('admin.pembayaran.index');
    }

    public function create()
    {
        return view('admin.pembayaran.create');
    }

    public function store(PembayaranRequest $request)
    {
        try {
            $this->pembayaranService->createPembayaran($request->validated());
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        return view('admin.pembayaran.edit', compact('pembayaran'));
    }

    public function update(PembayaranRequest $request, $id)
    {
        try {
            $this->pembayaranService->updatePembayaran($id, $request->validated());
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update pembayaran: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->pembayaranService->deletePembayaran($id);
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal hapus pembayaran: ' . $e->getMessage());
        }
    }

    public function laporan()
    {
        return view('admin.pembayaran.laporan');
    }
}