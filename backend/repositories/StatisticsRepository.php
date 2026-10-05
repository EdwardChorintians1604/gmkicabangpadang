<?php

namespace App\Repositories;

use App\Core\Database;

class StatisticsRepository
{
    public function getSummaryMetrics(): array
    {
        $notMaperca = "`tingkat_kaderisasi` <> 'Maperca'";
        $totalCivitas = (int)(Database::fetchOne("SELECT COUNT(*) as c FROM `civitas` WHERE {$notMaperca}")['c'] ?? 0);
        $totalMaperca = (int)(Database::fetchOne("SELECT COUNT(*) as c FROM `civitas` WHERE `tingkat_kaderisasi` = 'Maperca'")['c'] ?? 0);
        $activeCivitas = (int)(Database::fetchOne("SELECT COUNT(*) as c FROM `civitas` WHERE `status_keanggotaan` = 'Aktif' AND {$notMaperca}")['c'] ?? 0);
        $alumniCivitas = (int)(Database::fetchOne("SELECT COUNT(*) as c FROM `civitas` WHERE `status_keanggotaan` = 'Alumni/Senior' AND {$notMaperca}")['c'] ?? 0);
        $totalKomisariat = (int)(Database::fetchOne("SELECT COUNT(DISTINCT `komisariat`) as c FROM `civitas` WHERE `anggota_komisariat` = 1 AND `komisariat` != ''")['c'] ?? 0);
        $totalBerita = (int)(Database::fetchOne("SELECT COUNT(*) as c FROM `berita` WHERE `status` = 'published'")['c'] ?? 0);
        $totalViews = (int)(Database::fetchOne("SELECT SUM(`views`) as c FROM `berita`")['c'] ?? 0);

        return [
            'total_civitas' => $totalCivitas,
            'total_maperca' => $totalMaperca,
            'active_civitas' => $activeCivitas,
            'alumni_civitas' => $alumniCivitas,
            'total_komisariat' => $totalKomisariat,
            'total_berita' => $totalBerita,
            'total_views' => $totalViews,
        ];
    }

    public function getMembersByCommissariat(): array
    {
        $sql = "SELECT `komisariat`, COUNT(*) as total 
                FROM `civitas` 
                WHERE `anggota_komisariat` = 1 AND `komisariat` IS NOT NULL AND `komisariat` != '' AND `tingkat_kaderisasi` <> 'Maperca'
                GROUP BY `komisariat` 
                ORDER BY total DESC";
        return Database::fetchAll($sql);
    }

    public function getMembersByUniversity(int $limit = 8): array
    {
        $sql = "SELECT `perguruan_tinggi`, COUNT(*) as total 
                FROM `civitas` 
                WHERE `perguruan_tinggi` IS NOT NULL AND `perguruan_tinggi` != '' AND `tingkat_kaderisasi` <> 'Maperca'
                GROUP BY `perguruan_tinggi` 
                ORDER BY total DESC 
                LIMIT :limit";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMembersByGender(): array
    {
        $sql = "SELECT `jenis_kelamin`, COUNT(*) as total 
                FROM `civitas` 
                WHERE `tingkat_kaderisasi` <> 'Maperca'
                GROUP BY `jenis_kelamin`";
        return Database::fetchAll($sql);
    }

    public function getMembersByMapercaYear(): array
    {
        $sql = "SELECT `tahun_maperca`, COUNT(*) as total 
                FROM `civitas` 
                GROUP BY `tahun_maperca` 
                ORDER BY `tahun_maperca` ASC";
        return Database::fetchAll($sql);
    }

    public function getMembersByKaderisasi(): array
    {
        $sql = "SELECT `tingkat_kaderisasi`, COUNT(*) as total 
                FROM `civitas` 
                GROUP BY `tingkat_kaderisasi`";
        return Database::fetchAll($sql);
    }
}
