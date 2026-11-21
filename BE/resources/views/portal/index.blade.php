<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

        <!-- HEADER BANNER -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 border border-indigo-500/30 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-10 -top-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-[10px] font-black uppercase tracking-widest rounded-full flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                            IoT Hardware & Access Control
                        </span>
                        <span class="px-3 py-1 bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-[10px] font-black uppercase tracking-widest rounded-full">
                            ESP32 + e-KTP RFID/NFC
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        <span>🚪 Portal Gerbang Perumahan & IoT RFID</span>
                    </h1>
                    <p class="text-slate-400 text-xs sm:text-sm mt-1 max-w-2xl">
                        Sistem keamanan gerbang otomatis terintegrasi. Warga dapat melakukan tap e-KTP untuk membuka palang pintu. Akses ditolak otomatis jika KTP belum terdaftar.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('portal.firmware') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-cyan-300 border border-cyan-500/30 hover:border-cyan-400/60 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-lg">
                        <span>⚡</span> Panduan & Kode ESP32
                    </a>
                    <a href="{{ route('portal.logs') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-lg">
                        <span>📋</span> Riwayat Lengkap Akses
                    </a>
                </div>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES / ERROR -->
        @if(session('success'))
            <div class="p-4 bg-emerald-950/60 border border-emerald-500/40 text-emerald-200 rounded-2xl text-xs font-semibold flex items-center gap-3 shadow-lg">
                <span class="text-lg">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-950/60 border border-rose-500/40 text-rose-200 rounded-2xl text-xs font-semibold flex items-center gap-3 shadow-lg">
                <span class="text-lg">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- STATS CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total KTP Terdaftar</span>
                    <span class="text-xl">💳</span>
                </div>
                <p class="text-2xl font-black text-white mt-2">{{ $stats['total_registered'] }} <span class="text-xs font-medium text-slate-400">Warga</span></p>
                <p class="text-[10px] text-cyan-400 font-bold mt-1">Kartu KTP aktif di sistem</p>
            </div>

            <div class="bg-slate-900/90 border border-emerald-900/40 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-400">Akses Diterima (Hari Ini)</span>
                    <span class="text-xl">🟢</span>
                </div>
                <p class="text-2xl font-black text-emerald-400 mt-2">{{ $stats['total_granted_today'] }} <span class="text-xs font-medium text-slate-400">Tap</span></p>
                <p class="text-[10px] text-slate-400 font-bold mt-1">Gerbang berhasil terbuka</p>
            </div>

            <div class="bg-slate-900/90 border border-rose-900/40 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-rose-400">Akses Ditolak (Hari Ini)</span>
                    <span class="text-xl">🚫</span>
                </div>
                <p class="text-2xl font-black text-rose-400 mt-2">{{ $stats['total_denied_today'] }} <span class="text-xs font-medium text-slate-400">Tap</span></p>
                <p class="text-[10px] text-rose-300/80 font-bold mt-1">KTP asing / belum terdaftar</p>
            </div>

            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Status Perangkat ESP32</span>
                    <span class="text-xl">📡</span>
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-3 h-3 rounded-full {{ $stats['device_online'] ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' }}"></span>
                    <p class="text-lg font-black text-white">{{ $stats['device_online'] ? 'Online & Siap' : 'Menunggu Sinyal' }}</p>
                </div>
                <p class="text-[10px] text-slate-400 font-bold mt-1">{{ $device->name }} ({{ $device->device_id }})</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- KOLOM KIRI: SIMULATOR GERBANG & PAIRING KTP (5 COLS) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- KARTU STATUS KTP USER YANG LOGIN -->
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-600/30 border border-indigo-500/40 flex items-center justify-center text-lg text-indigo-300">
                                🪪
                            </div>
                            <div>
                                <h2 class="text-sm font-black text-white">Status e-KTP Saya</h2>
                                <p class="text-[10px] text-slate-400">{{ Auth::user()->name }} ({{ Auth::user()->role_label }})</p>
                            </div>
                        </div>
                        @if(Auth::user()->hasRegisteredKtp())
                            <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-black uppercase tracking-wider rounded-lg">
                                Terdaftar
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-black uppercase tracking-wider rounded-lg">
                                Belum Terdaftar
                            </span>
                        @endif
                    </div>

                    @if(Auth::user()->hasRegisteredKtp())
                        <div class="bg-gradient-to-r from-slate-950 to-slate-900 border border-slate-800 rounded-2xl p-4 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-400">UID e-KTP RFID:</span>
                                <span class="font-mono font-black text-cyan-300 text-sm tracking-wider px-2 py-0.5 bg-slate-900 rounded border border-cyan-500/30">{{ Auth::user()->rfid_uid }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-400">Status Akses:</span>
                                <span class="font-bold {{ Auth::user()->portal_access_enabled ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ Auth::user()->portal_access_enabled ? 'Aktif (Dapat Buka Palang)' : 'Dinonaktifkan Sementara' }}
                                </span>
                            </div>
                            @if(Auth::user()->rfid_registered_at)
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-400">Tanggal Daftar:</span>
                                    <span class="text-slate-300 text-[11px]">{{ \Carbon\Carbon::parse(Auth::user()->rfid_registered_at)->format('d M Y H:i') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-2 mt-4">
                            <button type="button" onclick="openPairingModal()" class="flex-1 py-2.5 bg-indigo-600/30 hover:bg-indigo-600 text-indigo-200 hover:text-white border border-indigo-500/40 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                                <span>🔄</span> Ganti / Daftarkan Ulang KTP
                            </button>
                            <form action="{{ route('portal.remove-rfid', Auth::user()) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kartu KTP Anda dari portal gerbang?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-rose-950/40 hover:bg-rose-900/60 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-bold transition">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-amber-950/20 border border-amber-500/30 rounded-2xl p-4 text-center space-y-3">
                            <span class="text-3xl block">🪪</span>
                            <p class="text-xs text-amber-200">
                                Anda belum mendaftarkan e-KTP. Daftarkan e-KTP Anda sekarang agar dapat membuka palang gerbang perumahan otomatis saat pulang ke rumah!
                            </p>
                            <button type="button" onclick="openPairingModal()" class="w-full py-2.5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white rounded-xl text-xs font-black tracking-wide shadow-lg transition flex items-center justify-center gap-2">
                                <span>✨</span> DAFTARKAN e-KTP SAYA SEKARANG
                            </button>
                        </div>
                    @endif
                </div>

                <!-- SIMULATOR PALANG PINTU GERBANG (INTERACTIVE GATE) -->
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h2 class="text-sm font-black text-white flex items-center gap-2">
                            <span>🚧</span> Visualisasi Palang Gerbang
                        </h2>
                        <span id="gate-badge" class="px-2.5 py-1 bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-black uppercase tracking-wider rounded-lg">
                            Tertutup
                        </span>
                    </div>

                    <!-- ANIMATED GATE BARRIER GRAPHIC -->
                    <div class="bg-slate-950 border border-slate-800/80 rounded-2xl p-6 flex flex-col items-center justify-center min-h-[200px] relative overflow-hidden">
                        <!-- Jalan Perumahan -->
                        <div class="w-full h-8 bg-slate-900 rounded-md border-t border-b border-dashed border-slate-700 flex items-center justify-center mb-6">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-500">JALUR KENDARAAN WARGA</span>
                        </div>

                        <!-- Palang Gerbang SVG & Tiang -->
                        <div class="relative w-64 h-24 flex items-end justify-start">
                            <!-- Tiang Penyangga -->
                            <div class="w-8 h-20 bg-gradient-to-t from-slate-800 to-slate-700 rounded-t-lg border-2 border-slate-600 relative z-20 flex flex-col items-center justify-between py-2 shadow-2xl">
                                <span id="gate-led" class="w-3 h-3 rounded-full bg-rose-500 shadow-lg shadow-rose-500/50"></span>
                                <span class="text-[7px] font-black text-slate-300">ESP32</span>
                            </div>

                            <!-- Batang Palang Pintu yang Bisa Terangkat (CSS Rotate) -->
                            <div id="gate-arm" class="w-52 h-3.5 bg-gradient-to-r from-red-600 via-white to-red-600 rounded-r-full border border-black shadow-lg origin-bottom-left transition-transform duration-700 ease-in-out absolute left-6 bottom-4 z-10" style="transform: rotate(0deg);"></div>
                        </div>

                        <!-- Live Status Teks -->
                        <div class="mt-4 text-center">
                            <p id="gate-status-text" class="text-xs font-bold text-slate-300">Palang Gerbang dalam Posisi Menutup</p>
                            <p class="text-[10px] text-slate-500">Otomatis terangkat saat KTP terdaftar di-tap</p>
                        </div>
                    </div>

                    <!-- TOMBOL BUKA MANUAL (PENGURUS / DARURAT) -->
                    <form action="{{ route('portal.open-manual') }}" method="POST" id="manual-open-form">
                        @csrf
                        <button type="submit" onclick="simulateGateOpen(event)" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-xl shadow-emerald-900/30 transition flex items-center justify-center gap-2">
                            <span>⚡</span> Buka Palang Gerbang Manual (Darurat)
                        </button>
                    </form>
                </div>

            </div>

            <!-- KOLOM KANAN: FEED LOG AKTIVITAS TAP REAL-TIME & DAFTAR WARGA (7 COLS) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- FEED SCAN REAL-TIME TERBARU -->
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-400 animate-pulse"></span>
                            <h2 class="text-sm font-black text-white">Live Feed Tap Gerbang Terkini</h2>
                        </div>
                        <a href="{{ route('portal.logs') }}" class="text-[11px] font-bold text-cyan-400 hover:text-cyan-300">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <div id="live-logs-container" class="space-y-2.5 max-h-[290px] overflow-y-auto custom-scrollbar pr-1">
                        @forelse($recentLogs as $log)
                            <div class="p-3.5 rounded-2xl border transition flex items-center justify-between gap-3 {{ $log->status == 'granted' ? 'bg-emerald-950/20 border-emerald-500/30 hover:border-emerald-500/50' : 'bg-rose-950/20 border-rose-500/30 hover:border-rose-500/50' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black shrink-0 {{ $log->status == 'granted' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-rose-500/20 text-rose-400 border border-rose-500/40' }}">
                                        {{ $log->status == 'granted' ? '✓' : '✗' }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-xs font-black text-white">{{ $log->user_name ?? ($log->user ? $log->user->name : 'Tidak Dikenal') }}</p>
                                            <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-950 border border-slate-800 text-slate-300">
                                                UID: {{ $log->rfid_uid }}
                                            </span>
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $log->keterangan }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $log->status == 'granted' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                                        {{ $log->status == 'granted' ? 'Diterima' : 'Ditolak' }}
                                    </span>
                                    <p class="text-[9px] text-slate-500 font-bold mt-1">{{ $log->created_at->format('H:i:s') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-500 text-xs">
                                Belum ada riwayat tap gerbang hari ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- DAFTAR KARTU KTP WARGA (KHUSUS PENGURUS & SUPERADMIN) -->
                @if(Auth::user()->role != \App\Models\User::ROLE_WARGA && $usersList)
                    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-4">
                            <div>
                                <h2 class="text-sm font-black text-white">Manajemen Kartu KTP Warga</h2>
                                <p class="text-[10px] text-slate-400">Atur hak akses dan UID KTP untuk seluruh warga perumahan</p>
                            </div>
                            <form method="GET" action="{{ route('portal.index') }}" class="flex items-center gap-2">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari warga / UID..." class="px-3 py-1.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:ring-1 focus:ring-cyan-500">
                                <button type="submit" class="px-3 py-1.5 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl text-xs font-bold transition">
                                    Cari
                                </button>
                            </form>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-300">
                                <thead>
                                    <tr class="border-b border-slate-800 text-[10px] font-black uppercase text-slate-400">
                                        <th class="py-2.5 px-3">Nama Warga</th>
                                        <th class="py-2.5 px-3">Jabatan</th>
                                        <th class="py-2.5 px-3">UID e-KTP</th>
                                        <th class="py-2.5 px-3">Akses Gerbang</th>
                                        <th class="py-2.5 px-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/60">
                                    @foreach($usersList as $itemUser)
                                        <tr class="hover:bg-slate-800/40 transition">
                                            <td class="py-3 px-3">
                                                <div class="font-bold text-white">{{ $itemUser->name }}</div>
                                                <div class="text-[10px] text-slate-500">{{ $itemUser->email }}</div>
                                            </td>
                                            <td class="py-3 px-3">
                                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-800 text-slate-300">
                                                    {{ $itemUser->role_label }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-3">
                                                @if($itemUser->rfid_uid)
                                                    <span class="font-mono font-bold text-cyan-300 px-2 py-0.5 bg-slate-950 rounded border border-cyan-500/30 text-[11px]">
                                                        {{ $itemUser->rfid_uid }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-500 text-[11px] italic">Belum Ada</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3">
                                                @if($itemUser->rfid_uid)
                                                    <form action="{{ route('portal.toggle-access', $itemUser) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 rounded-lg text-[10px] font-black uppercase transition {{ $itemUser->portal_access_enabled ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 hover:bg-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/40 hover:bg-rose-500/30' }}">
                                                            {{ $itemUser->portal_access_enabled ? '✓ Aktif' : '✗ Diblokir' }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-slate-600 text-[10px]">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3 text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <button type="button" onclick="openAdminRegisterModal({{ $itemUser->id }}, '{{ addslashes($itemUser->name) }}', '{{ $itemUser->rfid_uid ?? '' }}')" class="p-1.5 bg-indigo-600/30 hover:bg-indigo-600 text-indigo-300 hover:text-white rounded-lg transition" title="Daftarkan / Edit UID KTP">
                                                        ✏️
                                                    </button>
                                                    @if($itemUser->rfid_uid)
                                                        <form action="{{ route('portal.remove-rfid', $itemUser) }}" method="POST" onsubmit="return confirm('Hapus kartu KTP milik {{ $itemUser->name }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-1.5 bg-rose-600/30 hover:bg-rose-600 text-rose-300 hover:text-white rounded-lg transition" title="Hapus Kartu">
                                                                🗑️
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-2">
                            {{ $usersList->links() }}
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- MODAL PENDAFTARAN / PAIRING KTP (USER & ADMIN) -->
    <div id="pairing-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🪪</span>
                    <div>
                        <h3 class="text-base font-black text-white" id="modal-title">Daftarkan e-KTP untuk Akses Gerbang</h3>
                        <p class="text-[11px] text-slate-400">Pilih metode pendaftaran kartu KTP Anda</p>
                    </div>
                </div>
                <button type="button" onclick="closePairingModal()" class="text-slate-400 hover:text-white p-2">✕</button>
            </div>

            <!-- TABS PILIHAN SCAN -->
            <div class="flex gap-2 p-1 bg-slate-950 border border-slate-800 rounded-2xl">
                <button type="button" onclick="switchScanTab('esp32')" id="tab-btn-esp32" class="flex-1 py-2 rounded-xl text-xs font-bold transition bg-indigo-600 text-white">
                    📡 Tap di ESP32 Gerbang
                </button>
                <button type="button" onclick="switchScanTab('nfc')" id="tab-btn-nfc" class="flex-1 py-2 rounded-xl text-xs font-bold transition text-slate-400 hover:text-white">
                    📱 Web NFC HP
                </button>
                <button type="button" onclick="switchScanTab('manual')" id="tab-btn-manual" class="flex-1 py-2 rounded-xl text-xs font-bold transition text-slate-400 hover:text-white">
                    ⌨️ Ketik Manual
                </button>
            </div>

            <!-- TAB 1: PAIRING VIA ESP32 (JARAK JAUH) -->
            <div id="tab-content-esp32" class="space-y-4 text-center">
                <div class="p-5 bg-slate-950/80 border border-indigo-500/30 rounded-2xl space-y-3">
                    <div class="w-16 h-16 mx-auto rounded-full bg-indigo-600/20 border-2 border-indigo-500/50 flex items-center justify-center text-3xl animate-bounce">
                        💳
                    </div>
                    <h4 class="text-sm font-black text-white">Mode Siap Scan di Reader Gerbang</h4>
                    <p class="text-xs text-slate-400">
                        Klik tombol di bawah, lalu dalam 90 detik, <strong class="text-indigo-300">tempelkan e-KTP Anda ke Reader ESP32 di gerbang</strong>. UID akan otomatis tersimpan!
                    </p>
                    <div id="pairing-timer-box" class="hidden">
                        <span class="text-3xl font-mono font-black text-cyan-400" id="pairing-countdown">90</span>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-1">Detik Tersisa (Menunggu Tap...)</p>
                    </div>
                </div>

                <button type="button" onclick="startEsp32Pairing()" id="btn-start-pairing" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-lg transition">
                    Mulai Sesi Scan KTP di ESP32
                </button>
            </div>

            <!-- TAB 2: WEB NFC SCAN VIA SMARTPHONE -->
            <div id="tab-content-nfc" class="hidden space-y-4 text-center">
                <div class="p-5 bg-slate-950/80 border border-cyan-500/30 rounded-2xl space-y-3">
                    <div class="w-16 h-16 mx-auto rounded-full bg-cyan-600/20 border-2 border-cyan-500/50 flex items-center justify-center text-3xl">
                        📲
                    </div>
                    <h4 class="text-sm font-black text-white">Scan KTP Lewat Sensor NFC HP</h4>
                    <p class="text-xs text-slate-400">
                        Khusus pengguna Android (Chrome) dengan fitur NFC aktif. Tempelkan e-KTP ke bagian belakang bodi HP Anda.
                    </p>
                    <p id="nfc-support-status" class="text-[11px] font-bold text-amber-400"></p>
                </div>

                <button type="button" onclick="startWebNfcScan()" id="btn-start-nfc" class="w-full py-3 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-lg transition">
                    Aktifkan Scan NFC di HP
                </button>
            </div>

            <!-- TAB 3: INPUT MANUAL UID -->
            <div id="tab-content-manual" class="hidden space-y-4">
                <form action="{{ route('portal.register-rfid') }}" method="POST" id="manual-register-form">
                    @csrf
                    <input type="hidden" name="user_id" id="modal-target-user-id" value="">
                    
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300">UID e-KTP / RFID (Hexadecimal):</label>
                        <input type="text" name="rfid_uid" id="manual-uid-input" required placeholder="Contoh: 04A23B1C atau 04:A2:3B:1C" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-sm font-mono font-bold text-cyan-300 uppercase tracking-widest placeholder-slate-600 focus:ring-2 focus:ring-cyan-500">
                        <p class="text-[10px] text-slate-500">Masukkan 8 atau 14 digit kode UID unik kartu Anda.</p>
                    </div>

                    <button type="submit" class="w-full mt-4 py-3 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-lg transition">
                        Simpan Kartu e-KTP
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC REALTIME & NFC -->
    <script>
        let pairingInterval = null;

        function openPairingModal() {
            document.getElementById('modal-target-user-id').value = '';
            document.getElementById('modal-title').innerText = 'Daftarkan e-KTP Saya';
            document.getElementById('pairing-modal').classList.remove('hidden');
            switchScanTab('esp32');
        }

        function openAdminRegisterModal(userId, userName, currentUid) {
            document.getElementById('modal-target-user-id').value = userId;
            document.getElementById('modal-title').innerText = 'Daftarkan KTP untuk: ' + userName;
            document.getElementById('manual-uid-input').value = currentUid;
            document.getElementById('pairing-modal').classList.remove('hidden');
            switchScanTab('manual');
        }

        function closePairingModal() {
            document.getElementById('pairing-modal').classList.add('hidden');
            if (pairingInterval) clearInterval(pairingInterval);
            document.getElementById('pairing-timer-box').classList.add('hidden');
            document.getElementById('btn-start-pairing').classList.remove('hidden');
        }

        function switchScanTab(tab) {
            ['esp32', 'nfc', 'manual'].forEach(t => {
                document.getElementById('tab-content-' + t).classList.add('hidden');
                document.getElementById('tab-btn-' + t).classList.remove('bg-indigo-600', 'text-white');
                document.getElementById('tab-btn-' + t).classList.add('text-slate-400');
            });
            document.getElementById('tab-content-' + tab).classList.remove('hidden');
            document.getElementById('tab-btn-' + tab).classList.add('bg-indigo-600', 'text-white');
            document.getElementById('tab-btn-' + tab).classList.remove('text-slate-400');

            if (tab === 'nfc') {
                checkWebNfcSupport();
            }
        }

        // 1. ESP32 PAIRING LOGIC
        async function startEsp32Pairing() {
            try {
                const res = await fetch("{{ route('portal.pairing.start') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                if (data.status === 'success') {
                    document.getElementById('btn-start-pairing').classList.add('hidden');
                    document.getElementById('pairing-timer-box').classList.remove('hidden');
                    
                    let timeLeft = 90;
                    document.getElementById('pairing-countdown').innerText = timeLeft;

                    if (pairingInterval) clearInterval(pairingInterval);
                    pairingInterval = setInterval(async () => {
                        timeLeft--;
                        document.getElementById('pairing-countdown').innerText = timeLeft;

                        // Poll status
                        const checkRes = await fetch("{{ url('/portal/pairing/check') }}/" + data.token);
                        const checkData = await checkRes.json();

                        if (checkData.status === 'completed') {
                            clearInterval(pairingInterval);
                            alert("🎉 " + checkData.message);
                            window.location.reload();
                        } else if (timeLeft <= 0 || checkData.status === 'expired') {
                            clearInterval(pairingInterval);
                            alert("⏰ Waktu pairing habis. Silakan coba lagi.");
                            closePairingModal();
                        }
                    }, 2000);
                }
            } catch (err) {
                alert("Gagal memulai sesi pairing: " + err.message);
            }
        }

        // 2. WEB NFC SMARTPHONE SCANNER LOGIC
        function checkWebNfcSupport() {
            const statusEl = document.getElementById('nfc-support-status');
            if ('NDEFReader' in window) {
                statusEl.innerText = "✅ Sensor NFC terdeteksi di perangkat Anda!";
                statusEl.className = "text-[11px] font-bold text-emerald-400";
            } else {
                statusEl.innerText = "ℹ️ Browser/HP ini belum mendukung Web NFC. Silakan gunakan metode 'Tap di ESP32' atau 'Ketik Manual'.";
                statusEl.className = "text-[11px] font-bold text-amber-400";
            }
        }

        async function startWebNfcScan() {
            if (!('NDEFReader' in window)) {
                alert("Web NFC tidak didukung di browser ini. Gunakan Chrome di HP Android dengan NFC aktif.");
                return;
            }

            try {
                const ndef = new NDEFReader();
                await ndef.scan();
                document.getElementById('nfc-support-status').innerText = "📡 Tempelkan e-KTP ke belakang HP Anda sekarang...";

                ndef.addEventListener("reading", async ({ serialNumber }) => {
                    const cleanSerial = serialNumber.replace(/:/g, '').toUpperCase();
                    // Kirim ke server
                    const res = await fetch("{{ route('portal.register-rfid') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ rfid_uid: cleanSerial })
                    });
                    const result = await res.json();
                    if (result.status === 'success') {
                        alert("🎉 e-KTP UID [" + cleanSerial + "] Berhasil Didaftarkan!");
                        window.location.reload();
                    } else {
                        alert("⚠️ " + result.message);
                    }
                });
            } catch (err) {
                alert("NFC Error: " + err.message);
            }
        }

        // 3. SIMULATOR BUKA GERBANG MANUAL VISUAL
        function simulateGateOpen(e) {
            const arm = document.getElementById('gate-arm');
            const badge = document.getElementById('gate-badge');
            const led = document.getElementById('gate-led');
            const text = document.getElementById('gate-status-text');

            // Angkat palang gerbang (rotasi -75 derajat)
            arm.style.transform = 'rotate(-75deg)';
            badge.innerText = 'TERBUKA';
            badge.className = 'px-2.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-black uppercase tracking-wider rounded-lg';
            led.className = 'w-3 h-3 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/50';
            text.innerText = 'Palang Gerbang TERANGKAT (Terbuka 5 Detik)';

            // Setelah 5 detik, turunkan kembali palang gerbang
            setTimeout(() => {
                arm.style.transform = 'rotate(0deg)';
                badge.innerText = 'TERTUTUP';
                badge.className = 'px-2.5 py-1 bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-black uppercase tracking-wider rounded-lg';
                led.className = 'w-3 h-3 rounded-full bg-rose-500 shadow-lg shadow-rose-500/50';
                text.innerText = 'Palang Gerbang dalam Posisi Menutup';
            }, 5000);
        }

        // 4. AUTO-POLL STATUS UNTUK LIVE UPDATE
        setInterval(async () => {
            try {
                const res = await fetch("{{ url('/api/iot/portal/status') }}");
                const data = await res.json();
                if (data && data.latest_log) {
                    // Jika ada tap baru yang sukses dalam beberapa detik terakhir, beri efek visual gerbang
                    // ...
                }
            } catch (e) {}
        }, 5000);
    </script>
</x-app-layout>

