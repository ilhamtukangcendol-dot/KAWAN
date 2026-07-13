<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarisController extends Controller
{
    /**
     * Tampilkan daftar inventaris barang RT
     */
    public function index()
    {
        $daftarInventaris = Inventaris::orderBy('created_at', 'desc')->paginate(15);
        return view('bendahara.inventaris.index', compact('daftarInventaris'));
    }

    /**
     * Formulir tambah barang inventaris baru
     */
    public function create()
    {
        return view('bendahara.inventaris.create');
    }

    /**
     * Simpan barang inventaris baru ke database (dengan integrasi Kas)
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tanggal_perolehan' => 'required|date',
            'keterangan' => 'nullable|string',
        ];

        // Jika dicatat sebagai pengeluaran kas RT
        if ($request->has('beli_pakai_kas')) {
            $rules['harga_satuan'] = 'required|numeric|min:1';
        }

        $request->validate($rules);

        // 1. Simpan ke database Inventaris
        $inventaris = Inventaris::create([
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'tanggal_perolehan' => $request->tanggal_perolehan,
            'keterangan' => $request->keterangan,
        ]);

        // 2. Jika dicatat sebagai pengeluaran Kas RT
        if ($request->has('beli_pakai_kas')) {
            $totalHarga = $request->harga_satuan * $request->jumlah;
            
            Kas::create([
                'keterangan' => 'Pembelian Inventaris: ' . $request->nama_barang . ' (' . $request->jumlah . ' Pcs)',
                'pemasukan' => 0,
                'pengeluaran' => $totalHarga,
                'tanggal' => $request->tanggal_perolehan,
                'user_id' => Auth::id(), // Pencatat (Ibu Bendahara)
            ]);
        }

        return redirect()->route('bendahara.inventaris')->with('success', 'Barang inventaris baru berhasil dicatat!');
    }
}
