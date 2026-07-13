<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $status = $request->get('status');

        $query = Aspirasi::with('user');
        if ($user->role == 4) {
            $query->where('user_id', $user->id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $aspirasiList = $query->latest()->paginate(15)->withQueryString();

        return view('aspirasi.index', compact('aspirasiList', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'isi' => 'required|string',
        ]);

        $aspirasi = Aspirasi::create([
            'user_id' => auth()->id(),
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'isi' => $validated['isi'],
            'status' => 'open',
        ]);

        ActivityLog::log('Kirim Aspirasi', 'Pengajuan aspirasi/pengaduan ID #' . $aspirasi->id);

        return redirect()->route('aspirasi.index')->with('success', 'Aspirasi / Pengaduan berhasil dikirimkan ke Pengurus RT.');
    }

    public function respond(Request $request, Aspirasi $aspirasi)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
            'tanggapan' => 'required|string',
        ]);

        $aspirasi->update($validated);
        ActivityLog::log('Tanggapan Aspirasi', 'Tanggapan pengurus untuk aspirasi ID #' . $aspirasi->id);

        return redirect()->back()->with('success', 'Tanggapan & status pengaduan berhasil diperbarui.');
    }

    public function destroy(Aspirasi $aspirasi)
    {
        $aspirasi->delete();
        ActivityLog::log('Hapus Aspirasi', 'Menghapus aspirasi ID #' . $aspirasi->id);
        return redirect()->route('aspirasi.index')->with('success', 'Aspirasi berhasil dihapus.');
    }
}
