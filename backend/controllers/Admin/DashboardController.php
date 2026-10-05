<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/DashboardController.php
 * Deskripsi: Controller Dashboard Panel Utama Administrator
 * Lapisan: Controller (Admin)
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Services\AuditLogService;
use App\Services\StatisticsService;

class DashboardController
{
    protected StatisticsService $statsService;
    protected AuditLogService $auditService;

    public function __construct()
    {
        $this->statsService = new StatisticsService();
        $this->auditService = new AuditLogService();
    }

    /**
     * Tampilkan halaman utama panel kontrol admin
     */
    public function index(Request $request): Response
    {
        $summary = $this->statsService->getDashboardSummary();
        $recentLogs = $this->auditService->getRecent(8);
        $chartData = $this->statsService->getChartData();

        return view('admin.dashboard', [
            'pageTitle' => 'Dashboard Kendali Administrator - GMKI Cabang Padang',
            'summary' => $summary,
            'recentLogs' => $recentLogs,
            'chartData' => $chartData,
            'user' => Auth::user(),
        ], 'admin');
    }
}
