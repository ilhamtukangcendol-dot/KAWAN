<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function index() {
        $saldo = \App\Models\Kas::sum('pemasukan') - \App\Models\Kas::sum('pengeluaran');
        return view('warga.dashboard', compact('saldo'));
    }

    /**
     * Tampilkan form pembayaran kas oleh warga secara mandiri
     */
    public function paymentForm()
    {
        $saldo = \App\Models\Kas::sum('pemasukan') - \App\Models\Kas::sum('pengeluaran');
        return view('warga.bayar', compact('saldo'));
    }

    /**
     * Simpan transaksi pembayaran kas mandiri dari warga (Masuk antrean persetujuan)
     */
    public function storePayment(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        \App\Models\SetoranPending::create([
            'user_id' => auth()->id(), // Mencatat warga yang menyetor
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'status' => 'pending', // Menunggu persetujuan Bendahara
        ]);

        return redirect()->route('warga.dashboard')->with('success', 'Setoran kas RT Anda berhasil dikirim! Menunggu konfirmasi dari Ibu Bendahara.');
    }

    /**
     * Tampilkan informasi metode pembayaran kas RT
     */
    public function paymentMethods()
    {
        return view('warga.metode');
    }

    /**
     * Tampilkan riwayat setoran iuran mandiri warga yang sedang login
     */
    public function history()
    {
        $riwayatSetoran = \App\Models\SetoranPending::where('user_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('warga.history', compact('riwayatSetoran'));
    }
}
