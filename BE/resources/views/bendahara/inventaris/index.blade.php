@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;850&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfaff; }
</style>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Inventaris Barang RT</h2>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Catatan kepemilikan aset fasilitas lingkungan RT.001</p>
        </div>
        <a href="{{ route('bendahara.inventaris.create') }}" class="px-5 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-indigo-500/10 flex items-center gap-2 transition duration-300">
            <i class="fas fa-plus-circle"></i> Tambah Aset Barang
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-xs font-semibold flex items-center gap-2">
        <i class="fas fa-check-circle text-base"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Inventory Table Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="pb-4">Nama Barang / Aset</th>
                        <th class="pb-4 text-center">Jumlah (Qty)</th>
                        <th class="pb-4 text-center">Kondisi</th>
                        <th class="pb-4 text-center">Tanggal Perolehan</th>
                        <th class="pb-4 text-right">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($daftarInventaris as $item)
                    <tr class="group hover:bg-slate-50/50 transition">
                        <td class="py-4 font-extrabold text-slate-850 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                                <i class="fas fa-box"></i>
                            </div>
                            <span>{{ $item->nama_barang }}</span>
                        </td>
                        <td class="py-4 text-center font-bold text-slate-700">
                            {{ $item->jumlah }} Pcs
                        </td>
                        <td class="py-4 text-center">
                            @if($item->kondisi == 'Baik')
                                <span class="inline-block text-[9px] font-black px-2.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg">
                                    Baik
                                </span>
                            @elseif($item->kondisi == 'Rusak Ringan')
                                <span class="inline-block text-[9px] font-black px-2.5 py-0.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg">
                                    Rusak Ringan
                                </span>
                            @else
                                <span class="inline-block text-[9px] font-black px-2.5 py-0.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg">
                                    Rusak Berat
                                </span>
                            @endif
                        </td>
                        <td class="py-4 text-center text-xs text-slate-400 font-semibold">
                            {{ \Carbon\Carbon::parse($item->tanggal_perolehan)->format('d M Y') }}
                        </td>
                        <td class="py-4 text-right text-xs text-slate-400 font-semibold max-w-[200px] truncate">
                            {{ $item->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400 text-sm font-semibold">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-boxes text-3xl text-slate-200"></i>
                                <span>Belum ada aset barang inventaris terdaftar.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarInventaris->hasPages())
        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
            {{ $daftarInventaris->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
