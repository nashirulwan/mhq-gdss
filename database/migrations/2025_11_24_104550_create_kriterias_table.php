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
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kriteria'); // Tajwid, Kelancaran, Fasohah, Adab, Tartil
            $table->text('deskripsi')->nullable();
            $table->decimal('bobot', 5, 3); // Bobot untuk SMART method
            $table->decimal('bobot_borda', 5, 3)->default(1.0); // Bobot untuk Borda
            $table->integer('nilai_max')->default(100); // Nilai maksimal
            $table->integer('nilai_min')->default(0); // Nilai minimal
            $table->string('atribut'); // benefit (semakin besar semakin baik)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
