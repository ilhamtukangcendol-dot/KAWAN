<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $table = 'kas';

    // Daftarkan kolom agar bisa diisi (Mass Assignment)
    protected $fillable = [
        'keterangan',
        'pemasukan',
        'pengeluaran',
        'tanggal',
        'user_id'
    ];
}