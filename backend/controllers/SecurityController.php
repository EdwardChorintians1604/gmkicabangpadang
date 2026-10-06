<?php

namespace App\Controllers;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\AccessLogService;
use App\Services\AuditLogService;
use App\Services\SecurityService;

class SecurityController
{
    protected AuditLogService $auditService;
    protected SecurityService $securityService;
    protected AccessLogService $accessLogService;

    public function __construct()
    {
        $this->auditService = new AuditLogService();
        $this->securityService = new SecurityService();
        $this->accessLogService = new AccessLogService();
    }

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

        return view('keamanan.audit-log', [
            'pageTitle' => 'Jejak Audit Sistem - GMKI Cabang Padang',
            'logs' => $logData['data'],
            'total' => $logData['total'],
            'currentPage' => $logData['current_page'],
            'totalPages' => $logData['total_pages'],
            'selectedAction' => $action,
            'selectedEntity' => $entity,
            'searchUsername' => $username,
        ], 'dashboard');
    }

    public function ancaman(Request $request): Response
    {
        Authorization::authorize('security.threats');

        $page = (int)$request->query('page', 1);
        $threatData = $this->securityService->getThreatsList($page, 25);
        $healthChecks = $this->securityService->getSecurityHealth();

        return view('keamanan.ancaman', [
            'pageTitle' => 'Deteksi Ancaman & Keamanan - GMKI Cabang Padang',
            'threats' => $threatData['data'],
            'total' => $threatData['total'],
            'currentPage' => $threatData['current_page'],
            'totalPages' => $threatData['total_pages'],
            'healthChecks' => $healthChecks,
        ], 'dashboard');
    }

    public function resolveAncaman(Request $request, string $id): Response
    {
        Authorization::authorize('security.threats');

        $this->securityService->resolveThreat((int)$id);
        Session::flash('success', 'Status ancaman telah diperbarui menjadi Terselesaikan.');
        redirect('/admin/keamanan/ancaman');
    }

    public function accessLog(Request $request): Response
    {
        Authorization::authorize('security.audit_log');

        $page = (int)$request->query('page', 1);
        $ip = $request->query('ip');
        $username = $request->query('username');
        $deviceType = $request->query('device_type');
        $path = $request->query('path');

        $filters = array_filter([
            'ip' => $ip,
            'username' => $username,
            'device_type' => $deviceType,
            'path' => $path,
        ]);

        $logData = $this->accessLogService->getLogs($filters, $page, 25);
        $summary = $this->accessLogService->getSummaryStats();

        return view('admin.keamanan.akses-log', [
            'pageTitle' => 'Audit Akses & Perangkat Pengunjung - GMKI Cabang Padang',
            'logs' => $logData['data'],
            'total' => $logData['total'],
            'currentPage' => $logData['current_page'],
            'totalPages' => $logData['total_pages'],
            'summary' => $summary,
            'searchIp' => $ip,
            'searchUsername' => $username,
            'selectedDevice' => $deviceType,
            'searchPath' => $path,
        ], 'dashboard');
    }
}
