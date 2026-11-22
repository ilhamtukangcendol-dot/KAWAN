# 🔌 PANDUAN FIRMWARE IOT ESP32 — PORTAL GERBANG KAWAN RT

File sketch Arduino:
📁 `d:\web-KAWAN\IOT\kawan_portal_esp32\kawan_portal_esp32.ino`

Mendukung 2 Jenis Board:
1. **Arduino Nano ESP32**
2. **ESP32 Standar (DevKit 30/38 pin)**

---

## 📦 Library Arduino yang Diperlukan
Buka **Arduino IDE** -> **Sketch** -> **Include Library** -> **Manage Libraries...**, lalu install:
1. **MFRC522** (oleh GithubCommunity / Miguel Balboa)
2. **ArduinoJson** (oleh Benoit Blanchon - versi 6 atau 7)

---

## 📌 1. Skema Wiring: Arduino Nano ESP32

| Pin RC522 | Pin Arduino Nano ESP32 | Keterangan |
|-----------|------------------------|------------|
| **SDA (SS)** | **Pin D10** (GPIO 21) | SPI Slave Select |
| **SCK**      | **Pin D13** (GPIO 48) | SPI Clock |
| **MOSI**     | **Pin D11** (GPIO 38) | SPI Master Out Slave In |
| **MISO**     | **Pin D12** (GPIO 47) | SPI Master In Slave Out |
| **RST**      | **Pin D9**  (GPIO 18) | Reset Pin |
| **GND**      | **GND**               | Ground Bersama |
| **3.3V**     | **3V3**               | ⚠️ **WAJIB 3.3V (JANGAN KE 5V / VBUS!)** |

**Perangkat Output (Arduino Nano ESP32):**
- **Relay IN** : Pin **D8** (GPIO 17) [Active LOW]
- **Buzzer (+)** : Pin **D7** (GPIO 10)
- **LED Hijau (+)** : Pin **D6** (GPIO 9)
- **LED Merah (+)** : Pin **D5** (GPIO 8)

---

## 📌 2. Skema Wiring: ESP32 DevKit Standar (30 / 38 Pin)

| Pin RC522 | Pin ESP32 DevKit | Keterangan |
|-----------|------------------|------------|
| **SDA (SS)** | **GPIO 5**       | SPI Slave Select |
| **SCK**      | **GPIO 18**      | SPI Clock |
| **MOSI**     | **GPIO 23**      | SPI Master Out Slave In |
| **MISO**     | **GPIO 19**      | SPI Master In Slave Out |
| **RST**      | **GPIO 22**      | Reset Pin |
| **GND**      | **GND**          | Ground Bersama |
| **3.3V**     | **3.3V**         | ⚠️ **WAJIB 3.3V (JANGAN KE 5V / VIN!)** |

**Perangkat Output (ESP32 DevKit):**
- **Relay IN** : **GPIO 26** [Active LOW]
- **Buzzer (+)** : **GPIO 27**
- **LED Hijau (+)** : **GPIO 14**
- **LED Merah (+)** : **GPIO 12**

---

## ⚙️ Cara Memilih Board di Sketch (`kawan_portal_esp32.ino`)

Di baris 53 file `.ino`:

- Jika memakai **Arduino Nano ESP32**:
  Cukup pilih board **"Arduino Nano ESP32"** di menu **Tools -> Board** di Arduino IDE, atau aktifkan baris:
  ```cpp
  #define BOARD_ARDUINO_NANO_ESP32
  ```

- Jika memakai **ESP32 DevKit Standar (30/38 Pin)**:
  Biarkan baris tersebut dikomentari (default):
  ```cpp
  // #define BOARD_ARDUINO_NANO_ESP32
  ```

---

## 📶 Konfigurasi WiFi & API Server
```cpp
const char* WIFI_SSID     = "NAMA_WIFI_KAMU";
const char* WIFI_PASSWORD = "PASSWORD_WIFI";

// Server API KAWAN
const char* API_URL_SCAN = "https://kawan.unique-monsters.my.id/api/iot/portal/scan";
const char* API_URL_PING = "https://kawan.unique-monsters.my.id/api/iot/portal/ping";
const char* DEVICE_ID    = "PORTAL-GATE-01";
const char* DEVICE_KEY   = "kawan_esp32_secret_2026";
```
