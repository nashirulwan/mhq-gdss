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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained()->onDelete('cascade');
            $table->foreignId('juri_id')->constrained()->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained()->onDelete('cascade');
            $table->integer('nilai'); // 0-100
            $table->decimal('nilai_normalisasi', 8, 4)->nullable(); // Hasil normalisasi SMART
            $table->decimal('nilai_terbobot', 8, 4)->nullable(); // Hasil perhitungan SMART
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['peserta_id', 'juri_id', 'kriteria_id']); // Mencegah duplikasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
