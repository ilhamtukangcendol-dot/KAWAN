<div x-data="{ open: false }" @toggle-sidebar.window="open = !open" @close-sidebar.window="open = false" class="relative">
    <!-- Sidebar Backdrop for Mobile -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 z-[45] bg-slate-950/60 backdrop-blur-sm lg:hidden" style="display:none;"></div>

    <nav :class="open ? 'translate-x-0' : '-translate-x-full'" class="flex flex-col h-screen bg-slate-900 border-r border-slate-800 w-72 fixed left-0 top-0 z-50 text-white transition-transform duration-300 lg:translate-x-0">
        <div class="p-6 border-b border-slate-800">
            <div class="flex items-center space-x-3 group">
                <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 3L4 13.5V27.5C4 28.3284 4.67157 29 5.5 29H26.5C27.3284 29 28 28.3284 28 27.5V13.5L16 3Z" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="2"/>
                        <circle cx="16" cy="12" r="4" fill="#fbbf24"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tighter flex items-center gap-1">KAWAN<span class="text-amber-400 text-[9px] font-extrabold px-1.5 py-0.5 bg-amber-400/10 border border-amber-400/20 rounded-md">2026</span></h1>
                    <p class="text-[8px] font-black text-slate-400 tracking-[0.05em] uppercase mt-0.5">Komunikasi & Aplikasi Warga Nyaman</p>
                </div>
            </div>
        </div>

        <div id="sidebar-scroll" class="flex-grow px-3 py-4 overflow-y-auto custom-scrollbar space-y-5">

            <!-- KATEGORI 1: UTAMA & DASHBOARD -->
            <div>
                <p class="px-3 text-[9px] font-black text-indigo-400 uppercase tracking-[0.25em] mb-2">Utama & Dashboard</p>
                <div class="space-y-1 text-xs">
                    @if(Auth::user()->role == 1)
                        <a href="{{ route('superadmin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('superadmin.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>📊 Dashboard Admin</span>
                        </a>
                    @elseif(Auth::user()->role == 2)
                        <a href="{{ route('ketua.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('ketua.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>🏛️ Dashboard Ketua RT</span>
                        </a>
                    @elseif(Auth::user()->role == 3)
                        <a href="{{ route('bendahara.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('bendahara.dashboard') ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>💼 Dashboard Bendahara</span>
                        </a>
                    @elseif(Auth::user()->role == 4)
                        <a href="{{ route('warga.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('warga.dashboard') ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>🏡 Dashboard Warga</span>
                        </a>
                    @endif

                    @if(Auth::user()->role == 1)
                        <a href="{{ route('superadmin.users.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('superadmin.users.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>👥 Manajemen User</span>
                        </a>
                        <a href="{{ route('activity-logs.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('activity-logs.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>📜 Audit Log Aktivitas</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- KATEGORI 2: KEUANGAN & KAS RT -->
            <div>
                <p class="px-3 text-[9px] font-black text-amber-400 uppercase tracking-[0.25em] mb-2">Keuangan & Kas RT</p>
                <div class="space-y-1 text-xs">
                    @if(Auth::user()->role <= 3)
                        <a href="{{ route('kas.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('kas.*') ? 'bg-amber-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>💰 Buku Kas Utama</span>
                        </a>
                        <a href="{{ route('pemasukan.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('pemasukan.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>📈 Pemasukan Kas</span>
                        </a>
                        <a href="{{ route('pengeluaran.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('pengeluaran.*') ? 'bg-rose-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>📉 Pengeluaran Kas</span>
                        </a>
                    @endif

                    <a href="{{ route('iuran.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('iuran.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>💳 {{ Auth::user()->role == 4 ? 'Bayar Iuran Bulanan' : 'Iuran Warga Bulanan' }}</span>
                    </a>

                    @if(Auth::user()->role <= 3)
                        <a href="{{ route('asset.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('asset.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>📦 Asset & Inventaris RT</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- KATEGORI 3: ADMINISTRASI & TATA KELOLA -->
            <div>
                <p class="px-3 text-[9px] font-black text-blue-400 uppercase tracking-[0.25em] mb-2">Administrasi & Pelayanan</p>
                <div class="space-y-1 text-xs">
                    <a href="{{ route('data-warga.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('data-warga.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>👥 Database Warga RT</span>
                    </a>
                    <a href="{{ route('pengurus.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('pengurus.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>👔 Struktur Pengurus RT</span>
                    </a>
                    <a href="{{ route('surat.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('surat.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>✉️ Layanan Surat RT</span>
                    </a>
                    <a href="{{ route('pengumuman.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('pengumuman.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>📢 Pengumuman & Berita</span>
                    </a>
                </div>
            </div>

            <!-- KATEGORI 4: EKONOMI WARGA -->
            <div>
                <p class="px-3 text-[9px] font-black text-emerald-400 uppercase tracking-[0.25em] mb-2">Ekonomi & Lingkungan</p>
                <div class="space-y-1 text-xs">
                    @if(Auth::user()->role != 2)
                        <a href="{{ route('koperasi.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('koperasi.*') ? 'bg-amber-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>🛒 Koperasi Sembako RT</span>
                        </a>
                    @endif
                    @if(Auth::user()->role != 2)
                        <a href="{{ route('bank-sampah.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('bank-sampah.*') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>♻️ Bank Sampah RT</span>
                        </a>
                    @endif
                    <a href="{{ route('umkm.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('umkm.*') ? 'bg-purple-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>🏪 Katalog UMKM Warga</span>
                    </a>
                </div>
            </div>

            <!-- KATEGORI 5: SOSIAL, RONDA & KESEHATAN -->
            <div>
                <p class="px-3 text-[9px] font-black text-teal-400 uppercase tracking-[0.25em] mb-2">Sosial, Ronda & Kesehatan</p>
                <div class="space-y-1 text-xs">
                    <a href="{{ route('posyandu.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('posyandu.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>🏥 Posyandu Balita & Lansia</span>
                    </a>
                    <a href="{{ route('ronda.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('ronda.*') ? 'bg-rose-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>🛡️ Keamanan & Ronda</span>
                    </a>
                    <a href="{{ route('kegiatan.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('kegiatan.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>📅 Agenda Kegiatan RT</span>
                    </a>
                    <a href="{{ route('rukem.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('rukem.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>🤝 Rukem (Belasungkawa)</span>
                    </a>
                    <a href="{{ route('aspirasi.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('aspirasi.*') ? 'bg-amber-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>💡 Kotak Aspirasi Warga</span>
                    </a>
                </div>
            </div>

            <!-- KATEGORI 6: PENGATURAN -->
            <div class="pt-2 border-t border-slate-800">
                <div class="space-y-1 text-xs">
                    <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2.5 rounded-xl font-bold transition {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800' }}">
                        <span>⚙️ Pengaturan Akun & Sistem</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- User Profile Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950 flex items-center justify-between">
            <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 overflow-hidden group">
                @if(Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar)))
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-xl object-cover shrink-0 border border-purple-500/50 shadow group-hover:scale-105 transition">
                @else
                    <div class="w-9 h-9 rounded-xl bg-purple-600 text-white flex items-center justify-center font-black text-xs shrink-0 shadow group-hover:scale-105 transition">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif
                <div class="overflow-hidden">
                    <p class="text-xs font-black truncate text-white uppercase leading-none mb-1 group-hover:text-purple-300 transition">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-purple-400">{{ Auth::user()->role_label }}</p>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-rose-400 hover:text-rose-300 hover:bg-rose-500/20 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </nav>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
</style>