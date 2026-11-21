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

    public const ROLE_SUPERADMIN = 1;     // Super Admin
    public const ROLE_RT = 2;             // Ketua RT
    public const ROLE_BENDAHARA = 3;      // Bendahara RT (alias legacy)
    public const ROLE_BENDAHARA_RT = 3;   // Bendahara RT
    public const ROLE_WARGA = 4;          // Warga
    public const ROLE_RW = 5;             // Ketua RW / RW
    public const ROLE_SEKRETARIS_RW = 6;  // Sekretaris RW
    public const ROLE_BENDAHARA_RW = 7;   // Bendahara RW
    public const ROLE_SEKRETARIS_RT = 8;  // Sekretaris RT

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
        'rfid_uid',
        'rfid_registered_at',
        'portal_access_enabled',
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

    public function isRw(): bool
    {
        return (int) $this->role === self::ROLE_RW;
    }

    public function isSekretarisRw(): bool
    {
        return (int) $this->role === self::ROLE_SEKRETARIS_RW;
    }

    public function isBendaharaRw(): bool
    {
        return (int) $this->role === self::ROLE_BENDAHARA_RW;
    }

    public function isRt(): bool
    {
        return (int) $this->role === self::ROLE_RT;
    }

    public function isSekretarisRt(): bool
    {
        return (int) $this->role === self::ROLE_SEKRETARIS_RT;
    }

    public function isBendaharaRt(): bool
    {
        return (int) $this->role === self::ROLE_BENDAHARA_RT;
    }

    public function isBendahara(): bool
    {
        return in_array((int) $this->role, [self::ROLE_BENDAHARA_RT, self::ROLE_BENDAHARA_RW]);
    }

    public function isSekretaris(): bool
    {
        return in_array((int) $this->role, [self::ROLE_SEKRETARIS_RT, self::ROLE_SEKRETARIS_RW]);
    }

    public function isWarga(): bool
    {
        return (int) $this->role === self::ROLE_WARGA;
    }

    /**
     * Cek apakah user adalah pengurus (RT, RW, atau Superadmin)
     */
    public function isPengurus(): bool
    {
        return in_array((int) $this->role, [
            self::ROLE_SUPERADMIN,
            self::ROLE_RW,
            self::ROLE_SEKRETARIS_RW,
            self::ROLE_BENDAHARA_RW,
            self::ROLE_RT,
            self::ROLE_SEKRETARIS_RT,
            self::ROLE_BENDAHARA_RT,
        ]);
    }

    public function getRoleNameAttribute(): string
    {
        return match ((int) $this->role) {
            self::ROLE_SUPERADMIN => 'superadmin',
            self::ROLE_RW => 'rw',
            self::ROLE_SEKRETARIS_RW => 'sekretaris_rw',
            self::ROLE_BENDAHARA_RW => 'bendahara_rw',
            self::ROLE_RT => 'rt',
            self::ROLE_SEKRETARIS_RT => 'sekretaris_rt',
            self::ROLE_BENDAHARA_RT => 'bendahara_rt',
            self::ROLE_WARGA => 'warga',
            default => 'warga',
        };
    }

    public function getRoleLabelAttribute(): string
    {
        return match ((int) $this->role) {
            self::ROLE_SUPERADMIN => 'Super Admin',
            self::ROLE_RW => 'Ketua RW',
            self::ROLE_SEKRETARIS_RW => 'Sekretaris RW',
            self::ROLE_BENDAHARA_RW => 'Bendahara RW',
            self::ROLE_RT => 'Ketua RT',
            self::ROLE_SEKRETARIS_RT => 'Sekretaris RT',
            self::ROLE_BENDAHARA_RT => 'Bendahara RT',
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

    /**
     * Riwayat akses portal RFID/NFC
     */
    public function portalLogs()
    {
        return $this->hasMany(IotPortalLog::class);
    }

    /**
     * Helper cek apakah sudah mendaftarkan kartu KTP
     */
    public function hasRegisteredKtp(): bool
    {
        return !empty($this->rfid_uid);
    }
}
