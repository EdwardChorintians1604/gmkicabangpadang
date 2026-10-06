<?php
define('GMKI_SECURE_ACCESS', true);
$rootDir = dirname(__DIR__, 2);

$envFile = $rootDir . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#'))
            continue;
        if (str_contains($line, '=')) {
            [$k, $v] = explode('=', $line, 2);
            $_ENV[trim($k)] = trim(trim($v), '"\'');
        }
    }
}

require_once $rootDir . '/backend/core/Autoloader.php';
\App\Core\Autoloader::register();

try {
    $pdo = \App\Core\Database::getConnection();

    $pdo->exec("ALTER TABLE `civitas` MODIFY COLUMN `tingkat_kaderisasi` ENUM('Maperca', 'Anggota', 'KTB', 'KK', 'Alumni') NOT NULL DEFAULT 'Anggota'");
    $stmt = $pdo->exec("UPDATE `civitas` SET `tingkat_kaderisasi` = 'Anggota' WHERE `tingkat_kaderisasi` = 'KTB'");
    echo "Updated rows: " . $stmt . "\n";
    $pdo->exec("ALTER TABLE `civitas` MODIFY COLUMN `tingkat_kaderisasi` ENUM('Maperca', 'Anggota', 'KK', 'Alumni') NOT NULL DEFAULT 'Anggota'");

    echo "SUCCESS: Migration 011 executed. KTB removed, replaced with Anggota.\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
