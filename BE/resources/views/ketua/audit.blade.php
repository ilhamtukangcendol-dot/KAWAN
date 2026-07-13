<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-blue-900 tracking-tighter uppercase">
            Audit <span class="text-indigo-600">Keuangan</span>
        </h2>
        <p class="text-[10px] font-bold text-gray-400 tracking-[0.3em] mt-1">VERIFIKASI TRANSAKSI REAL-TIME</p>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-2xl rounded-[3rem] border border-indigo-50 overflow-hidden">
            <div class="p-10">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h3 class="text-xl font-black text-blue-900 uppercase">Jurnal Transaksi Kas</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Laporan detail masuk & keluar</p>
                    </div>
                    <button onclick="window.print()" class="px-6 py-3 bg-indigo-50 text-indigo-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                        Cetak Laporan
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4">Tipe</th>
                                <th class="px-6 py-4 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatKas as $item)
                            <tr class="bg-gray-50 hover:bg-indigo-50 transition-all duration-300 rounded-2xl group">
                                <td class="px-6 py-5 rounded-l-2xl">
                                    <p class="font-bold text-blue-900 text-sm">{{ $item->created_at->format('d M Y') }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase font-black">{{ $item->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-gray-700">{{ $item->keterangan }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    @if($item->pemasukan > 0)
                                        <span class="px-3 py-1 bg-green-100 text-green-600 text-[9px] font-black uppercase rounded-lg">Masuk</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-600 text-[9px] font-black uppercase rounded-lg">Keluar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right rounded-r-2xl font-black {{ $item->pemasukan > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $item->pemasukan > 0 ? '+ Rp '.number_format($item->pemasukan) : '- Rp '.number_format($item->pengeluaran) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>