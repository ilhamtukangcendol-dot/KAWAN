<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\Iuran;
use App\Models\Inventaris;
use App\Models\BankSampah;
use App\Models\SetoranPending;
use Illuminate\Support\Facades\Auth;

class BendaharaController extends Controller
{
    public function index(Request $request)
    {
        $kas = Kas::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->take(8)->get();

        $totalPemasukan = Kas::sum('pemasukan');
        $totalPengeluaran = Kas::sum('pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $iuranPendingCount = Iuran::where('status', 'pending')->count();
        $totalAssetNilai = Inventaris::selectRaw('sum(jumlah * nominal) as total')->value('total') ?? 0;
        $totalBankSampahRupiah = BankSampah::sum('total_harga');

        $view = (Auth::user()->role == 2) ? 'ketua.dashboard' : 'bendahara.dashboard';

        return view($view, compact(
            'kas',
            'saldo',
            'totalPemasukan',
            'totalPengeluaran',
            'iuranPendingCount',
            'totalAssetNilai',
            'totalBankSampahRupiah'
        ));
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

        return redirect()->route('bendahara.dashboard')->with('success', 'Transaksi kas berhasil dicatat!');
    }

    public function persetujuan()
    {
        $daftarPersetujuan = SetoranPending::with('user')
            ->where('status', 'pending')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('bendahara.persetujuan', compact('daftarPersetujuan'));
    }

    public function approveSetoran($id)
    {
        $setoran = SetoranPending::findOrFail($id);
        $setoran->update(['status' => 'approved']);

        Kas::create([
            'keterangan' => $setoran->keterangan . ' (Pembayaran Kas Warga: ' . $setoran->user->name . ')',
            'pemasukan' => $setoran->nominal,
            'pengeluaran' => 0,
            'tanggal' => $setoran->tanggal,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran kas dari ' . $setoran->user->name . ' berhasil disetujui dan dibukukan!');
    }

    public function rejectSetoran($id)
    {
        $setoran = SetoranPending::findOrFail($id);
        $setoran->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Pembayaran kas dari ' . $setoran->user->name . ' telah ditolak.');
    }
}