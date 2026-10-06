<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Pengawas/MonitoringController.php
 * Deskripsi: Controller Pemantauan Jejak Audit, Keamanan, dan Arsip Backup
 * Lapisan: Controller (Pengawas) - Mode Pantauan Eksekutif & Pengawasan
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Pengawas;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Services\AuditLogService;
use App\Services\BackupService;
use App\Services\SecurityService;

class MonitoringController
{
    protected AuditLogService $auditService;
    protected SecurityService $securityService;
    protected BackupService $backupService;

    public function __construct()
    {
        $this->auditService = new AuditLogService();
        $this->securityService = new SecurityService();
        $this->backupService = new BackupService();
    }

    /**
     * Tampilkan jejak audit aktivitas pengurus cabang
     */
    public function auditLog(Request $request): Response
    {
        Authorization::authorize('security.audit_log');

        $page = (int)$request->query('page', 1);
        $action = $request->query('action');
        $entity = $request->query('entity');
        $username = $request->query('username');

        $filters = array_filter([
            'action' => $action,
            'entity' => $entity,
            'username' => $username,
        ]);

        $logData = $this->auditService->getLogs($filters, $page, 25);

        return view('pengawas.pemantauan.audit-log', [
            'pageTitle' => 'Pengawasan Jejak Audit Aktivitas - GMKI Cabang Padang',
            'logs' => $logData['data'],
            'total' => $logData['total'],
            'currentPage' => $logData['current_page'],
            'totalPages' => $logData['total_pages'],
            'selectedAction' => $action,
            'selectedEntity' => $entity,
            'searchUsername' => $username,
        ], 'pengawas');
    }

    /**
     * Tampilkan pemantauan kesehatan keamanan dan riwayat ancaman
     */
    public function threats(Request $request): Response
    {
        Authorization::authorize('security.audit_log');

        $page = (int)$request->query('page', 1);
        $threatData = $this->securityService->getThreatsList($page, 25);
        $healthChecks = $this->securityService->getSecurityHealth();

        return view('pengawas.pemantauan.keamanan', [
            'pageTitle' => 'Pemantauan Keamanan Sistem - GMKI Cabang Padang',
            'threats' => $threatData['data'],
            'total' => $threatData['total'],
            'currentPage' => $threatData['current_page'],
            'totalPages' => $threatData['total_pages'],
            'healthChecks' => $healthChecks,
        ], 'pengawas');
    }

    /**
     * Tampilkan status pencadangan basis data & manifest berkas
     */
    public function backup(Request $request): Response
    {
        Authorization::authorize('backup.view');

        $databaseBackups = $this->backupService->getDatabaseBackups();
        $manifestEntries = $this->backupService->getUploadsManifest();

        return view('pengawas.pemantauan.backup', [
            'pageTitle' => 'Pemantauan Arsip Cadangan Data - GMKI Cabang Padang',
            'dbBackups' => $databaseBackups,
            'manifestEntries' => $manifestEntries,
        ], 'pengawas');
    }

    /**
     * Unduh berkas cadangan database (Khusus Pengawas / BENCAB / MPPC)
     */
    public function download(Request $request): void
    {
        Authorization::authorize('backup.download');

        $filename = basename($request->query('file', $request->param('filename', '')));

        if (empty($filename) || !str_ends_with($filename, '.sql.gz') || !preg_match('/^[a-zA-Z0-9_\-\.]+\.sql\.gz$/', $filename)) {
            \App\Core\Session::flash('error', 'Format nama berkas cadangan tidak valid.');
            redirect('/pengawas/pemantauan/backup');
            return;
        }

        $storageService = new \App\Services\StorageService();
        $backupDir = realpath($storageService->getBackupDir('database'));
        $targetPath = realpath($storageService->getBackupDir('database') . DIRECTORY_SEPARATOR . $filename);

        if (!$backupDir || !$targetPath || !str_starts_with($targetPath, $backupDir) || !is_file($targetPath)) {
            \App\Core\Session::flash('error', 'Berkas cadangan tidak ditemukan di direktori penyimpanan.');
            redirect('/pengawas/pemantauan/backup');
            return;
        }

        $this->auditService->log('DOWNLOAD_BACKUP_PENGAWAS', 'system', $filename, [
            'filename' => $filename,
            'size' => filesize($targetPath),
            'sha256' => hash_file('sha256', $targetPath),
        ]);

        $response = new Response();
        $response->file($targetPath, $filename, 'attachment');
    }
}
