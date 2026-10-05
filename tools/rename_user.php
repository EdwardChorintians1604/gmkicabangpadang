<?php
/**
 * TOOL: GANTI USERNAME + PASSWORD PENGGUNA
 * Cara pakai:
 *   php tools/rename_user.php <username_lama> <username_baru> <password_baru>
 */

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

[$script, $old, $new, $pass] = array_pad($argv, 4, null);
if (!$old || !$new || !$pass) {
    echo "Penggunaan: php tools/rename_user.php <username_lama> <username_baru> <password_baru>\n";
    exit(1);
}

$user = \App\Core\Database::query("SELECT id, role FROM `users` WHERE `username` = ?", [$old])->fetch();
if (!$user) {
    echo "Error: username '{$old}' tidak ditemukan.\n";
    exit(1);
}

$hash = \App\Core\Hash::make($pass);
\App\Core\Database::query(
    "UPDATE `users` SET `username` = ?, `password` = ? WHERE `id` = ?",
    [$new, $hash, $user['id']]
);
\App\Core\Database::query("DELETE FROM login_attempts");

echo "OK  id={$user['id']} role={$user['role']}  {$old} -> {$new}\n";
echo "Hash: {$hash}\n";
