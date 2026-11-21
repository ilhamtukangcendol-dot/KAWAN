<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('portal.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1 mb-2">
                    &larr; Kembali ke Dashboard Portal
                </a>
                <h1 class="text-2xl font-black text-white tracking-tight flex items-center gap-2">
                    <span>📜 Riwayat Lengkap Akses Gerbang (Audit Log)</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Seluruh rekaman aktivitas tap e-KTP dan perintah buka gerbang masuk/keluar perumahan.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('portal.logs', ['status' => 'granted']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') == 'granted' ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-emerald-300 border border-emerald-500/30' }}">
                    🟢 Diterima
                </a>
                <a href="{{ route('portal.logs', ['status' => 'denied']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') == 'denied' ? 'bg-rose-600 text-white' : 'bg-slate-800 text-rose-300 border border-rose-500/30' }}">
                    🚫 Ditolak
                </a>
                <a href="{{ route('portal.logs') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ !request('status') ? 'bg-cyan-600 text-white' : 'bg-slate-800 text-slate-300' }}">
                    Semua
                </a>
            </div>
        </div>

        <!-- SEARCH FORM -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
            <form method="GET" action="{{ route('portal.logs') }}" class="flex flex-col sm:flex-row gap-3">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama warga, UID e-KTP, atau keterangan..." class="flex-1 px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:ring-1 focus:ring-cyan-500">
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition">
                    Filter Riwayat
                </button>
            </form>
        </div>

        <!-- TABLE LOGS -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead>
                        <tr class="bg-slate-950 border-b border-slate-800 text-[10px] font-black uppercase text-slate-400">
                            <th class="py-3 px-4">Waktu & Tanggal</th>
                            <th class="py-3 px-4">Identitas Warga</th>
                            <th class="py-3 px-4">UID e-KTP</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Tipe Akses</th>
                            <th class="py-3 px-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-mono font-bold text-white">{{ $log->created_at->format('H:i:s') }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $log->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-white">{{ $log->user_name ?? ($log->user ? $log->user->name : 'Tidak Dikenal') }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $log->user ? $log->user->role_label : 'Non-Member' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-black text-cyan-300 px-2 py-0.5 bg-slate-950 rounded border border-cyan-500/30 text-[11px]">
                                        {{ $log->rfid_uid }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($log->status == 'granted')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center gap-1 w-max">
                                            <span>✓</span> Akses Diterima
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-rose-500/20 text-rose-300 border border-rose-500/40 flex items-center gap-1 w-max">
                                            <span>✕</span> Akses Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">
                                        {{ str_replace('_', ' ', $log->access_type) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="text-slate-300 text-[11px]">{{ $log->keterangan }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500">
                                    Belum ada catatan riwayat akses gerbang yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800">
                {{ $logs->links() }}
            </div>
        </div>

    </div>
</x-app-layout>

