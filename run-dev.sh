#!/usr/bin/env bash
# KAWAN RT - Local Dev Runner (Bash)

echo "========================================================"
echo "      KAWAN RT - LOCAL DEVELOPMENT RUNNER (BASH)"
echo "========================================================"
echo ""

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "[*] Backend  : cd BE && php artisan serve"
echo "[*] Frontend : cd FE && npm run dev"
echo ""

# Jalankan backend di background
(cd "$ROOT_DIR/BE" && php artisan serve) &
BE_PID=$!

# Jalankan frontend
(cd "$ROOT_DIR/FE" && npm run dev) &
FE_PID=$!

trap "kill $BE_PID $FE_PID 2>/dev/null; exit" SIGINT SIGTERM

echo "Backend PID: $BE_PID | Frontend PID: $FE_PID"
echo "Tekan Ctrl+C untuk menghentikan kedua server."
wait

