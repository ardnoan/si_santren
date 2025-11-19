<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis', ['masuk', 'keluar']); // pemasukan atau pengeluaran
            $table->enum('kategori', [
                'saldo_awal',
                'pembayaran_santri',
                'infak',
                'donasi',
                'pengeluaran_operasional',
                'gaji',
                'lainnya'
            ]);
            $table->decimal('jumlah', 12, 2);
            $table->decimal('saldo', 12, 2); // saldo setelah transaksi
            $table->text('keterangan')->nullable();
            $table->string('referensi_tipe')->nullable(); // App\Models\Pembayaran atau App\Models\Pengeluaran
            $table->unsignedBigInteger('referensi_id')->nullable(); // ID dari pembayaran/pengeluaran
            $table->foreignId('user_id')->constrained('users'); // yang input
            $table->timestamps();
            
            $table->index('tanggal');
            $table->index('jenis');
            $table->index(['referensi_tipe', 'referensi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};