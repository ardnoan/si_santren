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

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('kelas')->truncate();
        DB::table('mata_pelajarans')->truncate();
        DB::table('santris')->truncate();
        DB::table('kehadirans')->truncate();
        DB::table('pembayarans')->truncate();
        DB::table('nilais')->truncate();
        DB::table('jadwal_pelajarans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
                'status' => $faker->randomElement(['aktif', 'cuti', 'lulus', 'keluar']),
            ]);

            // ====== KEHADIRAN (5 ENTRIES / SANTRI, TANPA DUPLIKAT) ======
            $tanggalDipakai = [];

            for ($j = 0; $j < 5; $j++) {
                do {
                    $tanggal = now()->subDays(rand(1, 30))->format('Y-m-d');
                } while (in_array($tanggal, $tanggalDipakai));

                $tanggalDipakai[] = $tanggal;

                \App\Models\Kehadiran::create([
                    'santri_id' => $santri->id,
                    'tanggal' => $tanggal,
                    'status' => $faker->randomElement(['hadir', 'izin', 'sakit', 'alpa']),
                    'keterangan' => $faker->sentence(3),
                    'jam_masuk' => '07:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                    'jam_keluar' => '15:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                ]);
            }

            // ====== PEMBAYARAN (3 ENTRIES / SANTRI) ======
            for ($p = 0; $p < 3; $p++) {
                \App\Models\Pembayaran::create([
                    'santri_id' => $santri->id,
                    'jenis_pembayaran' => $faker->randomElement(['spp', 'pendaftaran', 'seragam', 'lainnya']),
                    'jumlah' => $faker->numberBetween(50000, 500000),
                    'tanggal_bayar' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                    'bulan_bayar' => $faker->monthName . ' ' . $faker->year,
                    'metode_pembayaran' => $faker->randomElement(['tunai', 'transfer', 'qris']),
                    'status' => $faker->randomElement(['pending', 'lunas', 'dibatalkan']),
                    'admin_id' => $admin->id,
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
        foreach ($kelasIds as $kelasId) {
            foreach ($mapelIds as $mapelId) {
                \App\Models\JadwalPelajaran::create([
                    'kelas_id' => $kelasId,
                    'mata_pelajaran_id' => $mapelId,
                    'pengajar_id' => $ustadz->id,
                    'hari' => $faker->randomElement($hariList),
                    'jam_mulai' => '07:00:00',
                    'jam_selesai' => '08:30:00',
                ]);
            }
        }

        echo "\n✅ Seeder selesai dijalankan!\n";
        echo "Admin: admin / admin123\n";
        echo "Ustadz: ustadz1 / ustadz123\n";
        echo "Santri: santri1–50 / santri123\n";
    }
}
