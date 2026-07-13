import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../api';

export default function Dashboard({ theme, toggleTheme }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchUser = async () => {
      const storedUser = localStorage.getItem('user');
      const token = localStorage.getItem('token');

      if (!token) {
        navigate('/login');
        return;
      }

      if (storedUser) {
        setUser(JSON.parse(storedUser));
      }

      try {
        const response = await api.get('/user');
        if (response.data.status === 'success') {
          setUser(response.data.user);
          localStorage.setItem('user', JSON.stringify(response.data.user));
        }
      } catch (err) {
        // Token might be invalid, clear and redirect
        localStorage.clear();
        navigate('/login');
      } finally {
        setLoading(false);
      }
    };

    fetchUser();
  }, [navigate]);

  const handleLogout = async () => {
    try {
      await api.post('/logout');
    } catch (err) {
      // Even if API request fails, clear local storage and redirect
    } finally {
      localStorage.clear();
      navigate('/login');
    }
  };

  if (loading) {
    return (
      <div className={`min-h-screen flex items-center justify-center font-sans transition-colors duration-500 ${
        theme === 'dark' ? 'bg-slate-950 text-white' : 'bg-slate-50 text-slate-800'
      }`}>
        <div className="flex flex-col items-center gap-3">
          <svg className="animate-spin h-8 w-8 text-rose-500" fill="none" viewBox="0 0 24 24">
            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"/>
            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
          </svg>
          <span className="text-sm font-semibold">Memuat profil...</span>
        </div>
      </div>
    );
  }

  return (
    <div className={`min-h-screen font-sans transition-colors duration-500 ${
      theme === 'dark' ? 'bg-slate-950 text-white' : 'bg-slate-50 text-slate-800'
    }`}>
      
      {/* Navbar Dashboard */}
      <nav className={`border-b transition duration-500 ${
        theme === 'dark' ? 'bg-slate-900/60 border-slate-800' : 'bg-white/80 border-slate-200'
      }`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between h-16 items-center">
            
            <div className="flex items-center gap-3">
              <span className="text-xl font-black bg-gradient-to-r from-rose-500 to-rose-600 bg-clip-text text-transparent tracking-wider">
                KAWAN
              </span>
              <span className={`text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-full ${
                theme === 'dark' ? 'bg-slate-800 text-slate-400' : 'bg-slate-100 text-slate-500'
              }`}>
                {user?.role_label || 'Warga'}
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
              
              <button 
                onClick={handleLogout}
                className="bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 rounded-xl shadow-lg shadow-rose-600/10 transition"
              >
                Keluar
              </button>
            </div>

          </div>
        </div>
      </nav>

      {/* Main Content */}
      <main className="max-w-4xl mx-auto px-4 py-12">
        
        {/* Greetings */}
        <div className={`p-8 rounded-3xl border shadow-xl transition-all duration-500 ${
          theme === 'dark' ? 'bg-slate-900/40 border-slate-850' : 'bg-white border-slate-200'
        }`}>
          <h1 className="text-3xl font-black tracking-tight">Halo, {user?.name}! 👋</h1>
          <p className={`mt-2 font-medium ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
            Anda telah berhasil masuk ke portal warga **KAWAN** (Komunikasi & Aplikasi Warga Nyaman) menggunakan REST API.
          </p>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div className={`p-5 rounded-2xl border transition ${
              theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-200'
            }`}>
              <span className={`text-[10px] font-black uppercase tracking-widest ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>
                Alamat Email Aktif
              </span>
              <p className="text-lg font-bold mt-1 text-rose-500">{user?.email}</p>
            </div>
            
            <div className={`p-5 rounded-2xl border transition ${
              theme === 'dark' ? 'bg-slate-950/60 border-slate-850' : 'bg-slate-50 border-slate-200'
            }`}>
              <span className={`text-[10px] font-black uppercase tracking-widest ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>
                Tingkat Otoritas
              </span>
              <p className="text-lg font-bold mt-1 text-rose-500">
                {user?.role_label} (Role ID: {user?.role})
              </p>
            </div>
          </div>

          <div className="mt-8 pt-6 border-t border-slate-850/10 text-center">
            <span className={`text-xs ${theme === 'dark' ? 'text-slate-500' : 'text-slate-400'}`}>
              Status: Terhubung via Laravel Sanctum Bearer Token
            </span>
          </div>
        </div>

      </main>

    </div>
  );
}
