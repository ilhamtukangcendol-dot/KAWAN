<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-blue-900 leading-tight">
                💳 Metode Pembayaran Kas RT
            </h2>
            <span class="bg-indigo-100 text-indigo-700 px-4 py-1 rounded-full text-xs font-bold uppercase">Panduan Setoran</span>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-12 space-y-8">
        <!-- Banner Info -->
        <div class="bg-indigo-50 p-6 rounded-[2rem] border border-indigo-100 text-sm text-indigo-900 leading-relaxed font-semibold italic flex gap-3 shadow-inner">
            <i class="fas fa-info-circle text-indigo-600 text-lg mt-0.5 animate-bounce"></i>
            <span>Setelah melakukan transfer bank atau scan QRIS, harap catat data pembayaran Anda secara mandiri di menu <strong>"Bayar Kas RT"</strong> agar otomatis masuk ke pembukuan transparan warga.</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- QRIS Scan -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-150 p-6 md:p-8 space-y-6 flex flex-col items-center text-center">
                <div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-wide">Scan QRIS RT.001</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Pembayaran instan e-wallet (DANA, OVO, GoPay, LinkAja)</p>
                </div>
                
                <!-- Mock QRIS Box -->
                <div class="p-6 bg-slate-50 border-2 border-slate-100 rounded-3xl relative flex flex-col items-center">
                    <div class="w-48 h-48 bg-white border border-slate-200 rounded-2xl flex items-center justify-center shadow-inner overflow-hidden">
                        <!-- QR Simulation using SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-40 h-40 text-slate-800" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2M3 13h6v6H3v-6zm2 2v2h2v-2H5zm13-2h3v3h-3v-3zm1 7h2v2h-2v-2zm-3-3h2v2h-2v-2zm-3-2h2v2h-2v-2zm8-2h1v1h-1v-1zm-6 8h2v2h-2v-2zm8-5v2h-1v-2h1zm-5-3h1v1h-1v-1zm-2 2v1h-1v-1h1zm4-6h1v1h-1V3zm2 1h1v1h-1V4zm-9 9h1v1H8v-1zm0 2h1v1H8v-1zm5 5h1v1h-1v-1zm1-3h1v1h-1v-1zm4-5h1v1h-1v-1zm-6 2h1v1h-1v-1z"/>
                        </svg>
                    </div>
                    <div class="mt-4 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow">
                        QRIS GPN RT DIGITAL
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase italic">* Tersedia 24 jam otomatis terverifikasi warga</p>
            </div>

            <!-- Transfer Bank & Cash -->
            <div class="space-y-6">
                <!-- Bank Transfer -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-150 p-6 md:p-8 space-y-6">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-wide">Transfer Bank</h3>
                        <p class="text-xs text-slate-400 font-semibold mt-1">Rekening Resmi Rukun Tetangga 001</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Mandiri -->
                        <div class="p-4 bg-slate-50/50 border border-slate-100 rounded-2xl flex items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">BANK MANDIRI</span>
                                <h4 class="text-sm font-extrabold text-blue-900 leading-none mt-1.5">123-00-987654-3</h4>
                                <small class="text-[10px] text-slate-400">a.n KAS RT 001 DIGITAL</small>
                            </div>
                            <div class="text-2xl text-blue-600 font-black italic">mandiri</div>
                        </div>

                        <!-- BCA -->
                        <div class="p-4 bg-slate-50/50 border border-slate-100 rounded-2xl flex items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">BANK BCA</span>
                                <h4 class="text-sm font-extrabold text-blue-900 leading-none mt-1.5">765-432-1098</h4>
                                <small class="text-[10px] text-slate-400">a.n KAS RT 001 DIGITAL</small>
                            </div>
                            <div class="text-2xl text-indigo-700 font-black italic">BCA</div>
                        </div>
                    </div>
                </div>

                <!-- Tunai / Cash -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-150 p-6 md:p-8 space-y-4">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wide">Setoran Tunai Manual</h3>
                        <p class="text-xs text-slate-400 font-semibold mt-1">Melalui Bendahara Lingkungan</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl flex gap-3 text-xs text-slate-600 font-semibold">
                        <i class="fas fa-home text-slate-400 text-lg mt-0.5"></i>
                        <div>
                            <p class="font-extrabold text-slate-800 mb-1">Rumah Ibu Bendahara</p>
                            <p class="text-[11px] leading-relaxed">Jl. Mawar No. 12, RT 01/RW 05 (Waktu penyerahan: Setiap hari pukul 08:00 - 20:00 WIB)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
