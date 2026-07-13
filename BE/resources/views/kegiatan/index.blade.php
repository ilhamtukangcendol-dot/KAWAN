<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Agenda & Kegiatan Warga RT') }}
            </h2>
            @if(Auth::user()->role <= 2)
                <button onclick="document.getElementById('modal-kegiatan').classList.remove('hidden')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Buat Agenda Kegiatan Baru
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

            <!-- Grid Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($kegiatanList as $k)
                    <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-xl space-y-4 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-black text-[10px] uppercase rounded-xl">{{ $k->kategori }}</span>
                                <span class="text-xs font-bold text-slate-400">📅 {{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</span>
                            </div>
                            <h3 class="font-black text-slate-800 text-lg leading-tight mt-3">{{ $k->nama_kegiatan }}</h3>
                            <p class="text-xs text-slate-500 font-semibold mt-1">📍 {{ $k->lokasi }} &bull; 🕒 {{ $k->waktu }}</p>
                            <p class="text-xs text-slate-600 font-medium mt-3 leading-relaxed">{{ $k->deskripsi }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-500">PJ: {{ $k->penanggung_jawab }}</span>
                            @if(Auth::user()->role <= 2)
                                <form method="POST" action="{{ route('kegiatan.destroy', $k->id) }}" onsubmit="return confirm('Hapus kegiatan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 font-bold hover:underline">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-12 text-center rounded-3xl border border-slate-100 text-slate-400 font-bold text-xs">
                        Belum ada agenda kegiatan warga.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Modal Form -->
    @if(Auth::user()->role <= 2)
        <div id="modal-kegiatan" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Buat Agenda Kegiatan RT</h3>
                    <button onclick="document.getElementById('modal-kegiatan').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('kegiatan.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Kategori Event</label>
                            <input type="text" name="kategori" value="Sosial" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Waktu Execution</label>
                            <input type="text" name="waktu" placeholder="08:00 - 12:00 WIB" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Lokasi</label>
                            <input type="text" name="lokasi" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Penanggung Jawab (PJ)</label>
                        <input type="text" name="penanggung_jawab" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Anggaran Estimasi (Rp)</label>
                        <input type="number" name="anggaran" value="0" required min="0" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Deskripsi & Rincian</label>
                        <textarea name="deskripsi" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold"></textarea>
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-kegiatan').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Simpan Agenda</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
