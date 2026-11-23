<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Warga;
use App\Models\IotDevice;
use App\Models\IotPortalLog;
use App\Models\IotPairingSession;
use App\Models\ActivityLog;

class RfidController extends Controller
{
    /**
     * Endpoint Menerima Data Tap Kartu RFID / e-KTP (POST /api/rfid/tap)
     *
     * Header yang diharapkan:
     * - Content-Type: application/json
     * - Accept: application/json
     * - X-Device-Key: smartrw-rfid-device
     *
     * Body payload:
     * {
     *   "uid": "E3A82C10",
     *   "device_id": "esp32-scanner-01"
     * }
     */
    public function tap(Request $request)
    {
        // 1. Validasi input: uid wajib dan bertipe string
        $validator = Validator::make($request->all(), [
            'uid' => 'required|string|max:50',
            'device_id' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter uid wajib dikirim dalam format string.',
                'errors' => $validator->errors()
            ], 422);
        }

        $rawUid = $request->input('uid');
        $deviceId = $request->input('device_id', 'esp32-scanner-01');

        // Bersihkan nomor UID (uppercase, hanya huruf & angka)
        $cleanUid = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $rawUid));

        // Simpan status online alat di Cache
        Cache::put('rfid_reader_online', true, 60);
        Cache::put('rfid_last_uid', $cleanUid, 60);
        Cache::put('rfid_last_device', $deviceId, 60);

        // Sinkronisasi status perangkat ke database
        IotDevice::updateOrCreate(
            ['device_id' => $deviceId],
            [
                'name' => 'ESP32 RFID Scanner Kelompok',
                'location' => 'Portal Gerbang Utama Perumahan',
                'secret_key' => 'smartrw-rfid-device',
                'status' => 'online',
                'ip_address' => $request->ip(),
                'last_ping_at' => now(),
            ]
        );

        // 2. CEK LOGIKA A: APAKAH ADA SESI PENDAFTARAN / PAIRING DARI WEB YANG AKTIF?
        $activePairing = IotPairingSession::with('user')
            ->where('status', 'waiting')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($activePairing) {
            $targetUser = $activePairing->user;

            // Daftarkan UID kartu ke akun warga/user yang sedang menunggu tap di web
            $targetUser->update([
                'rfid_uid' => $cleanUid,
                'rfid_registered_at' => now(),
                'portal_access_enabled' => true,
            ]);

            $activePairing->update([
                'status' => 'completed',
                'scanned_uid' => $cleanUid
            ]);

            // Catat riwayat log portal
            try {
                IotPortalLog::create([
                    'device_id' => $deviceId,
                    'rfid_uid' => $cleanUid,
                    'user_id' => $targetUser->id,
                    'status' => 'granted',
                    'access_type' => 'pairing',
                    'user_name' => $targetUser->name,
                    'keterangan' => 'Kartu e-KTP berhasil didaftarkan untuk: ' . $targetUser->name . ' (' . $targetUser->role_label . ')',
                ]);
                ActivityLog::log('Registrasi e-KTP via ESP32', 'e-KTP UID ' . $cleanUid . ' berhasil didaftarkan untuk ' . $targetUser->name);
            } catch (\Throwable $e) {
                // Jangan gagalkan respon jika pencatatan log pendukung bermasalah
            }

            return response()->json([
                'success' => true,
                'access' => true,
                'status' => 'pairing_success',
                'message' => 'Kartu berhasil dibaca dan didaftarkan untuk ' . $targetUser->name,
                'uid' => $cleanUid,
                'user' => [
                    'id' => $targetUser->id,
                    'name' => $targetUser->name,
                    'role' => $targetUser->role_label
                ]
            ], 200);
        }

        // 3. CEK LOGIKA B: APAKAH NOMOR KARTU SUDAH TERDAFTAR DI DATABASE?
        $user = User::where('rfid_uid', $cleanUid)->first();

        if ($user) {
            // Periksa apakah hak akses portal gerbang aktif
            if (!$user->portal_access_enabled) {
                IotPortalLog::create([
                    'device_id' => $deviceId,
                    'rfid_uid' => $cleanUid,
                    'user_id' => $user->id,
                    'status' => 'denied',
                    'access_type' => 'rfid_tap',
                    'user_name' => $user->name,
                    'keterangan' => 'Akses ditolak: Izin akses dinonaktifkan sementara oleh pengurus.',
                ]);

                return response()->json([
                    'success' => false,
                    'access' => false,
                    'status' => 'denied',
                    'message' => 'Akses Ditolak: Kartu dinonaktifkan untuk ' . $user->name,
                    'uid' => $cleanUid,
                    'user' => $user->name
                ], 200);
            }

            // Akses DITERIMA! Palang gerbang terbuka
            try {
                IotPortalLog::create([
                    'device_id' => $deviceId,
                    'rfid_uid' => $cleanUid,
                    'user_id' => $user->id,
                    'status' => 'granted',
                    'access_type' => 'rfid_tap',
                    'user_name' => $user->name,
                    'keterangan' => 'Akses Diterima: ' . $user->name . ' (' . $user->role_label . ') membuka palang gerbang.',
                ]);
                ActivityLog::log('Akses Portal Gerbang', 'Warga ' . $user->name . ' membuka portal gerbang dengan e-KTP UID ' . $cleanUid);
            } catch (\Throwable $e) {
                // Ignore log errors
            }

            return response()->json([
                'success' => true,
                'access' => true,
                'status' => 'granted',
                'message' => 'Kartu berhasil dibaca. Selamat datang, ' . $user->name . '!',
                'uid' => $cleanUid,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role_label,
                    'email' => $user->email
                ]
            ], 200);
        }

        // 4. KONDISI C: KARTU BELUM TERDAFTAR (AKSES DITOLAK)
        try {
            IotPortalLog::create([
                'device_id' => $deviceId,
                'rfid_uid' => $cleanUid,
                'user_id' => null,
                'status' => 'denied',
                'access_type' => 'rfid_tap',
                'user_name' => 'Tamu / Kartu Tak Dikenal',
                'keterangan' => 'Akses Ditolak: e-KTP UID [' . $cleanUid . '] belum terdaftar di aplikasi KAWAN.',
            ]);
        } catch (\Throwable $e) {
            // Ignore log error
        }

        return response()->json([
            'success' => false,
            'access' => false,
            'status' => 'denied',
            'message' => 'Kartu berhasil dibaca (Akses ditolak: e-KTP belum terdaftar di aplikasi KAWAN)',
            'uid' => $cleanUid
        ], 200);
    }

    /**
     * Endpoint Menerima Sinyal Heartbeat ESP32 (POST /api/rfid/heartbeat)
     *
     * Header yang diharapkan:
     * - Content-Type: application/json
     * - Accept: application/json
     * - X-Device-Key: smartrw-rfid-device
     *
     * Body payload:
     * {
     *   "device_id": "esp32-scanner-01",
     *   "ip": "192.168.43.50"
     * }
     */
    public function heartbeat(Request $request)
    {
        $deviceId = $request->input('device_id', 'esp32-scanner-01');
        $ip = $request->input('ip', $request->ip());

        // Simpan status online alat di Cache Laravel (durasi 30 detik)
        Cache::put('rfid_reader_online', true, 30);
        Cache::put('rfid_device_id', $deviceId, 30);
        Cache::put('rfid_device_ip', $ip, 30);
        Cache::put('rfid_last_heartbeat', now()->toDateTimeString(), 30);

        // Update status perangkat di database
        IotDevice::updateOrCreate(
            ['device_id' => $deviceId],
            [
                'name' => 'ESP32 RFID Scanner Kelompok',
                'location' => 'Portal Gerbang Utama Perumahan',
                'secret_key' => 'smartrw-rfid-device',
                'status' => 'online',
                'ip_address' => $ip,
                'last_ping_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'online',
            'device_id' => $deviceId,
            'ip' => $ip,
            'server_time' => now()->toIso8601String()
        ], 200);
    }
}
