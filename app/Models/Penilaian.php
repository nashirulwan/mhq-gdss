<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $fillable = [
        'peserta_id',
        'juri_id',
        'kriteria_id',
        'nilai',
        'nilai_normalisasi',
        'nilai_terbobot',
        'catatan'
    ];

    protected $casts = [
        'nilai' => 'integer',
        'nilai_normalisasi' => 'decimal:4',
        'nilai_terbobot' => 'decimal:4'
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    public function juri(): BelongsTo
    {
        return $this->belongsTo(Juri::class);
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class);
    }

    // Normalisasi nilai untuk SMART method
    public function normalisasiNilai($maxNilai, $minNilai)
    {
        if ($maxNilai - $minNilai == 0) return 0;

        if ($this->kriteria->atribut === 'benefit') {
            // Untuk benefit: semakin besar semakin baik
            return ($this->nilai - $minNilai) / ($maxNilai - $minNilai);
        } else {
            // Untuk cost: semakin kecil semakin baik
            return ($maxNilai - $this->nilai) / ($maxNilai - $minNilai);
        }
    }

    // Hitung nilai terbobot (normalisasi × bobot)
    public function hitungNilaiTerbobot()
    {
        return $this->nilai_normalisasi * $this->kriteria->getBobotNormalisasi();
    }
}
