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
        // 1. Tambah kolom RFID ke tabel users jika belum ada
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'rfid_uid')) {
                $table->string('rfid_uid', 50)->nullable()->unique()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'rfid_registered_at')) {
                $table->timestamp('rfid_registered_at')->nullable()->after('rfid_uid');
            }
            if (!Schema::hasColumn('users', 'portal_access_enabled')) {
                $table->boolean('portal_access_enabled')->default(true)->after('rfid_registered_at');
            }
        });

        // 2. Tabel Perangkat IoT Portal (ESP32 Gate Controllers)
        if (!Schema::hasTable('iot_devices')) {
            Schema::create('iot_devices', function (Blueprint $table) {
                $table->id();
                $table->string('device_id', 50)->unique(); // e.g. PORTAL-GATE-01
                $table->string('name', 100);               // e.g. Gerbang Utama RT 01
                $table->string('location', 150)->nullable(); // e.g. Pos Satpam Utama
                $table->string('secret_key', 64)->default('kawan_esp32_secret_2026');
                $table->string('ip_address', 45)->nullable();
                $table->enum('status', ['online', 'offline', 'maintenance'])->default('offline');
                $table->timestamp('last_ping_at')->nullable();
                $table->timestamps();
            });
        }

        // 3. Tabel Log Akses Portal Gerbang
        if (!Schema::hasTable('iot_portal_logs')) {
            Schema::create('iot_portal_logs', function (Blueprint $table) {
                $table->id();
                $table->string('device_id', 50)->nullable();
                $table->string('rfid_uid', 50)->index();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('status', ['granted', 'denied'])->default('denied'); // granted = dibuka, denied = ditolak
                $table->enum('access_type', ['rfid_ktp', 'manual_web', 'pairing'])->default('rfid_ktp');
                $table->string('user_name', 100)->nullable();
                $table->string('keterangan', 255)->nullable();
                $table->timestamps();
            });
        }

        // 4. Tabel Sesi Pairing / Pendaftaran KTP Realtime
        if (!Schema::hasTable('iot_pairing_sessions')) {
            Schema::create('iot_pairing_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('token', 64)->unique();
                $table->enum('status', ['waiting', 'scanned', 'completed', 'expired'])->default('waiting');
                $table->string('scanned_uid', 50)->nullable();
                $table->timestamp('expires_at');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_pairing_sessions');
        Schema::dropIfExists('iot_portal_logs');
        Schema::dropIfExists('iot_devices');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'portal_access_enabled')) {
                $table->dropColumn('portal_access_enabled');
            }
            if (Schema::hasColumn('users', 'rfid_registered_at')) {
                $table->dropColumn('rfid_registered_at');
            }
            if (Schema::hasColumn('users', 'rfid_uid')) {
                $table->dropColumn('rfid_uid');
            }
        });
    }
};

