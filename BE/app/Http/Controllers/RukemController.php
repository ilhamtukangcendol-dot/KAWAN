<?php

namespace App\Http\Controllers;

use App\Models\Rukem;
use App\Models\Warga;
use App\Models\Kas;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RukemController extends Controller
{
    public function index()
    {
        $rukemList = Rukem::with('warga')->latest('tanggal_wafat')->paginate(15);
        $wargaList = Warga::orderBy('nama_lengkap', 'asc')->get();
        $totalSantunanPaid = Rukem::where('status', 'dicairkan')->sum('nominal_santunan');

        return view('rukem.index', compact('rukemList', 'wargaList', 'totalSantunanPaid'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id' => 'nullable|exists:warga,id',
            'nama_almarhum' => 'required|string|max:255',
            'tanggal_wafat' => 'required|date',
            'ahli_waris' => 'required|string|max:255',
            'nominal_santunan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $status = (auth()->user()->role <= 3) ? 'dicairkan' : 'pending';

        $rukem = Rukem::create([
            'warga_id' => $validated['warga_id'] ?? null,
            'nama_almarhum' => $validated['nama_almarhum'],
            'tanggal_wafat' => $validated['tanggal_wafat'],
            'ahli_waris' => $validated['ahli_waris'],
            'nominal_santunan' => $validated['nominal_santunan'],
            'status' => $status,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        if ($status === 'dicairkan') {
            Kas::create([
                'keterangan' => 'Pencairan Dana Santunan Duka (Rukem) - Alm/h ' . $rukem->nama_almarhum,
                'pemasukan' => 0,
                'pengeluaran' => $rukem->nominal_santunan,
                'tanggal' => now()->format('Y-m-d'),
                'user_id' => auth()->id(),
            ]);
        }

        ActivityLog::log('Pendaftaran Rukem', 'Laporan duka Rukem ID #' . $rukem->id . ' Alm/h ' . $rukem->nama_almarhum);

        return redirect()->route('rukem.index')->with('success', 'Data Rukun Kematian (Rukem) berhasil dicatat.');
    }

    public function cairkan(Rukem $rukem)
    {
        $rukem->status = 'dicairkan';
        $rukem->save();

        Kas::create([
            'keterangan' => 'Pencairan Dana Santunan Duka (Rukem) - Alm/h ' . $rukem->nama_almarhum,
            'pemasukan' => 0,
            'pengeluaran' => $rukem->nominal_santunan,
            'tanggal' => now()->format('Y-m-d'),
            'user_id' => auth()->id(),
        ]);

        ActivityLog::log('Cairkan Santunan Rukem', 'Pencairan santunan duka ID #' . $rukem->id);

        return redirect()->back()->with('success', 'Santunan duka berhasil dicairkan dan dibukukan ke Kas Pengeluaran.');
    }

    public function destroy(Rukem $rukem)
    {
        $rukem->delete();
        ActivityLog::log('Hapus Data Rukem', 'Menghapus catatan Rukem ID #' . $rukem->id);

        return redirect()->route('rukem.index')->with('success', 'Data Rukem berhasil dihapus.');
    }
}
