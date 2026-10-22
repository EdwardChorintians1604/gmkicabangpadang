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
        $ip = $request->query('ip');
        $date = $request->query('date');

        $filters = array_filter([
            'action' => $action,
            'entity' => $entity,
            'username' => $username,
            'ip' => $ip,
            'date' => $date,
        ]);

        $logData = $this->auditService->getLogs($filters, $page, 25);
        $summaryStats = $this->auditService->getSummaryStats();

        return view('admin.keamanan.audit-log', [
            'pageTitle' => 'Jejak Audit Aktivitas Sistem - GMKI Cabang Padang',
            'logs' => $logData['data'],
            'total' => $logData['total'],
            'currentPage' => $logData['current_page'],
            'totalPages' => $logData['total_pages'],
            'summaryStats' => $summaryStats,
            'selectedAction' => $action,
            'selectedEntity' => $entity,
            'searchUsername' => $username,
            'searchIp' => $ip,
            'searchDate' => $date,
        ], 'admin');
    }

    /**
     * Ekspor jejak audit ke format CSV
     */
    public function exportAuditLog(Request $request): void
    {
        Authorization::authorize('security.audit_log');

        $filters = array_filter([
            'action' => $request->query('action'),
            'entity' => $request->query('entity'),
            'username' => $request->query('username'),
            'ip' => $request->query('ip'),
            'date' => $request->query('date'),
        ]);

        $logs = $this->auditService->getForExport($filters);

        $filename = 'audit_trail_gmki_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // Write UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['ID', 'Waktu (WIB)', 'Pengguna', 'Role', 'Aksi', 'Entitas', 'ID Entitas', 'Detail Perubahan', 'Alamat IP', 'User Agent']);

        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['username'],
                $log['role'],
                $log['action'],
                $log['entity'],
                $log['entity_id'] ?? '-',
                $log['details'] ?? '-',
                $log['ip_address'],
                $log['user_agent'],
            ]);
        }

        fclose($output);
        exit;
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

    /**
     * Tampilkan audit log akses pengunjung, IP, dan perangkat website
     */
    public function accessLog(Request $request): Response
    {
        Authorization::authorize('security.audit_log');

        $page = (int)$request->query('page', 1);
        $ip = $request->query('ip');
        $username = $request->query('username');
        $deviceType = $request->query('device_type');
        $path = $request->query('path');
        $statusCode = $request->query('status_code');

        $filters = array_filter([
            'ip' => $ip,
            'username' => $username,
            'device_type' => $deviceType,
            'path' => $path,
            'status_code' => $statusCode,
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
            'selectedStatus' => $statusCode,
        ], 'admin');
    }

    /**
     * Ekspor riwayat akses pengunjung ke format CSV
     */
    public function exportAccessLog(Request $request): void
    {
        Authorization::authorize('security.audit_log');

        $filters = array_filter([
            'ip' => $request->query('ip'),
            'username' => $request->query('username'),
            'device_type' => $request->query('device_type'),
            'path' => $request->query('path'),
            'status_code' => $request->query('status_code'),
        ]);

        $logs = $this->accessLogService->getForExport($filters);

        $filename = 'audit_akses_pengunjung_gmki_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // Write UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['ID', 'Waktu Akses (WIB)', 'Alamat IP', 'Pengguna', 'Role', 'Method', 'Path/URL', 'Status Code', 'Perangkat', 'Platform OS', 'Browser', 'Referer', 'User Agent']);

        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['ip_address'],
                $log['username'],
                $log['role'],
                $log['method'],
                $log['path'],
                $log['status_code'],
                $log['device_type'],
                $log['platform'],
                $log['browser'],
                $log['referer'] ?? '-',
                $log['user_agent'],
            ]);
        }

        fclose($output);
        exit;
    }
}
