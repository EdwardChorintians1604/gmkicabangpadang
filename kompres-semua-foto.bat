@echo off
setlocal
title GMKI Cabang Padang - Kompresi Massal Foto Sistem
cls

cd /d "%~dp0"

set "PHP_EXE="
where php >nul 2>&1 && set "PHP_EXE=php"
if not defined PHP_EXE for /d %%D in ("D:\laragon\bin\php\php-*") do if exist "%%~fD\php.exe" set "PHP_EXE=%%~fD\php.exe"
if not defined PHP_EXE for /d %%D in ("C:\laragon\bin\php\php-*") do if exist "%%~fD\php.exe" set "PHP_EXE=%%~fD\php.exe"
if not defined PHP_EXE if exist "E:\WebProgBPNama\php8\php.exe" set "PHP_EXE=E:\WebProgBPNama\php8\php.exe"

set "SCRIPT_PATH=%~dp0backend\photo_compression\run_compression.php"

if "%PHP_EXE%"=="" (
    echo [ERROR] PHP tidak ditemukan di sistem atau Laragon.
    pause
    exit /b 1
)

if not exist "%SCRIPT_PATH%" (
    echo [ERROR] Skrip kompresi tidak ditemukan di: "%SCRIPT_PATH%"
    pause
    exit /b 1
)

"%PHP_EXE%" "%SCRIPT_PATH%"

echo Tekan tombol apa saja untuk menutup jendela ini...
pause >nul
