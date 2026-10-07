<?php
/**
 * TOOL: UBAH ROLE / NAMA LENGKAP PENGGUNA
 * Cara pakai:
 *   php tools/set_role.php <username> <role> ["Nama Lengkap"]
 *   Role: admin, ketcab, sekcab, bencab, sekfung_medko, pengawas, operator
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

[$script, $username, $role, $nama] = array_pad($argv, 4, null);
if (!$username || !in_array($role, ['admin', 'ketcab', 'sekcab', 'bencab', 'sekfung_medko', 'pengawas', 'operator'], true)) {
    echo "Penggunaan: php tools/set_role.php <username> <admin|ketcab|sekcab|bencab|sekfung_medko|pengawas|operator> [\"Nama Lengkap\"]\n";
    exit(1);
}

if ($nama) {
    \App\Core\Database::query("UPDATE `users` SET `role` = ?, `nama_lengkap` = ? WHERE `username` = ?", [$role, $nama, $username]);
} else {
    \App\Core\Database::query("UPDATE `users` SET `role` = ? WHERE `username` = ?", [$role, $username]);
}

$u = \App\Core\Database::query("SELECT id, username, nama_lengkap, role FROM `users` WHERE `username` = ?", [$username])->fetch();
echo $u ? "OK  {$u['username']} | {$u['nama_lengkap']} | role={$u['role']}\n" : "User tidak ditemukan.\n";
