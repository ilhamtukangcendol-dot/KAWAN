<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\Warga; 

class KetuaController extends Controller
{
    public function index()
    {
        $totalPemasukan = Kas::sum('pemasukan');
        $totalPengeluaran = Kas::sum('pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;
        $transaksiTerakhir = Kas::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->take(5)->get();

        try {
            $totalWarga = Warga::count();
            $totalKK = Warga::distinct('no_kk')->count();
            $wargaLaki = Warga::where('jenis_kelamin', 'L')->count();
            $wargaPerempuan = Warga::where('jenis_kelamin', 'P')->count();
        } catch (\Exception $e) {
            $totalWarga = 0; $totalKK = 0; $wargaLaki = 0; $wargaPerempuan = 0;
        }

        return view('ketua.dashboard', compact(
            'saldo', 'totalPemasukan', 'totalPengeluaran', 'transaksiTerakhir',
            'totalWarga', 'totalKK', 'wargaLaki', 'wargaPerempuan'
        ));
    }

    // Fungsi baru untuk melihat daftar warga dengan filter kategori usia
    public function dataWarga(Request $request)
    {
        $kategori = $request->get('kategori');
        $search = $request->get('search');

        $query = Warga::with('user');

        // Filter berdasarkan pencarian nama, NIK, atau KK
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhere('no_kk', 'like', '%' . $search . '%');
            });
        }

        if ($kategori) {
            if ($kategori == 'bayi') {
                $query->where('umur', '<=', 2);
            } elseif ($kategori == 'balita') {
                $query->whereBetween('umur', [3, 5]);
            } elseif ($kategori == 'anak') {
                $query->whereBetween('umur', [6, 12]);
            } elseif ($kategori == 'remaja') {
                $query->whereBetween('umur', [13, 17]);
            } elseif ($kategori == 'dewasa') {
                $query->whereBetween('umur', [18, 59]);
            } elseif ($kategori == 'lansia') {
                $query->where('umur', '>=', 60);
            }
        }

        $daftarWarga = $query->paginate(10)->withQueryString();

        // Hitung statistik kategori umur secara global (untuk semua warga)
        $statBayi = Warga::where('umur', '<=', 2)->count();
        $statBalita = Warga::whereBetween('umur', [3, 5])->count();
        $statAnak = Warga::whereBetween('umur', [6, 12])->count();
        $statRemaja = Warga::whereBetween('umur', [13, 17])->count();
        $statDewasa = Warga::whereBetween('umur', [18, 59])->count();
        $statLansia = Warga::where('umur', '>=', 60)->count();

        return view('ketua.warga', compact(
            'daftarWarga', 'kategori', 'search',
            'statBayi', 'statBalita', 'statAnak', 'statRemaja', 'statDewasa', 'statLansia'
        ));
    }

    // Fungsi baru untuk menampilkan monitoring kas
    public function monitoring()
    {
        $totalPemasukan = Kas::sum('pemasukan');
        $totalPengeluaran = Kas::sum('pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;
        
        // Mengambil riwayat transaksi kas untuk visualisasi grafik real-time
        $riwayatKas = Kas::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        return view('ketua.monitoring', compact('totalPemasukan', 'totalPengeluaran', 'saldo', 'riwayatKas'));
    }

    // Fungsi baru untuk audit keuangan
    public function audit()
    {
        $riwayatKas = Kas::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();
        return view('ketua.audit', compact('riwayatKas'));
    }

    // Tampilkan formulir pembayaran ke warga
    public function paymentForm()
    {
        $daftarWarga = Warga::all();
        
        $totalPemasukan = Kas::sum('pemasukan');
        $totalPengeluaran = Kas::sum('pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('ketua.payment', compact('daftarWarga', 'saldo'));
    }

    // Simpan data transaksi pembayaran iuran warga ke kas RT (Pemasukan Kas)
    public function storePayment(Request $request)
    {
        $request->validate([
            'warga_id' => 'required|exists:warga,id',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $warga = Warga::findOrFail($request->warga_id);

        Kas::create([
            'keterangan' => $request->keterangan . ' (Pembayaran Kas Oleh Warga: ' . $warga->nama_lengkap . ')',
            'pemasukan' => $request->nominal,
            'pengeluaran' => 0,
            'tanggal' => $request->tanggal,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('ketua.dashboard')->with('success', 'Pembayaran kas dari ' . $warga->nama_lengkap . ' berhasil dicatat!');
    }
}