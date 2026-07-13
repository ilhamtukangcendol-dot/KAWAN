<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                    {{ __('Database & Kartu Keluarga (KK) Warga RT') }}
                </h2>
                <p class="text-xs font-bold text-slate-400 mt-1">Direktori kependudukan terkelompok berdasarkan Kepala Keluarga</p>
            </div>
            @if(Auth::user()->role <= 2)
                <button onclick="openModalTambah('')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg hover:scale-105 transition">
                    + Tambah Anggota / KK Baru
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Metric Stats Summary Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 rounded-3xl p-6 text-white shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest block mb-1">Total Kepala Keluarga (KK)</span>
                        <h3 class="text-3xl font-black text-amber-400">{{ $totalKK }} KK</h3>
                    </div>
                    <span class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-2xl">🏡</span>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Total Jiwa Terdaftar</span>
                        <h3 class="text-3xl font-black text-indigo-600">{{ $totalWarga }} Orang</h3>
                    </div>
                    <span class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl">👥</span>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Rata-rata Anggota / KK</span>
                        <h3 class="text-3xl font-black text-emerald-600">{{ $totalKK > 0 ? number_format($totalWarga / $totalKK, 1) : 0 }} Jiwa</h3>
                    </div>
                    <span class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl">📊</span>
                </div>
            </div>

            <!-- Filter, Search & View Switcher Bar -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                <form method="GET" action="{{ route('data-warga.index') }}" class="flex-grow flex gap-3">
                    <input type="hidden" name="view" value="{{ $viewMode }}">
                    <div class="relative w-full">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama warga, NIK, No. KK, atau alamat..." class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-xs py-3 px-4 font-semibold text-slate-700">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-black text-white text-xs font-black uppercase tracking-wider rounded-2xl transition">Cari</button>
                </form>

                <!-- Toggle Buttons Cards vs Table -->
                <div class="flex bg-slate-100 p-1.5 rounded-2xl shrink-0">
                    <a href="{{ route('data-warga.index', ['view' => 'cards', 'search' => $search]) }}" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition {{ $viewMode == 'cards' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:text-slate-900' }}">
                        🏡 Kartu Keluarga (KK)
                    </a>
                    <a href="{{ route('data-warga.index', ['view' => 'table', 'search' => $search]) }}" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition {{ $viewMode == 'table' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:text-slate-900' }}">
                        📋 Tabel Individu
                    </a>
                </div>
            </div>

            <!-- MODE 1: FAMILY CARDS VIEW (GROUPED BY KEPALA KELUARGA / KK WITH PAGINATION) -->
            @if($viewMode == 'cards')
                <div class="space-y-6">
                    @forelse($familyGroups as $noKK => $anggota)
                        @php
                            $kepala = $anggota->firstWhere('status_keluarga', 'Kepala Keluarga') ?? $anggota->first();
                        @endphp
                        
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                            <!-- Header Kartu Keluarga -->
                            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 text-white flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 bg-amber-400 text-slate-900 font-black text-[10px] uppercase rounded-lg shadow">KARTU KELUARGA</span>
                                        <span class="text-xs font-mono font-bold text-slate-300">NO. KK: {{ $noKK }}</span>
                                    </div>
                                    <h3 class="text-xl font-black text-white tracking-tight">
                                        Keluarga {{ $kepala->nama_lengkap }}
                                    </h3>
                                    <p class="text-xs text-slate-300 font-medium">📍 {{ $kepala->alamat ?? 'RT 01 / RW 05' }} &bull; Status Tempat Tinggal: <span class="text-amber-300 font-bold">{{ $kepala->status_tinggal }}</span></p>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="px-4 py-2 bg-white/10 rounded-2xl border border-white/20 text-xs font-black uppercase tracking-wider">
                                        👨‍👩‍👧‍👦 {{ $anggota->count() }} Anggota Keluarga
                                    </span>
                                    @if(Auth::user()->role <= 2)
                                        <button onclick="openModalTambah('{{ $noKK }}', '{{ addslashes($kepala->alamat) }}')" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow transition">
                                            + Tambah Anggota
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Daftar Anggota Keluarga -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                            <th class="py-4 px-6">Hubungan Keluarga</th>
                                            <th class="py-4 px-6">NIK</th>
                                            <th class="py-4 px-6">Nama Lengkap</th>
                                            <th class="py-4 px-6">Gender / Umur</th>
                                            <th class="py-4 px-6">Status Tinggal</th>
                                            @if(Auth::user()->role <= 2)
                                                <th class="py-4 px-6 text-center">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 text-sm">
                                        @foreach($anggota as $item)
                                            <tr class="hover:bg-slate-50/80 transition">
                                                <td class="py-4 px-6">
                                                    @if($item->status_keluarga == 'Kepala Keluarga')
                                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-lg uppercase">👑 KEPALA KELUARGA</span>
                                                    @elseif($item->status_keluarga == 'Istri')
                                                        <span class="px-2.5 py-1 bg-purple-100 text-purple-800 text-[10px] font-black rounded-lg uppercase">👩 ISTRI</span>
                                                    @elseif($item->status_keluarga == 'Anak')
                                                        <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-[10px] font-black rounded-lg uppercase">👦 ANAK</span>
                                                    @else
                                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-black rounded-lg uppercase">{{ strtoupper($item->status_keluarga) }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6 font-mono font-bold text-xs text-slate-700">{{ $item->nik }}</td>
                                                <td class="py-4 px-6 font-bold text-slate-800 text-xs">
                                                    {{ $item->nama_lengkap }}
                                                    @if($item->user)
                                                        <span class="ml-1 text-[9px] px-1.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded">Akun User</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6 font-semibold text-slate-600 text-xs">
                                                    {{ $item->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }} ({{ $item->umur }} Thn)
                                                </td>
                                                <td class="py-4 px-6 font-semibold text-slate-600 text-xs">
                                                    {{ $item->status_tinggal }}
                                                </td>
                                                @if(Auth::user()->role <= 2)
                                                    <td class="py-4 px-6 text-center">
                                                        <form method="POST" action="{{ route('data-warga.destroy', $item->id) }}" onsubmit="return confirm('Hapus anggota keluarga ini dari KK?');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs rounded-lg transition">Hapus</button>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-12 text-center rounded-3xl border border-slate-100 text-slate-400 font-bold text-xs">
                            Belum ada data Kartu Keluarga (KK) terdaftar.
                        </div>
                    @endforelse

                    <!-- Halaman Selanjutnya / Prev Pagination Navigation -->
                    <div class="p-4 border border-slate-100 bg-white rounded-3xl shadow-lg">
                        {{ $familyPaginated->links() }}
                    </div>
                </div>

            <!-- MODE 2: INDIVIDUAL TABLE VIEW -->
            @else
                <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="py-4 px-6">NIK / No. KK</th>
                                    <th class="py-4 px-6">Nama Lengkap</th>
                                    <th class="py-4 px-6">Status Keluarga</th>
                                    <th class="py-4 px-6">Gender / Umur</th>
                                    <th class="py-4 px-6">Status Tempat Tinggal</th>
                                    <th class="py-4 px-6">Alamat</th>
                                    @if(Auth::user()->role <= 2)
                                        <th class="py-4 px-6 text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                @forelse($wargaList as $warga)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="py-4 px-6">
                                            <p class="font-bold text-slate-800 text-xs">NIK: {{ $warga->nik }}</p>
                                            <p class="text-[10px] text-slate-400 font-semibold">KK: {{ $warga->no_kk }}</p>
                                        </td>
                                        <td class="py-4 px-6 font-bold text-slate-800 text-xs">{{ $warga->nama_lengkap }}</td>
                                        <td class="py-4 px-6 font-bold text-xs text-indigo-700 uppercase">{{ $warga->status_keluarga }}</td>
                                        <td class="py-4 px-6 font-semibold text-slate-600 text-xs">{{ $warga->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }} ({{ $warga->umur }} Thn)</td>
                                        <td class="py-4 px-6 font-semibold text-slate-600 text-xs">{{ $warga->status_tinggal }}</td>
                                        <td class="py-4 px-6 font-semibold text-slate-600 text-xs">{{ $warga->alamat }}</td>
                                        @if(Auth::user()->role <= 2)
                                            <td class="py-4 px-6 text-center">
                                                <form method="POST" action="{{ route('data-warga.destroy', $warga->id) }}" onsubmit="return confirm('Hapus data warga ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs rounded-lg transition">Hapus</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-slate-400 font-bold text-xs">Belum ada data warga terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100 bg-slate-50">
                        {{ $wargaList->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Modal Form Tambah Data Warga / Anggota KK -->
    @if(Auth::user()->role <= 2)
        <div id="modal-warga" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-lg w-full shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Tambah Anggota / Data Warga Baru</h3>
                    <button onclick="document.getElementById('modal-warga').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('data-warga.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="view" value="{{ $viewMode }}">

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nomor KK (16 Digit)</label>
                            <input type="text" id="input_no_kk" name="no_kk" maxlength="16" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">NIK (16 Digit)</label>
                            <input type="text" name="nik" maxlength="16" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Hubungan Keluarga</label>
                            <select name="status_keluarga" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-2 font-semibold">
                                <option value="Kepala Keluarga">Kepala Keluarga</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Orang Tua">Orang Tua</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-2 font-semibold">
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Umur (Tahun)</label>
                            <input type="number" name="umur" min="0" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-3 font-semibold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Status Tempat Tinggal</label>
                        <select name="status_tinggal" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold">
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Kontrak">Kontrak / Sewa</option>
                            <option value="Kos">Kos</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Alamat Lengkap RT</label>
                        <textarea id="input_alamat" name="alamat" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold"></textarea>
                    </div>

                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-warga').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase shadow-md">Simpan Warga</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openModalTambah(noKK = '', alamat = '') {
                if (noKK) {
                    document.getElementById('input_no_kk').value = noKK;
                }
                if (alamat) {
                    document.getElementById('input_alamat').value = alamat;
                }
                document.getElementById('modal-warga').classList.remove('hidden');
            }
        </script>
    @endif
</x-app-layout>
