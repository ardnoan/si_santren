<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan', 100);
            $table->enum('jenis', [
                'ibadah',      // Shalat Berjamaah, Tahfidz
                'akademik',    // Belajar, Kajian
                'kebersihan',  // Piket, Bersih-bersih
                'olahraga',    // Senam, Futsal
                'lainnya'
            ]);
            $table->enum('frekuensi', ['harian', 'mingguan', 'bulanan', 'insidental']);
            $table->string('hari')->nullable(); // Senin, Selasa, ... atau "Setiap Hari"
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('tempat', 100)->nullable();
            $table->foreignId('penanggung_jawab')->nullable()->constrained('users'); // ustadz_id
            $table->text('deskripsi')->nullable();
            $table->boolean('is_wajib')->default(false); // wajib untuk santri
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('jenis');
            $table->index('frekuensi');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kegiatans');
    }
};