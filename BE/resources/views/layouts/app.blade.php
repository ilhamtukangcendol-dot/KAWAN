<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'KAWAN - Komunikasi & Aplikasi Warga Nyaman') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .glass-card {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .bg-subtle {
                background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-subtle">
        <div class="min-h-screen">
    @include('layouts.navigation')

    <div class="lg:ml-72 transition-all duration-300">
        
        @isset($header)
            <header class="bg-white/50 backdrop-blur-md border-b border-blue-100 sticky top-0 z-40">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>
</div>
    </body>
</html>