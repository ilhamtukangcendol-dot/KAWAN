<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Warga;
use App\Models\IotDevice;
use App\Models\IotPortalLog;
use App\Models\IotPairingSession;
use App\Models\ActivityLog;

class IotPortalController extends Controller
{
    /**
     * Tampilkan Halaman Utama Portal Gerbang & IoT RFID
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil data perangkat gerbang utama
        $device = IotDevice::firstOrCreate(
            ['device_id' => 'PORTAL-GATE-01'],
            [
                'name' => 'Portal Gerbang Utama Perumahan',
                'location' => 'Pos Keamanan & Portal RT 01 / RW 05',
                'secret_key' => 'kawan_esp32_secret_2026',
                'status' => 'online',
                'last_ping_at' => now(),
            ]
        );

        // Ambil 10 log akses terbaru
        if ($user->role == User::ROLE_WARGA) {
            // Warga hanya melihat riwayat aksesnya sendiri
            $recentLogs = IotPortalLog::where('user_id', $user->id)
                ->latest()
                ->take(15)
                ->get();
        } else {
            // Pengurus & Superadmin melihat semua log
            $recentLogs = IotPortalLog::with('user')
                ->latest()
                ->take(20)
                ->get();
        }

        // Statistik portal
        $stats = [
            'total_registered' => User::whereNotNull('rfid_uid')->count(),
            'total_granted_today' => IotPortalLog::where('status', 'granted')->whereDate('created_at', today())->count(),
            'total_denied_today' => IotPortalLog::where('status', 'denied')->whereDate('created_at', today())->count(),
            'device_online' => $device->is_online,
        ];

        // Daftar warga dan kartu KTP (untuk admin/pengurus)
        $usersList = null;
        if ($user->role != User::ROLE_WARGA) {
            $usersQuery = User::with('warga')->orderBy('name', 'asc');
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $usersQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('rfid_uid', 'like', "%{$search}%");
                });
            }
            if ($request->has('filter') && $request->filter == 'registered') {
                $usersQuery->whereNotNull('rfid_uid');
            } elseif ($request->has('filter') && $request->filter == 'unregistered') {
                $usersQuery->whereNull('rfid_uid');
            }
            $usersList = $usersQuery->paginate(15)->withQueryString();
        }

        return view('portal.index', compact('device', 'recentLogs', 'stats', 'usersList'));
    }

    /**
     * Buka Portal Manual dari Web Dashboard (Pengurus/Satpam)
     */
    public function openManual(Request $request)
    {
        $user = Auth::user();

        // Catat log buka manual
        $log = IotPortalLog::create([
            'device_id' => 'PORTAL-GATE-01',
            'rfid_uid' => 'MANUAL-WEB',
            'user_id' => $user->id,
            'status' => 'granted',
            'access_type' => 'manual_web',
            'user_name' => $user->name,
            'keterangan' => 'Gerbang dibuka secara manual via aplikasi web oleh ' . $user->name . ' (' . $user->role_label . ')',
        ]);

        ActivityLog::record(
            ActivityLog::TYPE_ACCESS,
            'Buka Portal Gerbang Manual',
            'Pengurus ' . $user->name . ' membuka portal gerbang utama via tombol web.'
        );

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sinyal Buka Gerbang Manual Berhasil Dikirim!',
                'log' => $log
            ]);
        }

        return back()->with('success', 'Perintah buka gerbang manual berhasil dieksekusi!');
    }

    /**
     * Mulai Sesi Pairing (Pendaftaran KTP via Tap ESP32 atau Web NFC)
     */
    public function startPairing(Request $request)
    {
        $user = Auth::user();

        // Nonaktifkan sesi sebelumnya yang masih menunggu
        IotPairingSession::where('user_id', $user->id)
            ->where('status', 'waiting')
            ->delete();

        // Buat sesi pairing baru (berlaku 90 detik)
        $token = 'PAIR-' . strtoupper(bin2hex(random_bytes(8)));
        $session = IotPairingSession::create([
            'user_id' => $user->id,
            'token' => $token,
            'status' => 'waiting',
            'expires_at' => now()->addSeconds(90),
        ]);

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'expires_in_seconds' => 90,
            'message' => 'Sesi pairing aktif. Silakan tempelkan e-KTP Anda ke Reader ESP32 atau scan dengan ponsel NFC Anda sekarang!'
        ]);
    }

    /**
     * Polling Sesi Pairing dari Browser
     */
    public function checkPairing(Request $request, $token)
    {
        $session = IotPairingSession::where('token', $token)->first();

        if (!$session) {
            return response()->json(['status' => 'not_found', 'message' => 'Sesi tidak ditemukan.'], 404);
        }

        if ($session->is_expired && $session->status == 'waiting') {
            $session->update(['status' => 'expired']);
            return response()->json(['status' => 'expired', 'message' => 'Waktu pendaftaran KTP telah habis. Silakan ulangi kembali.']);
        }

        if ($session->status == 'scanned' || $session->status == 'completed') {
            return response()->json([
                'status' => 'completed',
                'rfid_uid' => $session->scanned_uid,
                'user_name' => $session->user->name,
                'message' => 'e-KTP UID [' . $session->scanned_uid . '] Berhasil Didaftarkan!'
            ]);
        }

        return response()->json([
            'status' => 'waiting',
            'seconds_remaining' => max(0, now()->diffInSeconds($session->expires_at, false))
        ]);
    }

    /**
     * Daftarkan / Update KTP RFID UID secara langsung (Form atau Web NFC)
     */
    public function registerKtp(Request $request)
    {
        $request->validate([
            'rfid_uid' => 'required|string|max:50',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $targetUser = Auth::user();
        if ($request->filled('user_id') && Auth::user()->role != User::ROLE_WARGA) {
            $targetUser = User::findOrFail($request->user_id);
        }

        // Bersihkan format UID (hilangkan tanda titik dua, spasi, dash)
        $cleanUid = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $request->rfid_uid));

        // Cek apakah UID sudah dipakai oleh warga/user lain
        $existing = User::where('rfid_uid', $cleanUid)->where('id', '!=', $targetUser->id)->first();
        if ($existing) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'UID KTP [' . $cleanUid . '] ini sudah terdaftar atas nama: ' . $existing->name . ' (' . $existing->email . '). Gunakan kartu lain atau hapus dari akun sebelumnya.'
                ], 422);
            }
            return back()->with('error', 'UID KTP ini sudah terdaftar atas nama warga lain: ' . $existing->name);
        }

        $targetUser->update([
            'rfid_uid' => $cleanUid,
            'rfid_registered_at' => now(),
            'portal_access_enabled' => true,
        ]);

        // Catat log
        IotPortalLog::create([
            'device_id' => 'PORTAL-GATE-01',
            'rfid_uid' => $cleanUid,
            'user_id' => $targetUser->id,
            'status' => 'granted',
            'access_type' => 'pairing',
            'user_name' => $targetUser->name,
            'keterangan' => 'Pendaftaran / Pairing KTP RFID Berhasil untuk: ' . $targetUser->name,
        ]);

        ActivityLog::record(
            ActivityLog::TYPE_UPDATE,
            'Registrasi Kartu KTP RFID',
            'Pengguna ' . $targetUser->name . ' berhasil mendaftarkan KTP RFID UID: ' . $cleanUid
        );

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'rfid_uid' => $cleanUid,
                'user_name' => $targetUser->name,
                'message' => 'e-KTP berhasil didaftarkan dan kartu siap digunakan untuk membuka gerbang!'
            ]);
        }

        return back()->with('success', 'e-KTP berhasil didaftarkan untuk warga: ' . $targetUser->name);
    }

    /**
     * Aktifkan / Nonaktifkan Akses Gerbang untuk User Tertentu
     */
    public function toggleAccess(Request $request, User $user)
    {
        $user->update([
            'portal_access_enabled' => !$user->portal_access_enabled
        ]);

        $statusStr = $user->portal_access_enabled ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            ActivityLog::TYPE_UPDATE,
            'Ubah Status Akses Portal',
            'Akses portal untuk ' . $user->name . ' telah ' . $statusStr . '.'
        );

        return back()->with('success', 'Akses gerbang untuk ' . $user->name . ' berhasil ' . $statusStr . '.');
    }

    /**
     * Hapus Kartu KTP Terdaftar dari User
     */
    public function removeKtp(Request $request, User $user)
    {
        $oldUid = $user->rfid_uid;
        $user->update([
            'rfid_uid' => null,
            'rfid_registered_at' => null,
        ]);

        ActivityLog::record(
            ActivityLog::TYPE_DELETE,
            'Hapus Kartu KTP RFID',
            'Kartu KTP UID ' . $oldUid . ' milik ' . $user->name . ' telah dihapus.'
        );

        return back()->with('success', 'Data kartu KTP berhasil dihapus.');
    }

    /**
     * Riwayat Lengkap Log Akses
     */
    public function logs(Request $request)
    {
        $query = IotPortalLog::with('user')->latest();

        if ($request->has('status') && in_array($request->status, ['granted', 'denied'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('rfid_uid', 'like', "%{$s}%")
                  ->orWhere('user_name', 'like', "%{$s}%")
                  ->orWhere('keterangan', 'like', "%{$s}%");
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('portal.logs', compact('logs'));
    }

    /**
     * Tampilkan Halaman Firmware & Panduan ESP32
     */
    public function firmware()
    {
        return view('portal.firmware');
    }

    // =========================================================================
    // API ENDPOINTS UNTUK PERANGKAT KERAS ESP32 (HARDWARE IOT)
    // =========================================================================

    /**
     * Endpoint Scan KTP dari ESP32 (POST /api/iot/portal/scan)
     * 
     * Body payload:
     * {
     *   "device_id": "PORTAL-GATE-01",
     *   "rfid_uid": "04A23B1C",
     *   "device_key": "kawan_esp32_secret_2026"
     * }
     */
    public function apiScan(Request $request)
    {
        $rawUid = $request->input('rfid_uid');
        $deviceId = $request->input('device_id', 'PORTAL-GATE-01');

        if (!$rawUid) {
            return response()->json([
                'status' => 'error',
                'access' => false,
                'message' => 'Parameter rfid_uid wajib dikirim oleh ESP32.'
            ], 400);
        }

        // Bersihkan UID
        $cleanUid = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $rawUid));

        // Update waktu ping perangkat
        IotDevice::where('device_id', $deviceId)->update([
            'last_ping_at' => now(),
            'status' => 'online',
            'ip_address' => $request->ip()
        ]);

        // 1. CEK APAKAH ADA SESI PAIRING KTP AKTIF (MODE PENDAFTARAN KTP DARI WEB)
        $activePairing = IotPairingSession::with('user')
            ->where('status', 'waiting')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($activePairing) {
            // Ada user yang sedang menunggu tap KTP di web!
            $targetUser = $activePairing->user;

            // Daftarkan UID ini ke user tersebut
            $targetUser->update([
                'rfid_uid' => $cleanUid,
                'rfid_registered_at' => now(),
                'portal_access_enabled' => true,
            ]);

            $activePairing->update([
                'status' => 'completed',
                'scanned_uid' => $cleanUid
            ]);

            // Catat log pairing
            IotPortalLog::create([
                'device_id' => $deviceId,
                'rfid_uid' => $cleanUid,
                'user_id' => $targetUser->id,
                'status' => 'granted',
                'access_type' => 'pairing',
                'user_name' => $targetUser->name,
                'keterangan' => 'Pairing Sukses: e-KTP didaftarkan untuk ' . $targetUser->name . ' (' . $targetUser->role_label . ')',
            ]);

            return response()->json([
                'status' => 'pairing_success',
                'access' => false, // Jangan buka palang saat mode pendaftaran
                'message' => 'KTP Berhasil Didaftarkan untuk ' . $targetUser->name . '!',
                'rfid_uid' => $cleanUid,
                'user_name' => $targetUser->name,
                'buzzer_beeps' => 2 // Bunyikan buzzer 2x tanda sukses pairing
            ]);
        }

        // 2. CEK APAKAH UID SUDAH TERDAFTAR DI DATABASE USER KAWAN
        $user = User::where('rfid_uid', $cleanUid)->first();

        if ($user) {
            // Cek apakah akses user ini aktif
            if (!$user->portal_access_enabled) {
                // AKSES DITOLAK: KARTU DINONAKTIFKAN SEMENTARA
                IotPortalLog::create([
                    'device_id' => $deviceId,
                    'rfid_uid' => $cleanUid,
                    'user_id' => $user->id,
                    'status' => 'denied',
                    'access_type' => 'rfid_ktp',
                    'user_name' => $user->name,
                    'keterangan' => 'Akses Ditolak: Kartu dinonaktifkan sementara untuk ' . $user->name,
                ]);

                return response()->json([
                    'status' => 'denied',
                    'access' => false,
                    'message' => 'Akses Ditolak! Kartu KTP Anda sedang dinonaktifkan sementara oleh pengurus.',
                    'rfid_uid' => $cleanUid,
                    'user_name' => $user->name,
                    'buzzer_beeps' => 3
                ]);
            }

            // AKSES DITERIMA: BUKA GERBANG!
            IotPortalLog::create([
                'device_id' => $deviceId,
                'rfid_uid' => $cleanUid,
                'user_id' => $user->id,
                'status' => 'granted',
                'access_type' => 'rfid_ktp',
                'user_name' => $user->name,
                'keterangan' => 'Akses Diterima: ' . $user->name . ' (' . $user->role_label . ') memasuki gerbang perumahan.',
            ]);

            return response()->json([
                'status' => 'granted',
                'access' => true,
                'message' => 'Akses Diterima! Selamat Datang, ' . $user->name,
                'open_duration_ms' => 5000, // Palang terbuka selama 5 detik
                'buzzer_beeps' => 1,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role_label,
                    'email' => $user->email,
                    'rfid_uid' => $cleanUid,
                ]
            ]);
        }

        // 3. AKSES DITOLAK: KTP BELUM TERDAFTAR DI SISTEM
        IotPortalLog::create([
            'device_id' => $deviceId,
            'rfid_uid' => $cleanUid,
            'user_id' => null,
            'status' => 'denied',
            'access_type' => 'rfid_ktp',
            'user_name' => 'Tidak Dikenal',
            'keterangan' => 'Akses Ditolak: KTP / Kartu RFID UID [' . $cleanUid . '] belum terdaftar di aplikasi KAWAN.',
        ]);

        return response()->json([
            'status' => 'denied',
            'access' => false,
            'message' => 'Akses Ditolak! KTP / Kartu RFID belum terdaftar di aplikasi web KAWAN.',
            'rfid_uid' => $cleanUid,
            'buzzer_beeps' => 4 // Alarm 4x beep
        ], 403);
    }

    /**
     * Heartbeat Ping dari ESP32 (POST /api/iot/portal/ping)
     */
    public function apiPing(Request $request)
    {
        $deviceId = $request->input('device_id', 'PORTAL-GATE-01');

        $device = IotDevice::updateOrCreate(
            ['device_id' => $deviceId],
            [
                'last_ping_at' => now(),
                'status' => 'online',
                'ip_address' => $request->ip(),
            ]
        );

        return response()->json([
            'status' => 'pong',
            'device_id' => $device->device_id,
            'server_time' => now()->toIso8601String(),
            'pairing_active' => IotPairingSession::where('status', 'waiting')->where('expires_at', '>', now())->exists()
        ]);
    }

    /**
     * Status Real-time untuk Live UI Dashboard (GET /api/iot/portal/status)
     */
    public function apiStatus(Request $request)
    {
        $latestLog = IotPortalLog::latest()->first();
        $device = IotDevice::where('device_id', 'PORTAL-GATE-01')->first();

        return response()->json([
            'device_online' => $device ? $device->is_online : false,
            'last_ping' => $device && $device->last_ping_at ? $device->last_ping_at->diffForHumans() : null,
            'latest_log' => $latestLog ? [
                'id' => $latestLog->id,
                'status' => $latestLog->status,
                'user_name' => $latestLog->user_name,
                'rfid_uid' => $latestLog->rfid_uid,
                'keterangan' => $latestLog->keterangan,
                'created_at' => $latestLog->created_at->format('H:i:s'),
                'time_ago' => $latestLog->created_at->diffForHumans(),
            ] : null,
            'total_granted_today' => IotPortalLog::where('status', 'granted')->whereDate('created_at', today())->count(),
            'total_denied_today' => IotPortalLog::where('status', 'denied')->whereDate('created_at', today())->count(),
        ]);
    }
}

