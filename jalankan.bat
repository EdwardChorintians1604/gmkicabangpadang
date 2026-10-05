@echo off
title GMKI Cabang Padang - Local Server
cls
echo =======================================================================
echo          SISTEM INFORMASI ^& MANAJEMEN GMKI CABANG PADANG
echo =======================================================================
echo.
echo  [PENTING] Pastikan MoWeS (mowes.exe) SUDAH BERJALAN agar MySQL aktif!
echo.
echo  Server Web Aktif di  : http://localhost:8000
echo  Halaman Login Admin  : http://localhost:8000/login
echo.
echo  Default Login:
echo    Username : admin
echo    Password : Admin@GMKI2026!
echo.
echo  (Tekan CTRL + C untuk mematikan server)
echo =======================================================================
echo.

"E:\WebProgBPNama\php8\php.exe" -S localhost:8000 -t frontend/public frontend/public/index.php

pause
