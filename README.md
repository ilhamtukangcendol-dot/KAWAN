# KAWAN - Portal Layanan & Sistem Informasi Kas RT

KAWAN adalah sistem manajemen kas dan administrasi rukun tetangga yang dirancang untuk transparansi dan kemudahan pengelolaan kas warga. Proyek ini dibagi menjadi dua bagian utama: **Backend (BE)** dan **Frontend (FE)**.

---

## 📁 Struktur Proyek (Decoupled Architecture)

Proyek ini terbagi menjadi dua direktori utama:
- **`BE/`**: Backend API yang dibangun menggunakan Laravel. Mengelola basis data, otentikasi, otorisasi peran (Ketua, Bendahara, Warga), dan logika bisnis kas.
- **`FE/`**: Frontend web SPA yang dibangun menggunakan React, Vite, dan Tailwind CSS. Menyajikan antarmuka pengguna yang dinamis dan interaktif.

---

## 🚀 Panduan Instalasi & Cara Menjalankan

### 1. Kloning Repositori
```bash
git clone https://github.com/ilhamtukangcendol-dot/KAWAN.git
cd KAWAN
```

---

### 💻 Backend (Laravel API) - `BE/`

Untuk menjalankan bagian backend, masuk ke folder `BE/`:

1. **Masuk ke Direktori Backend**:
   ```bash
   cd BE
   ```
2. **Pasang Dependensi PHP**:
   ```bash
   composer install
   ```
3. **Konfigurasi Lingkungan (`.env`)**:
   Salin berkas `.env.example` menjadi `.env` lalu sesuaikan kredensial database MySQL Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kas-rt
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```
5. **Jalankan Migrasi & Database Seeding**:
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Jalankan Server Laravel**:
   ```bash
   php artisan serve
   ```
   *Secara default server akan berjalan di `http://127.0.0.1:8000`.*

---

### 🎨 Frontend (React App) - `FE/`

Untuk menjalankan antarmuka frontend, buka terminal baru dan masuk ke folder `FE/`:

1. **Masuk ke Direktori Frontend**:
   ```bash
   cd FE
   ```
2. **Pasang Dependensi Node.js**:
   ```bash
   npm install
   ```
3. **Jalankan Server Development**:
   Di Windows PowerShell, jalankan menggunakan `.cmd` untuk menghindari pembatasan script execution policy:
   ```powershell
   npm.cmd run dev
   ```
   *Secara default server Vite akan berjalan di `http://localhost:5173`.*

---

## 👥 Akun Pengujian Default
Sandi default untuk seluruh akun di bawah ini adalah **`password`**:
*   👨‍✈️ **Ketua RT (Role 1)**: `ketua@gmail.com`
*   👩‍💼 **Bendahara RT (Role 2)**: `bendahara@gmail.com`
*   👥 **Bapak Warga (Role 3)**: `warga@gmail.com`

---

Built with ♥ by KAWAN Developer Community © 2026.
