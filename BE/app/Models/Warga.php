<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'warga';

    protected $fillable = [
        'nik',
        'no_kk',
        'nama_lengkap',
        'jenis_kelamin',
        'umur',
        'status_keluarga',
        'status_tinggal',
        'alamat',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->user && $this->user->avatar) {
            return asset($this->user->avatar);
        }

        $matchedUser = User::whereRaw('LOWER(name) = ?', [strtolower($this->nama_lengkap)])->first();
        if ($matchedUser && $matchedUser->avatar) {
            return asset($matchedUser->avatar);
        }

        return null;
    }
}