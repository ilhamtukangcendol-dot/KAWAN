<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-purple-200">
                        ⚡ Master Database & Migration Hub
                    </span>
                    <span class="px-2.5 py-1 {{ $dbStatus['connected'] ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-rose-100 text-rose-700 border-rose-200' }} rounded-lg text-[10px] font-black uppercase tracking-widest border">
                        {{ $dbStatus['connected'] ? 'Database Terhubung' : 'Terputus' }}
                    </span>
                </div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-tight mt-1.5 flex items-center gap-2">
                    <span>🗄️ Pusat Kendali Migrasi & Database KAWAN</span>
                </h2>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">
                    Monitoring total tabel data yang termigrasi, riwayat migrasi skema, dan status kesiapan data seeder bawaan.
                </p>
            </div>

            <!-- Action Buttons Header -->
            <div class="flex flex-wrap items-center gap-2" x-data="{ showFreshModal: false }">
                <!-- Tombol Jalankan Migrasi -->
                <form action="{{ route('superadmin.database.migrate') }}" method="POST" onsubmit="return confirm('Jalankan php artisan migrate untuk menerapkan migrasi skema database yang belum termigrasi?');">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-md shadow-indigo-500/20 transition flex items-center gap-2">
                        <span>🚀 Jalankan Migrasi</span>
                    </button>
                </form>

                <!-- Tombol Jalankan Seeder -->
                <form action="{{ route('superadmin.database.seed') }}" method="POST" onsubmit="return confirm('Jalankan DatabaseSeeder untuk mengisi data bawaan awal (100 Warga, 4 Akun Utama, Kas, UMKM)?');">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-md shadow-emerald-500/20 transition flex items-center gap-2">
                        <span>🌱 Jalankan Seeder</span>
                    </button>
                </form>

                <!-- Tombol Clear Cache -->
                <form action="{{ route('superadmin.database.optimize') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow transition flex items-center gap-1.5" title="Bersihkan cache route, view, config">
                        <span>🧹 Optimize Cache</span>
                    </button>
                </form>

                <!-- Tombol Fresh Migrate & Seed Modal Trigger -->
                <button type="button" @click="showFreshModal = true" class="px-3.5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow transition flex items-center gap-1.5" title="Reset Ulang Total (Fresh & Seed)">
                    <span>⚠️ Reset & Re-Seed</span>
                </button>

                <!-- Modal Konfirmasi Reset Ulang Fresh & Seed -->
                <div x-show="showFreshModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
                    <div @click.away="showFreshModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4">
                        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center font-black text-2xl mx-auto">
                            ⚠️
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Konfirmasi Reset Database</h3>
                            <p class="text-xs text-slate-500 font-semibold mt-1">
                                Tindakan ini akan menjalankan <code class="px-1.5 py-0.5 bg-slate-100 rounded text-rose-600 font-mono font-bold">php artisan migrate:fresh --seed</code>. Seluruh data lama akan dihapus dan diinisialisasi ulang dari awal seeder bawaan.
                            </p>
                        </div>
                        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-3 text-xs text-rose-800 font-semibold">
                            ⚠️ Pastikan Anda memahami bahwa seluruh transaksi kas, data iuran, dan akun kustom akan di-reset ke nilai default awal pabrik.
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" @click="showFreshModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                Batal
                            </button>
                            <form action="{{ route('superadmin.database.fresh') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-lg transition">
                                    Ya, Reset & Seed Ulang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-2 border-emerald-400/50 rounded-2xl text-emerald-900 text-sm font-bold shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border-2 border-rose-400/50 rounded-2xl text-rose-900 text-sm font-bold shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🚨</span>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Artisan Console Output Display -->
            @if(session('artisan_output'))
                <div class="bg-slate-950 rounded-3xl p-6 border border-slate-800 shadow-2xl text-slate-100 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            <span class="text-xs font-mono font-bold text-slate-400 ml-2">Terminal Output Console (Artisan)</span>
                        </div>
                        <span class="text-[10px] font-mono text-emerald-400 uppercase tracking-wider">Status: Command Executed</span>
                    </div>
                    <pre class="font-mono text-xs text-emerald-300 overflow-x-auto p-3 bg-slate-900/80 rounded-xl leading-relaxed whitespace-pre-wrap">{{ session('artisan_output') }}</pre>
                </div>
            @endif

            <!-- ============================================================ -->
            <!-- SECTION 1: NOTIFIKASI STATUS DATA SEEDER (PERSYARATAN UTAMA) -->
            <!-- ============================================================ -->
            <div class="rounded-3xl p-8 border-2 shadow-xl transition-all duration-300
                @if($seederStatus['state'] === 'seeded')
                    bg-gradient-to-r from-emerald-950 via-teal-950 to-slate-900 border-emerald-500 text-white shadow-emerald-950/30
                @elseif($seederStatus['state'] === 'partial')
                    bg-gradient-to-r from-amber-950 via-slate-900 to-slate-950 border-amber-500 text-white shadow-amber-950/30
                @else
                    bg-gradient-to-r from-rose-950 via-slate-900 to-slate-950 border-rose-500 text-white shadow-rose-950/30
                @endif
            ">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 border-b border-white/10 pb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0 shadow-lg
                            @if($seederStatus['state'] === 'seeded')
                                bg-emerald-500 text-white shadow-emerald-500/30
                            @elseif($seederStatus['state'] === 'partial')
                                bg-amber-500 text-white shadow-amber-500/30
                            @else
                                bg-rose-500 text-white shadow-rose-500/30
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
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                    @if($seederStatus['state'] === 'seeded')
                                        bg-emerald-400/20 text-emerald-300 border border-emerald-400/40
                                    @elseif($seederStatus['state'] === 'partial')
                                        bg-amber-400/20 text-amber-300 border border-amber-400/40
                                    @else
                                        bg-rose-400/20 text-rose-300 border border-rose-400/40
                                    @endif
                                ">
                                    Notifikasi Status Data Seeder
                                </span>
                                <span class="text-xs font-mono font-bold text-slate-300">
                                    {{ $seederStatus['seeded_count'] }}/{{ $seederStatus['total_components'] }} Komponen ({{ $seederStatus['percentage'] }}%)
                                </span>
                            </div>
                            <h3 class="text-2xl font-black tracking-tight mt-2 flex items-center gap-3">
                                <span>{{ $seederStatus['title'] }}</span>
                            </h3>
                            <p class="text-xs text-slate-200 font-semibold mt-1 max-w-3xl leading-relaxed">
                                {{ $seederStatus['message'] }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        @if($seederStatus['state'] !== 'seeded')
                            <form action="{{ route('superadmin.database.seed') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl shadow-emerald-500/30 transition flex items-center justify-center gap-2">
                                    <span>🌱 Jalankan Seeder Sekarang</span>
                                </button>
                            </form>
                        @else
                            <div class="px-4 py-3 bg-white/10 rounded-2xl border border-white/20 text-center">
                                <span class="text-[10px] uppercase font-bold text-emerald-300 block">Kondisi Seeder</span>
                                <span class="text-sm font-black text-white">Lengkap & Siap Pakai ✨</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progress Bar Data Seeder -->
                <div class="mt-6 space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-300">
                        <span>Kelengkapan Migrasi Dataset Seeder Bawaan</span>
                        <span class="font-mono text-emerald-400">{{ $seederStatus['percentage'] }}% Selesai</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-3 p-0.5 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700
                            @if($seederStatus['state'] === 'seeded')
                                bg-gradient-to-r from-emerald-500 to-teal-400
                            @elseif($seederStatus['state'] === 'partial')
                                bg-gradient-to-r from-amber-500 to-yellow-400
                            @else
                                bg-gradient-to-r from-rose-500 to-red-400
                            @endif
                        " style="width: {{ $seederStatus['percentage'] }}%"></div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 2: STATISTIK TOTAL TABEL & IKHTISAR DATABASE -->
            <!-- ============================================================ -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Tabel yang Sudah Termigrasi -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl relative overflow-hidden group hover:border-purple-200 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Total Tabel Termigrasi</span>
                        <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-base">
                            🗂️
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mt-3 tracking-tight">
                        {{ $tableList['total_tables'] }} <span class="text-sm font-bold text-slate-400">Tabel</span>
                    </h3>
                    <p class="text-xs text-slate-500 font-semibold mt-1">
                        Tercatat di skema database <code class="font-mono text-purple-600 font-bold">{{ $dbStatus['database'] }}</code>
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-slate-600">
                        <span>Status Skema:</span>
                        <span class="text-emerald-600">Terdaftar Aktif ✅</span>
                    </div>
                </div>

                <!-- Total File Migrasi Selesai -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl relative overflow-hidden group hover:border-indigo-200 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">File Migrasi Laravel</span>
                        <div class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-base">
                            📄
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mt-3 tracking-tight">
                        {{ $migrationData['migrated_count'] }} <span class="text-sm font-bold text-slate-400">/ {{ $migrationData['total_files'] }} File</span>
                    </h3>
                    <p class="text-xs text-slate-500 font-semibold mt-1">
                        {{ $migrationData['pending_count'] === 0 ? 'Semua migrasi telah dieksekusi' : $migrationData['pending_count'] . ' migrasi belum dieksekusi' }}
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold">
                        <span class="text-slate-600">Kelulusan Batch:</span>
                        <span class="{{ $migrationData['pending_count'] === 0 ? 'text-emerald-600 font-black' : 'text-amber-600 font-black' }}">
                            {{ $migrationData['percentage'] }}% Migrated
                        </span>
                    </div>
                </div>

                <!-- Total Baris Data Aktif -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl relative overflow-hidden group hover:border-emerald-200 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Total Record Data</span>
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base">
                            📊
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-emerald-600 mt-3 tracking-tight">
                        {{ number_format($tableList['total_rows'], 0, ',', '.') }} <span class="text-sm font-bold text-slate-400">Baris</span>
                    </h3>
                    <p class="text-xs text-slate-500 font-semibold mt-1">
                        Total baris data dari seluruh modul aktif
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-slate-600">
                        <span>Estimasi Ukuran:</span>
                        <span class="font-mono text-indigo-600 font-bold">{{ $tableList['total_size'] }}</span>
                    </div>
                </div>

                <!-- Detail Driver & Server Database -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl relative overflow-hidden group hover:border-amber-200 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Koneksi Database</span>
                        <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-base">
                            🔌
                        </div>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mt-3 tracking-tight">
                        {{ strtoupper($dbStatus['driver']) }} <span class="text-xs font-semibold text-slate-400 font-mono">{{ $dbStatus['server_version'] }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 font-semibold mt-1">
                        Host: <span class="font-mono font-bold text-slate-700">{{ $dbStatus['host'] }}:{{ $dbStatus['port'] }}</span>
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-slate-600">
                        <span>Status Socket:</span>
                        <span class="{{ $dbStatus['connected'] ? 'text-emerald-600 font-black' : 'text-rose-600 font-black' }}">
                            {{ $dbStatus['connected'] ? 'ONLINE 🟢' : 'OFFLINE 🔴' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 3: STATUS 18 DATASET SEEDER BAWAAN KAWAN -->
            <!-- ============================================================ -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-md text-[10px] font-black uppercase tracking-wider">
                                Checklist Modul Seeder
                            </span>
                        </div>
                        <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight mt-1">
                            Rincian Data Seeder Bawaan (18 Modul Lingkungan)
                        </h3>
                        <p class="text-xs text-slate-400 font-semibold">
                            Status verifikasi keberadaan data awal dari <code class="font-mono text-indigo-600 font-bold">DatabaseSeeder.php</code>
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500">
                            Terverifikasi: <strong class="text-emerald-600">{{ $seederStatus['seeded_count'] }}</strong> dari {{ $seederStatus['total_components'] }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($seederStatus['checklist'] as $item)
                        <div class="p-4 rounded-2xl border transition-all duration-200 {{ $item['is_seeded'] ? 'bg-slate-50/70 border-slate-200 hover:border-emerald-300' : 'bg-rose-50/40 border-rose-200 hover:border-rose-400' }}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">{{ $item['is_seeded'] ? '✅' : '❌' }}</span>
                                    <h4 class="font-black text-xs text-slate-800 uppercase tracking-wider">{{ $item['title'] }}</h4>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $item['is_seeded'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $item['is_seeded'] ? 'Termigrasi' : 'Belum Ada' }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-semibold mt-2 line-clamp-2">
                                {{ $item['description'] }}
                            </p>
                            <div class="mt-3 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400 font-bold">Data Terdaftar:</span>
                                <span class="font-mono font-black {{ $item['is_seeded'] ? 'text-emerald-600' : 'text-rose-600' }}">
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
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 rounded-md text-[10px] font-black uppercase tracking-wider">
                                Total: {{ $tableList['total_tables'] }} Tabel Termigrasi
                            </span>
                        </div>
                        <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight mt-1">
                            Daftar Keseluruhan Tabel Database KAWAN
                        </h3>
                        <p class="text-xs text-slate-400 font-semibold">
                            Tabel fisik yang tersimpan di dalam database MySQL beserta jumlah baris data dan ukuran memori.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold px-3 py-1.5 bg-slate-100 rounded-xl text-slate-700">
                            Total Records: <strong class="text-indigo-600">{{ number_format($tableList['total_rows'], 0, ',', '.') }}</strong>
                        </span>
                        <span class="text-xs font-bold px-3 py-1.5 bg-slate-100 rounded-xl text-slate-700">
                            Total Size: <strong class="text-indigo-600">{{ $tableList['total_size'] }}</strong>
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Nama Tabel</th>
                                <th class="py-3 px-4">Peruntukan / Modul</th>
                                <th class="py-3 px-4 text-center">Jumlah Baris</th>
                                <th class="py-3 px-4 text-center">Ukuran (Data + Index)</th>
                                <th class="py-3 px-4 text-center">Engine</th>
                                <th class="py-3 px-4 text-right">Status Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($tableList['tables'] as $index => $table)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-400">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $table['is_system_table'] ? '⚙️' : '📁' }}</span>
                                            <span class="text-indigo-600">{{ $table['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 font-semibold">
                                        {{ $table['description'] }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono font-bold {{ $table['rows'] > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ number_format($table['rows'], 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500 font-semibold">
                                        {{ $table['size_formatted'] }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono text-[11px] text-slate-500">
                                        {{ $table['engine'] }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        @if($table['rows'] > 0)
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                                Terisi ({{ $table['rows'] }})
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                                Kosong
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400 font-semibold">
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
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-800 rounded-md text-[10px] font-black uppercase tracking-wider">
                                File Migration Repository
                            </span>
                        </div>
                        <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight mt-1">
                            Status File Migrasi ({{ $migrationData['migrated_count'] }} / {{ $migrationData['total_files'] }})
                        </h3>
                        <p class="text-xs text-slate-400 font-semibold">
                            Pencatatan file migrasi di folder <code class="font-mono text-indigo-600 font-bold">database/migrations/</code> dan batch eksekusinya.
                        </p>
                    </div>

                    <div>
                        <span class="px-3 py-1.5 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold rounded-xl">
                            Kelulusan Migrasi: {{ $migrationData['percentage'] }}%
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Nama File Migrasi</th>
                                <th class="py-3 px-4 text-center">Batch ID</th>
                                <th class="py-3 px-4 text-center">ID Catatan</th>
                                <th class="py-3 px-4 text-right">Status Migrasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-mono">
                            @forelse($migrationData['details'] as $index => $mig)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-3 px-4 text-slate-400 font-bold">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-800">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $mig['is_migrated'] ? '✅' : '⏳' }}</span>
                                            <span class="{{ $mig['is_migrated'] ? 'text-slate-800' : 'text-amber-600' }}">{{ $mig['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center text-slate-600">
                                        {{ $mig['batch'] ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center text-slate-500">
                                        {{ $mig['id'] ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        @if($mig['is_migrated'])
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-lg text-[10px] font-black uppercase tracking-wider font-sans">
                                                Termigrasi (Batch #{{ $mig['batch'] }})
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-lg text-[10px] font-black uppercase tracking-wider font-sans">
                                                Pending (Belum)
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-semibold font-sans">
                                        Tidak ada berkas migrasi yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

