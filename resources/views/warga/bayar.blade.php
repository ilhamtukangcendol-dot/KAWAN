<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-blue-900 leading-tight">
                💳 Bayar Kas RT Mandiri
            </h2>
            <span class="bg-indigo-100 text-indigo-700 px-4 py-1 rounded-full text-xs font-bold uppercase">Setoran Warga</span>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 py-12 space-y-8">
        <!-- Saldo Kas Public RT Info Card -->
        <div class="bg-gradient-to-r from-blue-900 to-indigo-950 text-white rounded-[2.5rem] p-8 shadow-xl border border-blue-800/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            
            <div class="relative z-10 space-y-1">
                <span class="block text-[9px] font-black text-blue-300 uppercase tracking-widest">Saldo Kas RT Terkumpul (Transparan)</span>
                <h3 class="text-3xl font-black text-emerald-400">Rp {{ number_format($saldo) }}</h3>
            </div>
            <div class="relative z-10 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-2 rounded-xl text-[10px] font-extrabold uppercase tracking-wider flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                Transparansi Real-Time
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-blue-50 p-6 md:p-10 space-y-6">
            @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl text-xs font-semibold space-y-1">
                @foreach($errors->all() as $error)
                    <p><i class="fas fa-exclamation-circle mr-1.5"></i> {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('warga.payment.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nominal -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal Pembayaran Kas (Rp)</label>
                        <input type="number" name="nominal" value="{{ old('nominal') }}" placeholder="Contoh: 50000" class="w-full border-blue-50 rounded-2xl bg-slate-50/50 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
                    </div>

                    <!-- Tanggal -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Setoran</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full border-blue-50 rounded-2xl bg-slate-50/50 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis Iuran / Keperluan Pembayaran</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Iuran Bulanan Wajib Blok A No. 12, Sumbangan HUT RI" class="w-full border-blue-50 rounded-2xl bg-slate-50/50 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
                    <p class="text-[9px] text-slate-400 font-semibold italic">* Sistem akan mendokumentasikan setoran ini secara transparan atas nama akun warga Anda.</p>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-500/20 transition flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Bayar Kas RT Sekarang
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
