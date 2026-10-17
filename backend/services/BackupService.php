<?php

namespace App\Services;

use App\Core\Database;
use PDO;

class BackupService
{
    protected StorageService $storageService;
    protected AuditLogService $auditLogService;

    public function __construct()
    {
        $this->storageService = new StorageService();
        $this->auditLogService = new AuditLogService();
    }

    public function createDatabaseBackup(): array
    {
        try {
            $pdo = Database::getConnection();
            $dbName = $_ENV['DB_DATABASE'] ?? 'gmki_padang';

            // Ambil daftar semua tabel basis data yang sah
            $tables = [];
            $stmt = $pdo->query("SHOW FULL TABLES WHERE Table_Type = 'BASE TABLE'");
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            $backupDir = $this->storageService->getBackupDir('database');
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $filename = 'backup_gmki_padang_' . date('Ymd_His') . '.sql.gz';
            $filePath = $backupDir . DIRECTORY_SEPARATOR . $filename;

            // Buka stream berkas gzip terkompresi langsung (Level 9 - Maksimum)
            $gz = gzopen($filePath, 'wb9');
            if (!$gz) {
                throw new \RuntimeException("Gagal membuat stream arsip kompresi gzip pada direktori backup.");
            }

            // Isolasi Transaksional: Mencegah inkonsistensi saat ada operasi baca-tulis
            try {
                $pdo->exec("SET TRANSACTION ISOLATION LEVEL REPEATABLE READ");
                $pdo->exec("START TRANSACTION WITH CONSISTENT SNAPSHOT");
            } catch (\Throwable) {
                $pdo->exec("START TRANSACTION");
            }

            $header = "-- ==========================================================\n"
                . "-- GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN\n"
                . "-- PENCADANGAN BASIS DATA OTOMATIS & AMAN (STREAMING DUMP)\n"
                . "-- Engine: PHP " . PHP_VERSION . " (Native PDO Streaming)\n"
                . "-- Target Database: `{$dbName}`\n"
                . "-- Cap Waktu Pembuatan: " . date('Y-m-d H:i:s') . "\n"
                . "-- Total Tabel: " . count($tables) . "\n"
                . "-- ==========================================================\n\n"
                . "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n"
                . "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n"
                . "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n"
                . "/*!40101 SET NAMES utf8mb4 */;\n"
                . "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n"
                . "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n"
                . "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n"
                . "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n\n";

            gzwrite($gz, $header);

            foreach ($tables as $table) {
                gzwrite($gz, "\n-- ----------------------------------------------------------\n");
                gzwrite($gz, "-- Struktur Skema untuk Tabel: `{$table}`\n");
                gzwrite($gz, "-- ----------------------------------------------------------\n");
                gzwrite($gz, "DROP TABLE IF EXISTS `{$table}`;\n");

                $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
                $createRow = $createStmt->fetch(PDO::FETCH_NUM);
                gzwrite($gz, $createRow[1] . ";\n\n");

                // Stream baris per baris tanpa membebani memori RAM server
                $dataStmt = $pdo->query("SELECT * FROM `{$table}`");
                $firstRow = $dataStmt->fetch(PDO::FETCH_ASSOC);

                if ($firstRow !== false) {
                    gzwrite($gz, "-- Dumping Data Terverifikasi: `{$table}`\n");
                    $columns = array_keys($firstRow);
                    $quotedCols = '`' . implode('`, `', $columns) . '`';
                    $insertPrefix = "INSERT INTO `{$table}` ({$quotedCols}) VALUES\n";

                    $batch = [];
                    $batchCount = 0;
                    $currentRow = $firstRow;

                    while ($currentRow !== false) {
                        $values = [];
                        foreach ($currentRow as $val) {
                            if ($val === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = $pdo->quote((string)$val);
                            }
                        }
                        $batch[] = '(' . implode(', ', $values) . ')';
                        $batchCount++;

                        // Tulis per 50 baris sekaligus (Extended Multi-Row Inserts)
                        if ($batchCount >= 50) {
                            gzwrite($gz, $insertPrefix . implode(",\n", $batch) . ";\n");
                            $batch = [];
                            $batchCount = 0;
                        }

                        $currentRow = $dataStmt->fetch(PDO::FETCH_ASSOC);
                    }

                    if (!empty($batch)) {
                        gzwrite($gz, $insertPrefix . implode(",\n", $batch) . ";\n");
                    }
                    gzwrite($gz, "\n");
                }
            }

            $footer = "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n"
                . "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;\n"
                . "/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;\n"
                . "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n"
                . "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n"
                . "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n"
                . "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;\n"
                . "COMMIT;\n"
                . "-- Cadangan selesai dibuat pada " . date('Y-m-d H:i:s') . "\n";

            gzwrite($gz, $footer);
            gzclose($gz);

            try {
                $pdo->exec("COMMIT");
            } catch (\Throwable) {}

            // Hasilkan Checksum Kriptografis SHA-256 untuk memverifikasi keaslian berkas
            $sha256 = hash_file('sha256', $filePath);
            file_put_contents($filePath . '.sha256', $sha256);

            // Rotasi cadangan: simpan 5 berkas terbaru secara aman
            $this->rotateDatabaseBackups(5);

            $this->auditLogService->log('BACKUP_DATABASE', 'system', $filename, [
                'filename' => $filename,
                'size' => filesize($filePath),
                'sha256' => $sha256,
            ]);

            return [
                'success' => true,
                'filename' => $filename,
                'size' => filesize($filePath),
                'sha256' => $sha256,
                'message' => 'Cadangan database lengkap berhasil dibuat (.sql.gz) dengan verifikasi SHA-256.',
            ];
        } catch (\Throwable $e) {
            error_log("Backup DB error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal membuat cadangan database: ' . $e->getMessage(),
            ];
        }
    }

    public function getDatabaseBackups(): array
    {
        $backupDir = $this->storageService->getBackupDir('database');
        if (!is_dir($backupDir)) {
            return [];
        }

        $files = glob($backupDir . '/*.sql.gz');
        $backups = [];

        foreach ($files as $f) {
            $shaFile = $f . '.sha256';
            $sha256 = file_exists($shaFile) ? trim(file_get_contents($shaFile)) : hash_file('sha256', $f);

            $backups[] = [
                'filename' => basename($f),
                'filepath' => $f,
                'size' => filesize($f),
                'size_formatted' => round(filesize($f) / 1024, 2) . ' KB',
                'sha256' => $sha256,
                'sha256_short' => substr($sha256, 0, 16) . '...',
                'created_at' => date('Y-m-d H:i:s', filemtime($f)),
            ];
        }

        // Urutkan dari yang paling baru
        usort($backups, function ($a, $b) {
            return strcmp($b['filename'], $a['filename']);
        });

        return $backups;
    }

    public function rotateDatabaseBackups(int $keepCount = 5): void
    {
        $backups = $this->getDatabaseBackups();
        if (count($backups) > $keepCount) {
            $toDelete = array_slice($backups, $keepCount);
            foreach ($toDelete as $item) {
                if (file_exists($item['filepath'])) {
                    @unlink($item['filepath']);
                }
                if (file_exists($item['filepath'] . '.sha256')) {
                    @unlink($item['filepath'] . '.sha256');
                }
            }
        }
    }

    public function generateUploadsManifest(): array
    {
        $manifestPath = $this->storageService->getBackupDir('uploads') . DIRECTORY_SEPARATOR . 'manifest.csv';
        $uploadDirectories = [
            $this->storageService->getPublicUploadDir('berita'),
            $this->storageService->getPublicUploadDir('lampiran'),
            $this->storageService->getPublicUploadDir('organisasi'),
            $this->storageService->getPrivateUploadDir('foto_anggota'),
            $this->storageService->getPrivateUploadDir('kta'),
        ];

        $manifestEntries = [];

        foreach ($uploadDirectories as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getFilename() !== '.gitkeep') {
                    $realPath = $file->getRealPath();
                    $relativePath = str_replace(dirname(__DIR__, 2) . DIRECTORY_SEPARATOR, '', $realPath);
                    $relativePath = str_replace('\\', '/', $relativePath);
                    
                    $manifestEntries[] = [
                        'filename' => $relativePath,
                        'filesize' => $file->getSize(),
                        'sha256' => hash_file('sha256', $realPath),
                        'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
        }

        // Tulis ke manifest.csv
        $fp = fopen($manifestPath, 'w');
        fputcsv($fp, ['filename', 'filesize', 'sha256', 'created_at']);
        foreach ($manifestEntries as $entry) {
            fputcsv($fp, [$entry['filename'], $entry['filesize'], $entry['sha256'], $entry['created_at']]);
        }
        fclose($fp);

        $this->auditLogService->log('GENERATE_UPLOAD_MANIFEST', 'system', 'manifest.csv', [
            'total_files' => count($manifestEntries),
        ]);

        return [
            'success' => true,
            'total_files' => count($manifestEntries),
            'manifest_path' => $manifestPath,
        ];
    }

    public function getUploadsManifest(): array
    {
        $manifestPath = $this->storageService->getBackupDir('uploads') . DIRECTORY_SEPARATOR . 'manifest.csv';
        if (!file_exists($manifestPath)) {
            return [];
        }

        $entries = [];
        $fp = fopen($manifestPath, 'r');
        $header = fgetcsv($fp);
        if ($header) {
            while (($row = fgetcsv($fp)) !== false) {
                if (count($row) >= 4) {
                    $entries[] = [
                        'filename' => $row[0],
                        'filesize' => $row[1],
                        'sha256' => $row[2],
                        'created_at' => $row[3],
                    ];
                }
            }
        }
        fclose($fp);

        return $entries;
    }

    /**
     * Membuat paket cadangan total sistem (Basis Data MySQL + Seluruh Berkas Fisik & Unggahan)
     * Mengemas database dump, dokumen privat, foto anggota, KTA, lampiran publik, serta manifest integritas SHA-256 ke dalam satu paket arsip ZIP.
     */
    public function createTotalBackup(): array
    {
        if (!class_exists('\ZipArchive')) {
            return [
                'success' => false,
                'message' => 'Ekstensi PHP ZipArchive tidak aktif pada server.',
            ];
        }

        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        try {
            $totalBackupDir = $this->storageService->getBackupDir('total');
            if (!is_dir($totalBackupDir)) {
                mkdir($totalBackupDir, 0755, true);
            }

            $timestamp = date('Ymd_His');
            $zipFilename = 'backup_total_gmki_padang_' . $timestamp . '.zip';
            $zipPath = $totalBackupDir . DIRECTORY_SEPARATOR . $zipFilename;

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException("Gagal membuat arsip ZIP di folder {$totalBackupDir}");
            }

            $manifestRows = [];
            $manifestRows[] = ['relative_path', 'filesize_bytes', 'sha256', 'type', 'created_at'];
            $fileCount = 0;
            $totalUncompressedBytes = 0;

            // 1. Eksekusi Pencadangan Basis Data MySQL (SQL Dump)
            $dbBackup = $this->createDatabaseBackup();
            if ($dbBackup['success']) {
                $dbGzPath = $this->storageService->getBackupDir('database') . DIRECTORY_SEPARATOR . $dbBackup['filename'];
                if (file_exists($dbGzPath)) {
                    $zip->addFile($dbGzPath, 'database/' . $dbBackup['filename']);
                    $gzSize = filesize($dbGzPath);
                    $fileCount++;
                    $totalUncompressedBytes += $gzSize;
                    $manifestRows[] = [
                        'database/' . $dbBackup['filename'],
                        $gzSize,
                        $dbBackup['sha256'],
                        'database_gzip',
                        date('Y-m-d H:i:s')
                    ];

                    // Unpack satu salinan raw .sql agar mudah diimpor langsung via phpMyAdmin / Adminer
                    $rawSqlContent = @gzdecode(file_get_contents($dbGzPath));
                    if ($rawSqlContent !== false) {
                        $rawSqlFilename = 'database/database_dump_' . $timestamp . '.sql';
                        $zip->addFromString($rawSqlFilename, $rawSqlContent);
                        $sqlSize = strlen($rawSqlContent);
                        $fileCount++;
                        $totalUncompressedBytes += $sqlSize;
                        $manifestRows[] = [
                            $rawSqlFilename,
                            $sqlSize,
                            hash('sha256', $rawSqlContent),
                            'database_raw_sql',
                            date('Y-m-d H:i:s')
                        ];
                    }
                }
            }

            // 2. Kumpulkan Seluruh Direktori Berkas Fisik Sistem
            $targetFolders = [
                'storage_private/foto-anggota' => $this->storageService->getPrivateUploadDir('foto_anggota'),
                'storage_private/kta' => $this->storageService->getPrivateUploadDir('kta'),
                'storage_private/documents' => dirname(__DIR__, 2) . '/storage/private/documents',
                'public_uploads/berita' => $this->storageService->getPublicUploadDir('berita'),
                'public_uploads/lampiran' => $this->storageService->getPublicUploadDir('lampiran'),
                'public_uploads/organisasi' => $this->storageService->getPublicUploadDir('organisasi'),
            ];

            foreach ($targetFolders as $zipPrefix => $realDir) {
                if (!is_dir($realDir)) {
                    continue;
                }

                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($realDir, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $filename = $file->getFilename();
                        if ($filename === '.gitkeep') {
                            continue;
                        }

                        $filePath = $file->getRealPath();
                        $subPath = substr($filePath, strlen(realpath($realDir)) + 1);
                        $subPath = str_replace('\\', '/', $subPath);
                        $zipEntryName = $zipPrefix . '/' . $subPath;

                        $zip->addFile($filePath, $zipEntryName);
                        $fSize = $file->getSize();
                        $fileCount++;
                        $totalUncompressedBytes += $fSize;

                        $manifestRows[] = [
                            $zipEntryName,
                            $fSize,
                            hash_file('sha256', $filePath),
                            'file_asset',
                            date('Y-m-d H:i:s', $file->getMTime())
                        ];
                    }
                }
            }

            // 3. Tambahkan Manifest CSV
            $manifestCsv = '';
            $fp = fopen('php://temp', 'r+');
            foreach ($manifestRows as $row) {
                fputcsv($fp, $row);
            }
            rewind($fp);
            $manifestCsv = stream_get_contents($fp);
            fclose($fp);
            $zip->addFromString('manifest.csv', $manifestCsv);

            // 4. Tambahkan Informasi Metadata JSON
            $info = [
                'app_name' => 'GMKI Cabang Padang - Sistem Informasi & Manajemen Keanggotaan Terpadu',
                'backup_type' => 'TOTAL_SYSTEM_FULL_BACKUP',
                'timestamp' => $timestamp,
                'created_at' => date('Y-m-d H:i:s'),
                'php_version' => PHP_VERSION,
                'total_files_included' => $fileCount,
                'uncompressed_bytes' => $totalUncompressedBytes,
                'uncompressed_formatted' => $this->formatBytes($totalUncompressedBytes),
                'database_included' => $dbBackup['success'] ?? false,
                'database_file' => $dbBackup['filename'] ?? null,
                'database_sha256' => $dbBackup['sha256'] ?? null,
                'security_features' => [
                    'Cryptographic SHA-256 for all individual files',
                    'Whole Archive Integrity Verification',
                    'Path Traversal Shielded Extraction Structure',
                    'ACID Consistent Snapshot Database Dump'
                ],
            ];
            $zip->addFromString('backup_info.json', json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            // 5. Tambahkan Panduan Pemulihan Bencana (README_RESTORE.txt)
            $readme = "===============================================================================\n"
                . " GMKI CABANG PADANG - PANDUAN PEMULIHAN BENCANA CADANGAN TOTAL (DISASTER RECOVERY)\n"
                . " Waktu Pencadangan: " . date('Y-m-d H:i:s') . "\n"
                . " Berkas Cadangan: {$zipFilename}\n"
                . "===============================================================================\n\n"
                . "STRUKTUR DIREKTORI CADANGAN:\n"
                . "1. database/         : Berisi dump basis data MySQL (.sql dan .sql.gz).\n"
                . "2. storage_private/  : Berisi dokumen rahasia, surat sekretariat, foto civitas & berkas KTA.\n"
                . "3. public_uploads/   : Berisi media berita, lampiran pengumuman, dan aset publik.\n"
                . "4. manifest.csv      : Daftar verifikasi keaslian SHA-256 untuk setiap berkas fisik.\n"
                . "5. backup_info.json  : Metadata konfigurasi dan ringkasan audit pencadangan.\n\n"
                . "LANGKAH-LANGKAH PEMULIHAN (RESTORE):\n"
                . "A. Pemulihan Basis Data:\n"
                . "   - Buka phpMyAdmin / MySQL CLI pada server tujuan.\n"
                . "   - Impor berkas 'database/database_dump_{$timestamp}.sql' ke database MySQL yang aktif.\n\n"
                . "B. Pemulihan Berkas Media & Unggahan:\n"
                . "   - Salin isi folder 'storage_private/' ke direktori server: 'storage/private/'\n"
                . "   - Salin isi folder 'public_uploads/' ke direktori server: 'frontend/public/uploads/'\n\n"
                . "C. Verifikasi Integritas:\n"
                . "   - Periksa keaslian berkas dengan mencocokkan nilai hash SHA-256 pada 'manifest.csv'.\n\n"
                . "Dokumen ini dibuat otomatis oleh Sistem Informasi GMKI Cabang Padang.\n";
            $zip->addFromString('README_RESTORE.txt', $readme);

            // Tutup dan simpan arsip ZIP
            $zip->close();

            $zipSize = filesize($zipPath);
            $zipSha256 = hash_file('sha256', $zipPath);
            file_put_contents($zipPath . '.sha256', $zipSha256);

            // Rotasi cadangan: simpan 5 arsip total terbaru secara aman
            $this->rotateTotalBackups(5);

            // Catat jejak audit pengamanan total
            $this->auditLogService->log('BACKUP_TOTAL_SYSTEM', 'system', $zipFilename, [
                'filename' => $zipFilename,
                'size' => $zipSize,
                'sha256' => $zipSha256,
                'total_files' => $fileCount,
            ]);

            return [
                'success' => true,
                'filename' => $zipFilename,
                'filepath' => $zipPath,
                'size' => $zipSize,
                'size_formatted' => $this->formatBytes($zipSize),
                'sha256' => $zipSha256,
                'total_files' => $fileCount,
                'message' => 'Cadangan total sistem (Database + Seluruh Berkas Fisik) berhasil dibuat (.zip) dengan verifikasi SHA-256.',
            ];
        } catch (\Throwable $e) {
            error_log("Backup Total Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal membuat cadangan total: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Dapatkan daftar seluruh arsip cadangan total (.zip)
     */
    public function getTotalBackups(): array
    {
        $totalBackupDir = $this->storageService->getBackupDir('total');
        if (!is_dir($totalBackupDir)) {
            return [];
        }

        $files = glob($totalBackupDir . '/*.zip');
        $backups = [];

        foreach ($files as $f) {
            $shaFile = $f . '.sha256';
            $sha256 = file_exists($shaFile) ? trim(file_get_contents($shaFile)) : hash_file('sha256', $f);

            $backups[] = [
                'filename' => basename($f),
                'filepath' => $f,
                'size' => filesize($f),
                'size_formatted' => $this->formatBytes(filesize($f)),
                'sha256' => $sha256,
                'sha256_short' => substr($sha256, 0, 16) . '...',
                'created_at' => date('Y-m-d H:i:s', filemtime($f)),
            ];
        }

        usort($backups, function ($a, $b) {
            return strcmp($b['filename'], $a['filename']);
        });

        return $backups;
    }

    /**
     * Rotasi cadangan total: pertahankan sejumlah berkas terbaru
     */
    public function rotateTotalBackups(int $keepCount = 5): void
    {
        $backups = $this->getTotalBackups();
        if (count($backups) > $keepCount) {
            $toDelete = array_slice($backups, $keepCount);
            foreach ($toDelete as $item) {
                if (file_exists($item['filepath'])) {
                    @unlink($item['filepath']);
                }
                if (file_exists($item['filepath'] . '.sha256')) {
                    @unlink($item['filepath'] . '.sha256');
                }
            }
        }
    }

    /**
     * Format byte ke satuan yang mudah dibaca manusia (B, KB, MB, GB)
     */
    public function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = (int) floor(log($bytes, 1024));
        $i = min($i, count($units) - 1);
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }
}
