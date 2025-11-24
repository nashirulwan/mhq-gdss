<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nomor_peserta')->unique();
            $table->string('instansi');
            $table->string('kategori'); // 1 Juz, 5 Juz, 10 Juz, 20 Juz, 30 Juz
            $table->integer('usia');
            $table->string('kontak')->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('nilai_akhir_smart', 8, 3)->nullable(); // Hasil perhitungan SMART
            $table->integer('skor_borda')->nullable(); // Hasil perhitungan Borda
            $table->integer('peringkat')->nullable(); // Ranking akhir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};
