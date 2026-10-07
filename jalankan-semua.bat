@echo off
title GMKI Cabang Padang - Peluncur Sistem Terpadu
cls
echo =======================================================================
echo          SISTEM INFORMASI ^& MANAJEMEN GMKI CABANG PADANG
echo             PELUNCUR OTOMATIS: SERVER LOKAL + NGROK ONLINE
echo =======================================================================
echo.
echo  [PENTING] Pastikan MoWeS (mowes.exe) SUDAH AKTIF agar MySQL terhubung!
echo.
echo  1. Membuka Server Lokal di Port 8000...
start "Server Lokal GMKI" cmd /c "%~dp0jalankan.bat"
timeout /t 3 /nobreak >nul

echo  2. Mengaktifkan Tunnel Ngrok Online...
start "Ngrok Tunnel GMKI" cmd /c "%~dp0jalankan-ngrok.bat"

echo.
echo  =======================================================================
echo  Keduanya sedang berjalan di jendela Command Prompt masing-masing.
echo  - Apache Lokal  : http://localhost:8000
echo  - Status Ngrok  : http://127.0.0.1:4040
echo  =======================================================================
echo.
pause
