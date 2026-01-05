<?php
// FILE: app/Http/Controllers/DashboardController.php - FIXED VERSION

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
        
        // Route berdasarkan role
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
        // Total statistik
        $totalSantri = Santri::count();
        $totalSantriAktif = Santri::aktif()->count();
        $totalKelas = Kelas::count();
        
        // Pembayaran
        $totalPemasukan = Pembayaran::where('status', 'lunas')->sum('jumlah');
        $pemasukaBulanIni = Pembayaran::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
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
            ->orderBy('nama_kelas')
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
            ->orderBy('nama_kelas')
            ->get()
            ->map(function($kelas) {
                return [
                    'kelas' => $kelas->nama_kelas,
                    'jumlah' => $kelas->santris_count
                ];
            });
        
        // Kelas yang diampu
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
        // Saldo Kas Terkini
        $saldoKas = Kas::getSaldoTerkini();
        
        // Pemasukan Bulan Ini
        $pemasukanBulanIni = Pembayaran::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
            ->sum('jumlah');
        
        // Pengeluaran Bulan Ini
        $pengeluaranBulanIni = Pengeluaran::where('status', 'approved')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');
        
        // Pembayaran Pending
        $pembayaranPending = Pembayaran::where('status', 'pending')->count();
        
        // Pending Approval (untuk quick info)
        $pendingApproval = Pengeluaran::pending()->count();
        
        // Data untuk Chart: Cash Flow 7 hari terakhir
        $cashFlowData = $this->getCashFlowData();
        
        // Data untuk Chart: Pengeluaran per kategori bulan ini
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
        // Total Santri
        $totalSantri = Santri::aktif()->count();
        
        // Saldo Kas
        $saldoKas = Kas::getSaldoTerkini();
        
        // Pemasukan Bulan Ini
        $pemasukanBulanIni = Pembayaran::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
            ->sum('jumlah');
        
        // Pengeluaran Bulan Ini
        $pengeluaranBulanIni = Pengeluaran::where('status', 'approved')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');
        
        // Pending Approval
        $pendingApproval = Pengeluaran::pending()->count();
        
        // Chart Data: Trend Keuangan 6 Bulan
        $trendKeuangan = $this->getTrendKeuangan();
        
        // Chart Data: Kategori Pengeluaran
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
        
        // Statistik kehadiran
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
        
        // Pembayaran
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
        
        // Nilai rata-rata
        $nilaiRataRata = $santri->nilai()->avg('nilai_akhir');
        
        // Data untuk chart - kehadiran 7 hari terakhir
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
    
    // Helper methods untuk chart data
    
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
            $labels[] = ucfirst($item->kategori);
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