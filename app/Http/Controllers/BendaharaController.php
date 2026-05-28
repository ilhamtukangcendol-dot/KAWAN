<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BendaharaController extends Controller
{
    public function index(Request $request)
    {
        $days = $request->get('days');

        if ($days && is_numeric($days)) {
            $kas = Kas::where('tanggal', '>=', now()->subDays($days))->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();
        } else {
            $kas = Kas::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->take(10)->get();
        }

        $totalPemasukan = Kas::sum('pemasukan');
        $totalPengeluaran = Kas::sum('pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Cek siapa yang login, jika Ketua (1) arahkan ke view ketua, jika Bendahara (2) ke view bendahara
        $view = (Auth::user()->role == 1) ? 'ketua.dashboard' : 'bendahara.dashboard';
        return view($view, compact('kas', 'saldo', 'totalPemasukan', 'totalPengeluaran'));
    }

    public function iuran()
    {
        $iuran = Kas::where('pemasukan', '>', 0)->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(15);
        $totalIuran = Kas::sum('pemasukan');
        return view('bendahara.iuran', compact('iuran', 'totalIuran'));
    }

    public function laporan(Request $request)
    {
        $dari = $request->get('dari', date('Y-m-01'));
        $sampai = $request->get('sampai', date('Y-m-d'));

        $dataLaporan = Kas::whereBetween('tanggal', [$dari, $sampai])->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();
        $totalMasuk = $dataLaporan->sum('pemasukan');
        $totalKeluar = $dataLaporan->sum('pengeluaran');
        $saldoPeriode = $totalMasuk - $totalKeluar;

        return view('bendahara.laporan', compact('dataLaporan', 'totalMasuk', 'totalKeluar', 'saldoPeriode', 'dari', 'sampai'));
    }

    public function create() { return view('bendahara.entri'); }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required',
            'tanggal' => 'required|date',
            'jenis' => 'required',
            'nominal' => 'required|numeric',
        ]);

        $kas = new Kas();
        $kas->keterangan = $request->keterangan;
        $kas->tanggal = $request->tanggal;
        if ($request->jenis == 'masuk') {
            $kas->pemasukan = $request->nominal; $kas->pengeluaran = 0;
        } else {
            $kas->pemasukan = 0; $kas->pengeluaran = $request->nominal;
        }
        $kas->save();

        return redirect()->route('bendahara.dashboard')->with('success', 'Berhasil!');
    }

    /**
     * Tampilkan antrean persetujuan pembayaran kas warga
     */
    public function persetujuan()
    {
        $daftarPersetujuan = \App\Models\SetoranPending::with('user')
            ->where('status', 'pending')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('bendahara.persetujuan', compact('daftarPersetujuan'));
    }

    /**
     * Setujui pembayaran kas masuk warga
     */
    public function approveSetoran($id)
    {
        $setoran = \App\Models\SetoranPending::findOrFail($id);
        $setoran->update(['status' => 'approved']);

        // Masukkan data transaksi ke kas RT utama (Pemasukan)
        Kas::create([
            'keterangan' => $setoran->keterangan . ' (Pembayaran Kas Warga: ' . $setoran->user->name . ')',
            'pemasukan' => $setoran->nominal,
            'pengeluaran' => 0,
            'tanggal' => $setoran->tanggal,
            'user_id' => auth()->id(), // Mencatat bendahara yang memverifikasi/menyetujui
        ]);

        return redirect()->back()->with('success', 'Pembayaran kas dari ' . $setoran->user->name . ' berhasil disetujui dan dibukukan!');
    }

    /**
     * Tolak pembayaran kas masuk warga
     */
    public function rejectSetoran($id)
    {
        $setoran = \App\Models\SetoranPending::findOrFail($id);
        $setoran->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Pembayaran kas dari ' . $setoran->user->name . ' telah ditolak.');
    }
}