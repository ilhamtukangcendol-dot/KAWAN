/**
 * ==================================================================================
 * KAWAN 2026 - SISTEM IOT PORTAL GERBANG OTOMATIS BERBASIS ESP32 & e-KTP RFID/NFC
 * ==================================================================================
 * 
 * Mendukung 2 Jenis Board:
 * 1. Arduino Nano ESP32
 * 2. ESP32 Standar DevKit (30 pin / 38 pin)
 * 
 * ==================================================================================
 * TABEL WIRING PINOUT:
 * ==================================================================================
 * 
 * 1. Wiring RC522 ke Arduino Nano ESP32:
 *   RC522   | Pin Arduino Nano ESP32 | Keterangan
 *   --------|------------------------|---------------------------------
 *   SDA(SS) | Pin D10 (GPIO 21)      | SPI Slave Select
 *   SCK     | Pin D13 (GPIO 48)      | SPI Clock
 *   MOSI    | Pin D11 (GPIO 38)      | SPI MOSI
 *   MISO    | Pin D12 (GPIO 47)      | SPI MISO
 *   RST     | Pin D9  (GPIO 18)      | Reset Pin
 *   GND     | GND                    | Ground Bersama
 *   3.3V    | 3V3                    | ⚠️ WAJIB 3.3V (JANGAN KE 5V / VBUS!)
 * 
 *   Perangkat Tambahan (Arduino Nano ESP32):
 *   - Relay IN    -> Pin D8 (GPIO 17) [Active LOW]
 *   - Buzzer (+)  -> Pin D7 (GPIO 10)
 *   - LED Hijau   -> Pin D6 (GPIO 9)
 *   - LED Merah   -> Pin D5 (GPIO 8)
 * 
 * ----------------------------------------------------------------------------------
 * 
 * 2. Wiring RC522 ke ESP32 Standar (DevKit 30/38 pin):
 *   RC522   | Pin ESP32 DevKit       | Keterangan
 *   --------|------------------------|---------------------------------
 *   SDA(SS) | GPIO 5                 | SPI Slave Select
 *   SCK     | GPIO 18                | SPI Clock
 *   MOSI    | GPIO 23                | SPI MOSI
 *   MISO    | GPIO 19                | SPI MISO
 *   RST     | GPIO 22                | Reset Pin
 *   GND     | GND                    | Ground Bersama
 *   3.3V    | 3.3V                   | ⚠️ WAJIB 3.3V (JANGAN KE 5V / VIN!)
 * 
 *   Perangkat Tambahan (ESP32 DevKit):
 *   - Relay IN    -> GPIO 26 [Active LOW]
 *   - Buzzer (+)  -> GPIO 27
 *   - LED Hijau   -> GPIO 14
 *   - LED Merah   -> GPIO 12
 * ==================================================================================
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

// ==================================================================================
// PILIH TIPE BOARD ESP32:
// Jika memakai Arduino Nano ESP32, hilangkan tanda // pada baris di bawah:
// ==================================================================================
// #define BOARD_ARDUINO_NANO_ESP32

#if defined(ARDUINO_NANO_ESP32) || defined(BOARD_ARDUINO_NANO_ESP32)
  #define BOARD_NAME "Arduino Nano ESP32"
  #define SS_PIN     D10   // Pin D10 (GPIO 21)
  #define RST_PIN    D9    // Pin D9 (GPIO 18)
  #define RELAY_PIN  D8    // Pin D8 (GPIO 17)
  #define BUZZER_PIN D7    // Pin D7 (GPIO 10)
  #define LED_GREEN  D6    // Pin D6 (GPIO 9)
  #define LED_RED    D5    // Pin D5 (GPIO 8)
#else
  #define BOARD_NAME "ESP32 DevKit Standar (30/38 Pin)"
  #define SS_PIN     5     // GPIO 5
  #define RST_PIN    22    // GPIO 22
  #define RELAY_PIN  26    // GPIO 26
  #define BUZZER_PIN 27    // GPIO 27
  #define LED_GREEN  14    // GPIO 14
  #define LED_RED    12    // GPIO 12
#endif

// ----------------- KONFIGURASI WI-FI HOTSPOT -----------------
const char* WIFI_SSID     = "Poco F6505";
const char* WIFI_PASSWORD = "dimas12345";

// ----------------- KONFIGURASI SERVER KAWAN -----------------
const char* API_URL_SCAN = "https://kawan.unique-monsters.my.id/api/iot/portal/scan";
const char* API_URL_PING = "https://kawan.unique-monsters.my.id/api/iot/portal/ping";
const char* DEVICE_ID    = "PORTAL-GATE-01";
const char* DEVICE_KEY   = "kawan_esp32_secret_2026";

MFRC522 rfid(SS_PIN, RST_PIN);
unsigned long lastPingTime = 0;
const unsigned long pingInterval = 30000; // Ping heartbeat tiap 30 detik

void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("\n========================================================");
  Serial.println("  [KAWAN 2026] SISTEM IOT PORTAL GERBANG OTOMATIS");
  Serial.print("  [BOARD] Tipe Board: ");
  Serial.println(BOARD_NAME);
  Serial.println("========================================================");

  // Setup Pin IO
  pinMode(RELAY_PIN, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(LED_GREEN, OUTPUT);
  pinMode(LED_RED, OUTPUT);

  // Default state: Palang tertutup, LED Merah Menyala
  digitalWrite(RELAY_PIN, HIGH); // Relay Active-LOW (HIGH = Mati/Tutup)
  digitalWrite(LED_GREEN, LOW);
  digitalWrite(LED_RED, HIGH);
  digitalWrite(BUZZER_PIN, LOW);

  // Inisialisasi SPI & RFID RC522
  SPI.begin();
  rfid.PCD_Init();
  delay(100);
  rfid.PCD_DumpVersionToSerial();
  Serial.println("[RFID] Reader RC522 Siap Menunggu e-KTP.");

  // Koneksi Wi-Fi
  connectWiFi();

  // Nada pembuka saat booting sukses (2 beep pendek)
  beep(100); delay(100); beep(100);
}

void loop() {
  // Pastikan Wi-Fi tetap terhubung
  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }

  // Heartbeat ping ke server KAWAN tiap 30 detik
  if (millis() - lastPingTime > pingInterval) {
    sendHeartbeat();
    lastPingTime = millis();
  }

  // Cek apakah ada kartu e-KTP / RFID didekatkan
  if (!rfid.PICC_IsNewCardPresent()) {
    return;
  }
  if (!rfid.PICC_ReadCardSerial()) {
    return;
  }

  // Baca UID kartu e-KTP
  String uidStr = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    if (rfid.uid.uidByte[i] < 0x10) uidStr += "0";
    uidStr += String(rfid.uid.uidByte[i], HEX);
  }
  uidStr.toUpperCase();

  Serial.println("\n-------------------------------------------");
  Serial.print("💳 e-KTP / RFID Terdeteksi! UID: ");
  Serial.println(uidStr);

  // Kirim UID ke Server KAWAN untuk verifikasi hak akses
  verifyAccessWithServer(uidStr);

  // Hentikan enkripsi kartu agar siap untuk tap berikutnya
  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();

  delay(1500); // Debounce delay 1.5 detik agar tidak membaca ganda
}

// ================= FUNGSI VERIFIKASI KE SERVER =================
void verifyAccessWithServer(String uid) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("❌ Wi-Fi Terputus! Tidak dapat memverifikasi.");
    beepError();
    return;
  }

  HTTPClient http;
  http.begin(API_URL_SCAN);
  http.addHeader("Content-Type", "application/json");

  // Siapkan Payload JSON string langsung (tanpa perlu library eksternal)
  String jsonPayload = "{\"device_id\":\"" + String(DEVICE_ID) + "\",\"rfid_uid\":\"" + uid + "\",\"device_key\":\"" + String(DEVICE_KEY) + "\"}";

  Serial.println("📡 Mengirim verifikasi ke server KAWAN...");
  int httpCode = http.POST(jsonPayload);

  if (httpCode > 0) {
    String response = http.getString();
    Serial.print("Response Server (HTTP ");
    Serial.print(httpCode);
    Serial.println("):");
    Serial.println(response);

    // 1. KONDISI AKSES DITERIMA: BUKA GERBANG!
    if (response.indexOf("\"access\":true") >= 0 || response.indexOf("\"status\":\"granted\"") >= 0 || response.indexOf("granted") >= 0) {
      Serial.println("🟢 [AKSES DITERIMA] Selamat Datang! Palang Gerbang Terbuka.");
      openGateBarrier(5000); // Buka selama 5 detik
    }
    // 2. KONDISI MODE PAIRING KTP BERHASIL
    else if (response.indexOf("pairing_success") >= 0) {
      Serial.println("✨ [PAIRING SUKSES] KTP Berhasil Didaftarkan!");
      beepSuccessPairing();
    }
    // 3. KONDISI AKSES DITOLAK (KTP BELUM TERDAFTAR)
    else {
      Serial.println("🚫 [AKSES DITOLAK] e-KTP belum terdaftar di aplikasi KAWAN!");
      beepAccessDenied();
    }
  } else {
    Serial.print("HTTP Error: ");
    Serial.println(httpCode);
    beepError();
  }

  http.end();
}

// ================= FUNGSI KONTROL PALANG GERBANG =================
void openGateBarrier(int durationMs) {
  // Indikator lampu hijau & 1 beep sukses
  digitalWrite(LED_RED, LOW);
  digitalWrite(LED_GREEN, HIGH);
  beep(150);

  // Aktifkan Relay untuk menggerakkan motor palang gerbang
  digitalWrite(RELAY_PIN, LOW); // LOW = Relay Nyala / Buka Palang
  Serial.println("===> PALANG GERBANG TERANGKAT <===");

  // Tahan palang terbuka selama durasi yang ditentukan (misal 5 detik)
  delay(durationMs);

  // Tutup kembali palang gerbang
  digitalWrite(RELAY_PIN, HIGH); // HIGH = Relay Mati / Tutup Palang
  digitalWrite(LED_GREEN, LOW);
  digitalWrite(LED_RED, HIGH);
  Serial.println("===> PALANG GERBANG TERTUTUP KEMBALI <===");
}

// ================= INDIKATOR SUARA BUZZER =================
void beep(int ms) {
  digitalWrite(BUZZER_PIN, HIGH);
  delay(ms);
  digitalWrite(BUZZER_PIN, LOW);
}

void beepAccessDenied() {
  // Akses ditolak: 3 kali beep panjang & kedip merah
  for (int i = 0; i < 3; i++) {
    digitalWrite(LED_RED, LOW);
    digitalWrite(BUZZER_PIN, HIGH);
    delay(180);
    digitalWrite(LED_RED, HIGH);
    digitalWrite(BUZZER_PIN, LOW);
    delay(100);
  }
}

void beepSuccessPairing() {
  // Pairing berhasil: 2 kali nada gembira
  beep(120); delay(80);
  beep(250);
}

void beepError() {
  beep(500);
}

// ================= HEARTBEAT PING KE SERVER =================
void sendHeartbeat() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(API_URL_PING);
    http.addHeader("Content-Type", "application/json");

    String payload = "{\"device_id\":\"" + String(DEVICE_ID) + "\"}";
    http.POST(payload);
    http.end();
  }
}

// ================= KONEKSI WI-FI OTOMATIS =================
void connectWiFi() {
  Serial.print("[WiFi] Menghubungkan ke ");
  Serial.println(WIFI_SSID);
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 20) {
    delay(500);
    Serial.print(".");
    attempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[WiFi] Berhasil Terhubung!");
    Serial.print("[WiFi] IP Address ESP32: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("\n[WiFi] Gagal terhubung. Akan dicoba lagi nanti.");
  }
}
