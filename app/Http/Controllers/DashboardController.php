<?php
// FILE: app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Pembayaran;
use App\Models\Kehadiran;
use App\Models\Nilai;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Route berdasarkan role
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isUstadz()) {
            return $this->ustadzDashboard();
        } elseif ($user->isSantri()) {
            return $this->santriDashboard();
        }
        
        abort(403, 'Unauthorized access');
    }
    
    private function adminDashboard()
    {
        // Total statistik
        $totalSantri = Santri::count();
        $totalSantriAktif = Santri::aktif()->count();
        $totalKelas = Kelas::count();
        
        // Pembayaran
        $totalPemasukan = Pembayaran::where('status', 'lunas')->sum('jumlah');
        $pemasukaBulanIni = Pembayaran::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->sum('jumlah');
        $pembayaranPending = Pembayaran::where('status', 'pending')->count();
        
        // Kehadiran hari ini
        $kehadiranToday = Kehadiran::whereDate('tanggal', Carbon::today())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Santri per kelas
        $santriPerKelas = Kelas::withCount('santris')
            ->get()
            ->map(function($kelas) {
                return [
                    'kelas' => $kelas->nama_kelas,
                    'jumlah' => $kelas->santris_count
                ];
            });
        
        // Statistik gender
        $santriLaki = Santri::aktif()->byGender('L')->count();
        $santriPerempuan = Santri::aktif()->byGender('P')->count();
        
        return view('dashboard', compact(
            'totalSantri',
            'totalSantriAktif',
            'totalKelas',
            'totalPemasukan',
            'pemasukaBulanIni',
            'pembayaranPending',
            'kehadiranToday',
            'santriPerKelas',
            'santriLaki',
            'santriPerempuan'
        ));
    }
    
    private function ustadzDashboard()
    {
        // Statistik untuk ustadz
        $totalSantri = Santri::aktif()->count();
        $totalKelas = Kelas::count();
        
        // Kehadiran hari ini
        $kehadiranToday = Kehadiran::whereDate('tanggal', Carbon::today())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Santri per kelas
        $santriPerKelas = Kelas::withCount('santris')
            ->get()
            ->map(function($kelas) {
                return [
                    'kelas' => $kelas->nama_kelas,
                    'jumlah' => $kelas->santris_count
                ];
            });
        
        // Kelas yang diampu (jika ada relasi)
        $kelasDiampu = Kelas::where('wali_kelas', auth()->id())->count();
        
        return view('dashboard', compact(
            'totalSantri',
            'totalKelas',
            'kehadiranToday',
            'santriPerKelas',
            'kelasDiampu'
        ));
    }
    
    private function santriDashboard()
    {
        $santri = auth()->user()->santri;
        
        if (!$santri) {
            return redirect()->route('login')
                ->with('error', 'Data santri tidak ditemukan');
        }
        
        // Statistik kehadiran
        $totalKehadiran = $santri->kehadiran()->count();
        $kehadiranBulanIni = $santri->kehadiran()
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();
        
        $kehadiranHadir = $santri->kehadiran()
            ->where('status', 'hadir')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();
        
        // Pembayaran
        $totalPembayaran = $santri->pembayaran()
            ->where('status', 'lunas')
            ->sum('jumlah');
        
        $pembayaranBulanIni = $santri->pembayaran()
            ->where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->sum('jumlah');
        
        $pembayaranPending = $santri->pembayaran()
            ->where('status', 'pending')
            ->count();
        
        // Nilai rata-rata
        $nilaiRataRata = $santri->nilai()->avg('nilai_akhir');
        
        // Data untuk chart
        $kehadiranBulanIni7Hari = $santri->kehadiran()
            ->whereBetween('tanggal', [Carbon::now()->subDays(6), Carbon::now()])
            ->get()
            ->groupBy(function($item) {
                return $item->tanggal->format('Y-m-d');
            });
        
        return view('dashboard', compact(
            'santri',
            'totalKehadiran',
            'kehadiranBulanIni',
            'kehadiranHadir',
            'totalPembayaran',
            'pembayaranBulanIni',
            'pembayaranPending',
            'nilaiRataRata',
            'kehadiranBulanIni7Hari'
        ));
    }
}