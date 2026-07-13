<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-emerald-800 leading-tight uppercase tracking-wide">
                {{ __('Rincian Transaksi Pemasukan Kas RT') }}
            </h2>
            <a href="{{ route('kas.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition duration-300">
                &larr; Kembali ke Buku Kas
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-gradient-to-r from-emerald-800 to-teal-900 rounded-3xl p-7 text-white shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-200">Total Akumulasi Pemasukan</span>
                    <h3 class="text-3xl font-black text-white">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                </div>
                <span class="px-4 py-2 bg-white/10 rounded-2xl text-xs font-black uppercase tracking-wider border border-white/20">Modul Pemasukan</span>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-50/50 text-[10px] font-black text-emerald-800 uppercase tracking-widest border-b border-emerald-100">
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">Keterangan Pemasukan</th>
                                <th class="py-4 px-6 text-right">Nominal (Rp)</th>
                                <th class="py-4 px-6 text-center">Diinput Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($pemasukanList as $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-700 text-xs">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $item->keterangan }}</td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-600 text-xs">+ Rp {{ number_format($item->pemasukan, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-center text-xs font-semibold text-slate-500">{{ $item->user ? $item->user->name : 'Sistem' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada data pemasukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $pemasukanList->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
