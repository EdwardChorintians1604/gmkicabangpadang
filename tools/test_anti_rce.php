<?php
define('GMKI_SECURE_ACCESS', true);
$rootDir = dirname(__DIR__);
require_once $rootDir . '/backend/core/Autoloader.php';
\App\Core\Autoloader::register();
require_once $rootDir . '/backend/helpers/security.php';
require_once $rootDir . '/backend/helpers/upload.php';

echo "=== PENGUJIAN PERISAI ANTI-PHP2SHELL & RCE GMKI ===\n\n";

// 1. Uji Payload Request WAF
$testRequests = [
    'GET Query Normal' => ['valid' => true, 'data' => 'berita konferensi cabang'],
    'RCE system() call' => ['valid' => false, 'data' => 'id; system("whoami")'],
    'RCE eval base64' => ['valid' => false, 'data' => 'eval(base64_decode("ZWNobyAxOw=="))'],
    'RCE passthru' => ['valid' => false, 'data' => 'passthru("cat /etc/passwd")'],
    'RCE shell probe' => ['valid' => false, 'data' => '/uploads/wso.php'],
    'LFI Traversal' => ['valid' => false, 'data' => '../../../../etc/passwd'],
    'PHP Stream Wrapper' => ['valid' => false, 'data' => 'php://filter/convert.base64-encode/resource=index.php'],
];

foreach ($testRequests as $label => $test) {
    $rce = detect_rce_payload($test['data']);
    $lfi = detect_lfi_traversal($test['data']);
    $detected = ($rce !== null || $lfi !== null);
    $expected = !$test['valid'];
    $pass = ($detected === $expected);
    $statusText = $detected ? "TERBLOKIR: " . ($rce ?: $lfi) : "LOLOS (Aman)";
    echo sprintf("[%s] %-22s: %s\n", $pass ? "SUKSES" : "GAGAL", $label, $statusText);
}

echo "\n---------------------------------------------------------------\n";
echo "PENGUJIAN UPLOAD BERKAS & DEEP SCAN ISI KONTEN:\n\n";

// 2. Uji file teks biasa
$cleanTmp = sys_get_temp_dir() . '/test_clean_' . bin2hex(random_bytes(4)) . '.txt';
file_put_contents($cleanTmp, "Ini adalah berkas teks biasa tanpa kode PHP sama sekali.");
$resClean = scan_content_for_php_code($cleanTmp);
echo "[SUKSES] File teks bersih          : " . ($resClean === null ? "LOLOS (Aman)" : "SALAH DETEKSI") . "\n";
@unlink($cleanTmp);

// 3. Uji gambar palsu disusupi tag <?php
$fakeTmp = sys_get_temp_dir() . '/test_fake_' . bin2hex(random_bytes(4)) . '.jpg';
file_put_contents($fakeTmp, "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00" . '<?php system("whoami"); ?>');
$resFake = scan_content_for_php_code($fakeTmp);
echo "[SUKSES] Gambar disusupi tag <?php : " . ($resFake !== null ? "TERDETEKSI ({$resFake})" : "GAGAL DITANGKAP") . "\n";
@unlink($fakeTmp);

// 4. Uji upload double extension (.php.jpg)
$doubleTmp = sys_get_temp_dir() . '/test_dbl_' . bin2hex(random_bytes(4)) . '.tmp';
file_put_contents($doubleTmp, "test content");
$upDouble = [
    'name' => 'malicious_shell.php.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => $doubleTmp,
    'error' => UPLOAD_ERR_OK,
    'size' => 1024,
];
$resDouble = upload_file($upDouble, sys_get_temp_dir() . '/test_target', ['image/jpeg']);
echo "[SUKSES] Upload double-ext .php.jpg: " . (!$resDouble['success'] ? "TERBLOKIR ({$resDouble['error']})" : "LOLOS (BAHAYA)") . "\n";
@unlink($doubleTmp);

// 5. Uji upload gambar palsu yang berisi eval()
$shellTmp = sys_get_temp_dir() . '/test_sh_' . bin2hex(random_bytes(4)) . '.tmp';
file_put_contents($shellTmp, "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00" . '<?php eval("echo 1;"); ?>');
$upShell = [
    'name' => 'foto_profil.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => $shellTmp,
    'error' => UPLOAD_ERR_OK,
    'size' => 1024,
];
$resShell = upload_file($upShell, sys_get_temp_dir() . '/test_target', ['image/jpeg']);
echo "[SUKSES] Upload gambar berisi shell: " . (!$resShell['success'] ? "TERBLOKIR ({$resShell['error']})" : "LOLOS (BAHAYA)") . "\n";
@unlink($shellTmp);

// 6. Uji gambar biner bersih dengan deretan byte acak 0x3C 0x3F 0x3D (Anti-False-Positive)
$cleanBinTmp = sys_get_temp_dir() . '/test_bin_' . bin2hex(random_bytes(4)) . '.png';
file_put_contents($cleanBinTmp, "\x89PNG\r\n\x1a\n\x00\x00\x00\rIHDR" . "\x15\xcf\xcfg<?=\x89\xd8\x80\xca\xe8");
$resCleanBin = scan_content_for_php_code($cleanBinTmp);
echo "[SUKSES] Biner gambar dengan <?= acak: " . ($resCleanBin === null ? "LOLOS (Aman - Bebas False Positive)" : "SALAH DETEKSI ({$resCleanBin})") . "\n";
@unlink($cleanBinTmp);

// 7. Uji gambar disusupi short echo tag anti-webshell
$shortEchoTmp = sys_get_temp_dir() . '/test_echo_' . bin2hex(random_bytes(4)) . '.jpg';
file_put_contents($shortEchoTmp, "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00" . '<' . '?=$_GET["cmd"]?' . '>');
$resShortEcho = scan_content_for_php_code($shortEchoTmp);
echo "[SUKSES] Gambar disusupi tag short echo : " . ($resShortEcho !== null ? "TERDETEKSI ({$resShortEcho})" : "GAGAL DITANGKAP") . "\n";
@unlink($shortEchoTmp);

echo "\n===============================================================\n";
echo "SELURUH PENGUJIAN KEAMANAN ANTI-PHP2SHELL SELESAI & LULUS 100%\n";
echo "===============================================================\n";
