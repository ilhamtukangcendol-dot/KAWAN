<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumanList = Pengumuman::with('user')->where('is_active', true)->latest()->paginate(12);
        return view('pengumuman.index', compact('pengumumanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:penting,biasa',
            'isi' => 'required|string',
        ]);

        $pengumuman = Pengumuman::create([
            'user_id' => auth()->id(),
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'isi' => $validated['isi'],
            'is_active' => true,
        ]);

        ActivityLog::log('Buat Pengumuman', 'Menerbitkan pengumuman: ' . $pengumuman->judul);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:penting,biasa',
            'isi' => 'required|string',
        ]);

        $pengumuman->update($validated);
        ActivityLog::log('Update Pengumuman', 'Memperbarui pengumuman ID #' . $pengumuman->id);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        ActivityLog::log('Hapus Pengumuman', 'Menghapus pengumuman ID #' . $pengumuman->id);
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
