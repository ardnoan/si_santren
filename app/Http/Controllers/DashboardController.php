<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Pembayaran;
use App\Models\Kehadiran;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\Kas;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isUstadz()) {
            return $this->ustadzDashboard();
        } elseif ($user->isSantri()) {
            return $this->santriDashboard();
        } elseif ($user->isBendahara()) {
            return $this->bendaharaDashboard();
        } elseif ($user->isPemimpin()) {
            return $this->pemimpinDashboard();
        }
        
        abort(403, 'Unauthorized access');
    }
    
    private function adminDashboard()
    {
        $totalSantri = Santri::count();
        $totalSantriAktif = Santri::aktif()->count();
        $totalKelas = Kelas::count();
        
        $totalPemasukan = Pembayaran::where('status', 'lunas')->sum('jumlah');
        $pemasukaBulanIni = Pembayaran::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
            ->sum('jumlah');
        $pembayaranPending = Pembayaran::where('status', 'pending')->count();
        
        $kehadiranToday = Kehadiran::whereDate('tanggal', Carbon::today())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        $santriPerKelas = Kelas::withCount('santris')
            ->orderBy('nama_kelas')
            ->get()
            ->map(function($kelas) {
                return [
                    'kelas' => $kelas->nama_kelas,
                    'jumlah' => $kelas->santris_count
                ];
            });
        
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
        // FIX: tambahkan variable totalSantri
        $totalSantri = Santri::aktif()->count();
        $totalKelas = Kelas::count();
        
        $kehadiranToday = Kehadiran::whereDate('tanggal', Carbon::today())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        $santriPerKelas = Kelas::withCount('santris')
            ->orderBy('nama_kelas')
            ->get()
            ->map(function($kelas) {
                return [
                    'kelas' => $kelas->nama_kelas,
                    'jumlah' => $kelas->santris_count
                ];
            });
        
        $kelasDiampu = Kelas::where('wali_kelas', auth()->id())->count();
        
        return view('dashboard', compact(
            'totalSantri',
            'totalKelas',
            'kehadiranToday',
            'santriPerKelas',
            'kelasDiampu'
        ));
    }
    
    private function bendaharaDashboard()
    {
        $saldoKas = Kas::getSaldoTerkini();
        
        $pemasukanBulanIni = Pembayaran::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
            ->sum('jumlah');
        
        $pengeluaranBulanIni = Pengeluaran::where('status', 'approved')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');
        
        $pembayaranPending = Pembayaran::where('status', 'pending')->count();
        $pendingApproval = Pengeluaran::pending()->count();
        
        $cashFlowData = $this->getCashFlowData();
        $pengeluaranData = $this->getPengeluaranData();
        
        return view('dashboard', compact(
            'saldoKas',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'pembayaranPending',
            'pendingApproval',
            'cashFlowData',
            'pengeluaranData'
        ));
    }
    
    private function pemimpinDashboard()
    {
        $totalSantri = Santri::aktif()->count();
        $saldoKas = Kas::getSaldoTerkini();
        
        $pemasukanBulanIni = Pembayaran::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
            ->sum('jumlah');
        
        $pengeluaranBulanIni = Pengeluaran::where('status', 'approved')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');
        
        $pendingApproval = Pengeluaran::pending()->count();
        
        $trendKeuangan = $this->getTrendKeuangan();
        $kategoriPengeluaran = $this->getKategoriPengeluaran();
        
        return view('dashboard', compact(
            'totalSantri',
            'saldoKas',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'pendingApproval',
            'trendKeuangan',
            'kategoriPengeluaran'
        ));
    }
    
    private function santriDashboard()
    {
        $santri = auth()->user()->santri;
        
        if (!$santri) {
            return redirect()->route('login')
                ->with('error', 'Data santri tidak ditemukan');
        }
        
        $totalKehadiran = $santri->kehadiran()->count();
        $kehadiranBulanIni = $santri->kehadiran()
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->count();
        
        $kehadiranHadir = $santri->kehadiran()
            ->where('status', 'hadir')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->count();
        
        $totalPembayaran = $santri->pembayaran()
            ->where('status', 'lunas')
            ->sum('jumlah');
        
        $pembayaranBulanIni = $santri->pembayaran()
            ->where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
            ->sum('jumlah');
        
        $pembayaranPending = $santri->pembayaran()
            ->where('status', 'pending')
            ->count();
        
        $nilaiRataRata = $santri->nilai()->avg('nilai_akhir');
        
        $kehadiranBulanIni7Hari = $santri->kehadiran()
            ->whereBetween('tanggal', [Carbon::now()->subDays(6), Carbon::now()])
            ->orderBy('tanggal', 'asc')
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
    
    private function getCashFlowData()
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
            
            $pemasukan[] = Pembayaran::where('status', 'lunas')
                ->whereDate('tanggal_bayar', $date)
                ->sum('jumlah');
            
            $pengeluaran[] = Pengeluaran::where('status', 'approved')
                ->whereDate('tanggal', $date)
                ->sum('jumlah');
        }
        
        return compact('labels', 'pemasukan', 'pengeluaran');
    }
    
    private function getPengeluaranData()
    {
        $data = Pengeluaran::where('status', 'approved')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->selectRaw('kategori, SUM(jumlah) as total')
            ->groupBy('kategori')
            ->get();
        
        $labels = [];
        $values = [];
        
        foreach ($data as $item) {
            $labels[] = ucfirst(str_replace('_', ' ', $item->kategori));
            $values[] = $item->total;
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values)
        ];
    }
    
    private function getTrendKeuangan()
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $labels[] = $bulan->format('M Y');
            
            $pemasukan[] = Pembayaran::where('status', 'lunas')
                ->whereMonth('tanggal_bayar', $bulan->month)
                ->whereYear('tanggal_bayar', $bulan->year)
                ->sum('jumlah');
            
            $pengeluaran[] = Pengeluaran::where('status', 'approved')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');
        }
        
        return compact('labels', 'pemasukan', 'pengeluaran');
    }
    
    private function getKategoriPengeluaran()
    {
        $data = Pengeluaran::where('status', 'approved')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->selectRaw('kategori, SUM(jumlah) as total')
            ->groupBy('kategori')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        $labels = [];
        $values = [];
        
        foreach ($data as $item) {
            $labels[] = ucfirst(str_replace('_', ' ', $item->kategori));
            $values[] = $item->total;
        }
        
        return compact('labels', 'values');
    }
}