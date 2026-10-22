@echo off
setlocal
title GMKI Cabang Padang - Ngrok Online Tunnel
cls
echo =======================================================================
echo          TUNNEL ONLINE NGROK - GMKI CABANG PADANG
echo =======================================================================
echo.

set "TARGET_PORT=8000"
netstat -ano | findstr "LISTENING" | findstr ":80\>" >nul 2>&1
if not errorlevel 1 set "TARGET_PORT=80"

echo  [INFO] Meneruskan port lokal %TARGET_PORT% ke internet publik (HTTPS).
echo  Panel Status Ngrok : http://127.0.0.1:4040
echo.
echo  (Tekan CTRL + C untuk menutup akses tunnel online)
echo =======================================================================
echo.

set "NGROK_BIN="
if exist "%~dp0ngrok.exe" set "NGROK_BIN=%~dp0ngrok.exe"
if not defined NGROK_BIN if exist "D:\laragon\bin\ngrok\ngrok.exe" set "NGROK_BIN=D:\laragon\bin\ngrok\ngrok.exe"
if not defined NGROK_BIN where ngrok >nul 2>&1 && set "NGROK_BIN=ngrok"

if "%NGROK_BIN%"=="" (
    echo [ERROR] ngrok.exe tidak ditemukan.
    pause
    exit /b 1
)

if "%TARGET_PORT%"=="80" (
    echo Menghubungkan Ngrok ke Virtual Host Laragon: gmkicabangpadang.test ...
    "%NGROK_BIN%" http --host-header=gmkicabangpadang.test 80
) else (
    echo Menghubungkan Ngrok ke Port 8000 ...
    "%NGROK_BIN%" http 8000
)

pause
