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

            // Ambil daftar semua tabel
            $tables = [];
            $stmt = $pdo->query("SHOW FULL TABLES WHERE Table_Type = 'BASE TABLE'");
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            $sqlDump = "-- ==========================================================\n";
            $sqlDump .= "-- GMKI CABANG PADANG - AUTOMATED DATABASE BACKUP\n";
            $sqlDump .= "-- Database: {$dbName}\n";
            $sqlDump .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n";
            $sqlDump .= "-- ==========================================================\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\nSET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\nSTART TRANSACTION;\n\n";

            foreach ($tables as $table) {
                // Struktur tabel
                $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
                $createRow = $createStmt->fetch(PDO::FETCH_NUM);
                $sqlDump .= "\n-- Struktur untuk tabel `{$table}`\n";
                $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sqlDump .= $createRow[1] . ";\n\n";

                // Isi data tabel
                $dataStmt = $pdo->query("SELECT * FROM `{$table}`");
                $rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($rows)) {
                    $sqlDump .= "-- Data untuk tabel `{$table}`\n";
                    $columns = array_keys($rows[0]);
                    $colNames = implode('`, `', $columns);

                    foreach ($rows as $r) {
                        $values = [];
                        foreach ($r as $val) {
                            if ($val === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = $pdo->quote($val);
                            }
                        }
                        $sqlDump .= "INSERT INTO `{$table}` (`{$colNames}`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sqlDump .= "\n";
                }
            }

            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\nCOMMIT;\n";

            // Kompresi dengan GZIP
            $gzData = gzencode($sqlDump, 9);
            $backupDir = $this->storageService->getBackupDir('database');

            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $filename = 'backup_gmki_padang_' . date('Ymd_His') . '.sql.gz';
            $filePath = $backupDir . DIRECTORY_SEPARATOR . $filename;

            file_put_contents($filePath, $gzData);

            // Rotasi cadangan: simpan hanya 3 berkas terbaru
            $this->rotateDatabaseBackups(3);

            $this->auditLogService->log('BACKUP_DATABASE', 'system', $filename, [
                'filename' => $filename,
                'size' => filesize($filePath),
            ]);

            return [
                'success' => true,
                'filename' => $filename,
                'size' => filesize($filePath),
                'message' => 'Cadangan database berhasil dibuat (.sql.gz).',
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
            $backups[] = [
                'filename' => basename($f),
                'filepath' => $f,
                'size' => filesize($f),
                'size_formatted' => round(filesize($f) / 1024, 2) . ' KB',
                'created_at' => date('Y-m-d H:i:s', filemtime($f)),
            ];
        }

        // Urutkan dari yang paling baru
        usort($backups, function ($a, $b) {
            return strcmp($b['filename'], $a['filename']);
        });

        return $backups;
    }

    public function rotateDatabaseBackups(int $keepCount = 3): void
    {
        $backups = $this->getDatabaseBackups();
        if (count($backups) > $keepCount) {
            $toDelete = array_slice($backups, $keepCount);
            foreach ($toDelete as $item) {
                if (file_exists($item['filepath'])) {
                    @unlink($item['filepath']);
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
}
