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

$docs = \App\Core\Database::fetchAll("SELECT id, stored_name FROM office_documents WHERE document_type = 'archive'");
$dir = $rootDir . '/storage/private/documents';
$bakDir = $rootDir . '/storage/private/documents/backups';
if (!is_dir($bakDir)) {
    mkdir($bakDir, 0750, true);
}

foreach ($docs as $d) {
    $src = $dir . '/' . $d['stored_name'];
    if (is_file($src)) {
        $bak = 'bak_' . $d['stored_name'];
        copy($src, $bakDir . '/' . $bak);
        $hash = hash_file('sha256', $src);
        \App\Core\Database::execute(
            "UPDATE office_documents SET backup_stored_name = ?, file_hash = ?, is_duplicated = 1, duplicate_status = 'Duplikasi Terverifikasi (Identik)' WHERE id = ?",
            [$bak, $hash, $d['id']]
        );
        echo "Backed up document ID {$d['id']} with SHA-256: " . substr($hash, 0, 16) . "...\n";
    }
}
echo "Sync complete!\n";
