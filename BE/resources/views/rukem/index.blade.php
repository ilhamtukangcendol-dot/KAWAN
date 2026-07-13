<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Modul Rukem (Rukun Kematian)') }}
            </h2>
            <button onclick="document.getElementById('modal-rukem').classList.remove('hidden')" class="px-5 py-3 bg-slate-900 hover:bg-black text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                + Catat Data Rukem / Belasungkawa
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

            <!-- Summary -->
            <div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-3xl p-7 text-white shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Total Dana Santunan Duka Tersalurkan</span>
                    <h3 class="text-3xl font-black text-amber-400">Rp {{ number_format($totalSantunanPaid, 0, ',', '.') }}</h3>
                </div>
                <span class="px-4 py-2 bg-white/10 rounded-2xl text-xs font-black uppercase tracking-wider border border-white/20">Rukun Kematian RT</span>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Daftar Santunan Belasungkawa & Klaim Rukem</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">Tgl Wafat</th>
                                <th class="py-4 px-6">Nama Almarhum / Almarhumah</th>
                                <th class="py-4 px-6">Penerima Ahli Waris</th>
                                <th class="py-4 px-6 text-right">Nominal Santunan</th>
                                <th class="py-4 px-6 text-center">Status Pencairan</th>
                                <th class="py-4 px-6 text-center">Aksi Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($rukemList as $r)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-700 text-xs">{{ \Carbon\Carbon::parse($r->tanggal_wafat)->format('d M Y') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $r->nama_almarhum }}</td>
                                    <td class="py-4 px-6 font-semibold text-slate-700 text-xs">{{ $r->ahli_waris }}</td>
                                    <td class="py-4 px-6 text-right font-black text-slate-800 text-xs">Rp {{ number_format($r->nominal_santunan, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if($r->status == 'dicairkan')
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-lg uppercase">DICAIRKAN & DIBUKUKAN</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-lg uppercase">MENUNGGU PENCAIRAN</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex justify-center gap-2">
                                            @if($r->status == 'pending' && Auth::user()->role <= 3)
                                                <form method="POST" action="{{ route('rukem.cairkan', $r->id) }}">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white font-bold text-xs rounded-lg shadow">Cairkan & Kas</button>
                                                </form>
                                            @endif
                                            @if(Auth::user()->role <= 3)
                                                <form method="POST" action="{{ route('rukem.destroy', $r->id) }}" onsubmit="return confirm('Hapus data rukem?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 font-bold text-xs rounded-lg">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada data Rukem.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Form -->
    <div id="modal-rukem" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
            <div class="flex justify-between items-center">
                <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Data Duka Rukem Baru</h3>
                <button onclick="document.getElementById('modal-rukem').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('rukem.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Almarhum / Almarhumah</label>
                    <input type="text" name="nama_almarhum" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal Wafat</label>
                        <input type="date" name="tanggal_wafat" value="{{ date('Y-m-d') }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nominal Santunan (Rp)</label>
                        <input type="number" name="nominal_santunan" value="1000000" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Ahli Waris / Kontak Penerima</label>
                    <input type="text" name="ahli_waris" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Catatan Tambahan</label>
                    <textarea name="keterangan" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold"></textarea>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-rukem').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl font-black text-xs uppercase shadow-md">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
