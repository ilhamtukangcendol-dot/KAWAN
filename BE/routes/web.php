<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\KetuaController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\KoperasiController;
use App\Http\Controllers\BankSampahController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\PosyanduController;
use App\Http\Controllers\RondaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\RukemController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;

// API: Pencarian Warga untuk autocomplete layanan warga
Route::get('/api/warga', function () {
    $search = request('q');
    $warga  = \App\Models\Warga::when($search, function ($query) use ($search) {
        $query->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('nik', 'like', "%{$search}%");
    })
    ->orderBy('nama_lengkap')
    ->limit(30)
    ->get(['id', 'nama_lengkap', 'nik', 'umur', 'jenis_kelamin', 'alamat', 'status_keluarga']);
    return response()->json($warga);
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Web Routes - Smart RT Digital Ecosystem 2026
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $totalPemasukan = \App\Models\Kas::sum('pemasukan');
    $totalPengeluaran = \App\Models\Kas::sum('pengeluaran');
    $saldo = $totalPemasukan - $totalPengeluaran;

    $pengumumanList = \App\Models\Pengumuman::where('is_active', true)->latest()->take(4)->get();
    $kegiatanList = \App\Models\Kegiatan::where('tanggal', '>=', date('Y-m-d'))->orderBy('tanggal', 'asc')->take(4)->get();
    
    $umkmList = \App\Models\Umkm::where('is_active', true)->latest()->get();
    $umkmKategori = \App\Models\Umkm::where('is_active', true)->select('kategori')->distinct()->pluck('kategori');
    
    $pengurusList = \App\Models\Pengurus::with('warga')->take(4)->get();

    $settings = [
        'nama_rt' => \App\Models\Setting::get('nama_rt', 'RT 01 / RW 05 Komp. Mawar Asri'),
        'alamat_rt' => \App\Models\Setting::get('alamat_rt', 'Jl. Mawar Asri No. 1, Bandung'),
        'kontak_rt' => \App\Models\Setting::get('kontak_rt', '0812-3456-7890'),
        'nominal_iuran' => \App\Models\Setting::get('nominal_iuran', '50000'),
        'nominal_rukem' => \App\Models\Setting::get('nominal_rukem', '1000000'),
    ];

    $totalWarga = \App\Models\Warga::count();
    $totalKK = \App\Models\Warga::distinct('no_kk')->count();

    return view('welcome', compact(
        'saldo',
        'totalPemasukan',
        'totalPengeluaran',
        'pengumumanList',
        'kegiatanList',
        'umkmList',
        'umkmKategori',
        'pengurusList',
        'settings',
        'totalWarga',
        'totalKK'
    ));
});

Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD SYSTEM PER ROLE ---
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->middleware('role:1')->name('superadmin.dashboard');
    Route::get('/ketua/dashboard', [KetuaController::class, 'index'])->middleware('role:1,2')->name('ketua.dashboard');
    Route::get('/bendahara/dashboard', [BendaharaController::class, 'index'])->middleware('role:1,3')->name('bendahara.dashboard');
    Route::get('/warga/dashboard', [WargaController::class, 'index'])->name('warga.dashboard');

    // --- MANAJEMEN USER & AUDIT LOG (HANYA SUPERADMIN - ROLE 1) ---
    Route::middleware(['role:1'])->group(function () {
        Route::get('/superadmin/users', [SuperAdminController::class, 'index'])->name('superadmin.users.index');
        Route::get('/superadmin/users/create', [SuperAdminController::class, 'create'])->name('superadmin.users.create');
        Route::post('/superadmin/users', [SuperAdminController::class, 'store'])->name('superadmin.users.store');
        Route::get('/superadmin/users/{user}/edit', [SuperAdminController::class, 'edit'])->name('superadmin.users.edit');
        Route::put('/superadmin/users/{user}', [SuperAdminController::class, 'update'])->name('superadmin.users.update');
        Route::delete('/superadmin/users/{user}', [SuperAdminController::class, 'destroy'])->name('superadmin.users.destroy');

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::post('/activity-logs/clear', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');
    });

    // --- KAS, PEMASUKAN, PENGELUARAN, ASSET (HANYA SUPERADMIN, RT, BENDAHARA - ROLE 1, 2, 3) ---
    Route::middleware(['role:1,2,3'])->group(function () {
        Route::get('/kas', [KasController::class, 'index'])->name('kas.index');
        Route::get('/pemasukan', [KasController::class, 'pemasukan'])->name('pemasukan.index');
        Route::get('/pengeluaran', [KasController::class, 'pengeluaran'])->name('pengeluaran.index');
        Route::post('/kas/store', [KasController::class, 'store'])->name('kas.store');
        Route::put('/kas/{ka}', [KasController::class, 'update'])->name('kas.update');
        Route::delete('/kas/{ka}', [KasController::class, 'destroy'])->name('kas.destroy');

        Route::get('/asset', [AssetController::class, 'index'])->name('asset.index');
        Route::post('/asset', [AssetController::class, 'store'])->name('asset.store');
        Route::put('/asset/{asset}', [AssetController::class, 'update'])->name('asset.update');
        Route::delete('/asset/{asset}', [AssetController::class, 'destroy'])->name('asset.destroy');
    });

    // --- IURAN WARGA ---
    Route::get('/iuran', [IuranController::class, 'index'])->name('iuran.index');
    Route::post('/iuran', [IuranController::class, 'store'])->middleware('role:1,2,3')->name('iuran.store');
    Route::post('/iuran/mass', [IuranController::class, 'storeMass'])->middleware('role:1,3')->name('iuran.store_mass');
    Route::post('/iuran/{iuran}/bayar', [IuranController::class, 'pay'])->name('iuran.pay');
    Route::post('/iuran/{iuran}/verify', [IuranController::class, 'verify'])->middleware('role:1,2,3')->name('iuran.verify');
    Route::delete('/iuran/{iuran}', [IuranController::class, 'destroy'])->middleware('role:1,2,3')->name('iuran.destroy');

    // --- DATA WARGA & PENGURUS RT ---
    Route::get('/data-warga', [DataWargaController::class, 'index'])->name('data-warga.index');
    Route::post('/data-warga', [DataWargaController::class, 'store'])->middleware('role:1,2')->name('data-warga.store');
    Route::put('/data-warga/{dataWarga}', [DataWargaController::class, 'update'])->middleware('role:1,2')->name('data-warga.update');
    Route::delete('/data-warga/{dataWarga}', [DataWargaController::class, 'destroy'])->middleware('role:1,2')->name('data-warga.destroy');
    Route::get('/warga', [DataWargaController::class, 'index'])->name('warga.index');

    Route::get('/pengurus', [PengurusController::class, 'index'])->name('pengurus.index');
    Route::post('/pengurus', [PengurusController::class, 'store'])->middleware('role:1,2')->name('pengurus.store');
    Route::put('/pengurus/{penguru}', [PengurusController::class, 'update'])->middleware('role:1,2')->name('pengurus.update');
    Route::delete('/pengurus/{penguru}', [PengurusController::class, 'destroy'])->middleware('role:1,2')->name('pengurus.destroy');

    // --- KOPERASI RT ---
    Route::get('/koperasi', [KoperasiController::class, 'index'])->name('koperasi.index');
    Route::post('/koperasi/produk', [KoperasiController::class, 'storeProduct'])->middleware('role:1,3')->name('koperasi.produk.store');
    Route::post('/koperasi/transaksi', [KoperasiController::class, 'storeTransaksi'])->name('koperasi.transaksi.store');
    Route::post('/koperasi/transaksi/{transaksi}/status', [KoperasiController::class, 'updateStatus'])->middleware('role:1,3')->name('koperasi.transaksi.status');
    Route::delete('/koperasi/produk/{koperasi}', [KoperasiController::class, 'destroy'])->middleware('role:1,3')->name('koperasi.produk.destroy');

    // --- BANK SAMPAH ---
    Route::get('/bank-sampah', [BankSampahController::class, 'index'])->name('bank-sampah.index');
    Route::post('/bank-sampah', [BankSampahController::class, 'store'])->middleware('role:1,3')->name('bank-sampah.store');
    Route::delete('/bank-sampah/{bankSampah}', [BankSampahController::class, 'destroy'])->middleware('role:1,3')->name('bank-sampah.destroy');

    // --- UMKM WARGA ---
    Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
    Route::post('/umkm', [UmkmController::class, 'store'])->name('umkm.store');
    Route::put('/umkm/{umkm}', [UmkmController::class, 'update'])->name('umkm.update');
    Route::delete('/umkm/{umkm}', [UmkmController::class, 'destroy'])->name('umkm.destroy');

    // --- SURAT MENYURAT ---
    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
    Route::post('/surat/{surat}/approve', [SuratController::class, 'approve'])->middleware('role:1,2')->name('surat.approve');
    Route::post('/surat/{surat}/reject', [SuratController::class, 'reject'])->middleware('role:1,2')->name('surat.reject');
    Route::delete('/surat/{surat}', [SuratController::class, 'destroy'])->middleware('role:1,2')->name('surat.destroy');

    // --- POSYANDU ---
    Route::get('/posyandu', [PosyanduController::class, 'index'])->name('posyandu.index');
    Route::post('/posyandu', [PosyanduController::class, 'store'])->middleware('role:1,2,3')->name('posyandu.store');
    Route::delete('/posyandu/{posyandu}', [PosyanduController::class, 'destroy'])->middleware('role:1,2,3')->name('posyandu.destroy');

    // --- RONDA & KEAMANAN ---
    Route::get('/ronda', [RondaController::class, 'index'])->name('ronda.index');
    Route::post('/ronda', [RondaController::class, 'store'])->middleware('role:1,2')->name('ronda.store');
    Route::put('/ronda/{ronda}/laporan', [RondaController::class, 'updateLaporan'])->name('ronda.laporan.update');
    Route::delete('/ronda/{ronda}', [RondaController::class, 'destroy'])->middleware('role:1,2')->name('ronda.destroy');

    // --- KEGIATAN WARGA ---
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->middleware('role:1,2')->name('kegiatan.store');
    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->middleware('role:1,2')->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->middleware('role:1,2')->name('kegiatan.destroy');

    // --- RUKEM ---
    Route::get('/rukem', [RukemController::class, 'index'])->name('rukem.index');
    Route::post('/rukem', [RukemController::class, 'store'])->name('rukem.store');
    Route::post('/rukem/{rukem}/cairkan', [RukemController::class, 'cairkan'])->middleware('role:1,2,3')->name('rukem.cairkan');
    Route::delete('/rukem/{rukem}', [RukemController::class, 'destroy'])->middleware('role:1,2,3')->name('rukem.destroy');

    // --- ASPIRASI ---
    Route::get('/aspirasi', [AspirasiController::class, 'index'])->name('aspirasi.index');
    Route::post('/aspirasi', [AspirasiController::class, 'store'])->name('aspirasi.store');
    Route::post('/aspirasi/{aspirasi}/respond', [AspirasiController::class, 'respond'])->middleware('role:1,2')->name('aspirasi.respond');
    Route::delete('/aspirasi/{aspirasi}', [AspirasiController::class, 'destroy'])->name('aspirasi.destroy');

    // --- PENGUMUMAN ---
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->middleware('role:1,2')->name('pengumuman.store');
    Route::put('/pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->middleware('role:1,2')->name('pengumuman.update');
    Route::delete('/pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->middleware('role:1,2')->name('pengumuman.destroy');

    // --- PENGATURAN (UNIVESAL UNTUK SEMUA ROLE) ---
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // --- LEGACY COMPATIBILITY ALIASES ---
    Route::get('/ketua/monitoring', [KasController::class, 'index'])->middleware('role:1,2')->name('ketua.monitoring');
    Route::get('/ketua/audit', [KasController::class, 'index'])->middleware('role:1,2')->name('ketua.audit');
    Route::get('/ketua/warga', [DataWargaController::class, 'index'])->middleware('role:1,2')->name('ketua.warga');
    Route::get('/ketua/laporan', [KasController::class, 'index'])->middleware('role:1,2')->name('ketua.laporan');
    Route::get('/ketua/iuran', [IuranController::class, 'index'])->middleware('role:1,2')->name('ketua.iuran');
    Route::get('/ketua/payment', [IuranController::class, 'index'])->middleware('role:1,2')->name('ketua.payment');
    
    Route::get('/bendahara/entri', [KasController::class, 'index'])->middleware('role:1,3')->name('bendahara.entri');
    Route::get('/bendahara/iuran', [IuranController::class, 'index'])->middleware('role:1,3')->name('bendahara.iuran');
    Route::get('/bendahara/laporan', [KasController::class, 'index'])->middleware('role:1,3')->name('bendahara.laporan');
    Route::get('/bendahara/inventaris', [AssetController::class, 'index'])->middleware('role:1,3')->name('bendahara.inventaris');
    Route::get('/bendahara/persetujuan', [IuranController::class, 'index'])->middleware('role:1,3')->name('bendahara.persetujuan');

    Route::get('/warga/bayar', [IuranController::class, 'index'])->name('warga.payment');
    Route::get('/warga/metode-pembayaran', [SettingController::class, 'index'])->name('warga.payment_methods');
    Route::get('/warga/riwayat-setoran', [IuranController::class, 'index'])->name('warga.history');

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar/delete', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
});

require __DIR__.'/auth.php';