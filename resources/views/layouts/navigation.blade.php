<nav x-data="{ open: false }" class="flex flex-col h-screen bg-white border-r border-gray-100 w-72 fixed left-0 top-0 z-50">
    <div class="p-8">
        <div class="flex items-center space-x-3 group">
            <div class="w-12 h-12 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[1.2rem] flex items-center justify-center shadow-lg shadow-indigo-200 transition-transform group-hover:rotate-6">
                <!-- SIKAS Premium Logo SVG: A hybrid of a community house and a rising cash/coin dynamic -->
                <svg class="w-7 h-7 text-white" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 3L4 13.5V27.5C4 28.3284 4.67157 29 5.5 29H26.5C27.3284 29 28 28.3284 28 27.5V13.5L16 3Z" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <path d="M12 29V19C12 18.4477 12.4477 18 13 18H19C19.5523 18 20 18.4477 20 19V29" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <circle cx="16" cy="12" r="4.5" fill="#fbbf24" stroke="#d97706" stroke-width="1.5" class="animate-pulse"/>
                    <path d="M16 10V14M14 12H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-blue-900 tracking-tighter leading-none flex items-center gap-0.5">SIKAS<span class="text-amber-500 text-[10px] self-start font-extrabold px-1.5 py-0.5 bg-amber-50 border border-amber-200 rounded-md tracking-normal ml-1">RT</span></h1>
                <p class="text-[8px] font-black text-slate-400 tracking-[0.22em] uppercase mt-1">Sistem Informasi Kas RT</p>
            </div>
        </div>
    </div>

    <div class="flex-grow px-4 overflow-y-auto custom-scrollbar">
        <div class="space-y-1">
            
            @if(Auth::user()->role == 1)
                <p class="px-4 text-[10px] font-black text-gray-300 uppercase tracking-[0.3em] mb-4 text-blue-600">Panel Otoritas RT</p>
                
                <a href="{{ route('ketua.dashboard') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('ketua.dashboard') ? 'bg-blue-600 text-white shadow-xl shadow-blue-100' : 'text-blue-900 hover:bg-blue-50' }}">
                    <span class="text-[11px] font-black uppercase tracking-widest italic">Dashboard Utama</span>
                </a>

                <a href="{{ route('ketua.monitoring') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('ketua.monitoring') ? 'bg-blue-600 text-white shadow-xl shadow-blue-100' : 'text-blue-900 hover:bg-blue-50' }}">
                    <span class="text-[11px] font-black uppercase tracking-widest italic">Monitoring Kas</span>
                </a>

                <a href="{{ route('ketua.audit') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('ketua.audit') ? 'bg-blue-600 text-white shadow-xl shadow-blue-100' : 'text-blue-900 hover:bg-blue-50' }}">
                    <span class="text-[11px] font-black uppercase tracking-widest italic">Audit Keuangan</span>
                </a>

                <a href="{{ route('ketua.payment') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('ketua.payment') ? 'bg-blue-600 text-white shadow-xl shadow-blue-100' : 'text-blue-900 hover:bg-blue-50' }}">
                    <span class="text-[11px] font-black uppercase tracking-widest italic">Pembayaran Warga</span>
                </a>
            @endif

            @if(Auth::user()->role == 2)
    <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-4">Operasional Keuangan</p>
    
    <a href="{{ route('bendahara.dashboard') }}" 
       class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('bendahara.dashboard') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
        </svg>
        <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Ringkasan Kas</span>
    </a>

    <a href="{{ route('bendahara.entri') }}" 
       class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('bendahara.entri') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Catat Transaksi</span>
    </a>

    <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-6 mb-4">Administrasi & Iuran</p>

    <a href="{{ route('bendahara.iuran') }}" 
       class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('bendahara.iuran') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Iuran Warga</span>
    </a>

    <a href="{{ route('bendahara.laporan') }}" 
       class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('bendahara.laporan') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m-9 9h12a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Rekap Laporan</span>
    </a>

    <a href="{{ route('bendahara.inventaris') }}" 
       class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('bendahara.inventaris') || request()->routeIs('bendahara.inventaris.create') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Inventaris Barang</span>
    </a>

    <a href="{{ route('bendahara.persetujuan') }}" 
       class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('bendahara.persetujuan') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Persetujuan Kas</span>
    </a>

    <a href="{{ route('warga.index') }}" 
       class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('warga.index') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Daftar Warga</span>
    </a>
@endif

            @if(Auth::user()->role == 1)
                <p class="px-4 text-[10px] font-black text-gray-300 uppercase tracking-[0.3em] mt-8 mb-4">Data Lingkungan</p>
                <a href="{{ route('warga.index') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('warga.index') ? 'bg-slate-800 text-white shadow-xl' : 'text-blue-900 hover:bg-slate-50' }}">
                    <span class="text-[11px] font-black uppercase tracking-widest italic">Database Warga</span>
                </a>
            @endif

            @if(Auth::user()->role == 3)
                <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-8 mb-4">Akses Warga</p>
                
                <a href="{{ route('warga.dashboard') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('warga.dashboard') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Dashboard Warga</span>
                </a>

                <a href="{{ route('warga.payment') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('warga.payment') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Bayar Kas RT</span>
                </a>

                <a href="{{ route('warga.payment_methods') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('warga.payment_methods') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Metode Pembayaran</span>
                </a>

                <a href="{{ route('warga.history') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('warga.history') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Riwayat Setoran Saya</span>
                </a>

                <a href="{{ route('warga.index') }}" 
                   class="flex items-center px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('warga.index') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'text-blue-900 hover:bg-indigo-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="ml-4 text-[11px] font-black uppercase tracking-widest">Daftar Warga RT</span>
                </a>
            @endif
        </div>
    </div>

    <div class="p-6 mt-auto border-t border-gray-50 bg-gray-50/50">
        <a href="{{ route('profile.edit') }}" class="flex items-center mb-6 px-2 hover:bg-slate-100/80 p-2 rounded-2xl transition duration-300 group">
            <div class="w-10 h-10 rounded-xl overflow-hidden border border-slate-100 shadow-inner flex items-center justify-center font-black text-xs shrink-0">
                @if(Auth::user()->avatar)
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full {{ Auth::user()->role == 1 ? 'bg-blue-100 text-blue-600' : (Auth::user()->role == 2 ? 'bg-orange-100 text-orange-600' : 'bg-indigo-100 text-indigo-600') }} flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif
            </div>
            <div class="ml-3 overflow-hidden">
                <p class="text-[11px] font-black text-blue-900 truncate uppercase leading-none mb-1 group-hover:text-blue-600 transition-colors">{{ Auth::user()->name }}</p>
                <p class="text-[9px] font-bold {{ Auth::user()->role == 1 ? 'text-blue-400' : (Auth::user()->role == 2 ? 'text-orange-500' : 'text-indigo-500') }} uppercase tracking-tighter">
                    {{ Auth::user()->role == 1 ? 'Ketua RT Aktif' : (Auth::user()->role == 2 ? 'Otoritas Bendahara' : 'Warga RT') }}
                </p>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="w-full flex items-center justify-center space-x-3 px-6 py-4 bg-red-50 text-red-600 rounded-[1.5rem] hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="text-[10px] font-black uppercase tracking-widest">Logout</span>
            </button>
        </form>
    </div>
</nav>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f1f1; border-radius: 10px; }
</style>