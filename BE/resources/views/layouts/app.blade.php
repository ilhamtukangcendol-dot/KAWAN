<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
        <div class="min-h-screen" x-data>
            @include('layouts.navigation')

            <!-- Mobile Top Nav Bar -->
            <header class="bg-slate-900 border-b border-slate-800 text-white px-4 py-3 sticky top-0 z-40 flex items-center justify-between lg:hidden shadow-md">
                <div class="flex items-center space-x-3">
                    <!-- Hamburger Button -->
                    <button @click="$dispatch('toggle-sidebar')" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition shrink-0 flex items-center justify-center" aria-label="Toggle Sidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <span class="text-base font-black tracking-tighter text-white">KAWAN</span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 bg-amber-400/10 border border-amber-400/20 text-amber-400 rounded-md uppercase">
                        {{ Auth::user()->role_label }}
                    </span>
                </div>
            </header>

            <div class="lg:ml-72 transition-all duration-300">
                
                @isset($header)
                    <header class="bg-white/50 backdrop-blur-md border-b border-blue-100 lg:sticky lg:top-0 z-30">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="py-12 pb-24 lg:pb-12">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @yield('content')
                        {{ $slot ?? '' }}
                    </div>
                </main>
            </div>

            @php
                $dashboardRoute = route('warga.dashboard');
                if (Auth::user()->role == 1) {
                    $dashboardRoute = route('superadmin.dashboard');
                } elseif (Auth::user()->role == 2) {
                    $dashboardRoute = route('ketua.dashboard');
                } elseif (Auth::user()->role == 3) {
                    $dashboardRoute = route('bendahara.dashboard');
                }
            @endphp

            <!-- Bottom Navigation Bar for Mobile/Smartphone -->
            <div class="fixed bottom-0 left-0 right-0 z-40 bg-slate-900/90 backdrop-blur-lg border-t border-slate-800 lg:hidden px-4 py-2 shadow-2xl">
                <div class="flex items-center justify-between max-w-md mx-auto">
                    <!-- Dashboard Tab -->
                    <a href="{{ $dashboardRoute }}" class="flex flex-col items-center justify-center py-1 px-3 transition group {{ request()->routeIs('*.dashboard') ? 'text-indigo-400 font-extrabold' : 'text-slate-400 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="text-[9px] uppercase tracking-wider mt-1">Beranda</span>
                    </a>

                    <!-- Iuran Tab -->
                    <a href="{{ route('iuran.index') }}" class="flex flex-col items-center justify-center py-1 px-3 transition group {{ request()->routeIs('iuran.*') ? 'text-indigo-400 font-extrabold' : 'text-slate-400 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="text-[9px] uppercase tracking-wider mt-1">Iuran</span>
                    </a>

                    <!-- Surat Tab -->
                    <a href="{{ route('surat.index') }}" class="flex flex-col items-center justify-center py-1 px-3 transition group {{ request()->routeIs('surat.*') ? 'text-indigo-400 font-extrabold' : 'text-slate-400 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-[9px] uppercase tracking-wider mt-1">Surat</span>
                    </a>

                    <!-- UMKM Tab -->
                    <a href="{{ route('umkm.index') }}" class="flex flex-col items-center justify-center py-1 px-3 transition group {{ request()->routeIs('umkm.*') ? 'text-indigo-400 font-extrabold' : 'text-slate-400 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-[9px] uppercase tracking-wider mt-1">UMKM</span>
                    </a>

                    <!-- Sidebar Trigger Button (Menu) -->
                    <button @click="$dispatch('toggle-sidebar')" class="flex flex-col items-center justify-center py-1 px-3 text-slate-400 hover:text-white transition group focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <span class="text-[9px] uppercase tracking-wider mt-1">Menu</span>
                    </button>
                </div>
            </div>
        </div>
        @stack('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const key = 'scrollY_' + location.pathname;
                const saved = sessionStorage.getItem(key);

                // Restore scroll position if saved
                if (saved !== null) {
                    requestAnimationFrame(function() {
                        window.scrollTo(0, parseInt(saved));
                        sessionStorage.removeItem(key);
                    });
                }

                // Save scroll on GET form submissions (filters, search, sort)
                document.querySelectorAll('form[method="GET"]').forEach(function(form) {
                    form.addEventListener('submit', function() {
                        sessionStorage.setItem(key, window.scrollY);
                    });
                });

                // Save scroll on select onchange that triggers form submit
                document.querySelectorAll('select[onchange]').forEach(function(sel) {
                    sel.addEventListener('change', function() {
                        sessionStorage.setItem(key, window.scrollY);
                    });
                });

                // Save scroll on links staying on the same page (pagination, view toggle, sort headers)
                document.querySelectorAll('a[href]').forEach(function(link) {
                    link.addEventListener('click', function() {
                        try {
                            var linkUrl = new URL(this.href, location.origin);
                            if (linkUrl.pathname === location.pathname) {
                                sessionStorage.setItem(key, window.scrollY);
                            }
                        } catch(e) {}
                    });
                });

                // --- Sidebar Scroll Preservation & Auto-scroll Active Item ---
                const sidebarScroll = document.getElementById('sidebar-scroll');
                if (sidebarScroll) {
                    const sidebarKey = 'sidebarScrollY';
                    const savedSidebar = sessionStorage.getItem(sidebarKey);

                    if (savedSidebar !== null) {
                        // Restore saved scroll position
                        sidebarScroll.scrollTop = parseInt(savedSidebar);
                        sessionStorage.removeItem(sidebarKey);
                    } else {
                        // If no saved position, auto-scroll the active menu item into view
                        const activeItem = sidebarScroll.querySelector('a.bg-purple-600, a.bg-blue-600, a.bg-amber-600, a.bg-emerald-600, a.bg-rose-600, a.bg-indigo-600, a.bg-teal-600, a.bg-slate-700');
                        if (activeItem) {
                            // Use setTimeout to ensure sidebar layout is complete before scrolling
                            setTimeout(function() {
                                activeItem.scrollIntoView({ block: 'nearest', behavior: 'instant' });
                            }, 50);
                        }
                    }

                    // Save scroll position when any link inside sidebar is clicked
                    sidebarScroll.querySelectorAll('a[href]').forEach(function(link) {
                        link.addEventListener('click', function() {
                            sessionStorage.setItem(sidebarKey, sidebarScroll.scrollTop);
                        });
                    });
                }
            });
        </script>
    </body>
</html>