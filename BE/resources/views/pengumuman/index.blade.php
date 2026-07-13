<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Pengumuman & Informasi RT') }}
            </h2>
            @if(Auth::user()->role <= 2)
                <button onclick="document.getElementById('modal-pengumuman').classList.remove('hidden')" class="px-5 py-3 bg-purple-600 hover:bg-purple-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Buat Pengumuman Baru
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

            <!-- List Pengumuman -->
            <div class="space-y-4">
                @forelse($pengumumanList as $p)
                    <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-xl space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if($p->kategori == 'penting')
                                    <span class="px-3 py-1 bg-rose-100 text-rose-800 font-black text-[10px] uppercase rounded-xl">📢 PENTING & URGENT</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-700 font-black text-[10px] uppercase rounded-xl">INFORMASI BIASA</span>
                                @endif
                                <span class="text-xs font-bold text-slate-400">Diterbitkan: {{ $p->created_at->format('d M Y H:i') }} &bull; Oleh {{ $p->user ? $p->user->name : 'Pengurus RT' }}</span>
                            </div>
                            @if(Auth::user()->role <= 2)
                                <form method="POST" action="{{ route('pengumuman.destroy', $p->id) }}" onsubmit="return confirm('Hapus pengumuman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 font-bold text-xs hover:underline">Hapus</button>
                                </form>
                            @endif
                        </div>

                        <h3 class="font-black text-slate-800 text-xl leading-tight">{{ $p->judul }}</h3>
                        <p class="text-xs text-slate-700 font-medium leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">{{ $p->isi }}</p>
                    </div>
                @empty
                    <div class="bg-white p-12 text-center rounded-3xl border border-slate-100 text-slate-400 font-bold text-xs">
                        Belum ada pengumuman penerbitan RT.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Modal Form Pengumuman -->
    @if(Auth::user()->role <= 2)
        <div id="modal-pengumuman" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Terbitkan Pengumuman Baru</h3>
                    <button onclick="document.getElementById('modal-pengumuman').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('pengumuman.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Judul Pengumuman</label>
                        <input type="text" name="judul" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Kategori Pengumuman</label>
                        <select name="kategori" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                            <option value="biasa">Informasi Biasa</option>
                            <option value="penting">Penting & Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Isi Lengkap Pengumuman</label>
                        <textarea name="isi" required rows="5" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold"></textarea>
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-pengumuman').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-purple-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Terbitkan Now</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
