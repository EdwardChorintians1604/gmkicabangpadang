<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/photo_compression/ImageCompressor.php
 * Deskripsi: Mesin kompresi dan optimasi gambar cerdas berbasis PHP GD.
 *            Menghemat ruang penyimpanan hingga 80% - 90% dengan tetap
 *            menjaga ketajaman visual gambar.
 * =====================================================================
 */

declare(strict_types=1);

namespace App\PhotoCompression;

class ImageCompressor
{
    /**
     * Konfigurasi kompresi bawaan
     */
    protected array $defaultOptions = [
        'maxWidth'             => 1280, // Resolusi lebar maksimum (pixel)
        'maxHeight'            => 1280, // Resolusi tinggi maksimum (pixel)
        'qualityJpeg'          => 78,   // Kualitas kompresi JPEG (1-100, 75-80 adalah sweet spot)
        'qualityWebp'          => 80,   // Kualitas kompresi WebP (1-100)
        'pngCompression'       => 8,    // Tingkat kompresi PNG (0-9)
        'stripExif'            => true, // Hapus metadata EXIF sampah untuk hemat memori
        'autoRotate'           => true, // Sesuaikan orientasi foto kamera HP yang miring/terbalik
        'preserveTransparency' => true, // Pertahankan transparansi PNG / WebP
    ];

    public function __construct(array $customOptions = [])
    {
        $this->defaultOptions = array_merge($this->defaultOptions, $customOptions);
    }

    /**
     * Kompresi berkas gambar yang ada di disk
     * Jika destPath bernilai null atau sama dengan sourcePath, file asli akan ditimpa dengan versi kompresi.
     */
    public function compressFile(string $sourcePath, ?string $destPath = null, array $options = []): array
    {
        if (!file_exists($sourcePath) || !is_file($sourcePath)) {
            return [
                'success' => false,
                'error'   => 'Berkas sumber tidak ditemukan: ' . $sourcePath,
            ];
        }

        $opts = array_merge($this->defaultOptions, $options);
        $destPath = $destPath ?? $sourcePath;
        $originalSize = (int)filesize($sourcePath);

        // Abaikan file yang ukurannya sudah sangat kecil (di bawah 30 KB) agar tidak memboroskan CPU
        if ($originalSize < 30 * 1024 && $sourcePath === $destPath) {
            return [
                'success'       => true,
                'skipped'       => true,
                'reason'        => 'Berkas sudah sangat kecil (< 30 KB)',
                'originalSize'  => $originalSize,
                'compressedSize'=> $originalSize,
                'savedBytes'    => 0,
                'savedPercent'  => 0,
                'path'          => $destPath,
            ];
        }

        // Dapatkan informasi gambar
        $imageInfo = @getimagesize($sourcePath);
        if ($imageInfo === false) {
            return [
                'success' => false,
                'error'   => 'Berkas bukan format gambar yang valid atau rusak.',
            ];
        }

        [$width, $height, $imageType] = $imageInfo;
        $mime = $imageInfo['mime'] ?? '';

        // Muat resource gambar berdasarkan tipe
        $sourceImage = $this->createImageResource($sourcePath, $imageType);
        if ($sourceImage === false || $sourceImage === null) {
            return [
                'success' => false,
                'error'   => 'PHP GD tidak dapat membaca gambar (MIME: ' . $mime . ').',
            ];
        }

        // Perbaiki orientasi foto (EXIF Orientation pada kamera ponsel)
        if ($opts['autoRotate'] && ($imageType === IMAGETYPE_JPEG) && function_exists('exif_read_data')) {
            $sourceImage = $this->fixOrientation($sourceImage, $sourcePath);
            $width = imagesx($sourceImage);
            $height = imagesy($sourceImage);
        }

        // Hitung dimensi baru jika melebihi batas maksimum
        [$newWidth, $newHeight] = $this->calculateDimensions(
            $width,
            $height,
            $opts['maxWidth'],
            $opts['maxHeight']
        );

        // Buat canvas gambar baru
        $targetImage = imagecreatetruecolor($newWidth, $newHeight);

        // Tangani transparansi untuk PNG dan WebP
        if ($opts['preserveTransparency'] && in_array($imageType, [IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
            $transparent = imagecolorallocatealpha($targetImage, 0, 0, 0, 127);
            imagefilledrectangle($targetImage, 0, 0, $newWidth, $newHeight, $transparent);
        } else {
            // Untuk JPEG, pastikan latar belakang putih bersih jika ada alpha
            $white = imagecolorallocate($targetImage, 255, 255, 255);
            imagefilledrectangle($targetImage, 0, 0, $newWidth, $newHeight, $white);
        }

        // Salin dan perkecil gambar dengan algoritma resample halus
        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        // Simpan gambar ke file temporer terlebih dahulu untuk keamanan
        $tempOut = $destPath . '.tmp_' . bin2hex(random_bytes(6));
        $saveSuccess = $this->saveImageResource($targetImage, $tempOut, $imageType, $opts);

        // Bebaskan memori GD
        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        if (!$saveSuccess || !file_exists($tempOut)) {
            if (file_exists($tempOut)) {
                @unlink($tempOut);
            }
            return [
                'success' => false,
                'error'   => 'Gagal mengekspor gambar terkompresi ke disk.',
            ];
        }

        $newSize = (int)filesize($tempOut);

        // Jika hasil kompresi ternyata lebih besar dari file asli (kasus langka), gunakan file asli
        if ($newSize >= $originalSize && $sourcePath === $destPath) {
            @unlink($tempOut);
            return [
                'success'       => true,
                'skipped'       => true,
                'reason'        => 'Berkas asli sudah teroptimasi dengan baik.',
                'originalSize'  => $originalSize,
                'compressedSize'=> $originalSize,
                'savedBytes'    => 0,
                'savedPercent'  => 0,
                'path'          => $destPath,
            ];
        }

        // Ganti file tujuan dengan file terkompresi
        @unlink($destPath);
        if (!rename($tempOut, $destPath)) {
            copy($tempOut, $destPath);
            @unlink($tempOut);
        }

        $savedBytes = max(0, $originalSize - $newSize);
        $savedPercent = $originalSize > 0 ? round(($savedBytes / $originalSize) * 100, 1) : 0;

        return [
            'success'       => true,
            'skipped'       => false,
            'originalSize'  => $originalSize,
            'compressedSize'=> $newSize,
            'savedBytes'    => $savedBytes,
            'savedPercent'  => $savedPercent,
            'dimensions'    => ['from' => "{$width}x{$height}", 'to' => "{$newWidth}x{$newHeight}"],
            'path'          => $destPath,
        ];
    }

    /**
     * Hitung rasio dimensi baru proporsional
     */
    protected function calculateDimensions(int $width, int $height, int $maxWidth, int $maxHeight): array
    {
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return [$width, $height];
        }

        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = max(1, (int)round($width * $ratio));
        $newHeight = max(1, (int)round($height * $ratio));

        return [$newWidth, $newHeight];
    }

    /**
     * Buat GD image resource berdasarkan tipe format
     */
    protected function createImageResource(string $path, int $type)
    {
        return match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            IMAGETYPE_GIF  => @imagecreatefromgif($path),
            default        => false,
        };
    }

    /**
     * Simpan GD image resource ke disk dengan kompresi optimal
     */
    protected function saveImageResource($image, string $targetPath, int $type, array $opts): bool
    {
        return match ($type) {
            IMAGETYPE_JPEG => imagejpeg($image, $targetPath, (int)$opts['qualityJpeg']),
            IMAGETYPE_PNG  => imagepng($image, $targetPath, (int)$opts['pngCompression']),
            IMAGETYPE_WEBP => function_exists('imagewebp') 
                ? imagewebp($image, $targetPath, (int)$opts['qualityWebp']) 
                : imagejpeg($image, $targetPath, (int)$opts['qualityJpeg']),
            IMAGETYPE_GIF  => imagegif($image, $targetPath),
            default        => false,
        };
    }

    /**
     * Memperbaiki orientasi gambar foto HP berdasarkan tag EXIF
     */
    protected function fixOrientation($image, string $path)
    {
        try {
            $exif = @exif_read_data($path);
            if (empty($exif['Orientation'])) {
                return $image;
            }

            return match ((int)$exif['Orientation']) {
                3 => imagerotate($image, 180, 0),
                6 => imagerotate($image, -90, 0),
                8 => imagerotate($image, 90, 0),
                default => $image,
            };
        } catch (\Throwable $e) {
            return $image;
        }
    }
}
