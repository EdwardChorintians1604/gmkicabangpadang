@echo off
setlocal
title GMKI Cabang Padang - Paketkan Hosting InfinityFree
cd /d "%~dp0"

set "PHP_BIN="
where php >nul 2>&1 && set "PHP_BIN=php"
if not defined PHP_BIN for /d %%D in ("D:\laragon\bin\php\php-*") do if exist "%%~fD\php.exe" set "PHP_BIN=%%~fD\php.exe"
if not defined PHP_BIN for /d %%D in ("C:\laragon\bin\php\php-*") do if exist "%%~fD\php.exe" set "PHP_BIN=%%~fD\php.exe"
if not defined PHP_BIN if exist "E:\WebProgBPNama\php8\php.exe" set "PHP_BIN=E:\WebProgBPNama\php8\php.exe"

if "%PHP_BIN%"=="" (
    echo [ERROR] PHP tidak ditemukan di sistem atau Laragon.
    pause
    exit /b 1
)

echo Memulai proses pemaketan file produksi untuk InfinityFree...
echo.
"%PHP_BIN%" tools\paketkan-hosting.php
echo.
pause
