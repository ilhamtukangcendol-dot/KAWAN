<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Keamanan & Jadwal Ronda Malam RT') }}
            </h2>
            @if(Auth::user()->role <= 2)
                <button onclick="document.getElementById('modal-ronda').classList.remove('hidden')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Tambah Jadwal Ronda
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

            <!-- Grid Jadwal Ronda -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($jadwalRonda as $r)
                    <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-xl space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-black text-xs uppercase rounded-xl border border-indigo-100">{{ $r->hari }}</span>
                            <span class="text-xs font-bold text-slate-400">Tgl: {{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}</span>
                        </div>

                        <div>
                            <h3 class="font-black text-slate-800 text-lg leading-tight">{{ $r->regu }} &bull; {{ $r->pos_ronda }}</h3>
                            <p class="text-xs font-bold text-slate-500 mt-2">Daftar Petugas Warga Ronda:</p>
                            <p class="text-xs font-semibold text-slate-700 mt-1 leading-relaxed bg-slate-50 p-3 rounded-2xl border border-slate-100">{{ $r->anggota_warga }}</p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="font-bold {{ $r->status_piket == 'selesai' ? 'text-emerald-600' : 'text-amber-600' }}">
                                Status: {{ strtoupper($r->status_piket) }}
                            </span>
                            @if(Auth::user()->role <= 2)
                                <form method="POST" action="{{ route('ronda.destroy', $r->id) }}" onsubmit="return confirm('Hapus jadwal ronda?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 font-bold hover:underline">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 bg-white p-12 text-center rounded-3xl border border-slate-100 text-slate-400 font-bold text-xs">
                        👮 Belum ada jadwal ronda keamanan malam.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Modal Form Ronda -->
    @if(Auth::user()->role <= 2)
        <div id="modal-ronda" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-lg w-full shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Jadwal Ronda Malam Baru</h3>
                    <button onclick="document.getElementById('modal-ronda').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('ronda.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Hari Ronda</label>
                            <input type="text" name="hari" placeholder="Contoh: Sabtu Malam" required class="w-full border border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:outline-none focus:border-indigo-400">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full border border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:outline-none focus:border-indigo-400">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Regu</label>
                        <input type="text" name="regu" placeholder="Regu Mawar 1" required class="w-full border border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:outline-none focus:border-indigo-400">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Pos Ronda</label>
                        <input type="text" name="pos_ronda" value="Pos Ronda Utama RT 01" required class="w-full border border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:outline-none focus:border-indigo-400">
                    </div>

                    <!-- Pilihan Anggota dari Warga RT -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">
                            Pilih Anggota Ronda <span class="text-indigo-600">(Otomatis dari Data RT)</span>
                        </label>
                        <input type="text" id="ronda-warga-search" placeholder="🔍 Cari nama warga..." class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-2 px-3 font-semibold mb-2 focus:outline-none focus:border-indigo-400" oninput="filterRondaWarga(this.value)">
                        
                        <div class="border border-slate-100 rounded-2xl p-3 bg-slate-50/50 max-h-40 overflow-y-auto space-y-1.5" id="ronda-warga-list">
                            @foreach($wargaList as $w)
                                <label class="flex items-center gap-2 px-2 py-1 hover:bg-white rounded-lg cursor-pointer transition w-full text-xs font-semibold text-slate-700 warga-item" data-nama="{{ strtolower($w->nama_lengkap) }}">
                                    <input type="checkbox" name="anggota_ids[]" value="{{ $w->id }}" class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                    <span>{{ $w->nama_lengkap }} <span class="text-[10px] text-slate-400 font-normal">({{ $w->nik }})</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Anggota Tambahan / Manual (Opsional)</label>
                        <input type="text" name="anggota_manual" placeholder="Tulis nama dipisah koma jika tidak ada di database..." class="w-full border border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:outline-none focus:border-indigo-400">
                    </div>

                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-ronda').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase shadow-md hover:bg-indigo-700 transition">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        function filterRondaWarga(query) {
            const items = document.querySelectorAll('#ronda-warga-list .warga-item');
            const cleanQuery = query.toLowerCase().trim();
            
            items.forEach(item => {
                const name = item.getAttribute('data-nama');
                if (cleanQuery === '' || name.includes(cleanQuery)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
