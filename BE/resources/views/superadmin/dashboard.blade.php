<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Control Panel Super Admin System') }}
            </h2>
            <a href="{{ route('superadmin.users.create') }}" class="px-5 py-3 bg-purple-600 hover:bg-purple-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                + Tambah Pengguna Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Hero Banner Admin -->
            <div class="bg-gradient-to-r from-purple-900 via-indigo-950 to-slate-900 rounded-3xl p-8 text-white shadow-2xl space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-purple-500/30 pb-6">
                    <div>
                        <span class="px-3 py-1 bg-purple-500/20 border border-purple-400/30 rounded-full text-purple-200 text-xs font-bold uppercase tracking-widest">
                            Master System Controller
                        </span>
                        <h1 class="text-3xl font-black tracking-tight mt-2">Pusat Kendali Pengguna & Ekosistem SIKAS</h1>
                        <p class="text-purple-200 text-xs font-semibold mt-1">Kelola Seluruh Rute, Pengguna, Audit Log & 19 Modul Sistem</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20 text-right">
                        <span class="text-[10px] font-black uppercase text-purple-200 tracking-wider">Total Akun Terdaftar</span>
                        <h2 class="text-3xl font-black text-amber-400">{{ $totalUsers }} User</h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15">
                        <span class="text-[10px] uppercase font-bold text-purple-200 block">Super Admin</span>
                        <p class="text-2xl font-black text-white mt-1">{{ $totalSuperadmin }} Akun</p>
                        <span class="text-[10px] text-purple-300 font-semibold">Full System Bypass</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15">
                        <span class="text-[10px] uppercase font-bold text-purple-200 block">Ketua RT</span>
                        <p class="text-2xl font-black text-white mt-1">{{ $totalRt }} Akun</p>
                        <span class="text-[10px] text-purple-300 font-semibold">Otoritas RT</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15">
                        <span class="text-[10px] uppercase font-bold text-purple-200 block">Bendahara</span>
                        <p class="text-2xl font-black text-white mt-1">{{ $totalBendahara }} Akun</p>
                        <span class="text-[10px] text-purple-300 font-semibold">Operator Keuangan</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15">
                        <span class="text-[10px] uppercase font-bold text-purple-200 block">Warga RT</span>
                        <p class="text-2xl font-black text-emerald-300 mt-1">{{ $totalWarga }} Akun</p>
                        <span class="text-[10px] text-emerald-400 font-semibold">Pengguna Publik</span>
                    </div>
                </div>
            </div>

            <!-- Master Shortcuts Grid -->
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight mb-4 uppercase">Direct Shortcut Modul Ekosistem</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('superadmin.users.index') }}" class="p-5 bg-white hover:bg-purple-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">👥</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-purple-700 block uppercase">Manajemen User</span>
                    </a>

                    <a href="{{ route('activity-logs.index') }}" class="p-5 bg-white hover:bg-blue-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">📜</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-blue-700 block uppercase">Audit Log Aktivitas</span>
                    </a>

                    <a href="{{ route('settings.index') }}" class="p-5 bg-white hover:bg-emerald-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">⚙️</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-emerald-700 block uppercase">Pengaturan RT</span>
                    </a>

                    <a href="{{ route('kas.index') }}" class="p-5 bg-white hover:bg-amber-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">💰</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-amber-700 block uppercase">Bookkeeping Kas RT</span>
                    </a>
                </div>
            </div>

            <!-- Double Grid Audit Log & Recent Users -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- User Baru Terdaftar -->
                <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-xl space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Pengguna Baru Terdaftar</h3>
                        <a href="{{ route('superadmin.users.index') }}" class="text-xs font-bold text-purple-600 hover:underline">Kelola Pengguna &rarr;</a>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @foreach($recentUsers as $user)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-black text-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-xs">{{ $user->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-semibold">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 bg-purple-50 text-purple-700 font-black text-[10px] rounded-lg uppercase">{{ $user->role_label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Log Aktivitas Audit Terbaru -->
                <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-xl space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Live Stream Audit Log</h3>
                        <a href="{{ route('activity-logs.index') }}" class="text-xs font-bold text-purple-600 hover:underline">Log Lengkap &rarr;</a>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($recentLogs as $log)
                            <div class="py-3 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-purple-700 uppercase">{{ $log->action }}</span>
                                    <span class="text-[10px] font-mono text-slate-400">{{ $log->created_at->format('H:i:s') }}</span>
                                </div>
                                <p class="text-xs text-slate-700 font-semibold leading-snug">{{ $log->description }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 font-bold text-center py-6">Belum ada aktivitas terekam.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
