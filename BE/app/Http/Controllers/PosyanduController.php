<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PosyanduController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'balita');
        $pemeriksaanList = Posyandu::where('kategori', $kategori)->latest('tanggal_periksa')->paginate(15);

        return view('posyandu.index', compact('pemeriksaanList', 'kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'kategori' => 'required|in:balita,lansia',
            'umur_bulan_atau_tahun' => 'required|integer|min:0',
            'bb_kg' => 'required|numeric|min:0',
            'tb_cm' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'tanggal_periksa' => 'required|date',
            'petugas' => 'required|string|max:255',
        ]);

        $entry = Posyandu::create($validated);
        ActivityLog::log('Pemeriksaan Posyandu', 'Pemeriksaan posyandu pasien: ' . $entry->nama_pasien);

        return redirect()->route('posyandu.index', ['kategori' => $validated['kategori']])->with('success', 'Catatan pemeriksaan kesehatan Posyandu berhasil disimpan.');
    }

    public function destroy(Posyandu $posyandu)
    {
        $kategori = $posyandu->kategori;
        $posyandu->delete();
        ActivityLog::log('Hapus Posyandu', 'Menghapus catatan posyandu ID #' . $posyandu->id);

        return redirect()->route('posyandu.index', ['kategori' => $kategori])->with('success', 'Data posyandu berhasil dihapus.');
    }
}
