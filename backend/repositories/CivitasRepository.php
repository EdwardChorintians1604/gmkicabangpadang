<?php

namespace App\Repositories;

use App\Core\Database;

class CivitasRepository
{
    public function paginate(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        [$whereClause, $params] = $this->buildFilterConditions($filters);

        $sql = "SELECT `id`, `nim`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, 
                       `telepon`, `email`, `perguruan_tinggi`, `fakultas`, `jurusan`, `komisariat`, `anggota_komisariat`,
                       `tahun_maperca`, `tingkat_kaderisasi`, `status_keanggotaan`, `foto_anggota`, `file_kta`, `created_at`
                FROM `civitas`
                {$whereClause}
                ORDER BY `tahun_maperca` DESC, `nama_lengkap` ASC
                LIMIT :limit OFFSET :offset";

        $stmt = Database::getConnection()->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function count(array $filters = []): int
    {
        [$whereClause, $params] = $this->buildFilterConditions($filters);
        $sql = "SELECT COUNT(*) as total FROM `civitas` {$whereClause}";
        $row = Database::fetchOne($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    protected function buildFilterConditions(array $filters): array
    {
        $clauses = [];
        $params = [];

        if (!empty($filters['search'])) {
            $clauses[] = "(`nama_lengkap` LIKE :search1 OR `nim` LIKE :search2 OR `perguruan_tinggi` LIKE :search3)";
            $term = '%' . $filters['search'] . '%';
            $params[':search1'] = $term;
            $params[':search2'] = $term;
            $params[':search3'] = $term;
        }

        if (!empty($filters['komisariat'])) {
            $clauses[] = "`komisariat` = :komisariat";
            $params[':komisariat'] = $filters['komisariat'];
        }

        if (!empty($filters['perguruan_tinggi'])) {
            $clauses[] = "`perguruan_tinggi` = :perguruan_tinggi";
            $params[':perguruan_tinggi'] = $filters['perguruan_tinggi'];
        }

        if (!empty($filters['tahun_maperca'])) {
            $clauses[] = "`tahun_maperca` = :tahun_maperca";
            $params[':tahun_maperca'] = $filters['tahun_maperca'];
        }

        if (!empty($filters['status_keanggotaan'])) {
            $clauses[] = "`status_keanggotaan` = :status_keanggotaan";
            $params[':status_keanggotaan'] = $filters['status_keanggotaan'];
        }

        if (!empty($filters['tingkat_kaderisasi'])) {
            $clauses[] = "`tingkat_kaderisasi` = :tingkat_kaderisasi";
            $params[':tingkat_kaderisasi'] = $filters['tingkat_kaderisasi'];
        }

        // Pemisahan kelompok: 'maperca' = kader baru, 'anggota' = civitas resmi (non-Maperca)
        if (($filters['kelompok'] ?? '') === 'maperca') {
            $clauses[] = "`tingkat_kaderisasi` = 'Maperca'";
        } elseif (($filters['kelompok'] ?? '') === 'anggota') {
            $clauses[] = "`tingkat_kaderisasi` <> 'Maperca'";
        }

        $whereClause = !empty($clauses) ? 'WHERE ' . implode(' AND ', $clauses) : '';
        return [$whereClause, $params];
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM `civitas` WHERE `id` = :id LIMIT 1";
        return Database::fetchOne($sql, [':id' => $id]);
    }

    public function findByNim(string $nim): ?array
    {
        $sql = "SELECT * FROM `civitas` WHERE `nim` = :nim LIMIT 1";
        return Database::fetchOne($sql, [':nim' => $nim]);
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO `civitas` (
                    `nim`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`,
                    `telepon`, `email`, `perguruan_tinggi`, `fakultas`, `jurusan`,
                    `komisariat`, `anggota_komisariat`, `tahun_maperca`, `tingkat_kaderisasi`, `status_keanggotaan`,
                    `alamat_padang`, `alamat_asal`, `foto_anggota`, `file_kta`, `catatan`,
                    `created_by`, `created_at`
                ) VALUES (
                    :nim, :nama_lengkap, :jenis_kelamin, :tempat_lahir, :tanggal_lahir,
                    :telepon, :email, :perguruan_tinggi, :fakultas, :jurusan,
                    :komisariat, :anggota_komisariat, :tahun_maperca, :tingkat_kaderisasi, :status_keanggotaan,
                    :alamat_padang, :alamat_asal, :foto_anggota, :file_kta, :catatan,
                    :created_by, NOW()
                )";

        Database::execute($sql, [
            ':nim' => $data['nim'],
            ':nama_lengkap' => $data['nama_lengkap'],
            ':jenis_kelamin' => $data['jenis_kelamin'],
            ':tempat_lahir' => $data['tempat_lahir'] ?? null,
            ':tanggal_lahir' => !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null,
            ':telepon' => $data['telepon'] ?? null,
            ':email' => $data['email'] ?? null,
            ':perguruan_tinggi' => $data['perguruan_tinggi'],
            ':fakultas' => $data['fakultas'] ?? null,
            ':jurusan' => $data['jurusan'] ?? null,
            ':komisariat' => $data['komisariat'] ?? null,
            ':anggota_komisariat' => $data['anggota_komisariat'] ?? 1,
            ':tahun_maperca' => $data['tahun_maperca'] ?? null,
            ':tingkat_kaderisasi' => $data['tingkat_kaderisasi'] ?? 'Maperca',
            ':status_keanggotaan' => $data['status_keanggotaan'] ?? 'Aktif',
            ':alamat_padang' => $data['alamat_padang'] ?? null,
            ':alamat_asal' => $data['alamat_asal'] ?? null,
            ':foto_anggota' => $data['foto_anggota'] ?? null,
            ':file_kta' => $data['file_kta'] ?? null,
            ':catatan' => $data['catatan'] ?? null,
            ':created_by' => $data['created_by'] ?? null,
        ]);

        return (int)Database::lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        $allowedColumns = [
            'nim', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'telepon', 'email', 'perguruan_tinggi', 'fakultas', 'jurusan',
            'komisariat', 'anggota_komisariat', 'tahun_maperca', 'tingkat_kaderisasi', 'status_keanggotaan',
            'alamat_padang', 'alamat_asal', 'foto_anggota', 'file_kta', 'catatan'
        ];

        foreach ($allowedColumns as $col) {
            if (array_key_exists($col, $data)) {
                $fields[] = "`{$col}` = :{$col}";
                $params[":{$col}"] = $data[$col];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE `civitas` SET " . implode(', ', $fields) . ", `updated_at` = NOW() WHERE `id` = :id";
        return Database::execute($sql, $params);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM `civitas` WHERE `id` = :id";
        return Database::execute($sql, [':id' => $id]);
    }

    public function getAllForExport(array $filters = []): array
    {
        [$whereClause, $params] = $this->buildFilterConditions($filters);
        $sql = "SELECT `nim`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, 
                       `telepon`, `email`, `perguruan_tinggi`, `fakultas`, `jurusan`, `komisariat`, `anggota_komisariat`,
                       `tahun_maperca`, `tingkat_kaderisasi`, `status_keanggotaan`, `alamat_padang`, `alamat_asal`
                FROM `civitas` {$whereClause}
                ORDER BY `komisariat` ASC, `nama_lengkap` ASC";

        return Database::fetchAll($sql, $params);
    }

    public function getDistinctKomisariat(): array
    {
        $sql = "SELECT DISTINCT `komisariat` FROM `civitas` WHERE `anggota_komisariat` = 1 AND `komisariat` IS NOT NULL AND `komisariat` != '' ORDER BY `komisariat` ASC";
        $rows = Database::fetchAll($sql);
        return array_column($rows, 'komisariat');
    }

    public function getDistinctPerguruanTinggi(): array
    {
        $sql = "SELECT DISTINCT `perguruan_tinggi` FROM `civitas` WHERE `perguruan_tinggi` IS NOT NULL AND `perguruan_tinggi` != '' ORDER BY `perguruan_tinggi` ASC";
        $rows = Database::fetchAll($sql);
        return array_column($rows, 'perguruan_tinggi');
    }

    public function getDistinctTahunMaperca(): array
    {
        $sql = "SELECT DISTINCT `tahun_maperca` FROM `civitas` ORDER BY `tahun_maperca` DESC";
        $rows = Database::fetchAll($sql);
        return array_column($rows, 'tahun_maperca');
    }
}
