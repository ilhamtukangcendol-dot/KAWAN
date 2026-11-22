# 🔌 PANDUAN FIRMWARE IOT ESP32 — PORTAL GERBANG KAWAN RT

File sketch Arduino:
📁 `d:\web-KAWAN\IOT\kawan_portal_esp32\kawan_portal_esp32.ino`

---

## 📦 Library Arduino yang Diperlukan
Buka **Arduino IDE** -> **Sketch** -> **Include Library** -> **Manage Libraries...**, lalu install:
1. **MFRC522** (oleh GithubCommunity / Miguel Balboa)
2. **ArduinoJson** (oleh Benoit Blanchon - versi 6 atau 7)
3. **ESP32Servo** (oleh Kevin Harrington)

---

## 📌 Skema Wiring / Pinout ESP32 (30/38 Pin)

### 1. RFID-RC522 (Spi Mode)
| Pin RC522 | Pin ESP32 | Keterangan |
|-----------|-----------|------------|
| 3.3V      | 3V3       | **JANGAN KE 5V!** |
| RST       | GPIO 22   | Reset Pin |
| GND       | GND       | Ground |
| MISO      | GPIO 19   | SPI MISO |
| MOSI      | GPIO 23   | SPI MOSI |
| SCK       | GPIO 18   | SPI Clock |
| SDA (SS)  | GPIO 5    | Slave Select |

### 2. Servo Motor SG90 / MG995 / MG996R (Simulasi Palang Gerbang)
| Kabel Servo | Sambungan |
|-------------|-----------|
| Merah (VCC) | 5V / VIN / Adaptor Eksternal 5V |
| Cokelat/Hitam (GND) | GND ESP32 |
| Oranye/Kuning (Signal) | GPIO 13 |

### 3. Buzzer & LED Indikator
- **Buzzer (+)** : GPIO 12 (kaki minus ke GND)
- **LED Hijau (+)** : GPIO 2 (kaki minus lewat resistor 220Ω ke GND)
- **LED Merah (+)** : GPIO 4 (kaki minus lewat resistor 220Ω ke GND)

---

## ⚙️ Konfigurasi WiFi di Sketch
Buka file `kawan_portal_esp32.ino`, lalu sesuaikan bagian atas:

```cpp
const char* WIFI_SSID     = "NAMA_WIFI_KAMU";
const char* WIFI_PASSWORD = "PASSWORD_WIFI";

// Server API (bisa online VPS atau lokal)
// Jika Online:
const char* API_BASE_URL  = "https://kawan.unique-monsters.my.id/api/iot";
// Jika Lokal (ganti IP dengan IP laptop kamu):
// const char* API_BASE_URL = "http://192.168.1.10:8000/api/iot";

const char* DEVICE_KEY    = "kawan_esp32_secret_2026";
```
