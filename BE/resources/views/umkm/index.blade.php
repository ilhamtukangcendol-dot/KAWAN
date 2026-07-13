<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Katalog UMKM & Usaha Warga RT') }}
            </h2>
            <button onclick="document.getElementById('modal-umkm').classList.remove('hidden')" class="px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                + Daftarkan Usaha / UMKM
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Grid UMKM Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($umkmList as $u)
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl space-y-4 flex flex-col justify-between">
                        <div>
                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold text-[10px] uppercase rounded-md">{{ $u->kategori }}</span>
                            <h3 class="font-black text-slate-800 text-lg leading-tight mt-2">{{ $u->nama_usaha }}</h3>
                            <p class="text-xs font-bold text-slate-400">Pemilik: {{ $u->pemilik }}</p>
                            <p class="text-xs text-slate-600 font-medium mt-2 leading-relaxed">{{ $u->deskripsi }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $u->no_whatsapp) }}" target="_blank" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase rounded-xl shadow transition">
                                💬 Hubungi WA
                            </a>
                            @if(Auth::id() === $u->user_id || Auth::user()->role <= 2)
                                <form method="POST" action="{{ route('umkm.destroy', $u->id) }}" onsubmit="return confirm('Hapus profil UMKM ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 font-bold text-xs hover:underline">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-12 text-center rounded-3xl border border-slate-100 text-slate-400 font-bold">
                        Belum ada usaha UMKM terdaftar di lingkungan RT.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Modal Form -->
    <div id="modal-umkm" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
            <div class="flex justify-between items-center">
                <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Daftarkan Usaha UMKM Warga</h3>
                <button onclick="document.getElementById('modal-umkm').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('umkm.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Usaha / Toko</label>
                    <input type="text" name="nama_usaha" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Pemilik</label>
                        <input type="text" name="pemilik" value="{{ Auth::user()->name }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Kategori Usaha</label>
                        <input type="text" name="kategori" placeholder="Kuliner / Jasa / Sembako" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nomor WhatsApp Jualan</label>
                    <input type="text" name="no_whatsapp" placeholder="0812xxxx" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Alamat Usaha RT</label>
                    <input type="text" name="alamat" placeholder="Jl. Mawar No. X" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Deskripsi & Produk Unggulan</label>
                    <textarea name="deskripsi" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold"></textarea>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-umkm').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-amber-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Simpan UMKM</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
