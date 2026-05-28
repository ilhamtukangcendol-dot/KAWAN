<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-white">Daftar Akun</h2>
        <p class="text-blue-100 opacity-80 text-sm mt-2">Bergabunglah dalam transparansi Kas RT Digital</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block font-bold text-sm text-yellow-300 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus 
                class="block w-full bg-white/10 border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-yellow-400 focus:border-yellow-400 shadow-inner"
                placeholder="Contoh: Budi Santoso">
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-300" />
        </div>

        <div>
            <label class="block font-bold text-sm text-yellow-300 mb-1">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required 
                class="block w-full bg-white/10 border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-yellow-400 focus:border-yellow-400 shadow-inner"
                placeholder="budi@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-300" />
        </div>

        <div>
            <label class="block font-bold text-sm text-yellow-300 mb-1">Password</label>
            <input id="password" type="password" name="password" required 
                class="block w-full bg-white/10 border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-yellow-400 focus:border-yellow-400 shadow-inner"
                placeholder="Minimal 8 karakter">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-300" />
        </div>

        <div>
            <label class="block font-bold text-sm text-yellow-300 mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required 
                class="block w-full bg-white/10 border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-yellow-400 focus:border-yellow-400 shadow-inner"
                placeholder="Ulangi password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-300" />
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-black py-4 rounded-2xl shadow-xl transition duration-300 transform hover:-translate-y-1 uppercase tracking-wider">
                Daftar Sekarang
            </button>
        </div>

        <div class="text-center mt-6">
            <a class="text-sm text-white/60 hover:text-white transition" href="{{ route('login') }}">
                {{ __('Sudah punya akun? ') }} <span class="text-yellow-300 font-bold underline">Login di sini</span>
            </a>
        </div>
    </form>
</x-guest-layout>