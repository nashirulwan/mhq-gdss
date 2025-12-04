<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nomor_peserta',
        'instansi',
        'kategori',
        'usia',
        'keahlian',
        'institusi',
        'bio',
        'social_media',
        'website'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'usia' => 'integer',
        ];
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full profile name
     */
    public function getFullNameAttribute(): string
    {
        return $this->user ? $this->user->name : '';
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return match($this->kategori) {
            'anak' => 'Kategori Anak-Anak',
            'remaja' => 'Kategori Remaja',
            'dewasa' => 'Kategori Dewasa',
            default => 'Tidak Diketahui'
        };
    }
}
