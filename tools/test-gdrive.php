<?php

declare(strict_types=1);

$rootDir = dirname(__DIR__);
$envFile = $rootDir . DIRECTORY_SEPARATOR . '.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = trim(trim($value), '"\'');
            putenv($key . '=' . $_ENV[$key]);
        }
    }
}

require_once $rootDir . DIRECTORY_SEPARATOR . 'backend' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Autoloader.php';
\App\Core\Autoloader::register();

echo "=== DIAGNOSTIK KONEKSI GOOGLE DRIVE API - GMKI CABANG PADANG ===\n\n";

if (!\App\Services\GoogleDriveService::isConfigured()) {
    echo "⚠️  STATUS: Google Drive API BELUM DIKONFIGURASI di .env!\n\n";
    echo "Periksa hal berikut:\n";
    echo "1. GDRIVE_ENABLED harus bernilai true di .env\n";
    echo "2. GDRIVE_FOLDER_ID harus diisi ID folder Google Drive tujuan.\n";
    echo "3. Salah satu metode otentikasi harus terisi:\n";
    echo "   - Metode A (Gmail Pribadi 15 GB): GDRIVE_CLIENT_ID, GDRIVE_CLIENT_SECRET, GDRIVE_REFRESH_TOKEN\n";
    echo "   - Metode B (Google Workspace): File JSON Service Account di storage/private/keys/\n\n";
    exit(1);
}

$hasOAuth = !empty($_ENV['GDRIVE_CLIENT_ID']) && !empty($_ENV['GDRIVE_REFRESH_TOKEN']);
$folderId = \App\Services\GoogleDriveService::cleanFolderId();

echo "1. Memeriksa Folder Google Drive Tujuan...\n";
echo "   -> Folder ID : {$folderId}\n";

echo "\n2. Memeriksa Metode Otentikasi...\n";
if ($hasOAuth) {
    echo "   -> Menggunakan : OAuth2 Refresh Token (Akun Gmail Pribadi - Kuota 15 GB)\n";
} else {
    $keyPath = \App\Services\GoogleDriveService::getKeyFilePath();
    echo "   -> Menggunakan : Service Account JSON ({$keyPath})\n";
}

echo "\n3. Menguji Pembuatan Access Token...\n";
$token = \App\Services\GoogleDriveService::getAccessToken();
if (!$token) {
    echo "   ❌ GAGAL: Tidak dapat memperoleh Access Token dari Google.\n";
    echo "   Periksa kembali kredensial Anda.\n";
    exit(1);
}
echo "   ✅ BERHASIL: Access Token diperoleh!\n";

echo "\n4. Menguji Unggah Berkas Uji Coba ke Google Drive...\n";
$tempFile = tempnam(sys_get_temp_dir(), 'gmki_gdrive_test_');
file_put_contents($tempFile, 'Tes koneksi sistem GMKI Cabang Padang ' . date('Y-m-d H:i:s'));

$uploadResult = \App\Services\GoogleDriveService::uploadFile($tempFile, 'test-koneksi-gmki.txt', 'text/plain');
@unlink($tempFile);

if (!$uploadResult || empty($uploadResult['id'])) {
    echo "   ❌ GAGAL: Tidak dapat mengunggah berkas ke Google Drive.\n\n";
    if (!$hasOAuth) {
        echo "💡 CATATAN PENTING GOOGLE UNTUK AKUN @gmail.com:\n";
        echo "Google membatasi Service Account agar tidak dapat mengunggah berkas ke akun Gmail pribadi (@gmail.com) karena Service Account dialokasikan kuota 0 byte (hanya untuk Google Workspace Shared Drive).\n\n";
        echo "Solusi agar langsung menggunakan kuota 15 GB akun Gmail Anda:\n";
        echo "Gunakan OAuth2 Client ID + Refresh Token di .env:\n";
        echo "GDRIVE_CLIENT_ID=\"...\"\n";
        echo "GDRIVE_CLIENT_SECRET=\"...\"\n";
        echo "GDRIVE_REFRESH_TOKEN=\"...\"\n\n";
        echo "Panduan lengkap dapat dilihat di panduan yang kami berikan.\n";
    }
    exit(1);
}

echo "   ✅ BERHASIL: Berkas uji coba berhasil diunggah!\n";
echo "      - File ID      : {$uploadResult['id']}\n";
echo "      - Tautan Lihat : {$uploadResult['web_view_link']}\n";

echo "\n5. Membersihkan Berkas Uji Coba dari Google Drive...\n";
$deleteOk = \App\Services\GoogleDriveService::deleteFile($uploadResult['id']);
if ($deleteOk) {
    echo "   ✅ BERHASIL: Berkas uji coba berhasil dibersihkan.\n";
} else {
    echo "   ⚠️  Pembersihan berkas uji coba dilewati.\n";
}

echo "\n🎉 SEMPURNA! Sistem integrasi Google Drive API 100% siap digunakan untuk hosting gratis!\n";
