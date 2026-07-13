<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use App\Models\Warga;
use App\Models\Pengurus;
use App\Models\Kas;
use App\Models\Iuran;
use App\Models\Koperasi;
use App\Models\KoperasiTransaksi;
use App\Models\BankSampah;
use App\Models\Umkm;
use App\Models\Surat;
use App\Models\Posyandu;
use App\Models\Ronda;
use App\Models\Kegiatan;
use App\Models\Rukem;
use App\Models\Aspirasi;
use App\Models\Pengumuman;
use App\Models\Inventaris;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed 4 Core Role Users
        $superadmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SUPERADMIN, // 1
        ]);

        $ketua = User::create([
            'name' => 'Pak Ketua RT',
            'email' => 'ketua@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_RT, // 2
        ]);

        $bendahara = User::create([
            'name' => 'Ibu Bendahara',
            'email' => 'bendahara@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_BENDAHARA, // 3
        ]);

        $wargaUser = User::create([
            'name' => 'Bapak Warga',
            'email' => 'warga@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_WARGA, // 4
        ]);

        // 2. Settings Initial
        Setting::set('nama_rt', 'RT 01 / RW 05 Komp. Mawar Asri');
        Setting::set('alamat_rt', 'Jl. Mawar Asri No. 1, Bandung');
        Setting::set('nominal_iuran', '50000');
        Setting::set('nominal_rukem', '1000000');
        Setting::set('kontak_rt', '0812-3456-7890');

        // 3. Seed Warga Data (Target: 100 Warga)
        $w1 = Warga::create([
            'nik' => '3273010000000001',
            'no_kk' => '3273011000000001',
            'nama_lengkap' => 'Pak Ketua RT',
            'jenis_kelamin' => 'L',
            'umur' => 45,
            'status_keluarga' => 'Kepala Keluarga',
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => 'Jl. Mawar No. 1, RT 01/RW 05',
            'user_id' => $ketua->id,
        ]);

        Warga::create([
            'nik' => '3273010000000011',
            'no_kk' => '3273011000000001',
            'nama_lengkap' => 'Ibu Siti Aminah (Istri Ketua RT)',
            'jenis_kelamin' => 'P',
            'umur' => 42,
            'status_keluarga' => 'Istri',
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => 'Jl. Mawar No. 1, RT 01/RW 05',
        ]);

        Warga::create([
            'nik' => '3273010000000012',
            'no_kk' => '3273011000000001',
            'nama_lengkap' => 'Bintang Pratama (Anak RT)',
            'jenis_kelamin' => 'L',
            'umur' => 16,
            'status_keluarga' => 'Anak',
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => 'Jl. Mawar No. 1, RT 01/RW 05',
        ]);

        $w2 = Warga::create([
            'nik' => '3273010000000002',
            'no_kk' => '3273011000000002',
            'nama_lengkap' => 'Ibu Bendahara',
            'jenis_kelamin' => 'P',
            'umur' => 38,
            'status_keluarga' => 'Kepala Keluarga',
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => 'Jl. Mawar No. 2, RT 01/RW 05',
            'user_id' => $bendahara->id,
        ]);

        Warga::create([
            'nik' => '3273010000000021',
            'no_kk' => '3273011000000002',
            'nama_lengkap' => 'Rahmat Hidayat (Anak)',
            'jenis_kelamin' => 'L',
            'umur' => 12,
            'status_keluarga' => 'Anak',
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => 'Jl. Mawar No. 2, RT 01/RW 05',
        ]);

        $w3 = Warga::create([
            'nik' => '3273010000000003',
            'no_kk' => '3273011000000003',
            'nama_lengkap' => 'Bapak Warga',
            'jenis_kelamin' => 'L',
            'umur' => 40,
            'status_keluarga' => 'Kepala Keluarga',
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => 'Jl. Mawar No. 3, RT 01/RW 05',
            'user_id' => $wargaUser->id,
        ]);

        Warga::create([
            'nik' => '3273010000000031',
            'no_kk' => '3273011000000003',
            'nama_lengkap' => 'Dewi Lestari (Istri)',
            'jenis_kelamin' => 'P',
            'umur' => 36,
            'status_keluarga' => 'Istri',
            'status_tinggal' => 'Milik Sendiri',
            'alamat' => 'Jl. Mawar No. 3, RT 01/RW 05',
        ]);

        // Generate Families until total Warga == 100
        $maleNames = ['Budi', 'Agus', 'Slamet', 'Hendra', 'Eko', 'Bambang', 'Joko', 'Dedi', 'Rudi', 'Aris', 'Denny', 'Fajar', 'Gigih', 'Hadi', 'Irfan', 'Juli', 'Kuncoro', 'Lukman', 'Mulyono', 'Nugroho', 'Oki', 'Prasetyo', 'Qomar', 'Rizki', 'Surya', 'Teguh', 'Utomo', 'Victor', 'Wahyu', 'Yudi', 'Zainal'];
        $femaleNames = ['Siti', 'Dewi', 'Sri', 'Nani', 'Rina', 'Yuni', 'Endang', 'Titin', 'Lilis', 'Ratna', 'Anisa', 'Bunga', 'Citra', 'Dian', 'Elvira', 'Fitri', 'Gita', 'Hani', 'Indah', 'Julianti', 'Kartika', 'Lestari', 'Maya', 'Novi', 'Oktavia', 'Putri', 'Qonita', 'Rini', 'Suci', 'Tri'];
        $lastNames = ['Santoso', 'Wibowo', 'Pratama', 'Saputra', 'Suryono', 'Kusuma', 'Kurniawan', 'Setiawan', 'Firmansyah', 'Wijaya', 'Sutrisno', 'Hidayat', 'Ramadhan', 'Nugraha', 'Purnama', 'Gunawan', 'Susanto', 'Aditya', 'Mahendra', 'Budiman'];

        $kkIndex = 4;
        $nikCounter = 100;

        while (Warga::count() < 100) {
            $noKK = '327301' . str_pad($kkIndex + 100, 10, '0', STR_PAD_LEFT);
            $houseNo = $kkIndex;
            $statusTinggal = ($kkIndex % 4 == 0) ? 'Kontrak' : 'Milik Sendiri';
            $alamat = 'Jl. Mawar No. ' . $houseNo . ', RT 01/RW 05';

            $familyName = $lastNames[($kkIndex - 4) % count($lastNames)];
            $husbandFirst = $maleNames[($kkIndex - 4) % count($maleNames)];
            $wifeFirst = $femaleNames[($kkIndex - 4) % count($femaleNames)];

            // 1. Kepala Keluarga
            if (Warga::count() < 100) {
                Warga::create([
                    'nik' => '327301' . str_pad($nikCounter++, 10, '0', STR_PAD_LEFT),
                    'no_kk' => $noKK,
                    'nama_lengkap' => $husbandFirst . ' ' . $familyName,
                    'jenis_kelamin' => 'L',
                    'umur' => rand(30, 60),
                    'status_keluarga' => 'Kepala Keluarga',
                    'status_tinggal' => $statusTinggal,
                    'alamat' => $alamat,
                ]);
            }

            // 2. Istri
            if (Warga::count() < 100) {
                Warga::create([
                    'nik' => '327301' . str_pad($nikCounter++, 10, '0', STR_PAD_LEFT),
                    'no_kk' => $noKK,
                    'nama_lengkap' => $wifeFirst . ' ' . $familyName,
                    'jenis_kelamin' => 'P',
                    'umur' => rand(25, 55),
                    'status_keluarga' => 'Istri',
                    'status_tinggal' => $statusTinggal,
                    'alamat' => $alamat,
                ]);
            }

            // 3. Anak 1
            if (Warga::count() < 100) {
                $child1Gender = rand(0, 1) ? 'L' : 'P';
                $child1First = $child1Gender == 'L' ? $maleNames[($nikCounter) % count($maleNames)] : $femaleNames[($nikCounter) % count($femaleNames)];
                Warga::create([
                    'nik' => '327301' . str_pad($nikCounter++, 10, '0', STR_PAD_LEFT),
                    'no_kk' => $noKK,
                    'nama_lengkap' => $child1First . ' ' . $familyName,
                    'jenis_kelamin' => $child1Gender,
                    'umur' => rand(1, 20),
                    'status_keluarga' => 'Anak',
                    'status_tinggal' => $statusTinggal,
                    'alamat' => $alamat,
                ]);
            }

            // 4. Anak 2 (Setiap KK ke-3)
            if ($kkIndex % 3 == 0 && Warga::count() < 100) {
                $child2Gender = rand(0, 1) ? 'L' : 'P';
                $child2First = $child2Gender == 'L' ? $maleNames[($nikCounter + 2) % count($maleNames)] : $femaleNames[($nikCounter + 2) % count($femaleNames)];
                Warga::create([
                    'nik' => '327301' . str_pad($nikCounter++, 10, '0', STR_PAD_LEFT),
                    'no_kk' => $noKK,
                    'nama_lengkap' => $child2First . ' ' . $familyName,
                    'jenis_kelamin' => $child2Gender,
                    'umur' => rand(1, 15),
                    'status_keluarga' => 'Anak',
                    'status_tinggal' => $statusTinggal,
                    'alamat' => $alamat,
                ]);
            }

            $kkIndex++;
        }

        // 4. Seed Pengurus RT
        Pengurus::create([
            'warga_id' => $w1->id,
            'nama' => 'Pak Ketua RT',
            'jabatan' => 'Ketua RT',
            'periode_mulai' => 2024,
            'periode_selesai' => 2027,
            'no_hp' => '0812-3456-7890',
        ]);

        Pengurus::create([
            'warga_id' => $w2->id,
            'nama' => 'Ibu Bendahara',
            'jabatan' => 'Bendahara RT',
            'periode_mulai' => 2024,
            'periode_selesai' => 2027,
            'no_hp' => '0813-9876-5432',
        ]);

        // 5. Seed Kas Entries Initial
        Kas::create([
            'keterangan' => 'Saldo Awal Kas RT 2026',
            'pemasukan' => 5000000,
            'pengeluaran' => 0,
            'tanggal' => date('Y-01-01'),
            'user_id' => $bendahara->id,
        ]);

        Kas::create([
            'keterangan' => 'Pengadaan Lampu Jalan RT',
            'pemasukan' => 0,
            'pengeluaran' => 450000,
            'tanggal' => date('Y-01-10'),
            'user_id' => $bendahara->id,
        ]);

        // 6. Seed Iuran Warga
        $allWargaSample = Warga::take(10)->get();
        foreach ($allWargaSample as $w) {
            Iuran::create([
                'warga_id' => $w->id,
                'bulan' => date('n'),
                'tahun' => date('Y'),
                'nominal' => 50000,
                'status' => 'paid',
                'tanggal_bayar' => date('Y-m-d'),
                'verified_by' => $bendahara->id,
            ]);
        }

        // 7. Seed Koperasi Products & Transactions
        $p1 = Koperasi::create([
            'nama_produk' => 'Beras Pandan Wangi 5kg',
            'kategori' => 'Sembako',
            'harga' => 75000,
            'stok' => 30,
            'deskripsi' => 'Beras kualitas super untuk kebutuhan warga RT.',
        ]);

        $p2 = Koperasi::create([
            'nama_produk' => 'Minyak Goreng 2L',
            'kategori' => 'Sembako',
            'harga' => 34000,
            'stok' => 50,
            'deskripsi' => 'Minyak goreng kelapa sawit higienis.',
        ]);

        KoperasiTransaksi::create([
            'user_id' => $wargaUser->id,
            'koperasi_id' => $p1->id,
            'jenis' => 'pembelian',
            'nominal' => 75000,
            'status' => 'approved',
            'keterangan' => 'Pembelian beras warga',
        ]);

        // 8. Seed Bank Sampah
        BankSampah::create([
            'user_id' => $wargaUser->id,
            'jenis_sampah' => 'Botol Plastik PET',
            'berat_kg' => 5.5,
            'harga_per_kg' => 4000,
            'total_harga' => 22000,
            'tanggal' => date('Y-m-d'),
        ]);

        // 9. Seed 10 UMKM Warga & 2D Flat Illustration Assets
        $umkmData = [
            [
                'nama_usaha' => 'Warung Nasi Uduk & Lauk Ibu Dewi',
                'pemilik' => 'Ibu Dewi',
                'kategori' => 'Kuliner',
                'deskripsi' => 'Nasi uduk gurih wangi komplit dengan ayam goreng, tempe orek, dan sambal pedas nikmat.',
                'alamat' => 'Jl. Mawar No. 3, RT 01',
                'no_whatsapp' => '081299887766',
                'foto' => 'images/umkm_nasi_uduk.png',
            ],
            [
                'nama_usaha' => 'Express Clean Laundry RT 01',
                'pemilik' => 'Ibu Siti Aminah',
                'kategori' => 'Jasa',
                'deskripsi' => 'Layanan cuci kilon, setrika wangi, dan ekspress 1 hari selesai untuk warga kompleks.',
                'alamat' => 'Jl. Mawar No. 1, RT 01',
                'no_whatsapp' => '081311223344',
                'foto' => 'images/umkm_laundry.png',
            ],
            [
                'nama_usaha' => 'Lapak Sayur Segar Pak Budi',
                'pemilik' => 'Pak Budi',
                'kategori' => 'Sembako',
                'deskripsi' => 'Menjual sayur-sayuran segar setiap pagi, bumbu dapur lengkap, dan lauk mentah berkualitas.',
                'alamat' => 'Jl. Mawar No. 4, RT 01',
                'no_whatsapp' => '081422334455',
                'foto' => 'images/umkm_sayur_segar.png',
            ],
            [
                'nama_usaha' => 'Bakso Solo Asli Pak Slamet',
                'pemilik' => 'Pak Slamet',
                'kategori' => 'Kuliner',
                'deskripsi' => 'Bakso sapi asli Solo kuah kaldu sapi mantap, mie ayam lezat, dan pangsit renyah.',
                'alamat' => 'Jl. Mawar No. 5, RT 01',
                'no_whatsapp' => '081533445566',
                'foto' => 'images/umkm_bakso_solo.svg',
            ],
            [
                'nama_usaha' => 'Servis HP & Laptop Bang Hendra',
                'pemilik' => 'Bang Hendra',
                'kategori' => 'Jasa',
                'deskripsi' => 'Perbaikan layar, ganti baterai, install ulang laptop & HP android/iOS terpercaya.',
                'alamat' => 'Jl. Mawar No. 6, RT 01',
                'no_whatsapp' => '081644556677',
                'foto' => 'images/umkm_servis_hp.svg',
            ],
            [
                'nama_usaha' => 'Kedai Kopi Aren Kekinian Warga',
                'pemilik' => 'Rizki Pratama',
                'kategori' => 'Kuliner',
                'deskripsi' => 'Minuman es kopi susu gula aren, boba, dan artisan tea segar buatan pemuda RT.',
                'alamat' => 'Jl. Mawar No. 7, RT 01',
                'no_whatsapp' => '081755667788',
                'foto' => 'images/umkm_kopi_kekinian.svg',
            ],
            [
                'nama_usaha' => 'Penjahit Pakaian Ibu Anisa',
                'pemilik' => 'Ibu Anisa',
                'kategori' => 'Fashion',
                'deskripsi' => 'Terima jahit busana muslim, seragam kerja, veramak celana jeans, dan gaun wanita.',
                'alamat' => 'Jl. Mawar No. 8, RT 01',
                'no_whatsapp' => '081866778899',
                'foto' => 'images/umkm_jahit_pakaian.svg',
            ],
            [
                'nama_usaha' => 'Aneka Kue Basah & Snack Mbak Rina',
                'pemilik' => 'Mbak Rina',
                'kategori' => 'Kuliner',
                'deskripsi' => 'Pesanan kue tampah, klepon, risoles mayo, lemper, dan kue kering acara warga.',
                'alamat' => 'Jl. Mawar No. 9, RT 01',
                'no_whatsapp' => '081977889900',
                'foto' => 'images/umkm_snack_kue.svg',
            ],
            [
                'nama_usaha' => 'Bengkel Motor Presisi Pak Joko',
                'pemilik' => 'Pak Joko',
                'kategori' => 'Jasa',
                'deskripsi' => 'Service rutin motor matic & bebek, ganti oli, tambal ban tubeless, dan suku cadang motor.',
                'alamat' => 'Jl. Mawar No. 10, RT 01',
                'no_whatsapp' => '081288990011',
                'foto' => 'images/umkm_bengkel_motor.svg',
            ],
            [
                'nama_usaha' => 'Depot Galon Air Mineral & LPG Mawar',
                'pemilik' => 'Pak Eko',
                'kategori' => 'Sembako',
                'deskripsi' => 'Layanan antar galon isi ulang air higienis dan tabung gas LPG 3kg / 12kg langsung ke rumah.',
                'alamat' => 'Jl. Mawar No. 11, RT 01',
                'no_whatsapp' => '081399001122',
                'foto' => 'images/umkm_galon_air.svg',
            ],
        ];

        foreach ($umkmData as $uItem) {
            Umkm::create(array_merge($uItem, [
                'user_id' => $wargaUser->id,
                'is_active' => true,
            ]));
        }

        // 10. Seed Surat Menyurat
        Surat::create([
            'user_id' => $wargaUser->id,
            'no_surat' => '470/001/RT.01/2026',
            'jenis_surat' => 'Surat Pengantar KTP / KK',
            'keperluan' => 'Permohonan perpanjangan e-KTP di Kelurahan',
            'status' => 'approved',
            'tanggal_pengajuan' => date('Y-m-d'),
            'tanggal_disetujui' => date('Y-m-d'),
        ]);

        // 11. Seed Posyandu
        Posyandu::create([
            'nama_pasien' => 'Ananda Bintang',
            'kategori' => 'balita',
            'umur_bulan_atau_tahun' => 14,
            'bb_kg' => 10.5,
            'tb_cm' => 78,
            'catatan' => 'Pertumbuhan normal, imunisasi vitamin A lengkap.',
            'tanggal_periksa' => date('Y-m-d'),
            'petugas' => 'Kader Posyandu Mawar',
        ]);

        // 12. Seed Keamanan & Ronda
        Ronda::create([
            'hari' => 'Sabtu Malam',
            'tanggal' => date('Y-m-d'),
            'regu' => 'Regu Elang 1',
            'anggota_warga' => 'Pak Budi, Pak Joko, Pak Hendra, Bapak Warga',
            'pos_ronda' => 'Pos Ronda Utama RT 01',
            'status_piket' => 'selesai',
        ]);

        // 13. Seed Kegiatan Warga
        Kegiatan::create([
            'nama_kegiatan' => 'Kerja Bakti & Bersih Lingkungan',
            'kategori' => 'Gotong Royong',
            'tanggal' => date('Y-m-d', strtotime('+5 days')),
            'waktu' => '07:00 - 11:00 WIB',
            'lokasi' => 'Lapangan & Saluran Air RT 01',
            'deskripsi' => 'Diharapkan kepada seluruh warga RT 01/RW 05 untuk dapat berpartisipasi dalam kerja bakti massal.',
            'penanggung_jawab' => 'Pak Ketua RT',
            'anggaran' => 500000,
        ]);

        // 14. Seed Rukem
        Rukem::create([
            'warga_id' => $w3->id,
            'nama_almarhum' => 'Almarhum Mbah Sutrisno',
            'tanggal_wafat' => date('Y-05-10'),
            'ahli_waris' => 'Bapak Warga (Anak)',
            'nominal_santunan' => 1000000,
            'status' => 'dicairkan',
            'keterangan' => 'Santunan belasungkawa resmi RT.',
        ]);

        // 15. Seed Aspirasi
        Aspirasi::create([
            'user_id' => $wargaUser->id,
            'judul' => 'Usulan Penambahan Lampu Penerangan Jalan',
            'kategori' => 'Fasilitas Umum',
            'isi' => 'Mohon ditambahkan penerangan di gang samping pos ronda karena saat malam terasa gelap.',
            'status' => 'resolved',
            'tanggapan' => 'Terima kasih atas usulannya, pengadaan lampu jalan telah disetujui.',
        ]);

        // 16. Seed Asset RT
        Inventaris::create([
            'nama_barang' => 'Tenda Hajatan RT (4x6m)',
            'jumlah' => 2,
            'kondisi' => 'Baik',
            'tanggal_perolehan' => date('Y-02-01'),
            'nominal' => 3500000,
            'keterangan' => 'Disimpan di Gudang RT',
        ]);

        Inventaris::create([
            'nama_barang' => 'Kursi Lipat Chitose',
            'jumlah' => 50,
            'kondisi' => 'Baik',
            'tanggal_perolehan' => date('Y-02-01'),
            'nominal' => 150000,
            'keterangan' => 'Inventaris RT',
        ]);

        // 17. Seed Pengumuman
        Pengumuman::create([
            'user_id' => $ketua->id,
            'judul' => 'Undangan Kerja Bakti Massal RT 01',
            'kategori' => 'penting',
            'isi' => 'Diberitahukan kepada seluruh warga RT 01/RW 05 untuk dapat berpartisipasi dalam kerja bakti hari Minggu mendatang.',
            'is_active' => true,
        ]);

        // 18. Seed Activity Log
        ActivityLog::create([
            'user_id' => $superadmin->id,
            'action' => 'Inisialisasi Sistem 100 Warga',
            'description' => 'Sistem Smart RT 2026 berhasil diinisialisasi dengan 100 data warga terstruktur.',
            'ip_address' => '127.0.0.1',
        ]);
    }
}