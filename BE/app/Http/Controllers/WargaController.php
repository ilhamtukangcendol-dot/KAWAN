<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Iuran;
use App\Models\Pengumuman;
use App\Models\Kegiatan;
use App\Models\Ronda;
use App\Models\Surat;
use App\Models\SetoranPending;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalPemasukan = Kas::sum('pemasukan');
        $totalPengeluaran = Kas::sum('pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $myIuranCount = Iuran::where('warga_id', optional($user->warga)->id)
            ->where('tahun', date('Y'))
            ->where('status', 'paid')
            ->count();

        $pengumumanList = Pengumuman::where('is_active', true)->latest()->take(3)->get();
        $kegiatanList = Kegiatan::where('tanggal', '>=', now()->format('Y-m-d'))->orderBy('tanggal', 'asc')->take(3)->get();
        $suratPendingCount = Surat::where('user_id', $user->id)->where('status', 'pending')->count();
        $mySuratLatest = Surat::where('user_id', $user->id)->latest()->take(3)->get();

        return view('warga.dashboard', compact(
            'saldo',
            'totalPemasukan',
            'totalPengeluaran',
            'myIuranCount',
            'pengumumanList',
            'kegiatanList',
            'suratPendingCount',
            'mySuratLatest'
        ));
    }

    public function paymentForm()
    {
        $saldo = Kas::sum('pemasukan') - Kas::sum('pengeluaran');
        return view('warga.bayar', compact('saldo'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        SetoranPending::create([
            'user_id' => auth()->id(),
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'status' => 'pending',
        ]);

        return redirect()->route('warga.dashboard')->with('success', 'Setoran kas RT Anda berhasil dikirim! Menunggu konfirmasi dari Ibu Bendahara.');
    }

    public function paymentMethods()
    {
        return view('warga.metode');
    }

    public function history()
    {
        $riwayatSetoran = SetoranPending::where('user_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('warga.history', compact('riwayatSetoran'));
    }
}
