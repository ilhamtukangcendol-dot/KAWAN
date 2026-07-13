<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoperasiTransaksi extends Model
{
    use HasFactory;

    protected $table = 'koperasi_transaksi';

    protected $fillable = [
        'user_id',
        'koperasi_id',
        'jenis',
        'nominal',
        'status',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Koperasi::class, 'koperasi_id');
    }
}
