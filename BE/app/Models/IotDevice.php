<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotDevice extends Model
{
    use HasFactory;

    protected $table = 'iot_devices';

    protected $fillable = [
        'device_id',
        'name',
        'location',
        'secret_key',
        'ip_address',
        'status',
        'last_ping_at',
    ];

    protected function casts(): array
    {
        return [
            'last_ping_at' => 'datetime',
        ];
    }

    /**
     * Cek apakah perangkat aktif dalam 2 menit terakhir
     */
    public function getIsOnlineAttribute(): bool
    {
        if (!$this->last_ping_at) {
            return false;
        }

        return $this->last_ping_at->diffInMinutes(now()) <= 2;
    }
}

