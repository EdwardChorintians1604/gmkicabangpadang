<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/packages/ZipArchivePolyfill.php
 * Deskripsi: Polyfill & Fallback Murni PHP (Pure-PHP PKZIP 2.0 Stream Writer)
 *            apabila ekstensi C ZipArchive (ext-zip) tidak aktif di server.
 * =====================================================================
 */

declare(strict_types=1);

if (!class_exists('ZipArchive', false)) {
    class ZipArchive
    {
        public const CREATE = 1;
        public const OVERWRITE = 8;
        public const EXCL = 2;
        public const CHECKCONS = 4;

        /** @var resource|null */
        private $fp = null;
        private string $zipPath = '';
        private array $centralDir = [];
        private int $offset = 0;
        public int $numFiles = 0;

        public function open(string $filename, int $flags = 0): bool|int
        {
            $dir = dirname($filename);
            if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
                return false;
            }

            $mode = ($flags & self::OVERWRITE) || ($flags & self::CREATE) || !file_exists($filename) ? 'wb' : 'r+b';
            $fp = @fopen($filename, $mode);
            if ($fp === false) {
                return false;
            }

            $this->fp = $fp;
            $this->zipPath = $filename;
            $this->centralDir = [];
            $this->offset = 0;
            $this->numFiles = 0;

            return true;
        }

        public function addFile(
            string $filepath,
            string $entryname = '',
            int $start = 0,
            int $length = 0,
            int $flags = 0
        ): bool {
            if ($this->fp === null || !is_file($filepath) || !is_readable($filepath)) {
                return false;
            }

            $content = @file_get_contents($filepath);
            if ($content === false) {
                return false;
            }

            if ($start > 0 || $length > 0) {
                $content = $length > 0 ? substr($content, $start, $length) : substr($content, $start);
            }

            $entry = $entryname !== '' ? $entryname : basename($filepath);
            $mtime = @filemtime($filepath) ?: time();

            return $this->writeEntry($entry, $content, $mtime);
        }

        public function addFromString(string $name, string $content, int $flags = 0): bool
        {
            if ($this->fp === null) {
                return false;
            }

            return $this->writeEntry($name, $content, time());
        }

        public function addEmptyDir(string $dirname, int $flags = 0): bool
        {
            if ($this->fp === null) {
                return false;
            }

            $entry = rtrim(str_replace('\\', '/', $dirname), '/') . '/';
            return $this->writeEntry($entry, '', time(), true);
        }

        private function writeEntry(string $name, string $content, int $timestamp, bool $isDir = false): bool
        {
            $entryName = ltrim(str_replace('\\', '/', $name), '/');
            if ($entryName === '') {
                return false;
            }

            $uncompressedSize = strlen($content);
            $crc = crc32($content);
            if ($crc < 0) {
                $crc += 4294967296;
            }

            if (!$isDir && $uncompressedSize > 0 && function_exists('gzdeflate')) {
                $compressed = gzdeflate($content, 6);
                if ($compressed !== false && strlen($compressed) < $uncompressedSize) {
                    $method = 8; // Deflate
                } else {
                    $compressed = $content;
                    $method = 0; // Store
                }
            } else {
                $compressed = $content;
                $method = 0; // Store
            }

            $compressedSize = strlen($compressed);
            [$dosTime, $dosDate] = $this->unixToDosTime($timestamp);
            $gpFlags = 0x0800; // UTF-8 filename flag
            $nameLen = strlen($entryName);

            // Local File Header (30 bytes + filename)
            $localHeader = pack(
                'VvvvvvVVVvv',
                0x04034b50,
                20,
                $gpFlags,
                $method,
                $dosTime,
                $dosDate,
                $crc,
                $compressedSize,
                $uncompressedSize,
                $nameLen,
                0
            ) . $entryName;

            $localOffset = $this->offset;
            $writtenHeader = fwrite($this->fp, $localHeader);
            $writtenData = $compressedSize > 0 ? fwrite($this->fp, $compressed) : 0;

            if ($writtenHeader === false || $writtenData === false) {
                return false;
            }

            $this->offset += strlen($localHeader) + $compressedSize;

            // Central Directory File Header (46 bytes + filename)
            $extAttr = $isDir ? 0x10 : 0x20;
            $this->centralDir[] = pack(
                'VvvvvvvVVVvvvvvVV',
                0x02014b50,
                20,
                20,
                $gpFlags,
                $method,
                $dosTime,
                $dosDate,
                $crc,
                $compressedSize,
                $uncompressedSize,
                $nameLen,
                0,
                0,
                0,
                0,
                $extAttr,
                $localOffset
            ) . $entryName;

            $this->numFiles++;
            return true;
        }

        private function unixToDosTime(int $timestamp): array
        {
            $dt = getdate($timestamp);
            if ($dt['year'] < 1980) {
                return [0, (1 << 5) | 1];
            }
            $dosDate = (($dt['year'] - 1980) << 9) | ($dt['mon'] << 5) | $dt['mday'];
            $dosTime = ($dt['hours'] << 11) | ($dt['minutes'] << 5) | ($dt['seconds'] >> 1);
            return [$dosTime, $dosDate];
        }

        public function close(): bool
        {
            if ($this->fp === null) {
                return false;
            }

            $cdContent = implode('', $this->centralDir);
            $cdSize = strlen($cdContent);
            $cdOffset = $this->offset;
            $count = count($this->centralDir);

            if ($cdSize > 0) {
                fwrite($this->fp, $cdContent);
            }

            // End of Central Directory Record (22 bytes)
            $eocd = pack(
                'VvvvvVVv',
                0x06054b50,
                0,
                0,
                $count,
                $count,
                $cdSize,
                $cdOffset,
                0
            );

            fwrite($this->fp, $eocd);
            $res = fclose($this->fp);

            $this->fp = null;
            $this->centralDir = [];
            $this->offset = 0;

            return $res;
        }
    }
}

