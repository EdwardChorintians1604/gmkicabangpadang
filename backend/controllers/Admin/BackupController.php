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
     * Unduh berkas backup database
     */
    public function download(Request $request): void
    {
        Authorization::authorize('backup.download');

        $filename = $request->query('file', '');
        $safeFilename = basename($filename);
        $backupDir = $this->storageService->getBackupDir('database');
        $fullPath = $backupDir . DIRECTORY_SEPARATOR . $safeFilename;

        if (!file_exists($fullPath)) {
            Session::flash('error', 'Berkas backup tidak ditemukan.');
            redirect('/admin/keamanan/backup');
        }

        $response = new Response();
        $response->file($fullPath, $safeFilename, 'attachment');
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
