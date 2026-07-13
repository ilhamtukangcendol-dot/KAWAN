<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ronda extends Model
{
    use HasFactory;

    protected $table = 'ronda';

    protected $fillable = [
        'hari',
        'tanggal',
        'regu',
        'anggota_warga',
        'pos_ronda',
        'laporan_kejadian',
        'status_piket',
    ];
}
