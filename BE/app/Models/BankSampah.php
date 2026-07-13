<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSampah extends Model
{
    use HasFactory;

    protected $table = 'bank_sampah';

    protected $fillable = [
        'user_id',
        'jenis_sampah',
        'berat_kg',
        'harga_per_kg',
        'total_harga',
        'tanggal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
