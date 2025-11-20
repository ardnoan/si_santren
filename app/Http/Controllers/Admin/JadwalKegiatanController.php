<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKegiatan;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalKegiatanController extends Controller
{
  public function index(Request $request)
  {
    $query = JadwalKegiatan::with('penanggungJawab');

    // Filter by jenis
    if ($request->jenis) {
      $query->where('jenis', $request->jenis);
    }

    // Filter by hari
    if ($request->hari) {
      $query->where('hari', $request->hari);
    }

    // Filter aktif/nonaktif
    if ($request->has('is_active')) {
      $query->where('is_active', $request->is_active);
    }

    $jadwal = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(20);

    return view('pages.jadwal-kegiatan.index', compact('jadwal'));
  }

  public function create()
  {
    $ustadz = User::byRole('ustadz')->orderBy('username')->get();
    return view('pages.jadwal-kegiatan.form', compact('ustadz'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'nama_kegiatan' => 'required|string|max:124',
      'jenis' => 'required|in:ibadah,akademik,kebersihan,olahraga,lainnya',
      'frekuensi' => 'required|in:harian,mingguan,bulanan,insidental',
      'hari' => 'nullable|string|max:64',
      'jam_mulai' => 'required|date_format:H:i',
      'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
      'tempat' => 'nullable|string|max:124',
      'penanggung_jawab' => 'nullable|exists:users,id',
      'deskripsi' => 'nullable|string',
      'is_wajib' => 'boolean',
      'is_active' => 'boolean',
    ]);

    try {
      JadwalKegiatan::create($validated);

      return redirect()
        ->route('admin.jadwal-kegiatan.index')
        ->with('success', 'Jadwal kegiatan berhasil ditambahkan!');
    } catch (\Exception $e) {
      return redirect()
        ->back()
        ->withInput()
        ->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage());
    }
  }

  public function show($id)
  {
    $jadwal = JadwalKegiatan::with('penanggungJawab')->findOrFail($id);
    return view('pages.jadwal-kegiatan.show', compact('jadwal'));
  }

  public function edit($id)
  {
    $jadwal = JadwalKegiatan::findOrFail($id);
    $ustadz = User::byRole('ustadz')->orderBy('username')->get();

    return view('pages.jadwal-kegiatan.form', compact('jadwal', 'ustadz'));
  }

  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'nama_kegiatan' => 'required|string|max:124',
      'jenis' => 'required|in:ibadah,akademik,kebersihan,olahraga,lainnya',
      'frekuensi' => 'required|in:harian,mingguan,bulanan,insidental',
      'hari' => 'nullable|string|max:64',
      'jam_mulai' => 'required|date_format:H:i',
      'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
      'tempat' => 'nullable|string|max:124',
      'penanggung_jawab' => 'nullable|exists:users,id',
      'deskripsi' => 'nullable|string',
      'is_wajib' => 'boolean',
      'is_active' => 'boolean',
    ]);

    try {
      $jadwal = JadwalKegiatan::findOrFail($id);
      $jadwal->update($validated);

      return redirect()
        ->route('admin.jadwal-kegiatan.index')
        ->with('success', 'Jadwal kegiatan berhasil diupdate!');
    } catch (\Exception $e) {
      return redirect()
        ->back()
        ->withInput()
        ->with('error', 'Gagal update jadwal: ' . $e->getMessage());
    }
  }

  public function destroy($id)
  {
    try {
      JadwalKegiatan::findOrFail($id)->delete();

      return redirect()
        ->route('admin.jadwal-kegiatan.index')
        ->with('success', 'Jadwal kegiatan berhasil dihapus!');
    } catch (\Exception $e) {
      return redirect()
        ->back()
        ->with('error', 'Gagal hapus jadwal: ' . $e->getMessage());
    }
  }

  // Toggle aktif/nonaktif
  public function toggleActive($id)
  {
    try {
      $jadwal = JadwalKegiatan::findOrFail($id);
      $jadwal->is_active = !$jadwal->is_active;
      $jadwal->save();

      $status = $jadwal->is_active ? 'diaktifkan' : 'dinonaktifkan';

      return redirect()
        ->back()
        ->with('success', "Jadwal kegiatan berhasil {$status}!");
    } catch (\Exception $e) {
      return redirect()
        ->back()
        ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
    }
  }
}
