<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
            {{ __('Pengaturan Akun & Sistem SIKAS RT') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- SECTION 1: PENGATURAN PROFIL DIRI & FOTO PROFIL (UNTUK SEMUA ROLE) -->
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-2xl flex items-center justify-center font-black text-xs">
                            ⚙️
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 text-base leading-tight uppercase tracking-wide">Profil & Keamanan Akun Saya</h3>
                            <p class="text-xs text-slate-400 font-semibold">Berlaku untuk seluruh pengguna (Role: {{ $user->role_label }})</p>
                        </div>
                    </div>

                    <!-- FOTO PROFIL UPLOAD SECTION -->
                    <div class="p-6 bg-slate-50/70 border border-slate-100 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center space-x-5">
                            @if($user->avatar && file_exists(public_path($user->avatar)))
                                <img id="preview-avatar" src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-indigo-500 shadow-md">
                            @else
                                <div id="preview-initials" class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center font-black text-2xl shadow-md border-2 border-indigo-400">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <img id="preview-avatar" src="#" alt="Preview" class="w-20 h-20 rounded-2xl object-cover border-2 border-indigo-500 shadow-md hidden">
                            @endif
                            <div>
                                <h4 class="font-black text-slate-800 text-sm">Foto Profil Akun</h4>
                                <p class="text-xs text-slate-400 font-medium">Format JPEG, PNG, WEBP (Maksimal 2 MB)</p>
                                @if($user->avatar)
                                    <label class="inline-flex items-center gap-2 mt-2 cursor-pointer">
                                        <input type="checkbox" name="remove_avatar" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        <span class="text-xs font-bold text-rose-600">Hapus Foto Profil Saat Ini</span>
                                    </label>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-lg cursor-pointer transition inline-block">
                                📷 Pilih Foto Profil
                                <input type="file" name="avatar" accept="image/*" class="hidden" onchange="previewImage(this)">
                            </label>
                            @error('avatar') <p class="text-rose-500 text-xs font-bold mt-1 text-right">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap Pengguna</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                            @error('name') <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Alamat Email Login</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                            @error('email') <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="p-5 bg-slate-50/70 border border-slate-100 rounded-2xl space-y-4">
                        <h4 class="font-black text-xs uppercase tracking-wider text-slate-700">Ganti Password (Opsional)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kata Sandi Baru</label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter..." class="w-full border-slate-200 rounded-2xl bg-white focus:ring-indigo-500 text-sm py-3 px-4 font-semibold text-slate-700">
                                @error('password') <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi..." class="w-full border-slate-200 rounded-2xl bg-white focus:ring-indigo-500 text-sm py-3 px-4 font-semibold text-slate-700">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: PENGATURAN GLOBAL OPERASIONAL RT (KHUSUS SUPERADMIN, RT, BENDAHARA) -->
                @if(Auth::user()->role <= 3)
                    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
                        <div class="pb-4 border-b border-slate-100">
                            <h3 class="font-black text-slate-800 text-base leading-tight uppercase tracking-wide">Konfigurasi & Identitas Lingkungan RT</h3>
                            <p class="text-xs text-slate-400 font-semibold">Pengaturan acuan tagihan iuran, santunan, dan identitas pengurus</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Identitas RT / RW</label>
                            <input type="text" name="nama_rt" value="{{ $settings['nama_rt'] }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Sekretariat RT</label>
                            <input type="text" name="alamat_rt" value="{{ $settings['alamat_rt'] }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Standard Nominal Iuran Warga (Rp/Bulan)</label>
                                <input type="number" name="nominal_iuran" value="{{ $settings['nominal_iuran'] }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nominal Standar Santunan Rukem (Rp)</label>
                                <input type="number" name="nominal_rukem" value="{{ $settings['nominal_rukem'] }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nomor Telepon Hotline RT / Pengurus</label>
                            <input type="text" name="kontak_rt" value="{{ $settings['kontak_rt'] }}" required class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                        </div>
                    </div>
                @endif

                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg transition">
                        Simpan Semua Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.getElementById('preview-avatar');
                    const previewInitials = document.getElementById('preview-initials');
                    
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    if (previewInitials) previewInitials.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
