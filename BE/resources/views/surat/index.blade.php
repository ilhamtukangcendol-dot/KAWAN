<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Modul Layanan Surat Menyurat RT') }}
            </h2>
            <button onclick="document.getElementById('modal-surat').classList.remove('hidden')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">
                + Ajukan Surat Keterangan RT
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

            <!-- Tabel Pengajuan Surat -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-black text-slate-800 text-base uppercase tracking-wider">Daftar Pengajuan Surat Keterangan</h3>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-xl">{{ $suratList->total() }} Pengajuan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">Tgl Pengajuan</th>
                                <th class="py-4 px-6">Pemohon Warga</th>
                                <th class="py-4 px-6">NIK Warga</th>
                                <th class="py-4 px-6">Jenis Surat & Keperluan</th>
                                <th class="py-4 px-6 text-center">Nomor Surat RT</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Aksi / Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($suratList as $s)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-bold text-slate-700 text-xs">{{ \Carbon\Carbon::parse($s->tanggal_pengajuan)->format('d M Y') }}</td>
                                    <td class="py-4 px-6">
                                        <p class="font-black text-slate-800 text-xs">
                                            {{ $s->warga ? $s->warga->nama_lengkap : ($s->user ? $s->user->name : 'Warga') }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                            Diajukan oleh: {{ $s->user ? $s->user->name : '-' }}
                                        </p>
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs text-slate-500 font-semibold">
                                        {{ $s->warga ? $s->warga->nik : '-' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <p class="font-black text-indigo-700 text-xs">{{ $s->jenis_surat }}</p>
                                        <p class="text-xs text-slate-500 font-semibold mt-0.5">{{ $s->keperluan }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-center font-mono font-bold text-xs text-slate-700">
                                        {{ $s->no_surat ?? '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($s->status == 'approved')
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-lg">DISETUJUI</span>
                                        @elseif($s->status == 'rejected')
                                            <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-[10px] font-black rounded-lg">DITOLAK</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-lg">PROSES RT</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if(Auth::user()->role <= 2 && $s->status == 'pending')
                                            <form method="POST" action="{{ route('surat.approve', $s->id) }}" class="inline-flex gap-2">
                                                @csrf
                                                <input type="hidden" name="no_surat" value="470/{{ str_pad($s->id, 3, '0', STR_PAD_LEFT) }}/RT.01/{{ date('Y') }}">
                                                <button type="submit" class="px-3 py-1 bg-emerald-600 text-white font-bold text-xs rounded-lg shadow">Setujui & TTD</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400 font-bold">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400 font-bold text-xs">
                                        ✉️ Belum ada pengajuan surat keterangan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($suratList->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $suratList->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Modal Form Surat -->
    <div id="modal-surat" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-3xl p-7 max-w-md w-full shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center">
                <h3 class="font-black text-slate-800 text-lg uppercase tracking-wide">Ajukan Surat Keterangan RT</h3>
                <button onclick="document.getElementById('modal-surat').classList.add('hidden')" class="text-slate-400 font-black text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('surat.store') }}" class="space-y-4">
                @csrf

                <!-- Pilih Warga dari Database -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">
                        Pilih Warga Pemohon <span class="text-indigo-600">(Otomatis dari Data RT)</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="surat-search" placeholder="🔍 Ketik nama warga untuk mencari..." class="w-full border border-slate-200 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:border-indigo-400 focus:outline-none" autocomplete="off" oninput="searchWargaSurat(this.value)" onfocus="searchWargaSurat(this.value)" onclick="searchWargaSurat(this.value)">
                        <input type="hidden" name="warga_id" id="surat-warga-id">
                        <!-- Dropdown hasil pencarian -->
                        <div id="surat-dropdown" class="absolute z-50 w-full bg-white border border-slate-200 rounded-2xl shadow-xl mt-1 hidden max-h-48 overflow-y-auto">
                            <!-- Diisi via JS -->
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 font-semibold mt-1">Klik atau ketik untuk melihat daftar seluruh warga RT</p>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Jenis Surat Keterangan</label>
                    <select name="jenis_surat" required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:outline-none focus:border-indigo-400">
                        <option value="Surat Pengantar KTP / KK">Surat Pengantar KTP / KK</option>
                        <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                        <option value="Surat Keterangan Usaha (SKU)">Surat Keterangan Usaha (SKU)</option>
                        <option value="Surat Keterangan Belum Menikah">Surat Keterangan Belum Menikah</option>
                        <option value="Surat Keterangan Kematian">Surat Keterangan Kematian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Keperluan & Alasan</label>
                    <textarea name="keperluan" placeholder="Jelaskan secara singkat keperluan permohonan surat..." required class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold focus:outline-none focus:border-indigo-400"></textarea>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-surat').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase shadow-md hover:bg-indigo-700 transition">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const allWargaSurat = @json($wargaList);

        function searchWargaSurat(query) {
            const dropdown = document.getElementById('surat-dropdown');
            
            // Tampilkan semua warga jika input kosong / di-klik
            const results = query.length >= 1
                ? allWargaSurat.filter(w =>
                    w.nama_lengkap.toLowerCase().includes(query.toLowerCase()) ||
                    (w.nik && w.nik.includes(query))
                  )
                : allWargaSurat;

            if (results.length === 0) {
                dropdown.innerHTML = '<div class="px-4 py-3 text-xs text-slate-400 font-semibold">Tidak ada warga ditemukan</div>';
                dropdown.classList.remove('hidden');
                return;
            }

            dropdown.innerHTML = results.slice(0, 15).map(w => `
                <div class="px-4 py-2.5 hover:bg-indigo-50 cursor-pointer border-b border-slate-50 last:border-0 flex justify-between items-center"
                     onclick="selectWargaSurat(${w.id}, '${w.nama_lengkap.replace(/'/g,"\\'")}')">
                    <div>
                        <p class="text-xs font-black text-slate-800">${w.nama_lengkap}</p>
                        <p class="text-[10px] text-slate-400 font-semibold">${w.nik ?? '-'} • ${w.umur} Thn • ${w.jenis_kelamin == 'L' ? '♂ Laki-laki' : '♀ Perempuan'}</p>
                    </div>
                    <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg">${w.status_keluarga}</span>
                </div>
            `).join('');
            dropdown.classList.remove('hidden');
        }

        function selectWargaSurat(id, nama) {
            document.getElementById('surat-warga-id').value = id;
            document.getElementById('surat-search').value = nama;
            document.getElementById('surat-dropdown').classList.add('hidden');
        }

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('surat-dropdown');
            const input = document.getElementById('surat-search');
            if (dropdown && !dropdown.contains(e.target) && e.target !== input) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>
