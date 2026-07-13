<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KAWAN - Komunikasi & Aplikasi Warga Nyaman') }}</title>

        <!-- Theme Initialization Script -->
        <script>
            function getTheme() {
                return localStorage.getItem('theme') || 'dark';
            }
            function initTheme() {
                const theme = getTheme();
                if (theme === 'light') {
                    document.documentElement.classList.add('light-theme');
                } else {
                    document.documentElement.classList.remove('light-theme');
                }
                updateToggleIcons();
            }
            function toggleTheme() {
                const currentTheme = getTheme();
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('theme', newTheme);
                initTheme();
            }
            function updateToggleIcons() {
                document.addEventListener('DOMContentLoaded', () => {
                    const theme = getTheme();
                    const sunIcon = document.getElementById('sun-icon-guest');
                    const moonIcon = document.getElementById('moon-icon-guest');
                    if (sunIcon && moonIcon) {
                        if (theme === 'light') {
                            sunIcon.classList.add('hidden');
                            moonIcon.classList.remove('hidden');
                        } else {
                            sunIcon.classList.remove('hidden');
                            moonIcon.classList.add('hidden');
                        }
                    }
                });
            }
            initTheme();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            @keyframes morph {
                0% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; transform: translate(0, 0) rotate(0deg) scale(1); }
                33% { border-radius: 70% 30% 52% 48% / 60% 40% 60% 40%; transform: translate(80px, -50px) rotate(120deg) scale(1.15); }
                66% { border-radius: 50% 50% 30% 70% / 40% 60% 40% 60%; transform: translate(-40px, 60px) rotate(240deg) scale(0.9); }
                100% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; transform: translate(0, 0) rotate(360deg) scale(1); }
            }
            @keyframes morphReverse {
                0% { border-radius: 70% 30% 52% 48% / 60% 40% 60% 40%; transform: translate(0, 0) rotate(360deg) scale(1); }
                50% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; transform: translate(-80px, 50px) rotate(180deg) scale(1.2); }
                100% { border-radius: 70% 30% 52% 48% / 60% 40% 60% 40%; transform: translate(0, 0) rotate(0deg) scale(1); }
            }
            @keyframes sparkle {
                0%, 100% { transform: translateY(0) rotate(0deg) scale(0.8); opacity: 0.15; }
                50% { transform: translateY(-80px) rotate(180deg) scale(1.3); opacity: 0.5; }
            }
            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @keyframes zoomPanBg {
                0% { transform: scale(1.04) translate(0, 0); }
                50% { transform: scale(1.12) translate(-8px, -12px); }
                100% { transform: scale(1.04) translate(0, 0); }
            }
            
            .full-bg-container {
                position: fixed;
                inset: 0;
                z-index: 0;
                overflow: hidden;
                pointer-events: none;
            }
            .full-bg-image {
                position: absolute;
                inset: -20px;
                background-image: url('{{ asset("images/indo_gotong_royong.png") }}');
                background-size: cover;
                background-position: center;
                animation: zoomPanBg 28s ease-in-out infinite;
                filter: brightness(0.35) saturate(1.2);
                transition: filter 1s ease;
            }
            .full-bg-overlay {
                position: absolute;
                inset: 0;
                background: radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(2, 6, 23, 0.88) 100%);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                transition: background 1s ease;
            }
            
            .liquid-blob {
                position: absolute;
                filter: blur(80px);
                mix-blend-mode: screen;
                opacity: 0.3;
                transition: background-color 1s ease, opacity 1s ease;
                pointer-events: none;
                z-index: 0;
            }
            
            /* Dark Mode Blob Colors (Vibrant Neon) */
            .blob-1 {
                background: radial-gradient(circle, #ff007f 0%, transparent 80%);
                width: 650px;
                height: 650px;
                top: -10%;
                left: -10%;
                animation: morph 20s ease-in-out infinite;
            }
            .blob-2 {
                background: radial-gradient(circle, #7f00ff 0%, transparent 80%);
                width: 700px;
                height: 700px;
                bottom: -15%;
                right: -10%;
                animation: morphReverse 25s ease-in-out infinite;
            }
            .blob-3 {
                background: radial-gradient(circle, #00ffff 0%, transparent 80%);
                width: 500px;
                height: 500px;
                top: 30%;
                right: 15%;
                animation: morph 18s ease-in-out infinite;
            }
            .blob-4 {
                background: radial-gradient(circle, #39ff14 0%, transparent 80%);
                width: 550px;
                height: 550px;
                bottom: 20%;
                left: 10%;
                animation: morphReverse 22s ease-in-out infinite;
            }

            .sparkle-star {
                position: absolute;
                fill: currentColor;
                animation: sparkle 10s ease-in-out infinite;
                pointer-events: none;
                z-index: 0;
            }
            .star-color {
                color: #fbbf24;
                transition: color 1s ease;
            }

            .bg-grid-pattern {
                background-image: radial-gradient(rgba(244, 63, 94, 0.08) 1.5px, transparent 1.5px);
                background-size: 28px 28px;
                animation: gridMove 10s linear infinite;
            }
            @keyframes gridMove {
                from { background-position: 0 0; }
                to { background-position: 28px 28px; }
            }
            .animated-gradient-border {
                background: linear-gradient(90deg, #f43f5e, #fbbf24, #10b981, #6366f1, #f43f5e);
                background-size: 300% 100%;
                animation: gradientShift 6s linear infinite;
            }
            .animate-card-load {
                animation: fadeInUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            /* ================= LIGHT THEME OVERRIDES ================= */
            html.light-theme body {
                background-color: #f8fafc !important;
                color: #0f172a !important;
            }
            html.light-theme .bg-grid-pattern {
                background-image: radial-gradient(rgba(15, 23, 42, 0.05) 1.5px, transparent 1.5px) !important;
            }
            html.light-theme .liquid-blob {
                mix-blend-mode: multiply !important;
                opacity: 0.22 !important;
                filter: blur(100px) !important;
            }
            html.light-theme .blob-1 {
                background: radial-gradient(circle, #ff9a9e 0%, transparent 80%) !important;
            }
            html.light-theme .blob-2 {
                background: radial-gradient(circle, #a1c4fd 0%, transparent 80%) !important;
            }
            html.light-theme .blob-3 {
                background: radial-gradient(circle, #fecfef 0%, transparent 80%) !important;
            }
            html.light-theme .blob-4 {
                background: radial-gradient(circle, #fbc2eb 0%, transparent 80%) !important;
            }
            html.light-theme .star-color {
                color: #ec4899 !important;
            }
            html.light-theme .w-full.sm:max-w-md {
                background-color: rgba(255, 255, 255, 0.75) !important;
                border-color: #cbd5e1 !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08) !important;
                border-radius: 2.5rem !important;
                clip-path: none !important;
            }
            html.light-theme .w-full.sm:max-w-md h2 {
                color: #0f172a !important;
            }
            html.light-theme .w-full.sm:max-w-md p, html.light-theme .w-full.sm:max-w-md span {
                color: #475569 !important;
            }
            html.light-theme .w-full.sm:max-w-md label {
                color: #475569 !important;
            }
            html.light-theme .w-full.sm:max-w-md input[type="email"], html.light-theme .w-full.sm:max-w-md input[type="password"], html.light-theme .w-full.sm:max-w-md input[type="text"] {
                background-color: #ffffff !important;
                border-color: #cbd5e1 !important;
                color: #0f172a !important;
                border-radius: 1rem !important;
            }
            html.light-theme .w-full.sm:max-w-md input::placeholder {
                color: #94a3b8 !important;
            }
            html.light-theme .w-full.sm:max-w-md input[type="checkbox"] {
                background-color: #ffffff !important;
                border-color: #cbd5e1 !important;
                border-radius: 0.25rem !important;
            }
            html.light-theme .w-full.sm:max-w-md .border-t {
                border-color: #cbd5e1 !important;
            }
            html.light-theme .w-full.sm:max-w-md button[type="button"] {
                background-color: #ffffff !important;
                border-color: #cbd5e1 !important;
                color: #334155 !important;
                border-radius: 0.75rem !important;
            }
            html.light-theme .w-full.sm:max-w-md button[type="button"]:hover {
                background-color: #f1f5f9 !important;
            }
            html.light-theme a.group {
                background-color: rgba(255, 255, 255, 0.8) !important;
                border-color: #cbd5e1 !important;
                color: #475569 !important;
                border-radius: 9999px !important;
            }
            html.light-theme a.group:hover {
                color: #0f172a !important;
                background-color: #ffffff !important;
                border-radius: 9999px !important;
            }
            html.light-theme .full-bg-image {
                filter: brightness(0.95) saturate(1.1) contrast(0.9) !important;
            }
            html.light-theme .full-bg-overlay {
                background: radial-gradient(circle at center, rgba(248, 250, 252, 0.45) 0%, rgba(226, 232, 240, 0.85) 100%) !important;
            }
        </style>
    </head>
    <body class="bg-slate-950 text-slate-100 antialiased selection:bg-rose-600 selection:text-white overflow-x-hidden min-h-screen relative flex items-center justify-center py-12">
        
        <!-- Ambient Glowing Background Blobs (Dynamic Liquid Mesh) -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="liquid-blob blob-1"></div>
            <div class="liquid-blob blob-2"></div>
            <div class="liquid-blob blob-3"></div>
            <div class="liquid-blob blob-4"></div>
        </div>

        <!-- Animated Grid Pattern Overlay -->
        <div class="fixed inset-0 bg-grid-pattern pointer-events-none z-0"></div>

        <!-- Floating Sparkles (Cyber Magic Vibe) -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <svg class="sparkle-star star-color w-6 h-6 top-[15%] left-[20%] animate-delay-100" viewBox="0 0 24 24">
                <path d="M12 0L14.6 9.4L24 12L14.6 14.6L12 24L9.4 14.6L0 12L9.4 9.4Z"/>
            </svg>
            <svg class="sparkle-star star-color w-8 h-8 top-[60%] left-[10%] animate-delay-[3s]" style="animation-duration: 14s;" viewBox="0 0 24 24">
                <path d="M12 0L14.6 9.4L24 12L14.6 14.6L12 24L9.4 14.6L0 12L9.4 9.4Z"/>
            </svg>
            <svg class="sparkle-star star-color w-5 h-5 top-[25%] right-[20%] animate-delay-[1.5s]" style="animation-duration: 8s;" viewBox="0 0 24 24">
                <path d="M12 0L14.6 9.4L24 12L14.6 14.6L12 24L9.4 14.6L0 12L9.4 9.4Z"/>
            </svg>
            <svg class="sparkle-star star-color w-7 h-7 top-[75%] right-[15%] animate-delay-[4.5s]" style="animation-duration: 12s;" viewBox="0 0 24 24">
                <path d="M12 0L14.6 9.4L24 12L14.6 14.6L12 24L9.4 14.6L0 12L9.4 9.4Z"/>
            </svg>
        </div>

        <!-- Full 1 Ratio Background Cover Image with Zoom Parallax & Overlay -->
        <div class="full-bg-container">
            <div class="full-bg-image"></div>
            <div class="full-bg-overlay"></div>
        </div>

        <div class="w-full relative z-10 px-4 flex flex-col items-center">
            
            <!-- Back to Home & Theme Toggle Buttons -->
            <div class="mb-6 animate-card-load [animation-delay:100ms] opacity-0 flex items-center gap-3" style="animation-fill-mode: forwards;">
                <a href="/" class="group flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900/50 hover:bg-slate-900 border border-slate-800/80 hover:border-rose-500/30 text-xs font-semibold text-slate-400 hover:text-white transition duration-300 shadow-xl">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
                
                <!-- Theme Toggle Button -->
                <button onclick="toggleTheme()" class="p-2 rounded-full bg-slate-900/50 hover:bg-slate-900 border border-slate-800/80 hover:border-rose-500/30 text-slate-400 hover:text-white transition duration-300 shadow-xl flex items-center justify-center" aria-label="Toggle Theme">
                    <svg id="sun-icon-guest" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21M4.93 4.93l1.59 1.59m10.96 10.96l1.59 1.59M3 12h2.25m13.5 0H21m-16.07 6.07l1.59-1.59M16.24 6.24l1.59-1.59M12 7.5a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Z" />
                    </svg>
                    <svg id="moon-icon-guest" class="w-4 h-4 text-slate-500 hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>
            </div>

            <!-- Sleek Glassmorphism Card Container with Entry Animation -->
            <div class="w-full sm:max-w-md px-8 py-10 rounded-[2.5rem] backdrop-blur-3xl bg-slate-900/40 border border-slate-800/80 shadow-2xl relative overflow-hidden group hover:border-slate-700/60 transition duration-500 animate-card-load [animation-delay:250ms] opacity-0" style="animation-fill-mode: forwards; hover:shadow-[0_0_50px_rgba(244,63,94,0.1)]">
                <!-- Glowing Rotating/Shifting Border Top Highlight -->
                <div class="absolute top-0 inset-x-0 h-[3px] animated-gradient-border opacity-70 group-hover:opacity-100 transition duration-500"></div>
                
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-slate-500 text-[10px] font-bold tracking-widest uppercase">© {{ date('Y') }} KAWAN &bull; Komunikasi & Aplikasi Warga Nyaman 🇮🇩</p>
        </div>
    </body>
</html>