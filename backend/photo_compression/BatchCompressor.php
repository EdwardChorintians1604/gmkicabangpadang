<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/photo_compression/BatchCompressor.php
 * Deskripsi: Pemindai dan kompresor massal untuk seluruh foto yang ada
 *            di folder penyimpanan sistem.
 * =====================================================================
 */

declare(strict_types=1);

namespace App\PhotoCompression;

require_once __DIR__ . '/ImageCompressor.php';

class BatchCompressor
{
    protected ImageCompressor $compressor;
    protected string $rootDir;

    public function __construct(?string $rootDir = null, array $options = [])
    {
        $this->rootDir = $rootDir ?? dirname(__DIR__, 2);
        $this->compressor = new ImageCompressor($options);
    }

    /**
     * Dapatkan daftar direktori gambar di dalam proyek
     */
    public function getTargetDirectories(): array
    {
        return [
            'Foto Pengurus Organisasi' => $this->rootDir . '/frontend/public/uploads/organisasi',
            'Foto Warta & Berita'      => $this->rootDir . '/frontend/public/uploads/berita',
            'Lampiran Dokumen/Gambar'  => $this->rootDir . '/frontend/public/uploads/lampiran',
            'Foto Civitas / Anggota'   => $this->rootDir . '/storage/private/foto-anggota',
            'Berkas KTA Anggota'       => $this->rootDir . '/storage/private/kta',
        ];
    }

    /**
     * Jalankan kompresi pada seluruh direktori target
     */
    public function processAll(?callable $progressCallback = null): array
    {
        $directories = $this->getTargetDirectories();
        $overallStats = [
            'totalFilesFound'   => 0,
            'totalProcessed'    => 0,
            'totalSkipped'      => 0,
            'totalErrors'       => 0,
            'totalOriginalSize' => 0,
            'totalNewSize'      => 0,
            'totalSavedBytes'   => 0,
            'totalSavedPercent' => 0,
            'directoryResults'  => [],
        ];

        foreach ($directories as $categoryName => $dirPath) {
            $dirResult = $this->processDirectory($dirPath, $progressCallback, $categoryName);
            $overallStats['directoryResults'][$categoryName] = $dirResult;

            $overallStats['totalFilesFound']   += $dirResult['filesFound'];
            $overallStats['totalProcessed']    += $dirResult['processed'];
            $overallStats['totalSkipped']      += $dirResult['skipped'];
            $overallStats['totalErrors']       += $dirResult['errors'];
            $overallStats['totalOriginalSize'] += $dirResult['originalBytes'];
            $overallStats['totalNewSize']      += $dirResult['newBytes'];
            $overallStats['totalSavedBytes']   += $dirResult['savedBytes'];
        }

        if ($overallStats['totalOriginalSize'] > 0) {
            $overallStats['totalSavedPercent'] = round(
                ($overallStats['totalSavedBytes'] / $overallStats['totalOriginalSize']) * 100,
                1
            );
        }

        return $overallStats;
    }

    /**
     * Kompresi satu direktori secara rekursif
     */
    public function processDirectory(string $directory, ?callable $progressCallback = null, string $label = ''): array
    {
        $stats = [
            'directory'     => $directory,
            'label'         => $label,
            'filesFound'    => 0,
            'processed'     => 0,
            'skipped'       => 0,
            'errors'        => 0,
            'originalBytes' => 0,
            'newBytes'      => 0,
            'savedBytes'    => 0,
            'savedPercent'  => 0,
            'files'         => [],
        ];

        if (!is_dir($directory)) {
            return $stats;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }

            $extension = strtolower($fileInfo->getExtension());
            if (!in_array($extension, $allowedExtensions, true)) {
                continue;
            }

            $filePath = $fileInfo->getPathname();
            $stats['filesFound']++;
            $fileOrigSize = (int)filesize($filePath);
            $stats['originalBytes'] += $fileOrigSize;

            $result = $this->compressor->compressFile($filePath);

            if ($result['success']) {
                if (!empty($result['skipped'])) {
                    $stats['skipped']++;
                    $stats['newBytes'] += $fileOrigSize;
                } else {
                    $stats['processed']++;
                    $stats['newBytes']   += $result['compressedSize'];
                    $stats['savedBytes'] += $result['savedBytes'];
                }
            } else {
                $stats['errors']++;
                $stats['newBytes'] += $fileOrigSize;
            }

            $stats['files'][] = [
                'filename' => $fileInfo->getFilename(),
                'result'   => $result,
            ];

            if ($progressCallback !== null) {
                $progressCallback($fileInfo->getFilename(), $result, $label);
            }
        }

        if ($stats['originalBytes'] > 0) {
            $stats['savedPercent'] = round(
                ($stats['savedBytes'] / $stats['originalBytes']) * 100,
                1
            );
        }

        return $stats;
    }

    /**
     * Format byte menjadi satuan yang mudah dibaca (KB / MB / GB)
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
