<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assetList = Inventaris::latest()->paginate(15);
        $totalAsset = Inventaris::sum('jumlah');
        $totalNilai = Inventaris::selectRaw('sum(jumlah * nominal) as total')->value('total') ?? 0;

        return view('asset.index', compact('assetList', 'totalAsset', 'totalNilai'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|string|max:100',
            'tanggal_perolehan' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $asset = Inventaris::create($validated);
        ActivityLog::log('Tambah Asset RT', 'Menambahkan asset inventaris RT: ' . $asset->nama_barang);

        return redirect()->route('asset.index')->with('success', 'Asset / Inventaris RT berhasil ditambahkan.');
    }

    public function update(Request $request, Inventaris $asset)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|string|max:100',
            'tanggal_perolehan' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $asset->update($validated);
        ActivityLog::log('Update Asset RT', 'Memperbarui asset ID #' . $asset->id);

        return redirect()->route('asset.index')->with('success', 'Data asset RT berhasil diperbarui.');
    }

    public function destroy(Inventaris $asset)
    {
        $asset->delete();
        ActivityLog::log('Hapus Asset RT', 'Menghapus inventaris ID #' . $asset->id);

        return redirect()->route('asset.index')->with('success', 'Asset RT berhasil dihapus.');
    }
}
