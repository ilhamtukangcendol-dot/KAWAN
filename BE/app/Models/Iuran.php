<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    use HasFactory;

    protected $table = 'iuran';

    protected $fillable = [
        'warga_id',
        'bulan',
        'tahun',
        'nominal',
        'metode_pembayaran',
        'status',
        'bukti_bayar',
        'tanggal_bayar',
        'verified_by',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getBulanNamaAttribute()
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $namaBulan[(int) $this->bulan] ?? '-';
    }
}
