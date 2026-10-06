<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/BackupController.php
 * Deskripsi: Controller Pencadangan Basis Data & Integritas Berkas SHA-256
 * Lapisan: Controller (Admin)
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\BackupService;
use App\Services\StorageService;

class BackupController
{
    protected BackupService $backupService;
    protected StorageService $storageService;

    public function __construct()
    {
        $this->backupService = new BackupService();
        $this->storageService = new StorageService();
    }

    /**
     * Tampilkan daftar arsip backup database & manifest berkas
     */
    public function index(Request $request): Response
    {
        Authorization::authorize('backup.view');

        $databaseBackups = $this->backupService->getDatabaseBackups();
        $manifestEntries = $this->backupService->getUploadsManifest();

        return view('admin.keamanan.backup', [
            'pageTitle' => 'Pencadangan Data & Integritas Berkas - GMKI Cabang Padang',
            'dbBackups' => $databaseBackups,
            'manifestEntries' => $manifestEntries,
        ], 'admin');
    }

    /**
     * Eksekusi dump pencadangan basis data terkompresi .sql.gz
     */
    public function backupDatabase(Request $request): Response
    {
        Authorization::authorize('backup.create');

        $result = $this->backupService->createDatabaseBackup();

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['message']);
        }

        redirect('/admin/keamanan/backup');
    }

    /**
     * Unduh berkas backup database dengan proteksi ketat Path Traversal
     */
    public function download(Request $request): void
    {
        Authorization::authorize('backup.download');

        $filename = basename($request->query('file', $request->param('filename', '')));

        if (empty($filename) || !str_ends_with($filename, '.sql.gz') || !preg_match('/^[a-zA-Z0-9_\-\.]+\.sql\.gz$/', $filename)) {
            Session::flash('error', 'Format nama berkas cadangan tidak valid atau mengandung karakter terlarang.');
            redirect('/admin/keamanan/backup');
            return;
        }

        $backupDir = realpath($this->storageService->getBackupDir('database'));
        $targetPath = realpath($this->storageService->getBackupDir('database') . DIRECTORY_SEPARATOR . $filename);

        if (!$backupDir || !$targetPath || !str_starts_with($targetPath, $backupDir) || !is_file($targetPath)) {
            Session::flash('error', 'Berkas backup tidak ditemukan di direktori penyimpanan aman.');
            redirect('/admin/keamanan/backup');
            return;
        }

        // Catat jejak audit pengunduhan
        $auditService = new \App\Services\AuditLogService();
        $auditService->log('DOWNLOAD_BACKUP_DATABASE', 'system', $filename, [
            'filename' => $filename,
            'size' => filesize($targetPath),
            'sha256' => hash_file('sha256', $targetPath),
        ]);

        $response = new Response();
        $response->file($targetPath, $filename, 'attachment');
    }

    /**
     * Buat cadangan terbaru lalu langsung unduh dalam satu langkah
     */
    public function createAndDownload(Request $request): void
    {
        Authorization::authorize('backup.create');
        Authorization::authorize('backup.download');

        $result = $this->backupService->createDatabaseBackup();

        if (!$result['success']) {
            Session::flash('error', 'Gagal membuat cadangan database: ' . ($result['message'] ?? ''));
            redirect('/admin/keamanan/backup');
            return;
        }

        $filename = $result['filename'];
        $backupDir = realpath($this->storageService->getBackupDir('database'));
        $targetPath = realpath($backupDir . DIRECTORY_SEPARATOR . $filename);

        if (!$backupDir || !$targetPath || !str_starts_with($targetPath, $backupDir) || !is_file($targetPath)) {
            Session::flash('error', 'Berkas backup gagal dipersiapkan untuk pengunduhan.');
            redirect('/admin/keamanan/backup');
            return;
        }

        // Catat jejak audit
        $auditService = new \App\Services\AuditLogService();
        $auditService->log('INSTANT_BACKUP_AND_DOWNLOAD', 'system', $filename, [
            'filename' => $filename,
            'size' => filesize($targetPath),
            'sha256' => $result['sha256'] ?? '',
        ]);

        $response = new Response();
        $response->file($targetPath, $filename, 'attachment');
    }

    /**
     * Hasilkan manifest berkas unggahan lengkap dengan hash SHA-256
     */
    public function generateManifest(Request $request): Response
    {
        Authorization::authorize('backup.create');

        $res = $this->backupService->generateUploadsManifest();

        if ($res['success']) {
            Session::flash('success', "Manifest berkas berhasil diperbarui ({$res['total_files']} berkas terverifikasi SHA-256).");
        } else {
            Session::flash('error', 'Gagal membuat manifest berkas.');
        }

        redirect('/admin/keamanan/backup');
    }
}
