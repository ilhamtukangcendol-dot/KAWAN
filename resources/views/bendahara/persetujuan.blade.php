@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;850&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfaff; }
</style>

<div class="space-y-8">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-black text-slate-800">Antrean Persetujuan Kas Warga</h2>
        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Konfirmasi dan setujui pembayaran kas masuk mandiri yang dikirimkan oleh warga</p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-xs font-semibold flex items-center gap-2">
        <i class="fas fa-check-circle text-base"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="pb-4">Warga Penyetor</th>
                        <th class="pb-4">Tanggal Setoran</th>
                        <th class="pb-4">Keterangan</th>
                        <th class="pb-4 text-center">Nominal</th>
                        <th class="pb-4 text-right">Tindakan Otoritas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($daftarPersetujuan as $item)
                    <tr class="group hover:bg-slate-50/50 transition">
                        <td class="py-5 font-extrabold text-slate-850 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-slate-800 font-extrabold text-sm leading-none">{{ $item->user->name }}</p>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter leading-none mt-1 block">Warga Aktif</span>
                            </div>
                        </td>
                        <td class="py-5 text-xs text-slate-400 font-semibold">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td>
                        <td class="py-5 font-semibold text-slate-600 max-w-[240px] truncate">
                            {{ $item->keterangan }}
                        </td>
                        <td class="py-5 text-center font-black text-emerald-500">
                            Rp {{ number_format($item->nominal) }}
                        </td>
                        <td class="py-5 text-right">
                            <div class="inline-flex gap-2">
                                <!-- Form Setujui -->
                                <form action="{{ route('bendahara.persetujuan.setujui', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-sm hover:shadow transition">
                                        <i class="fas fa-check mr-1"></i> Setujui
                                    </button>
                                </form>

                                <!-- Form Tolak -->
                                <form action="{{ route('bendahara.persetujuan.tolak', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak setoran kas dari {{ $item->user->name }}?')" class="px-3.5 py-2 bg-rose-500 hover:bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-sm hover:shadow transition">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-slate-400 text-sm font-semibold">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-clipboard-check text-3xl text-slate-200"></i>
                                <span>Antrean persetujuan kas bersih. Belum ada pembayaran baru yang menunggu konfirmasi.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarPersetujuan->hasPages())
        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
            {{ $daftarPersetujuan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
