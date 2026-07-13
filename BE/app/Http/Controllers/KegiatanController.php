<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatanList = Kegiatan::orderBy('tanggal', 'desc')->paginate(12);
        return view('kegiatan.index', compact('kegiatanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'waktu' => 'required|string|max:100',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'penanggung_jawab' => 'required|string|max:255',
            'anggaran' => 'required|numeric|min:0',
        ]);

        $kegiatan = Kegiatan::create($validated);
        ActivityLog::log('Tambah Agenda Kegiatan', 'Menambahkan agenda RT: ' . $kegiatan->nama_kegiatan);

        return redirect()->route('kegiatan.index')->with('success', 'Agenda kegiatan RT berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'waktu' => 'required|string|max:100',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'penanggung_jawab' => 'required|string|max:255',
            'anggaran' => 'required|numeric|min:0',
        ]);

        $kegiatan->update($validated);
        ActivityLog::log('Update Kegiatan', 'Memperbarui data kegiatan ID #' . $kegiatan->id);

        return redirect()->route('kegiatan.index')->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        ActivityLog::log('Hapus Kegiatan', 'Menghapus agenda kegiatan ID #' . $kegiatan->id);
        return redirect()->route('kegiatan.index')->with('success', 'Agenda kegiatan berhasil dihapus.');
    }
}
