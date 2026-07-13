<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Modul Iuran Warga Bulanan') }}
            </h2>
            @if(Auth::user()->role <= 3)
                <button onclick="document.getElementById('modal-iuran').classList.remove('hidden')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Buat Tagihan Iuran
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
                        <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest block mb-1">Total Iuran Terkumpul (Tahun {{ $tahun }})</span>
                        <h3 class="text-3xl font-black text-amber-400">Rp {{ number_format($totalTerkumpul, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-7 border border-amber-100 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest block mb-1">Iuran Menunggu Verifikasi</span>
                        <h3 class="text-3xl font-black text-amber-700">Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Daftar Status Iuran Warga</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">Warga</th>
                                <th class="py-4 px-6">Periode</th>
                                <th class="py-4 px-6 text-right">Nominal</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Aksi Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($iuranList as $iuran)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">
                                        {{ $iuran->warga ? $iuran->warga->nama_lengkap : 'Warga Non-Linked' }}
                                    </td>
                                    <td class="py-4 px-6 font-bold text-slate-600 text-xs">{{ $iuran->bulan_nama }} {{ $iuran->tahun }}</td>
                                    <td class="py-4 px-6 text-right font-black text-slate-800 text-xs">Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if($iuran->status == 'paid')
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-lg uppercase">LUNAS</span>
                                        @elseif($iuran->status == 'pending')
                                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-lg uppercase">MENUNGGU VERIFIKASI</span>
                                        @else
                                            <span class="px-3 py-1 bg-rose-100 text-rose-800 text-[10px] font-black rounded-lg uppercase">BELUM BAYAR</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex justify-center gap-2">
                                            @if($iuran->status != 'paid')
                                                @if(Auth::user()->role <= 3 && $iuran->status == 'pending')
                                                    <form method="POST" action="{{ route('iuran.verify', $iuran->id) }}">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1 bg-emerald-600 text-white font-bold text-xs rounded-lg shadow">Verifikasi & Kas</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('iuran.pay', $iuran->id) }}">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1 bg-indigo-600 text-white font-bold text-xs rounded-lg shadow">Setor / Bayar</button>
                                                    </form>
                                                @endif
                                            @endif

                                            @if(Auth::user()->role <= 3)
                                                <form method="POST" action="{{ route('iuran.destroy', $iuran->id) }}" onsubmit="return confirm('Hapus tagihan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs rounded-lg transition">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada data iuran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $iuranList->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Buat Tagihan -->
    @if(Auth::user()->role <= 3)
        <div id="modal-iuran" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Buat Tagihan Iuran</h3>
                    <button onclick="document.getElementById('modal-iuran').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('iuran.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Pilih Warga</label>
                        <select name="warga_id" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                            @foreach($wargaList as $warga)
                                <option value="{{ $warga->id }}">{{ $warga->nama_lengkap }} ({{ $warga->alamat }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Bulan</label>
                            <select name="bulan" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                                @for($b=1; $b<=12; $b++)
                                    <option value="{{ $b }}" {{ date('n') == $b ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $b)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tahun</label>
                            <input type="number" name="tahun" value="{{ date('Y') }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nominal Tagihan (Rp)</label>
                        <input type="number" name="nominal" value="50000" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>

                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-iuran').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-md">Simpan Tagihan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
