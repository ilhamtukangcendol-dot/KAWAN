<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah warga_id ke tabel posyandu
        Schema::table('posyandu', function (Blueprint $table) {
            $table->foreignId('warga_id')->nullable()->after('id')->constrained('warga')->nullOnDelete();
        });

        // Tambah warga_id ke tabel surat
        Schema::table('surat', function (Blueprint $table) {
            $table->foreignId('warga_id')->nullable()->after('user_id')->constrained('warga')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('posyandu', function (Blueprint $table) {
            $table->dropForeign(['warga_id']);
            $table->dropColumn('warga_id');
        });

        Schema::table('surat', function (Blueprint $table) {
            $table->dropForeign(['warga_id']);
            $table->dropColumn('warga_id');
        });
    }
};
