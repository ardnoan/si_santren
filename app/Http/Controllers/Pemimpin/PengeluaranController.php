<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
  public function index(Request $request)
  {
    $query = Pengeluaran::with(['bendahara', 'approver']);

    // Filter by status
    if ($request->status) {
      $query->where('status', $request->status);
    } else {
      // Default: show pending first
      $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')");
    }

    // Filter by kategori
    if ($request->kategori) {
      $query->where('kategori', $request->kategori);
    }

    // Filter by tanggal
    if ($request->tanggal) {
      $query->whereDate('tanggal', $request->tanggal);
    }

    $pengeluaran = $query->orderBy('tanggal', 'desc')
      ->orderBy('created_at', 'desc')
      ->paginate(20);

    // Statistics
    $totalPending = Pengeluaran::where('status', 'pending')->count();
    $totalPendingNominal = Pengeluaran::where('status', 'pending')->sum('jumlah');

    return view('pemimpin.pengeluaran.index', compact(
      'pengeluaran',
      'totalPending',
      'totalPendingNominal'
    ));
  }

  public function show($id)
  {
    $pengeluaran = Pengeluaran::with(['bendahara', 'approver'])->findOrFail($id);
    return view('pemimpin.pengeluaran.show', compact('pengeluaran'));
  }

  public function approve(Request $request, $id)
  {
    $pengeluaran = Pengeluaran::findOrFail($id);

    if ($pengeluaran->status !== 'pending') {
      return back()->with('error', 'Pengeluaran ini sudah diproses.');
    }

    $validated = $request->validate([
      'catatan' => 'nullable|string|max:500',
    ]);

    DB::beginTransaction();
    try {
      // Update status pengeluaran
      $pengeluaran->update([
        'status' => 'approved',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
      ]);

      // Catat ke kas
      $saldoSebelum = Kas::getSaldoTerkini();

      Kas::create([
        'tanggal' => now(),
        'jenis' => 'keluar',
        'kategori' => $pengeluaran->kategori,
        'jumlah' => $pengeluaran->jumlah,
        'saldo' => $saldoSebelum - $pengeluaran->jumlah,
        'keterangan' => 'Pengeluaran ' . $pengeluaran->kategori_label . ': ' . $pengeluaran->keterangan . ($validated['catatan'] ? ' | ' . $validated['catatan'] : ''),
        'referensi_tipe' => Pengeluaran::class,
        'referensi_id' => $pengeluaran->id,
        'user_id' => auth()->id(),
      ]);

      DB::commit();

      return back()->with('success', 'Pengeluaran berhasil disetujui dan dicatat ke kas.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Gagal approve: ' . $e->getMessage());
    }
  }

  public function reject(Request $request, $id)
  {
    $pengeluaran = Pengeluaran::findOrFail($id);

    if ($pengeluaran->status !== 'pending') {
      return back()->with('error', 'Pengeluaran ini sudah diproses.');
    }

    $validated = $request->validate([
      'alasan' => 'required|string|max:500',
    ]);

    try {
      $pengeluaran->update([
        'status' => 'rejected',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
        'keterangan' => $pengeluaran->keterangan . ' | Ditolak: ' . $validated['alasan'],
      ]);

      return back()->with('success', 'Pengeluaran berhasil ditolak.');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal reject: ' . $e->getMessage());
    }
  }
}
