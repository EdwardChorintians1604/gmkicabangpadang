<?php

namespace App\Controllers;

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

    public function admin(Request $request): Response
    {
        $summary = $this->statsService->getDashboardSummary();
        $recentLogs = $this->auditService->getRecent(6);
        $chartData = $this->statsService->getChartData();

        return view('dashboard.admin', [
            'pageTitle' => 'Dashboard Pengurus - GMKI Cabang Padang',
            'summary' => $summary,
            'recentLogs' => $recentLogs,
            'chartData' => $chartData,
            'user' => Auth::user(),
        ], 'dashboard');
    }

    public function pengawas(Request $request): Response
    {
        $summary = $this->statsService->getDashboardSummary();
        $recentLogs = $this->auditService->getRecent(12);
        $chartData = $this->statsService->getChartData();

        return view('dashboard.pengawas', [
            'pageTitle' => 'Panel Pengawas Cabang - GMKI Cabang Padang',
            'summary' => $summary,
            'recentLogs' => $recentLogs,
            'chartData' => $chartData,
            'user' => Auth::user(),
        ], 'dashboard');
    }
}
