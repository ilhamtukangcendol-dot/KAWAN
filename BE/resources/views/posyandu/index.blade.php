<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Modul Layanan Posyandu Balita & Lansia') }}
            </h2>
            @if(Auth::user()->role <= 3)
                <button onclick="document.getElementById('modal-posyandu').classList.remove('hidden')" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Catat Pemeriksaan Kesehatan
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

            <!-- Tabs -->
            <div class="flex gap-3">
                <a href="{{ route('posyandu.index', ['kategori' => 'balita']) }}" class="px-5 py-2.5 rounded-2xl font-black text-xs uppercase tracking-wider transition {{ $kategori == 'balita' ? 'bg-emerald-600 text-white shadow-lg' : 'bg-white text-slate-700 border border-slate-100' }}">
                    👶 Posyandu Balita
                </a>
                <a href="{{ route('posyandu.index', ['kategori' => 'lansia']) }}" class="px-5 py-2.5 rounded-2xl font-black text-xs uppercase tracking-wider transition {{ $kategori == 'lansia' ? 'bg-emerald-600 text-white shadow-lg' : 'bg-white text-slate-700 border border-slate-100' }}">
                    👴 Posyandu Lansia
                </a>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-50/50 text-[10px] font-black text-emerald-800 uppercase tracking-widest border-b border-emerald-100">
                                <th class="py-4 px-6">Tanggal Periksa</th>
                                <th class="py-4 px-6">Nama Pasien / Warga</th>
                                <th class="py-4 px-6">Umur</th>
                                <th class="py-4 px-6 text-right">Berat Badan (Kg)</th>
                                <th class="py-4 px-6 text-right">Tinggi / Panjang (Cm)</th>
                                <th class="py-4 px-6">Catatan Medis Kader</th>
                                @if(Auth::user()->role <= 3)
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($pemeriksaanList as $p)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-700 text-xs">{{ \Carbon\Carbon::parse($p->tanggal_periksa)->format('d M Y') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $p->nama_pasien }}</td>
                                    <td class="py-4 px-6 font-semibold text-slate-600 text-xs">{{ $p->umur_bulan_atau_tahun }} {{ $p->kategori == 'balita' ? 'Bulan' : 'Tahun' }}</td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-600 text-xs">{{ $p->bb_kg }} Kg</td>
                                    <td class="py-4 px-6 text-right font-black text-slate-700 text-xs">{{ $p->tb_cm }} Cm</td>
                                    <td class="py-4 px-6 font-medium text-slate-600 text-xs">{{ $p->catatan ?? '-' }}</td>
                                    @if(Auth::user()->role <= 3)
                                        <td class="py-4 px-6 text-center">
                                            <form method="POST" action="{{ route('posyandu.destroy', $p->id) }}" onsubmit="return confirm('Hapus catatan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs rounded-lg transition">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada pemeriksaan Posyandu {{ $kategori }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Form Posyandu -->
    @if(Auth::user()->role <= 3)
        <div id="modal-posyandu" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Pemeriksaan Posyandu Baru</h3>
                    <button onclick="document.getElementById('modal-posyandu').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('posyandu.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Kategori Posyandu</label>
                        <select name="kategori" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                            <option value="balita" {{ $kategori == 'balita' ? 'selected' : '' }}>Balita</option>
                            <option value="lansia" {{ $kategori == 'lansia' ? 'selected' : '' }}>Lansia</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Pasien</label>
                        <input type="text" name="nama_pasien" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Umur</label>
                            <input type="number" name="umur_bulan_atau_tahun" min="0" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-3 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">BB (Kg)</label>
                            <input type="number" step="0.1" name="bb_kg" required min="0" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-3 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">TB (Cm)</label>
                            <input type="number" step="0.1" name="tb_cm" required min="0" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-3 font-semibold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Catatan Medis / Pemberian Gizi</label>
                        <textarea name="catatan" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal Periksa</label>
                            <input type="date" name="tanggal_periksa" value="{{ date('Y-m-d') }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Petugas / Kader</label>
                            <input type="text" name="petugas" value="Kader Posyandu RT" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-posyandu').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Simpan Catatan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
