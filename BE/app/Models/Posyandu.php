<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    use HasFactory;

    protected $table = 'posyandu';

    protected $fillable = [
        'nama_pasien',
        'kategori',
        'umur_bulan_atau_tahun',
        'bb_kg',
        'tb_cm',
        'catatan',
        'tanggal_periksa',
        'petugas',
    ];
}
