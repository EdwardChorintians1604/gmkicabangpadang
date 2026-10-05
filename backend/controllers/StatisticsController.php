<?php

namespace App\Controllers;

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

    public function index(Request $request): Response
    {
        Authorization::authorize('statistics.view');

        $summary = $this->statsService->getDashboardSummary();
        $chartData = $this->statsService->getChartData();

        return view('statistik.index', [
            'pageTitle' => 'Statistik & Analisis Data Civitas - GMKI Cabang Padang',
            'summary' => $summary,
            'chartData' => $chartData,
        ], 'dashboard');
    }

    public function apiData(Request $request): Response
    {
        Authorization::authorize('statistics.view');

        $data = $this->statsService->getChartData();
        $response = new Response();
        return $response->json($data);
    }
}
