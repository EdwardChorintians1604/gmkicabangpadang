<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/SecurityController.php
 * Deskripsi: Controller Pemantauan Audit Log & Ancaman Keamanan Sistem
 * Lapisan: Controller (Admin)
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\AuditLogService;
use App\Services\SecurityService;

class SecurityController
{
    protected AuditLogService $auditService;
    protected SecurityService $securityService;

    public function __construct()
    {
        $this->auditService = new AuditLogService();
        $this->securityService = new SecurityService();
    }

    /**
     * Tampilkan riwayat audit log aktivitas sistem
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

        return view('admin.keamanan.audit-log', [
            'pageTitle' => 'Jejak Audit Aktivitas Sistem - GMKI Cabang Padang',
            'logs' => $logData['data'],
            'total' => $logData['total'],
            'currentPage' => $logData['current_page'],
            'totalPages' => $logData['total_pages'],
            'selectedAction' => $action,
            'selectedEntity' => $entity,
            'searchUsername' => $username,
        ], 'admin');
    }

    /**
     * Tampilkan pantauan ancaman & status kesehatan keamanan sistem
     */
    public function threats(Request $request): Response
    {
        Authorization::authorize('security.threats');

        $page = (int)$request->query('page', 1);
        $threatData = $this->securityService->getThreatsList($page, 25);
        $healthChecks = $this->securityService->getSecurityHealth();

        return view('admin.keamanan.ancaman', [
            'pageTitle' => 'Deteksi Ancaman & Kesehatan Sistem - GMKI Cabang Padang',
            'threats' => $threatData['data'],
            'total' => $threatData['total'],
            'currentPage' => $threatData['current_page'],
            'totalPages' => $threatData['total_pages'],
            'healthChecks' => $healthChecks,
        ], 'admin');
    }

    /**
     * Tandai ancaman keamanan sebagai selesai diatasi
     */
    public function resolveAncaman(Request $request, string $id): Response
    {
        Authorization::authorize('security.threats');

        $this->securityService->resolveThreat((int)$id);
        Session::flash('success', 'Status ancaman telah diperbarui menjadi Terselesaikan.');
        redirect('/admin/keamanan/ancaman');
    }
}
