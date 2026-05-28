<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class KasController extends Controller
{
    /**
     * Menyimpan transaksi baru dari form Bendahara
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'tipe' => 'required|in:masuk,keluar',
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
        ]);

        // 2. Logika Pemasukan vs Pengeluaran
        $pemasukan = $request->tipe == 'masuk' ? $request->nominal : 0;
        $pengeluaran = $request->tipe == 'keluar' ? $request->nominal : 0;

        // 3. Simpan ke Database
        Kas::create([
            'keterangan' => $request->keterangan,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'tanggal' => $request->tanggal,
            'user_id' => Auth::id(), // Mencatat siapa yang input
        ]);

        // 4. Kembali ke Dashboard dengan Notifikasi
        return redirect()->back()->with('success', 'Transaksi berhasil dicatat!');
    }

    /**
     * Menghapus transaksi (Opsional jika dibutuhkan)
     */
    public function destroy($id)
    {
        $kas = Kas::findOrFail($id);
        $kas->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}