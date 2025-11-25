<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $fillable = [
        'nama_kriteria',
        'deskripsi',
        'bobot',
        'bobot_borda',
        'nilai_max',
        'nilai_min',
        'atribut',
        'is_active'
    ];

    protected $casts = [
        'bobot' => 'decimal:3',
        'bobot_borda' => 'decimal:3',
        'nilai_max' => 'integer',
        'nilai_min' => 'integer',
        'is_active' => 'boolean'
    ];

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    // Get total bobot semua kriteria (untuk normalisasi SMART)
    public static function getTotalBobot()
    {
        return self::where('is_active', true)->sum('bobot');
    }

    // Normalisasi bobot untuk SMART method
    public function getBobotNormalisasi()
    {
        $totalBobot = self::getTotalBobot();
        return $totalBobot > 0 ? $this->bobot / $totalBobot : 0;
    }
}
