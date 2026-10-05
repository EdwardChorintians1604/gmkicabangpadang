<?php

namespace App\Services;

class StorageService
{
    protected array $config;

    public function __construct()
    {
        $this->config = require dirname(__DIR__) . '/config/storage.php';
    }

    public function getPrivatePath(string $type, string $filename): string
    {
        $filename = basename($filename); // cegah directory traversal
        if ($type === 'foto_anggota') {
            return $this->config['private_storage']['foto_anggota'] . DIRECTORY_SEPARATOR . $filename;
        }
        if ($type === 'kta') {
            return $this->config['private_storage']['kta'] . DIRECTORY_SEPARATOR . $filename;
        }
        return $this->config['private_storage']['base'] . DIRECTORY_SEPARATOR . $filename;
    }

    public function getPublicUploadDir(string $type = 'berita'): string
    {
        return $this->config['public_uploads'][$type] ?? $this->config['public_uploads']['base'];
    }

    public function getPrivateUploadDir(string $type = 'foto_anggota'): string
    {
        return $this->config['private_storage'][$type] ?? $this->config['private_storage']['base'];
    }

    public function getBackupDir(string $type = 'database'): string
    {
        return $this->config['backup'][$type] ?? $this->config['backup']['database'];
    }

    public function deletePrivateFile(string $type, ?string $filename): void
    {
        if (empty($filename)) {
            return;
        }
        $fullPath = $this->getPrivatePath($type, $filename);
        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function deletePublicFile(string $type, ?string $filename): void
    {
        if (empty($filename)) {
            return;
        }
        $dir = $this->getPublicUploadDir($type);
        $fullPath = $dir . DIRECTORY_SEPARATOR . basename($filename);
        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
