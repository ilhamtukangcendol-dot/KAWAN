import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import api from '../api';

export default function Register({ theme, toggleTheme }) {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  // Clear errors/success when typing
  useEffect(() => {
    setError('');
  }, [name, email, password, passwordConfirmation]);

  const handleRegister = async (e) => {
    e.preventDefault();
    if (!name || !email || !password || !passwordConfirmation) {
      setError('Semua kolom wajib diisi.');
      return;
    }

    if (password !== passwordConfirmation) {
      setError('Konfirmasi password tidak cocok.');
      return;
    }

    setLoading(true);
    setError('');
    setSuccess('');

    try {
      const response = await api.post('/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });

      if (response.data.status === 'success') {
        setSuccess('Pendaftaran berhasil! Mengalihkan...');
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        setTimeout(() => {
          navigate('/dashboard');
        }, 1500);
      }
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal mendaftar. Silakan coba lagi.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="relative min-h-screen w-full flex items-center justify-center py-12 px-4 overflow-hidden font-sans">
      
      {/* Parallax Breathing Full Background Image */}
      <div className="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div 
          className="absolute inset-[-20px] bg-cover bg-center transition-all duration-1000"
          style={{
            backgroundImage: "url('http://127.0.0.1:8000/images/indo_gotong_royong.png')",
            filter: theme === 'dark' ? 'brightness(0.32) saturate(1.2)' : 'brightness(0.92) saturate(1.1) contrast(0.9)',
            animation: 'bgZoomPan 28s ease-in-out infinite'
          }}
        />
        {/* Vignette Overlay */}
        <div 
          className="absolute inset-0 transition-colors duration-1000"
          style={{
            background: theme === 'dark' 
              ? 'radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(2, 6, 23, 0.88) 100%)'
              : 'radial-gradient(circle at center, rgba(248, 250, 252, 0.45) 0%, rgba(226, 232, 240, 0.85) 100%)',
            backdropFilter: 'blur(8px)',
            WebkitBackdropFilter: 'blur(8px)'
          }}
        />
      </div>

      <style>{`
        @keyframes bgZoomPan {
          0% { transform: scale(1.04) translate(0, 0); }
          50% { transform: scale(1.12) translate(-8px, -12px); }
          100% { transform: scale(1.04) translate(0, 0); }
        }
        @keyframes spinSlow {
          from { transform: rotate(0deg); }
          to { transform: rotate(360deg); }
        }
        @keyframes float {
          0% { transform: translateY(0px) scale(1); }
          50% { transform: translateY(-10px) scale(1.05); }
          100% { transform: translateY(0px) scale(1); }
        }
        .animated-gradient-border {
          position: relative;
          border-radius: 2.5rem;
          background: linear-gradient(to right, #f43f5e, #e11d48, #be123c);
          padding: 1.5px;
        }
        .glass-card-inner {
          border-radius: calc(2.5rem - 1.5px);
        }
      `}</style>

      {/* Floating Sparkles Background Overlay */}
      <div className="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <svg 
          className="absolute w-6 h-6 text-rose-500/40 opacity-70 top-[15%] left-[20%]" 
          style={{ animation: 'spinSlow 12s linear infinite, float 6s ease-in-out infinite' }}
          viewBox="0 0 24 24"
        >
          <path fill="currentColor" d="M12 0L14.6 9.4L24 12L14.6 14.6L12 24L9.4 14.6L0 12L9.4 9.4Z"/>
        </svg>
        <svg 
          className="absolute w-5 h-5 text-rose-500/40 opacity-70 top-[25%] right-[20%]" 
          style={{ animation: 'spinSlow 10s linear infinite, float 5s ease-in-out infinite' }}
          viewBox="0 0 24 24"
        >
          <path fill="currentColor" d="M12 0L14.6 9.4L24 12L14.6 14.6L12 24L9.4 14.6L0 12L9.4 9.4Z"/>
        </svg>
      </div>

      <div className="w-full max-w-lg relative z-10 flex flex-col items-center">
        
        {/* Header Controls */}
        <div className="w-full flex items-center justify-between mb-8 px-4">
          <Link 
            to="/" 
            className={`flex items-center gap-2 px-4 py-2 rounded-full border transition font-bold text-xs uppercase tracking-wider ${
              theme === 'dark'
                ? 'bg-slate-900/60 border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800'
                : 'bg-white/80 border-slate-200 text-slate-700 hover:text-slate-900 hover:bg-slate-50'
            }`}
          >
            ← Kembali ke Beranda
          </Link>

          <button 
            onClick={toggleTheme}
            className={`p-2.5 rounded-full border transition ${
              theme === 'dark'
                ? 'bg-slate-900/60 border-slate-800 text-amber-400 hover:bg-slate-800'
                : 'bg-white/80 border-slate-200 text-slate-700 hover:bg-slate-50'
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
        </div>

        {/* Elegant Glassmorphic Card */}
        <div className="w-full animated-gradient-border shadow-2xl">
          <div className={`w-full glass-card-inner backdrop-blur-3xl px-8 py-10 transition duration-300 ${
            theme === 'dark' 
              ? 'bg-slate-900/40 text-white' 
              : 'bg-white/70 text-slate-800'
          }`}>
            
            <div className="text-center mb-8">
              <h2 className="text-3xl font-black tracking-tight">Daftar Akun</h2>
              <p className={`text-sm mt-2 font-medium ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                Daftarkan akun warga untuk mengakses portal layanan RT
              </p>
            </div>

            {error && (
              <div className="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs font-bold text-center">
                {error}
              </div>
            )}

            {success && (
              <div className="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold text-center">
                {success}
              </div>
            )}

            <form onSubmit={handleRegister} className="space-y-5">
              <div>
                <label className={`block font-black text-[10px] uppercase tracking-widest mb-2 ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                  Nama Lengkap
                </label>
                <input 
                  type="text" 
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  required
                  className={`block w-full px-4 py-3.5 rounded-2xl shadow-inner text-sm transition focus:outline-none focus:ring-2 focus:ring-rose-500 ${
                    theme === 'dark'
                      ? 'bg-slate-950/60 border border-slate-800 text-white placeholder-slate-600 focus:border-rose-500'
                      : 'bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:border-rose-500'
                  }`}
                  placeholder="Contoh: Budi Santoso"
                />
              </div>

              <div>
                <label className={`block font-black text-[10px] uppercase tracking-widest mb-2 ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                  Alamat Email
                </label>
                <input 
                  type="email" 
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                  className={`block w-full px-4 py-3.5 rounded-2xl shadow-inner text-sm transition focus:outline-none focus:ring-2 focus:ring-rose-500 ${
                    theme === 'dark'
                      ? 'bg-slate-950/60 border border-slate-800 text-white placeholder-slate-600 focus:border-rose-500'
                      : 'bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:border-rose-500'
                  }`}
                  placeholder="budi@email.com"
                />
              </div>

              <div>
                <label className={`block font-black text-[10px] uppercase tracking-widest mb-2 ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                  Password
                </label>
                <input 
                  type="password" 
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                  className={`block w-full px-4 py-3.5 rounded-2xl shadow-inner text-sm transition focus:outline-none focus:ring-2 focus:ring-rose-500 ${
                    theme === 'dark'
                      ? 'bg-slate-950/60 border border-slate-800 text-white placeholder-slate-600 focus:border-rose-500'
                      : 'bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:border-rose-500'
                  }`}
                  placeholder="Minimal 8 karakter"
                />
              </div>

              <div>
                <label className={`block font-black text-[10px] uppercase tracking-widest mb-2 ${theme === 'dark' ? 'text-slate-400' : 'text-slate-500'}`}>
                  Konfirmasi Password
                </label>
                <input 
                  type="password" 
                  value={passwordConfirmation}
                  onChange={(e) => setPasswordConfirmation(e.target.value)}
                  required
                  className={`block w-full px-4 py-3.5 rounded-2xl shadow-inner text-sm transition focus:outline-none focus:ring-2 focus:ring-rose-500 ${
                    theme === 'dark'
                      ? 'bg-slate-950/60 border border-slate-800 text-white placeholder-slate-600 focus:border-rose-500'
                      : 'bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:border-rose-500'
                  }`}
                  placeholder="Ulangi password"
                />
              </div>

              <div className="pt-4">
                <button 
                  type="submit" 
                  disabled={loading}
                  className="w-full bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-500 hover:to-red-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-rose-600/25 transition duration-300 transform hover:-translate-y-0.5 uppercase tracking-wider text-xs flex justify-center items-center gap-2"
                >
                  {loading ? (
                    <svg className="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                      <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"/>
                      <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                  ) : 'Daftar Sekarang'}
                </button>
              </div>

              <div className="text-center mt-6">
                <p className="text-xs text-slate-500 font-semibold">
                  Sudah memiliki akun?{' '}
                  <Link to="/login" className="text-rose-400 font-bold hover:text-rose-300 hover:underline">
                    Masuk di sini
                  </Link>
                </p>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  );
}
