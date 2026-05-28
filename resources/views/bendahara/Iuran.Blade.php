@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfaff; }
</style>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Manajemen Iuran Warga</h2>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Riwayat Penerimaan Arus Kas Masuk</p>
        </div>
        <div class="bg-emerald-500 text-white rounded-2xl px-6 py-4 shadow-lg shadow-emerald-500/10 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-lg">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <small class="block text-[9px] font-bold text-emerald-100 uppercase tracking-widest leading-none mb-1">Total Terkumpul</small>
                <span class="text-lg font-black leading-none">Rp {{ number_format($totalIuran) }}</span>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="pb-4">Keterangan Penerimaan</th>
                        <th class="pb-4">Tanggal Pembukuan</th>
                        <th class="pb-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($iuran as $item)
                    <tr class="group hover:bg-slate-50/50 transition">
                        <td class="py-4">
                            <div class="font-extrabold text-slate-800 text-sm group-hover:text-emerald-600 transition-colors">{{ $item->keterangan }}</div>
                        </td>
                        <td class="py-4 text-xs text-slate-400 font-semibold">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td>
                        <td class="py-4 text-right font-black text-sm text-emerald-500">
                            + Rp {{ number_format($item->pemasukan) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-slate-400 text-sm font-semibold">Belum ada data iuran warga tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($iuran->hasPages())
        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
            {{ $iuran->links() }}
        </div>
        @endif
    </div>
</div>
@endsection