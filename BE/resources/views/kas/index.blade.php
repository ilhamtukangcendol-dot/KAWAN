<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Kas & Pembukuan Utama RT') }}
            </h2>
            @if(Auth::user()->role <= 3)
                <button onclick="document.getElementById('modal-kas').classList.remove('hidden')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-indigo-200 transition duration-300">
                    + Catat Transaksi Kas
                </button>
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

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-7 text-white shadow-xl">
                    <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest block mb-1">Total Saldo Kas RT</span>
                    <h3 class="text-3xl font-black text-amber-400">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-3">Saldo Akumulasi Real-Time</p>
                </div>

                <div class="bg-white rounded-3xl p-7 border border-emerald-100 shadow-xl shadow-emerald-50/50">
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest block mb-1">Total Pemasukan Kas</span>
                    <h3 class="text-3xl font-black text-emerald-700">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                    <a href="{{ route('pemasukan.index') }}" class="text-xs font-bold text-emerald-600 hover:underline mt-3 block">Lihat Rincian Pemasukan &rarr;</a>
                </div>

                <div class="bg-white rounded-3xl p-7 border border-rose-100 shadow-xl shadow-rose-50/50">
                    <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest block mb-1">Total Pengeluaran Kas</span>
                    <h3 class="text-3xl font-black text-rose-700">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                    <a href="{{ route('pengeluaran.index') }}" class="text-xs font-bold text-rose-600 hover:underline mt-3 block">Lihat Rincian Pengeluaran &rarr;</a>
                </div>
            </div>

            <!-- Table Kas Transactions -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Jurnal Transaksi Kas RT</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">Keterangan Transaksi</th>
                                <th class="py-4 px-6 text-right">Pemasukan</th>
                                <th class="py-4 px-6 text-right">Pengeluaran</th>
                                <th class="py-4 px-6 text-center">Petugas</th>
                                @if(Auth::user()->role <= 3)
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($kasList as $kas)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-700 text-xs">{{ \Carbon\Carbon::parse($kas->tanggal)->format('d M Y') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $kas->keterangan }}</td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-600 text-xs">
                                        {{ $kas->pemasukan > 0 ? '+ Rp ' . number_format($kas->pemasukan, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-rose-600 text-xs">
                                        {{ $kas->pengeluaran > 0 ? '- Rp ' . number_format($kas->pengeluaran, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-center text-xs font-semibold text-slate-500">
                                        {{ $kas->user ? $kas->user->name : 'Sistem' }}
                                    </td>
                                    @if(Auth::user()->role <= 3)
                                        <td class="py-4 px-6 text-center">
                                            <form method="POST" action="{{ route('kas.destroy', $kas->id) }}" onsubmit="return confirm('Hapus transaksi ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs rounded-lg transition">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada transaksi kas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $kasList->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Catat Kas -->
    @if(Auth::user()->role <= 3)
        <div id="modal-kas" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Catat Transaksi Kas</h3>
                    <button onclick="document.getElementById('modal-kas').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('kas.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tipe Transaksi</label>
                        <select name="tipe" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-bold focus:ring-indigo-500">
                            <option value="masuk">Pemasukan (+)</option>
                            <option value="keluar">Pengeluaran (-)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Keterangan Transaksi</label>
                        <input type="text" name="keterangan" required placeholder="Contoh: Pembelian Sapu & Sampah" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nominal (Rp)</label>
                        <input type="number" name="nominal" required min="1" placeholder="50000" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:ring-indigo-500">
                    </div>

                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-kas').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
