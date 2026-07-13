<?php

namespace App\Http\Controllers;

use App\Models\Koperasi;
use App\Models\KoperasiTransaksi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class KoperasiController extends Controller
{
    public function index()
    {
        $produkList = Koperasi::latest()->get();
        $transaksiList = KoperasiTransaksi::with(['user', 'produk'])->latest()->paginate(15);

        return view('koperasi.index', compact('produkList', 'transaksiList'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $produk = Koperasi::create($validated);
        ActivityLog::log('Tambah Produk Koperasi', 'Menambahkan barang koperasi: ' . $produk->nama_produk);

        return redirect()->route('koperasi.index')->with('success', 'Produk Koperasi berhasil ditambahkan.');
    }

    public function storeTransaksi(Request $request)
    {
        $validated = $request->validate([
            'koperasi_id' => 'nullable|exists:koperasi,id',
            'jenis' => 'required|in:pembelian,simpanan,pinjaman',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $status = (auth()->user()->role <= 3) ? 'approved' : 'pending';

        $tx = KoperasiTransaksi::create([
            'user_id' => auth()->id(),
            'koperasi_id' => $validated['koperasi_id'] ?? null,
            'jenis' => $validated['jenis'],
            'nominal' => $validated['nominal'],
            'status' => $status,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        // If approved purchase, deduct stock
        if ($status === 'approved' && $tx->koperasi_id && $tx->jenis === 'pembelian') {
            $prod = Koperasi::find($tx->koperasi_id);
            if ($prod && $prod->stok > 0) {
                $prod->decrement('stok', 1);
            }
        }

        ActivityLog::log('Transaksi Koperasi', 'Pengajuan transaksi koperasi ID #' . $tx->id);

        return redirect()->route('koperasi.index')->with('success', 'Transaksi Koperasi berhasil dicatat.');
    }

    public function updateStatus(Request $request, KoperasiTransaksi $transaksi)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $transaksi->status = $request->status;
        $transaksi->save();

        ActivityLog::log('Update Status Transaksi Koperasi', 'Update status ID #' . $transaksi->id . ' menjadi ' . $request->status);

        return redirect()->back()->with('success', 'Status transaksi koperasi berhasil diperbarui.');
    }

    public function destroy(Koperasi $koperasi)
    {
        $koperasi->delete();
        ActivityLog::log('Hapus Produk Koperasi', 'Menghapus produk ID #' . $koperasi->id);
        return redirect()->route('koperasi.index')->with('success', 'Produk berhasil dihapus.');
    }
}
