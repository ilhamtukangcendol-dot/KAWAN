@echo off
title Push KAWAN ke GitHub
color 0b
echo =========================================================
echo       MENGUNGGAH PERUBAHAN WEB KAWAN KE GITHUB
echo =========================================================
echo.
echo Sedang melakukan push ke https://github.com/ilhamtukangcendol-dot/KAWAN ...
echo.

cd /d "D:\web-KAWAN"

"C:\Program Files\Git\cmd\git.exe" push origin main

if %ERRORLEVEL% EQU 0 (
    echo.
    echo =========================================================
    echo [BERHASIL] Seluruh fitur terbaru telah terunggah ke GitHub!
    echo =========================================================
    echo.
    echo Langkah Terakhir di Web Online (kawan.unique-monsters.my.id):
    echo 1. Buka cPanel hosting Anda -> Masuk ke "Git Version Control"
    echo 2. Klik "Pull or Deploy" -> Klik "Update from Remote"
    echo 3. Selesai! Buka kawan.unique-monsters.my.id/data-warga
    echo.
) else (
    echo.
    echo =========================================================
    echo [PERHATIAN] Jika muncul pop-up login GitHub, silakan klik
    echo "Sign in with your browser" untuk menyelesaikan login.
    echo =========================================================
    echo.
)

pause

