<x-app-layout>
    <div class="min-h-screen py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6" style="background-color: #0b0f19; color: #f1f5f9;">

        <!-- ============================================================ -->
        <!-- HEADER BANNER PUSAT KENDALI MIGRASI & DATABASE -->
        <!-- ============================================================ -->
        <div class="rounded-3xl p-6 sm:p-8 border shadow-2xl relative overflow-hidden" 
             style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%); border-color: rgba(99, 102, 241, 0.35);">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full blur-3xl pointer-events-none" style="background: rgba(99, 102, 241, 0.15);"></div>
            <div class="absolute -left-10 -top-10 w-64 h-64 rounded-full blur-3xl pointer-events-none" style="background: rgba(16, 185, 129, 0.15);"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6" x-data="{ showFreshModal: false }">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5"
                              style="background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.35);">
                            ⚡ Master Database & Migration Hub
                        </span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5"
                              style="{{ $dbStatus['connected'] ? 'background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.35);' : 'background: rgba(244, 63, 94, 0.2); color: #fda4af; border: 1px solid rgba(244, 63, 94, 0.35);' }}">
                            <span class="w-2 h-2 rounded-full {{ $dbStatus['connected'] ? 'bg-emerald-400 animate-ping' : 'bg-rose-400' }}"></span>
                            {{ $dbStatus['connected'] ? 'Database Terhubung (Online)' : 'Terputus (Offline)' }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-2">
                        <span>🗄️ Pusat Kendali Migrasi & Database KAWAN</span>
                    </h1>
                    <p class="text-xs sm:text-sm font-medium mt-1 max-w-2xl" style="color: #94a3b8;">
                        Monitoring total tabel skema data, audit riwayat migrasi Laravel, serta validasi kelengkapan data seeder awal.
                    </p>
                </div>

                <!-- Tombol Aksi Artisan -->
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Tombol Jalankan Migrasi -->
                    <form action="{{ route('superadmin.database.migrate') }}" method="POST" onsubmit="return confirm('Jalankan php artisan migrate untuk menerapkan migrasi skema database yang belum termigrasi?');">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition flex items-center gap-1.5">
                            <span>🚀 Jalankan Migrasi</span>
                        </button>
                    </form>

                    <!-- Tombol Jalankan Seeder -->
                    <form action="{{ route('superadmin.database.seed') }}" method="POST" onsubmit="return confirm('Jalankan DatabaseSeeder untuk mengisi data bawaan awal (100 Warga, 4 Akun Utama, Kas, UMKM)?');">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition flex items-center gap-1.5">
                            <span>🌱 Jalankan Seeder</span>
                        </button>
                    </form>

                    <!-- Tombol Clear Cache -->
                    <form action="{{ route('superadmin.database.optimize') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-2.5 text-white font-black text-xs uppercase tracking-wider rounded-xl transition flex items-center gap-1.5" 
                                style="background: #1e293b; border: 1px solid #334155;" title="Bersihkan cache route, view, config">
                            <span>🧹 Optimize Cache</span>
                        </button>
                    </form>

                    <!-- Tombol Fresh Migrate & Seed Modal Trigger -->
                    <button type="button" @click="showFreshModal = true" class="px-3.5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition flex items-center gap-1.5" title="Reset Ulang Total (Fresh & Seed)">
                        <span>⚠️ Reset & Re-Seed</span>
                    </button>

                    <!-- Modal Konfirmasi Reset Ulang Fresh & Seed -->
                    <div x-show="showFreshModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" style="background: rgba(2, 6, 23, 0.85); backdrop-filter: blur(8px);">
                        <div @click.away="showFreshModal = false" class="rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4" style="background: #0f172a; border: 1px solid #334155; color: #f8fafc;">
                            <div class="w-12 h-12 bg-rose-500/20 text-rose-400 rounded-2xl flex items-center justify-center font-black text-2xl mx-auto border border-rose-500/30">
                                ⚠️
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-black text-white uppercase tracking-tight">Konfirmasi Reset Database</h3>
                                <p class="text-xs font-semibold mt-1" style="color: #94a3b8;">
                                    Tindakan ini akan mengeksekusi <code class="px-1.5 py-0.5 rounded font-mono font-bold text-rose-400" style="background: #1e293b;">php artisan migrate:fresh --seed</code>. Seluruh data lama akan dihapus dan diinisialisasi ulang.
                                </p>
                            </div>
                            <div class="rounded-2xl p-3 text-xs font-semibold" style="background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.3); color: #fecdd3;">
                                ⚠️ Pastikan Anda memahami bahwa seluruh transaksi kas, data iuran, log akses, dan akun kustom akan di-reset ke nilai bawaan.
                            </div>
                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button" @click="showFreshModal = false" class="px-4 py-2 text-slate-300 text-xs font-bold rounded-xl transition" style="background: #1e293b;">
                                    Batal
                                </button>
                                <form action="{{ route('superadmin.database.fresh') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-lg transition">
                                        Ya, Reset & Seed Ulang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- NOTIFIKASI FLASH & OUTPUT ARTISAN -->
        <!-- ============================================================ -->
        @if(session('success'))
            <div class="p-4 rounded-2xl text-xs font-bold flex items-center gap-3 shadow-lg" 
                 style="background: rgba(6, 78, 59, 0.7); border: 1px solid #10b981; color: #a7f3d0;">
                <span class="text-lg">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl text-xs font-bold flex items-center gap-3 shadow-lg" 
                 style="background: rgba(136, 19, 55, 0.7); border: 1px solid #f43f5e; color: #fecdd3;">
                <span class="text-lg">🚨</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('artisan_output'))
            <div class="rounded-3xl p-6 shadow-2xl space-y-3" style="background: #020617; border: 1px solid #1e293b;">
                <div class="flex items-center justify-between border-b pb-3" style="border-color: #1e293b;">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                        <span class="text-xs font-mono font-bold ml-2" style="color: #94a3b8;">Terminal Output Console (Artisan)</span>
                    </div>
                    <span class="text-[10px] font-mono uppercase tracking-wider text-emerald-400">Status: Command Executed</span>
                </div>
                <pre class="font-mono text-xs overflow-x-auto p-4 rounded-xl leading-relaxed whitespace-pre-wrap" 
                     style="background: #0f172a; color: #34d399; border: 1px solid #1e293b;">{{ session('artisan_output') }}</pre>
            </div>
        @endif

        <!-- ============================================================ -->
        <!-- SECTION 1: NOTIFIKASI STATUS DATA SEEDER (DARK EMBOSSED HERO) -->
        <!-- ============================================================ -->
        <div class="rounded-3xl p-6 sm:p-8 border shadow-2xl transition-all duration-300 relative overflow-hidden"
             style="
                @if($seederStatus['state'] === 'seeded')
                    background: linear-gradient(135deg, #064e3b 0%, #042f2e 50%, #0f172a 100%);
                    border-color: #10b981;
                @elseif($seederStatus['state'] === 'partial')
                    background: linear-gradient(135deg, #78350f 0%, #451a03 50%, #0f172a 100%);
                    border-color: #f59e0b;
                @else
                    background: linear-gradient(135deg, #881337 0%, #4c0519 50%, #0f172a 100%);
                    border-color: #f43f5e;
                @endif
             ">
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 border-b pb-6" style="border-color: rgba(255, 255, 255, 0.12);">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0 shadow-lg border"
                         style="
                            @if($seederStatus['state'] === 'seeded')
                                background: #10b981; border-color: #34d399;
                            @elseif($seederStatus['state'] === 'partial')
                                background: #f59e0b; border-color: #fbbf24;
                            @else
                                background: #f43f5e; border-color: #fb7185;
                            @endif
                         ">
                        @if($seederStatus['state'] === 'seeded')
                            ✅
                        @elseif($seederStatus['state'] === 'partial')
                            ⚠️
                        @else
                            🚨
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"
                                 style="
                                    @if($seederStatus['state'] === 'seeded')
                                        background: rgba(16, 185, 129, 0.25); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.5);
                                    @elseif($seederStatus['state'] === 'partial')
                                        background: rgba(245, 158, 11, 0.25); color: #fcd34d; border: 1px solid rgba(245, 158, 11, 0.5);
                                    @else
                                        background: rgba(244, 63, 94, 0.25); color: #fda4af; border: 1px solid rgba(244, 63, 94, 0.5);
                                    @endif
                                 ">
                                Notifikasi Status Data Seeder
                            </span>
                            <span class="text-xs font-mono font-bold text-slate-300">
                                {{ $seederStatus['seeded_count'] }}/{{ $seederStatus['total_components'] }} Komponen ({{ $seederStatus['percentage'] }}%)
                            </span>
                        </div>

                        <!-- Judul Notifikasi (Warna Putih Tebal & Terang) -->
                        <h3 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-2 flex items-center gap-3">
                            <span>{{ $seederStatus['title'] }}</span>
                        </h3>
                        <p class="text-xs sm:text-sm font-semibold mt-1 max-w-3xl leading-relaxed" style="color: #cbd5e1;">
                            {{ $seederStatus['message'] }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    @if($seederStatus['state'] !== 'seeded')
                        <form action="{{ route('superadmin.database.seed') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl transition flex items-center justify-center gap-2">
                                <span>🌱 Jalankan Seeder Sekarang</span>
                            </button>
                        </form>
                    @else
                        <div class="px-5 py-3 rounded-2xl border text-center shadow-lg" style="background: rgba(15, 23, 42, 0.7); border-color: rgba(52, 211, 153, 0.4);">
                            <span class="text-[10px] uppercase font-bold text-emerald-400 block tracking-wider">Kondisi Seeder</span>
                            <span class="text-sm font-black text-white">Lengkap & Siap Pakai ✨</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress Bar Data Seeder -->
            <div class="mt-6 space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-200">
                    <span>Kelengkapan Migrasi Dataset Seeder Bawaan</span>
                    <span class="font-mono text-emerald-300 font-black">{{ $seederStatus['percentage'] }}% Selesai</span>
                </div>
                <div class="w-full rounded-full h-3.5 p-0.5 overflow-hidden" style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="h-full rounded-full transition-all duration-700" 
                         style="
                            width: {{ $seederStatus['percentage'] }}%;
                            background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
                         "></div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECTION 2: STATISTIK TOTAL TABEL & IKHTISAR DATABASE -->
        <!-- ============================================================ -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Total Tabel yang Sudah Termigrasi -->
            <div class="rounded-3xl p-6 border shadow-xl relative overflow-hidden transition"
                 style="background: #0f172a; border-color: #1e293b;">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider" style="color: #94a3b8;">Total Tabel Termigrasi</span>
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-base"
                         style="background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3);">
                        🗂️
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white mt-3 tracking-tight">
                    {{ $tableList['total_tables'] }} <span class="text-sm font-bold" style="color: #64748b;">Tabel</span>
                </h3>
                <p class="text-xs font-semibold mt-1" style="color: #94a3b8;">
                    Tercatat di skema database <code class="font-mono font-bold text-purple-400">{{ $dbStatus['database'] }}</code>
                </p>
                <div class="mt-4 pt-3 border-t flex items-center justify-between text-[11px] font-bold" style="border-color: #1e293b;">
                    <span style="color: #64748b;">Status Skema:</span>
                    <span class="text-emerald-400 font-bold">Terdaftar Aktif ✅</span>
                </div>
            </div>

            <!-- Total File Migrasi Selesai -->
            <div class="rounded-3xl p-6 border shadow-xl relative overflow-hidden transition"
                 style="background: #0f172a; border-color: #1e293b;">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider" style="color: #94a3b8;">File Migrasi Laravel</span>
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-base"
                         style="background: rgba(99, 102, 241, 0.2); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3);">
                        📄
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white mt-3 tracking-tight">
                    {{ $migrationData['migrated_count'] }} <span class="text-sm font-bold" style="color: #64748b;">/ {{ $migrationData['total_files'] }} File</span>
                </h3>
                <p class="text-xs font-semibold mt-1" style="color: #94a3b8;">
                    {{ $migrationData['pending_count'] === 0 ? 'Semua migrasi telah dieksekusi' : $migrationData['pending_count'] . ' migrasi belum dieksekusi' }}
                </p>
                <div class="mt-4 pt-3 border-t flex items-center justify-between text-[11px] font-bold" style="border-color: #1e293b;">
                    <span style="color: #64748b;">Kelulusan Batch:</span>
                    <span class="{{ $migrationData['pending_count'] === 0 ? 'text-emerald-400 font-black' : 'text-amber-400 font-black' }}">
                        {{ $migrationData['percentage'] }}% Migrated
                    </span>
                </div>
            </div>

            <!-- Total Baris Data Aktif -->
            <div class="rounded-3xl p-6 border shadow-xl relative overflow-hidden transition"
                 style="background: #0f172a; border-color: #1e293b;">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider" style="color: #94a3b8;">Total Record Data</span>
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-base"
                         style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                        📊
                    </div>
                </div>
                <h3 class="text-3xl font-black text-emerald-400 mt-3 tracking-tight">
                    {{ number_format($tableList['total_rows'], 0, ',', '.') }} <span class="text-sm font-bold" style="color: #64748b;">Baris</span>
                </h3>
                <p class="text-xs font-semibold mt-1" style="color: #94a3b8;">
                    Total baris data dari seluruh modul aktif
                </p>
                <div class="mt-4 pt-3 border-t flex items-center justify-between text-[11px] font-bold" style="border-color: #1e293b;">
                    <span style="color: #64748b;">Estimasi Ukuran:</span>
                    <span class="font-mono text-cyan-400 font-bold">{{ $tableList['total_size'] }}</span>
                </div>
            </div>

            <!-- Detail Driver & Server Database -->
            <div class="rounded-3xl p-6 border shadow-xl relative overflow-hidden transition"
                 style="background: #0f172a; border-color: #1e293b;">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider" style="color: #94a3b8;">Koneksi Database</span>
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-base"
                         style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3);">
                        🔌
                    </div>
                </div>
                <h3 class="text-2xl font-black text-white mt-3 tracking-tight">
                    {{ strtoupper($dbStatus['driver']) }} <span class="text-xs font-semibold font-mono" style="color: #64748b;">{{ $dbStatus['server_version'] }}</span>
                </h3>
                <p class="text-xs font-semibold mt-1" style="color: #94a3b8;">
                    Host: <span class="font-mono font-bold text-slate-300">{{ $dbStatus['host'] }}:{{ $dbStatus['port'] }}</span>
                </p>
                <div class="mt-4 pt-3 border-t flex items-center justify-between text-[11px] font-bold" style="border-color: #1e293b;">
                    <span style="color: #64748b;">Status Socket:</span>
                    <span class="{{ $dbStatus['connected'] ? 'text-emerald-400 font-black' : 'text-rose-400 font-black' }}">
                        {{ $dbStatus['connected'] ? 'ONLINE 🟢' : 'OFFLINE 🔴' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECTION 3: STATUS 18 DATASET SEEDER BAWAAN KAWAN -->
        <!-- ============================================================ -->
        <div class="rounded-3xl p-6 sm:p-8 border shadow-xl space-y-6" style="background: #0f172a; border-color: #1e293b;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-5" style="border-color: #1e293b;">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider"
                              style="background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3);">
                            Checklist Modul Seeder
                        </span>
                    </div>
                    <h3 class="font-black text-xl text-white uppercase tracking-tight mt-1">
                        Rincian Data Seeder Bawaan (18 Modul Lingkungan)
                    </h3>
                    <p class="text-xs font-semibold" style="color: #94a3b8;">
                        Status verifikasi keberadaan data awal dari <code class="font-mono font-bold text-indigo-400">DatabaseSeeder.php</code>
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-300">
                        Terverifikasi: <strong class="text-emerald-400">{{ $seederStatus['seeded_count'] }}</strong> dari {{ $seederStatus['total_components'] }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($seederStatus['checklist'] as $item)
                    <div class="p-4 rounded-2xl border transition-all duration-200" 
                         style="{{ $item['is_seeded'] ? 'background: #0b1120; border-color: rgba(51, 65, 85, 0.7);' : 'background: rgba(136, 19, 55, 0.15); border-color: rgba(244, 63, 94, 0.3);' }}">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-base">{{ $item['is_seeded'] ? '✅' : '❌' }}</span>
                                <h4 class="font-black text-xs text-white uppercase tracking-wider">{{ $item['title'] }}</h4>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider"
                                  style="{{ $item['is_seeded'] ? 'background: rgba(16, 185, 129, 0.2); color: #34d399;' : 'background: rgba(244, 63, 94, 0.2); color: #fb7185;' }}">
                                {{ $item['is_seeded'] ? 'Termigrasi' : 'Belum Ada' }}
                            </span>
                        </div>
                        <p class="text-[11px] font-semibold mt-2 line-clamp-2" style="color: #94a3b8;">
                            {{ $item['description'] }}
                        </p>
                        <div class="mt-3 pt-2 border-t flex items-center justify-between text-[11px]" style="border-color: #1e293b;">
                            <span class="font-bold" style="color: #64748b;">Data Terdaftar:</span>
                            <span class="font-mono font-black {{ $item['is_seeded'] ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $item['count'] }} data (Min: {{ $item['target'] }})
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECTION 4: DAFTAR SELURUH TABEL DATABASE TERMIGRASI -->
        <!-- ============================================================ -->
        <div class="rounded-3xl p-6 sm:p-8 border shadow-xl space-y-6" style="background: #0f172a; border-color: #1e293b;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-5" style="border-color: #1e293b;">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider"
                              style="background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3);">
                            Total: {{ $tableList['total_tables'] }} Tabel Termigrasi
                        </span>
                    </div>
                    <h3 class="font-black text-xl text-white uppercase tracking-tight mt-1">
                        Daftar Keseluruhan Tabel Database KAWAN
                    </h3>
                    <p class="text-xs font-semibold" style="color: #94a3b8;">
                        Tabel fisik yang tersimpan di dalam database MySQL beserta jumlah baris data dan ukuran memori.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold px-3 py-1.5 rounded-xl" style="background: #1e293b; color: #cbd5e1;">
                        Total Records: <strong class="text-indigo-400">{{ number_format($tableList['total_rows'], 0, ',', '.') }}</strong>
                    </span>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-xl" style="background: #1e293b; color: #cbd5e1;">
                        Total Size: <strong class="text-indigo-400">{{ $tableList['total_size'] }}</strong>
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-[10px] font-black uppercase tracking-wider" style="border-color: #1e293b; color: #64748b;">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Nama Tabel</th>
                            <th class="py-3 px-4">Peruntukan / Modul</th>
                            <th class="py-3 px-4 text-center">Jumlah Baris</th>
                            <th class="py-3 px-4 text-center">Ukuran (Data + Index)</th>
                            <th class="py-3 px-4 text-center">Engine</th>
                            <th class="py-3 px-4 text-right">Status Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-xs" style="border-color: #1e293b;">
                        @forelse($tableList['tables'] as $index => $table)
                            <tr class="transition" style="border-color: #1e293b;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='transparent'">
                                <td class="py-3.5 px-4 font-mono font-bold" style="color: #64748b;">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-white">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $table['is_system_table'] ? '⚙️' : '📁' }}</span>
                                        <span class="text-indigo-400">{{ $table['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-semibold" style="color: #cbd5e1;">
                                    {{ $table['description'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold {{ $table['rows'] > 0 ? 'text-emerald-400' : 'text-slate-500' }}">
                                    {{ number_format($table['rows'], 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-semibold" style="color: #94a3b8;">
                                    {{ $table['size_formatted'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono text-[11px]" style="color: #64748b;">
                                    {{ $table['engine'] }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    @if($table['rows'] > 0)
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider"
                                              style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                            Terisi ({{ $table['rows'] }})
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider"
                                              style="background: #1e293b; color: #64748b;">
                                            Kosong
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center font-semibold" style="color: #64748b;">
                                    Tidak ada tabel yang ditemukan di skema database. Silakan jalankan migrasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECTION 5: DAFTAR RIWAYAT FILE MIGRASI LARAVEL -->
        <!-- ============================================================ -->
        <div class="rounded-3xl p-6 sm:p-8 border shadow-xl space-y-6" style="background: #0f172a; border-color: #1e293b;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-5" style="border-color: #1e293b;">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider"
                              style="background: rgba(99, 102, 241, 0.2); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3);">
                            File Migration Repository
                        </span>
                    </div>
                    <h3 class="font-black text-xl text-white uppercase tracking-tight mt-1">
                        Status File Migrasi ({{ $migrationData['migrated_count'] }} / {{ $migrationData['total_files'] }})
                    </h3>
                    <p class="text-xs font-semibold" style="color: #94a3b8;">
                        Pencatatan file migrasi di folder <code class="font-mono font-bold text-indigo-400">database/migrations/</code> dan batch eksekusinya.
                    </p>
                </div>

                <div>
                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold"
                          style="background: rgba(99, 102, 241, 0.2); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3);">
                        Kelulusan Migrasi: {{ $migrationData['percentage'] }}%
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-[10px] font-black uppercase tracking-wider" style="border-color: #1e293b; color: #64748b;">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Nama File Migrasi</th>
                            <th class="py-3 px-4 text-center">Batch ID</th>
                            <th class="py-3 px-4 text-center">ID Catatan</th>
                            <th class="py-3 px-4 text-right">Status Migrasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-xs font-mono" style="border-color: #1e293b;">
                        @forelse($migrationData['details'] as $index => $mig)
                            <tr class="transition" style="border-color: #1e293b;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='transparent'">
                                <td class="py-3 px-4 font-bold" style="color: #64748b;">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3 px-4 font-bold text-white">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $mig['is_migrated'] ? '✅' : '⏳' }}</span>
                                        <span class="{{ $mig['is_migrated'] ? 'text-slate-200' : 'text-amber-400' }}">{{ $mig['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center" style="color: #94a3b8;">
                                    {{ $mig['batch'] ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-center" style="color: #64748b;">
                                    {{ $mig['id'] ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if($mig['is_migrated'])
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider font-sans"
                                              style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                            Termigrasi (Batch #{{ $mig['batch'] }})
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider font-sans"
                                              style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3);">
                                            Pending (Belum)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center font-semibold font-sans" style="color: #64748b;">
                                    Tidak ada berkas migrasi yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
