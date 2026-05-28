<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIKAS - Sistem Informasi Kas RT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animasi Gradasi Warna Biru-Gold */
        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .bg-animate {
            background: linear-gradient(-45deg, #1e3a8a, #3b82f6, #fbbf24, #d97706);
            background-size: 400% 400%;
            animation: gradientAnimation 10s ease infinite;
        }

        /* Animasi Floating untuk Elemen Dekorasi */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .float-element {
            animation: float 6s ease-in-out infinite;
        }

        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="antialiased font-['Figtree'] overflow-hidden">
    <div class="relative min-h-screen bg-animate flex items-center justify-center p-4">
        
        <div class="absolute top-20 left-20 w-32 h-32 bg-yellow-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 float-element" style="animation-delay: 0s;"></div>
        <div class="absolute bottom-20 right-20 w-48 h-48 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 float-element" style="animation-delay: 2s;"></div>

        <div class="max-w-4xl w-full relative z-10">
            <div class="text-center mb-12 transform transition duration-1000 hover:scale-105">
                <div class="inline-block p-5 rounded-[2rem] glass mb-6 shadow-2xl bg-gradient-to-tr from-white/10 to-white/5 border border-white/20">
                    <!-- SIKAS Premium Logo SVG -->
                    <svg class="w-16 h-16 text-white" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 3L4 13.5V27.5C4 28.3284 4.67157 29 5.5 29H26.5C27.3284 29 28 28.3284 28 27.5V13.5L16 3Z" fill="currentColor" fill-opacity="0.15" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M12 29V19C12 18.4477 12.4477 18 13 18H19C19.5523 18 20 18.4477 20 19V29" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <circle cx="16" cy="12" r="4.5" fill="#facc15" stroke="#d97706" stroke-width="1.5" class="animate-pulse"/>
                        <path d="M16 10V14M14 12H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <h1 class="text-6xl md:text-7xl font-extrabold text-white tracking-tighter mb-4 drop-shadow-lg leading-none">
                    SIKAS<span class="text-yellow-300 text-3xl md:text-4xl block md:inline md:ml-2 tracking-normal font-semibold px-4 py-1.5 bg-white/10 rounded-2xl border border-white/20">RT</span>
                </h1>
                <p class="text-lg md:text-xl text-blue-50 font-bold opacity-90 max-w-xl mx-auto leading-relaxed mt-4">
                    Sistem Informasi Kas Keuangan Lingkungan Rukun Tetangga secara Transparan & Real-Time.
                </p>
            </div>

            <div class="glass rounded-[2.5rem] p-8 md:p-12 shadow-2xl overflow-hidden relative group">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-yellow-300 to-transparent"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="text-white space-y-6">
                        <div class="flex items-start space-x-4 group">
                            <div class="bg-yellow-400/20 p-2 rounded-lg text-yellow-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Keamanan Terjamin</h4>
                                <p class="text-sm opacity-70">Data keuangan terenkripsi dan aman di sistem cloud RT.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-blue-400/20 p-2 rounded-lg text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Transparansi Publik</h4>
                                <p class="text-sm opacity-70">Warga dapat memantau saldo kapanpun secara real-time.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ Auth::user()->role == 1 ? route('ketua.dashboard') : (Auth::user()->role == 2 ? route('bendahara.dashboard') : route('warga.dashboard')) }}" class="w-full py-4 bg-white text-blue-900 text-center font-bold rounded-2xl shadow-lg hover:bg-yellow-300 hover:scale-105 transition duration-300">
                                    Buka Dashboard Saya
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="w-full py-4 bg-white text-blue-900 text-center font-bold rounded-2xl shadow-lg hover:bg-yellow-300 hover:scale-105 transition duration-300 group flex items-center justify-center">
                                    Masuk ke Sistem
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-2 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="w-full py-4 glass text-white text-center font-bold rounded-2xl hover:bg-white/20 transition duration-300 border border-white/30">
                                        Daftar Akun Warga
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-white/50 text-xs font-semibold uppercase tracking-widest">
                    &copy; {{ date('Y') }} SIKAS - Sistem Informasi Kas RT. Built with &hearts; for Community.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Efek Parallax Ringan pada Mouse Move
        document.addEventListener('mousemove', (e) => {
            const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
            const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
            document.querySelector('.glass').style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
    </script>
</body>
</html>