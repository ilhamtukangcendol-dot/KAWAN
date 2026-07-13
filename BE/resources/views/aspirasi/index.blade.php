<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Kotak Aspirasi & Pengaduan Warga') }}
            </h2>
            <button onclick="document.getElementById('modal-aspirasi').classList.remove('hidden')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                + Kirim Aspirasi / Pengaduan
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

            <!-- List Pengaduan & Aspirasi -->
            <div class="space-y-4">
                @forelse($aspirasiList as $a)
                    <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-xl space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-black text-[10px] uppercase rounded-xl">{{ $a->kategori }}</span>
                                <span class="text-xs font-bold text-slate-400">Pengirim: {{ $a->user ? $a->user->name : 'Warga' }} &bull; {{ $a->created_at->diffForHumans() }}</span>
                            </div>
                            @if($a->status == 'resolved')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-black text-[10px] uppercase rounded-lg">SELESAI / RAMPUNG</span>
                            @elseif($a->status == 'in_progress')
                                <span class="px-3 py-1 bg-amber-100 text-amber-800 font-black text-[10px] uppercase rounded-lg">DALAM PROSES PROSES RT</span>
                            @else
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 font-black text-[10px] uppercase rounded-lg">OPEN</span>
                            @endif
                        </div>

                        <div>
                            <h3 class="font-black text-slate-800 text-lg leading-tight">{{ $a->judul }}</h3>
                            <p class="text-xs text-slate-700 font-medium mt-2 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">{{ $a->isi }}</p>
                        </div>

                        @if($a->tanggapan)
                            <div class="p-4 bg-indigo-50/60 border border-indigo-100 rounded-2xl text-xs space-y-1">
                                <span class="font-black text-indigo-800 uppercase tracking-widest text-[10px]">Tanggapan Pengurus RT:</span>
                                <p class="text-slate-800 font-semibold leading-relaxed">{{ $a->tanggapan }}</p>
                            </div>
                        @endif

                        @if(Auth::user()->role <= 2)
                            <form method="POST" action="{{ route('aspirasi.respond', $a->id) }}" class="pt-2 border-t border-slate-100 flex items-center gap-3">
                                @csrf
                                <select name="status" class="border-slate-100 bg-slate-50 text-xs py-2 px-3 font-bold rounded-xl">
                                    <option value="open" {{ $a->status == 'open' ? 'selected' : '' }}>OPEN</option>
                                    <option value="in_progress" {{ $a->status == 'in_progress' ? 'selected' : '' }}>PROSES (IN PROGRESS)</option>
                                    <option value="resolved" {{ $a->status == 'resolved' ? 'selected' : '' }}>SELESAI (RESOLVED)</option>
                                </select>
                                <input type="text" name="tanggapan" value="{{ $a->tanggapan }}" placeholder="Tuliskan jawaban / tanggapan RT..." required class="w-full border-slate-100 rounded-xl bg-slate-50 text-xs py-2 px-3 font-semibold">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-bold text-xs rounded-xl uppercase tracking-wider shadow shrink-0">Beri Tanggapan</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="bg-white p-12 text-center rounded-3xl border border-slate-100 text-slate-400 font-bold text-xs">
                        Belum ada aspirasi atau pengaduan warga.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Modal Form Aspirasi -->
    <div id="modal-aspirasi" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
            <div class="flex justify-between items-center">
                <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Kirim Aspirasi / Pengaduan</h3>
                <button onclick="document.getElementById('modal-aspirasi').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('aspirasi.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Judul Aspirasi / Topik</label>
                    <input type="text" name="judul" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Kategori Kategori</label>
                    <input type="text" name="kategori" value="Infrastruktur & Kebersihan" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Isi Lengkap Pesan Aspirasi</label>
                    <textarea name="isi" required rows="4" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold"></textarea>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-aspirasi').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Kirim Aspirasi</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
