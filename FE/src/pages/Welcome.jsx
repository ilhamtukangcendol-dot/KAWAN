import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../api';

export default function Welcome({ theme, toggleTheme }) {
  const [data, setData] = useState({
    saldo: 0,
    totalPemasukan: 0,
    totalPengeluaran: 0,
    pengumumanList: [],
    kegiatanList: [],
    umkmList: [],
    umkmKategori: [],
    pengurusList: [],
    settings: {
      nama_rt: 'RT 01 / RW 05 Komp. Mawar Asri',
      alamat_rt: 'Jl. Mawar Asri No. 1, Bandung',
      kontak_rt: '0812-3456-7890'
    },
    totalWarga: 0,
    totalKK: 0
  });

  const [activeCategory, setActiveCategory] = useState('Semua');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchPublicData = async () => {
      try {
        const response = await api.get('/public-data');
        if (response.data.status === 'success') {
          setData(response.data.data);
        }
      } catch (err) {
        console.warn('Failed to load live data, using fallbacks.', err);
      } finally {
        setLoading(false);
      }
    };
    fetchPublicData();
  }, []);

  const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(value);
  };

  const filteredUmkm = activeCategory === 'Semua' 
    ? data.umkmList 
    : data.umkmList.filter(item => item.kategori === activeCategory);

  return (
    <div className={`min-h-screen font-sans transition-colors duration-500 ${
      theme === 'dark' ? 'bg-slate-950 text-white' : 'bg-slate-50 text-slate-800'
    }`}>
      
      {/* Decorative Grid and mesh glows */}
      <div className="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        {/* Soft Background Blobs */}
        <div className={`absolute top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full filter blur-[120px] opacity-30 transition-colors duration-1000 ${
          theme === 'dark' ? 'bg-rose-500' : 'bg-rose-300'
        }`} />
        <div className={`absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full filter blur-[120px] opacity-30 transition-colors duration-1000 ${
          theme === 'dark' ? 'bg-indigo-500' : 'bg-blue-300'
        }`} />
      </div>

      {/* Navbar Header */}
      <header className={`sticky top-0 z-50 backdrop-blur-md border-b transition duration-500 ${
        theme === 'dark' ? 'bg-slate-950/80 border-slate-900' : 'bg-white/80 border-slate-100'
      }`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
          
          <div className="flex flex-col">
            <span className="text-2xl font-black bg-gradient-to-r from-rose-500 to-rose-600 bg-clip-text text-transparent tracking-widest">
              KAWAN
            </span>
            <span className={`text-[9px] font-bold tracking-widest uppercase ${
              theme === 'dark' ? 'text-slate-400' : 'text-slate-500'
            }`}>
              Komunikasi & Aplikasi Warga Nyaman
            </span>
          </div>

          <div className="flex items-center gap-4">
            <button 
              onClick={toggleTheme}
              className={`p-2.5 rounded-full border transition ${
                theme === 'dark'
                  ? 'bg-slate-900/60 border-slate-850 text-amber-400 hover:bg-slate-800'
                  : 'bg-white/80 border-slate-200 text-slate-700 hover:bg-slate-100'
              }`}
              aria-label="Toggle Theme"
            >
              {theme === 'dark' ? (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="5"/>
                  <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 18.36l1.42-1.42M18.36 4.22l-1.42 1.42"/>
                </svg>
              ) : (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                  <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
              )}
            </button>

            {localStorage.getItem('token') ? (
              <Link 
                to="/dashboard" 
                className="bg-rose-600 hover:bg-rose-500 text-white font-black text-xs uppercase tracking-wider px-5 py-3 rounded-2xl shadow-lg shadow-rose-600/10 transition"
              >
                Dashboard Portal
              </Link>
            ) : (
              <Link 
                to="/login" 
                className="bg-rose-600 hover:bg-rose-500 text-white font-black text-xs uppercase tracking-wider px-6 py-3.5 rounded-2xl shadow-lg shadow-rose-600/15 transition transform hover:-translate-y-0.5"
              >
                Masuk
              </Link>
            )}
          </div>

        </div>
      </header>

      {/* Hero Section */}
      <section className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div className="lg:col-span-7 flex flex-col justify-center text-center lg:text-left">
          <div className="inline-flex self-center lg:self-start items-center gap-2 px-3 py-1.5 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs font-black uppercase tracking-wider mb-6">
            ✨ KAWAN PORTAL RT DIGITAL
          </div>
          <h1 className="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
            Komunikasi & <br />
            <span className="bg-gradient-to-r from-rose-500 to-rose-600 bg-clip-text text-transparent">
              Aplikasi Warga Nyaman
            </span>
          </h1>
          <p className={`mt-6 text-base sm:text-lg font-medium max-w-xl ${
            theme === 'dark' ? 'text-slate-400' : 'text-slate-500'
          }`}>
            Portal digital modern warga {data.settings.nama_rt}. Kelola iuran, cek agenda posyandu, periksa jadwal ronda, hingga jelajahi produk lokal UMKM unggulan RT kita dengan mudah dalam satu pintu.
          </p>

          {/* Core Info Cards */}
          <div className="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-10">
            <div className={`p-4 rounded-2xl border transition ${
              theme === 'dark' ? 'bg-slate-900/40 border-slate-900' : 'bg-white border-slate-200'
            }`}>
              <span className={`text-[10px] font-black uppercase tracking-widest ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>Total Warga</span>
              <p className="text-2xl font-black mt-1 text-rose-500">{data.totalWarga || '180'} Orang</p>
            </div>
            <div className={`p-4 rounded-2xl border transition ${
              theme === 'dark' ? 'bg-slate-900/40 border-slate-900' : 'bg-white border-slate-200'
            }`}>
              <span className={`text-[10px] font-black uppercase tracking-widest ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>Kepala Keluarga</span>
              <p className="text-2xl font-black mt-1 text-rose-500">{data.totalKK || '45'} KK</p>
            </div>
            <div className={`col-span-2 sm:col-span-1 p-4 rounded-2xl border transition ${
              theme === 'dark' ? 'bg-slate-900/40 border-slate-900' : 'bg-white border-slate-200'
            }`}>
              <span className={`text-[10px] font-black uppercase tracking-widest ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>Nominal Iuran</span>
              <p className="text-2xl font-black mt-1 text-rose-500">
                {formatRupiah(data.settings.nominal_iuran || 50000)}
              </p>
            </div>
          </div>
        </div>

        <div className="lg:col-span-5 flex justify-center relative">
          <div className="absolute inset-0 bg-gradient-to-r from-rose-500/20 to-indigo-500/20 rounded-full filter blur-3xl opacity-50 animate-pulse" />
          <img 
            src="http://127.0.0.1:8000/images/smart_rt_hero.png" 
            alt="KAWAN Portal RT" 
            className="w-full max-w-md relative z-10 drop-shadow-2xl transition hover:scale-105 duration-500"
            onError={(e) => {
              // Graceful fallback illustration image if API not running
              e.target.style.display = 'none';
            }}
          />
        </div>
      </section>

      {/* Kas & Keuangan Section */}
      <section className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className={`p-8 rounded-3xl border shadow-xl transition duration-500 ${
          theme === 'dark' ? 'bg-slate-900/40 border-slate-900' : 'bg-white border-slate-200'
        }`}>
          <div className="text-center lg:text-left mb-8">
            <h2 className="text-2xl sm:text-3xl font-black tracking-tight">Kondisi Keuangan Kas RT</h2>
            <p className={`text-sm mt-1 font-semibold ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
              Laporan saldo terkini dan transparan kas warga
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div className={`p-6 rounded-2xl border transition ${
              theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'
            }`}>
              <span className={`text-[10px] font-black uppercase tracking-widest ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>Saldo Saat Ini</span>
              <p className="text-3xl font-black mt-2 text-rose-500">
                {formatRupiah(data.saldo || 12500000)}
              </p>
            </div>
            <div className={`p-6 rounded-2xl border transition ${
              theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'
            }`}>
              <span className={`text-[10px] font-black uppercase tracking-widest ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>Total Pemasukan</span>
              <p className="text-3xl font-black mt-2 text-emerald-500">
                {formatRupiah(data.totalPemasukan || 17200000)}
              </p>
            </div>
            <div className={`p-6 rounded-2xl border transition ${
              theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'
            }`}>
              <span className={`text-[10px] font-black uppercase tracking-widest ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>Total Pengeluaran</span>
              <p className="text-3xl font-black mt-2 text-amber-500">
                {formatRupiah(data.totalPengeluaran || 4700000)}
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Announcements & Activities Side by Side */}
      <section className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {/* Announcements List */}
        <div className={`p-8 rounded-3xl border shadow-xl transition duration-500 ${
          theme === 'dark' ? 'bg-slate-900/40 border-slate-900' : 'bg-white border-slate-200'
        }`}>
          <h3 className="text-2xl font-black tracking-tight mb-6">📢 Pengumuman Warga</h3>
          <div className="space-y-4">
            {data.pengumumanList.length > 0 ? (
              data.pengumumanList.map((item) => (
                <div key={item.id} className={`p-5 rounded-2xl border transition ${
                  theme === 'dark' ? 'bg-slate-950/60 border-slate-850 hover:bg-slate-900/40' : 'bg-slate-50 border-slate-150 hover:bg-slate-100/50'
                }`}>
                  <span className="text-[10px] font-bold bg-rose-500/10 text-rose-500 px-2.5 py-1 rounded-full uppercase tracking-wider">
                    {new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                  </span>
                  <h4 className="text-base font-black mt-3 text-rose-500">{item.judul}</h4>
                  <p className={`text-xs mt-2 font-medium leading-relaxed ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                    {item.konten}
                  </p>
                </div>
              ))
            ) : (
              // Sensible Fallbacks
              <div className="space-y-4">
                <div className={`p-5 rounded-2xl border ${theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'}`}>
                  <span className="text-[10px] font-bold bg-rose-500/10 text-rose-500 px-2.5 py-1 rounded-full">12 Jul 2026</span>
                  <h4 className="text-base font-black mt-3 text-rose-500">Kerja Bakti Massal Kebersihan Saluran</h4>
                  <p className={`text-xs mt-2 font-medium ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                    Dihimbau kepada seluruh warga RT 01 untuk hadir membawa peralatan gotong royong pada hari Minggu pagi pukul 07:00 WIB.
                  </p>
                </div>
                <div className={`p-5 rounded-2xl border ${theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'}`}>
                  <span className="text-[10px] font-bold bg-rose-500/10 text-rose-500 px-2.5 py-1 rounded-full">08 Jul 2026</span>
                  <h4 className="text-base font-black mt-3 text-rose-500">Pembayaran Iuran Bulanan Juli</h4>
                  <p className={`text-xs mt-2 font-medium ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                    Layanan loket setor kas bulanan dibuka di rumah Bendahara RT setiap hari kerja mulai jam 16:00 sampai 20:00 WIB.
                  </p>
                </div>
              </div>
            )}
          </div>
        </div>

        {/* Kegiatan/Activities List */}
        <div className={`p-8 rounded-3xl border shadow-xl transition duration-500 ${
          theme === 'dark' ? 'bg-slate-900/40 border-slate-900' : 'bg-white border-slate-200'
        }`}>
          <h3 className="text-2xl font-black tracking-tight mb-6">📅 Kegiatan Mendatang</h3>
          <div className="space-y-4">
            {data.kegiatanList.length > 0 ? (
              data.kegiatanList.map((item) => (
                <div key={item.id} className={`p-5 rounded-2xl border transition ${
                  theme === 'dark' ? 'bg-slate-950/60 border-slate-850 hover:bg-slate-900/40' : 'bg-slate-50 border-slate-150 hover:bg-slate-100/50'
                }`}>
                  <div className="flex items-center justify-between">
                    <span className="text-[10px] font-bold bg-indigo-500/10 text-indigo-500 px-2.5 py-1 rounded-full uppercase tracking-wider">
                      {new Date(item.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                    </span>
                    <span className={`text-[10px] font-bold ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>
                      ⏰ {item.waktu || '08:00'} - Selesai
                    </span>
                  </div>
                  <h4 className="text-base font-black mt-3 text-indigo-500">{item.nama_kegiatan}</h4>
                  <p className={`text-xs mt-2 font-medium ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                    📍 Lokasi: {item.tempat || 'Balai RT'}
                  </p>
                </div>
              ))
            ) : (
              // Sensible Fallbacks
              <div className="space-y-4">
                <div className={`p-5 rounded-2xl border ${theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'}`}>
                  <div className="flex items-center justify-between">
                    <span className="text-[10px] font-bold bg-indigo-500/10 text-indigo-500 px-2.5 py-1 rounded-full">19 Jul 2026</span>
                    <span className={`text-[10px] font-bold ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>⏰ 08:30 - Selesai</span>
                  </div>
                  <h4 className="text-base font-black mt-3 text-indigo-500">Pemeriksaan Posyandu Balita & Lansia</h4>
                  <p className={`text-xs mt-2 font-medium ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                    📍 Lokasi: Pos Ronda Utama Indah
                  </p>
                </div>
                <div className={`p-5 rounded-2xl border ${theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'}`}>
                  <div className="flex items-center justify-between">
                    <span className="text-[10px] font-bold bg-indigo-500/10 text-indigo-500 px-2.5 py-1 rounded-full">26 Jul 2026</span>
                    <span className={`text-[10px] font-bold ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>⏰ 19:30 - 22:00</span>
                  </div>
                  <h4 className="text-base font-black mt-3 text-indigo-500">Rapat Warga Evaluasi Program KAWAN</h4>
                  <p className={`text-xs mt-2 font-medium ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                    📍 Lokasi: Balai Musyawarah Warga
                  </p>
                </div>
              </div>
            )}
          </div>
        </div>

      </section>

      {/* UMKM Warga Section with Categories Filter */}
      <section className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className={`p-8 rounded-3xl border shadow-xl transition duration-500 ${
          theme === 'dark' ? 'bg-slate-900/40 border-slate-900' : 'bg-white border-slate-200'
        }`}>
          
          <div className="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
              <h2 className="text-2xl sm:text-3xl font-black tracking-tight">🛍️ UMKM Warga Unggulan</h2>
              <p className={`text-sm mt-1 font-semibold ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                Jelajahi produk buatan warga untuk memajukan ekonomi lokal
              </p>
            </div>
            
            {/* Categories filter */}
            <div className="flex flex-wrap gap-2">
              {['Semua', ...data.umkmKategori].map((cat) => (
                <button
                  key={cat}
                  onClick={() => setActiveCategory(cat)}
                  className={`px-4 py-2 rounded-xl text-xs font-bold transition ${
                    activeCategory === cat
                      ? 'bg-rose-600 text-white shadow-md shadow-rose-600/10'
                      : theme === 'dark'
                        ? 'bg-slate-950/60 border border-slate-800 text-slate-400 hover:text-white'
                        : 'bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900'
                  }`}
                >
                  {cat}
                </button>
              ))}
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredUmkm.length > 0 ? (
              filteredUmkm.map((item) => (
                <div 
                  key={item.id} 
                  className={`rounded-2xl border overflow-hidden transition duration-300 hover:scale-[1.02] ${
                    theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'
                  }`}
                >
                  <div className="h-48 overflow-hidden bg-slate-900 relative">
                    <img 
                      src={`http://127.0.0.1:8000/images/${item.gambar}`} 
                      alt={item.nama_usaha} 
                      className="w-full h-full object-cover transition duration-500 hover:scale-105"
                      onError={(e) => {
                        // fallback placeholder if image cannot be loaded
                        e.target.src = "https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&auto=format&fit=crop&q=60";
                      }}
                    />
                    <span className="absolute top-3 right-3 text-[10px] font-black uppercase tracking-wider bg-rose-600 text-white px-3 py-1 rounded-full">
                      {item.kategori}
                    </span>
                  </div>
                  <div className="p-5">
                    <h4 className="text-lg font-black text-rose-500">{item.nama_usaha}</h4>
                    <p className={`text-xs mt-1 font-semibold ${theme === 'dark' ? 'text-slate-400' : 'text-slate-600'}`}>
                      👤 Pemilik: {item.pemilik}
                    </p>
                    <p className={`text-xs mt-3 leading-relaxed font-medium ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                      {item.deskripsi}
                    </p>
                    <div className="mt-5 pt-4 border-t border-slate-850/10 flex justify-between items-center">
                      <span className="text-[10px] font-black uppercase tracking-widest text-slate-500">Hubungi Kontak:</span>
                      <a 
                        href={`https://wa.me/${item.kontak}`} 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        className="text-xs font-bold text-emerald-500 hover:text-emerald-400 hover:underline"
                      >
                        💬 {item.kontak}
                      </a>
                    </div>
                  </div>
                </div>
              ))
            ) : (
              // Fallback cards if UMKM table is empty or loading
              <>
                <div className={`rounded-2xl border overflow-hidden ${theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'}`}>
                  <div className="h-48 bg-slate-900 flex items-center justify-center">
                    <svg className="w-16 h-16 text-rose-500" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2A10 10 0 002 12a10 10 0 0010 10 10 10 0 0010-10A10 10 0 0012 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                  </div>
                  <div className="p-5">
                    <h4 className="text-lg font-black text-rose-500">Toko Kelontong Berkah</h4>
                    <p className="text-xs mt-1 font-semibold text-slate-500">👤 Pemilik: Ibu Aminah</p>
                    <p className="text-xs mt-3 text-slate-500 font-medium">Menyediakan berbagai kebutuhan pokok sehari-hari, sayur segar, gas elpiji, dan galon air mineral dengan pengantaran gratis ke rumah.</p>
                  </div>
                </div>
                <div className={`rounded-2xl border overflow-hidden ${theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-150'}`}>
                  <div className="h-48 bg-slate-900 flex items-center justify-center">
                    <svg className="w-16 h-16 text-rose-500" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2A10 10 0 002 12a10 10 0 0010 10 10 10 0 0010-10A10 10 0 0012 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                  </div>
                  <div className="p-5">
                    <h4 className="text-lg font-black text-rose-500">Nasi Uduk Gurih Mawar</h4>
                    <p className="text-xs mt-1 font-semibold text-slate-500">👤 Pemilik: Pak Joko</p>
                    <p className="text-xs mt-3 text-slate-500 font-medium">Nasi uduk harum khas Betawi komplit dengan semur jengkol empuk, ayam goreng serundeng garing, dan sambal terasi pedas manis.</p>
                  </div>
                </div>
              </>
            )}
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className={`border-t py-12 transition duration-500 ${
        theme === 'dark' ? 'bg-slate-950 border-slate-900 text-slate-500' : 'bg-white border-slate-100 text-slate-400'
      }`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6">
          <div className="text-center sm:text-left">
            <p className="text-sm font-black bg-gradient-to-r from-rose-500 to-rose-600 bg-clip-text text-transparent tracking-wide">
              KAWAN ECOSYSTEM
            </p>
            <p className="text-xs mt-1 font-semibold">
              © 2026 {data.settings.nama_rt}. Komunikasi & Aplikasi Warga Nyaman.
            </p>
          </div>
          <div className="flex gap-6 text-xs font-bold text-slate-500 hover:text-slate-400">
            <span>Hubungi Layanan RT: {data.settings.kontak_rt || '0812-3456-7890'}</span>
          </div>
        </div>
      </footer>

    </div>
  );
}
