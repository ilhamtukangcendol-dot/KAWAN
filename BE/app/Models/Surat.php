<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';

    protected $fillable = [
        'user_id',
        'no_surat',
        'jenis_surat',
        'keperluan',
        'status',
        'catatan_rt',
        'file_surat',
        'tanggal_pengajuan',
        'tanggal_disetujui',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
