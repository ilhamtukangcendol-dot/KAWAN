<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Setting;
use App\Models\Warga;
use App\Models\Pengurus;
use App\Models\Kas;
use App\Models\Iuran;
use App\Models\Koperasi;
use App\Models\BankSampah;
use App\Models\Umkm;
use App\Models\Surat;
use App\Models\Posyandu;
use App\Models\Ronda;
use App\Models\Kegiatan;
use App\Models\Rukem;
use App\Models\Aspirasi;
use App\Models\Inventaris;
use App\Models\Pengumuman;

class DatabaseMigrationController extends Controller
{
    /**
     * Tampilkan Pusat Kendali Migrasi & Database KAWAN
     */
    public function index()
    {
        $dbStatus = $this->getDatabaseOverview();
        $migrationData = $this->getMigrationStatus();
        $tableList = $this->getTablesDetailed();
        $seederStatus = $this->getSeederStatus();

        return view('superadmin.database.index', compact(
            'dbStatus',
            'migrationData',
            'tableList',
            'seederStatus'
        ));
    }

    /**
     * Ringkasan koneksi database
     */
    protected function getDatabaseOverview()
    {
        $connected = false;
        $error = null;
        $dbName = config('database.connections.' . config('database.default') . '.database');
        $dbDriver = config('database.default');
        $dbHost = config('database.connections.' . $dbDriver . '.host');
        $dbPort = config('database.connections.' . $dbDriver . '.port');
        $serverVersion = 'N/A';

        try {
            DB::connection()->getPdo();
            $connected = true;
            $versionResult = DB::select('SELECT VERSION() as version');
            if (!empty($versionResult)) {
                $serverVersion = $versionResult[0]->version ?? 'N/A';
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return [
            'connected' => $connected,
            'driver' => $dbDriver,
            'database' => $dbName,
            'host' => $dbHost,
            'port' => $dbPort,
            'server_version' => $serverVersion,
            'error' => $error,
        ];
    }

    /**
     * Status file migrasi vs tabel migrations
     */
    protected function getMigrationStatus()
    {
        $migrationFiles = glob(database_path('migrations/*.php'));
        $totalFiles = count($migrationFiles);
        $ranMigrations = collect();

        $hasMigrationTable = false;
        try {
            $hasMigrationTable = Schema::hasTable('migrations');
            if ($hasMigrationTable) {
                $ranMigrations = DB::table('migrations')->get()->keyBy('migration');
            }
        } catch (\Exception $e) {
            // Abaikan jika database belum siap
        }

        $migrationsDetail = [];
        $migratedCount = 0;

        foreach ($migrationFiles as $file) {
            $fileName = basename($file, '.php');
            $isRan = $ranMigrations->has($fileName);
            $batch = $isRan ? $ranMigrations->get($fileName)->batch : null;
            $migrationId = $isRan ? $ranMigrations->get($fileName)->id : null;

            if ($isRan) {
                $migratedCount++;
            }

            $migrationsDetail[] = [
                'name' => $fileName,
                'is_migrated' => $isRan,
                'batch' => $batch,
                'id' => $migrationId,
                'file_path' => $file,
            ];
        }

        $pendingCount = $totalFiles - $migratedCount;
        $percentage = $totalFiles > 0 ? round(($migratedCount / $totalFiles) * 100) : 0;

        return [
            'has_table' => $hasMigrationTable,
            'total_files' => $totalFiles,
            'migrated_count' => $migratedCount,
            'pending_count' => $pendingCount,
            'percentage' => $percentage,
            'details' => $migrationsDetail,
        ];
    }

    /**
     * Detail seluruh tabel data yang sudah termigrasi
     */
    protected function getTablesDetailed()
    {
        $tables = [];
        $totalRowsAll = 0;
        $totalSizeBytes = 0;

        $moduleDescriptions = [
            'users' => 'Data Akun & Autentikasi Pengguna (Superadmin, RT, Bendahara, Warga)',
            'wargas' => 'Database Kependudukan & Profil Warga RT',
            'warga' => 'Database Kependudukan & Profil Warga RT',
            'pengurus' => 'Struktur Kepengurusan Rukun Tetangga (RT)',
            'kas' => 'Buku Kas Utama RT (Pemasukan & Pengeluaran)',
            'iurans' => 'Pencatatan & Status Pembayaran Iuran Warga',
            'koperasis' => 'Katalog Produk Koperasi Sembako RT',
            'koperasi_transaksis' => 'Riwayat Transaksi Belanja Koperasi Warga',
            'bank_sampahs' => 'Setoran & Rekap Tabungan Bank Sampah RT',
            'umkms' => 'Katalog Produk & Direktori UMKM Warga RT',
            'surats' => 'Pelayanan Surat Pengantar & Dokumen Warga',
            'posyandus' => 'Pencatatan Kesehatan Balita & Lansia Posyandu',
            'rondas' => 'Jadwal & Presensi Petugas Ronda Malam RT',
            'kegiatans' => 'Agenda Kegiatan, Rapat & Gotong Royong Warga',
            'rukems' => 'Santunan Belasungkawa & Rukun Kematian (Rukem)',
            'aspirasis' => 'Kotak Aspirasi, Saran & Pengaduan Warga Online',
            'inventaris' => 'Pencatatan Asset & Barang Inventaris RT',
            'pengumumans' => 'Papan Pengumuman & Berita Lingkungan RT',
            'activity_logs' => 'Audit Trail & Rekam Jejak Aktivitas Sistem',
            'settings' => 'Konfigurasi Global & Identitas Lingkungan RT',
            'migrations' => 'Pencatatan Riwayat Eksekusi Migrasi Database Laravel',
            'cache' => 'Penyimpanan Data Sementara (Framework Cache)',
            'cache_locks' => 'Atomic Lock Framework Cache',
            'jobs' => 'Antrian Pekerjaan Latar Belakang (Queue Jobs)',
            'job_batches' => 'Penyimpanan Batch Pekerjaan Antrian Laravel',
            'failed_jobs' => 'Daftar Pekerjaan Antrian yang Gagal',
            'personal_access_tokens' => 'Token API REST Sanctum untuk Klien Frontend',
            'sessions' => 'Sesi Login Pengguna Web',
            'password_reset_tokens' => 'Token Reset Kata Sandi Pengguna',
        ];

        try {
            $dbName = config('database.connections.' . config('database.default') . '.database');
            
            // Query information_schema di MySQL
            $rawTables = DB::select("
                SELECT 
                    table_name AS name,
                    table_rows AS approx_rows,
                    data_length,
                    index_length,
                    create_time,
                    update_time,
                    engine
                FROM information_schema.tables 
                WHERE table_schema = ?
                ORDER BY table_name ASC
            ", [$dbName]);

            if (empty($rawTables)) {
                // Fallback jika bukan MySQL atau hak akses terbatas
                $tableNames = Schema::getTableListing();
                foreach ($tableNames as $tName) {
                    $rawTables[] = (object)[
                        'name' => $tName,
                        'approx_rows' => 0,
                        'data_length' => 0,
                        'index_length' => 0,
                        'create_time' => null,
                        'update_time' => null,
                        'engine' => 'InnoDB',
                    ];
                }
            }

            foreach ($rawTables as $t) {
                $tableName = $t->name;
                $exactCount = 0;
                try {
                    $exactCount = DB::table($tableName)->count();
                } catch (\Exception $e) {
                    $exactCount = $t->approx_rows ?? 0;
                }

                $size = ($t->data_length ?? 0) + ($t->index_length ?? 0);
                $totalSizeBytes += $size;
                $totalRowsAll += $exactCount;

                $tables[] = [
                    'name' => $tableName,
                    'rows' => $exactCount,
                    'size_bytes' => $size,
                    'size_formatted' => $this->formatBytes($size),
                    'engine' => $t->engine ?? 'InnoDB',
                    'created_at' => $t->create_time,
                    'updated_at' => $t->update_time,
                    'description' => $moduleDescriptions[$tableName] ?? 'Tabel Data Sistem KAWAN',
                    'is_system_table' => in_array($tableName, ['migrations', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'sessions', 'password_reset_tokens', 'personal_access_tokens']),
                ];
            }
        } catch (\Exception $e) {
            // Error handling
        }

        return [
            'total_tables' => count($tables),
            'total_rows' => $totalRowsAll,
            'total_size' => $this->formatBytes($totalSizeBytes),
            'tables' => $tables,
        ];
    }

    /**
     * Analisis mendalam status notifikasi data seeder
     */
    protected function getSeederStatus()
    {
        $checklist = [];
        $seededCount = 0;

        $checkModel = function ($key, $title, $description, $target, $callback) use (&$checklist, &$seededCount) {
            $count = 0;
            $status = false;
            try {
                $count = $callback();
                $status = ($count >= $target);
            } catch (\Exception $e) {
                $count = 0;
                $status = false;
            }

            if ($status) {
                $seededCount++;
            }

            $checklist[] = [
                'key' => $key,
                'title' => $title,
                'description' => $description,
                'target' => $target,
                'count' => $count,
                'is_seeded' => $status,
            ];
        };

        // 1. Akun Pengguna Inti (Superadmin, RW, Sekr RW, Bend RW, RT, Sekr RT, Bend RT, Warga)
        $checkModel('users', '8 Akun Pengguna Bawaan', 'Super Administrator, Pengurus RW, Pengurus RT, dan Warga', 4, function() {
            return User::whereIn('email', [
                'superadmin@gmail.com',
                'rw@gmail.com',
                'sekretaris_rw@gmail.com',
                'bendahara_rw@gmail.com',
                'ketua@gmail.com',
                'sekretaris_rt@gmail.com',
                'bendahara@gmail.com',
                'warga@gmail.com',
            ])->count();
        });

        // 2. Konfigurasi Setting RT
        $checkModel('settings', 'Pengaturan Lingkungan RT', 'Identitas RT, Iuran Wajib, Santunan Rukem, dan Kontak RT', 4, function() {
            return Setting::whereIn('key', ['nama_rt', 'nominal_iuran', 'nominal_rukem', 'kontak_rt'])->count();
        });

        // 3. Database Warga
        $checkModel('warga', 'Database 100 Warga Terstruktur', 'Data penduduk lengkap dengan NIK, No KK, hubungan keluarga & alamat', 50, function() {
            return Warga::count();
        });

        // 4. Struktur Pengurus RT
        $checkModel('pengurus', 'Struktur Pengurus RT', 'Ketua RT, Bendahara RT dan perangkat pengurus aktif', 2, function() {
            return Pengurus::count();
        });

        // 5. Saldo & Kas Awal
        $checkModel('kas', 'Buku Kas Utama RT', 'Saldo awal kas dan catatan pengeluaran fasilitas lingkungan', 2, function() {
            return Kas::count();
        });

        // 6. Iuran Warga
        $checkModel('iuran', 'Pencatatan Iuran Warga', 'Riwayat pembayaran dan validasi iuran bulanan warga', 5, function() {
            return Iuran::count();
        });

        // 7. Produk Koperasi RT
        $checkModel('koperasi', 'Koperasi Sembako RT', 'Katalog sembako warga (Beras, Minyak) dan transaksi', 2, function() {
            return Koperasi::count();
        });

        // 8. Bank Sampah RT
        $checkModel('bank_sampah', 'Bank Sampah RT', 'Data penimbangan sampah plastik dan konversi saldo rupiah', 1, function() {
            return BankSampah::count();
        });

        // 9. Katalog UMKM Warga
        $checkModel('umkm', '10 Katalog UMKM Warga', 'Kuliner, Jasa, Sembako, Fashion dilengkapi aset visual', 5, function() {
            return Umkm::count();
        });

        // 10. Layanan Surat Pengantar
        $checkModel('surat', 'Layanan Surat Pengantar', 'Permohonan surat pengantar KTP/KK warga online', 1, function() {
            return Surat::count();
        });

        // 11. Posyandu Balita & Lansia
        $checkModel('posyandu', 'Posyandu Balita & Lansia', 'Pencatatan berat badan, tinggi badan, & vitamin posyandu', 1, function() {
            return Posyandu::count();
        });

        // 12. Keamanan & Ronda
        $checkModel('ronda', 'Jadwal & Regu Ronda RT', 'Pembagian regu elang, pos ronda, dan piket malam', 1, function() {
            return Ronda::count();
        });

        // 13. Agenda Kegiatan RT
        $checkModel('kegiatan', 'Agenda Kegiatan Lingkungan', 'Jadwal kerja bakti, gotong royong dan peringatan hari besar', 1, function() {
            return Kegiatan::count();
        });

        // 14. Rukem Belasungkawa
        $checkModel('rukem', 'Santunan Kematian (Rukem)', 'Data pencairan santunan belasungkawa warga', 1, function() {
            return Rukem::count();
        });

        // 15. Kotak Aspirasi Warga
        $checkModel('aspirasi', 'Kotak Aspirasi & Usulan', 'Penerimaan usulan fasilitas penerangan jalan warga', 1, function() {
            return Aspirasi::count();
        });

        // 16. Asset Inventaris RT
        $checkModel('inventaris', 'Inventaris & Asset RT', 'Pencatatan tenda hajatan, kursi lipat, dan barang milik RT', 2, function() {
            return Inventaris::count();
        });

        // 17. Pengumuman Lingkungan
        $checkModel('pengumuman', 'Pengumuman RT Resmi', 'Warta resmi pengurus untuk seluruh warga', 1, function() {
            return Pengumuman::count();
        });

        // 18. Audit Activity Log
        $checkModel('activity_log', 'Audit Log Aktivitas', 'Rekam jejak inisialisasi sistem 100 warga', 1, function() {
            return ActivityLog::count();
        });

        $totalComponents = count($checklist);
        $percentage = round(($seededCount / $totalComponents) * 100);

        if ($percentage >= 80) {
            $statusState = 'seeded';
            $badgeColor = 'emerald';
            $title = 'DATA SEEDER SUDAH TERMIGRASI';
            $message = 'Seluruh data bawaan awal KAWAN (4 Akun Sistem, 100 Data Warga, Kas RT, UMKM, dan 15 Modul) telah berhasil dimigrasi dan aktif di database.';
        } elseif ($percentage > 0) {
            $statusState = 'partial';
            $badgeColor = 'amber';
            $title = 'DATA SEEDER TERMIGRASI SEBAGIAN (' . $percentage . '%)';
            $message = 'Data seeder terdeteksi sebagian. Beberapa modul telah memiliki data, namun modul lainnya masih kosong.';
        } else {
            $statusState = 'unseeded';
            $badgeColor = 'rose';
            $title = 'DATA SEEDER BELUM TERMIGRASI';
            $message = 'Database masih kosong dan belum memiliki data seeder bawaan. Silakan tekan tombol "Jalankan Data Seeder" untuk menginisialisasi sistem.';
        }

        return [
            'state' => $statusState,
            'badge_color' => $badgeColor,
            'title' => $title,
            'message' => $message,
            'percentage' => $percentage,
            'seeded_count' => $seededCount,
            'total_components' => $totalComponents,
            'checklist' => $checklist,
        ];
    }

    /**
     * Jalankan `php artisan migrate` via Web
     */
    public function runMigrate(Request $request)
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            ActivityLog::log('Eksekusi Migrasi Database', 'Menjalankan php artisan migrate dari Pusat Kendali');

            return redirect()->route('superadmin.database.index')
                ->with('success', 'Migrasi database berhasil dijalankan!')
                ->with('artisan_output', $output);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.database.index')
                ->with('error', 'Gagal menjalankan migrasi: ' . $e->getMessage());
        }
    }

    /**
     * Jalankan `php artisan db:seed` via Web
     */
    public function runSeed(Request $request)
    {
        try {
            Artisan::call('db:seed', ['--force' => true]);
            $output = Artisan::output();

            ActivityLog::log('Eksekusi Seeder Database', 'Menjalankan DatabaseSeeder dari Pusat Kendali');

            return redirect()->route('superadmin.database.index')
                ->with('success', 'Data seeder bawaan berhasil dimigrasikan ke database!')
                ->with('artisan_output', $output);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.database.index')
                ->with('error', 'Gagal menjalankan seeder: ' . $e->getMessage());
        }
    }

    /**
     * Jalankan `php artisan migrate:fresh --seed` via Web
     */
    public function runFresh(Request $request)
    {
        try {
            Artisan::call('migrate:fresh', [
                '--seed' => true,
                '--force' => true
            ]);
            $output = Artisan::output();

            ActivityLog::log('Reset Database & Seed Ulang', 'Menjalankan php artisan migrate:fresh --seed dari Pusat Kendali');

            return redirect()->route('superadmin.database.index')
                ->with('success', 'Database berhasil di-reset, dimigrasi ulang dan seluruh data seeder berhasil diisi!')
                ->with('artisan_output', $output);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.database.index')
                ->with('error', 'Gagal reset database: ' . $e->getMessage());
        }
    }

    /**
     * Jalankan `php artisan optimize:clear`
     */
    public function runOptimize(Request $request)
    {
        try {
            Artisan::call('optimize:clear');
            $output = Artisan::output();

            ActivityLog::log('Optimasi & Bersihkan Cache', 'Membersihkan cache config, route, view dan compiled classes');

            return redirect()->route('superadmin.database.index')
                ->with('success', 'Cache framework berhasil dibersihkan dan dioptimasi!')
                ->with('artisan_output', $output);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.database.index')
                ->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }

    /**
     * API Status Endpoint
     */
    public function apiStatus()
    {
        return response()->json([
            'status' => 'success',
            'database' => $this->getDatabaseOverview(),
            'migrations' => $this->getMigrationStatus(),
            'tables' => $this->getTablesDetailed(),
            'seeders' => $this->getSeederStatus(),
        ]);
    }

    /**
     * Helper formatting bytes
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

