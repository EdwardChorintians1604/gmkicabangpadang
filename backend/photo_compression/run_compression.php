<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/photo_compression/run_compression.php
 * Deskripsi: Antarmuka CLI untuk menjalankan kompresi massal foto sistem.
 * Jalankan via terminal:
 *   E:\WebProgBPNama\php8\php.exe backend\photo_compression\run_compression.php
 * =====================================================================
 */

declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Akses hanya diizinkan melalui baris perintah (CLI).');
}

require_once __DIR__ . '/ImageCompressor.php';
require_once __DIR__ . '/BatchCompressor.php';

use App\PhotoCompression\BatchCompressor;

echo "\n";
echo "=======================================================================\n";
echo "     OPTIMASI & KOMPRESI MASSAL FOTO - GMKI CABANG PADANG            \n";
echo "=======================================================================\n";
echo " Waktu Eksekusi: " . date('d-m-Y H:i:s') . "\n";
echo " PHP Version   : " . PHP_VERSION . "\n";

if (!extension_loaded('gd')) {
    echo "\n[ERROR] Ekstensi PHP GD belum aktif. Kompresi gambar membutuhkan GD.\n";
    exit(1);
}

echo " Status GD     : Aktif (Mendukung JPEG, PNG, WebP)\n";
echo "-----------------------------------------------------------------------\n";
echo " Memulai pemindaian dan kompresi berkas foto sistem...\n\n";

$batch = new BatchCompressor();

$startTime = microtime(true);

$callback = function (string $filename, array $result, string $category) {
    if (!empty($result['skipped'])) {
        echo "  [-] [LEWATI] {$filename} ({$result['reason']})\n";
    } elseif ($result['success']) {
        $orig = BatchCompressor::formatBytes($result['originalSize']);
        $comp = BatchCompressor::formatBytes($result['compressedSize']);
        $pct  = $result['savedPercent'];
        $dim  = $result['dimensions']['to'] ?? '';
        echo "  [✓] [SUKSES] {$filename}\n";
        echo "      └─ {$orig} -> {$comp} (Hemat {$pct}% | Dimensi: {$dim})\n";
    } else {
        echo "  [x] [GAGAL]  {$filename} : {$result['error']}\n";
    }
};

$results = $batch->processAll($callback);
$elapsed = round(microtime(true) - $startTime, 2);

echo "\n=======================================================================\n";
echo "                      RINGKASAN HASIL KOMPRESI                         \n";
echo "=======================================================================\n";
echo " Total Berkas Foto Ditemukan : " . $results['totalFilesFound'] . "\n";
echo " Berkas Berhasil Dikompresi  : " . $results['totalProcessed'] . "\n";
echo " Berkas Dilewati (Sudah pas) : " . $results['totalSkipped'] . "\n";
echo " Berkas Gagal                : " . $results['totalErrors'] . "\n";
echo "-----------------------------------------------------------------------\n";
echo " Ukuran Sebelum Kompresi     : " . BatchCompressor::formatBytes($results['totalOriginalSize']) . "\n";
echo " Ukuran Setelah Kompresi     : " . BatchCompressor::formatBytes($results['totalNewSize']) . "\n";
echo " Total Ruang Disk Dihemat    : " . BatchCompressor::formatBytes($results['totalSavedBytes']) . " (" . $results['totalSavedPercent'] . "% hemat!)\n";
echo " Waktu Pengerjaan            : {$elapsed} detik\n";
echo "=======================================================================\n\n";
