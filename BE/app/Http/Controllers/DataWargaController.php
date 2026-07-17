<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DataWargaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $gender = $request->get('gender');
        $sort = $request->get('sort');
        $direction = $request->get('direction', 'asc');
        $sortUmur = $request->get('sort_umur'); // 'asc' or 'desc'
        $viewMode = $request->get('view', 'cards'); // 'cards' (Kartu Keluarga) or 'table' (Individual)

        $totalWarga = Warga::count();
        $totalKK = Warga::distinct('no_kk')->count();
        $users = User::orderBy('name', 'asc')->get();

        // 1. PAGINATED FAMILY CARDS VIEW (5 Kartu Keluarga per halaman)
        // Grouping by no_kk ensures total items in paginator matches exact count of Kartu Keluarga (KK)
        $familyQuery = Warga::select('no_kk')
            ->groupBy('no_kk')
            ->when($search, function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            })
            ->when($gender, function($q) use ($gender) {
                $q->whereIn('no_kk', function($sub) use ($gender) {
                    $sub->select('no_kk')->from('warga')->where('jenis_kelamin', $gender);
                });
            })
            ->orderBy('no_kk', 'asc');

        $familyPaginated = $familyQuery->paginate(5)->withQueryString();
        $kkList = $familyPaginated->pluck('no_kk');

        $familyWarga = Warga::with('user')
            ->whereIn('no_kk', $kkList)
            ->when($gender, function($q) use ($gender) {
                $q->where('jenis_kelamin', $gender);
            })
            ->orderBy('no_kk', 'asc')
            ->orderByRaw("CASE WHEN status_keluarga = 'Kepala Keluarga' THEN 1 ELSE 2 END")
            ->when($sortUmur, function($q) use ($sortUmur) {
                $q->orderBy('umur', $sortUmur);
            })
            ->get();

        $familyGroups = $familyWarga->groupBy('no_kk');

        // If sort_umur is active in cards mode, sort members within each family group
        if ($sortUmur) {
            $familyGroups = $familyGroups->map(function($anggota) use ($sortUmur) {
                return $sortUmur === 'asc'
                    ? $anggota->sortBy('umur')->values()
                    : $anggota->sortByDesc('umur')->values();
            });
        }

        // 2. PAGINATED INDIVIDUAL TABLE VIEW
        $wargaList = Warga::with('user')
            ->when($search, function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%");
            })
            ->when($gender, function($q) use ($gender) {
                $q->where('jenis_kelamin', $gender);
            })
            ->when($sortUmur, function($q) use ($sortUmur) {
                $q->orderBy('umur', $sortUmur);
            })
            ->when($sort && !$sortUmur, function($q) use ($sort, $direction) {
                if (in_array($sort, ['jenis_kelamin', 'nama_lengkap', 'umur'])) {
                    $q->orderBy($sort, $direction);
                }
            }, function($q) use ($sortUmur) {
                if (!$sortUmur) {
                    $q->latest();
                }
            })
            ->paginate(15)
            ->withQueryString();

        return view('data-warga.index', compact(
            'familyGroups',
            'familyPaginated',
            'wargaList',
            'totalWarga',
            'totalKK',
            'search',
            'gender',
            'viewMode',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:warga,nik',
            'no_kk' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'umur' => 'required|integer|min:0',
            'status_keluarga' => 'required|string|max:100',
            'status_tinggal' => 'required|string|max:100',
            'alamat' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $warga = Warga::create($validated);
        ActivityLog::log('Tambah Data Warga', 'Menambah anggota keluarga baru: ' . $warga->nama_lengkap . ' (KK: ' . $warga->no_kk . ')');

        return redirect()->route('data-warga.index', ['view' => $request->get('view', 'cards')])->with('success', 'Data anggota keluarga berhasil ditambahkan.');
    }

    public function update(Request $request, Warga $dataWarga)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:warga,nik,' . $dataWarga->id,
            'no_kk' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'umur' => 'required|integer|min:0',
            'status_keluarga' => 'required|string|max:100',
            'status_tinggal' => 'required|string|max:100',
            'alamat' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $dataWarga->update($validated);
        ActivityLog::log('Update Data Warga', 'Memperbarui data warga ID #' . $dataWarga->id);

        return redirect()->route('data-warga.index', ['view' => $request->get('view', 'cards')])->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(Warga $dataWarga)
    {
        $nama = $dataWarga->nama_lengkap;
        $dataWarga->delete();
        ActivityLog::log('Hapus Data Warga', 'Menghapus data warga: ' . $nama);

        return redirect()->back()->with('success', 'Data warga berhasil dihapus.');
    }
}
