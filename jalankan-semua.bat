@echo off
setlocal
title GMKI Cabang Padang - Peluncur Sistem Terpadu
cls
echo =======================================================================
echo          SISTEM INFORMASI ^& MANAJEMEN GMKI CABANG PADANG
echo                      PELUNCUR SISTEM TERPADU
echo =======================================================================
echo.
echo  [PENTING] Pastikan MySQL di Laragon SUDAH AKTIF (Klik Start All di Laragon).
echo.
echo  1. Membuka Web Server...
start "Web Server GMKI" cmd /c "%~dp0jalankan.bat"
timeout /t 2 /nobreak >nul 2>&1 || ping 127.0.0.1 -n 3 >nul 2>&1

echo  2. Mengaktifkan Antrean Worker Ekspor Dokumen...
start "Worker Antrean GMKI" cmd /c "%~dp0jalankan-queue.bat"
timeout /t 1 /nobreak >nul 2>&1 || ping 127.0.0.1 -n 2 >nul 2>&1

echo.
set "ENABLE_NGROK=n"
set /p "ENABLE_NGROK=Apakah Anda ingin mengaktifkan tunnel online Ngrok? (y/N): "
if /i "%ENABLE_NGROK:~0,1%"=="y" goto ACTIVATE_NGROK

echo  3. Ngrok dilewati - berjalan dalam mode lokal aman.
goto SUMMARY

:ACTIVATE_NGROK
echo  3. Mengaktifkan Tunnel Ngrok Online...
start "Ngrok Tunnel GMKI" cmd /c "%~dp0jalankan-ngrok.bat"

:SUMMARY
echo.
echo  =======================================================================
echo  Layanan sedang berjalan di jendela Command Prompt masing-masing:
echo  - Web Server     : http://gmkicabangpadang.test atau http://127.0.0.1:8000
echo  - Worker Antrean : Memproses ekspor dan antrean di latar belakang
echo  =======================================================================
echo.
pause
