@echo off
title KAWAN RT Local Dev Launcher
echo ========================================================
echo       KAWAN RT - LOCAL DEVELOPMENT SERVER
echo ========================================================
echo.
echo Pastikan Laragon / MySQL sudah aktif!
echo.
echo [1/2] Menjalankan Laravel Backend (http://127.0.0.1:8000)...
start "KAWAN Backend (Port 8000)" cmd /k "cd /d %~dp0BE && php artisan serve"

echo [2/2] Menjalankan Vite Frontend (http://localhost:5173)...
start "KAWAN Frontend (Port 5173)" cmd /k "cd /d %~dp0FE && npm run dev"

echo.
echo ========================================================
echo   Backend  : http://127.0.0.1:8000
echo   Frontend : http://localhost:5173
echo ========================================================
echo Kedua server sedang berjalan di jendela terminal terpisah.
echo Anda dapat menutup jendela ini.
pause

