<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'user_id',
        'judul',
        'kategori',
        'isi',
        'attachment',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
