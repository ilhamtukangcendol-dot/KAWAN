<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotPairingSession extends Model
{
    use HasFactory;

    protected $table = 'iot_pairing_sessions';

    protected $fillable = [
        'user_id',
        'token',
        'status',
        'scanned_uid',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at->isPast();
    }
}

