<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/core/Autoloader.php
 * Deskripsi: Autoloader mandiri berbasis standar PSR-4.
 * Menjamin resolusi kelas lintas platform (Windows & Linux Hosting).
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Core;

if (!defined('GMKI_SECURE_ACCESS') && php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Akses langsung ditolak.');
}

class Autoloader
{
    protected static string $baseDir;

    /**
     * Daftarkan autoloader ke SPL queue
     */
    public static function register(): void
    {
        self::$baseDir = dirname(__DIR__);

        spl_autoload_register(function (string $class) {
            if ($class === 'Shuchkin\\SimpleXLSX') {
                $file = self::$baseDir . '/packages/SimpleXLSX.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
            if ($class === 'Shuchkin\\SimpleXLSXGen') {
                $file = self::$baseDir . '/packages/SimpleXLSXGen.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }

            $prefix = 'App\\';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relativeClass = substr($class, $len);
            $parts = explode('\\', $relativeClass);

            // Coba 1: Jalur asli (misal: Controllers/Admin/DashboardController.php)
            $file = self::$baseDir . '/' . implode('/', $parts) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }

            // Coba 2: Folder pertama lowercase (misal: controllers/Admin/DashboardController.php)
            $partsLower = $parts;
            $partsLower[0] = strtolower($partsLower[0]);
            $fileLower = self::$baseDir . '/' . implode('/', $partsLower) . '.php';
            if (file_exists($fileLower)) {
                require_once $fileLower;
                return;
            }

            // Coba 3: Seluruh folder lowercase kecuali nama kelas
            $partsAllLower = array_map('strtolower', array_slice($parts, 0, -1));
            $partsAllLower[] = end($parts);
            $fileAllLower = self::$baseDir . '/' . implode('/', $partsAllLower) . '.php';
            if (file_exists($fileAllLower)) {
                require_once $fileAllLower;
                return;
            }
        });

        // Muat berkas helper penting
        $helpers = [
            self::$baseDir . '/helpers/functions.php',
            self::$baseDir . '/helpers/security.php',
            self::$baseDir . '/helpers/upload.php',
        ];

        foreach ($helpers as $helper) {
            if (file_exists($helper)) {
                require_once $helper;
            }
        }
    }
}
