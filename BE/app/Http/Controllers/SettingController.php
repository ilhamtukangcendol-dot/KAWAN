<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $settings = [
            'nama_rt' => Setting::get('nama_rt', 'RT 01 / RW 05 Komp. Mawar Asri'),
            'alamat_rt' => Setting::get('alamat_rt', 'Jl. Mawar Asri No. 1, Bandung'),
            'nominal_iuran' => Setting::get('nominal_iuran', '50000'),
            'nominal_rukem' => Setting::get('nominal_rukem', '1000000'),
            'kontak_rt' => Setting::get('kontak_rt', '081234567890'),
        ];

        return view('settings.index', compact('settings', 'user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. Profil & Foto Pengguna (Berlaku untuk SEMUA Role)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Handling Hapus Foto Profil
        if ($request->has('remove_avatar') && $request->remove_avatar == '1') {
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }
            $user->avatar = null;
        }

        // Handling Upload Foto Profil Baru
        if ($request->hasFile('avatar')) {
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            $user->avatar = 'uploads/avatars/' . $filename;
        }

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // 2. Pengaturan Identitas & Nilai Standar RT (Hanya Superadmin, RT, & Bendahara)
        if ($user->role <= 3) {
            $validatedRt = $request->validate([
                'nama_rt' => 'required|string|max:255',
                'alamat_rt' => 'required|string|max:255',
                'nominal_iuran' => 'required|numeric|min:0',
                'nominal_rukem' => 'required|numeric|min:0',
                'kontak_rt' => 'required|string|max:100',
            ]);

            foreach ($validatedRt as $key => $val) {
                Setting::set($key, $val);
            }
        }

        ActivityLog::log('Update Pengaturan', 'Memperbarui foto profil / akun / identitas RT untuk ' . $user->name);

        return redirect()->route('settings.index')->with('success', 'Profil & foto pengguna berhasil diperbarui!');
    }
}
