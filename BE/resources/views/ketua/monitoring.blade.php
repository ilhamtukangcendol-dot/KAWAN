@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
</style>

<div class="space-y-8">
    <!-- Header Title Area -->
    <div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Monitoring Utama</h2>
        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Analisis Kesehatan Ekonomi RT & Riwayat Grafik Aliran Kas</p>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Total Pemasukan Card -->
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-between h-40 hover:shadow-md transition-all">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pemasukan</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shadow-inner">
                    <i class="fas fa-arrow-down-long"></i>
                </div>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800 tracking-tight">Rp {{ number_format($totalPemasukan) }}</h4>
                <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Card -->
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-between h-40 hover:shadow-md transition-all">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pengeluaran</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shadow-inner">
                    <i class="fas fa-arrow-up-long"></i>
                </div>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800 tracking-tight">Rp {{ number_format($totalPengeluaran) }}</h4>
                @php $persen = $totalPemasukan > 0 ? ($totalPengeluaran / $totalPemasukan) * 100 : 0; @endphp
                <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-rose-500" style="width: min({{ $persen }}, 100)%"></div>
                </div>
                <p class="text-[9px] text-slate-400 mt-2 font-bold uppercase tracking-wider flex items-center gap-1">
                    <i class="fas fa-percentage text-rose-500"></i> {{ number_format($persen, 1) }}% dari pemasukan
                </p>
            </div>
        </div>

        <!-- Saldo Efektif Card -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-800 p-6 rounded-[2.5rem] shadow-lg text-white flex flex-col justify-between h-40 hover:scale-[1.01] transition-transform">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-black text-blue-200 uppercase tracking-widest">Saldo Efektif Saat Ini</span>
                <div class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center text-sm">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div>
                <h4 class="text-3xl font-black tracking-tight">Rp {{ number_format($saldo) }}</h4>
                <div class="mt-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[10px] text-blue-100 font-extrabold uppercase tracking-widest">Status: Terverifikasi SIKAS</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-Time Cash Flow Chart Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-slate-800">Tren Grafik Aliran Kas</h3>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Visualisasi real-time kronologi pemasukan & pengeluaran kas lingkungan</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pemasukan</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pengeluaran</span>
                </div>
            </div>
        </div>
        <div class="h-80 w-full">
            <canvas id="monitoringFlowChart"></canvas>
        </div>
    </div>

    <!-- Analysis Summary Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">
        <div>
            <h3 class="text-lg font-extrabold text-slate-800">Ringkasan Analisis Keuangan</h3>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Tinjauan analitik untuk panduan kebijakan Ketua RT</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kesehatan Kas -->
            <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100/50 flex gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg shrink-0 shadow-sm">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="space-y-1">
                    <h5 class="font-extrabold text-blue-900 text-sm">Kesehatan Kas RT</h5>
                    <p class="text-xs text-slate-500 font-semibold leading-relaxed">
                        Saat ini saldo kas berada dalam kondisi <strong class="text-blue-700 uppercase">{{ $saldo >= 0 ? 'Surplus (Sehat)' : 'Defisit (Perlu Perbaikan)' }}</strong>. 
                        Pastikan penggunaan alokasi dana tetap terencana dengan baik sesuai dengan Anggaran Pendapatan Belanja RT yang disepakati warga.
                    </p>
                </div>
            </div>

            <!-- Saran Kebijakan -->
            <div class="p-6 bg-amber-50/50 rounded-3xl border border-amber-100/50 flex gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg shrink-0 shadow-sm">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="space-y-1">
                    <h5 class="font-extrabold text-amber-900 text-sm">Rekomendasi Kebijakan RT</h5>
                    <p class="text-xs text-slate-500 font-semibold leading-relaxed">
                        Disarankan untuk melakukan koordinasi pembukuan berkala dengan Ibu Bendahara di setiap akhir bulan guna memvalidasi kecocokan saldo kas fisik serta kuitansi pengeluaran inventaris lingkungan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Integration script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('monitoringFlowChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        
        // Ambil maksimal 10 data kas terbaru dan balikkan agar mengalir secara kronologis (kiri ke kanan)
        const rawData = @json($riwayatKas->take(10)->reverse()->values()->map(function($item) {
            return [
                'tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d M'),
                'masuk' => $item->pemasukan,
                'keluar' => $item->pengeluaran
            ];
        }));

        const labels = rawData.map(item => item.tanggal);
        const dataMasuk = rawData.map(item => item.masuk);
        const dataKeluar = rawData.map(item => item.keluar);

        // Gradient Pemasukan
        const gradMasuk = ctx.createLinearGradient(0, 0, 0, 300);
        gradMasuk.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
        gradMasuk.addColorStop(1, 'rgba(16, 185, 129, 0)');

        // Gradient Pengeluaran
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
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans' },
                        padding: 12,
                        cornerRadius: 16,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label.split(' ')[0] + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', weight: '650', size: 10 },
                            color: '#94a3b8',
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
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection