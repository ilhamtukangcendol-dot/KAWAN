<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rukem extends Model
{
    use HasFactory;

    protected $table = 'rukem';

    protected $fillable = [
        'warga_id',
        'nama_almarhum',
        'tanggal_wafat',
        'ahli_waris',
        'nominal_santunan',
        'status',
        'keterangan',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}
