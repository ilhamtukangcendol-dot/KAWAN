<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranPending extends Model
{
    use HasFactory;

    protected $table = 'setoran_pending';

    protected $fillable = [
        'user_id',
        'nominal',
        'keterangan',
        'tanggal',
        'status'
    ];

    /**
     * Hubungan relasi ke model User (warga yang menyetor)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
