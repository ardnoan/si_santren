<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        \App\Models\User::create([
            'username' => 'admin',
            'email' => 'admin@sisantren.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Ustadz User
        \App\Models\User::create([
            'username' => 'ustadz1',
            'email' => 'ustadz@sisantren.com',
            'password' => Hash::make('ustadz123'),
            'role' => 'ustadz',
            'is_active' => true,
        ]);

        // Create Kelas
        $kelas = [
            ['nama_kelas' => '1A', 'tingkat' => 1, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30],
            ['nama_kelas' => '1B', 'tingkat' => 1, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30],
            ['nama_kelas' => '2A', 'tingkat' => 2, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30],
            ['nama_kelas' => '2B', 'tingkat' => 2, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30],
            ['nama_kelas' => '3A', 'tingkat' => 3, 'tahun_ajaran' => '2024/2025', 'kapasitas' => 30],
        ];

        foreach ($kelas as $k) {
            \App\Models\Kelas::create($k);
        }

        // Create Mata Pelajaran
        $mapel = [
            ['kode_mapel' => 'AL-QUR01', 'nama_mapel' => 'Al-Quran'],
            ['kode_mapel' => 'HADIS01', 'nama_mapel' => 'Hadits'],
            ['kode_mapel' => 'FIQIH01', 'nama_mapel' => 'Fiqih'],
            ['kode_mapel' => 'ARAB01', 'nama_mapel' => 'Bahasa Arab'],
            ['kode_mapel' => 'IND01', 'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'MTK01', 'nama_mapel' => 'Matematika'],
        ];

        foreach ($mapel as $m) {
            \App\Models\MataPelajaran::create($m);
        }

        // Create Sample Santri
        for ($i = 1; $i <= 10; $i++) {
            $user = \App\Models\User::create([
                'username' => 'santri' . $i,
                'email' => 'santri' . $i . '@sisantren.com',
                'password' => Hash::make('santri123'),
                'role' => 'santri',
                'is_active' => true,
            ]);

            \App\Models\Santri::create([
                'user_id' => $user->id,
                'nomor_induk' => '2024' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_lengkap' => 'Santri ' . $i,
                'nama_panggilan' => 'Santri' . $i,
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2010-01-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'alamat' => 'Jl. Raya Pesantren No. ' . $i,
                'kelas_id' => rand(1, 5),
                'nama_wali' => 'Wali Santri ' . $i,
                'no_telp_wali' => '081234567' . $i,
                'status' => 'aktif',
            ]);
        }

        echo "\n✅ Seeding completed successfully!\n";
        echo "Admin: admin / admin123\n";
        echo "Ustadz: ustadz1 / ustadz123\n";
        echo "Santri: santri1-10 / santri123\n";
    }
}