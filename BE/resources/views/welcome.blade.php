<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KAWAN 2026 - Portal Layanan & Rembuk RT 01 / RW 05</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light-theme');
        } else {
            document.documentElement.classList.remove('light-theme');
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes morph {
            0% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; transform: rotate(0deg) scale(1); }
            33% { border-radius: 70% 30% 52% 48% / 60% 40% 60% 40%; transform: rotate(120deg) scale(1.1); }
            66% { border-radius: 50% 50% 30% 70% / 40% 60% 40% 60%; transform: rotate(240deg) scale(0.95); }
            100% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; transform: rotate(360deg) scale(1); }
        }
        @keyframes morphReverse {
            0% { border-radius: 70% 30% 52% 48% / 60% 40% 60% 40%; transform: rotate(360deg) scale(1); }
            50% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; transform: rotate(180deg) scale(1.15); }
            100% { border-radius: 70% 30% 52% 48% / 60% 40% 60% 40%; transform: rotate(0deg) scale(1); }
        }
        @keyframes gridMove {
            from { background-position: 0 0; }
            to { background-position: 32px 32px; }
        }
        .glow-blob {
            position: absolute;
            filter: blur(100px);
            opacity: 0.25;
            pointer-events: none;
            z-index: 0;
            mix-blend-mode: screen;
            transition: background 1s ease, opacity 1s ease;
        }
        .glow-blob-rose {
            background: radial-gradient(circle, #ff007f 0%, transparent 80%);
            animation: morph 20s ease-in-out infinite alternate;
        }
        .glow-blob-amber {
            background: radial-gradient(circle, #7f00ff 0%, transparent 80%);
            animation: morphReverse 24s ease-in-out infinite alternate;
        }
        .glow-blob-emerald {
            background: radial-gradient(circle, #00ffff 0%, transparent 80%);
            animation: morph 28s ease-in-out infinite alternate;
        }
        .glow-blob-indigo {
            background: radial-gradient(circle, #39ff14 0%, transparent 80%);
            animation: morphReverse 22s ease-in-out infinite alternate;
        }
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(244, 63, 94, 0.05) 1.5px, transparent 1.5px);
            background-size: 32px 32px;
            animation: gridMove 10s linear infinite;
        }
        
        /* ================= LIGHT THEME OVERRIDES ================= */
        html.light-theme body {
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }
        html.light-theme .bg-grid-pattern {
            background-image: radial-gradient(rgba(15, 23, 42, 0.05) 1.5px, transparent 1.5px) !important;
        }
        html.light-theme .glow-blob {
            mix-blend-mode: multiply !important;
            opacity: 0.22 !important;
            filter: blur(110px) !important;
        }
        html.light-theme .glow-blob-rose {
            background: radial-gradient(circle, #ff9a9e 0%, transparent 80%) !important;
        }
        html.light-theme .glow-blob-amber {
            background: radial-gradient(circle, #a1c4fd 0%, transparent 80%) !important;
        }
        html.light-theme .glow-blob-emerald {
            background: radial-gradient(circle, #fecfef 0%, transparent 80%) !important;
        }
        html.light-theme .glow-blob-indigo {
            background: radial-gradient(circle, #fbc2eb 0%, transparent 80%) !important;
        }
        html.light-theme nav {
            background-color: rgba(248, 250, 252, 0.9) !important;
            border-color: #cbd5e1 !important;
        }
        html.light-theme nav h1, html.light-theme nav a {
            color: #0f172a !important;
        }
        html.light-theme nav a:hover {
            color: #e11d48 !important;
        }
        html.light-theme header#hero {
            background: linear-gradient(to bottom, #f1f5f9, #f8fafc) !important;
            border-color: #e2e8f0 !important;
        }
        html.light-theme header#hero h1 {
            color: #0f172a !important;
        }
        html.light-theme header#hero p {
            color: #334155 !important;
        }
        html.light-theme header#hero .border-t {
            border-color: #cbd5e1 !important;
        }
        html.light-theme section {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
        }
        html.light-theme section#umkm-kategori {
            background-color: #f1f5f9 !important;
        }
        html.light-theme section h2, html.light-theme section h3, html.light-theme section h4 {
            color: #0f172a !important;
        }
        html.light-theme section p {
            color: #475569 !important;
        }
        html.light-theme .bg-slate-900 {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
        }
        html.light-theme .border-slate-800, html.light-theme .border-slate-800\/80 {
            border-color: #cbd5e1 !important;
        }
        html.light-theme .text-slate-300 {
            color: #334155 !important;
        }
        html.light-theme .text-slate-400 {
            color: #475569 !important;
        }
        html.light-theme .text-slate-500 {
            color: #64748b !important;
        }
        html.light-theme .text-white {
            color: #0f172a !important;
        }
        html.light-theme a.bg-slate-900 {
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        html.light-theme a.bg-slate-900:hover {
            background-color: #e2e8f0 !important;
        }
        html.light-theme footer {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #64748b !important;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased selection:bg-rose-600 selection:text-white bg-grid-pattern relative min-h-screen overflow-x-hidden" x-data="{ 
    activeCategory: 'semua',
    theme: localStorage.getItem('theme') || 'dark',
    init() {
        this.applyTheme();
    },
    toggleTheme() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', this.theme);
        this.applyTheme();
    },
    applyTheme() {
        if (this.theme === 'light') {
            document.documentElement.classList.add('light-theme');
        } else {
            document.documentElement.classList.remove('light-theme');
        }
    }
}">

    <!-- Global Ambient Glow Blobs distributed down the long page -->
    <div class="absolute top-[8%] left-[-12%] w-[600px] h-[600px] glow-blob glow-blob-rose"></div>
    <div class="absolute top-[22%] right-[-10%] w-[700px] h-[700px] glow-blob glow-blob-amber"></div>
    <div class="absolute top-[42%] left-[-15%] w-[700px] h-[700px] glow-blob glow-blob-emerald"></div>
    <div class="absolute top-[65%] right-[-15%] w-[650px] h-[650px] glow-blob glow-blob-indigo"></div>
    <div class="absolute top-[85%] left-[-12%] w-[600px] h-[600px] glow-blob glow-blob-rose"></div>

    <!-- NAVBAR INDONESIAN VIBE WITH SHORTCUTS -->
    <nav class="sticky top-0 z-50 backdrop-blur-md bg-slate-950/90 border-b border-slate-800/80 py-3.5 px-6 md:px-12 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-tr from-rose-600 to-red-700 rounded-2xl flex items-center justify-center shadow-lg shadow-rose-600/30">
                <span class="text-xl">🇮🇩</span>
            </div>
            <div>
                <h1 class="text-xl font-black tracking-tighter text-white flex items-center gap-1.5">
                    KAWAN<span class="text-rose-400 text-[9px] font-black px-1.5 py-0.5 bg-rose-500/10 border border-rose-500/30 rounded-md">RT.01/RW.05</span>
                </h1>
                <p class="text-[8px] font-black text-slate-400 tracking-[0.05em] uppercase">Komunikasi & Aplikasi Warga Nyaman</p>
            </div>
        </div>

        <!-- NAVIGATION SHORTCUT MENU LINKS -->
        <div class="hidden lg:flex items-center space-x-6 text-xs font-bold text-slate-300">
            <a href="#hero" class="hover:text-rose-400 transition">🏠 Beranda</a>
            <a href="#tentang" class="hover:text-rose-400 transition">🏛️ Tentang RT</a>
            <a href="#umkm-kategori" class="hover:text-rose-400 transition">🏪 Katalog UMKM (10)</a>
            <a href="#pengumuman" class="hover:text-rose-400 transition">📢 Pengumuman</a>
            <a href="#program-wilayah" class="hover:text-rose-400 transition">🌟 Program RT</a>
            <a href="#kontak" class="hover:text-rose-400 transition">📞 Kontak</a>
        </div>

        <div class="flex items-center gap-3">
            <!-- Theme Toggle Button -->
            <button @click="toggleTheme()" class="p-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white transition border border-slate-800 shrink-0 flex items-center justify-center" aria-label="Toggle Theme">
                <!-- Sun Icon (shown in dark mode) -->
                <svg x-show="theme === 'dark'" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21M4.93 4.93l1.59 1.59m10.96 10.96l1.59 1.59M3 12h2.25m13.5 0H21m-16.07 6.07l1.59-1.59M16.24 6.24l1.59-1.59M12 7.5a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Z" />
                </svg>
                <!-- Moon Icon (shown in light mode) -->
                <svg x-show="theme === 'light'" class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                </svg>
            </button>
            @if (Route::has('login'))
                @auth
                    <a href="{{ Auth::user()->role == 1 ? route('superadmin.dashboard') : (Auth::user()->role == 2 ? route('ketua.dashboard') : (Auth::user()->role == 3 ? route('bendahara.dashboard') : route('warga.dashboard'))) }}" class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-500 hover:to-red-600 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-rose-600/25 transition">
                        🏛️ Buka Dashboard Saya
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-2xl transition border border-slate-800">
                        Masuk Warga
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-500 hover:to-red-600 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-rose-600/25 transition">
                            Daftar Akun
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- HERO SECTION WITH INDONESIAN GOTONG ROYONG ARTWORK -->
    <header id="hero" class="relative overflow-hidden py-16 px-6 md:px-12 border-b border-slate-900 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-rose-500/10 border border-rose-500/30 rounded-full text-rose-300 text-xs font-black uppercase tracking-widest">
                    <span>🇲🇨</span> Rembuk & Layanan RT 01 Komp. Mawar Asri
                </div>
                
                <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white leading-tight">
                    Aplikasi Digital dan Pelayanan Warga
                </h1>
                
                <p class="text-slate-300 text-sm md:text-base font-semibold leading-relaxed">
                    Sistem terpadu yang dirancang khusus untuk mempermudah warga dalam mengakses layanan administrasi RT, memantau informasi lingkungan terkini, hingga mendukung pertumbuhan ekosistem UMKM lokal. Nikmati kemudahan akses satu pintu yang lebih modern dan transparan dalam genggaman Anda.
                </p>

                <!-- STATS METRICS INDONESIA -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-800/80">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Warga Terdaftar</span>
                        <h4 class="text-xl md:text-2xl font-black text-rose-400">{{ $totalWarga }} Jiwa</h4>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Kartu Keluarga (KK)</span>
                        <h4 class="text-xl md:text-2xl font-black text-emerald-400">{{ $totalKK }} KK</h4>
                    </div>
                </div>
            </div>

            <!-- INDONESIAN ARTWORK SHOWCASE -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-rose-600 to-amber-500 rounded-3xl blur-xl opacity-35 group-hover:opacity-60 transition duration-500"></div>
                <img src="{{ asset('images/indo_gotong_royong.png') }}" alt="Gotong Royong Warga RT Indonesia" class="relative rounded-3xl shadow-2xl border border-slate-800 w-full object-cover">
            </div>
        </div>
    </header>

    <!-- SECTION 1: TENTANG RT & PENGURUS RT INTI -->
    <section id="tentang" class="py-16 px-6 md:px-12 bg-slate-950 border-b border-slate-900">
        <div class="max-w-7xl mx-auto space-y-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="space-y-4">
                    <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Profil & Identitas Lingkungan</span>
                    <h2 class="text-3xl font-black text-white">Tentang {{ $settings['nama_rt'] }}</h2>
                    <p class="text-slate-300 text-xs font-semibold leading-relaxed">
                        Kami adalah rukun tetangga yang berkomitmen pada kerukunan warga, kebersihan lingkungan, serta kemudahan pelayanan warga. Berdiri di atas nilai-nilai kekeluargaan Gotong Royong Indonesia.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div class="p-4 bg-slate-900 rounded-2xl border border-slate-800">
                            <span class="text-[9px] font-black text-slate-400 uppercase block mb-1">Standard Iuran Warga</span>
                            <h4 class="text-lg font-black text-amber-400">Rp {{ number_format($settings['nominal_iuran'], 0, ',', '.') }} / Bln</h4>
                        </div>
                        <div class="p-4 bg-slate-900 rounded-2xl border border-slate-800">
                            <span class="text-[9px] font-black text-slate-400 uppercase block mb-1">Santunan Duka Rukem</span>
                            <h4 class="text-lg font-black text-rose-400">Rp {{ number_format($settings['nominal_rukem'], 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>

                <!-- DAFTAR PENGURUS INTI -->
                <div class="space-y-4">
                    <h3 class="font-black text-white text-sm uppercase tracking-wider">👔 Jajaran Pengurus RT Periode Aktif</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($pengurusList as $p)
                            <div class="p-4 bg-slate-900 rounded-2xl border border-slate-800 flex items-center space-x-3">
                                <div class="w-10 h-10 bg-rose-600/20 text-rose-400 rounded-xl flex items-center justify-center font-black text-sm border border-rose-500/30">
                                    {{ strtoupper(substr($p->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-xs">{{ $p->nama }}</h4>
                                    <p class="text-[10px] font-black text-amber-400 uppercase">{{ $p->jabatan }}</p>
                                    <p class="text-[9px] text-slate-400 font-medium">📞 {{ $p->no_hp }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 p-4 bg-slate-900 text-slate-500 text-xs font-bold rounded-2xl">Pengurus RT belum diinput.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: ETALASE WARUNG & 10 UMKM WARGA DENGAN GAMBAR KHUSUS -->
    <section id="umkm-kategori" class="py-16 px-6 md:px-12 border-b border-slate-900 bg-slate-900/40">
        <div class="max-w-7xl mx-auto space-y-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div class="space-y-2">
                    <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">Beli & Dukung Tetangga Sendiri</span>
                    <h2 class="text-3xl font-black text-white">Etalase 10 Usaha & UMKM Warga RT</h2>
                    <p class="text-slate-300 text-xs font-semibold">Daftar produk kuliner, jasa, sembako, dan perkakas tetangga sekitar kita.</p>
                </div>
            </div>

            <!-- TAB PENGELOMPOKAN KATEGORI UMKM (INTERAKTIF ALPINE JS) -->
            <div class="flex flex-wrap gap-2 justify-center bg-slate-900 p-2 rounded-2xl border border-slate-800">
                <button @click="activeCategory = 'semua'" :class="activeCategory === 'semua' ? 'bg-rose-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition">
                    🛒 Semua (10 Usaha)
                </button>
                @foreach($umkmKategori as $kat)
                    <button @click="activeCategory = '{{ strtolower($kat) }}'" :class="activeCategory === '{{ strtolower($kat) }}' ? 'bg-rose-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition">
                        🏷️ {{ $kat }}
                    </button>
                @endforeach
            </div>

            <!-- GRID KATALOG 10 UMKM DENGAN FOTO MASING-MASING -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($umkmList as $u)
                    <div x-show="activeCategory === 'semua' || activeCategory === '{{ strtolower($u->kategori) }}'" x-transition class="bg-slate-900 p-5 rounded-3xl border border-slate-800 shadow-xl space-y-4 flex flex-col justify-between hover:border-rose-500/40 transition group">
                        <div class="space-y-3">
                            <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-950 relative h-40">
                                <img src="{{ asset($u->foto ? $u->foto : 'images/indo_pasar_umkm.png') }}" alt="{{ $u->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                <span class="absolute top-2 right-2 px-2.5 py-0.5 bg-slate-950/80 backdrop-blur-sm text-amber-400 font-black text-[9px] uppercase rounded-lg border border-slate-800">{{ $u->kategori }}</span>
                            </div>
                            <div>
                                <h3 class="font-black text-white text-base leading-tight">{{ $u->nama_usaha }}</h3>
                                <p class="text-[11px] font-bold text-slate-400 mt-1">👤 {{ $u->pemilik }} &bull; 📍 {{ $u->alamat }}</p>
                                <p class="text-xs text-slate-300 font-medium mt-2 line-clamp-3 leading-relaxed">{{ $u->deskripsi }}</p>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-800/80">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $u->no_whatsapp) }}" target="_blank" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow flex items-center justify-center gap-2 transition">
                                💬 Pesan via WhatsApp
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 bg-slate-900 p-8 text-center rounded-3xl border border-slate-800 text-slate-500 font-bold text-xs">
                        Belum ada usaha UMKM terdaftar di lingkungan RT.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 3: LIVE FEEDS INFORMASI & KEGIATAN LINGKUNGAN -->
    <section id="pengumuman" class="py-16 px-6 md:px-12 bg-slate-950 border-b border-slate-900">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10">
            
            <!-- Pengumuman RT -->
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <h3 class="font-black text-white text-lg uppercase tracking-wide flex items-center gap-2">
                        📢 Pengumuman Pengurus RT
                    </h3>
                </div>

                <div class="space-y-4">
                    @forelse($pengumumanList as $p)
                        <div class="p-5 bg-slate-900 rounded-3xl border border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 bg-rose-500/10 text-rose-400 font-black text-[10px] uppercase rounded-md border border-rose-500/20">{{ $p->kategori }}</span>
                                <span class="text-[10px] font-semibold text-slate-500">{{ $p->created_at->format('d M Y') }}</span>
                            </div>
                            <h4 class="font-bold text-white text-base leading-tight">{{ $p->judul }}</h4>
                            <p class="text-xs text-slate-400 font-medium leading-relaxed">{{ $p->isi }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 font-bold text-center py-6">Belum ada pengumuman penerbitan RT.</p>
                    @endforelse
                </div>
            </div>

            <!-- Agenda Kerja Bakti & Kegiatan -->
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <h3 class="font-black text-white text-lg uppercase tracking-wide flex items-center gap-2">
                        📅 Agenda Kerja Bakti & Event
                    </h3>
                </div>

                <div class="space-y-4">
                    @forelse($kegiatanList as $k)
                        <div class="p-5 bg-slate-900 rounded-3xl border border-slate-800 flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="px-2.5 py-0.5 bg-indigo-500/10 text-indigo-400 font-black text-[10px] uppercase rounded-md border border-indigo-500/20">{{ $k->kategori }}</span>
                                <h4 class="font-bold text-white text-base leading-tight">{{ $k->nama_kegiatan }}</h4>
                                <p class="text-xs text-slate-400 font-medium">📍 {{ $k->lokasi }} &bull; 🕒 {{ $k->waktu }}</p>
                            </div>
                            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-2xl font-black text-xs text-center shrink-0">
                                {{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 font-bold text-center py-6">Belum ada agenda kegiatan terdekat.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 4: PROGRAM KERJA UNGGULAN WILAYAH RT -->
    <section id="program-wilayah" class="py-16 px-6 md:px-12 bg-slate-900/60 border-b border-slate-900">
        <div class="max-w-7xl mx-auto space-y-10">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Pemberdayaan Lingkungan</span>
                <h2 class="text-3xl font-black text-white">Program Unggulan Wilayah RT 01</h2>
                <p class="text-slate-400 text-xs font-semibold">Empat pilar utama program kemasyarakatan yang berjalan aktif di lingkungan kami.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="p-6 bg-slate-900 rounded-3xl border border-slate-800 space-y-3">
                    <span class="text-3xl">♻️</span>
                    <h3 class="font-black text-white text-base">Bank Sampah Zero Waste</h3>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Pengumpulan dan pemilahan sampah anorganik warga menjadi nilai rupiah saldo kas.</p>
                </div>

                <div class="p-6 bg-slate-900 rounded-3xl border border-slate-800 space-y-3">
                    <span class="text-3xl">🛡️</span>
                    <h3 class="font-black text-white text-base">Siskamling & Pos Ronda</h3>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Piket ronda malam bergilir 4 regu menjaga keamanan lingkungan 24 jam nonstop.</p>
                </div>

                <div class="p-6 bg-slate-900 rounded-3xl border border-slate-800 space-y-3">
                    <span class="text-3xl">🏥</span>
                    <h3 class="font-black text-white text-base">Posyandu Balita & Lansia</h3>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Pemeriksaan tumbuh kembang balita, imunisasi, serta cek kesehatan lansia berkala.</p>
                </div>

                <div class="p-6 bg-slate-900 rounded-3xl border border-slate-800 space-y-3">
                    <span class="text-3xl">🛒</span>
                    <h3 class="font-black text-white text-base">Koperasi Sembako Murah</h3>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Penyediaan beras, minyak goreng, dan sembako harga terjangkau bagi warga RT.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: KONTAK & HOTLINE SEKRETARIAT RT -->
    <section id="kontak" class="py-16 px-6 md:px-12 bg-slate-950">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="space-y-4">
                <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Sekretariat Pengurus</span>
                <h2 class="text-3xl font-black text-white">Hubungi Sekretariat RT 01</h2>
                <p class="text-slate-300 text-xs font-semibold leading-relaxed">
                    Membutuhkan pelayanan surat darurat, informasi kas, atau melaporkan kejadian di lingkungan? Hubungi hotline resmi di bawah ini.
                </p>
                <div class="space-y-2 pt-2 text-xs font-bold text-slate-300">
                    <p>📍 <span class="text-white">Alamat Sekretariat:</span> {{ $settings['alamat_rt'] }}</p>
                    <p>📞 <span class="text-white">Hotline Pengurus:</span> {{ $settings['kontak_rt'] }}</p>
                    <p>🚨 <span class="text-white">Pos Ronda Emergency:</span> 0812-9900-1122</p>
                </div>
            </div>

            <div class="p-8 bg-slate-900 rounded-3xl border border-slate-800 space-y-4 text-center">
                <span class="text-4xl">🇮🇩</span>
                <h3 class="font-black text-white text-lg">Mari Bersama Membangun RT 01</h3>
                <p class="text-xs text-slate-400 font-medium">Bagi warga yang ingin mendaftar akun atau mengakses layanan online, silakan masuk ke portal warga.</p>
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    Portal Masuk Warga
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER PUBLIC -->
    <footer class="py-8 px-6 md:px-12 text-center text-slate-500 text-xs font-semibold border-t border-slate-900 bg-slate-950">
        <p>&copy; {{ date('Y') }} KAWAN {{ $settings['nama_rt'] }}. Komunikasi & Aplikasi Warga Nyaman Berbasis Gotong Royong 🇮🇩.</p>
    </footer>

</body>
</html>