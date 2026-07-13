<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori');
        $query = Umkm::with('user')->where('is_active', true);

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $umkmList = $query->latest()->paginate(12)->withQueryString();

        return view('umkm.index', compact('umkmList', 'kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string',
            'no_whatsapp' => 'required|string|max:30',
        ]);

        $umkm = Umkm::create([
            'user_id' => auth()->id(),
            'nama_usaha' => $validated['nama_usaha'],
            'pemilik' => $validated['pemilik'],
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'],
            'alamat' => $validated['alamat'],
            'no_whatsapp' => $validated['no_whatsapp'],
            'is_active' => true,
        ]);

        ActivityLog::log('Pendaftaran UMKM', 'Mendaftarkan usaha UMKM Warga: ' . $umkm->nama_usaha);

        return redirect()->route('umkm.index')->with('success', 'Usaha UMKM Warga berhasil terdaftar.');
    }

    public function update(Request $request, Umkm $umkm)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string',
            'no_whatsapp' => 'required|string|max:30',
        ]);

        $umkm->update($validated);
        ActivityLog::log('Update UMKM', 'Memperbarui data UMKM ID #' . $umkm->id);

        return redirect()->route('umkm.index')->with('success', 'Data UMKM berhasil diperbarui.');
    }

    public function destroy(Umkm $umkm)
    {
        $umkm->delete();
        ActivityLog::log('Hapus UMKM', 'Menghapus profil UMKM ID #' . $umkm->id);

        return redirect()->route('umkm.index')->with('success', 'Profil UMKM berhasil dihapus.');
    }
}
