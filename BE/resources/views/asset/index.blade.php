<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Modul Asset & Inventaris RT') }}
            </h2>
            @if(Auth::user()->role <= 3)
                <button onclick="document.getElementById('modal-asset').classList.remove('hidden')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Tambah Asset Baru
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

            <!-- Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-7 text-white shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest block mb-1">Total Unit Barang RT</span>
                        <h3 class="text-3xl font-black text-amber-400">{{ $totalAsset }} Unit</h3>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-7 border border-indigo-100 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest block mb-1">Estimasi Valuasi Asset RT</span>
                        <h3 class="text-3xl font-black text-indigo-700">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Daftar Inventaris & Fasilitas RT</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">Nama Barang / Asset</th>
                                <th class="py-4 px-6 text-center">Jumlah Unit</th>
                                <th class="py-4 px-6 text-center">Kondisi</th>
                                <th class="py-4 px-6 text-right">Harga Perolehan</th>
                                <th class="py-4 px-6">Tgl Perolehan & Lokasi</th>
                                @if(Auth::user()->role <= 3)
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($assetList as $asset)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $asset->nama_barang }}</td>
                                    <td class="py-4 px-6 text-center font-bold text-indigo-700 text-xs">{{ $asset->jumlah }} Unit</td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-lg uppercase">{{ $asset->kondisi }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-slate-800 text-xs">Rp {{ number_format($asset->nominal, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-xs text-slate-600 font-medium">
                                        <p class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d M Y') }}</p>
                                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $asset->keterangan ?? 'Inventaris RT' }}</p>
                                    </td>
                                    @if(Auth::user()->role <= 3)
                                        <td class="py-4 px-6 text-center">
                                            <form method="POST" action="{{ route('asset.destroy', $asset->id) }}" onsubmit="return confirm('Hapus asset ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs rounded-lg transition">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada inventaris asset RT.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Form Asset -->
    @if(Auth::user()->role <= 3)
        <div id="modal-asset" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Tambah Asset RT Baru</h3>
                    <button onclick="document.getElementById('modal-asset').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('asset.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Barang / Asset</label>
                        <input type="text" name="nama_barang" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Jumlah Unit</label>
                            <input type="number" name="jumlah" value="1" min="1" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Kondisi</label>
                            <select name="kondisi" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Harga Perolehan (Rp)</label>
                            <input type="number" name="nominal" required min="0" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal Perolehan</label>
                            <input type="date" name="tanggal_perolehan" value="{{ date('Y-m-d') }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Keterangan / Penyimpanan</label>
                        <textarea name="keterangan" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold"></textarea>
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-asset').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Simpan Asset</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
