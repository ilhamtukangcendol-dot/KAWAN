<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-white">Selamat Datang</h2>
        <p class="text-blue-100 opacity-80 text-sm mt-2">Silakan masuk untuk mengelola Kas RT</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block font-bold text-sm text-yellow-300 mb-2">Alamat Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                class="block w-full bg-white/10 border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-yellow-400 focus:border-yellow-400 shadow-inner" 
                placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <div class="mt-4">
            <label class="block font-bold text-sm text-yellow-300 mb-2">Password</label>
            <input id="password" type="password" name="password" required 
                class="block w-full bg-white/10 border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-yellow-400 focus:border-yellow-400 shadow-inner" 
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white/20 border-white/30 text-yellow-500 focus:ring-yellow-400" name="remember">
                <span class="ms-2 text-sm text-white/80 italic">{{ __('Ingat saya') }}</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm text-yellow-300 hover:text-white transition underline" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-black py-4 rounded-2xl shadow-xl transition duration-300 transform hover:-translate-y-1 uppercase tracking-wider">
                Masuk Sekarang
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-white/60">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-yellow-300 font-bold hover:underline">Daftar Warga</a>
            </p>
        </div>
    </form>
</x-guest-layout>