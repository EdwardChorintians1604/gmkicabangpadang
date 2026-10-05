<?php

namespace App\Controllers;

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

    public function index(Request $request): Response
    {
        Authorization::authorize('backup.view');

        $databaseBackups = $this->backupService->getDatabaseBackups();
        $manifestEntries = $this->backupService->getUploadsManifest();

        return view('keamanan.backup', [
            'pageTitle' => 'Pencadangan Data & Berkas - GMKI Cabang Padang',
            'dbBackups' => $databaseBackups,
            'manifestEntries' => $manifestEntries,
        ], 'dashboard');
    }

    public function createDatabaseBackup(Request $request): Response
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

    public function downloadDatabaseBackup(Request $request, string $filename): void
    {
        Authorization::authorize('backup.download');

        $safeFilename = basename($filename);
        $backupDir = $this->storageService->getBackupDir('database');
        $fullPath = $backupDir . DIRECTORY_SEPARATOR . $safeFilename;

        $response = new Response();
        $response->file($fullPath, $safeFilename, 'attachment');
    }

    public function generateManifest(Request $request): Response
    {
        Authorization::authorize('backup.create');

        $res = $this->backupService->generateUploadsManifest();

        if ($res['success']) {
            Session::flash('success', "Manifest berkas berhasil diperbarui ({$res['total_files']} berkas diverifikasi checksum SHA-256).");
        } else {
            Session::flash('error', 'Gagal membuat manifest berkas.');
        }

        redirect('/admin/keamanan/backup');
    }
}
