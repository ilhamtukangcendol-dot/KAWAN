<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    use HasFactory;

    protected $table = 'umkm';

    protected $fillable = [
        'user_id',
        'nama_usaha',
        'pemilik',
        'kategori',
        'deskripsi',
        'alamat',
        'no_whatsapp',
        'foto',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
