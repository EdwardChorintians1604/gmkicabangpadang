<?php
/**
 * =====================================================================
 * TOOL: UPDATE KATA SANDI PENGGUNA VIA TERMINAL
 * Cara pakai:
 *   E:\WebProgBPNama\php8\php.exe tools/update_user_password.php <username> <password_baru>
 * Contoh:
 *   E:\WebProgBPNama\php8\php.exe tools/update_user_password.php MacTavish0987 mactavish00
 * =====================================================================
 */

$rootDir = dirname(__DIR__);

// Muat .env
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

require_once $rootDir . '/backend/core/Autoloader.php';
\App\Core\Autoloader::register();

$username = $argv[1] ?? null;
$newPassword = $argv[2] ?? null;

if (!$username || !$newPassword) {
    echo "Penggunaan: php tools/update_user_password.php <username> <password_baru>\n";
    echo "Contoh: php tools/update_user_password.php MacTavish0987 mactavish00\n";
    exit(1);
}

try {
    $user = \App\Core\Database::query("SELECT id, username, email FROM `users` WHERE `username` = ?", [$username])->fetch();
    if (!$user) {
        echo "Error: Pengguna dengan username '{$username}' tidak ditemukan di basis data!\n";
        exit(1);
    }

    $hash = \App\Core\Hash::make($newPassword);

    \App\Core\Database::query("UPDATE `users` SET `password` = ? WHERE `id` = ?", [$hash, $user['id']]);

    echo "=====================================================================\n";
    echo "             KATA SANDI BERHASIL DIPERBARUI!                         \n";
    echo "=====================================================================\n";
    echo "ID Pengguna   : " . $user['id'] . "\n";
    echo "Username      : " . $user['username'] . "\n";
    echo "Password Baru : " . $newPassword . "\n";
    echo "Hash Baru     : " . $hash . "\n";
    echo "Status        : Tersimpan aman dengan enkripsi Bcrypt di database.\n";
    echo "=====================================================================\n";
} catch (\PDOException $e) {
    echo "Gagal: " . $e->getMessage() . "\n";
    exit(1);
}
