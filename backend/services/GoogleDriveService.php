<?php

declare(strict_types=1);

namespace App\Services;

/**
 * GoogleDriveService
 * 
 * Layanan integrasi Google Drive API v3 (REST API murni via cURL & OpenSSL).
 * Dibangun tanpa library pihak ketiga (Zero Composer bloat) untuk menghemat
 * kuota Inode dan memori, sangat optimal dan aman untuk Shared Hosting / InfinityFree.
 */
class GoogleDriveService
{
    private static ?string $cachedAccessToken = null;
    private static int $tokenExpiresAt = 0;

    /**
     * Cek apakah Google Drive API sudah diaktifkan dan dikonfigurasi di lingkungan (.env).
     */
    public static function isConfigured(): bool
    {
        $enabled = filter_var($_ENV['GDRIVE_ENABLED'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if (!$enabled) {
            return false;
        }

        $folderId = self::cleanFolderId();
        if (empty($folderId)) {
            return false;
        }

        // Cek apakah ada OAuth2 Refresh Token (Pribadi 15 GB) ATAU Service Account JSON
        $hasOAuth = !empty($_ENV['GDRIVE_CLIENT_ID']) && !empty($_ENV['GDRIVE_REFRESH_TOKEN']);
        $jsonKeyPath = self::getKeyFilePath();
        $hasServiceAccount = !empty($jsonKeyPath) && file_exists($jsonKeyPath);

        return $hasOAuth || $hasServiceAccount;
    }

    /**
     * Bersihkan dan ekstrak ID Folder Google Drive murni (bahkan jika pengguna menempelkan full URL).
     */
    public static function cleanFolderId(?string $folderId = null): string
    {
        $id = trim((string)($folderId ?: ($_ENV['GDRIVE_FOLDER_ID'] ?? '')));
        if (empty($id)) {
            return '';
        }

        // Ekstrak dari URL format https://drive.google.com/drive/folders/{ID}?hl=...
        if (preg_match('#/folders/([a-zA-Z0-9_-]+)#', $id, $matches)) {
            return $matches[1];
        }

        if (str_contains($id, '?')) {
            $id = explode('?', $id)[0];
        }

        return trim($id, '/ ');
    }

    /**
     * Dapatkan path file kunci kredensial JSON Service Account Google.
     */
    public static function getKeyFilePath(): string
    {
        $path = trim((string)($_ENV['GDRIVE_SERVICE_ACCOUNT_JSON'] ?? ''));
        if (!empty($path)) {
            if (file_exists($path)) {
                return $path;
            }

            // Relatif terhadap root proyek
            $rootPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
            if (file_exists($rootPath)) {
                return $rootPath;
            }
        }

        // Auto-discovery: cari file .json apa pun di folder storage/private/keys/
        $keysDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'keys';
        if (is_dir($keysDir)) {
            $files = glob($keysDir . DIRECTORY_SEPARATOR . '*.json');
            if (!empty($files)) {
                foreach ($files as $file) {
                    $content = @file_get_contents($file);
                    if ($content && str_contains($content, 'service_account')) {
                        return $file;
                    }
                }
            }
        }

        return '';
    }

    /**
     * Dapatkan Access Token OAuth2.
     * Prioritas 1: OAuth2 Refresh Token (Menggunakan kuota 15 GB akun Gmail pengguna)
     * Prioritas 2: Service Account JWT (Google Workspace / Shared Drive)
     */
    public static function getAccessToken(): ?string
    {
        if (self::$cachedAccessToken && time() < (self::$tokenExpiresAt - 300)) {
            return self::$cachedAccessToken;
        }

        $clientId = trim((string)($_ENV['GDRIVE_CLIENT_ID'] ?? ''));
        $clientSecret = trim((string)($_ENV['GDRIVE_CLIENT_SECRET'] ?? ''));
        $refreshToken = trim((string)($_ENV['GDRIVE_REFRESH_TOKEN'] ?? ''));

        if (!empty($clientId) && !empty($refreshToken)) {
            return self::getAccessTokenFromRefreshToken($clientId, $clientSecret, $refreshToken);
        }

        return self::getAccessTokenFromServiceAccount();
    }

    /**
     * Tukar Refresh Token dengan Access Token (Akun Gmail Pribadi - Kuota 15 GB).
     */
    public static function getAccessTokenFromRefreshToken(string $clientId, string $clientSecret, string $refreshToken): ?string
    {
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type'    => 'refresh_token',
        ]));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        self::applyCurlSecurity($ch);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            error_log('GoogleDriveService Error: Gagal refresh token (' . $httpCode . '): ' . $response);
            return null;
        }

        $tokenData = json_decode($response, true);
        if (empty($tokenData['access_token'])) {
            return null;
        }

        self::$cachedAccessToken = $tokenData['access_token'];
        self::$tokenExpiresAt = time() + (int)($tokenData['expires_in'] ?? 3600);

        return self::$cachedAccessToken;
    }

    /**
     * Dapatkan Access Token dari Service Account via JWT Assertion.
     */
    public static function getAccessTokenFromServiceAccount(): ?string
    {
        $keyPath = self::getKeyFilePath();
        if (!file_exists($keyPath)) {
            error_log('GoogleDriveService Error: Kunci Service Account tidak ditemukan di ' . $keyPath);
            return null;
        }

        $keyContent = file_get_contents($keyPath);
        $keyData = json_decode($keyContent, true);
        if (!$keyData || empty($keyData['client_email']) || empty($keyData['private_key'])) {
            error_log('GoogleDriveService Error: Struktur JSON Service Account tidak valid.');
            return null;
        }

        $clientEmail = $keyData['client_email'];
        $privateKey = $keyData['private_key'];

        $now = time();
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $claim = [
            'iss'   => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/drive',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'exp'   => $now + 3600,
            'iat'   => $now,
        ];

        $encodedHeader = self::base64UrlEncode(json_encode($header));
        $encodedClaim = self::base64UrlEncode(json_encode($claim));
        $signatureInput = $encodedHeader . '.' . $encodedClaim;

        $binarySignature = '';
        if (!openssl_sign($signatureInput, $binarySignature, $privateKey, OPENSSL_ALGO_SHA256)) {
            error_log('GoogleDriveService Error: Gagal menandatangani JWT assertion.');
            return null;
        }

        $jwt = $signatureInput . '.' . self::base64UrlEncode($binarySignature);

        // Tukar JWT dengan Access Token
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        self::applyCurlSecurity($ch);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        $curlErrNo = curl_errno($ch);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            error_log('GoogleDriveService Error: Gagal menukar token (HTTP ' . $httpCode . ', cURL #' . $curlErrNo . ': ' . $curlErr . '): ' . $response);
            return null;
        }

        $tokenData = json_decode($response, true);
        if (empty($tokenData['access_token'])) {
            error_log('GoogleDriveService Error: Access token kosong dalam respon.');
            return null;
        }

        self::$cachedAccessToken = $tokenData['access_token'];
        self::$tokenExpiresAt = $now + (int)($tokenData['expires_in'] ?? 3600);

        return self::$cachedAccessToken;
    }

    /**
     * Unggah berkas ke Google Drive (Multipart Upload).
     *
     * @param string $localFilePath Path fisik berkas di komputer/server lokal
     * @param string $fileName Nama berkas yang ingin ditampilkan di Google Drive
     * @param string $mimeType Tipe MIME berkas
     * @param string|null $targetFolderId ID folder Google Drive (default dari .env)
     * @return array|null Array informasi berkas jika berhasil, null jika gagal
     */
    public static function uploadFile(
        string $localFilePath,
        string $fileName,
        string $mimeType = 'application/octet-stream',
        ?string $targetFolderId = null
    ): ?array {
        if (!file_exists($localFilePath)) {
            return null;
        }

        $token = self::getAccessToken();
        if (!$token) {
            return null;
        }

        $folderId = self::cleanFolderId($targetFolderId);
        $metadata = [
            'name' => $fileName,
        ];
        if (!empty($folderId)) {
            $metadata['parents'] = [$folderId];
        }

        $boundary = '-------GMKI_GDRIVE_BOUNDARY_' . bin2hex(random_bytes(8));
        $delimiter = "\r\n--" . $boundary . "\r\n";
        $closeDelimiter = "\r\n--" . $boundary . "--";

        $fileContent = file_get_contents($localFilePath);
        $postData = $delimiter
            . "Content-Type: application/json; charset=UTF-8\r\n\r\n"
            . json_encode($metadata)
            . $delimiter
            . "Content-Type: " . $mimeType . "\r\n\r\n"
            . $fileContent
            . $closeDelimiter;

        $url = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&supportsAllDrives=true&fields=id,name,mimeType,size,webViewLink,webContentLink';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: multipart/related; boundary=' . $boundary,
            'Content-Length: ' . strlen($postData),
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        self::applyCurlSecurity($ch);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 && $httpCode !== 201) {
            error_log('GoogleDriveService Upload Error (' . $httpCode . '): ' . $response);
            return null;
        }

        $data = json_decode($response, true);
        if (!$data || empty($data['id'])) {
            return null;
        }

        // Set permission publik "Anyone with link can read" agar civitas & BPC dapat mengunduh langsung tanpa login Google
        self::makeFileReadable($data['id']);

        return [
            'id'              => $data['id'],
            'name'            => $data['name'] ?? $fileName,
            'mime_type'       => $data['mimeType'] ?? $mimeType,
            'size'            => (int)($data['size'] ?? strlen($fileContent)),
            'web_view_link'   => $data['webViewLink'] ?? ('https://drive.google.com/file/d/' . $data['id'] . '/view'),
            'web_content_link'=> $data['webContentLink'] ?? ('https://drive.google.com/uc?export=download&id=' . $data['id']),
        ];
    }

    /**
     * Bagikan berkas agar dapat dibaca/diunduh oleh siapa saja yang memiliki tautan.
     */
    public static function makeFileReadable(string $fileId): bool
    {
        $token = self::getAccessToken();
        if (!$token) {
            return false;
        }

        $url = 'https://www.googleapis.com/drive/v3/files/' . urlencode($fileId) . '/permissions';
        $body = json_encode([
            'role' => 'reader',
            'type' => 'anyone',
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json; charset=UTF-8',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        self::applyCurlSecurity($ch);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpCode === 200 || $httpCode === 201);
    }

    /**
     * Dapatkan URL unduh langsung dari Google Drive.
     */
    public static function getDownloadUrl(string $fileId): string
    {
        return 'https://drive.google.com/uc?export=download&id=' . urlencode($fileId);
    }

    /**
     * Dapatkan URL pratinjau (preview) Google Drive.
     */
    public static function getPreviewUrl(string $fileId): string
    {
        return 'https://drive.google.com/file/d/' . urlencode($fileId) . '/view?usp=sharing';
    }

    /**
     * Unduh konten berkas dari Google Drive ke lokal.
     */
    public static function downloadContent(string $fileId): ?string
    {
        $token = self::getAccessToken();
        if (!$token) {
            return null;
        }

        $url = 'https://www.googleapis.com/drive/v3/files/' . urlencode($fileId) . '?alt=media';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        self::applyCurlSecurity($ch);

        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || $content === false) {
            return null;
        }

        return $content;
    }

    /**
     * Hapus berkas dari Google Drive.
     */
    public static function deleteFile(string $fileId): bool
    {
        $token = self::getAccessToken();
        if (!$token) {
            return false;
        }

        $url = 'https://www.googleapis.com/drive/v3/files/' . urlencode($fileId);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        self::applyCurlSecurity($ch);

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpCode === 204 || $httpCode === 200);
    }

    /**
     * Konfigurasi SSL CA Cert yang andal (Cross-Platform Windows & Linux / Hosting).
     */
    private static function applyCurlSecurity($ch): void
    {
        $caBundle = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'keys' . DIRECTORY_SEPARATOR . 'cacert.pem';
        if (file_exists($caBundle)) {
            curl_setopt($ch, CURLOPT_CAINFO, $caBundle);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        } else {
            // Pada server Linux / InfinityFree, gunakan CA store bawaan OS
            $verifyPeer = filter_var($_ENV['GDRIVE_SSL_VERIFY'] ?? true, FILTER_VALIDATE_BOOLEAN);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifyPeer);
        }
    }

    /**
     * Helper URL-safe Base64 Encode.
     */
    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
