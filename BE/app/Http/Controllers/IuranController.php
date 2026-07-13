<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\Warga;
use App\Models\Kas;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $status = $request->get('status');

        $query = Iuran::with(['warga', 'verifier'])->where('tahun', $tahun);

        if ($status) {
            $query->where('status', $status);
        }

        $iuranList = $query->latest()->paginate(15)->withQueryString();

        $totalTerkumpul = Iuran::where('tahun', $tahun)->where('status', 'paid')->sum('nominal');
        $totalPending = Iuran::where('tahun', $tahun)->where('status', 'pending')->sum('nominal');

        $wargaList = Warga::orderBy('nama_lengkap', 'asc')->get();

        return view('iuran.index', compact('iuranList', 'totalTerkumpul', 'totalPending', 'tahun', 'status', 'wargaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:warga,id',
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
            'nominal' => 'required|numeric|min:0',
        ]);

        $exists = Iuran::where('warga_id', $validated['warga_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Tagihan iuran untuk warga dan periode ini sudah ada.');
        }

        $iuran = Iuran::create([
            'warga_id' => $validated['warga_id'],
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
            'nominal' => $validated['nominal'],
            'status' => 'unpaid',
        ]);

        ActivityLog::log('Create Tagihan Iuran', 'Menambahkan tagihan iuran ID #' . $iuran->id);

        return redirect()->route('iuran.index')->with('success', 'Tagihan iuran warga berhasil dibuat.');
    }

    public function pay(Request $request, Iuran $iuran)
    {
        $request->validate([
            'bukti_bayar' => 'nullable|image|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('bukti_bayar')) {
            $filePath = 'uploads/iuran/' . time() . '_' . $request->file('bukti_bayar')->getClientOriginalName();
            $request->file('bukti_bayar')->move(public_path('uploads/iuran'), basename($filePath));
            $iuran->bukti_bayar = $filePath;
        }

        $iuran->status = (auth()->user()->role <= 3) ? 'paid' : 'pending';
        $iuran->tanggal_bayar = now()->format('Y-m-d');
        if (auth()->user()->role <= 3) {
            $iuran->verified_by = auth()->id();

            // Auto-book into Kas
            Kas::create([
                'keterangan' => 'Iuran Warga Bulanan (' . $iuran->bulan_nama . ' ' . $iuran->tahun . ') - ' . $iuran->warga->nama_lengkap,
                'pemasukan' => $iuran->nominal,
                'pengeluaran' => 0,
                'tanggal' => now()->format('Y-m-d'),
                'user_id' => auth()->id(),
            ]);
        }
        $iuran->save();

        ActivityLog::log('Bayar Iuran', 'Setoran iuran iuran ID #' . $iuran->id);

        return redirect()->back()->with('success', 'Pembayaran iuran berhasil diproses.');
    }

    public function verify(Iuran $iuran)
    {
        $iuran->status = 'paid';
        $iuran->verified_by = auth()->id();
        $iuran->save();

        // Auto-book into Kas
        Kas::create([
            'keterangan' => 'Iuran Warga Bulanan (' . $iuran->bulan_nama . ' ' . $iuran->tahun . ') - ' . $iuran->warga->nama_lengkap,
            'pemasukan' => $iuran->nominal,
            'pengeluaran' => 0,
            'tanggal' => $iuran->tanggal_bayar ?? now()->format('Y-m-d'),
            'user_id' => auth()->id(),
        ]);

        ActivityLog::log('Verifikasi Iuran', 'Menyetujui iuran ID #' . $iuran->id);

        return redirect()->back()->with('success', 'Iuran berhasil diverifikasi dan dibukukan ke Kas.');
    }

    public function destroy(Iuran $iuran)
    {
        $iuran->delete();
        ActivityLog::log('Hapus Iuran', 'Menghapus data iuran ID #' . $iuran->id);
        return redirect()->route('iuran.index')->with('success', 'Data iuran berhasil dihapus.');
    }
}
