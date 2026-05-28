<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KetuaController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\ProfileController; // Sesuaikan jika ada

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Kas RT Digital 2026
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // --- GRUP OTORITAS KETUA (Role 1) ---
    Route::middleware(['role:1'])->group(function () {
        // Route Utama Ketua
        Route::get('/ketua/dashboard', [KetuaController::class, 'index'])->name('ketua.dashboard');
        
        // Alias agar tidak error
        Route::get('/ketua/monitoring', [KetuaController::class, 'monitoring'])->name('ketua.monitoring');
        
        // Ketua bisa akses menu bendahara tapi hanya lihat (Read-Only)
        Route::get('/ketua/iuran', [BendaharaController::class, 'iuran'])->name('ketua.iuran');
        Route::get('/ketua/audit', [KetuaController::class, 'audit'])->name('ketua.audit');
        Route::get('/ketua/warga', [KetuaController::class, 'dataWarga'])->name('ketua.warga');
        Route::get('/ketua/laporan', [BendaharaController::class, 'laporan'])->name('ketua.laporan');
        
        // Rute Pembayaran Warga oleh Ketua RT
        Route::get('/ketua/payment', [KetuaController::class, 'paymentForm'])->name('ketua.payment');
        Route::post('/ketua/payment', [KetuaController::class, 'storePayment'])->name('ketua.payment.store');
    });

    // --- GRUP BENDAHARA (Role 2) ---
    Route::middleware(['role:2'])->group(function () {
        Route::get('/bendahara/dashboard', [BendaharaController::class, 'index'])->name('bendahara.dashboard');
        Route::get('/bendahara/entri', [BendaharaController::class, 'create'])->name('bendahara.entri');
        Route::post('/bendahara/store', [BendaharaController::class, 'store'])->name('bendahara.store');
        Route::get('/bendahara/iuran', [BendaharaController::class, 'iuran'])->name('bendahara.iuran');
        Route::get('/bendahara/laporan', [BendaharaController::class, 'laporan'])->name('bendahara.laporan');
        
        // Rute untuk form entri agar menyimpan kas dengan mencatat user_id pembuku
        Route::post('/kas/store', [\App\Http\Controllers\KasController::class, 'store'])->name('kas.store');

        // Rute Inventaris Barang RT (Operasional Bendahara)
        Route::get('/bendahara/inventaris', [\App\Http\Controllers\InventarisController::class, 'index'])->name('bendahara.inventaris');
        Route::get('/bendahara/inventaris/create', [\App\Http\Controllers\InventarisController::class, 'create'])->name('bendahara.inventaris.create');
        Route::post('/bendahara/inventaris/store', [\App\Http\Controllers\InventarisController::class, 'store'])->name('bendahara.inventaris.store');

        // Rute Persetujuan Setoran Kas Warga (Bendahara)
        Route::get('/bendahara/persetujuan', [BendaharaController::class, 'persetujuan'])->name('bendahara.persetujuan');
        Route::post('/bendahara/persetujuan/{id}/setujui', [BendaharaController::class, 'approveSetoran'])->name('bendahara.persetujuan.setujui');
        Route::post('/bendahara/persetujuan/{id}/tolak', [BendaharaController::class, 'rejectSetoran'])->name('bendahara.persetujuan.tolak');
    });

    // --- GRUP WARGA (Role 3) ---
    Route::middleware(['role:3'])->group(function () {
        Route::get('/warga/dashboard', [\App\Http\Controllers\WargaController::class, 'index'])->name('warga.dashboard');
        
        // Rute Pembayaran Mandiri oleh Warga
        Route::get('/warga/bayar', [\App\Http\Controllers\WargaController::class, 'paymentForm'])->name('warga.payment');
        Route::post('/warga/bayar', [\App\Http\Controllers\WargaController::class, 'storePayment'])->name('warga.payment.store');

        // Rute Tambahan Warga: Metode Pembayaran & Riwayat Setoran Pribadi
        Route::get('/warga/metode-pembayaran', [\App\Http\Controllers\WargaController::class, 'paymentMethods'])->name('warga.payment_methods');
        Route::get('/warga/riwayat-setoran', [\App\Http\Controllers\WargaController::class, 'history'])->name('warga.history');
    });

    // Route Umum (Jika ada)
    Route::get('/warga', [KetuaController::class, 'dataWarga'])->name('warga.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar/delete', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
});

require __DIR__.'/auth.php';