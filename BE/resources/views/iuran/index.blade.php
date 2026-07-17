<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Modul Iuran Warga Bulanan') }}
            </h2>
            <div class="flex flex-wrap gap-2">
                @if(Auth::user()->role <= 3)
                    <button onclick="openIuranModal('tab-satuan')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                        + Buat Tagihan Iuran
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-bold">
                    ❌ {{ session('error') }}
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
                                <th class="py-4 px-6 text-center">Status & Metode</th>
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
                                            @if($iuran->metode_pembayaran)
                                                <p class="text-[9px] text-slate-500 font-bold mt-1 uppercase">💳 {{ $iuran->metode_pembayaran == 'qris' ? 'QRIS' : ($iuran->metode_pembayaran == 'transfer' ? 'ATM/Transfer' : 'Tunai') }}</p>
                                            @endif
                                        @elseif($iuran->status == 'pending')
                                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-lg uppercase">VERIFIKASI</span>
                                            @if($iuran->metode_pembayaran)
                                                <p class="text-[9px] text-slate-500 font-bold mt-1 uppercase">💳 {{ $iuran->metode_pembayaran == 'qris' ? 'QRIS' : ($iuran->metode_pembayaran == 'transfer' ? 'ATM/Transfer' : 'Tunai') }}</p>
                                            @endif
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
                                                    <button onclick="openBayarModal({{ $iuran->id }}, {{ $iuran->nominal }}, '{{ $iuran->bulan_nama }}', {{ $iuran->tahun }})" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-lg shadow transition">Setor / Bayar</button>
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

    {{-- ======= UNIFIED IURAN MODAL (TABBED) ======= --}}
    @if(Auth::user()->role <= 3)
    <div id="modal-iuran" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center hidden p-4" style="z-index:9999;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">

            {{-- Modal Header with gradient --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-7 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-white text-base uppercase tracking-wide">Manajemen Tagihan Iuran</h3>
                        <p class="text-indigo-200 text-[10px] font-semibold">Pilih jenis tagihan yang ingin dibuat</p>
                    </div>
                </div>
                <button type="button" onclick="closeIuranModal()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white font-black text-lg transition">&times;</button>
            </div>

            {{-- Tab Navigation --}}
            <div class="flex border-b border-slate-100 px-7 pt-5 gap-1">
                <button id="btn-tab-satuan" type="button" onclick="switchTab('tab-satuan')"
                    class="tab-btn flex items-center gap-2 px-4 py-2.5 rounded-t-xl text-xs font-black uppercase tracking-wider transition border-b-2 border-indigo-600 text-indigo-600 bg-indigo-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Tagihan Satuan
                </button>
                @if(in_array(Auth::user()->role, [1, 3]))
                <button id="btn-tab-massal" type="button" onclick="switchTab('tab-massal')"
                    class="tab-btn flex items-center gap-2 px-4 py-2.5 rounded-t-xl text-xs font-black uppercase tracking-wider transition border-b-2 border-transparent text-slate-400 bg-transparent hover:bg-slate-50 hover:text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    ⚡ Tagih Sekaligus
                    <span class="ml-0.5 px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-full">MASSAL</span>
                </button>
                @endif
            </div>

            {{-- TAB 1: Tagihan Satuan --}}
            <div id="tab-satuan" class="px-7 py-5 space-y-4">
                <form method="POST" action="{{ route('iuran.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">👤 Pilih Warga</label>
                        <select name="warga_id" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            @foreach($wargaList as $warga)
                                <option value="{{ $warga->id }}">{{ $warga->nama_lengkap }} ({{ $warga->alamat }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">📅 Bulan</label>
                            <select name="bulan" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                @for($b=1; $b<=12; $b++)
                                    <option value="{{ $b }}" {{ date('n') == $b ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $b)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">🗓️ Tahun</label>
                            <input type="number" name="tahun" value="{{ date('Y') }}" required
                                class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">💰 Nominal Tagihan (Rp)</label>
                        <input type="number" name="nominal" value="{{ \App\Models\Setting::get('nominal_iuran', '50000') }}" required
                            class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div class="pt-1 flex justify-end gap-2">
                        <button type="button" onclick="closeIuranModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition">Batal</button>
                        <button type="submit" class="flex items-center gap-1.5 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-md transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Simpan Tagihan
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB 2: Tagih Sekaligus (Massal) - hanya role 1 & 3 --}}
            @if(in_array(Auth::user()->role, [1, 3]))
            <div id="tab-massal" class="px-7 py-5 space-y-4 hidden">
                <form id="form-mass-iuran" method="POST" action="{{ route('iuran.store_mass') }}" class="space-y-4" onsubmit="return validateMassIuran()">
                    @csrf

                    {{-- Banner info massal --}}
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl p-4 flex items-start gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-white font-black text-xs uppercase tracking-wider">Tagihan untuk Seluruh Warga</p>
                            <p class="text-emerald-100 text-[10px] font-semibold mt-0.5">Pilih bulan & nominal, lalu sistem akan membuat tagihan otomatis untuk semua warga. Bulan yang sudah ada tagihannya akan dilewati. Pengumuman otomatis akan diterbitkan.</p>
                        </div>
                    </div>

                    {{-- Tahun & Nominal --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">🗓️ Tahun</label>
                            <input type="number" name="tahun" id="mass_tahun" value="{{ date('Y') }}" min="2020" max="2099" required
                                class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-sm py-3 px-4 font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">💰 Nominal / Bulan (Rp)</label>
                            <input type="number" name="nominal" id="mass_nominal" value="{{ \App\Models\Setting::get('nominal_iuran', '50000') }}" min="0" required
                                class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-sm py-3 px-4 font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        </div>
                    </div>

                    {{-- Pilih Bulan --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">📆 Pilih Bulan yang Ditagih</label>
                            <div class="flex gap-2">
                                <button type="button" onclick="selectAllMonths()"
                                    class="flex items-center gap-1 text-[10px] font-black text-emerald-600 hover:text-emerald-800 uppercase tracking-widest transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Semua
                                </button>
                                <span class="text-slate-200">|</span>
                                <button type="button" onclick="clearAllMonths()"
                                    class="flex items-center gap-1 text-[10px] font-black text-rose-500 hover:text-rose-700 uppercase tracking-widest transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Kosongkan
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-1.5 bg-slate-50 rounded-2xl p-3 border border-slate-100">
                            @php
                                $bulanMassal = [
                                    1=>'🌸 Januari', 2=>'🌷 Februari', 3=>'🍃 Maret',
                                    4=>'🌻 April', 5=>'🌞 Mei', 6=>'⛅ Juni',
                                    7=>'🌈 Juli', 8=>'🎋 Agustus', 9=>'🍂 September',
                                    10=>'🍁 Oktober', 11=>'🌙 November', 12=>'❄️ Desember'
                                ];
                            @endphp
                            @foreach($bulanMassal as $num => $label)
                                <label class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 cursor-pointer hover:bg-emerald-50 hover:text-emerald-700 rounded-xl px-2 py-1.5 transition group">
                                    <input type="checkbox" name="bulan[]" value="{{ $num }}"
                                        class="mass-bulan-checkbox w-3.5 h-3.5 rounded accent-emerald-500 cursor-pointer">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        <p id="mass-bulan-error" class="text-[10px] text-rose-500 font-bold mt-1.5 hidden">⚠ Pilih minimal satu bulan terlebih dahulu.</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" onclick="closeIuranModal()"
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition">Batal</button>
                        <button type="submit"
                            class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-md transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Terbitkan Tagihan
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>{{-- end card --}}
    </div>{{-- end overlay --}}
    @endif

    {{-- ======= BAYAR IURAN MODAL (QRIS & ATM/TRANSFER) ======= --}}
    <div id="modal-bayar-iuran" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center hidden p-4" style="z-index:9999;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-850 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="font-black text-white text-xs uppercase tracking-wide">Pilih Metode & Bayar Iuran</h3>
                </div>
                <button type="button" onclick="closeBayarModal()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white font-black text-lg transition">&times;</button>
            </div>
            
            <form id="form-bayar-iuran" method="POST" action="" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-xs font-semibold text-slate-700 space-y-1">
                    <div class="flex justify-between">
                        <span>Periode Tagihan:</span>
                        <span id="bayar-periode" class="font-bold text-slate-900"></span>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span>Nominal:</span>
                        <span id="bayar-nominal" class="font-bold text-indigo-700"></span>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">💳 Pilih Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="bayar-metode" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400" onchange="toggleMetodeDetails(this.value)">
                        <option value="cash">💵 Tunai / Cash (Ke Pengurus RT)</option>
                        <option value="transfer">🏦 ATM / Transfer Bank</option>
                        <option value="qris">📱 Pembayaran via QRIS</option>
                    </select>
                </div>

                {{-- ATM / Transfer Details --}}
                <div id="details-transfer" class="hidden bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Rekening Bank RT</p>
                    <div class="text-xs text-slate-700 font-semibold space-y-1">
                        <div class="flex justify-between">
                            <span>Bank:</span>
                            <span class="font-bold text-slate-900">Bank Mandiri</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Nomor Rekening:</span>
                            <span class="font-bold text-slate-900 font-mono select-all">123-456-7890</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Atas Nama:</span>
                            <span class="font-bold text-slate-900">KAS RT 01</span>
                        </div>
                    </div>
                    <p class="text-[9px] text-amber-600 font-bold mt-1">⚠️ Harap transfer tepat sesuai nominal tagihan.</p>
                </div>

                {{-- QRIS Details --}}
                <div id="details-qris" class="hidden bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col items-center gap-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Scan QRIS Untuk Membayar</p>
                    <img src="{{ asset('qris_mockup.png') }}" class="w-48 h-auto rounded-xl shadow-md border border-slate-100">
                    <p class="text-[9px] text-slate-500 font-semibold text-center">Bisa scan menggunakan GoPay, OVO, Dana, LinkAja, BCA Mobile, dll.</p>
                </div>

                {{-- Bukti Bayar --}}
                <div id="wrapper-bukti-bayar" class="hidden">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">📷 Upload Bukti Transfer / Transaksi</label>
                    <input type="file" name="bukti_bayar" id="bukti_bayar_input" class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-2 px-3 font-semibold text-slate-700 focus:outline-none">
                    <p class="text-[9px] text-slate-400 font-medium mt-1">Format gambar (JPG, PNG, max 2MB)</p>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeBayarModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-md transition">Kirim Pembayaran</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openIuranModal(tab) {
        document.getElementById('modal-iuran').classList.remove('hidden');
        switchTab(tab);
    }
    function closeIuranModal() {
        document.getElementById('modal-iuran').classList.add('hidden');
    }
    function switchTab(tab) {
        // Hide all tabs
        ['tab-satuan','tab-massal'].forEach(t => {
            const el = document.getElementById(t);
            if (el) el.classList.add('hidden');
        });
        // Reset all tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-indigo-600','text-indigo-600','bg-indigo-50','border-emerald-500','text-emerald-600','bg-emerald-50');
            btn.classList.add('border-transparent','text-slate-400','bg-transparent');
        });
        // Show selected tab
        const activeTab = document.getElementById(tab);
        if (activeTab) activeTab.classList.remove('hidden');
        // Style active button
        const activeBtn = document.getElementById('btn-' + tab);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent','text-slate-400','bg-transparent');
            if (tab === 'tab-satuan') {
                activeBtn.classList.add('border-indigo-600','text-indigo-600','bg-indigo-50');
            } else {
                activeBtn.classList.add('border-emerald-500','text-emerald-600','bg-emerald-50');
            }
        }
    }
    function selectAllMonths() {
        document.querySelectorAll('.mass-bulan-checkbox').forEach(cb => cb.checked = true);
    }
    function clearAllMonths() {
        document.querySelectorAll('.mass-bulan-checkbox').forEach(cb => cb.checked = false);
    }
    function validateMassIuran() {
        const checked = document.querySelectorAll('.mass-bulan-checkbox:checked').length;
        if (checked === 0) {
            document.getElementById('mass-bulan-error').classList.remove('hidden');
            return false;
        }
        document.getElementById('mass-bulan-error').classList.add('hidden');
        return confirm('Yakin ingin menerbitkan tagihan untuk ' + checked + ' bulan bagi seluruh warga?');
    }

    // Payment Modal Functions
    function openBayarModal(id, nominal, bulan, tahun) {
        const form = document.getElementById('form-bayar-iuran');
        form.action = `/iuran/${id}/bayar`;
        
        document.getElementById('bayar-periode').innerText = bulan + ' ' + tahun;
        document.getElementById('bayar-nominal').innerText = 'Rp ' + Number(nominal).toLocaleString('id-ID');
        
        document.getElementById('bayar-metode').value = 'cash';
        toggleMetodeDetails('cash');

        document.getElementById('modal-bayar-iuran').classList.remove('hidden');
    }
    
    function closeBayarModal() {
        document.getElementById('modal-bayar-iuran').classList.add('hidden');
    }
    
    function toggleMetodeDetails(metode) {
        const transferDiv = document.getElementById('details-transfer');
        const qrisDiv = document.getElementById('details-qris');
        const buktiDiv = document.getElementById('wrapper-bukti-bayar');
        const fileInput = document.getElementById('bukti_bayar_input');

        transferDiv.classList.add('hidden');
        qrisDiv.classList.add('hidden');
        buktiDiv.classList.add('hidden');
        fileInput.removeAttribute('required');

        if (metode === 'transfer') {
            transferDiv.classList.remove('hidden');
            buktiDiv.classList.remove('hidden');
            fileInput.setAttribute('required', 'required');
        } else if (metode === 'qris') {
            qrisDiv.classList.remove('hidden');
            buktiDiv.classList.remove('hidden');
            fileInput.setAttribute('required', 'required');
        }
    }

    // Close modal on backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal-iuran');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeIuranModal();
            });
        }
        const bayarModal = document.getElementById('modal-bayar-iuran');
        if (bayarModal) {
            bayarModal.addEventListener('click', function(e) {
                if (e.target === bayarModal) closeBayarModal();
            });
        }
    });
    </script>
</x-app-layout>
