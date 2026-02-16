<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan semua seeder.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Disable foreign key checks untuk PostgreSQL
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        // Urutan penghapusan yang benar (dari child ke parent)
        DB::table('jadwal_pelajarans')->delete();
        DB::table('nilais')->delete();
        DB::table('pembayarans')->delete();
        DB::table('kehadirans')->delete();
        DB::table('santris')->delete();
        DB::table('jadwal_kegiatans')->delete();
        DB::table('pengeluarans')->delete();
        DB::table('kas')->delete();
        DB::table('mata_pelajarans')->delete();
        DB::table('kelas')->delete();
        DB::table('users')->delete();

        // Re-enable foreign key checks
        DB::statement('SET CONSTRAINTS ALL IMMEDIATE');

        // ====== USER ADMIN ======
        $admin = \App\Models\User::create([
            'username' => 'admin',
            'email' => 'admin@sisantren.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // ====== USER USTADZ ======
        $ustadz = \App\Models\User::create([
            'username' => 'ustadz1',
            'email' => 'ustadz@sisantren.com',
            'password' => Hash::make('ustadz123'),
            'role' => 'ustadz',
            'is_active' => true,
        ]);

        // ====== KELAS ======
        $kelasList = [
            ['nama_kelas' => '1A', 'tingkat' => 1, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30, 'wali_kelas' => $ustadz->id],
            ['nama_kelas' => '1B', 'tingkat' => 1, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30, 'wali_kelas' => $ustadz->id],
            ['nama_kelas' => '2A', 'tingkat' => 2, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30, 'wali_kelas' => $ustadz->id],
            ['nama_kelas' => '2B', 'tingkat' => 2, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30, 'wali_kelas' => $ustadz->id],
            ['nama_kelas' => '3A', 'tingkat' => 3, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30, 'wali_kelas' => $ustadz->id],
        ];
        foreach ($kelasList as $k) {
            \App\Models\Kelas::create($k);
        }

        // ====== MATA PELAJARAN ======
        $mapelList = [
            ['kode_mapel' => 'ALQUR01', 'nama_mapel' => 'Al-Quran'],
            ['kode_mapel' => 'HADIS01', 'nama_mapel' => 'Hadits'],
            ['kode_mapel' => 'FIQIH01', 'nama_mapel' => 'Fiqih'],
            ['kode_mapel' => 'ARAB01', 'nama_mapel' => 'Bahasa Arab'],
            ['kode_mapel' => 'IND01', 'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'MTK01', 'nama_mapel' => 'Matematika'],
        ];
        foreach ($mapelList as $m) {
            \App\Models\MataPelajaran::create($m);
        }

        $mapelIds = \App\Models\MataPelajaran::pluck('id')->toArray();
        $kelasIds = \App\Models\Kelas::pluck('id')->toArray();

        // ====== USER BENDAHARA ======
        $bendahara = \App\Models\User::create([
            'username' => 'bendahara',
            'email' => 'bendahara@sisantren.com',
            'password' => Hash::make('bendahara123'),
            'role' => 'bendahara',
            'is_active' => true,
        ]);

        // ====== USER PEMIMPIN ======
        $pemimpin = \App\Models\User::create([
            'username' => 'pemimpin',
            'email' => 'pemimpin@sisantren.com',
            'password' => Hash::make('pemimpin123'),
            'role' => 'pemimpin',
            'is_active' => true,
        ]);

        // ====== SANTRI (50 DATA) ======
        for ($i = 1; $i <= 50; $i++) {
            $user = \App\Models\User::create([
                'username' => 'santri' . $i,
                'email' => 'santri' . $i . '@sisantren.com',
                'password' => Hash::make('santri123'),
                'role' => 'santri',
                'is_active' => true,
            ]);

            $santri = \App\Models\Santri::create([
                'user_id' => $user->id,
                'nomor_induk' => '2024' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_lengkap' => $faker->name(),
                'nama_panggilan' => $faker->firstName(),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $faker->dateTimeBetween('-15 years', '-10 years')->format('Y-m-d'),
                'alamat' => $faker->address(),
                'kelas_id' => $faker->randomElement($kelasIds),
                'nama_wali' => $faker->name(),
                'no_telp_wali' => '08' . rand(1000000000, 9999999999),
                'status' => $faker->randomElement(['aktif', 'aktif', 'aktif', 'cuti']), // lebih banyak aktif
            ]);

            // ====== KEHADIRAN (6 ENTRIES / SANTRI, TERMASUK HARI INI) ======
            $tanggalDipakai = [];

            // KEHADIRAN HARI INI (untuk dokumentasi dashboard)
            \App\Models\Kehadiran::create([
                'santri_id' => $santri->id,
                'tanggal' => now()->format('Y-m-d'),
                'status' => $faker->randomElement(['hadir', 'hadir', 'hadir', 'hadir', 'izin', 'sakit']), // mayoritas hadir
                'keterangan' => $santri->status === 'aktif' ? 'Hadir tepat waktu' : $faker->sentence(3),
                'jam_masuk' => '07:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT),
                'jam_keluar' => null, // belum pulang (masih di pesantren)
            ]);

            // KEHADIRAN KEMARIN
            \App\Models\Kehadiran::create([
                'santri_id' => $santri->id,
                'tanggal' => now()->subDay()->format('Y-m-d'),
                'status' => $faker->randomElement(['hadir', 'hadir', 'izin']),
                'keterangan' => $faker->sentence(3),
                'jam_masuk' => '07:' . str_pad(rand(0, 45), 2, '0', STR_PAD_LEFT),
                'jam_keluar' => '15:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT),
            ]);

            // KEHADIRAN MINGGU INI (5 hari terakhir)
            for ($j = 2; $j <= 6; $j++) {
                $tanggal = now()->subDays($j)->format('Y-m-d');
                
                \App\Models\Kehadiran::create([
                    'santri_id' => $santri->id,
                    'tanggal' => $tanggal,
                    'status' => $faker->randomElement(['hadir', 'hadir', 'hadir', 'izin', 'sakit', 'alpa']),
                    'keterangan' => $faker->sentence(3),
                    'jam_masuk' => '07:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                    'jam_keluar' => '15:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                ]);
            }

            // ====== PEMBAYARAN (4 ENTRIES / SANTRI, TERMASUK YANG BARU) ======
            
            // PEMBAYARAN HARI INI (beberapa santri bayar hari ini)
            if ($i % 5 == 0) { // setiap 5 santri ada yang bayar hari ini
                \App\Models\Pembayaran::create([
                    'santri_id' => $santri->id,
                    'jenis_pembayaran' => $faker->randomElement(['spp', 'pendaftaran']),
                    'jumlah' => $faker->randomElement([300000, 500000, 750000]),
                    'tanggal_bayar' => now()->format('Y-m-d'),
                    'bulan_bayar' => now()->format('F Y'),
                    'metode_pembayaran' => $faker->randomElement(['tunai', 'transfer', 'qris']),
                    'status' => 'lunas',
                    'admin_id' => $bendahara->id,
                    'keterangan' => 'Pembayaran hari ini',
                ]);
            }

            // PEMBAYARAN MINGGU INI
            if ($i % 3 == 0) {
                \App\Models\Pembayaran::create([
                    'santri_id' => $santri->id,
                    'jenis_pembayaran' => 'spp',
                    'jumlah' => 300000,
                    'tanggal_bayar' => now()->subDays(rand(1, 7))->format('Y-m-d'),
                    'bulan_bayar' => now()->format('F Y'),
                    'metode_pembayaran' => $faker->randomElement(['tunai', 'transfer']),
                    'status' => 'lunas',
                    'admin_id' => $bendahara->id,
                    'keterangan' => 'Pembayaran SPP bulan ini',
                ]);
            }

            // PEMBAYARAN PENDING (untuk beberapa santri)
            if ($i % 7 == 0) {
                \App\Models\Pembayaran::create([
                    'santri_id' => $santri->id,
                    'jenis_pembayaran' => 'spp',
                    'jumlah' => 300000,
                    'tanggal_bayar' => now()->format('Y-m-d'),
                    'bulan_bayar' => now()->format('F Y'),
                    'metode_pembayaran' => 'transfer',
                    'status' => 'pending',
                    'admin_id' => $bendahara->id,
                    'keterangan' => 'Menunggu konfirmasi transfer',
                ]);
            }

            // PEMBAYARAN SEBELUMNYA (2 bulan lalu)
            for ($p = 0; $p < 2; $p++) {
                \App\Models\Pembayaran::create([
                    'santri_id' => $santri->id,
                    'jenis_pembayaran' => $faker->randomElement(['spp', 'seragam', 'lainnya']),
                    'jumlah' => $faker->numberBetween(100000, 500000),
                    'tanggal_bayar' => $faker->dateTimeBetween('-2 months', '-1 month')->format('Y-m-d'),
                    'bulan_bayar' => $faker->monthName . ' ' . $faker->year,
                    'metode_pembayaran' => $faker->randomElement(['tunai', 'transfer', 'qris']),
                    'status' => 'lunas',
                    'admin_id' => $bendahara->id,
                    'keterangan' => $faker->sentence(4),
                ]);
            }

            // ====== NILAI (UNTUK SETIAP MAPEL) ======
            foreach ($mapelIds as $mapelId) {
                $nilaiTugas = $faker->numberBetween(60, 100);
                $nilaiUts = $faker->numberBetween(60, 100);
                $nilaiUas = $faker->numberBetween(60, 100);
                $nilaiAkhir = round(($nilaiTugas + $nilaiUts + $nilaiUas) / 3, 2);
                $predikat = match (true) {
                    $nilaiAkhir >= 90 => 'A',
                    $nilaiAkhir >= 80 => 'B',
                    $nilaiAkhir >= 70 => 'C',
                    $nilaiAkhir >= 60 => 'D',
                    default => 'E',
                };

                \App\Models\Nilai::create([
                    'santri_id' => $santri->id,
                    'mata_pelajaran_id' => $mapelId,
                    'semester' => 'Ganjil',
                    'tahun_ajaran' => '2024/2025',
                    'nilai_tugas' => $nilaiTugas,
                    'nilai_uts' => $nilaiUts,
                    'nilai_uas' => $nilaiUas,
                    'nilai_akhir' => $nilaiAkhir,
                    'predikat' => $predikat,
                ]);
            }
        }

        // ====== JADWAL PELAJARAN ======
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jamMulaiList = ['07:00:00', '08:30:00', '10:00:00', '13:00:00', '14:30:00'];
        
        foreach ($kelasIds as $kelasId) {
            $jadwalIndex = 0;
            foreach ($mapelIds as $mapelId) {
                $jamMulai = $jamMulaiList[$jadwalIndex % count($jamMulaiList)];
                $jamMulaiTimestamp = strtotime($jamMulai);
                $jamSelesai = date('H:i:s', $jamMulaiTimestamp + (90 * 60)); // +90 menit
                
                \App\Models\JadwalPelajaran::create([
                    'kelas_id' => $kelasId,
                    'mata_pelajaran_id' => $mapelId,
                    'pengajar_id' => $ustadz->id,
                    'hari' => $hariList[$jadwalIndex % count($hariList)],
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                ]);
                $jadwalIndex++;
            }
        }

        // ====== JADWAL KEGIATAN (SAMPLE) ======
        $kegiatanList = [
            [
                'nama_kegiatan' => 'Shalat Subuh Berjamaah',
                'jenis' => 'ibadah',
                'frekuensi' => 'harian',
                'hari' => 'Setiap Hari',
                'jam_mulai' => '04:30:00',
                'jam_selesai' => '05:00:00',
                'tempat' => 'Masjid',
                'penanggung_jawab' => $ustadz->id,
                'is_wajib' => true,
                'is_active' => true,
            ],
            [
                'nama_kegiatan' => 'Tahfidz Al-Quran',
                'jenis' => 'ibadah',
                'frekuensi' => 'harian',
                'hari' => 'Setiap Hari',
                'jam_mulai' => '06:00:00',
                'jam_selesai' => '07:00:00',
                'tempat' => 'Ruang Tahfidz',
                'penanggung_jawab' => $ustadz->id,
                'is_wajib' => true,
                'is_active' => true,
            ],
            [
                'nama_kegiatan' => 'Kajian Fiqih',
                'jenis' => 'akademik',
                'frekuensi' => 'mingguan',
                'hari' => 'Jumat',
                'jam_mulai' => '19:30:00',
                'jam_selesai' => '21:00:00',
                'tempat' => 'Aula',
                'penanggung_jawab' => $ustadz->id,
                'is_wajib' => false,
                'is_active' => true,
            ],
            [
                'nama_kegiatan' => 'Piket Kebersihan',
                'jenis' => 'kebersihan',
                'frekuensi' => 'harian',
                'hari' => 'Setiap Hari',
                'jam_mulai' => '05:30:00',
                'jam_selesai' => '06:00:00',
                'tempat' => 'Area Pesantren',
                'penanggung_jawab' => null,
                'is_wajib' => true,
                'is_active' => true,
            ],
            [
                'nama_kegiatan' => 'Olahraga Bersama',
                'jenis' => 'olahraga',
                'frekuensi' => 'mingguan',
                'hari' => 'Sabtu',
                'jam_mulai' => '16:00:00',
                'jam_selesai' => '17:30:00',
                'tempat' => 'Lapangan',
                'penanggung_jawab' => $ustadz->id,
                'is_wajib' => false,
                'is_active' => true,
            ],
        ];

        foreach ($kegiatanList as $k) {
            \App\Models\JadwalKegiatan::create($k);
        }

        // ====== PENGELUARAN (DATA BULAN INI) ======
        $pengeluaranList = [];
        
        // Pengeluaran hari ini (untuk dokumentasi)
        $pengeluaranList[] = [
            'tanggal' => now()->format('Y-m-d'),
            'kategori' => 'konsumsi',
            'jumlah' => 500000,
            'keterangan' => 'Belanja bahan makanan harian',
            'bendahara_id' => $bendahara->id,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $pengeluaranList[] = [
            'tanggal' => now()->format('Y-m-d'),
            'kategori' => 'kebersihan',
            'jumlah' => 250000,
            'keterangan' => 'Pembelian alat kebersihan',
            'bendahara_id' => $bendahara->id,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Pengeluaran kemarin (approved)
        $pengeluaranList[] = [
            'tanggal' => now()->subDay()->format('Y-m-d'),
            'kategori' => 'listrik',
            'jumlah' => 1500000,
            'keterangan' => 'Pembayaran listrik bulan ini',
            'bendahara_id' => $bendahara->id,
            'status' => 'approved',
            'approved_by' => $pemimpin->id,
            'approved_at' => now()->subDay(),
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ];

        // Pengeluaran minggu ini
        for ($i = 2; $i <= 7; $i++) {
            $kategori = $faker->randomElement(['konsumsi', 'kebersihan', 'alat_tulis', 'perawatan']);
            $jumlah = $faker->numberBetween(100000, 800000);
            
            $pengeluaranList[] = [
                'tanggal' => now()->subDays($i)->format('Y-m-d'),
                'kategori' => $kategori,
                'jumlah' => $jumlah,
                'keterangan' => 'Pengeluaran ' . $kategori,
                'bendahara_id' => $bendahara->id,
                'status' => $faker->randomElement(['approved', 'approved', 'approved', 'rejected']),
                'approved_by' => $pemimpin->id,
                'approved_at' => now()->subDays($i),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ];
        }

        // Pengeluaran bulan lalu
        for ($i = 0; $i < 10; $i++) {
            $kategori = $faker->randomElement(['gaji', 'konsumsi', 'air', 'listrik', 'perawatan']);
            $jumlah = $kategori === 'gaji' ? $faker->numberBetween(3000000, 5000000) : $faker->numberBetween(200000, 1000000);
            
            $pengeluaranList[] = [
                'tanggal' => $faker->dateTimeBetween('-1 month', '-8 days')->format('Y-m-d'),
                'kategori' => $kategori,
                'jumlah' => $jumlah,
                'keterangan' => 'Pengeluaran ' . $kategori,
                'bendahara_id' => $bendahara->id,
                'status' => 'approved',
                'approved_by' => $pemimpin->id,
                'approved_at' => $faker->dateTimeBetween('-1 month', '-8 days'),
                'created_at' => $faker->dateTimeBetween('-1 month', '-8 days'),
                'updated_at' => $faker->dateTimeBetween('-1 month', '-8 days'),
            ];
        }

        foreach ($pengeluaranList as $p) {
            \App\Models\Pengeluaran::create($p);
        }

        // ====== KAS (SALDO AWAL DAN TRANSAKSI) ======
        $saldoAwal = 50000000; // 50 juta saldo awal
        
        // Record saldo awal
        \App\Models\Kas::create([
            'tanggal' => now()->startOfMonth()->format('Y-m-d'),
            'jenis' => 'masuk',
            'kategori' => 'saldo_awal',
            'jumlah' => $saldoAwal,
            'saldo' => $saldoAwal,
            'keterangan' => 'Saldo awal bulan ini',
            'user_id' => $bendahara->id,
        ]);

        // Get all pembayaran yang sudah lunas untuk dicatat di kas
        $pembayaranLunas = \App\Models\Pembayaran::where('status', 'lunas')
            ->orderBy('tanggal_bayar')
            ->get();

        $saldoRunning = $saldoAwal;
        foreach ($pembayaranLunas as $bayar) {
            $saldoRunning += $bayar->jumlah;
            
            \App\Models\Kas::create([
                'tanggal' => $bayar->tanggal_bayar,
                'jenis' => 'masuk',
                'kategori' => 'pembayaran_santri',
                'jumlah' => $bayar->jumlah,
                'saldo' => $saldoRunning,
                'keterangan' => "Pembayaran {$bayar->jenis_pembayaran} dari santri",
                'referensi_tipe' => 'App\\Models\\Pembayaran',
                'referensi_id' => $bayar->id,
                'user_id' => $bendahara->id,
            ]);
        }

        // Get all pengeluaran yang approved untuk dicatat di kas
        $pengeluaranApproved = \App\Models\Pengeluaran::where('status', 'approved')
            ->orderBy('tanggal')
            ->get();

        foreach ($pengeluaranApproved as $keluar) {
            $saldoRunning -= $keluar->jumlah;
            
            \App\Models\Kas::create([
                'tanggal' => $keluar->tanggal,
                'jenis' => 'keluar',
                'kategori' => 'pengeluaran_operasional',
                'jumlah' => $keluar->jumlah,
                'saldo' => $saldoRunning,
                'keterangan' => "Pengeluaran {$keluar->kategori}",
                'referensi_tipe' => 'App\\Models\\Pengeluaran',
                'referensi_id' => $keluar->id,
                'user_id' => $bendahara->id,
            ]);
        }

        // Tambahan donasi/infak (beberapa data)
        for ($i = 0; $i < 5; $i++) {
            $jumlah = $faker->numberBetween(500000, 2000000);
            $saldoRunning += $jumlah;
            
            \App\Models\Kas::create([
                'tanggal' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                'jenis' => 'masuk',
                'kategori' => $faker->randomElement(['donasi', 'infak']),
                'jumlah' => $jumlah,
                'saldo' => $saldoRunning,
                'keterangan' => 'Donasi dari donatur',
                'user_id' => $bendahara->id,
            ]);
        }


        echo "\n✅ Seeder selesai dijalankan!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📋 DATA LOGIN:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Admin      : admin / admin123\n";
        echo "Ustadz     : ustadz1 / ustadz123\n";
        echo "Bendahara  : bendahara / bendahara123\n";
        echo "Pemimpin   : pemimpin / pemimpin123\n";
        echo "Santri     : santri1–50 / santri123\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📊 DATA UNTUK DOKUMENTASI:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✓ Kehadiran hari ini: 50 data (semua santri)\n";
        echo "✓ Pembayaran hari ini: ~10 data\n";
        echo "✓ Pembayaran pending: ~7 data\n";
        echo "✓ Pengeluaran pending: 2 data (perlu approval)\n";
        echo "✓ Pengeluaran hari ini: 2 data\n";
        echo "✓ Kehadiran minggu ini: lengkap\n";
        echo "✓ Kas: saldo awal + semua transaksi tercatat\n";
        echo "✓ Jadwal kegiatan: 5 kegiatan rutin\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "💰 SALDO KAS AKHIR: Rp " . number_format($saldoRunning, 0, ',', '.') . "\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}