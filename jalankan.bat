@echo off
setlocal
title GMKI Cabang Padang - Web Server
cls
cd /d "%~dp0"

echo =======================================================================
echo          SISTEM INFORMASI ^& MANAJEMEN GMKI CABANG PADANG
echo                      PELUNCUR WEB SERVER
echo =======================================================================
echo.

:: 1. Cari PHP Executable
set "PHP_BIN="
where php >nul 2>&1 && set "PHP_BIN=php"
if not defined PHP_BIN for /d %%D in ("D:\laragon\bin\php\php-*") do if exist "%%~fD\php.exe" set "PHP_BIN=%%~fD\php.exe"
if not defined PHP_BIN for /d %%D in ("C:\laragon\bin\php\php-*") do if exist "%%~fD\php.exe" set "PHP_BIN=%%~fD\php.exe"
if not defined PHP_BIN if exist "E:\WebProgBPNama\php8\php.exe" set "PHP_BIN=E:\WebProgBPNama\php8\php.exe"

if "%PHP_BIN%"=="" (
    echo [ERROR] PHP tidak ditemukan. Pastikan Laragon sudah terpasang.
    pause
    exit /b 1
)

:: 2. Cek apakah Port 80 (Apache Laragon) sedang aktif
netstat -ano | findstr "LISTENING" | findstr ":80\>" >nul 2>&1
if errorlevel 1 goto RUN_BUILTIN_SERVER

:RUN_LARAGON_APACHE
echo  [INFO] Web Server Laragon Port 80 TERDETEKSI AKTIF!
echo.
echo  Alamat Website       : http://gmkicabangpadang.test
echo  Halaman Login Admin  : http://gmkicabangpadang.test/login
echo  URL Alternatif       : http://localhost/gmkicabangpadang
echo.
echo  Membuka browser...
start http://gmkicabangpadang.test
echo.
echo  =======================================================================
echo  Sistem berjalan melalui Apache Laragon.
echo  Tekan tombol apa saja untuk menutup jendela ini.
echo  =======================================================================
pause
exit /b 0

:RUN_BUILTIN_SERVER
echo  [INFO] Apache Laragon belum aktif. Menjalankan Server Mandiri Port 8000...
echo.
echo  Menggunakan PHP      : %PHP_BIN%
echo  Alamat Server Lokal  : http://127.0.0.1:8000
echo  Halaman Login Admin  : http://127.0.0.1:8000/login
echo.
echo  (Tekan CTRL + C untuk mematikan server)
echo =======================================================================
echo.

timeout /t 2 /nobreak >nul 2>&1 || ping 127.0.0.1 -n 3 >nul 2>&1
start http://127.0.0.1:8000

"%PHP_BIN%" -S 127.0.0.1:8000 -t frontend\public frontend\public\index.php
pause
