<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
            {{ __('Dashboard Utama Warga RT') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Hero Welcome Card for Warga -->
            <div class="relative overflow-hidden bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 rounded-3xl shadow-2xl p-8 text-white">
                <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                    <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                </div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 border border-emerald-400/30 rounded-full text-emerald-200 text-xs font-bold uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            Portal Resmi Warga RT 01
                        </div>
                        <h1 class="text-3xl font-black tracking-tight">Selamat Datang, {{ Auth::user()->name }} 👋</h1>
                        <p class="text-slate-300 text-sm max-w-xl font-medium leading-relaxed">
                            Akses layanan iuran kas, permohonan surat RT, belanja kebutuhan koperasi, katalog UMKM warga, dan pantau pengumuman resmi lingkungan RT Anda secara real-time.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('iuran.index') }}" class="px-6 py-3.5 bg-amber-400 hover:bg-amber-300 text-slate-900 font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl hover:scale-105 transition duration-300">
                            💳 Bayar Iuran Bulanan
                        </a>
                        <a href="{{ route('surat.index') }}" class="px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white font-black text-xs uppercase tracking-widest rounded-2xl border border-white/20 transition duration-300">
                            ✉️ Ajukan Surat RT
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid Warga -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Stat 1: Kas RT Transparan -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Saldo Kas RT Transparan</span>
                        <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-slate-400 font-bold border-t border-slate-50 pt-3">
                        <span>Laporan Keuangan</span>
                        <span class="text-emerald-600">Terbuka & Akuntabel</span>
                    </div>
                </div>

                <!-- Stat 2: Status Iuran Saya -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Iuran Lunas ({{ date('Y') }})</span>
                        <h3 class="text-2xl font-black text-indigo-600">{{ $myIuranCount }} Bulan</h3>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-slate-400 font-bold border-t border-slate-50 pt-3">
                        <span>Status</span>
                        <span class="text-indigo-600 font-bold">Terdaftar RT</span>
                    </div>
                </div>

                <!-- Stat 3: Surat Pending -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Pengajuan Surat Diproses</span>
                        <h3 class="text-2xl font-black text-amber-500">{{ $suratPendingCount }} Dokumen</h3>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-slate-400 font-bold border-t border-slate-50 pt-3">
                        <span>Status Surat</span>
                        <a href="{{ route('surat.index') }}" class="text-amber-600 hover:underline">Cek Status &rarr;</a>
                    </div>
                </div>

                <!-- Stat 4: Hotbutton Pengaduan -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-6 rounded-3xl text-white shadow-xl shadow-amber-500/20 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-black text-amber-100 uppercase tracking-widest block mb-1">Kotak Aspirasi & Aduan</span>
                        <h3 class="text-lg font-black leading-tight">Sampaikan Kendala Lingkungan</h3>
                    </div>
                    <a href="{{ route('aspirasi.index') }}" class="mt-4 py-2 px-4 bg-white text-slate-900 text-center font-black text-xs uppercase tracking-wider rounded-xl shadow hover:bg-amber-100 transition">
                        Kirim Aduan &rarr;
                    </a>
                </div>
            </div>

            <!-- Quick Action Hub (Menu Cepat Warga) -->
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight mb-4 uppercase">Akses Layanan Cepat Warga</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <a href="{{ route('iuran.index') }}" class="p-5 bg-white hover:bg-emerald-50/50 border border-slate-100 rounded-3xl shadow-lg shadow-slate-100/40 text-center group transition duration-300">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">💳</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-emerald-700 block uppercase">Bayar Iuran</span>
                    </a>

                    <a href="{{ route('surat.index') }}" class="p-5 bg-white hover:bg-blue-50/50 border border-slate-100 rounded-3xl shadow-lg shadow-slate-100/40 text-center group transition duration-300">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">✉️</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-blue-700 block uppercase">Surat RT</span>
                    </a>

                    <a href="{{ route('koperasi.index') }}" class="p-5 bg-white hover:bg-amber-50/50 border border-slate-100 rounded-3xl shadow-lg shadow-slate-100/40 text-center group transition duration-300">
                        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">🛒</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-amber-700 block uppercase">Koperasi RT</span>
                    </a>

                    <a href="{{ route('bank-sampah.index') }}" class="p-5 bg-white hover:bg-teal-50/50 border border-slate-100 rounded-3xl shadow-lg shadow-slate-100/40 text-center group transition duration-300">
                        <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">♻️</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-teal-700 block uppercase">Bank Sampah</span>
                    </a>

                    <a href="{{ route('umkm.index') }}" class="p-5 bg-white hover:bg-purple-50/50 border border-slate-100 rounded-3xl shadow-lg shadow-slate-100/40 text-center group transition duration-300">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">🏪</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-purple-700 block uppercase">UMKM Warga</span>
                    </a>

                    <a href="{{ route('ronda.index') }}" class="p-5 bg-white hover:bg-rose-50/50 border border-slate-100 rounded-3xl shadow-lg shadow-slate-100/40 text-center group transition duration-300">
                        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">🛡️</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-rose-700 block uppercase">Ronda Malam</span>
                    </a>
                </div>
            </div>

            <!-- Feed Pengumuman & Agenda Terbaru -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Pengumuman Resmi RT -->
                <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-xl shadow-slate-100/40 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="font-black text-slate-800 text-base uppercase tracking-wider flex items-center gap-2">
                            📢 Pengumuman Pengurus RT
                        </h3>
                        <a href="{{ route('pengumuman.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua &rarr;</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($pengumumanList as $p)
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-emerald-700 uppercase">{{ $p->kategori }}</span>
                                    <span class="text-[10px] font-semibold text-slate-400">{{ $p->created_at->format('d M Y') }}</span>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm leading-tight">{{ $p->judul }}</h4>
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $p->isi }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 font-bold text-center py-6">Belum ada pengumuman penerbitan RT.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Agenda Kegiatan Warga -->
                <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-xl shadow-slate-100/40 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="font-black text-slate-800 text-base uppercase tracking-wider flex items-center gap-2">
                            📅 Agenda Kegiatan Terdekat
                        </h3>
                        <a href="{{ route('kegiatan.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua &rarr;</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($kegiatanList as $k)
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-black text-indigo-700 uppercase">{{ $k->kategori }}</span>
                                    <h4 class="font-bold text-slate-800 text-sm leading-tight">{{ $k->nama_kegiatan }}</h4>
                                    <p class="text-xs text-slate-500 font-semibold mt-1">📍 {{ $k->lokasi }} &bull; 🕒 {{ $k->waktu }}</p>
                                </div>
                                <div class="px-3 py-2 bg-emerald-100 text-emerald-800 rounded-xl font-black text-xs text-center shrink-0">
                                    {{ \Carbon\Carbon::parse($k->tanggal)->format('d M') }}
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 font-bold text-center py-6">Belum ada agenda kegiatan terdekat.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>