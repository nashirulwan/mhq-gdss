<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peserta extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'nomor_peserta',
        'instansi',
        'kategori',
        'usia',
        'kontak',
        'keterangan',
        'nilai_akhir_smart',
        'skor_borda',
        'peringkat'
    ];

    protected $casts = [
        'nilai_akhir_smart' => 'decimal:3',
        'usia' => 'integer',
        'skor_borda' => 'integer',
        'peringkat' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    // Get semua nilai dari setiap juri untuk setiap kriteria
    public function getAllNilaiByKriteria($kriteria_id)
    {
        return $this->penilaians()->where('kriteria_id', $kriteria_id)->get();
    }

    // Get total nilai SMART (sum dari semua kriteria yang sudah dibobot)
    public function getNilaiSMART()
    {
        return $this->penilaians()
            ->join('kriterias', 'penilaians.kriteria_id', '=', 'kriterias.id')
            ->sum('penilaians.nilai_terbobot');
    }

    // Get rata-rata nilai dari semua juri
    public function getRataRataNilai($kriteria_id)
    {
        return $this->penilaians()
            ->where('kriteria_id', $kriteria_id)
            ->avg('nilai');
    }
}
