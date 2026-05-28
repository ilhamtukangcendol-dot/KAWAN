<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-blue-900 leading-tight">
                📜 Riwayat Setoran Saya
            </h2>
            <span class="bg-indigo-100 text-indigo-700 px-4 py-1 rounded-full text-xs font-bold uppercase">Laporan Pribadi</span>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-12 space-y-8">
        <!-- Transparent Info Card -->
        <div>
            <h3 class="text-lg font-black text-slate-800">Log Setoran Iuran Anda</h3>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Daftar transaksi kas RT yang diinputkan secara mandiri oleh akun Anda</p>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-blue-50 p-6 md:p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-blue-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="pb-4">Detail Setoran</th>
                            <th class="pb-4">Tanggal Pembukuan</th>
                            <th class="pb-4 text-right">Nominal Setoran</th>
                            <th class="pb-4 text-right">Status Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50 text-sm">
                        @forelse($riwayatSetoran as $item)
                        <tr class="group hover:bg-slate-50/50 transition">
                            <td class="py-4">
                                <div class="font-extrabold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $item->keterangan }}</div>
                            </td>
                            <td class="py-4 text-xs text-slate-400 font-semibold">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </td>
                            <td class="py-4 text-right font-black text-sm text-slate-800">
                                + Rp {{ number_format($item->nominal) }}
                            </td>
                            <td class="py-4 text-right">
                                @if($item->status == 'pending')
                                    <span class="inline-block text-[9px] font-black uppercase px-2.5 py-0.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg animate-pulse">
                                        Menunggu Persetujuan
                                    </span>
                                @elseif($item->status == 'approved')
                                    <span class="inline-block text-[9px] font-black uppercase px-2.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg">
                                        Disetujui & Dibukukan
                                    </span>
                                @else
                                    <span class="inline-block text-[9px] font-black uppercase px-2.5 py-0.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-slate-400 text-sm font-semibold">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-history text-3xl text-slate-200"></i>
                                    <span>Anda belum memiliki riwayat setoran iuran kas mandiri.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($riwayatSetoran->hasPages())
            <div class="mt-8 pt-6 border-t border-blue-50 flex justify-center">
                {{ $riwayatSetoran->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
