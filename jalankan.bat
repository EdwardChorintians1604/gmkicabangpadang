@echo off
setlocal
title GMKI Cabang Padang - Apache Local Server
cls
cd /d "%~dp0"

set "HTTPD=E:\WebProgBPNama\apache2\bin\httpd.exe"
set "APACHE_CONFIG=%~dp0tools\apache\httpd-gmki.conf"

echo =======================================================================
echo          SISTEM INFORMASI ^& MANAJEMEN GMKI CABANG PADANG
echo =======================================================================
echo.
echo  [PENTING] Pastikan MoWeS (mowes.exe) SUDAH BERJALAN agar MySQL aktif!
echo.
echo  Apache + PHP 8 CGI   : http://127.0.0.1:8000
echo  Halaman Login Admin  : http://127.0.0.1:8000/login
echo  Kapasitas thread     : 32 request simultan
echo.
echo  Gunakan kredensial yang disiapkan administrator sistem.
echo.
echo  (Tekan CTRL + C untuk mematikan server)
echo =======================================================================
echo.

if not exist "%HTTPD%" (
    echo [ERROR] Apache tidak ditemukan: "%HTTPD%"
    pause
    exit /b 1
)

if not exist "%APACHE_CONFIG%" (
    echo [ERROR] Konfigurasi Apache tidak ditemukan: "%APACHE_CONFIG%"
    pause
    exit /b 1
)

"%HTTPD%" -t -f "%APACHE_CONFIG%"
if errorlevel 1 (
    echo [ERROR] Konfigurasi Apache tidak valid. Periksa tools\apache\httpd-gmki.conf.
    pause
    exit /b 1
)

"%HTTPD%" -w -f "%APACHE_CONFIG%"
if errorlevel 1 echo [ERROR] Apache berhenti. Periksa E:\WebProgBPNama\apache2\logs\gmki-error.log.
pause
