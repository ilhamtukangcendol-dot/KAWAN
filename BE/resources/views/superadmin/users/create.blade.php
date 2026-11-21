<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Tambah Akun Pengguna Baru') }}
            </h2>
            <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition duration-300">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-100">
                <form method="POST" action="{{ route('superadmin.users.store') }}" class="space-y-6">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap Pengguna</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Subagyo" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-purple-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                        @error('name')
                            <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@gmail.com" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-purple-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                        @error('email')
                            <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Hak Akses / Role SIKAS RT</label>
                        <select name="role" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-purple-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                            <option value="">-- Pilih Role --</option>
                            <optgroup label="Tingkat Eksekutif / Sistem">
                                <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>1 - Super Admin (Full Control / Pengguna & Seluruh Sistem)</option>
                            </optgroup>
                            <optgroup label="Tingkat Rukun Warga (RW)">
                                <option value="5" {{ old('role') == '5' ? 'selected' : '' }}>5 - Ketua RW (Pimpinan RW / Monitoring Wilayah)</option>
                                <option value="6" {{ old('role') == '6' ? 'selected' : '' }}>6 - Sekretaris RW (Administrasi & Pelayanan RW)</option>
                                <option value="7" {{ old('role') == '7' ? 'selected' : '' }}>7 - Bendahara RW (Keuangan & Kas Tingkat RW)</option>
                            </optgroup>
                            <optgroup label="Tingkat Rukun Tetangga (RT)">
                                <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>2 - Ketua RT (Pimpinan RT / Monitoring & Audit)</option>
                                <option value="8" {{ old('role') == '8' ? 'selected' : '' }}>8 - Sekretaris RT (Administrasi & Persuratan RT)</option>
                                <option value="3" {{ old('role') == '3' ? 'selected' : '' }}>3 - Bendahara RT (Kas, Iuran & Inventaris RT)</option>
                            </optgroup>
                            <optgroup label="Warga Masyarakat">
                                <option value="4" {{ old('role') == '4' ? 'selected' : '' }}>4 - Warga (Layanan Warga, Iuran Mandiri & Komunitas)</option>
                            </optgroup>
                        </select>
                        @error('role')
                            <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kata Sandi (Min 8 Karakter)</label>
                            <input type="password" name="password" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-purple-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                            @error('password')
                                <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-purple-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <a href="{{ route('superadmin.users.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-2xl transition duration-300">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-purple-200 transition duration-300">
                            Simpan Pengguna Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
