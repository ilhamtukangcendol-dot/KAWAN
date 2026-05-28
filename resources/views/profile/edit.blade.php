@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
</style>

<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header Title -->
    <div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Profil</h2>
        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Kelola identitas, foto profil, dan keamanan akun SIKAS Anda</p>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
        <i class="fas fa-check-circle text-lg text-emerald-600"></i>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('status') === 'password-updated')
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
        <i class="fas fa-key text-lg text-emerald-600"></i>
        <span class="text-sm font-semibold">Password Anda berhasil diperbarui!</span>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex flex-col gap-1 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-lg text-rose-600"></i>
            <span class="text-sm font-bold">Terjadi kesalahan validasi:</span>
        </div>
        <ul class="list-disc pl-9 text-xs font-semibold text-rose-600 mt-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Profile Info & Avatar Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8">
        <h3 class="text-lg font-extrabold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fas fa-user-circle text-blue-600"></i>
            Informasi Pribadi Warga/Pengurus
        </h3>

        <!-- Hidden Form for Avatar Deletion -->
        <form id="delete-avatar-form" method="POST" action="{{ route('profile.avatar.delete') }}" class="hidden">
            @csrf
        </form>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Avatar Uploader -->
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-slate-100 bg-slate-50 flex items-center justify-center shadow-inner relative z-10">
                        @if(Auth::user()->avatar)
                            <img id="avatar-preview" src="{{ asset(Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <!-- Placeholder with first two letters of user's name -->
                            <div id="avatar-initials" class="w-full h-full bg-gradient-to-tr from-blue-500 to-indigo-600 text-white flex items-center justify-center font-black text-2xl uppercase">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <img id="avatar-preview" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    <!-- Hover Edit Button Overlay -->
                    <label for="avatar-input" class="absolute inset-0 bg-black/40 rounded-full z-20 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity duration-300">
                        <i class="fas fa-camera text-white text-lg"></i>
                    </label>
                </div>
                <div class="space-y-2 text-center sm:text-left">
                    <h4 class="font-extrabold text-slate-800 text-sm">Foto Profil Anda</h4>
                    <p class="text-xs text-slate-400 font-medium max-w-sm">Unggah file foto berformat JPG, JPEG, PNG, atau WEBP dengan ukuran maks. 2MB.</p>
                    <div class="flex items-center justify-center sm:justify-start gap-3">
                        <label for="avatar-input" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black uppercase tracking-widest rounded-xl transition duration-300 cursor-pointer">
                            Pilih Foto Baru
                        </label>
                        <input id="avatar-input" type="file" name="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">

                        <button type="button" id="btn-delete-avatar" onclick="handleAvatarDeletion()" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-black uppercase tracking-widest rounded-xl transition duration-300 {{ Auth::user()->avatar ? '' : 'hidden' }}">
                            Hapus Foto
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profile Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i class="fas fa-user text-xs"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-300">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-300">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-slate-50">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition duration-300 shadow-lg shadow-blue-500/10">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Password Security Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8">
        <h3 class="text-lg font-extrabold text-slate-800 mb-2 flex items-center gap-2">
            <i class="fas fa-shield-alt text-orange-500"></i>
            Keamanan Akun & Sandi
        </h3>
        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-6">Perbarui password akun Anda secara berkala</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('put')

            <div class="grid grid-cols-1 gap-6">
                <!-- Current Password -->
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Password Saat Ini</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        <input type="password" name="current_password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition duration-300"
                            placeholder="Masukkan password saat ini">
                    </div>
                </div>

                <!-- New Password & Confirm Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Password Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                <i class="fas fa-key text-xs"></i>
                            </span>
                            <input type="password" name="password" required
                                class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition duration-300"
                                placeholder="Minimal 8 karakter">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                <i class="fas fa-check text-xs"></i>
                            </span>
                            <input type="password" name="password_confirmation" required
                                class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition duration-300"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-slate-50">
                <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition duration-300 shadow-lg shadow-orange-500/10">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const hasDatabaseAvatar = @json(Auth::user()->avatar ? true : false);

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const initials = document.getElementById('avatar-initials');
                const deleteBtn = document.getElementById('btn-delete-avatar');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                
                if (initials) {
                    initials.classList.add('hidden');
                }

                if (deleteBtn) {
                    deleteBtn.classList.remove('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleAvatarDeletion() {
        const input = document.getElementById('avatar-input');
        
        // If there is a newly selected file, clear it first
        if (input && input.files && input.files.length > 0) {
            input.value = ""; // Clear file input
            
            // Restore preview to database avatar or initials
            const preview = document.getElementById('avatar-preview');
            const initials = document.getElementById('avatar-initials');
            const deleteBtn = document.getElementById('btn-delete-avatar');
            
            if (hasDatabaseAvatar) {
                preview.src = "{{ asset(Auth::user()->avatar) }}";
                preview.classList.remove('hidden');
                if (initials) initials.classList.add('hidden');
                if (deleteBtn) deleteBtn.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
                preview.src = "";
                if (initials) initials.classList.remove('hidden');
                if (deleteBtn) deleteBtn.classList.add('hidden');
            }
        } else {
            // If no new file is selected, they are deleting the saved database avatar
            if (confirm('Apakah Anda yakin ingin menghapus foto profil ini secara permanen?')) {
                document.getElementById('delete-avatar-form').submit();
            }
        }
    }
</script>
@endsection
