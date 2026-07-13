<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Audit & Log Aktivitas Pengguna') }}
            </h2>
            @if(Auth::user()->role == 1)
                <form method="POST" action="{{ route('activity-logs.clear') }}" onsubmit="return confirm('Bersihkan semua riwayat log aktivitas?');">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white font-bold text-xs uppercase tracking-wider rounded-xl transition">
                        Bersihkan History Log
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">Waktu Timestamp</th>
                                <th class="py-4 px-6">Pengguna User</th>
                                <th class="py-4 px-6">Nama Aksi</th>
                                <th class="py-4 px-6">Rincian Deskripsi Audit</th>
                                <th class="py-4 px-6 text-right">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-mono font-bold text-slate-700 text-xs">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">
                                        {{ $log->user ? $log->user->name : 'System / Guest' }}
                                    </td>
                                    <td class="py-4 px-6 font-black text-indigo-700 text-xs uppercase">{{ $log->action }}</td>
                                    <td class="py-4 px-6 text-xs text-slate-600 font-medium">{{ $log->description }}</td>
                                    <td class="py-4 px-6 text-right font-mono text-xs text-slate-400">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada catatan log aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
