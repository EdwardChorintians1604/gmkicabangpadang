<?php
/**
 * GMKI Cabang Padang - Root Fallback Entry Point
 * Meneruskan akses langsung ke frontend/public/index.php
 */

// Konstanta pengaman agar file internal tidak dapat diakses secara langsung
if (!defined('GMKI_SECURE_ACCESS')) {
    define('GMKI_SECURE_ACCESS', true);
}

$publicIndex = __DIR__ . '/frontend/public/index.php';

if (file_exists($publicIndex)) {
    require_once $publicIndex;
} else {
    http_response_code(500);
    echo "Sistem GMKI Cabang Padang: berkas frontend/public/index.php tidak ditemukan.";
    exit;
}
