<?php

namespace App\Http\Controllers;

use App\Models\BankSampah;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BankSampahController extends Controller
{
    public function index()
    {
        $setoranList = BankSampah::with('user')->latest()->paginate(15);

        $totalTerkumpulKg = BankSampah::sum('berat_kg');
        $totalNilaiRupiah = BankSampah::sum('total_harga');

        // Total per User
        $tabunganWarga = BankSampah::selectRaw('user_id, sum(total_harga) as total_saldo, sum(berat_kg) as total_kg')
            ->groupBy('user_id')
            ->with('user')
            ->get();

        $users = User::orderBy('name', 'asc')->get();

        return view('bank-sampah.index', compact('setoranList', 'totalTerkumpulKg', 'totalNilaiRupiah', 'tabunganWarga', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_sampah' => 'required|string|max:100',
            'berat_kg' => 'required|numeric|min:0.1',
            'harga_per_kg' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $totalHarga = $validated['berat_kg'] * $validated['harga_per_kg'];

        $entry = BankSampah::create([
            'user_id' => $validated['user_id'],
            'jenis_sampah' => $validated['jenis_sampah'],
            'berat_kg' => $validated['berat_kg'],
            'harga_per_kg' => $validated['harga_per_kg'],
            'total_harga' => $totalHarga,
            'tanggal' => $validated['tanggal'],
        ]);

        ActivityLog::log('Input Bank Sampah', 'Setoran sampah ID #' . $entry->id . ' sebesar ' . $validated['berat_kg'] . ' kg');

        return redirect()->route('bank-sampah.index')->with('success', 'Setoran Bank Sampah berhasil dicatat.');
    }

    public function destroy(BankSampah $bankSampah)
    {
        $bankSampah->delete();
        ActivityLog::log('Hapus Bank Sampah', 'Menghapus setoran bank sampah ID #' . $bankSampah->id);
        return redirect()->route('bank-sampah.index')->with('success', 'Catatan Bank Sampah berhasil dihapus.');
    }
}
