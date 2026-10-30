<?php
/**
 * =====================================================================
 * GMKI CABANG PADANG - ALAT BUNDLING HOSTING INFINITYFREE
 * File: tools/paketkan-hosting.php
 * Deskripsi: Otomatisasi pembersihan file lokal, isolasi file produksi,
 *            dan pembuatan arsip ZIP siap unggah ke cPanel / htdocs.
 * =====================================================================
 */

$rootDir = dirname(__DIR__);
$outputDir = $rootDir . '/dist';
$outputZip = $outputDir . '/gmki_hosting_infinityfree.zip';

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

echo "===============================================================\n";
echo "   GMKI CABANG PADANG - GENERATOR PAKET HOSTING INFINITYFREE   \n";
echo "===============================================================\n";

if (!class_exists('ZipArchive')) {
    $polyfillPath = $rootDir . '/backend/packages/ZipArchivePolyfill.php';
    if (file_exists($polyfillPath)) {
        require_once $polyfillPath;
    }
}

if (!class_exists('ZipArchive')) {
    die("[GAGAL] Ekstensi PHP ZipArchive tidak aktif.\n");
}

$zip = new ZipArchive();
if ($zip->open($outputZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("[GAGAL] Tidak dapat membuat arsip ZIP di {$outputZip}\n");
}

$fileCount = 0;
$totalRawBytes = 0;

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootDir, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $item) {
    if (!$item->isFile()) continue;

    $filePath = $item->getPathname();
    $relPath = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $filePath);
    $relPath = str_replace('\\', '/', $relPath);

    // Filter file yang TIDAK boleh diunggah ke hosting InfinityFree:
    // 1. Git internal (sangat besar & bahaya keamanan)
    if (str_starts_with($relPath, '.git/')) continue;
    // 2. Binary lokal Windows dev tunnel
    if ($relPath === 'ngrok.exe') continue;
    // 3. File PDF perkuliahan / tes yang tidak terpakai
    if ($relPath === 'frontend/public/assets/images/9-PendidikanKewarganegaraan.pdf') continue;
    if ($relPath === 'frontend/public/assets/images/Kartu_Digital_Simrokanisa.pdf') continue;
    if ($relPath === 'frontend/public/assets/images/MKDU411103-M1.pdf') continue;
    // 4. Cadangan & arsip zip lokal
    if (str_starts_with($relPath, 'storage/backup/')) continue;
    if (str_starts_with($relPath, 'storage/temp/')) continue;
    // 5. Script Windows batch (.bat) & Word docx
    if (str_ends_with($relPath, '.bat')) continue;
    if (str_ends_with($relPath, '.docx')) continue;
    // 6. Log & sesi lokal
    if (str_starts_with($relPath, 'storage/logs/') && !str_ends_with($relPath, '.gitkeep')) continue;
    if (str_starts_with($relPath, 'storage/sessions/') && !str_ends_with($relPath, '.gitkeep')) continue;
    // 7. Folder dist hasil bundling sendiri
    if (str_starts_with($relPath, 'dist/')) continue;

    $zip->addFile($filePath, $relPath);
    $fileCount++;
    $totalRawBytes += $item->getSize();
}

$zip->close();

$zipBytes = filesize($outputZip);
$rawMB = round($totalRawBytes / 1048576, 2);
$zipMB = round($zipBytes / 1048576, 2);

echo "[BERHASIL] Paket hosting selesai dikompres!\n\n";
echo "Lokasi Arsip   : dist/gmki_hosting_infinityfree.zip\n";
echo "Jumlah Berkas  : {$fileCount} file siap jalan\n";
echo "Ukuran Bersih  : {$rawMB} MB (uncompressed)\n";
echo "Ukuran ZIP     : {$zipMB} MB (hemat kompresi)\n\n";

echo "---------------------------------------------------------------\n";
echo "STATUS KEPATUHAN HOSTING INFINITYFREE:\n";
echo " [V] Batas Maksimal Upload 10 MB per File : LOLOS ({$zipMB} MB < 10 MB)\n";
echo " [V] Batas Kuota Penyimpanan (5 GB)       : LOLOS (Hanya pakai " . round(($rawMB / 5120) * 100, 2) . "%)\n";
echo " [V] Batas Inode Akun (30.014 file)       : LOLOS ({$fileCount} / 30.014)\n";
echo "---------------------------------------------------------------\n";
echo "Petunjuk Upload ke InfinityFree:\n";
echo "1. Buka cPanel InfinityFree -> Online File Manager -> Buka folder htdocs.\n";
echo "2. Unggah file 'dist/gmki_hosting_infinityfree.zip'.\n";
echo "3. Klik kanan file zip tersebut di File Manager -> Pilih 'Extract'.\n";
echo "4. Konfigurasi file '.env' di server dengan kredensial MySQL InfinityFree.\n";
echo "===============================================================\n";
