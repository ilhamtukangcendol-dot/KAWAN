@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfaff; color: #1e293b; }
</style>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Rekap Laporan Keuangan</h2>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Laporan detail pertanggungjawaban kas RT</p>
        </div>
        <button onclick="window.print()" class="px-5 py-3 bg-slate-800 text-white hover:bg-slate-900 text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg transition flex items-center gap-2">
            <i class="fas fa-print"></i> Cetak Laporan PDF
        </button>
    </div>

    <!-- Filter Form (Hidden when printing) -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8 print:hidden">
        <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari }}" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3 px-4">
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Sampai Tanggal</label>
                <input type="date" name="dari" value="{{ $sampai }}" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3 px-4">
            </div>
            <div>
                <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 transition">
                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pemasukan -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 border-l-4 border-l-emerald-500 flex flex-col justify-between gap-2">
            <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Pemasukan</span>
            <span class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($totalMasuk) }}</span>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 border-l-4 border-l-rose-500 flex flex-col justify-between gap-2">
            <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Pengeluaran</span>
            <span class="text-xl font-extrabold text-rose-600">Rp {{ number_format($totalKeluar) }}</span>
        </div>

        <!-- Surplus/Defisit -->
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-6 rounded-[2rem] shadow-lg text-white flex flex-col justify-between gap-2">
            <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Surplus/Defisit Periode</span>
            <span class="text-xl font-black text-amber-400">Rp {{ number_format($saldoPeriode) }}</span>
        </div>
    </div>

    <!-- Table Rincian -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="py-6 px-8 border-b border-slate-100">
            <h3 class="text-base font-extrabold text-slate-800">Rincian Aliran Kas</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="py-4 px-6 text-center">No</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Keterangan</th>
                        <th class="py-4 px-6 text-right">Debet (Masuk)</th>
                        <th class="py-4 px-6 text-right">Kredit (Keluar)</th>
                        <th class="py-4 px-6 text-right">Saldo Arus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $runningSaldo = 0; @endphp
                    @forelse($dataLaporan as $key => $item)
                    @php $runningSaldo += ($item->pemasukan - $item->pengeluaran); @endphp
                    <tr class="hover:bg-slate-50/30 transition text-sm">
                        <td class="py-4 px-6 text-center text-xs text-slate-400 font-bold">{{ $key + 1 }}</td>
                        <td class="py-4 px-6 text-xs text-slate-500 font-semibold">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="py-4 px-6 font-extrabold text-slate-850">{{ $item->keterangan }}</td>
                        <td class="py-4 px-6 text-right text-emerald-500 font-black">
                            {{ $item->pemasukan > 0 ? 'Rp '.number_format($item->pemasukan) : '-' }}
                        </td>
                        <td class="py-4 px-6 text-right text-rose-500 font-black">
                            {{ $item->pengeluaran > 0 ? 'Rp '.number_format($item->pengeluaran) : '-' }}
                        </td>
                        <td class="py-4 px-6 text-right font-black text-slate-900">
                            Rp {{ number_format($runningSaldo) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-400 text-sm font-semibold">Tidak ditemukan data transaksi untuk periode terpilih.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($dataLaporan->count() > 0)
                <tfoot class="bg-slate-50 font-black text-xs">
                    <tr class="border-t border-slate-200">
                        <td colspan="3" class="py-4 px-6 text-right text-slate-500">TOTAL PERIODE INI</td>
                        <td class="py-4 px-6 text-right text-emerald-500">Rp {{ number_format($totalMasuk) }}</td>
                        <td class="py-4 px-6 text-right text-rose-500">Rp {{ number_format($totalKeluar) }}</td>
                        <td class="py-4 px-6 text-right bg-slate-900 text-amber-400">Rp {{ number_format($saldoPeriode) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Footer Printing -->
    <div class="text-center text-[10px] text-slate-400 font-semibold leading-relaxed">
        <p>Laporan digenerate secara otomatis oleh Sistem Keuangan RT Digital pada {{ date('d M Y H:i') }} WIB</p>
    </div>
</div>
@endsection