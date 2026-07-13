<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Kas;
use App\Models\Pengumuman;
use App\Models\Kegiatan;
use App\Models\Umkm;
use App\Models\Pengurus;
use App\Models\Setting;

class ApiController extends Controller
{
    /**
     * Handle user authentication and issue a Sanctum token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kredensial yang diberikan salah.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_name' => $user->role_name,
                'role_label' => $user->role_label,
            ]
        ]);
    }

    /**
     * Register a new citizen user account.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_WARGA, // default role for public registration
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_name' => $user->role_name,
                'role_label' => $user->role_label,
            ]
        ], 201);
    }

    /**
     * Revoke the current user's session token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil keluar.'
        ]);
    }

    /**
     * Fetch public dashboard / welcome statistics.
     */
    public function getPublicData()
    {
        $totalPemasukan = Kas::sum('pemasukan');
        $totalPengeluaran = Kas::sum('pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $pengumumanList = Pengumuman::where('is_active', true)->latest()->take(4)->get();
        $kegiatanList = Kegiatan::where('tanggal', '>=', date('Y-m-d'))->orderBy('tanggal', 'asc')->take(4)->get();
        
        $umkmList = Umkm::where('is_active', true)->latest()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_usaha' => $item->nama_usaha,
                'deskripsi' => $item->deskripsi,
                'kategori' => $item->kategori,
                'pemilik' => $item->pemilik,
                'kontak' => $item->kontak,
                'gambar' => $item->gambar,
                'is_active' => $item->is_active,
            ];
        });
        
        $umkmKategori = Umkm::where('is_active', true)->select('kategori')->distinct()->pluck('kategori');
        $pengurusList = Pengurus::with('warga')->take(4)->get();

        $settings = [
            'nama_rt' => Setting::get('nama_rt', 'RT 01 / RW 05 Komp. Mawar Asri'),
            'alamat_rt' => Setting::get('alamat_rt', 'Jl. Mawar Asri No. 1, Bandung'),
            'kontak_rt' => Setting::get('kontak_rt', '0812-3456-7890'),
            'nominal_iuran' => Setting::get('nominal_iuran', '50000'),
            'nominal_rukem' => Setting::get('nominal_rukem', '1000000'),
        ];

        $totalWarga = \App\Models\Warga::count();
        $totalKK = \App\Models\Warga::distinct('no_kk')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'saldo' => $saldo,
                'totalPemasukan' => $totalPemasukan,
                'totalPengeluaran' => $totalPengeluaran,
                'pengumumanList' => $pengumumanList,
                'kegiatanList' => $kegiatanList,
                'umkmList' => $umkmList,
                'umkmKategori' => $umkmKategori,
                'pengurusList' => $pengurusList,
                'settings' => $settings,
                'totalWarga' => $totalWarga,
                'totalKK' => $totalKK
            ]
        ]);
    }
}
