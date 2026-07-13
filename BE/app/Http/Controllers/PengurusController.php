<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use App\Models\Warga;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    public function index()
    {
        $pengurusList = Pengurus::with('warga')->orderBy('id', 'asc')->get();
        $wargaList = Warga::orderBy('nama_lengkap', 'asc')->get();
        return view('pengurus.index', compact('pengurusList', 'wargaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id' => 'nullable|exists:warga,id',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'periode_mulai' => 'required|integer',
            'periode_selesai' => 'required|integer',
            'no_hp' => 'nullable|string|max:30',
        ]);

        $pengurus = Pengurus::create($validated);
        ActivityLog::log('Create Pengurus', 'Menambah pengurus RT: ' . $pengurus->nama . ' (' . $pengurus->jabatan . ')');

        return redirect()->route('pengurus.index')->with('success', 'Data pengurus RT berhasil ditambahkan.');
    }

    public function update(Request $request, Pengurus $penguru)
    {
        $validated = $request->validate([
            'warga_id' => 'nullable|exists:warga,id',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'periode_mulai' => 'required|integer',
            'periode_selesai' => 'required|integer',
            'no_hp' => 'nullable|string|max:30',
        ]);

        $penguru->update($validated);
        ActivityLog::log('Update Pengurus', 'Memperbarui data pengurus ID #' . $penguru->id);

        return redirect()->route('pengurus.index')->with('success', 'Data pengurus RT berhasil diperbarui.');
    }

    public function destroy(Pengurus $penguru)
    {
        $nama = $penguru->nama;
        $penguru->delete();
        ActivityLog::log('Hapus Pengurus', 'Menghapus pengurus RT: ' . $nama);

        return redirect()->route('pengurus.index')->with('success', 'Pengurus RT berhasil dihapus.');
    }
}
