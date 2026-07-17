<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Modul Layanan Posyandu Balita & Lansia') }}
            </h2>
            @if(Auth::user()->role <= 3)
                <button onclick="document.getElementById('modal-posyandu').classList.remove('hidden')" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                    + Catat Pemeriksaan Kesehatan
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

            <!-- Tabs -->
            <div class="flex gap-3">
                <a href="{{ route('posyandu.index', ['kategori' => 'balita']) }}" class="px-5 py-2.5 rounded-2xl font-black text-xs uppercase tracking-wider transition {{ $kategori == 'balita' ? 'bg-emerald-600 text-white shadow-lg' : 'bg-white text-slate-700 border border-slate-100' }}">
                    👶 Posyandu Balita
                </a>
                <a href="{{ route('posyandu.index', ['kategori' => 'lansia']) }}" class="px-5 py-2.5 rounded-2xl font-black text-xs uppercase tracking-wider transition {{ $kategori == 'lansia' ? 'bg-emerald-600 text-white shadow-lg' : 'bg-white text-slate-700 border border-slate-100' }}">
                    👴 Posyandu Lansia
                </a>
            </div>

            <!-- Stats Info -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pemeriksaan</p>
                    <p class="text-2xl font-black text-emerald-600 mt-1">{{ $pemeriksaanList->total() }}</p>
                    <p class="text-[10px] text-slate-400 font-bold mt-1">{{ ucfirst($kategori) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Warga Terdaftar</p>
                    <p class="text-2xl font-black text-indigo-600 mt-1">{{ $allWarga->count() }}</p>
                    <p class="text-[10px] text-slate-400 font-bold mt-1">Semua warga</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Warga {{ ucfirst($kategori) }}</p>
                    <p class="text-2xl font-black text-amber-600 mt-1">{{ $wargaList->count() }}</p>
                    <p class="text-[10px] text-slate-400 font-bold mt-1">{{ $kategori == 'balita' ? '≤5 Tahun' : '≥60 Tahun' }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bulan Ini</p>
                    <p class="text-2xl font-black text-rose-600 mt-1">{{ $pemeriksaanList->filter(function($p){ return \Carbon\Carbon::parse($p->tanggal_periksa)->isCurrentMonth(); })->count() }}</p>
                    <p class="text-[10px] text-slate-400 font-bold mt-1">{{ now()->format('M Y') }}</p>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-50/50 text-[10px] font-black text-emerald-800 uppercase tracking-widest border-b border-emerald-100">
                                <th class="py-4 px-6">Tanggal Periksa</th>
                                <th class="py-4 px-6">Nama Pasien / Warga</th>
                                <th class="py-4 px-6">NIK Warga</th>
                                <th class="py-4 px-6">Umur</th>
                                <th class="py-4 px-6 text-right">Berat Badan (Kg)</th>
                                <th class="py-4 px-6 text-right">Tinggi / Panjang (Cm)</th>
                                <th class="py-4 px-6">Catatan Medis Kader</th>
                                <th class="py-4 px-6 text-center">Petugas</th>
                                @if(Auth::user()->role <= 3)
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($pemeriksaanList as $p)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-700 text-xs">{{ \Carbon\Carbon::parse($p->tanggal_periksa)->format('d M Y') }}</td>
                                    <td class="py-4 px-6">
                                        <p class="font-black text-slate-800 text-xs">{{ $p->nama_pasien }}</p>
                                        @if($p->nama_pendaftar)
                                            <p class="text-[10px] text-slate-500 font-medium mt-0.5">Pendaftar/Wali: {{ $p->nama_pendaftar }}</p>
                                        @endif
                                        @if($p->warga)
                                            <p class="text-[10px] text-emerald-600 font-bold mt-0.5">✓ Data Warga RT</p>
                                        @else
                                            <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Input Manual</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs text-slate-500 font-semibold">
                                        {{ $p->warga ? $p->warga->nik : '-' }}
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-slate-600 text-xs">{{ $p->umur_bulan_atau_tahun }} {{ $p->kategori == 'balita' ? 'Bulan' : 'Tahun' }}</td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-600 text-xs">{{ $p->bb_kg }} Kg</td>
                                    <td class="py-4 px-6 text-right font-black text-slate-700 text-xs">{{ $p->tb_cm }} Cm</td>
                                    <td class="py-4 px-6 font-medium text-slate-600 text-xs">{{ $p->catatan ?? '-' }}</td>
                                    <td class="py-4 px-6 text-center font-semibold text-slate-600 text-xs">{{ $p->petugas }}</td>
                                    @if(Auth::user()->role <= 3)
                                        <td class="py-4 px-6 text-center">
                                            <form method="POST" action="{{ route('posyandu.destroy', $p->id) }}" onsubmit="return confirm('Hapus catatan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs rounded-lg transition">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="text-4xl">🏥</span>
                                            <p class="text-slate-400 font-bold text-xs">Belum ada pemeriksaan Posyandu {{ $kategori }}.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pemeriksaanList->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $pemeriksaanList->appends(['kategori' => $kategori])->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Modal Form Posyandu -->
    @if(Auth::user()->role <= 3)
        <div id="modal-posyandu" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
            <div class="bg-white rounded-3xl p-7 max-w-lg w-full shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Pemeriksaan Posyandu Baru</h3>
                    <button onclick="document.getElementById('modal-posyandu').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
                </div>
                <form method="POST" action="{{ route('posyandu.store') }}" class="space-y-4" id="form-posyandu">
                    @csrf

                    <!-- Kategori -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Kategori Posyandu</label>
                        <select name="kategori" id="posyandu-kategori" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:border-emerald-400 focus:outline-none" onchange="filterWargaPosyandu(this.value)">
                            <option value="balita" {{ $kategori == 'balita' ? 'selected' : '' }}>👶 Balita (≤5 Tahun)</option>
                            <option value="lansia" {{ $kategori == 'lansia' ? 'selected' : '' }}>👴 Lansia (≥60 Tahun)</option>
                        </select>
                    </div>

                    <!-- Nama Pendaftar / Orang Tua / Wali (Pencarian & Input Otomatis) -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Pendaftar / Orang Tua / Wali</label>
                        <div class="relative">
                            <input type="text" name="nama_pendaftar" id="posyandu-nama-pendaftar" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:border-emerald-400 focus:outline-none" placeholder="🔍 Ketik nama pendaftar/orang tua..." autocomplete="off" oninput="searchPendaftarPosyandu(this.value)" onfocus="searchPendaftarPosyandu(this.value)" onclick="searchPendaftarPosyandu(this.value)">
                            <input type="hidden" name="pendaftar_warga_id" id="posyandu-pendaftar-warga-id">
                            <!-- Dropdown hasil pencarian -->
                            <div id="posyandu-pendaftar-dropdown" class="absolute z-50 w-full bg-white border border-slate-200 rounded-2xl shadow-xl mt-1 hidden max-h-48 overflow-y-auto">
                                <!-- Diisi via JS -->
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-semibold mt-1">Pilih dari daftar warga RT (orang tua / wali), atau ketik nama baru</p>
                    </div>

                    <!-- Nama Pasien / Pencarian Warga -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Pasien / Warga</label>
                        <div class="relative">
                            <input type="text" name="nama_pasien" id="posyandu-nama" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:border-emerald-400 focus:outline-none" placeholder="🔍 Ketik nama pasien untuk mencari..." autocomplete="off" oninput="searchWargaPosyandu(this.value)" onfocus="searchWargaPosyandu(this.value)" onclick="searchWargaPosyandu(this.value)">
                            <input type="hidden" name="warga_id" id="posyandu-warga-id">
                            <!-- Dropdown hasil pencarian -->
                            <div id="posyandu-dropdown" class="absolute z-50 w-full bg-white border border-slate-200 rounded-2xl shadow-xl mt-1 hidden max-h-48 overflow-y-auto">
                                <!-- Diisi via JS -->
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-semibold mt-1">Daftar nama pasien sesuai KK pendaftar (umur akan terisi otomatis), atau ketik nama baru</p>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Umur</label>
                            <input type="number" name="umur_bulan_atau_tahun" id="posyandu-umur" min="0" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-3 font-semibold focus:border-emerald-400 focus:outline-none" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">BB (Kg)</label>
                            <input type="number" step="0.1" name="bb_kg" required min="0" class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-3 font-semibold focus:border-emerald-400 focus:outline-none" placeholder="0.0">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">TB (Cm)</label>
                            <input type="number" step="0.1" name="tb_cm" required min="0" class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-3 font-semibold focus:border-emerald-400 focus:outline-none" placeholder="0.0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Catatan Medis / Pemberian Gizi</label>
                        <textarea name="catatan" rows="2" class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:border-emerald-400 focus:outline-none" placeholder="Contoh: Pertumbuhan normal, imunisasi vitamin A lengkap..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal Periksa</label>
                            <input type="date" name="tanggal_periksa" value="{{ date('Y-m-d') }}" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:border-emerald-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nama Petugas / Kader</label>
                            <input type="text" name="petugas" value="Kader Posyandu RT" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:border-emerald-400 focus:outline-none">
                        </div>
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-posyandu').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-black text-xs uppercase shadow-md hover:bg-emerald-700 transition">Simpan Catatan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        // Data warga dari server (semua warga)
        const allWargaData = @json($allWarga);
        let currentKategori = '{{ $kategori }}';
        let selectedPendaftarNoKK = null;

        function filterWargaPosyandu(kategori) {
            currentKategori = kategori;
            // Clear pilihan sebelumnya
            document.getElementById('posyandu-warga-id').value = '';
            document.getElementById('posyandu-nama').value = '';
            document.getElementById('posyandu-umur').value = '';
            document.getElementById('posyandu-pendaftar-warga-id').value = '';
            document.getElementById('posyandu-nama-pendaftar').value = '';
            document.getElementById('posyandu-dropdown').classList.add('hidden');
            document.getElementById('posyandu-pendaftar-dropdown').classList.add('hidden');
            selectedPendaftarNoKK = null;
        }

        function getFilteredWarga() {
            // Jika pendaftar sudah dipilih, filter hanya warga yang satu KK
            let list = allWargaData;
            if (selectedPendaftarNoKK) {
                list = allWargaData.filter(w => w.no_kk === selectedPendaftarNoKK);
            }

            return [...list].sort((a, b) => {
                const aMatch = (currentKategori === 'balita' && a.umur <= 5) || (currentKategori === 'lansia' && a.umur >= 60);
                const bMatch = (currentKategori === 'balita' && b.umur <= 5) || (currentKategori === 'lansia' && b.umur >= 60);
                if (aMatch && !bMatch) return -1;
                if (!aMatch && bMatch) return 1;
                return a.nama_lengkap.localeCompare(b.nama_lengkap);
            });
        }

        function searchWargaPosyandu(query) {
            const dropdown = document.getElementById('posyandu-dropdown');
            const filtered = getFilteredWarga();

            const results = query.length >= 1
                ? filtered.filter(w =>
                    w.nama_lengkap.toLowerCase().includes(query.toLowerCase()) ||
                    (w.nik && w.nik.includes(query))
                  )
                : filtered;

            if (results.length === 0) {
                dropdown.innerHTML = '<div class="px-4 py-3 text-xs text-slate-400 font-semibold">Tidak ada warga ditemukan</div>';
                dropdown.classList.remove('hidden');
                return;
            }

            dropdown.innerHTML = results.slice(0, 30).map(w => {
                const isEligible = (currentKategori === 'balita' && w.umur <= 5) || (currentKategori === 'lansia' && w.umur >= 60);
                const badgeColor = isEligible ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-500 border border-slate-100';
                const categoryLabel = isEligible ? (currentKategori === 'balita' ? '👶 Balita' : '👴 Lansia') : 'Umum';

                return `
                    <div class="px-4 py-2.5 hover:bg-emerald-50 cursor-pointer border-b border-slate-50 last:border-0 flex justify-between items-center"
                         onclick="selectWargaPosyandu(${w.id}, '${w.nama_lengkap.replace(/'/g,"\\'")}', ${w.umur})">
                        <div>
                            <p class="text-xs font-black text-slate-800">${w.nama_lengkap}</p>
                            <p class="text-[10px] text-slate-400 font-semibold">${w.nik ?? '-'} • ${w.umur} Thn • ${w.jenis_kelamin == 'L' ? '♂ Laki-laki' : '♀ Perempuan'}</p>
                        </div>
                        <span class="text-[9px] font-black px-2 py-0.5 rounded-lg ${badgeColor}">${categoryLabel}</span>
                    </div>
                `;
            }).join('');
            dropdown.classList.remove('hidden');
        }

        function selectWargaPosyandu(id, nama, umur) {
            document.getElementById('posyandu-warga-id').value  = id;
            document.getElementById('posyandu-nama').value      = nama;
            document.getElementById('posyandu-umur').value      = umur;
            document.getElementById('posyandu-dropdown').classList.add('hidden');
        }

        function searchPendaftarPosyandu(query) {
            const dropdown = document.getElementById('posyandu-pendaftar-dropdown');
            
            const results = query.length >= 1
                ? allWargaData.filter(w =>
                    w.nama_lengkap.toLowerCase().includes(query.toLowerCase()) ||
                    (w.nik && w.nik.includes(query))
                  )
                : allWargaData;

            if (results.length === 0) {
                dropdown.innerHTML = '<div class="px-4 py-3 text-xs text-slate-400 font-semibold">Tidak ada warga ditemukan</div>';
                dropdown.classList.remove('hidden');
                return;
            }

            dropdown.innerHTML = results.slice(0, 30).map(w => `
                <div class="px-4 py-2.5 hover:bg-emerald-50 cursor-pointer border-b border-slate-50 last:border-0 flex justify-between items-center"
                     onclick="selectPendaftarPosyandu(${w.id}, '${w.nama_lengkap.replace(/'/g,"\\'")}', '${w.no_kk}')">
                    <div>
                        <p class="text-xs font-black text-slate-800">${w.nama_lengkap}</p>
                        <p class="text-[10px] text-slate-400 font-semibold">${w.nik ?? '-'} • KK: ${w.no_kk} • ${w.umur} Thn</p>
                    </div>
                    <span class="text-[9px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-lg">${w.status_keluarga}</span>
                </div>
            `).join('');
            dropdown.classList.remove('hidden');
        }

        function selectPendaftarPosyandu(id, nama, no_kk) {
            document.getElementById('posyandu-pendaftar-warga-id').value  = id;
            document.getElementById('posyandu-nama-pendaftar').value      = nama;
            document.getElementById('posyandu-pendaftar-dropdown').classList.add('hidden');
            
            // Set no_kk pendaftar yang terpilih
            selectedPendaftarNoKK = no_kk;

            // Reset nama pasien & umur karena harus memilih dari KK pendaftar yang baru
            document.getElementById('posyandu-warga-id').value = '';
            document.getElementById('posyandu-nama').value = '';
            document.getElementById('posyandu-umur').value = '';
            
            // Tampilkan list pasien keluarga secara otomatis untuk memudahkan user
            setTimeout(() => {
                searchWargaPosyandu('');
            }, 100);
        }

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('posyandu-dropdown');
            const input    = document.getElementById('posyandu-nama');
            if (dropdown && !dropdown.contains(e.target) && e.target !== input) {
                dropdown.classList.add('hidden');
            }

            const pendaftarDropdown = document.getElementById('posyandu-pendaftar-dropdown');
            const pendaftarInput    = document.getElementById('posyandu-nama-pendaftar');
            if (pendaftarDropdown && !pendaftarDropdown.contains(e.target) && e.target !== pendaftarInput) {
                pendaftarDropdown.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>
