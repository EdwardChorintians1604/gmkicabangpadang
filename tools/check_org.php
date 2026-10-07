<?php
$rootDir = dirname(__DIR__);
$envFile = $rootDir . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim(trim($value), '"\'');
        }
    }
}
define('GMKI_SECURE_ACCESS', true);
require_once $rootDir . '/backend/core/Autoloader.php';
\App\Core\Autoloader::register();
require_once $rootDir . '/backend/helpers/functions.php';

$rows = \App\Core\Database::fetchAll('SELECT * FROM struktur_organisasi');
foreach ($rows as $r) {
    echo "ID " . $r['id'] . ": " . json_encode($r, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . "\n";
}
