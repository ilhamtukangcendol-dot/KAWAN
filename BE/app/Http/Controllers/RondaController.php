<?php

namespace App\Http\Controllers;

use App\Models\Ronda;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RondaController extends Controller
{
    public function index()
    {
        $jadwalRonda = Ronda::latest('tanggal')->get();
        return view('ronda.index', compact('jadwalRonda'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'regu' => 'required|string|max:100',
            'anggota_warga' => 'required|string',
            'pos_ronda' => 'required|string|max:255',
        ]);

        $ronda = Ronda::create([
            'hari' => $validated['hari'],
            'tanggal' => $validated['tanggal'],
            'regu' => $validated['regu'],
            'anggota_warga' => $validated['anggota_warga'],
            'pos_ronda' => $validated['pos_ronda'],
            'status_piket' => 'belum',
        ]);

        ActivityLog::log('Tambah Jadwal Ronda', 'Membuat jadwal ronda malam ' . $ronda->hari . ' (' . $ronda->regu . ')');

        return redirect()->route('ronda.index')->with('success', 'Jadwal ronda malam berhasil ditambahkan.');
    }

    public function updateLaporan(Request $request, Ronda $ronda)
    {
        $validated = $request->validate([
            'laporan_kejadian' => 'required|string',
            'status_piket' => 'required|in:selesai,berjalan,belum',
        ]);

        $ronda->update($validated);
        ActivityLog::log('Laporan Patroli Ronda', 'Update laporan ronda ID #' . $ronda->id);

        return redirect()->back()->with('success', 'Laporan kejadian ronda malam berhasil disimpan.');
    }

    public function destroy(Ronda $ronda)
    {
        $ronda->delete();
        ActivityLog::log('Hapus Jadwal Ronda', 'Menghapus jadwal ronda ID #' . $ronda->id);
        return redirect()->route('ronda.index')->with('success', 'Jadwal ronda berhasil dihapus.');
    }
}
