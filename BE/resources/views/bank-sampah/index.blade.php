<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Bank Sampah RT') }}
            </h2>
            @if(Auth::user()->role <= 3)
                <button onclick="document.getElementById('modal-sampah').classList.remove('hidden')" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Setor Sampah Warga
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

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-emerald-800 to-teal-900 rounded-3xl p-7 text-white shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-emerald-200 uppercase tracking-widest block mb-1">Total Berat Sampah Terkumpul</span>
                        <h3 class="text-3xl font-black text-amber-400">{{ number_format($totalTerkumpulKg, 1, ',', '.') }} Kg</h3>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-7 border border-emerald-100 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest block mb-1">Total Saldo Terbuku Warga</span>
                        <h3 class="text-3xl font-black text-emerald-700">Rp {{ number_format($totalNilaiRupiah, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <!-- Tabel Setoran Sampah -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Jurnal Setoran Bank Sampah RT</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-50/50 text-[10px] font-black text-emerald-800 uppercase tracking-widest border-b border-emerald-100">
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">Nasabah Warga</th>
                                <th class="py-4 px-6">Jenis Sampah</th>
                                <th class="py-4 px-6 text-right">Berat (Kg)</th>
                                <th class="py-4 px-6 text-right">Total Tabungan (Rp)</th>
                                @if(Auth::user()->role <= 3)
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($setoranList as $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-700 text-xs">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $item->user ? $item->user->name : 'Warga' }}</td>
                                    <td class="py-4 px-6 font-semibold text-slate-700 text-xs">{{ $item->jenis_sampah }}</td>
                                    <td class="py-4 px-6 text-right font-bold text-slate-800 text-xs">{{ $item->berat_kg }} Kg</td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-600 text-xs">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    @if(Auth::user()->role <= 3)
                                        <td class="py-4 px-6 text-center">
                                            <form method="POST" action="{{ route('bank-sampah.destroy', $item->id) }}" onsubmit="return confirm('Hapus setoran ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs rounded-lg transition">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada transaksi bank sampah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $setoranList->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Input -->
    @if(Auth::user()->role <= 3)
        <div id="modal-sampah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Input Setoran Bank Sampah</h3>
                    <button onclick="document.getElementById('modal-sampah').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('bank-sampah.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nasabah Warga</label>
                        <select name="user_id" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Jenis Sampah Daur Ulang</label>
                        <input type="text" name="jenis_sampah" placeholder="Contoh: Botol Plastik PET / Kardus Bekas" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Berat Total (Kg)</label>
                            <input type="number" step="0.1" name="berat_kg" required min="0.1" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Harga / Kg (Rp)</label>
                            <input type="number" name="harga_per_kg" value="4000" required min="0" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal Setor</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-sampah').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Simpan Setoran</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
