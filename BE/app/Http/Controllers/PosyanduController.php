<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use App\Models\Warga;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PosyanduController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'balita');
        $pemeriksaanList = Posyandu::with('warga')
            ->where('kategori', $kategori)
            ->latest('tanggal_periksa')
            ->paginate(15);

        // Ambil warga berdasarkan filter kategori posyandu
        // Balita: umur <= 5 tahun (60 bulan), Lansia: umur >= 60 tahun
        if ($kategori === 'balita') {
            $wargaList = Warga::where('umur', '<=', 5)
                ->orderBy('nama_lengkap')
                ->get();
        } else {
            $wargaList = Warga::where('umur', '>=', 60)
                ->orderBy('nama_lengkap')
                ->get();
        }

        // Semua warga untuk fallback (jika daftar spesifik kosong)
        $allWarga = Warga::orderBy('nama_lengkap')->get();

        return view('posyandu.index', compact('pemeriksaanList', 'kategori', 'wargaList', 'allWarga'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id'             => 'nullable|exists:warga,id',
            'pendaftar_warga_id'   => 'nullable|exists:warga,id',
            'nama_pasien'          => 'required|string|max:255',
            'nama_pendaftar'       => 'nullable|string|max:255',
            'kategori'             => 'required|in:balita,lansia',
            'umur_bulan_atau_tahun'=> 'required|integer|min:0',
            'bb_kg'                => 'required|numeric|min:0',
            'tb_cm'                => 'required|numeric|min:0',
            'catatan'              => 'nullable|string',
            'tanggal_periksa'      => 'required|date',
            'petugas'              => 'required|string|max:255',
        ]);

        // Jika warga dipilih dari database, gunakan nama resmi dari database
        if (!empty($validated['warga_id'])) {
            $warga = Warga::find($validated['warga_id']);
            if ($warga) {
                $validated['nama_pasien'] = $warga->nama_lengkap;
                // Auto-isi umur dari data warga jika tidak diisi manual
                if ($request->filled('warga_id') && !$request->filled('umur_bulan_atau_tahun')) {
                    $validated['umur_bulan_atau_tahun'] = $warga->umur;
                }
            }
        }

        // Jika pendaftar dipilih dari database, gunakan nama resmi pendaftar dari database
        if (!empty($validated['pendaftar_warga_id'])) {
            $pendaftar = Warga::find($validated['pendaftar_warga_id']);
            if ($pendaftar) {
                $validated['nama_pendaftar'] = $pendaftar->nama_lengkap;
            }
        }

        $entry = Posyandu::create($validated);
        ActivityLog::log('Pemeriksaan Posyandu', 'Pemeriksaan posyandu pasien: ' . $entry->nama_pasien);

        return redirect()->route('posyandu.index', ['kategori' => $validated['kategori']])
            ->with('success', 'Catatan pemeriksaan kesehatan Posyandu berhasil disimpan.');
    }

    public function destroy(Posyandu $posyandu)
    {
        $kategori = $posyandu->kategori;
        $posyandu->delete();
        ActivityLog::log('Hapus Posyandu', 'Menghapus catatan posyandu ID #' . $posyandu->id);

        return redirect()->route('posyandu.index', ['kategori' => $kategori])
            ->with('success', 'Data posyandu berhasil dihapus.');
    }
}
