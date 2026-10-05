<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/StatisticsController.php
 * Deskripsi: Controller Analisis Statistik & Visualisasi Grafik Chart.js
 * Lapisan: Controller (Admin)
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

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
     * Tampilkan halaman visualisasi grafik data civitas
     */
    public function index(Request $request): Response
    {
        Authorization::authorize('statistics.view');

        $summary = $this->statsService->getDashboardSummary();
        $chartData = $this->statsService->getChartData();

        return view('admin.statistik.index', [
            'pageTitle' => 'Statistik & Analisis Data Civitas - GMKI Cabang Padang',
            'summary' => $summary,
            'chartData' => $chartData,
        ], 'admin');
    }

    /**
     * Endpoint API JSON data grafik (untuk Chart.js AJAX reload)
     */
    public function apiData(Request $request): Response
    {
        Authorization::authorize('statistics.view');

        $data = $this->statsService->getChartData();
        $response = new Response();
        return $response->json($data);
    }
}
