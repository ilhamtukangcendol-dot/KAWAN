@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;850&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fbfaff; }
</style>

<div class="space-y-8 max-w-2xl mx-auto">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-black text-slate-800">Catat Inventaris Baru</h2>
        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Tambah aset atau fasilitas pendukung lingkungan RT baru</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-10 space-y-6">
        @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl text-xs font-semibold space-y-1">
            @foreach($errors->all() as $error)
                <p><i class="fas fa-exclamation-circle mr-1.5"></i> {{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('bendahara.inventaris.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Barang -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Aset / Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: Sapu Lidi Kerja Bakti, Tenda Pos Ronda" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Qty -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Jumlah Barang (Qty)</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
                </div>

                <!-- Kondisi -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kondisi Awal</label>
                    <select name="kondisi" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
            </div>

            <!-- Tanggal Perolehan -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Perolehan</label>
                <input type="date" name="tanggal_perolehan" value="{{ old('tanggal_perolehan', date('Y-m-d')) }}" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700" required>
            </div>

            <!-- Keterangan -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Catatan / Keterangan Barang</label>
                <textarea name="keterangan" placeholder="Contoh: Diletakkan di Gudang RT Blok C, Pembelian menggunakan anggaran kerja bakti." rows="3" class="w-full border-slate-100 rounded-2xl bg-slate-50 focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700">{{ old('keterangan') }}</textarea>
            </div>

            <!-- Integrasi Kas Checkbox -->
            <div class="p-5 bg-indigo-50/50 rounded-2xl border border-indigo-100 space-y-4">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="beli_pakai_kas" name="beli_pakai_kas" value="1" {{ old('beli_pakai_kas') ? 'checked' : '' }} class="rounded border-slate-200 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                    <label for="beli_pakai_kas" class="text-xs font-extrabold text-indigo-900 cursor-pointer select-none">
                        Beli Pakai Uang Kas RT? (Catat Pengeluaran Otomatis)
                    </label>
                </div>

                <!-- Input Harga Satuan (Hidden by default unless checked) -->
                <div id="harga_satuan_container" class="{{ old('beli_pakai_kas') ? '' : 'hidden' }} space-y-2 transition-all">
                    <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest">Harga Satuan Barang (Rp)</label>
                    <input type="number" id="harga_satuan" name="harga_satuan" value="{{ old('harga_satuan') }}" placeholder="0" class="w-full border-slate-100 rounded-2xl bg-white focus:ring-indigo-500 text-sm py-3.5 px-4 font-semibold text-slate-700">
                    <p class="text-[9px] text-slate-400 font-semibold italic">* Sistem akan mengalikan harga satuan dengan jumlah Qty untuk mendapatkan total pengeluaran kas.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-500/20 transition flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i> Catat Inventaris Aset
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkbox = document.getElementById('beli_pakai_kas');
        const container = document.getElementById('harga_satuan_container');
        const inputHarga = document.getElementById('harga_satuan');

        if (checkbox && container) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    container.classList.remove('hidden');
                    inputHarga.setAttribute('required', 'required');
                } else {
                    container.classList.add('hidden');
                    inputHarga.removeAttribute('required');
                    inputHarga.value = '';
                }
            });
        }
    });
</script>
@endsection
