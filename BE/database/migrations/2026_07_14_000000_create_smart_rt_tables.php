<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Settings Table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->timestamps();
        });

        // 2. Activity Logs Table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // 3. Pengurus RT Table
        Schema::create('pengurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->nullable()->constrained('warga')->nullOnDelete();
            $table->string('nama');
            $table->string('jabatan');
            $table->year('periode_mulai')->default(date('Y'));
            $table->year('periode_selesai')->default(date('Y') + 3);
            $table->string('no_hp')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        // 4. Iuran Warga Table
        Schema::create('iuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('warga')->cascadeOnDelete();
            $table->integer('bulan'); // 1 - 12
            $table->year('tahun');
            $table->decimal('nominal', 12, 2)->default(0);
            $table->enum('status', ['unpaid', 'pending', 'paid'])->default('unpaid');
            $table->string('bukti_bayar')->nullable();
            $table->date('tanggal_bayar')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 5. Koperasi RT Table & Transaksi
        Schema::create('koperasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('kategori')->default('Sembako');
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('koperasi_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('koperasi_id')->nullable()->constrained('koperasi')->nullOnDelete();
            $table->enum('jenis', ['pembelian', 'simpanan', 'pinjaman'])->default('pembelian');
            $table->decimal('nominal', 12, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 6. Bank Sampah Table
        Schema::create('bank_sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('jenis_sampah'); // Plastik, Kertas, Logam, Botol Kaca, dll
            $table->decimal('berat_kg', 8, 2);
            $table->decimal('harga_per_kg', 10, 2);
            $table->decimal('total_harga', 12, 2);
            $table->date('tanggal');
            $table->timestamps();
        });

        // 7. UMKM Warga Table
        Schema::create('umkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_usaha');
            $table->string('pemilik');
            $table->string('kategori')->default('Kuliner');
            $table->text('deskripsi');
            $table->string('alamat');
            $table->string('no_whatsapp');
            $table->string('foto')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Surat Menyurat Table
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('no_surat')->nullable();
            $table->string('jenis_surat'); // Surat Pengantar RT, Domisili, Keterangan Usaha, Kematian, Dll
            $table->text('keperluan');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('catatan_rt')->nullable();
            $table->string('file_surat')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_disetujui')->nullable();
            $table->timestamps();
        });

        // 9. Posyandu Table
        Schema::create('posyandu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pasien');
            $table->enum('kategori', ['balita', 'lansia'])->default('balita');
            $table->integer('umur_bulan_atau_tahun');
            $table->decimal('bb_kg', 5, 2);
            $table->decimal('tb_cm', 5, 2);
            $table->text('catatan')->nullable();
            $table->date('tanggal_periksa');
            $table->string('petugas')->default('Kader Posyandu RT');
            $table->timestamps();
        });

        // 10. Ronda & Keamanan Table
        Schema::create('ronda', function (Blueprint $table) {
            $table->id();
            $table->string('hari');
            $table->date('tanggal');
            $table->string('regu');
            $table->text('anggota_warga');
            $table->string('pos_ronda')->default('Pos Utama RT 01');
            $table->text('laporan_kejadian')->nullable();
            $table->enum('status_piket', ['selesai', 'berjalan', 'belum'])->default('belum');
            $table->timestamps();
        });

        // 11. Kegiatan Warga Table
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('kategori')->default('Sosial');
            $table->date('tanggal');
            $table->string('waktu');
            $table->string('lokasi');
            $table->text('deskripsi');
            $table->string('penanggung_jawab');
            $table->decimal('anggaran', 12, 2)->default(0);
            $table->timestamps();
        });

        // 12. Rukem (Rukun Kematian) Table
        Schema::create('rukem', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->nullable()->constrained('warga')->nullOnDelete();
            $table->string('nama_almarhum');
            $table->date('tanggal_wafat');
            $table->string('ahli_waris');
            $table->decimal('nominal_santunan', 12, 2);
            $table->enum('status', ['pending', 'dicairkan'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 13. Aspirasi & Pengaduan Table
        Schema::create('aspirasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->string('kategori')->default('Infrastruktur');
            $table->text('isi');
            $table->enum('status', ['open', 'in_progress', 'resolved'])->default('open');
            $table->text('tanggapan')->nullable();
            $table->timestamps();
        });

        // 14. Pengumuman Table
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->enum('kategori', ['penting', 'biasa'])->default('biasa');
            $table->text('isi');
            $table->string('attachment')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('aspirasi');
        Schema::dropIfExists('rukem');
        Schema::dropIfExists('kegiatan');
        Schema::dropIfExists('ronda');
        Schema::dropIfExists('posyandu');
        Schema::dropIfExists('surat');
        Schema::dropIfExists('umkm');
        Schema::dropIfExists('bank_sampah');
        Schema::dropIfExists('koperasi_transaksi');
        Schema::dropIfExists('koperasi');
        Schema::dropIfExists('iuran');
        Schema::dropIfExists('pengurus');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
    }
};
