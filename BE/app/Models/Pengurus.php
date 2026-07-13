<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    use HasFactory;

    protected $table = 'pengurus';

    protected $fillable = [
        'warga_id',
        'nama',
        'jabatan',
        'periode_mulai',
        'periode_selesai',
        'no_hp',
        'foto',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}
