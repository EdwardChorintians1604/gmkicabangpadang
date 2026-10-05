<?php

namespace App\Services;

use App\Repositories\StatisticsRepository;

class StatisticsService
{
    protected StatisticsRepository $statsRepo;

    public function __construct()
    {
        $this->statsRepo = new StatisticsRepository();
    }

    public function getDashboardSummary(): array
    {
        return $this->statsRepo->getSummaryMetrics();
    }

    public function getChartData(): array
    {
        // 1. Berdasarkan Komisariat
        $commissariats = $this->statsRepo->getMembersByCommissariat();
        $comLabels = array_column($commissariats, 'komisariat');
        $comValues = array_map('intval', array_column($commissariats, 'total'));

        // 2. Berdasarkan Perguruan Tinggi
        $universities = $this->statsRepo->getMembersByUniversity(6);
        $uniLabels = array_column($universities, 'perguruan_tinggi');
        $uniValues = array_map('intval', array_column($universities, 'total'));

        // 3. Berdasarkan Gender
        $genders = $this->statsRepo->getMembersByGender();
        $genderMap = ['L' => 0, 'P' => 0];
        foreach ($genders as $g) {
            $genderMap[$g['jenis_kelamin']] = (int)$g['total'];
        }

        // 4. Berdasarkan Tahun Maperca
        $mapercaYears = $this->statsRepo->getMembersByMapercaYear();
        $yearLabels = array_column($mapercaYears, 'tahun_maperca');
        $yearValues = array_map('intval', array_column($mapercaYears, 'total'));

        // 5. Berdasarkan Jenjang Kaderisasi
        $kaderisasi = $this->statsRepo->getMembersByKaderisasi();
        $kadLabels = array_column($kaderisasi, 'tingkat_kaderisasi');
        $kadValues = array_map('intval', array_column($kaderisasi, 'total'));

        return [
            'komisariat' => [
                'labels' => $comLabels,
                'data' => $comValues,
            ],
            'perguruan_tinggi' => [
                'labels' => $uniLabels,
                'data' => $uniValues,
            ],
            'gender' => [
                'labels' => ['Laki-laki', 'Perempuan'],
                'data' => [$genderMap['L'], $genderMap['P']],
            ],
            'tahun_maperca' => [
                'labels' => $yearLabels,
                'data' => $yearValues,
            ],
            'kaderisasi' => [
                'labels' => $kadLabels,
                'data' => $kadValues,
            ],
        ];
    }
}
