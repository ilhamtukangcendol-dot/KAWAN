<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('portal.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1 mb-2">
                    &larr; Kembali ke Dashboard Portal
                </a>
                <h1 class="text-2xl font-black text-white tracking-tight flex items-center gap-2">
                    <span>⚡ Panduan Hardware & Kode Arduino ESP32</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Skematik pengkabelan, daftar komponen, dan kode sumber firmware C++ siap upload ke ESP32.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="copyArduinoCode()" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white rounded-xl text-xs font-black shadow-lg transition flex items-center gap-1.5">
                    <span>📋</span> Salin Semua Kode (.ino)
                </button>
            </div>
        </div>

        <!-- 3 LANGKAH PERAKITAN ALAT -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 space-y-3">
                <div class="w-10 h-10 rounded-2xl bg-cyan-600/20 border border-cyan-500/40 text-cyan-300 flex items-center justify-center font-black text-sm">
                    1
                </div>
                <h3 class="text-sm font-black text-white">Komponen Hardware</h3>
                <ul class="text-xs text-slate-400 space-y-1.5 list-disc list-inside">
                    <li>ESP32 NodeMCU / DevKit V1</li>
                    <li>RFID Reader RC522 / PN532</li>
                    <li>Modul Relay 1 Channel 5V</li>
                    <li>Active Buzzer 5V & LED Indikator</li>
                    <li>Power Supply 5V / Adaptor</li>
                </ul>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 space-y-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600/20 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-black text-sm">
                    2
                </div>
                <h3 class="text-sm font-black text-white">Library Arduino IDE</h3>
                <ul class="text-xs text-slate-400 space-y-1.5 list-disc list-inside">
                    <li><strong class="text-white">MFRC522</strong> by GithubCommunity</li>
                    <li><strong class="text-white">ArduinoJson</strong> by Benoit Blanchon</li>
                    <li>Board ESP32 by Espressif Systems</li>
                    <li>Kecepatan Baud: <strong>115200</strong></li>
                </ul>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 space-y-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-600/20 border border-emerald-500/40 text-emerald-300 flex items-center justify-center font-black text-sm">
                    3
                </div>
                <h3 class="text-sm font-black text-white">Endpoint Terhubung</h3>
                <ul class="text-xs text-slate-400 space-y-1.5 list-disc list-inside">
                    <li>URL Scan: <code class="text-cyan-300 text-[10px]">/api/iot/portal/scan</code></li>
                    <li>Method: <strong class="text-white">POST (JSON)</strong></li>
                    <li>Autentikasi: Device ID & Key</li>
                    <li>Proteksi: KTP belum terdaftar otomatis ditolak (HTTP 403)</li>
                </ul>
            </div>
        </div>

        <!-- TABEL SKEMATIK WIRING PINOUT -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
            <div>
                <h2 class="text-sm font-black text-white flex items-center gap-2">
                    <span>🔌</span> Skematik Pinout Modul RC522 (RFID) ke ESP32
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Pilih skema sesuai tipe board mikrokontroler yang Anda gunakan.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- ARDUINO NANO ESP32 -->
                <div class="bg-slate-950/60 border border-indigo-500/30 rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between border-b border-indigo-500/20 pb-2">
                        <span class="text-xs font-black text-indigo-300 flex items-center gap-1.5">
                            <span>🟦</span> 1. Arduino Nano ESP32
                        </span>
                        <span class="text-[9px] bg-indigo-500/20 text-indigo-300 font-bold px-2 py-0.5 rounded-full">Nano Form Factor</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-300">
                            <thead>
                                <tr class="border-b border-slate-800 text-[9px] font-black uppercase text-slate-400">
                                    <th class="py-1.5 px-2">Pin RC522</th>
                                    <th class="py-1.5 px-2">Pin Nano ESP32</th>
                                    <th class="py-1.5 px-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/40 font-mono text-[11px]">
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">SDA (SS)</td>
                                    <td class="py-1.5 px-2 text-white font-bold">Pin D10 <span class="text-[9px] text-slate-500">(GPIO 21)</span></td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">SPI Slave Select</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">SCK</td>
                                    <td class="py-1.5 px-2 text-white font-bold">Pin D13 <span class="text-[9px] text-slate-500">(GPIO 48)</span></td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">SPI Clock</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">MOSI</td>
                                    <td class="py-1.5 px-2 text-white font-bold">Pin D11 <span class="text-[9px] text-slate-500">(GPIO 38)</span></td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">SPI MOSI</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">MISO</td>
                                    <td class="py-1.5 px-2 text-white font-bold">Pin D12 <span class="text-[9px] text-slate-500">(GPIO 47)</span></td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">SPI MISO</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">RST</td>
                                    <td class="py-1.5 px-2 text-white font-bold">Pin D9 <span class="text-[9px] text-slate-500">(GPIO 18)</span></td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">Reset Pin</td>
                                </tr>
                                <tr class="bg-amber-950/20 font-bold">
                                    <td class="py-1.5 px-2 text-amber-300">3.3V</td>
                                    <td class="py-1.5 px-2 text-amber-300">3V3</td>
                                    <td class="py-1.5 px-2 font-sans text-amber-300 text-[10px]">⚠️ JANGAN KE 5V/VBUS!</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-slate-400">GND</td>
                                    <td class="py-1.5 px-2 text-white">GND</td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">Ground</td>
                                </tr>
                                <tr class="bg-indigo-950/30">
                                    <td class="py-1.5 px-2 text-indigo-300 font-bold">Relay IN</td>
                                    <td class="py-1.5 px-2 text-white font-bold">Pin D8 <span class="text-[9px] text-slate-500">(GPIO 17)</span></td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">Trigger Gerbang (Active LOW)</td>
                                </tr>
                                <tr class="bg-indigo-950/30">
                                    <td class="py-1.5 px-2 text-indigo-300 font-bold">Buzzer (+)</td>
                                    <td class="py-1.5 px-2 text-white font-bold">Pin D7 <span class="text-[9px] text-slate-500">(GPIO 10)</span></td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">Buzzer Audio</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ESP32 STANDAR DEVKIT -->
                <div class="bg-slate-950/60 border border-cyan-500/30 rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between border-b border-cyan-500/20 pb-2">
                        <span class="text-xs font-black text-cyan-300 flex items-center gap-1.5">
                            <span>🟩</span> 2. ESP32 DevKit Standar (30/38 Pin)
                        </span>
                        <span class="text-[9px] bg-cyan-500/20 text-cyan-300 font-bold px-2 py-0.5 rounded-full">NodeMCU / DOIT</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-300">
                            <thead>
                                <tr class="border-b border-slate-800 text-[9px] font-black uppercase text-slate-400">
                                    <th class="py-1.5 px-2">Pin RC522</th>
                                    <th class="py-1.5 px-2">Pin ESP32 DevKit</th>
                                    <th class="py-1.5 px-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/40 font-mono text-[11px]">
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">SDA (SS)</td>
                                    <td class="py-1.5 px-2 text-white font-bold">GPIO 5</td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">SPI Slave Select</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">SCK</td>
                                    <td class="py-1.5 px-2 text-white font-bold">GPIO 18</td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">SPI Clock</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">MOSI</td>
                                    <td class="py-1.5 px-2 text-white font-bold">GPIO 23</td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">SPI MOSI</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">MISO</td>
                                    <td class="py-1.5 px-2 text-white font-bold">GPIO 19</td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">SPI MISO</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-cyan-300 font-bold">RST</td>
                                    <td class="py-1.5 px-2 text-white font-bold">GPIO 22</td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">Reset Pin</td>
                                </tr>
                                <tr class="bg-amber-950/20 font-bold">
                                    <td class="py-1.5 px-2 text-amber-300">3.3V</td>
                                    <td class="py-1.5 px-2 text-amber-300">3.3V</td>
                                    <td class="py-1.5 px-2 font-sans text-amber-300 text-[10px]">⚠️ JANGAN KE 5V/VIN!</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 text-slate-400">GND</td>
                                    <td class="py-1.5 px-2 text-white">GND</td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">Ground</td>
                                </tr>
                                <tr class="bg-indigo-950/30">
                                    <td class="py-1.5 px-2 text-indigo-300 font-bold">Relay IN</td>
                                    <td class="py-1.5 px-2 text-white font-bold">GPIO 26</td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">Trigger Gerbang (Active LOW)</td>
                                </tr>
                                <tr class="bg-indigo-950/30">
                                    <td class="py-1.5 px-2 text-indigo-300 font-bold">Buzzer (+)</td>
                                    <td class="py-1.5 px-2 text-white font-bold">GPIO 27</td>
                                    <td class="py-1.5 px-2 font-sans text-slate-400 text-[10px]">Buzzer Audio</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- CODE VIEWER -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2">
                    <span class="text-sm">📄</span>
                    <h2 class="text-sm font-black text-white">kawan_portal_esp32.ino (Arduino C++)</h2>
                </div>
                <button type="button" onclick="copyArduinoCode()" class="text-xs text-cyan-400 hover:text-cyan-300 font-bold flex items-center gap-1">
                    <span>📋</span> Salin Kode
                </button>
            </div>

            <pre id="arduino-code-block" class="p-5 bg-slate-950 border border-slate-800 rounded-2xl text-xs font-mono text-cyan-200 overflow-x-auto max-h-[500px] custom-scrollbar leading-relaxed"><code>{{ file_get_contents(resource_path('iot/kawan_portal_esp32.ino')) }}</code></pre>
        </div>

    </div>

    <script>
        function copyArduinoCode() {
            const code = document.getElementById('arduino-code-block').innerText;
            navigator.clipboard.writeText(code).then(() => {
                alert('✅ Kode Arduino (.ino) berhasil disalin ke clipboard!');
            }).catch(err => {
                alert('Gagal menyalin kode: ' + err);
            });
        }
    </script>
</x-app-layout>

