<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Pengawas/StatisticsController.php
 * Deskripsi: Controller Pemantauan Statistik & Analisis Demografi Civitas
 * Lapisan: Controller (Pengawas) - Visualisasi Chart.js
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Pengawas;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Services\StatisticsService;

class StatisticsController
{
    protected StatisticsService $statsService;

    public function __construct()
    {
        $this->statsService = new StatisticsService();
    }

    /**
     * Tampilkan visualisasi analitik statistik keanggotaan dan kaderisasi
     */
    public function index(Request $request): Response
    {
        Authorization::authorize('statistics.view');

        $summary = $this->statsService->getDashboardSummary();
        $chartData = $this->statsService->getChartData();

        return view('pengawas.statistik.index', [
            'pageTitle' => 'Statistik & Analisis Data Pengawasan - GMKI Cabang Padang',
            'summary' => $summary,
            'chartData' => $chartData,
        ], 'pengawas');
    }
}
