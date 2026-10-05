<?php
/**
 * =====================================================================
 * TOOL: INSPEKSI PENGGUNA & STATUS DI BASIS DATA
 * Jalankan lewat terminal:
 *   E:\WebProgBPNama\php8\php.exe tools/check_users.php
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

try {
    $users = \App\Core\Database::query("SELECT id, username, email, nama_lengkap, role, status, password, created_at, last_login_at FROM `users` ORDER BY id ASC")->fetchAll();
} catch (\PDOException $e) {
    echo "Gagal koneksi database: " . $e->getMessage() . "\n";
    exit(1);
}

echo "=====================================================================\n";
echo "       DATA PENGGUNA TERDAFTAR DI DATABASE (Tabel: users)             \n";
echo "=====================================================================\n";
echo "Total Akun: " . count($users) . "\n\n";

$knownPasswords = [
    'Admin@GMKI2026!',
    'Pengawas@2026!',
    'Operator@2026!',
    'mactavish00',
    'dani_mnk1598',
    'rlynpnjit76',
    'password'
];

foreach ($users as $u) {
    echo "ID            : " . $u['id'] . "\n";
    echo "Username      : " . $u['username'] . "\n";
    echo "Nama Lengkap  : " . $u['nama_lengkap'] . "\n";
    echo "Email         : " . $u['email'] . "\n";
    echo "Role          : " . $u['role'] . "\n";
    echo "Status        : " . $u['status'] . "\n";
    echo "Last Login    : " . ($u['last_login_at'] ?? 'Belum pernah') . "\n";
    echo "Password Hash : " . substr($u['password'], 0, 16) . "... (" . strlen($u['password']) . " karakter)\n";
    
    // Cek kecocokan password
    $matched = false;
    foreach ($knownPasswords as $candidate) {
        if (\App\Core\Hash::check($candidate, $u['password'])) {
            echo "-> Password Cocok: '" . $candidate . "' [OK]\n";
            $matched = true;
            break;
        }
    }
    if (!$matched) {
        echo "-> Password Cocok: [Custom / Tidak Termasuk di Daftar Bawaan]\n";
    }
    echo "---------------------------------------------------------------------\n";
}
