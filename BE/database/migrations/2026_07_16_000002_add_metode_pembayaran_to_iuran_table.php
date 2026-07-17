<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iuran', function (Blueprint $table) {
            $table->string('metode_pembayaran')->nullable()->default('cash')->after('nominal');
        });
    }

    public function down(): void
    {
        Schema::table('iuran', function (Blueprint $table) {
            $table->dropColumn('metode_pembayaran');
        });
    }
};
