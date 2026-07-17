<?php

namespace App\Http\Controllers;

use App\Models\Ronda;
use App\Models\Warga;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RondaController extends Controller
{
    public function index()
    {
        $jadwalRonda = Ronda::latest('tanggal')->get();
        $wargaList   = Warga::orderBy('nama_lengkap', 'asc')->get();

        return view('ronda.index', compact('jadwalRonda', 'wargaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari'           => 'required|string|max:50',
            'tanggal'        => 'required|date',
            'regu'           => 'required|string|max:100',
            'anggota_ids'    => 'nullable|array',
            'anggota_ids.*'  => 'exists:warga,id',
            'anggota_manual' => 'nullable|string',
            'pos_ronda'      => 'required|string|max:255',
        ]);

        // Bangun string anggota dari warga yang dipilih + input manual
        $anggotaWarga = '';
        if (!empty($validated['anggota_ids'])) {
            $namaWarga    = Warga::whereIn('id', $validated['anggota_ids'])
                ->orderBy('nama_lengkap')
                ->pluck('nama_lengkap')
                ->toArray();
            $anggotaWarga = implode(', ', $namaWarga);
        }
        // Jika ada tambahan manual, gabungkan
        if (!empty($validated['anggota_manual'])) {
            $anggotaWarga = $anggotaWarga
                ? $anggotaWarga . ', ' . $validated['anggota_manual']
                : $validated['anggota_manual'];
        }

        $ronda = Ronda::create([
            'hari'          => $validated['hari'],
            'tanggal'       => $validated['tanggal'],
            'regu'          => $validated['regu'],
            'anggota_warga' => $anggotaWarga,
            'pos_ronda'     => $validated['pos_ronda'],
            'status_piket'  => 'belum',
        ]);

        ActivityLog::log('Tambah Jadwal Ronda', 'Membuat jadwal ronda malam ' . $ronda->hari . ' (' . $ronda->regu . ')');

        return redirect()->route('ronda.index')->with('success', 'Jadwal ronda malam berhasil ditambahkan.');
    }

    public function updateLaporan(Request $request, Ronda $ronda)
    {
        $validated = $request->validate([
            'laporan_kejadian' => 'required|string',
            'status_piket'     => 'required|in:selesai,berjalan,belum',
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
