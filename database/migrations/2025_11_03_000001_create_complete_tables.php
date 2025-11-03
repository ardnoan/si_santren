<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kelas
        Schema::dropIfExists('kelas');
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 64);
            $table->integer('tingkat');
            $table->string('tahun_ajaran', 20);
            $table->integer('kapasitas');
            $table->foreignId('wali_kelas')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Santris
        Schema::dropIfExists('santris');
        Schema::create('santris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_induk', 20)->unique();
            $table->string('nama_lengkap', 124);
            $table->string('nama_panggilan', 64)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 64);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas');
            $table->string('nama_wali', 124);
            $table->string('no_telp_wali', 20);
            $table->dateTime('tanggal_masuk')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('tanggal_keluar')->nullable();
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'keluar'])->default('aktif');
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Mata Pelajaran
        Schema::dropIfExists('mata_pelajarans');
        Schema::create('mata_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 20)->unique();
            $table->string('nama_mapel', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Kehadiran
        Schema::dropIfExists('kehadirans');
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->text('keterangan')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->timestamps();
            
            $table->unique(['santri_id', 'tanggal']);
        });

        // Pembayaran
        Schema::dropIfExists('pembayarans');
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->enum('jenis_pembayaran', ['spp', 'pendaftaran', 'seragam', 'lainnya']);
            $table->decimal('jumlah', 12, 2);
            $table->date('tanggal_bayar');
            $table->string('bulan_bayar', 7)->nullable();
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris']);
            $table->enum('status', ['pending', 'lunas', 'dibatalkan'])->default('pending');
            $table->string('bukti_transfer')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('admin_id')->constrained('users');
            $table->timestamps();
        });

        // Nilai
        Schema::dropIfExists('nilais');
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans');
            $table->string('semester', 10);
            $table->string('tahun_ajaran', 20);
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->char('predikat', 1)->nullable();
            $table->timestamps();
        });

        // Jadwal Pelajaran
        Schema::dropIfExists('jadwal_pelajarans');
        Schema::create('jadwal_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans');
            $table->foreignId('pengajar_id')->constrained('users');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajarans');
        Schema::dropIfExists('nilais');
        Schema::dropIfExists('pembayarans');
        Schema::dropIfExists('kehadirans');
        Schema::dropIfExists('mata_pelajarans');
        Schema::dropIfExists('santris');
        Schema::dropIfExists('kelas');
    }
};