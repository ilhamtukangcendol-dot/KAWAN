@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
</style>

<div class="space-y-8">
    <!-- Header Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-slate-950 text-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-700/50">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -ml-20 -mb-20"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <span class="inline-block bg-blue-500/20 text-blue-300 text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full">
                    Panel Otoritas Utama
                </span>
                <h1 class="text-4xl font-extrabold tracking-tight">Monitoring RT.001</h1>
                <p class="text-slate-400 text-sm max-w-xl">Selamat datang kembali, Pak Ketua. Berikut adalah laporan data kependudukan dan ringkasan keuangan lingkungan RT Anda.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 shadow-lg min-w-[240px] text-left">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Saldo Kas RT</p>
                <h3 class="text-3xl font-black text-emerald-400">Rp {{ number_format($saldo) }}</h3>
                <span class="inline-flex items-center text-[10px] text-slate-400 mt-2 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-ping"></span>
                    Sinkron Digital Real-Time
                </span>
            </div>
        </div>
    </div>

    <!-- Statistik Warga Grid -->
    <div class="space-y-4">
        <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
            <i class="fas fa-users-cog text-blue-600"></i> Statistik Kependudukan
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Warga -->
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Warga</span>
                    <span class="text-2xl font-extrabold text-slate-900">{{ $totalWarga }}</span>
                    <span class="block text-[10px] text-slate-400">Jiwa Terdaftar</span>
                </div>
            </div>

            <!-- Total KK -->
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fas fa-home"></i>
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Total KK</span>
                    <span class="text-2xl font-extrabold text-slate-900">{{ $totalKK }}</span>
                    <span class="block text-[10px] text-slate-400">Kepala Keluarga</span>
                </div>
            </div>

            <!-- Laki-laki -->
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fas fa-mars"></i>
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Laki-Laki</span>
                    <span class="text-2xl font-extrabold text-slate-900">{{ $wargaLaki }}</span>
                    <span class="block text-[10px] text-indigo-400">Rasio Pria</span>
                </div>
            </div>

            <!-- Perempuan -->
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fas fa-venus"></i>
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Perempuan</span>
                    <span class="text-2xl font-extrabold text-slate-900">{{ $wargaPerempuan }}</span>
                    <span class="block text-[10px] text-rose-400">Rasio Wanita</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Transaksi Terakhir (Audit) -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-800">Riwayat Transaksi Terakhir</h3>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Jurnal Audit Bendahara RT</p>
                </div>
                <a href="{{ route('ketua.audit') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase tracking-widest rounded-xl transition-all">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="pb-4">Detail Transaksi</th>
                            <th class="pb-4">Tanggal</th>
                            <th class="pb-4 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transaksiTerakhir as $item)
                        <tr class="group">
                            <td class="py-4">
                                <div class="font-extrabold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $item->keterangan }}</div>
                                <span class="inline-block text-[9px] font-black uppercase px-2 py-0.5 rounded {{ $item->pemasukan > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} mt-1">
                                    {{ $item->pemasukan > 0 ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </td>
                            <td class="py-4 text-xs text-slate-400 font-semibold">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </td>
                            <td class="py-4 text-right font-black text-sm {{ $item->pemasukan > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $item->pemasukan > 0 ? '+ Rp '.number_format($item->pemasukan) : '- Rp '.number_format($item->pengeluaran) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-8 text-slate-400 text-sm font-semibold">Belum ada transaksi tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ringkasan Keuangan Card -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8 space-y-6 flex flex-col justify-between">
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-800">Ringkasan Keuangan</h3>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Akumulasi Kas RT</p>
                </div>

                <div class="space-y-4">
                    <!-- Total Pemasukan -->
                    <div class="p-5 bg-emerald-50/50 rounded-2xl border border-emerald-100 flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Pemasukan</span>
                            <h4 class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($totalPemasukan) }}</h4>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm shadow-sm">
                            <i class="fas fa-arrow-down-long"></i>
                        </div>
                    </div>

                    <!-- Total Pengeluaran -->
                    <div class="p-5 bg-rose-50/50 rounded-2xl border border-rose-100 flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Pengeluaran</span>
                            <h4 class="text-xl font-extrabold text-rose-600">Rp {{ number_format($totalPengeluaran) }}</h4>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-sm shadow-sm">
                            <i class="fas fa-arrow-up-long"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kebijakan RT Hint -->
            <div class="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 text-[11px] text-slate-400 leading-relaxed font-semibold italic flex gap-3">
                <i class="fas fa-info-circle text-blue-500 text-base mt-0.5"></i>
                <span>Ketua RT dapat mengaudit secara langsung seluruh data transaksi bendahara demi transparansi warga.</span>
            </div>
        </div>
    </div>
</div>
@endsection