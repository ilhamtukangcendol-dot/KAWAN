<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Akun Ketua RT
        $ketua = User::create([
            'name' => 'Pak Ketua RT',
            'email' => 'ketua@gmail.com',
            'password' => Hash::make('password'),
            'role' => 1,
        ]);

        // Buat Akun Bendahara
        $bendahara = User::create([
            'name' => 'Ibu Bendahara',
            'email' => 'bendahara@gmail.com',
            'password' => Hash::make('password'),
            'role' => 2,
        ]);

        // Buat Akun Warga
        $wargaUser = User::create([
            'name' => 'Bapak Warga',
            'email' => 'warga@gmail.com',
            'password' => Hash::make('password'),
            'role' => 3,
        ]);

        // Buat Akun Warga Lain untuk Data Warga (Generate 100 Warga acak)
        $namaDepanPria = ['Budi', 'Joko', 'Rahmat', 'Agus', 'Hendra', 'Aditya', 'Taufik', 'Rian', 'Bambang', 'Ahmad', 'Dedi', 'Eko', 'Fajar', 'Guntur', 'Hadi', 'Indra', 'Kurniawan', 'Mulyadi', 'Nugroho', 'Prabowo', 'Roni', 'Slamet', 'Teguh', 'Wahyu', 'Yanto', 'Rudi', 'Anwar', 'Cecep', 'Diki', 'Farhan'];
        $namaDepanWanita = ['Siti', 'Sari', 'Dewi', 'Lestari', 'Aminah', 'Fitri', 'Indah', 'Kartika', 'Mega', 'Nining', 'Putri', 'Rani', 'Sri', 'Utami', 'Wulan', 'Yani', 'Zahra', 'Anisa', 'Cahya', 'Dian', 'Elisa', 'Gita', 'Hana', 'Ika', 'Julia', 'Novi', 'Putu', 'Ratna', 'Suci', 'Tri'];
        $namaBelakang = ['Santoso', 'Hidayat', 'Pratama', 'Wibowo', 'Saputra', 'Lestari', 'Wijaya', 'Kusuma', 'Setiawan', 'Siregar', 'Nasution', 'Ginting', 'Simanjuntak', 'Sutrisno', 'Gunawan', 'Budiman', 'Purnama', 'Suryadi', 'Harahap', 'Pamungkas', 'Subagyo', 'Darsono', 'Suwarno', 'Sudarsono', 'Haryanto'];

        // Pra-generate 30 Nomor Kartu Keluarga (KK) & Alamat agar terstruktur
        $kkList = [];
        $alamatList = [];
        for ($k = 1; $k <= 30; $k++) {
            $kkList[$k] = '327301' . str_pad($k, 10, '0', STR_PAD_LEFT);
            $alamatList[$k] = 'Jl. Mawar No. ' . $k . ', RT 01/RW 05';
        }

        // 1. Warga untuk Ketua RT (Linked)
        \App\Models\Warga::create([
            'nik' => '3273010000000001',
            'no_kk' => $kkList[1],
            'nama_lengkap' => 'Pak Ketua RT',
            'jenis_kelamin' => 'L',
            'umur' => 45,
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => $alamatList[1],
            'user_id' => $ketua->id
        ]);

        // 2. Warga untuk Ibu Bendahara (Linked)
        \App\Models\Warga::create([
            'nik' => '3273010000000002',
            'no_kk' => $kkList[2],
            'nama_lengkap' => 'Ibu Bendahara',
            'jenis_kelamin' => 'P',
            'umur' => 38,
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => $alamatList[2],
            'user_id' => $bendahara->id
        ]);

        // 3. Warga untuk Bapak Warga (Linked)
        \App\Models\Warga::create([
            'nik' => '3273010000000003',
            'no_kk' => $kkList[3],
            'nama_lengkap' => 'Bapak Warga',
            'jenis_kelamin' => 'L',
            'umur' => 40,
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => $alamatList[3],
            'user_id' => $wargaUser->id
        ]);

        // Simpan 97 Warga Sisa secara acak
        for ($i = 4; $i <= 100; $i++) {
            $gender = (rand(0, 1) == 0) ? 'L' : 'P';
            $firstName = ($gender == 'L') ? $namaDepanPria[array_rand($namaDepanPria)] : $namaDepanWanita[array_rand($namaDepanWanita)];
            $lastName = $namaBelakang[array_rand($namaBelakang)];
            $fullName = $firstName . ' ' . $lastName;

            $nik = '327301' . str_pad($i + 100, 10, '0', STR_PAD_LEFT);
            
            $kkIndex = rand(4, 30);
            $noKk = $kkList[$kkIndex];
            $alamat = $alamatList[$kkIndex];
            $umur = rand(1, 80);
            $statusTinggal = (rand(1, 10) <= 7) ? 'Milik Sendiri' : 'Kontrak';

            \App\Models\Warga::create([
                'nik' => $nik,
                'no_kk' => $noKk,
                'nama_lengkap' => $fullName,
                'jenis_kelamin' => $gender,
                'umur' => $umur,
                'status_tinggal' => $statusTinggal,
                'alamat' => $alamat
            ]);
        }

        // Buat Data Transaksi Kas Awal
        \App\Models\Kas::create([
            'keterangan' => 'Iuran Keamanan Bulanan - Warga RT 001',
            'pemasukan' => 1500000,
            'pengeluaran' => 0,
            'tanggal' => now()->subDays(5)->format('Y-m-d'),
            'user_id' => $bendahara->id,
        ]);

        \App\Models\Kas::create([
            'keterangan' => 'Iuran Kebersihan - Warga RT 001',
            'pemasukan' => 750000,
            'pengeluaran' => 0,
            'tanggal' => now()->subDays(4)->format('Y-m-d'),
            'user_id' => $bendahara->id,
        ]);

        \App\Models\Kas::create([
            'keterangan' => 'Pembelian Alat Kebersihan (Sapu & Tempat Sampah)',
            'pemasukan' => 0,
            'pengeluaran' => 250000,
            'tanggal' => now()->subDays(3)->format('Y-m-d'),
            'user_id' => $bendahara->id,
        ]);

        \App\Models\Kas::create([
            'keterangan' => 'Honor Petugas Keamanan RT',
            'pemasukan' => 0,
            'pengeluaran' => 1000000,
            'tanggal' => now()->subDays(1)->format('Y-m-d'),
            'user_id' => $bendahara->id,
        ]);
    }
}