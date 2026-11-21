<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotPortalLog extends Model
{
    use HasFactory;

    protected $table = 'iot_portal_logs';

    protected $fillable = [
        'device_id',
        'rfid_uid',
        'user_id',
        'status',
        'access_type',
        'user_name',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsGrantedAttribute(): bool
    {
        return $this->status === 'granted';
    }
}

