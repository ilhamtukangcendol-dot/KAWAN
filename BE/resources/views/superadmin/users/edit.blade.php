<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Edit Pengguna & Hak Akses Role') }}
            </h2>
            <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition duration-300">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-100 space-y-6">

                <div class="flex items-center gap-4 p-4 bg-purple-50/50 border border-purple-100 rounded-2xl">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center font-black text-base shadow-lg shadow-purple-200">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 text-base leading-tight">{{ $user->name }}</h3>
                        <p class="text-xs text-purple-700 font-semibold">Mengubah Akun ID #{{ $user->id }} &bull; Current Role: {{ $user->role_label }} (Role {{ $user->role }})</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap Pengguna</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-purple-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                        @error('name')
                            <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-purple-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                        @error('email')
                            <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Hak Akses / Role SIKAS RT</label>
                        <select name="role" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-purple-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                            <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>1 - Super Admin (Full Access & Kelola User)</option>
                            <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>2 - Ketua RT (Monitoring, Audit & Warga Payment)</option>
                            <option value="3" {{ old('role', $user->role) == 3 ? 'selected' : '' }}>3 - Bendahara (Operasional Kas & Persetujuan Setoran)</option>
                            <option value="4" {{ old('role', $user->role) == 4 ? 'selected' : '' }}>4 - Warga (Setoran Kas Mandiri & History)</option>
                        </select>
                        @error('role')
                            <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reset Password Section (Optional) -->
                    <div class="p-5 bg-slate-50 border border-slate-100 rounded-2xl space-y-4">
                        <div>
                            <h4 class="font-black text-xs uppercase tracking-wider text-slate-700">Ubah Kata Sandi (Opsional)</h4>
                            <p class="text-[11px] text-slate-400 font-semibold">Kosongkan bidang password jika tidak ingin mengganti kata sandi pengguna ini.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kata Sandi Baru</label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter..." class="w-full border-slate-200 rounded-2xl bg-white focus:ring-purple-500 text-sm py-3 px-4 font-semibold text-slate-700">
                                @error('password')
                                    <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru..." class="w-full border-slate-200 rounded-2xl bg-white focus:ring-purple-500 text-sm py-3 px-4 font-semibold text-slate-700">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <a href="{{ route('superadmin.users.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-2xl transition duration-300">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-purple-200 transition duration-300">
                            Update Data Pengguna
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
