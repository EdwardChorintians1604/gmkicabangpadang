<?php

if (!function_exists('upload_file')) {
    function upload_file(array $file, string $targetDirectory, array $allowedMimes = [], int $maxSizeBytes = 10485760): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'Parameter berkas tidak valid.'];
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return ['success' => false, 'error' => 'Tidak ada berkas yang diunggah.'];
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return ['success' => false, 'error' => 'Ukuran berkas melebihi batas yang diizinkan server.'];
            default:
                return ['success' => false, 'error' => 'Terjadi kesalahan saat mengunggah berkas (Kode #' . $file['error'] . ').'];
        }

        if ($file['size'] > $maxSizeBytes) {
            $maxMb = round($maxSizeBytes / 1024 / 1024, 1);
            return ['success' => false, 'error' => "Ukuran berkas melebihi batas maksimum {$maxMb} MB."];
        }

        // 1. Periksa nama asli berkas dari upaya manipulasi double extension (misal: shell.php.jpg)
        $originalFilename = strtolower($file['name'] ?? '');
        if (preg_match('/\.(php[0-9]?|phtml|phar|inc|cgi|pl|py|sh|bash|exe|bat|cmd|dll|bin|shtml|asp|aspx|jsp|jspx|html|htm)(\.|$)/i', $originalFilename) || str_contains($originalFilename, "\0") || str_contains($originalFilename, '%00')) {
            @unlink($file['tmp_name']);
            if (function_exists('record_threat')) {
                record_threat('PHP2Shell / Malicious Filename Tampering', $originalFilename, 'critical');
            }
            return ['success' => false, 'error' => 'Nama berkas mengandung ekstensi script berbahaya dan telah diblokir demi keamanan server.'];
        }

        // 2. Pemindaian Fisik Isi Berkas Mentah (Deep Content Scan Anti-WebShell)
        if (function_exists('scan_content_for_php_code')) {
            $detectedShell = scan_content_for_php_code($file['tmp_name']);
            if ($detectedShell !== null) {
                @unlink($file['tmp_name']);
                if (function_exists('record_threat')) {
                    record_threat('PHP2Shell / Web Shell Signature in Upload', $detectedShell . ' in ' . $originalFilename, 'critical');
                }
                return ['success' => false, 'error' => "Berkas ditolak oleh Perisai Siber: {$detectedShell}."];
            }
        }

        // 3. Validasi MIME type sebenarnya dengan finfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        // Normalisasi JPEG & PNG MIME types
        $normalizedMime = match ($mimeType) {
            'image/pjpeg', 'image/jpg' => 'image/jpeg',
            'image/x-png' => 'image/png',
            default => $mimeType,
        };

        if (!empty($allowedMimes)) {
            $normalizedAllowed = array_map(function ($m) {
                return match ($m) {
                    'image/pjpeg', 'image/jpg' => 'image/jpeg',
                    'image/x-png' => 'image/png',
                    default => $m,
                };
            }, $allowedMimes);

            if (!in_array($normalizedMime, $normalizedAllowed, true) && !in_array($mimeType, $allowedMimes, true)) {
                @unlink($file['tmp_name']);
                return ['success' => false, 'error' => "Format berkas tidak diizinkan ({$mimeType}). Hanya menerima gambar JPG, PNG, atau WebP."];
            }
        }

        // 4. Jika format adalah gambar, pastikan struktur biner gambar benar-benar valid (bukan file palsu ber-header MIME fiktif)
        $isImageMime = str_starts_with($normalizedMime, 'image/');
        if ($isImageMime) {
            $imageInfo = @getimagesize($file['tmp_name']);
            if ($imageInfo === false || empty($imageInfo[0]) || empty($imageInfo[1])) {
                @unlink($file['tmp_name']);
                if (function_exists('record_threat')) {
                    record_threat('Corrupted / Faked Image Payload Upload', $originalFilename, 'high');
                }
                return ['success' => false, 'error' => 'Berkas gambar rusak atau tidak valid secara visual.'];
            }
        }

        // 5. Tentukan ekstensi aman HANYA dari MIME type terverifikasi
        $mimeExtensions = [
            'image/jpeg' => 'jpg',
            'image/pjpeg' => 'jpg',
            'image/png' => 'png',
            'image/x-png' => 'png',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf',
            'text/csv' => 'csv',
            'text/plain' => 'txt',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        ];

        $extension = $mimeExtensions[$normalizedMime] ?? $mimeExtensions[$mimeType] ?? null;
        if ($extension === null) {
            @unlink($file['tmp_name']);
            return ['success' => false, 'error' => 'Tipe berkas tidak dikenali dalam daftar aman server.'];
        }

        // Whitelist ketat ekstensi yang diizinkan sistem
        $strictAllowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'csv', 'xlsx', 'txt'];
        if (!in_array($extension, $strictAllowedExtensions, true)) {
            @unlink($file['tmp_name']);
            return ['success' => false, 'error' => 'Format ekstensi ditolak oleh kebijakan keamanan.'];
        }

        if (!is_dir($targetDirectory)) {
            @mkdir($targetDirectory, 0755, true);
        }

        // Acak nama berkas secara kriptografis (tidak menggunakan nama asli dari pengunjung)
        $safeBase = bin2hex(random_bytes(16));
        $newFilename = sprintf('%s_%s.%s', date('Ymd_His'), $safeBase, $extension);
        $targetPath = rtrim($targetDirectory, '/\\') . DIRECTORY_SEPARATOR . $newFilename;

        $moved = @move_uploaded_file($file['tmp_name'], $targetPath);
        if (!$moved) {
            $moved = @copy($file['tmp_name'], $targetPath);
            if ($moved) {
                @unlink($file['tmp_name']);
            }
        }

        if (!$moved) {
            return ['success' => false, 'error' => 'Gagal memindahkan berkas ke folder tujuan.'];
        }

        // Optimasi & Kompresi Otomatis jika berkas adalah gambar (Foto Berita, Pengurus, Civitas, dll)
        $finalSize = $file['size'];
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true) || str_starts_with($mimeType, 'image/');
        if ($isImage && file_exists(dirname(__DIR__) . '/photo_compression/ImageCompressor.php')) {
            try {
                require_once dirname(__DIR__) . '/photo_compression/ImageCompressor.php';
                $compressor = new \App\PhotoCompression\ImageCompressor();
                $compResult = $compressor->compressFile($targetPath);
                if (!empty($compResult['compressedSize'])) {
                    $finalSize = $compResult['compressedSize'];
                }
            } catch (\Throwable $e) {
                // Abaikan jika kompresi gagal agar berkas asli tetap aman tersimpan
            }
        }

        return [
            'success'  => true,
            'filename' => $newFilename,
            'path'     => $targetPath,
            'mime'     => $mimeType,
            'size'     => $finalSize,
            'error'    => null,
        ];
    }
}
