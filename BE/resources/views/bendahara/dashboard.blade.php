<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Dashboard Keuangan Bendahara RT') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('pemasukan.index') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition">
                    + Input Pemasukan
                </a>
                <a href="{{ route('pengeluaran.index') }}" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition">
                    - Entri Pengeluaran
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Hero Financial Overview Banner -->
            <div class="bg-gradient-to-r from-amber-600 via-amber-700 to-slate-900 rounded-3xl p-8 text-white shadow-2xl space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-amber-500/30 pb-6">
                    <div>
                        <span class="px-3 py-1 bg-amber-400/20 border border-amber-300/30 rounded-full text-amber-200 text-xs font-bold uppercase tracking-widest">
                            Command Center Keuangan Bendahara
                        </span>
                        <h1 class="text-3xl font-black tracking-tight mt-2">Buku Kas Utama RT 01</h1>
                        <p class="text-amber-100 text-xs font-semibold mt-1">Status Keuangan & Saldo Bersih Real-Time</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20 text-right">
                        <span class="text-[10px] font-black uppercase text-amber-200 tracking-wider">Saldo Akhir Kas</span>
                        <h2 class="text-3xl font-black text-white">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-amber-200">Total Akumulasi Pemasukan</span>
                            <p class="text-xl font-black text-emerald-300">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                        </div>
                        <span class="w-10 h-10 bg-emerald-500/20 text-emerald-300 rounded-xl flex items-center justify-center font-bold">📈</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-amber-200">Total Akumulasi Pengeluaran</span>
                            <p class="text-xl font-black text-rose-300">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                        <span class="w-10 h-10 bg-rose-500/20 text-rose-300 rounded-xl flex items-center justify-center font-bold">📉</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-amber-200">Estimasi Valuasi Asset RT</span>
                            <p class="text-xl font-black text-amber-300">Rp {{ number_format($totalAssetNilai, 0, ',', '.') }}</p>
                        </div>
                        <span class="w-10 h-10 bg-amber-500/20 text-amber-300 rounded-xl flex items-center justify-center font-bold">📦</span>
                    </div>
                </div>
            </div>

            <!-- Pending Verifications Alert Widget -->
            @if($iuranPendingCount > 0)
                <div class="bg-amber-500 rounded-3xl p-6 text-white shadow-xl flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">⚡</div>
                        <div>
                            <h4 class="font-black text-base uppercase">Ada {{ $iuranPendingCount }} Tagihan Iuran Menunggu Verifikasi</h4>
                            <p class="text-xs text-amber-100 font-medium">Periksa dan setujui konfirmasi pembayaran kas dari warga.</p>
                        </div>
                    </div>
                    <a href="{{ route('iuran.index') }}" class="px-5 py-2.5 bg-white text-slate-900 font-black text-xs uppercase tracking-wider rounded-xl shadow hover:bg-amber-100 transition">
                        Verifikasi Sekarang &rarr;
                    </a>
                </div>
            @endif

            <!-- Quick Action Hub Bendahara -->
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight mb-4 uppercase">Modul Pengelolaan Keuangan</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('kas.index') }}" class="p-5 bg-white hover:bg-amber-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">💰</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-amber-700 block uppercase">Buku Kas Utama</span>
                    </a>

                    <a href="{{ route('iuran.index') }}" class="p-5 bg-white hover:bg-emerald-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">💳</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-emerald-700 block uppercase">Iuran Bulanan Warga</span>
                    </a>

                    <a href="{{ route('koperasi.index') }}" class="p-5 bg-white hover:bg-blue-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">🛒</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-blue-700 block uppercase">Koperasi RT</span>
                    </a>

                    <a href="{{ route('asset.index') }}" class="p-5 bg-white hover:bg-purple-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">📦</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-purple-700 block uppercase">Inventaris & Asset</span>
                    </a>
                </div>
            </div>

            <!-- Jurnal Kas Terbaru -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Rincian 8 Transaksi Kas Terbaru</h3>
                    <a href="{{ route('kas.index') }}" class="text-xs font-bold text-amber-600 hover:underline">Buka Semua Jurnal &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">Keterangan Transaksi</th>
                                <th class="py-4 px-6 text-right">Debet Pemasukan (Rp)</th>
                                <th class="py-4 px-6 text-right">Kredit Pengeluaran (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($kas as $k)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-mono font-bold text-xs text-slate-700">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $k->keterangan }}</td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-600 text-xs">
                                        {{ $k->pemasukan > 0 ? '+ Rp ' . number_format($k->pemasukan, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-rose-600 text-xs">
                                        {{ $k->pengeluaran > 0 ? '- Rp ' . number_format($k->pengeluaran, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada rincian pembukuan kas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>