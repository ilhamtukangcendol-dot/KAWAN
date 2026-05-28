<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    // Menghubungkan ke tabel 'warga'
    protected $table = 'warga';

    protected $fillable = ['nik', 'no_kk', 'nama_lengkap', 'jenis_kelamin', 'umur', 'status_tinggal', 'alamat', 'user_id'];

    /**
     * Get the user account linked to this citizen.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the avatar URL from linked user or matched name fallback (case-insensitive).
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->user && $this->user->avatar) {
            return asset($this->user->avatar);
        }

        // Fallback: match by name (case-insensitive)
        $matchedUser = User::whereRaw('LOWER(name) = ?', [strtolower($this->nama_lengkap)])->first();
        if ($matchedUser && $matchedUser->avatar) {
            return asset($matchedUser->avatar);
        }

        return null;
    }
}