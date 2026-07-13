<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan');
            // Gunakan bigInteger untuk nominal uang yang besar
            $table->bigInteger('pemasukan')->default(0); 
            $table->bigInteger('pengeluaran')->default(0);
            $table->date('tanggal');
            
            // Relasi ke tabel users (siapa bendahara yang input)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};