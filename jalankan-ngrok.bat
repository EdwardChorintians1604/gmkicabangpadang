@echo off
title GMKI Cabang Padang - Ngrok Online Tunnel
cls
echo =======================================================================
echo          TUNNEL ONLINE NGROK - GMKI CABANG PADANG
echo =======================================================================
echo.
echo  [INFO] Meneruskan port lokal 8000 ke internet publik (HTTPS).
echo  Pastikan MoWeS dan server lokal (jalankan.bat) SUDAH BERJALAN!
echo.
echo  Panel Status Ngrok : http://127.0.0.1:4040
echo.
echo  (Tekan CTRL + C untuk menutup akses tunnel online)
echo =======================================================================
echo.

if exist "%~dp0ngrok.exe" (
    "%~dp0ngrok.exe" http 8000
) else if exist "%~dp0..\ngrok.exe" (
    "%~dp0..\ngrok.exe" http 8000
) else (
    ngrok http 8000
)

pause
