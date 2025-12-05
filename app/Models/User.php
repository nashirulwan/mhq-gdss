<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_active',
        'last_login_at',
        'profile_photo',
        'nomor_induk',
        'institusi'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Role constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_JURI = 'juri';
    const ROLE_PESERTA = 'peserta';

    /**
     * Available roles
     */
    public static $roles = [
        self::ROLE_ADMIN,
        self::ROLE_JURI,
        self::ROLE_PESERTA,
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is judge (juri)
     */
    public function isJuri(): bool
    {
        return $this->role === self::ROLE_JURI;
    }

    /**
     * Check if user is participant
     */
    public function isPeserta(): bool
    {
        return $this->role === self::ROLE_PESERTA;
    }

    /**
     * Get role label in Indonesian
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_JURI => 'Juri',
            self::ROLE_PESERTA => 'Peserta',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Scope active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Relationship with profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Relationship with penilaians as judge
     */
    public function penilaiansAsJuri()
    {
        return $this->hasMany(Penilaian::class, 'juri_id');
    }

    /**
     * Relationship with peserta record (if user is participant)
     */
    public function peserta()
    {
        return $this->hasOne(Peserta::class, 'user_id');
    }

    /**
     * Relationship with juri record (if user is judge)
     */
    public function juri()
    {
        return $this->hasOne(Juri::class, 'user_id');
    }

    /**
     * Set password attribute with proper hashing
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get dashboard route based on role
     */
    public function getDashboardRouteAttribute(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'admin.dashboard',
            self::ROLE_JURI => 'juri.dashboard',
            self::ROLE_PESERTA => 'peserta.dashboard',
            default => 'dashboard'
        };
    }

    /**
     * Check if user can access dashboard
     */
    public function canAccessDashboard(): bool
    {
        return $this->is_active && in_array($this->role, self::$roles);
    }
}
