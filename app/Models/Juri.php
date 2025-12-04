<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Juri extends Model
{
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'email',
        'keahlian',
        'institusi',
        'kontak',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    // Get semua penilaian yang sudah dilakukan oleh juri ini
    public function getAllPenilaian()
    {
        return $this->penilaians()->with(['peserta', 'kriteria'])->get();
    }

    // Check apakah juri sudah menilai peserta tertentu
    public function sudahMenilai($peserta_id, $kriteria_id)
    {
        return $this->penilaians()
            ->where('peserta_id', $peserta_id)
            ->where('kriteria_id', $kriteria_id)
            ->exists();
    }
}
