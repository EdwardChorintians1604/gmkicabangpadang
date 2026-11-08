<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/services/SettingService.php
 * Deskripsi: Layanan pengelolaan konfigurasi & pengaturan dinamis sistem
 *            (termasuk status izin pendaftaran Maperca / Anggota Baru).
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class SettingService
{
    private static ?array $cache = null;
    private static string $cacheFile = '';

    public function __construct()
    {
        if (self::$cacheFile === '') {
            self::$cacheFile = dirname(__DIR__, 2) . '/storage/settings.json';
        }
    }

    /**
     * Pastikan tabel settings tersedia di basis data
     */
    private function ensureTable(): void
    {
        static $checked = false;
        if ($checked) {
            return;
        }

        try {
            $sql = "CREATE TABLE IF NOT EXISTS `settings` (
                `key` VARCHAR(100) NOT NULL PRIMARY KEY,
                `value` TEXT NULL,
                `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
            Database::execute($sql);
            $checked = true;
        } catch (\Throwable $e) {
            // fallback gracefully
        }
    }

    /**
     * Dapatkan nilai pengaturan berdasarkan kunci
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();
        return $all[$key] ?? $default;
    }

    /**
     * Simpan / perbarui pengaturan
     */
    public function set(string $key, mixed $value): void
    {
        $this->ensureTable();

        $strVal = is_bool($value) ? ($value ? '1' : '0') : (string) $value;

        try {
            $sql = "INSERT INTO `settings` (`key`, `value`) 
                    VALUES (:key, :val) 
                    ON DUPLICATE KEY UPDATE `value` = :val_up";
            Database::execute($sql, [
                ':key' => $key,
                ':val' => $strVal,
                ':val_up' => $strVal
            ]);
        } catch (\Throwable $e) {
            // ignore DB failure and write to json cache
        }

        $all = $this->all();
        $all[$key] = $strVal;
        self::$cache = $all;
        $this->writeCache($all);
    }

    /**
     * Dapatkan semua pengaturan (dengan caching di memori & berkas JSON)
     */
    public function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        // Coba muat dari JSON cache dulu (sangat cepat)
        if (file_exists(self::$cacheFile)) {
            $json = @file_get_contents(self::$cacheFile);
            if ($json) {
                $decoded = json_decode($json, true);
                if (is_array($decoded)) {
                    self::$cache = $decoded;
                    return self::$cache;
                }
            }
        }

        $this->ensureTable();
        $settings = [];

        try {
            $rows = Database::fetchAll("SELECT `key`, `value` FROM `settings`");
            foreach ($rows as $row) {
                $settings[$row['key']] = $row['value'];
            }
        } catch (\Throwable $e) {
            // jika tabel belum ada atau DB mati
        }

        self::$cache = $settings;
        $this->writeCache($settings);

        return self::$cache;
    }

    private function writeCache(array $data): void
    {
        try {
            $dir = dirname(self::$cacheFile);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            @file_put_contents(self::$cacheFile, json_encode($data, JSON_PRETTY_PRINT));
        } catch (\Throwable $e) {
            // ignore file write errors
        }
    }

    /**
     * Cek apakah pendaftaran Maperca (calon anggota baru) sedang dibuka.
     * Standar default: Dibuka (true / '1').
     */
    public function isMapercaRegistrationOpen(): bool
    {
        $val = $this->get('maperca_registration_open', '1');
        return $val === '1' || $val === 'true' || $val === true || $val === 1;
    }

    /**
     * Ubah status izin pendaftaran Maperca.
     */
    public function setMapercaRegistrationOpen(bool $isOpen, ?string $message = null): void
    {
        $this->set('maperca_registration_open', $isOpen ? '1' : '0');
        if ($message !== null) {
            $this->set('maperca_closed_message', trim($message));
        }
    }
}

