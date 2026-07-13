<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-white tracking-tight">Daftar Akun</h2>
        <p class="text-slate-400 text-sm mt-2 font-medium">Daftarkan akun warga untuk mengakses portal layanan RT</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label class="block font-black text-[10px] uppercase tracking-widest text-slate-400 mb-2">Nama Lengkap</label>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus 
                class="block w-full bg-slate-950/60 border border-slate-800 rounded-2xl text-white placeholder-slate-600 focus:ring-rose-500 focus:border-rose-500 shadow-inner px-4 py-3.5 transition text-sm"
                placeholder="Contoh: Budi Santoso">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-400 text-xs font-semibold" />
        </div>

        <!-- Email -->
        <div>
            <label class="block font-black text-[10px] uppercase tracking-widest text-slate-400 mb-2">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required 
                class="block w-full bg-slate-950/60 border border-slate-800 rounded-2xl text-white placeholder-slate-600 focus:ring-rose-500 focus:border-rose-500 shadow-inner px-4 py-3.5 transition text-sm"
                placeholder="budi@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-400 text-xs font-semibold" />
        </div>

        <!-- Password -->
        <div>
            <label class="block font-black text-[10px] uppercase tracking-widest text-slate-400 mb-2">Password</label>
            <input id="password" type="password" name="password" required 
                class="block w-full bg-slate-950/60 border border-slate-800 rounded-2xl text-white placeholder-slate-600 focus:ring-rose-500 focus:border-rose-500 shadow-inner px-4 py-3.5 transition text-sm"
                placeholder="Minimal 8 karakter">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-400 text-xs font-semibold" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="block font-black text-[10px] uppercase tracking-widest text-slate-400 mb-2">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required 
                class="block w-full bg-slate-950/60 border border-slate-800 rounded-2xl text-white placeholder-slate-600 focus:ring-rose-500 focus:border-rose-500 shadow-inner px-4 py-3.5 transition text-sm"
                placeholder="Ulangi password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-400 text-xs font-semibold" />
        </div>

        <!-- Submit Button -->
        <div class="pt-6">
            <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-500 hover:to-red-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-rose-600/25 transition duration-300 transform hover:-translate-y-0.5 uppercase tracking-wider text-xs">
                Daftar Sekarang
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-6">
            <p class="text-xs text-slate-500 font-semibold">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="text-rose-400 font-bold hover:text-rose-300 hover:underline">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>