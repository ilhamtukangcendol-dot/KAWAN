@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;850&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fbfaff; }
</style>

<div class="space-y-8 max-w-3xl mx-auto">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-black text-slate-800">Catat Pembayaran Kas Warga</h2>
        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Otoritas Ketua RT untuk memproses pembayaran kas/iuran masuk dari warga</p>
    </div>

    <!-- Saldo & Status Alert -->
    <div class="grid grid-cols-1 gap-6">
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-850 to-slate-950 text-white p-6 md:p-8 rounded-[2.5rem] shadow-xl border border-slate-700/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
            
            <div class="relative z-10 space-y-1">
                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Saldo Kas RT Saat Ini</span>
                <h3 class="text-3xl font-black text-emerald-400">Rp {{ number_format($saldo) }}</h3>
            </div>
            <div class="relative z-10 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-2 rounded-xl text-[10px] font-extrabold uppercase tracking-wider">
                <i class="fas fa-arrow-down-long mr-1.5"></i> Pencatatan Pemasukan Kas
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-10 space-y-6">
        <!-- Error Alerts -->
        @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl text-xs font-semibold space-y-1">
            @foreach($errors->all() as $error)
                <p><i class="fas fa-exclamation-circle mr-1.5"></i> {{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('ketua.payment.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Pilih Warga Dropdown -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Penyetor (Warga RT.001)</label>
                <div class="relative">
                    <select name="warga_id" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-emerald-500 text-sm py-3.5 px-4 appearance-none font-semibold text-slate-700" required>
                        <option value="" disabled selected>-- Pilih Warga Penyetor --</option>
                        @foreach($daftarWarga as $warga)
                            <option value="{{ $warga->id }}" {{ old('warga_id') == $warga->id ? 'selected' : '' }}>
                                {{ $warga->nama_lengkap }} (NIK: {{ $warga->nik }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nominal -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal Setoran / Iuran (Rp)</label>
                    <input type="number" name="nominal" value="{{ old('nominal') }}" placeholder="Contoh: 100000" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-emerald-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
                </div>

                <!-- Tanggal -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Setoran</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-emerald-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan / Sumber Dana</label>
                <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Pembayaran Iuran Wajib Bulanan, Sumbangan Gotong Royong" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-emerald-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i> Catat Pemasukan Kas RT
            </button>
        </form>
    </div>
</div>
@endsection
