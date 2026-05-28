<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'SIKAS - Sistem Informasi Kas RT') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
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
            .glass {
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-animate">
            <div>
                <a href="/">
                    <div class="p-5 rounded-[1.8rem] glass shadow-2xl mb-4 flex items-center justify-center bg-gradient-to-tr from-white/10 to-white/5 border border-white/20 hover:scale-105 transition duration-300">
                        <!-- SIKAS Premium Logo SVG -->
                        <svg class="w-14 h-14 text-white" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 3L4 13.5V27.5C4 28.3284 4.67157 29 5.5 29H26.5C27.3284 29 28 28.3284 28 27.5V13.5L16 3Z" fill="currentColor" fill-opacity="0.15" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M12 29V19C12 18.4477 12.4477 18 13 18H19C19.5523 18 20 18.4477 20 19V29" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <circle cx="16" cy="12" r="4.5" fill="#fbbf24" stroke="#d97706" stroke-width="1.5" class="animate-pulse"/>
                            <path d="M16 10V14M14 12H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-10 glass shadow-2xl overflow-hidden sm:rounded-[2rem] text-white">
                {{ $slot }}
            </div>
            
            <p class="mt-6 text-white/50 text-xs">© {{ date('Y') }} SIKAS - Sistem Informasi Kas RT</p>
        </div>
    </body>
</html>