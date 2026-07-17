<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    use HasFactory;

    protected $table = 'posyandu';

    protected $fillable = [
        'warga_id',
        'pendaftar_warga_id',
        'nama_pasien',
        'nama_pendaftar',
        'kategori',
        'umur_bulan_atau_tahun',
        'bb_kg',
        'tb_cm',
        'catatan',
        'tanggal_periksa',
        'petugas',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function pendaftarWarga()
    {
        return $this->belongsTo(Warga::class, 'pendaftar_warga_id');
    }
}
