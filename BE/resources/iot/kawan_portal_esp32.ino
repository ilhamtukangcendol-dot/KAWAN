/**
 * ==================================================================================
 * KAWAN 2026 - SISTEM IOT PORTAL GERBANG OTOMATIS BERBASIS ESP32 & e-KTP RFID/NFC
 * ==================================================================================
 * 
 * Hardware Requirements:
 * 1. ESP32 DevKit V1 (30 pin / 38 pin)
 * 2. RFID/NFC Reader Module: MFRC522 (RC522) atau PN532 (SPI Mode)
 * 3. 1-Channel Relay Module 5V (Mengendalikan Motor Palang / Magnetic Lock / Solenoid)
 * 4. Active Buzzer 5V & Dual LED (Hijau & Merah)
 * 
 * Pinout Wiring ESP32 to RC522:
 * - RC522 SDA (SS)   -> ESP32 GPIO 5
 * - RC522 SCK        -> ESP32 GPIO 18
 * - RC522 MOSI       -> ESP32 GPIO 23
 * - RC522 MISO       -> ESP32 GPIO 19
 * - RC522 RST        -> ESP32 GPIO 22
 * - RC522 3.3V       -> ESP32 3.3V (PERHATIAN: JANGAN KE 5V!)
 * - RC522 GND        -> ESP32 GND
 * 
 * Pinout Perangkat Tambahan:
 * - Relay IN         -> ESP32 GPIO 26 (Active LOW)
 * - Buzzer (+)       -> ESP32 GPIO 27
 * - LED Hijau (+)    -> ESP32 GPIO 14
 * - LED Merah (+)    -> ESP32 GPIO 12
 * ==================================================================================
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <ArduinoJson.h>

// ----------------- KONFIGURASI WI-FI -----------------
const char* WIFI_SSID     = "NAMA_WIFI_PERUMAHAN";
const char* WIFI_PASSWORD = "PASSWORD_WIFI";

// ----------------- KONFIGURASI SERVER KAWAN -----------------
const char* API_URL_SCAN = "https://kawan.unique-monsters.my.id/api/iot/portal/scan";
const char* API_URL_PING = "https://kawan.unique-monsters.my.id/api/iot/portal/ping";
const char* DEVICE_ID    = "PORTAL-GATE-01";
const char* DEVICE_KEY   = "kawan_esp32_secret_2026";

// ----------------- KONFIGURASI PIN HARDWARE -----------------
#define SS_PIN    5
#define RST_PIN   22
#define RELAY_PIN 26
#define BUZZER_PIN 27
#define LED_GREEN 14
#define LED_RED   12

MFRC522 rfid(SS_PIN, RST_PIN);
unsigned long lastPingTime = 0;
const unsigned long pingInterval = 30000; // Ping server tiap 30 detik

void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("\n[KAWAN 2026] Inisialisasi Sistem Portal Gerbang IoT...");

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

  // Siapkan Payload JSON
  StaticJsonDocument<256> doc;
  doc["device_id"]  = DEVICE_ID;
  doc["rfid_uid"]   = uid;
  doc["device_key"] = DEVICE_KEY;

  String jsonPayload;
  serializeJson(doc, jsonPayload);

  Serial.println("📡 Mengirim verifikasi ke server KAWAN...");
  int httpCode = http.POST(jsonPayload);

  if (httpCode > 0) {
    String response = http.getString();
    Serial.print("Response Server (HTTP ");
    Serial.print(httpCode);
    Serial.println("):");
    Serial.println(response);

    StaticJsonDocument<512> resDoc;
    DeserializationError err = deserializeJson(resDoc, response);

    if (!err) {
      const char* status = resDoc["status"];
      bool access = resDoc["access"] | false;
      const char* msg = resDoc["message"];

      // 1. KONDISI AKSES DITERIMA: BUKA GERBANG!
      if (access == true || strcmp(status, "granted") == 0) {
        Serial.println("🟢 [AKSES DITERIMA] Selamat Datang! Palang Gerbang Terbuka.");
        openGateBarrier(5000); // Buka selama 5 detik
      }
      // 2. KONDISI MODE PAIRING KTP BERHASIL
      else if (strcmp(status, "pairing_success") == 0) {
        Serial.println("✨ [PAIRING SUKSES] KTP Berhasil Didaftarkan!");
        beepSuccessPairing();
      }
      // 3. KONDISI AKSES DITOLAK (KTP BELUM TERDAFTAR)
      else {
        Serial.println("🚫 [AKSES DITOLAK] e-KTP belum terdaftar di aplikasi KAWAN!");
        beepAccessDenied();
      }
    } else {
      Serial.println("JSON Parsing Error!");
      beepError();
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

    StaticJsonDocument<128> doc;
    doc["device_id"] = DEVICE_ID;
    String payload;
    serializeJson(doc, payload);

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

