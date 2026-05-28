@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fbfaff; color: #1e293b; }
</style>

<div class="space-y-8">
    <!-- Header Summary Card -->
    <div class="relative overflow-hidden bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-700 text-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-purple-500/10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -ml-20 -mb-20"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-8">
            <div class="space-y-3">
                <span class="inline-block bg-white/20 text-white text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full">
                    Finance Hub
                </span>
                <p class="text-violet-200 text-xs font-bold uppercase tracking-widest">Total Saldo Kas RT</p>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight">Rp {{ number_format($saldo) }}</h1>
                <p class="text-violet-100 text-xs font-semibold flex items-center gap-1.5"><i class="fas fa-bolt"></i> Terhubung langsung ke Sistem Digital RT</p>
            </div>
            
            <div class="flex items-center">
                <a href="{{ route('bendahara.entri') }}" class="px-6 py-4 bg-white text-violet-700 hover:bg-violet-50 text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg transition duration-300 flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Catat Transaksi Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Income & Expense Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pemasukan -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-arrow-down-long"></i>
            </div>
            <div class="space-y-1">
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pemasukan</span>
                <span class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($totalPemasukan) }}</span>
                <span class="block text-[10px] text-emerald-500">Iuran Masuk Terpembukukan</span>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-arrow-up-long"></i>
            </div>
            <div class="space-y-1">
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pengeluaran</span>
                <span class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($totalPengeluaran) }}</span>
                <span class="block text-[10px] text-rose-500">Dana Terpakai Lingkungan</span>
            </div>
        </div>
    </div>

    <!-- Grafik Real-Time Tren Arus Kas -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8 space-y-4">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800">Tren Arus Kas Keuangan</h3>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Visualisasi tren pemasukan dan pengeluaran kas RT</p>
        </div>
        <div class="h-80 w-full">
            <canvas id="cashFlowChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById('cashFlowChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            // Mengambil 10 data kas terbaru dan membaliknya agar berurutan secara kronologis
            const rawData = @json($kas->take(10)->reverse()->values()->map(function($item) {
                return [
                    'tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d M'),
                    'masuk' => $item->pemasukan,
                    'keluar' => $item->pengeluaran
                ];
            }));

            const labels = rawData.map(item => item.tanggal);
            const dataMasuk = rawData.map(item => item.masuk);
            const dataKeluar = rawData.map(item => item.keluar);

            // Membuat gradien warna yang cantik untuk area bawah grafik
            const gradMasuk = ctx.createLinearGradient(0, 0, 0, 300);
            gradMasuk.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradMasuk.addColorStop(1, 'rgba(16, 185, 129, 0)');

            const gradKeluar = ctx.createLinearGradient(0, 0, 0, 300);
            gradKeluar.addColorStop(0, 'rgba(239, 68, 68, 0.25)');
            gradKeluar.addColorStop(1, 'rgba(239, 68, 68, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pemasukan (Rp)',
                            data: dataMasuk,
                            borderColor: '#10b981',
                            backgroundColor: gradMasuk,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointHoverRadius: 7
                        },
                        {
                            label: 'Pengeluaran (Rp)',
                            data: dataKeluar,
                            borderColor: '#ef4444',
                            backgroundColor: gradKeluar,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ef4444',
                            pointHoverRadius: 7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    weight: 'bold',
                                    size: 11
                                },
                                color: '#64748b'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                            bodyFont: { family: 'Plus Jakarta Sans' },
                            padding: 12,
                            cornerRadius: 16
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: { family: 'Plus Jakarta Sans', weight: '650', size: 10 },
                                color: '#64748b',
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { family: 'Plus Jakarta Sans', weight: '650', size: 10 },
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        });
    </script>

    <!-- Aktivitas Terakhir & Filter -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-slate-800">Aktivitas Arus Kas</h3>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Jurnal Catatan Keuangan Lingkungan</p>
            </div>

            <!-- Days Filter Buttons -->
            <div class="flex gap-1.5 bg-slate-100 p-1 rounded-2xl text-[10px] font-bold">
                <a class="px-4 py-2 rounded-xl transition {{ !request('days') || request('days') == 'latest' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}" href="{{ request()->fullUrlWithQuery(['days' => 'latest']) }}">Terbaru</a>
                <a class="px-4 py-2 rounded-xl transition {{ request('days') == 3 ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}" href="{{ request()->fullUrlWithQuery(['days' => 3]) }}">3 Hari</a>
                <a class="px-4 py-2 rounded-xl transition {{ request('days') == 7 ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}" href="{{ request()->fullUrlWithQuery(['days' => 7]) }}">7 Hari</a>
                <a class="px-4 py-2 rounded-xl transition {{ request('days') == 30 ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}" href="{{ request()->fullUrlWithQuery(['days' => 30]) }}">30 Hari</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="pb-4">Detail Transaksi</th>
                        <th class="pb-4">Waktu</th>
                        <th class="pb-4 text-center">Nominal</th>
                        <th class="pb-4 text-right">Kategori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kas as $item)
                    <tr class="group">
                        <td class="py-5">
                            <div class="font-extrabold text-slate-800 text-sm group-hover:text-violet-600 transition-colors">{{ $item->keterangan }}</div>
                        </td>
                        <td class="py-5 text-xs text-slate-400 font-semibold">
                            {{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}
                        </td>
                        <td class="py-5 text-center font-black text-sm {{ $item->pemasukan > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $item->pemasukan > 0 ? '+ Rp '.number_format($item->pemasukan) : '- Rp '.number_format($item->pengeluaran) }}
                        </td>
                        <td class="py-5 text-right">
                            <span class="inline-block text-[9px] font-black uppercase px-2.5 py-1 rounded-lg {{ $item->pemasukan > 0 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                {{ $item->pemasukan > 0 ? 'MASUK' : 'KELUAR' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-slate-400 text-sm font-semibold">Belum ada transaksi kas tercatat untuk periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection