@echo off
title GMKI Cabang Padang - Queue Worker
cd /d "%~dp0"
echo Worker antrean berjalan. Biarkan jendela ini terbuka.
"E:\WebProgBPNama\php8\php.exe" tools\queue_worker.php
pause
