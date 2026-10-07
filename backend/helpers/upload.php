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

        // Validasi MIME type sebenarnya dengan finfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        // Normalisasi JPEG MIME types
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
                return ['success' => false, 'error' => "Format berkas tidak diizinkan ({$mimeType}). Hanya menerima gambar JPG, PNG, atau WebP."];
            }
        }

        // Tentukan ekstensi aman dari MIME type
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

        $extension = $mimeExtensions[$normalizedMime] ?? $mimeExtensions[$mimeType] ?? pathinfo($file['name'], PATHINFO_EXTENSION);
        $extension = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $extension));

        // Hindari ekstensi berbahaya
        if (in_array($extension, ['php', 'phtml', 'phar', 'exe', 'sh', 'bat', 'js', 'html', 'htm'], true)) {
            return ['success' => false, 'error' => 'Format berkas tidak aman dan ditolak.'];
        }

        if (!is_dir($targetDirectory)) {
            @mkdir($targetDirectory, 0777, true);
        }

        $safeBase = bin2hex(random_bytes(16));
        $newFilename = sprintf('%s_%s.%s', date('Ymd_His'), $safeBase, $extension);
        $targetPath = rtrim($targetDirectory, '/\\') . DIRECTORY_SEPARATOR . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'error' => 'Gagal memindahkan berkas ke folder tujuan.'];
        }

        return [
            'success' => true,
            'filename' => $newFilename,
            'path' => $targetPath,
            'mime' => $mimeType,
            'size' => $file['size'],
            'error' => null,
        ];
    }
}
