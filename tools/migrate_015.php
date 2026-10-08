<?php
$rootDir = dirname(__DIR__);

$envFile = $rootDir . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim(trim($v), '"\'');
    }
}

require_once $rootDir . '/backend/core/Autoloader.php';
\App\Core\Autoloader::register();

$pdo = \App\Core\Database::getConnection();

// Check if columns already exist
$cols = $pdo->query('SHOW COLUMNS FROM office_documents')->fetchAll(PDO::FETCH_COLUMN);

if (in_array('letter_number', $cols, true)) {
    echo "Columns already exist in office_documents.\n";
    exit(0);
}

$sql = file_get_contents($rootDir . '/backend/database/migrations/015_upgrade_sekcab_letter_archives.sql');
$pdo->exec($sql);
echo "Migration 015 successfully executed!\n";
