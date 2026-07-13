@extends('layouts.app')

@section('content')
<div class="p-8">
    <h2 class="text-2xl font-black text-blue-900 mb-6 uppercase tracking-tighter">Catat Transaksi Baru</h2>
    
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 max-w-2xl">
        <form action="{{ route('kas.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Keterangan</label>
                    <input type="text" name="keterangan" class="w-full border-gray-100 rounded-xl bg-gray-50 focus:ring-indigo-500" placeholder="Contoh: Iuran Keamanan Blok A" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tipe</label>
                        <select name="tipe" class="w-full border-gray-100 rounded-xl bg-gray-50">
                            <option value="masuk">Pemasukan (+)</option>
                            <option value="keluar">Pengeluaran (-)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="w-full border-gray-100 rounded-xl bg-gray-50" placeholder="0" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-gray-100 rounded-xl bg-gray-50">
                </div>

                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                    Simpan ke Buku Kas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection