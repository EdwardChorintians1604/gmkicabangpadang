<?php

declare(strict_types=1);

/**
 * GMKI Cabang Padang - Database Setup & Migration Runner
 * Otomatis menyiapkan database, mengimpor master data, dan menjalankan migrasi skema.
 */

$rootDir = dirname(__DIR__);

// 1. Muat Berkas .env
$envFile = $rootDir . DIRECTORY_SEPARATOR . '.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim(trim($value), '"\'');
        $_ENV[$key] = $value;
        putenv("{$key}={$value}");
    }
}

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = (int)($_ENV['DB_PORT'] ?? 3306);
$dbName = $_ENV['DB_DATABASE'] ?? 'gmki_padang';
$user = $_ENV['DB_USERNAME'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';

echo "=======================================================================\n";
echo "       GMKI CABANG PADANG - INISIALISASI & MIGRASI DATABASE\n";
echo "=======================================================================\n";
echo "Host     : {$host}:{$port}\n";
echo "Database : {$dbName}\n";
echo "User     : {$user}\n\n";

try {
    // 2. Hubungkan ke MySQL Server (tanpa memilih database dulu)
    $dsnServer = "mysql:host={$host};port={$port};charset=utf8mb4";
    $pdo = new PDO($dsnServer, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 3. Buat database jika belum ada
    echo "[1/4] Memeriksa keberadaan database '{$dbName}'... ";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "OK.\n";

    // 4. Hubungkan ke database target
    $pdo->exec("USE `{$dbName}`;");

    // 5. Cek apakah tabel master sudah ada
    echo "[2/4] Memeriksa tabel master sistem... ";
    $existingTables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('users', $existingTables, true) || !in_array('civitas', $existingTables, true)) {
        echo "Mengimpor skema master (gmki_padang.sql)... ";
        $masterSqlFile = $rootDir . '/backend/database/gmki_padang.sql';
        if (file_exists($masterSqlFile)) {
            $masterSql = file_get_contents($masterSqlFile);
            $pdo->exec($masterSql);
            echo "OK.\n";
        } else {
            echo "Gagal: file {$masterSqlFile} tidak ditemukan.\n";
        }
    } else {
        echo "Tabel master sudah ada. OK.\n";
    }

    // 6. Jalankan seluruh migrasi yang belum terpasang
    echo "[3/4] Menjalankan pembaruan skema (migrations)...\n";
    $migrationsDir = $rootDir . '/backend/database/migrations';
    if (is_dir($migrationsDir)) {
        $files = glob($migrationsDir . '/*.sql');
        sort($files);
        foreach ($files as $file) {
            $filename = basename($file);
            echo "  - Menjalankan: {$filename}... ";
            try {
                $sql = file_get_contents($file);
                // Matikan sementara foreign key check saat eksekusi migrasi
                $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
                $pdo->exec($sql);
                $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
                echo "SUKSES\n";
            } catch (Throwable $e) {
                // Abaikan error 'Duplicate column' atau 'Table already exists'
                if (str_contains($e->getMessage(), 'Duplicate column') ||
                    str_contains($e->getMessage(), 'already exists') ||
                    str_contains($e->getMessage(), 'Duplicate key')) {
                    echo "SUDAH DITERAPKAN (Dilewati)\n";
                } else {
                    echo "INFO: " . $e->getMessage() . "\n";
                }
            }
        }
    }

    // 7. Verifikasi tabel penting
    echo "\n[4/4] Verifikasi kelengkapan tabel utama:\n";
    $requiredTables = [
        'users', 'civitas', 'berita', 'profil_organisasi', 'struktur_organisasi',
        'komisariat', 'website_access_logs', 'inventory_items', 'office_documents',
        'organization_strategies', 'coordination_messages', 'background_jobs'
    ];
    $tablesNow = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $allOk = true;
    foreach ($requiredTables as $t) {
        $status = in_array($t, $tablesNow, true) ? "TERSEDIA" : "TIDAK DITEMUKAN";
        echo "  - {$t}: {$status}\n";
        if (!in_array($t, $tablesNow, true)) {
            $allOk = false;
        }
    }

    echo "\n=======================================================================\n";
    if ($allOk) {
        echo "STATUS: Database '{$dbName}' 100% SIAP DIGUNAKAN!\n";
    } else {
        echo "STATUS: Beberapa tabel belum lengkap, periksa file migrasi.\n";
    }
    echo "=======================================================================\n";

} catch (PDOException $e) {
    echo "\n[ERROR DATABASE]: " . $e->getMessage() . "\n";
    echo "Pastikan MySQL di Laragon sudah dalam keadaan START (aktif).\n";
    exit(1);
} catch (Throwable $e) {
    echo "\n[ERROR SISTEM]: " . $e->getMessage() . "\n";
    exit(1);
}
