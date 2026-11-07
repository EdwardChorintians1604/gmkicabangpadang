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

        $page = max(1, (int)$request->query('page', 1));
        $q = trim((string)$request->query('q', ''));
        $severity = trim((string)$request->query('severity', ''));
        $status = trim((string)$request->query('status', ''));

        $filters = array_filter([
            'q' => $q,
            'severity' => $severity,
            'status' => $status,
        ]);

        $threatData = $this->securityService->getThreatsList($page, 25, $filters);
        $healthChecks = $this->securityService->getSecurityHealth();
        $summary = $this->securityService->getSummaryStats();

        $okCount = 0;
        $openGaps = 0;
        foreach ($healthChecks as $chk) {
            if (($chk['status'] ?? '') === 'ok') {
                $okCount++;
            } else {
                $openGaps++;
            }
        }
        $securityScore = (int)round(($okCount / max(1, count($healthChecks))) * 100);

        return view('admin.keamanan.ancaman', [
            'pageTitle' => 'Deteksi Ancaman, WAF & Auto-Hardening - GMKI Cabang Padang',
            'threats' => $threatData['data'],
            'total' => $threatData['total'],
            'currentPage' => $threatData['current_page'],
            'totalPages' => $threatData['total_pages'],
            'healthChecks' => $healthChecks,
            'summary' => $summary,
            'securityScore' => $securityScore,
            'openGaps' => $openGaps,
            'searchQuery' => $q,
            'selectedSeverity' => $severity,
            'selectedStatus' => $status,
        ], 'admin');
    }

    /**
     * Eksekusi Perbaikan Langsung & Penutupan Celah Keamanan (One-Click Auto-Remediation)
     */
    public function autoFix(Request $request): Response
    {
        Authorization::authorize('security.threats');

        $target = trim((string)$request->input('target', 'all'));
        $actions = $this->securityService->autoFixVulnerabilities($target);

        if (!empty($actions)) {
            Session::flash('success', 'Perbaikan Langsung & Penutupan Celah Berhasil: ' . implode(' | ', $actions));
        } else {
            Session::flash('success', 'Seluruh lapisan keamanan telah diverifikasi dan berada dalam kondisi tertutup aman.');
        }

        redirect('/admin/keamanan/ancaman');
    }

    /**
     * Jalankan Pemindaian Mendalam (Deep Security & WebShell Scan)
     */
    public function deepScan(Request $request): Response
    {
        Authorization::authorize('security.threats');

        $report = $this->securityService->runDeepSecurityScan();
        $suspiciousCount = count($report['suspicious_files'] ?? []);
        $unprotectedCount = count($report['unprotected_dirs'] ?? []);

        if ($suspiciousCount === 0 && $unprotectedCount === 0) {
            Session::flash('success', "Pemindaian Mendalam Selesai ({$report['scanned_at']}): Skor Keamanan {$report['score']}% ({$report['passed_checks']}/{$report['total_checks']} perisai lulus). Tidak ditemukan backdoor PHP2Shell atau berkas berbahaya.");
        } else {
            Session::flash('error', "Pemindaian Mendalam menemukan {$suspiciousCount} berkas mencurigakan dan {$unprotectedCount} direktori belum terproteksi. Klik 'Perbaiki & Tutup Seluruh Celah' untuk memitigasi otomatis!");
        }

        redirect('/admin/keamanan/ancaman');
    }

    /**
     * Blokir / Karantina alamat IP penyerang ke daftar hitam WAF
     */
    public function blockIp(Request $request): Response
    {
        Authorization::authorize('security.threats');

        $ip = trim((string)$request->input('ip_address', ''));
        $reason = trim((string)$request->input('reason', 'Diblokir dari konsol Deteksi Ancaman'));

        if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP)) {
            Session::flash('error', 'Format alamat IP tidak valid.');
            redirect('/admin/keamanan/ancaman');
        }

        if (in_array($ip, ['127.0.0.1', '::1'], true)) {
            Session::flash('error', 'Alamat IP localhost (loopback) tidak dapat dimasukkan ke daftar hitam.');
            redirect('/admin/keamanan/ancaman');
        }

        if ($this->securityService->blockAttackerIp($ip, $reason)) {
            Session::flash('success', "Alamat IP {$ip} berhasil dikunci dalam daftar hitam (IPS Blacklist) WAF.");
        } else {
            Session::flash('error', "Gagal memblokir alamat IP {$ip}.");
        }

        redirect('/admin/keamanan/ancaman');
    }

    /**
     * Buka blokir alamat IP dari daftar hitam WAF
     */
    public function unblockIp(Request $request): Response
    {
        Authorization::authorize('security.threats');

        $ip = trim((string)$request->input('ip_address', ''));
        if ($ip !== '' && $this->securityService->unblockAttackerIp($ip)) {
            Session::flash('success', "Alamat IP {$ip} telah dihapus dari daftar hitam WAF.");
        } else {
            Session::flash('error', "Gagal membuka blokir alamat IP {$ip}.");
        }

        redirect('/admin/keamanan/ancaman');
    }

    /**
     * Tandai seluruh insiden ancaman sebagai selesai dimitigasi
     */
    public function resolveAll(Request $request): Response
    {
        Authorization::authorize('security.threats');

        $this->securityService->resolveAllThreats();
        Session::flash('success', 'Seluruh catatan insiden ancaman telah ditandai selesai dimitigasi.');
        redirect('/admin/keamanan/ancaman');
    }

    /**
     * Tandai ancaman keamanan sebagai selesai diatasi
     */
    public function resolveAncaman(Request $request, string $id): Response
    {
        Authorization::authorize('security.threats');

        $this->securityService->resolveThreat((int)$id);
        Session::flash('success', 'Status ancaman #' . (int)$id . ' telah diperbarui menjadi Terselesaikan.');
        redirect('/admin/keamanan/ancaman');
    }

    /**
     * Hapus catatan insiden ancaman dari log
     */
    public function deleteAncaman(Request $request, string $id): Response
    {
        Authorization::authorize('security.threats');

        $this->securityService->deleteThreat((int)$id);
        Session::flash('success', 'Catatan insiden ancaman #' . (int)$id . ' berhasil dihapus.');
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
