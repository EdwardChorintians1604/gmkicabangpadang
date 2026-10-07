<?php
/**
 * =====================================================================
 * TOOL: GENERATOR & VERIFIKATOR HASH PASSWORD (BCRYPT)
 * Jalankan lewat terminal:
 *   E:\WebProgBPNama\php8\php.exe tools/generate_hash.php [password_opsional]
 * =====================================================================
 */

$passwords = [];

if (isset($argv[1]) && !empty($argv[1])) {
    $passwords[] = $argv[1];
} else {
    fwrite(STDERR, "Gunakan: php tools/generate_hash.php <password>\n");
    fwrite(STDERR, "Catatan: argumen password dapat terlihat pada riwayat perintah dan daftar proses.\n");
    exit(1);
}

echo "=====================================================================\n";
echo "           GENERATOR HASH BCRYPT - GMKI CABANG PADANG                \n";
echo "=====================================================================\n\n";

foreach ($passwords as $pw) {
    $hash = password_hash($pw, PASSWORD_BCRYPT, ['cost' => 10]);
    $verified = password_verify($pw, $hash);

    echo "Hash Bcrypt (60 ch): " . $hash . "\n";
    echo "Status Verifikasi  : " . ($verified ? "VALID (OK)" : "GAGAL") . "\n";
    echo "---------------------------------------------------------------------\n";
}

unset($passwords, $pw);
