<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class KasController extends Controller
{
    public function index(Request $request)
    {
        $kasList = Kas::with('user')->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(15);

        $totalPemasukan = Kas::sum('pemasukan');
        $totalPengeluaran = Kas::sum('pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('kas.index', compact('kasList', 'totalPemasukan', 'totalPengeluaran', 'saldo'));
    }

    public function pemasukan()
    {
        $pemasukanList = Kas::with('user')->where('pemasukan', '>', 0)->latest('tanggal')->paginate(15);
        $totalPemasukan = Kas::sum('pemasukan');

        return view('kas.pemasukan', compact('pemasukanList', 'totalPemasukan'));
    }

    public function pengeluaran()
    {
        $pengeluaranList = Kas::with('user')->where('pengeluaran', '>', 0)->latest('tanggal')->paginate(15);
        $totalPengeluaran = Kas::sum('pengeluaran');

        return view('kas.pengeluaran', compact('pengeluaranList', 'totalPengeluaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'tipe' => 'required|in:masuk,keluar',
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
        ]);

        $pemasukan = $request->tipe === 'masuk' ? $request->nominal : 0;
        $pengeluaran = $request->tipe === 'keluar' ? $request->nominal : 0;

        $kas = Kas::create([
            'keterangan' => $request->keterangan,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'tanggal' => $request->tanggal,
            'user_id' => auth()->id(),
        ]);

        ActivityLog::log('Input Transaksi Kas', 'Mencatat transaksi kas ' . $request->tipe . ' ID #' . $kas->id . ' Rp ' . number_format($request->nominal, 0, ',', '.'));

        return redirect()->back()->with('success', 'Transaksi kas berhasil dicatat!');
    }

    public function update(Request $request, Kas $ka)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'pemasukan' => 'required|numeric|min:0',
            'pengeluaran' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $ka->update($request->only(['keterangan', 'pemasukan', 'pengeluaran', 'tanggal']));
        ActivityLog::log('Update Transaksi Kas', 'Memperbarui transaksi kas ID #' . $ka->id);

        return redirect()->back()->with('success', 'Transaksi kas berhasil diperbarui!');
    }

    public function destroy(Kas $ka)
    {
        $id = $ka->id;
        $ka->delete();
        ActivityLog::log('Hapus Transaksi Kas', 'Menghapus transaksi kas ID #' . $id);

        return redirect()->back()->with('success', 'Data transaksi kas berhasil dihapus.');
    }
}