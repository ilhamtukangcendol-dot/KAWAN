@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;850&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fbfaff; }
</style>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Basis Data Kependudukan</h2>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Statistik kelompok usia dan daftar warga RT.001</p>
        </div>
        @if($kategori || $search)
        <a href="{{ route('ketua.warga') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black uppercase tracking-widest rounded-xl transition duration-300 flex items-center gap-2">
            <i class="fas fa-undo"></i> Tampilkan Semua Warga
        </a>
        @endif
    </div>

    <!-- Clickable Age Categories Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Bayi -->
        <a href="{{ route('ketua.warga', ['kategori' => 'bayi', 'search' => $search]) }}" 
           class="p-5 rounded-3xl border transition duration-300 flex flex-col justify-between h-32 hover:scale-[1.03] shadow-sm {{ $kategori == 'bayi' ? 'bg-sky-600 border-sky-600 text-white shadow-sky-500/20' : 'bg-white border-slate-100 hover:shadow-md' }}">
            <div class="flex justify-between items-center">
                <span class="text-[9px] font-black uppercase tracking-widest opacity-85">Bayi (0-2)</span>
                <i class="fas fa-baby-carriage text-sm {{ $kategori == 'bayi' ? 'text-sky-200' : 'text-sky-500' }}"></i>
            </div>
            <div>
                <span class="block text-2xl font-black">{{ $statBayi }}</span>
                <span class="text-[9px] font-semibold opacity-70">Warna Sky</span>
            </div>
        </a>

        <!-- Balita -->
        <a href="{{ route('ketua.warga', ['kategori' => 'balita', 'search' => $search]) }}" 
           class="p-5 rounded-3xl border transition duration-300 flex flex-col justify-between h-32 hover:scale-[1.03] shadow-sm {{ $kategori == 'balita' ? 'bg-blue-600 border-blue-600 text-white shadow-blue-500/20' : 'bg-white border-slate-100 hover:shadow-md' }}">
            <div class="flex justify-between items-center">
                <span class="text-[9px] font-black uppercase tracking-widest opacity-85">Balita (3-5)</span>
                <i class="fas fa-baby text-sm {{ $kategori == 'balita' ? 'text-blue-200' : 'text-blue-500' }}"></i>
            </div>
            <div>
                <span class="block text-2xl font-black">{{ $statBalita }}</span>
                <span class="text-[9px] font-semibold opacity-70">Warna Biru</span>
            </div>
        </a>

        <!-- Anak-anak -->
        <a href="{{ route('ketua.warga', ['kategori' => 'anak', 'search' => $search]) }}" 
           class="p-5 rounded-3xl border transition duration-300 flex flex-col justify-between h-32 hover:scale-[1.03] shadow-sm {{ $kategori == 'anak' ? 'bg-emerald-600 border-emerald-600 text-white shadow-emerald-500/20' : 'bg-white border-slate-100 hover:shadow-md' }}">
            <div class="flex justify-between items-center">
                <span class="text-[9px] font-black uppercase tracking-widest opacity-85">Anak (6-12)</span>
                <i class="fas fa-child text-sm {{ $kategori == 'anak' ? 'text-emerald-200' : 'text-emerald-500' }}"></i>
            </div>
            <div>
                <span class="block text-2xl font-black">{{ $statAnak }}</span>
                <span class="text-[9px] font-semibold opacity-70">Warna Hijau</span>
            </div>
        </a>

        <!-- Remaja -->
        <a href="{{ route('ketua.warga', ['kategori' => 'remaja', 'search' => $search]) }}" 
           class="p-5 rounded-3xl border transition duration-300 flex flex-col justify-between h-32 hover:scale-[1.03] shadow-sm {{ $kategori == 'remaja' ? 'bg-violet-600 border-violet-600 text-white shadow-violet-500/20' : 'bg-white border-slate-100 hover:shadow-md' }}">
            <div class="flex justify-between items-center">
                <span class="text-[9px] font-black uppercase tracking-widest opacity-85">Remaja (13-17)</span>
                <i class="fas fa-user-graduate text-sm {{ $kategori == 'remaja' ? 'text-violet-200' : 'text-violet-500' }}"></i>
            </div>
            <div>
                <span class="block text-2xl font-black">{{ $statRemaja }}</span>
                <span class="text-[9px] font-semibold opacity-70">Warna Violet</span>
            </div>
        </a>

        <!-- Dewasa -->
        <a href="{{ route('ketua.warga', ['kategori' => 'dewasa', 'search' => $search]) }}" 
           class="p-5 rounded-3xl border transition duration-300 flex flex-col justify-between h-32 hover:scale-[1.03] shadow-sm {{ $kategori == 'dewasa' ? 'bg-amber-600 border-amber-600 text-white shadow-amber-500/20' : 'bg-white border-slate-100 hover:shadow-md' }}">
            <div class="flex justify-between items-center">
                <span class="text-[9px] font-black uppercase tracking-widest opacity-85">Dewasa (18-59)</span>
                <i class="fas fa-user-tie text-sm {{ $kategori == 'dewasa' ? 'text-amber-200' : 'text-amber-500' }}"></i>
            </div>
            <div>
                <span class="block text-2xl font-black">{{ $statDewasa }}</span>
                <span class="text-[9px] font-semibold opacity-70">Warna Kuning</span>
            </div>
        </a>

        <!-- Lansia -->
        <a href="{{ route('ketua.warga', ['kategori' => 'lansia', 'search' => $search]) }}" 
           class="p-5 rounded-3xl border transition duration-300 flex flex-col justify-between h-32 hover:scale-[1.03] shadow-sm {{ $kategori == 'lansia' ? 'bg-rose-600 border-rose-600 text-white shadow-rose-500/20' : 'bg-white border-slate-100 hover:shadow-md' }}">
            <div class="flex justify-between items-center">
                <span class="text-[9px] font-black uppercase tracking-widest opacity-85">Lansia (60+)</span>
                <i class="fas fa-blind text-sm {{ $kategori == 'lansia' ? 'text-rose-200' : 'text-rose-500' }}"></i>
            </div>
            <div>
                <span class="block text-2xl font-black">{{ $statLansia }}</span>
                <span class="text-[9px] font-semibold opacity-70">Warna Merah</span>
            </div>
        </a>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-800">
                    @if($kategori)
                        Data Kelompok Usia: <span class="capitalize text-indigo-600">{{ $kategori }}</span>
                    @else
                        Semua Warga Terdaftar
                    @endif
                </h3>
                @if($search)
                <p class="text-xs text-slate-400 mt-1 font-semibold">Hasil pencarian untuk: <span class="text-blue-600 font-bold">"{{ $search }}"</span></p>
                @endif
            </div>

            <!-- Search Bar Form -->
            <form method="GET" action="{{ route('ketua.warga') }}" class="flex items-center gap-2 max-w-md w-full md:w-80">
                @if($kategori)
                    <input type="hidden" name="kategori" value="{{ $kategori }}">
                @endif
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, atau KK..." 
                        class="block w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-300">
                    @if($search)
                    <a href="{{ route('ketua.warga', ['kategori' => $kategori]) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times-circle text-xs"></i>
                    </a>
                    @endif
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition duration-300 shadow-md shadow-blue-500/10 shrink-0">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="pb-4">Nama Lengkap / NIK</th>
                        <th class="pb-4">No. Kartu Keluarga</th>
                        <th class="pb-4 text-center">Usia</th>
                        <th class="pb-4 text-center">Status Hunian</th>
                        <th class="pb-4 text-right">Alamat Rumah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($daftarWarga as $warga)
                    <tr class="group hover:bg-slate-50/50 transition">
                        <td class="py-4 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full overflow-hidden border border-slate-100 flex items-center justify-center shadow-inner shrink-0">
                                @if($warga->avatar_url)
                                    <img src="{{ $warga->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full {{ $warga->jenis_kelamin == 'L' ? 'bg-indigo-50 text-indigo-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center font-black text-xs">
                                        {{ strtoupper(substr($warga->nama_lengkap, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-extrabold text-slate-800 text-sm leading-none mb-1">{{ $warga->nama_lengkap }}</p>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider leading-none">NIK: {{ $warga->nik }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-xs font-semibold text-slate-500">{{ $warga->no_kk }}</td>
                        <td class="py-4 text-center font-bold text-slate-700 text-xs">
                            {{ $warga->umur }} Tahun
                        </td>
                        <td class="py-4 text-center">
                            @if($warga->status_tinggal == 'Milik Sendiri')
                                <span class="inline-block text-[9px] font-black uppercase px-2.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg">
                                    Rumah Sendiri
                                </span>
                            @else
                                <span class="inline-block text-[9px] font-black uppercase px-2.5 py-0.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg">
                                    Kontrak / Ngontrak
                                </span>
                            @endif
                        </td>
                        <td class="py-4 text-right text-xs font-semibold text-slate-400 max-w-[200px] truncate">
                            {{ $warga->alamat ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-slate-400 text-sm font-semibold">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-user-slash text-3xl text-slate-200"></i>
                                <span>Tidak ditemukan data warga dalam kategori kelompok usia ini.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarWarga->hasPages())
        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
            {{ $daftarWarga->links() }}
        </div>
        @endif
    </div>
</div>
@endsection