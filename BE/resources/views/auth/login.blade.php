<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-white tracking-tight">Selamat Datang</h2>
        <p class="text-slate-400 text-sm mt-2 font-medium">Silakan masuk untuk mengelola portal RT</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label class="block font-black text-[10px] uppercase tracking-widest text-slate-400 mb-2">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25" />
                    </svg>
                </span>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                    class="block w-full bg-slate-950/60 border border-slate-800 rounded-2xl text-white placeholder-slate-600 focus:ring-rose-500 focus:border-rose-500 focus:ring-2 focus:ring-offset-0 shadow-inner pl-10 pr-4 py-3.5 transition text-sm" 
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-400 text-xs font-semibold" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label class="block font-black text-[10px] uppercase tracking-widest text-slate-400 mb-2">Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </span>
                <input id="password" type="password" name="password" required 
                    class="block w-full bg-slate-950/60 border border-slate-800 rounded-2xl text-white placeholder-slate-600 focus:ring-rose-500 focus:border-rose-500 focus:ring-2 focus:ring-offset-0 shadow-inner pl-10 pr-4 py-3.5 transition text-sm" 
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-400 text-xs font-semibold" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded bg-slate-950 border-slate-800 text-rose-600 focus:ring-rose-500/20 focus:ring-offset-slate-950 cursor-pointer" name="remember">
                <span class="ms-2 text-xs font-bold text-slate-400 hover:text-slate-300 transition">Ingat saya</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-xs text-rose-400 hover:text-rose-300 font-bold transition hover:underline" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-500 hover:to-red-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-rose-600/25 transition duration-300 transform hover:-translate-y-0.5 uppercase tracking-wider text-xs btn-shine">
                Masuk Sekarang
            </button>
        </div>

        <style>
            @keyframes shine {
                0% { left: -100%; }
                50%, 100% { left: 100%; }
            }
            .btn-shine {
                position: relative;
                overflow: hidden;
            }
            .btn-shine::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 50%;
                height: 100%;
                background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.25), transparent);
                transform: skewX(-25deg);
                animation: shine 4s ease-in-out infinite;
            }
        </style>

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-xs text-slate-500 font-semibold">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="text-rose-400 font-bold hover:text-rose-300 hover:underline">Daftar Warga</a>
            </p>
        </div>

        <!-- Quick Demo Login Shortcuts -->
        <div class="pt-4 border-t border-slate-800/80 mt-6">
            <span class="block font-black text-[9px] uppercase tracking-widest text-slate-500 mb-3 text-center">Masuk Cepat Akun Demo (Uji Coba)</span>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="fillLogin('warga@gmail.com', 'password')" class="px-3 py-2 bg-slate-950/40 hover:bg-slate-900 border border-slate-800 hover:border-emerald-500/40 text-[10px] font-bold text-slate-300 rounded-xl transition flex items-center justify-center gap-1.5">
                    <span>🟢</span> Warga
                </button>
                <button type="button" onclick="fillLogin('ketua@gmail.com', 'password')" class="px-3 py-2 bg-slate-950/40 hover:bg-slate-900 border border-slate-800 hover:border-rose-500/40 text-[10px] font-bold text-slate-300 rounded-xl transition flex items-center justify-center gap-1.5">
                    <span>👑</span> Ketua RT
                </button>
                <button type="button" onclick="fillLogin('bendahara@gmail.com', 'password')" class="px-3 py-2 bg-slate-950/40 hover:bg-slate-900 border border-slate-800 hover:border-amber-500/40 text-[10px] font-bold text-slate-300 rounded-xl transition flex items-center justify-center gap-1.5">
                    <span>💰</span> Bendahara
                </button>
                <button type="button" onclick="fillLogin('superadmin@gmail.com', 'password')" class="px-3 py-2 bg-slate-950/40 hover:bg-slate-900 border border-slate-800 hover:border-indigo-500/40 text-[10px] font-bold text-slate-300 rounded-xl transition flex items-center justify-center gap-1.5">
                    <span>⚡</span> Superadmin
                </button>
            </div>
        </div>
    </form>

    <script>
        function fillLogin(email, password) {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            
            emailInput.value = email;
            passInput.value = password;
            
            // Add a visual flash effect to inputs
            emailInput.classList.add('ring-2', 'ring-rose-500');
            passInput.classList.add('ring-2', 'ring-rose-500');
            
            setTimeout(() => {
                emailInput.classList.remove('ring-2', 'ring-rose-500');
                passInput.classList.remove('ring-2', 'ring-rose-500');
            }, 300);
        }
    </script>
</x-guest-layout>