<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                </svg>
            </div>
            <h2 class="font-black text-2xl text-blue-900 leading-tight">Daftar Pemasukan Kas</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-[2rem] p-8 mb-8 shadow-sm border border-green-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Uang Masuk</p>
                <h3 class="text-3xl font-black text-green-600 mt-1">Rp {{ number_format($total_pemasukan) }}</h3>
            </div>
            <div class="p-4 bg-green-50 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-blue-50 overflow-hidden">
            <div class="overflow-x-auto p-6">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-gray-400 text-xs font-black uppercase tracking-widest">
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3 text-center">Detail</th>
                            <th class="px-6 py-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_pemasukan as $item)
                        <tr class="bg-gray-50/50 hover:bg-green-50/30 transition">
                            <td class="px-6 py-4 rounded-l-2xl text-sm font-bold text-gray-400">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-blue-900 text-center uppercase tracking-tight">
                                {{ $item->keterangan }}
                            </td>
                            <td class="px-6 py-4 rounded-r-2xl text-right font-black text-green-600 flex items-center justify-end">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                                Rp {{ number_format($item->pemasukan) }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-10 text-center text-gray-400 italic font-medium">Data kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>