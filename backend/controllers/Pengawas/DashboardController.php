<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Pengawas/DashboardController.php
 * Deskripsi: Controller Dashboard Pemantauan Eksekutif untuk KETCAB, SEKCAB, BENCAB & MPPC
 * Lapisan: Controller (Pengawas) - Mode Baca Saja (Read-Only)
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Pengawas;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Services\AuditLogService;
use App\Services\CivitasService;
use App\Services\NewsService;
use App\Services\OrganizationService;
use App\Services\StatisticsService;

class DashboardController
{
    protected StatisticsService $statsService;
    protected AuditLogService $auditService;
    protected CivitasService $civitasService;
    protected NewsService $newsService;
    protected OrganizationService $orgService;

    public function __construct()
    {
        $this->statsService = new StatisticsService();
        $this->auditService = new AuditLogService();
        $this->civitasService = new CivitasService();
        $this->newsService = new NewsService();
        $this->orgService = new OrganizationService();
    }

    /**
     * Tampilkan panel dashboard pengawasan eksekutif
     */
    public function index(Request $request): Response
    {
        $summary = $this->statsService->getDashboardSummary();
        $recentLogs = $this->auditService->getRecent(10);
        $chartData = $this->statsService->getChartData();
        $structure = $this->orgService->getStructure(true);
        $profile = $this->orgService->getProfile();

        // Ambil data aktif tab pemantauan (default: ketcab)
        $tab = $request->query('tab', 'ketcab');

        return view('pengawas.dashboard.index', [
            'pageTitle' => 'Panel Pemantauan Eksekutif BPC - GMKI Cabang Padang',
            'summary' => $summary,
            'recentLogs' => $recentLogs,
            'chartData' => $chartData,
            'structure' => $structure,
            'profile' => $profile,
            'activeTab' => $tab,
            'user' => Auth::user(),
        ], 'pengawas');
    }
}
