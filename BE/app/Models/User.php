<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_SUPERADMIN = 1;
    public const ROLE_RT = 2;
    public const ROLE_BENDAHARA = 3;
    public const ROLE_WARGA = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return (int) $this->role === self::ROLE_SUPERADMIN;
    }

    public function isRt(): bool
    {
        return (int) $this->role === self::ROLE_RT;
    }

    public function isBendahara(): bool
    {
        return (int) $this->role === self::ROLE_BENDAHARA;
    }

    public function isWarga(): bool
    {
        return (int) $this->role === self::ROLE_WARGA;
    }

    public function getRoleNameAttribute(): string
    {
        return match ((int) $this->role) {
            self::ROLE_SUPERADMIN => 'superadmin',
            self::ROLE_RT => 'rt',
            self::ROLE_BENDAHARA => 'bendahara',
            self::ROLE_WARGA => 'warga',
            default => 'warga',
        };
    }

    public function getRoleLabelAttribute(): string
    {
        return match ((int) $this->role) {
            self::ROLE_SUPERADMIN => 'Super Admin',
            self::ROLE_RT => 'Ketua RT',
            self::ROLE_BENDAHARA => 'Bendahara',
            self::ROLE_WARGA => 'Warga',
            default => 'Warga',
        };
    }

    /**
     * Get the citizen details linked to this user account.
     */
    public function warga()
    {
        return $this->hasOne(Warga::class);
    }
}
