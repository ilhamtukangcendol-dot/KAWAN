<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Struktur Pengurus RT') }}
            </h2>
            @if(Auth::user()->role <= 2)
                <button onclick="document.getElementById('modal-pengurus').classList.remove('hidden')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Tambah Pengurus RT
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

            <!-- Grid Pengurus Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($pengurusList as $p)
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-indigo-100 text-indigo-700 font-black text-lg rounded-2xl flex items-center justify-center">
                                {{ strtoupper(substr($p->nama, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 text-base leading-tight">{{ $p->nama }}</h3>
                                <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">{{ $p->jabatan }}</p>
                                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Periode {{ $p->periode_mulai }} - {{ $p->periode_selesai }}</p>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span>📞 {{ $p->no_hp ?? '-' }}</span>
                            @if(Auth::user()->role <= 2)
                                <form method="POST" action="{{ route('pengurus.destroy', $p->id) }}" onsubmit="return confirm('Hapus pengurus ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:underline font-bold text-xs">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-12 text-center rounded-3xl border border-slate-100 text-slate-400 font-bold">
                        Belum ada struktur pengurus RT yang didaftarkan.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Modal Form -->
    @if(Auth::user()->role <= 2)
        <div id="modal-pengurus" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Tambah Pengurus RT</h3>
                    <button onclick="document.getElementById('modal-pengurus').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('pengurus.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Pengurus</label>
                        <input type="text" name="nama" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Jabatan Struktur</label>
                        <input type="text" name="jabatan" placeholder="Contoh: Ketua RT / Sekretaris / Bendahara" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Periode Mulai</label>
                            <input type="number" name="periode_mulai" value="{{ date('Y') }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Periode Selesai</label>
                            <input type="number" name="periode_selesai" value="{{ date('Y') + 3 }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nomor Telepon / HP</label>
                        <input type="text" name="no_hp" placeholder="0812xxxx" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-pengurus').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
