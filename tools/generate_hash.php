<?php
/**
 * =====================================================================
 * TOOL: GENERATOR & VERIFIKATOR HASH PASSWORD (BCRYPT)
 * Jalankan lewat terminal:
 *   E:\WebProgBPNama\php8\php.exe tools/generate_hash.php [password_opsional]
 * =====================================================================
 */

$passwords = [];

// Jika argumen dilewatkan via terminal CLI (contoh: php tools/generate_hash.php rahasia123)
if (isset($argv[1]) && !empty($argv[1])) {
    $passwords[] = $argv[1];
} else {
    // Daftar bawaan yang sering digunakan
    $passwords = [
        'mactavish00',
        'dani_mnk1598',
        'rlynpnjit76',
        'Admin@GMKI2026!',
        'Pengawas@2026!',
        'Operator@2026!'
    ];
}

echo "=====================================================================\n";
echo "           GENERATOR HASH BCRYPT - GMKI CABANG PADANG                \n";
echo "=====================================================================\n\n";

foreach ($passwords as $pw) {
    $hash = password_hash($pw, PASSWORD_BCRYPT, ['cost' => 10]);
    $verified = password_verify($pw, $hash);

    echo "Kata Sandi (Plain) : " . $pw . "\n";
    echo "Hash Bcrypt (60 ch): " . $hash . "\n";
    echo "Status Verifikasi  : " . ($verified ? "VALID (OK)" : "GAGAL") . "\n";
    echo "Query SQL Contoh   :\n";
    echo "  UPDATE `users` SET `password` = '" . $hash . "' WHERE `username` = '...';\n";
    echo "---------------------------------------------------------------------\n";
}

echo "\nTips: Anda bisa mengetes password apa saja dengan perintah:\n";
echo "  E:\\WebProgBPNama\\php8\\php.exe tools/generate_hash.php PasswordBaruAnda\n\n";
