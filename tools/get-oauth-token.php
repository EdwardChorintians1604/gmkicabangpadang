<?php

declare(strict_types=1);

/**
 * Tool Generator OAuth2 Refresh Token Google Drive untuk Akun Gmail Pribadi (15 GB)
 * 
 * Jalankan lewat terminal:
 * E:\WebProgBPNama\php8\php.exe tools/get-oauth-token.php
 */

$rootDir = dirname(__DIR__);
$envFile = $rootDir . DIRECTORY_SEPARATOR . '.env';

// Baca .env jika ada
$existingClientId = '';
$existingClientSecret = '';
if (file_exists($envFile)) {
    $envLines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($envLines as $line) {
        $line = trim($line);
        if (str_starts_with($line, 'GDRIVE_CLIENT_ID=')) {
            $existingClientId = trim(trim(explode('=', $line, 2)[1] ?? ''), '"\'');
        }
        if (str_starts_with($line, 'GDRIVE_CLIENT_SECRET=')) {
            $existingClientSecret = trim(trim(explode('=', $line, 2)[1] ?? ''), '"\'');
        }
    }
}

echo "=====================================================================\n";
echo "    GENERATOR REFRESH TOKEN GOOGLE DRIVE - GMKI CABANG PADANG        \n";
echo "=====================================================================\n\n";

echo "Untuk akun Gmail pribadi (@gmail.com), Google mewajibkan penggunaan\n";
echo "OAuth2 Refresh Token agar berkas dapat menggunakan kuota 15 GB akun Anda.\n\n";

if (!empty($existingClientId)) {
    echo "Client ID saat ini: {$existingClientId}\n";
    echo "Tekan [Enter] untuk memakai ini, atau ketik baru: ";
    $input = trim((string)fgets(STDIN));
    $clientId = !empty($input) ? $input : $existingClientId;
} else {
    echo "Masukkan Client ID: ";
    $clientId = trim((string)fgets(STDIN));
}

if (empty($clientId)) {
    echo "❌ Client ID tidak boleh kosong.\n";
    exit(1);
}

if (!empty($existingClientSecret)) {
    echo "Client Secret saat ini sudah tersimpan.\n";
    echo "Tekan [Enter] untuk memakai ini, atau ketik baru: ";
    $input = trim((string)fgets(STDIN));
    $clientSecret = !empty($input) ? $input : $existingClientSecret;
} else {
    echo "Masukkan Client Secret: ";
    $clientSecret = trim((string)fgets(STDIN));
}

if (empty($clientSecret)) {
    echo "❌ Client Secret tidak boleh kosong.\n";
    exit(1);
}

$redirectUri = 'https://developers.google.com/oauthplayground';

$authUrl = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
    'client_id'             => $clientId,
    'redirect_uri'          => $redirectUri,
    'response_type'         => 'code',
    'scope'                 => 'https://www.googleapis.com/auth/drive',
    'access_type'           => 'offline',
    'prompt'                => 'consent',
]);

echo "\n---------------------------------------------------------------------\n";
echo "SILAKAN BUKA TAUTAN OTORISASI BERIKUT DI BROWSER ANDA:\n\n";
echo $authUrl . "\n\n";
echo "---------------------------------------------------------------------\n";
echo "Petunjuk:\n";
echo "1. Login dengan akun paripurnaerantus2004@gmail.com.\n";
echo "2. Jika muncul peringatan, klik 'Lanjutan (Advanced)' > 'Buka gmki_padang (tidak aman)'.\n";
echo "3. Berikan izin Google Drive, lalu Anda akan diarahkan ke halaman OAuth Playground.\n";
echo "4. Anda bisa menyalin KODE (dimulai dengan 4/...) dari kolom 'Authorization code'\n";
echo "   ATAU langsung salin seluruh URL dari bilah alamat browser Anda!\n\n";

$code = '';
while (empty($code)) {
    echo "Tempelkan Authorization Code (atau URL browser) di sini: ";
    $inputCode = trim((string)fgets(STDIN));
    if (empty($inputCode)) {
        echo "⚠️  Kode tidak boleh kosong. Silakan tempelkan kodenya lalu tekan Enter.\n";
        continue;
    }
    $code = $inputCode;
}

// Cerdas mengekstrak jika user menempelkan full URL dari browser
if (preg_match('#[?&]code=([^&]+)#', $code, $matches)) {
    $code = urldecode($matches[1]);
}

echo "\nMenukar Authorization Code dengan Refresh Token...\n";

$caBundle = $rootDir . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'keys' . DIRECTORY_SEPARATOR . 'cacert.pem';

$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'client_id'     => $clientId,
    'client_secret' => $clientSecret,
    'code'          => $code,
    'grant_type'    => 'authorization_code',
    'redirect_uri'  => $redirectUri,
]));
curl_setopt($ch, CURLOPT_TIMEOUT, 20);

if (file_exists($caBundle)) {
    curl_setopt($ch, CURLOPT_CAINFO, $caBundle);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
} else {
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
}

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode((string)$response, true);
if ($httpCode !== 200 || empty($data['refresh_token'])) {
    echo "❌ GAGAL: Google menolak kode otorisasi (HTTP {$httpCode}).\n";
    echo "Respon dari Google: " . $response . "\n\n";
    echo "Tips: Pastikan kode yang disalin masih baru dan belum pernah ditukar sebelumnya.\n";
    exit(1);
}

$refreshToken = $data['refresh_token'];

echo "✅ BERHASIL MENDAPATKAN REFRESH TOKEN!\n";
echo "Refresh Token: " . $refreshToken . "\n\n";

// Perbarui file .env secara otomatis
if (file_exists($envFile)) {
    $content = file_get_contents($envFile);

    $keys = [
        'GDRIVE_CLIENT_ID'     => $clientId,
        'GDRIVE_CLIENT_SECRET' => $clientSecret,
        'GDRIVE_REFRESH_TOKEN' => $refreshToken,
    ];

    foreach ($keys as $k => $v) {
        if (preg_match('/^' . $k . '=.*/m', $content)) {
            $content = preg_replace('/^' . $k . '=.*/m', $k . '="' . $v . '"', $content);
        } else {
            $content .= "\n" . $k . '="' . $v . '"';
        }
    }

    file_put_contents($envFile, $content);
    echo "🎉 File .env berhasil diperbarui secara otomatis dengan kredensial baru!\n\n";
    echo "Silakan jalankan pengujian: E:\\WebProgBPNama\\php8\\php.exe tools/test-gdrive.php\n";
}
