<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-blue-900 leading-tight">
                👋 Halo, Warga!
            </h2>
            <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-xs font-bold uppercase">Akses Publik</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-xs font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-blue-50 overflow-hidden">
            <div class="p-12 text-center">
                <div class="inline-flex p-4 bg-blue-50 rounded-full mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-gray-500 font-medium uppercase tracking-widest text-sm">Saldo Kas RT Saat Ini</h3>
                <p class="text-6xl font-black text-blue-900 mt-4 mb-2">Rp {{ number_format($saldo) }}</p>
                <p class="text-green-500 font-bold flex items-center justify-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                    Transparan & Terbuka
                </p>
            </div>
            
            <div class="bg-blue-50/50 p-8 border-t border-blue-50 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="text-gray-600">
                    <h4 class="font-bold text-blue-900 mb-2">Kenapa transparansi penting?</h4>
                    <p>Setiap rupiah yang Anda bayarkan digunakan untuk keperluan bersama: keamanan, kebersihan, dan pemeliharaan lingkungan.</p>
                </div>
                <div class="text-gray-600">
                    <h4 class="font-bold text-blue-900 mb-2">Butuh bantuan?</h4>
                    <p>Jika ada ketidaksesuaian data atau ingin bertanya mengenai iuran, silakan hubungi pengurus RT melalui grup WhatsApp resmi.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>