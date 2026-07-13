<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Modul Koperasi RT') }}
            </h2>
            @if(Auth::user()->role <= 3)
                <button onclick="document.getElementById('modal-produk').classList.remove('hidden')" class="px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Tambah Barang Koperasi
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

            <!-- Katalog Barang Koperasi -->
            <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Katalog Produk Sembako Koperasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @forelse($produkList as $produk)
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl space-y-3 flex flex-col justify-between">
                        <div>
                            <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 font-bold text-[10px] uppercase rounded-md">{{ $produk->kategori }}</span>
                            <h4 class="font-black text-slate-800 text-base mt-2 leading-tight">{{ $produk->nama_produk }}</h4>
                            <p class="text-xs text-slate-500 font-semibold mt-1">{{ $produk->deskripsi ?? 'Persediaan resmi Koperasi RT.' }}</p>
                            <div class="mt-3 flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-400">Stok: {{ $produk->stok }} Pcs</span>
                                <span class="text-base font-black text-amber-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex gap-2">
                            <form method="POST" action="{{ route('koperasi.transaksi.store') }}" class="w-full">
                                @csrf
                                <input type="hidden" name="koperasi_id" value="{{ $produk->id }}">
                                <input type="hidden" name="jenis" value="pembelian">
                                <input type="hidden" name="nominal" value="{{ $produk->harga }}">
                                <button type="submit" class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow">Beli Produk</button>
                            </form>
                            @if(Auth::user()->role <= 3)
                                <form method="POST" action="{{ route('koperasi.produk.destroy', $produk->id) }}" onsubmit="return confirm('Hapus produk?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-rose-50 text-rose-600 font-bold text-xs rounded-xl hover:bg-rose-600 hover:text-white transition">&times;</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 bg-white p-8 text-center rounded-3xl border border-slate-100 text-slate-400 font-bold text-xs">
                        Belum ada barang di Koperasi RT.
                    </div>
                @endforelse
            </div>

            <!-- Jurnal Transaksi Koperasi -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden mt-8">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Riwayat Transaksi Koperasi (Beli / Simpan / Pinjam)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">Waktu</th>
                                <th class="py-4 px-6">Anggota Warga</th>
                                <th class="py-4 px-6">Jenis Transaksi</th>
                                <th class="py-4 px-6 text-right">Nominal (Rp)</th>
                                <th class="py-4 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($transaksiList as $tx)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-700 text-xs">{{ $tx->created_at->format('d M Y H:i') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $tx->user ? $tx->user->name : '-' }}</td>
                                    <td class="py-4 px-6 uppercase font-bold text-xs text-amber-700">{{ $tx->jenis }} {{ $tx->produk ? '(' . $tx->produk->nama_produk . ')' : '' }}</td>
                                    <td class="py-4 px-6 text-right font-black text-slate-800 text-xs">Rp {{ number_format($tx->nominal, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if($tx->status == 'approved')
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-lg">DISETUJUI</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-lg">PENDING</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada riwayat transaksi koperasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Form Product -->
    @if(Auth::user()->role <= 3)
        <div id="modal-produk" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Tambah Produk Koperasi</h3>
                    <button onclick="document.getElementById('modal-produk').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('koperasi.produk.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Barang / Produk</label>
                        <input type="text" name="nama_produk" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Kategori</label>
                            <input type="text" name="kategori" value="Sembako" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Stok (Pcs)</label>
                            <input type="number" name="stok" value="20" min="0" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="harga" required min="0" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold"></textarea>
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-produk').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-amber-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
