<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Dashboard Eksekutif Ketua RT') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('surat.index') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition">
                    ✉️ Otorisasi Surat
                </a>
                <a href="{{ route('pengumuman.index') }}" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition">
                    📢 Terbitkan Pengumuman
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

            <!-- Hero Executive Overview -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 rounded-3xl p-8 text-white shadow-2xl space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-indigo-500/30 pb-6">
                    <div>
                        <span class="px-3 py-1 bg-indigo-500/20 border border-indigo-400/30 rounded-full text-indigo-200 text-xs font-bold uppercase tracking-widest">
                            Panel Operasional Ketua RT
                        </span>
                        <h1 class="text-3xl font-black tracking-tight mt-2">Wilayah RT 01 / RW 05</h1>
                        <p class="text-indigo-200 text-xs font-semibold mt-1">Pemantauan Lingkungan, Kependudukan, & Layanan Publik</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20 text-right">
                        <span class="text-[10px] font-black uppercase text-indigo-200 tracking-wider">Total Saldo Kas RT</span>
                        <h2 class="text-3xl font-black text-amber-400">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15">
                        <span class="text-[10px] uppercase font-bold text-indigo-200 block">Total Jiwa Warga</span>
                        <p class="text-2xl font-black text-white mt-1">{{ $totalWarga }} Orang</p>
                        <span class="text-[10px] text-indigo-300 font-semibold">L: {{ $wargaLaki }} &bull; P: {{ $wargaPerempuan }}</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15">
                        <span class="text-[10px] uppercase font-bold text-indigo-200 block">Total Kartu Keluarga (KK)</span>
                        <p class="text-2xl font-black text-white mt-1">{{ $totalKK }} KK</p>
                        <span class="text-[10px] text-indigo-300 font-semibold">Terverifikasi Sistem</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15">
                        <span class="text-[10px] uppercase font-bold text-indigo-200 block">Total Pemasukan Kas</span>
                        <p class="text-xl font-black text-emerald-300 mt-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-emerald-400 font-semibold">Pemasukan Akumulatif</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15">
                        <span class="text-[10px] uppercase font-bold text-indigo-200 block">Total Pengeluaran Kas</span>
                        <p class="text-xl font-black text-rose-300 mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-rose-400 font-semibold">Pengeluaran Operasional</span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Hub Executive -->
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight mb-4 uppercase">Modul Tata Kelola RT</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <a href="{{ route('data-warga.index') }}" class="p-5 bg-white hover:bg-blue-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">👥</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-blue-700 block uppercase">Data Warga</span>
                    </a>

                    <a href="{{ route('pengurus.index') }}" class="p-5 bg-white hover:bg-purple-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">👔</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-purple-700 block uppercase">Struktur Pengurus</span>
                    </a>

                    <a href="{{ route('surat.index') }}" class="p-5 bg-white hover:bg-emerald-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">✉️</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-emerald-700 block uppercase">Surat Menyurat</span>
                    </a>

                    <a href="{{ route('ronda.index') }}" class="p-5 bg-white hover:bg-rose-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-rose-100 text-rose-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">🛡️</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-rose-700 block uppercase">Jadwal Ronda</span>
                    </a>

                    <a href="{{ route('kegiatan.index') }}" class="p-5 bg-white hover:bg-amber-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">📅</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-amber-700 block uppercase">Kegiatan RT</span>
                    </a>

                    <a href="{{ route('aspirasi.index') }}" class="p-5 bg-white hover:bg-teal-50/50 border border-slate-100 rounded-3xl shadow-lg text-center group transition">
                        <div class="w-12 h-12 bg-teal-100 text-teal-700 rounded-2xl mx-auto flex items-center justify-center text-xl font-bold mb-3 group-hover:scale-110 transition-transform">💡</div>
                        <span class="font-black text-xs text-slate-800 group-hover:text-teal-700 block uppercase">Kotak Aspirasi</span>
                    </a>
                </div>
            </div>

            <!-- Transaksi Terakhir & Jurnal Kas -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Ringkasan Mutasi Kas Lingkungan RT</h3>
                    <a href="{{ route('kas.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Monitor Seluruh Kas &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">Keterangan Aktivitas</th>
                                <th class="py-4 px-6 text-right">Pemasukan (Rp)</th>
                                <th class="py-4 px-6 text-right">Pengeluaran (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($transaksiTerakhir as $t)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-mono font-bold text-xs text-slate-700">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $t->keterangan }}</td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-600 text-xs">
                                        {{ $t->pemasukan > 0 ? '+ Rp ' . number_format($t->pemasukan, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-rose-600 text-xs">
                                        {{ $t->pengeluaran > 0 ? '- Rp ' . number_format($t->pengeluaran, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada mutasi transaksi kas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>