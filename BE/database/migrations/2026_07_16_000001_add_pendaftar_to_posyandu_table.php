<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posyandu', function (Blueprint $table) {
            $table->foreignId('pendaftar_warga_id')->nullable()->after('warga_id')->constrained('warga')->nullOnDelete();
            $table->string('nama_pendaftar')->nullable()->after('nama_pasien');
        });
    }

    public function down(): void
    {
        Schema::table('posyandu', function (Blueprint $table) {
            $table->dropForeign(['pendaftar_warga_id']);
            $table->dropColumn(['pendaftar_warga_id', 'nama_pendaftar']);
        });
    }
};
