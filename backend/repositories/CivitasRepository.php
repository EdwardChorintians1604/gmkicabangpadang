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
                       `tahun_maperca`, `tingkat_kaderisasi`, `status_keanggotaan`, `asal_civitas`, `foto_anggota`, `file_kta`, `created_at`
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
            $term = '%' . $filters['search'] . '%';
            $rawSearch = trim((string)$filters['search']);
            $idSearch = ltrim($rawSearch, '#');
            if (is_numeric($idSearch)) {
                $clauses[] = "(`id` = :search_id OR `nim` LIKE :search2 OR `nama_lengkap` LIKE :search1 OR `perguruan_tinggi` LIKE :search3)";
                $params[':search_id'] = (int)$idSearch;
            } else {
                $clauses[] = "(`nama_lengkap` LIKE :search1 OR `nim` LIKE :search2 OR `perguruan_tinggi` LIKE :search3)";
            }
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

    /**
     * Ekstrak inisial huruf kapital dari nama lengkap peserta/civitas.
     * Contoh: "Erantus Paripurna Samaloisa" -> "EPS"
     */
    public static function extractInitials(string $namaLengkap): string
    {
        $clean = trim($namaLengkap);
        if ($clean === '') {
            return 'XX';
        }
        $words = preg_split('/[\s\-]+/u', $clean) ?: [];
        $initials = '';
        foreach ($words as $word) {
            $lettersOnly = preg_replace('/[^a-zA-Z]/', '', $word);
            if ($lettersOnly !== '') {
                $initials .= strtoupper(substr($lettersOnly, 0, 1));
            }
        }
        return $initials !== '' ? $initials : 'XX';
    }

    /**
     * Bentuk ID Anggota / Civitas / Maperca Cabang Padang:
     * Format: {TAHUN_MAPER}-{2_DIGIT_TAHUN_LAHIR}-{INISIAL_NAMA}
     * Contoh: 2025-04-EPS
     */
    public function generateMemberCode(string $namaLengkap, ?string $tanggalLahir, $tahunMaperca, ?int $ignoreId = null): string
    {
        $yearMaper = (!empty($tahunMaperca) && is_numeric($tahunMaperca) && (int)$tahunMaperca >= 1950)
            ? (string)((int)$tahunMaperca)
            : date('Y');

        $birthTwoDigit = '00';
        if (!empty($tanggalLahir)) {
            if (preg_match('/^(\d{4})/', trim($tanggalLahir), $m)) {
                $birthTwoDigit = substr($m[1], -2);
            } else {
                $ts = strtotime($tanggalLahir);
                if ($ts !== false) {
                    $birthTwoDigit = date('y', $ts);
                }
            }
        }

        $initials = self::extractInitials($namaLengkap);
        $baseCode = "{$yearMaper}-{$birthTwoDigit}-{$initials}";

        return $this->ensureUniqueMemberCode($baseCode, $ignoreId);
    }

    /**
     * Pastikan kode ID Anggota unik pada kolom `nim`.
     */
    public function ensureUniqueMemberCode(string $baseCode, ?int $ignoreId = null): string
    {
        $candidate = strtoupper(trim($baseCode));
        $suffix = 1;
        while (true) {
            if ($ignoreId !== null) {
                $row = Database::fetchOne(
                    "SELECT `id` FROM `civitas` WHERE `nim` = :nim AND `id` <> :id LIMIT 1",
                    [':nim' => $candidate, ':id' => $ignoreId]
                );
            } else {
                $row = Database::fetchOne(
                    "SELECT `id` FROM `civitas` WHERE `nim` = :nim LIMIT 1",
                    [':nim' => $candidate]
                );
            }
            if (!$row) {
                return $candidate;
            }
            $suffix++;
            $candidate = strtoupper(trim($baseCode)) . '-' . $suffix;
        }
    }

    public function create(array $data): int
    {
        $tahunMaperca = !empty($data['tahun_maperca']) && is_numeric($data['tahun_maperca'])
            ? (int)$data['tahun_maperca']
            : (int)date('Y');
        $data['tahun_maperca'] = $tahunMaperca;

        $rawNim = trim((string)($data['nim'] ?? ''));
        if ($rawNim === '' || !preg_match('/^\d{4}-\d{2}-[A-Z0-9]+(-\d+)?$/i', $rawNim)) {
            $data['nim'] = $this->generateMemberCode(
                (string)($data['nama_lengkap'] ?? ''),
                !empty($data['tanggal_lahir']) ? (string)$data['tanggal_lahir'] : null,
                $tahunMaperca
            );
        } else {
            $data['nim'] = $this->ensureUniqueMemberCode($rawNim);
        }

        $sql = "INSERT INTO `civitas` (
                    `nim`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`,
                    `telepon`, `email`, `perguruan_tinggi`, `fakultas`, `jurusan`,
                    `komisariat`, `anggota_komisariat`, `tahun_maperca`, `tingkat_kaderisasi`, `status_keanggotaan`,
                    `alamat_padang`, `asal_civitas`, `foto_anggota`, `file_kta`, `catatan`,
                    `created_by`, `created_at`
                ) VALUES (
                    :nim, :nama_lengkap, :jenis_kelamin, :tempat_lahir, :tanggal_lahir,
                    :telepon, :email, :perguruan_tinggi, :fakultas, :jurusan,
                    :komisariat, :anggota_komisariat, :tahun_maperca, :tingkat_kaderisasi, :status_keanggotaan,
                    :alamat_padang, :asal_civitas, :foto_anggota, :file_kta, :catatan,
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
            ':asal_civitas' => $data['asal_civitas'] ?? $data['alamat_asal'] ?? null,
            ':foto_anggota' => $data['foto_anggota'] ?? null,
            ':file_kta' => $data['file_kta'] ?? null,
            ':catatan' => $data['catatan'] ?? null,
            ':created_by' => $data['created_by'] ?? null,
        ]);

        return (int)Database::lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        if (array_key_exists('alamat_asal', $data) && !array_key_exists('asal_civitas', $data)) {
            $data['asal_civitas'] = $data['alamat_asal'];
        }

        $existing = $this->findById($id);
        if ($existing) {
            $nama = (string)($data['nama_lengkap'] ?? $existing['nama_lengkap'] ?? '');
            $tglLahir = array_key_exists('tanggal_lahir', $data)
                ? (!empty($data['tanggal_lahir']) ? (string)$data['tanggal_lahir'] : null)
                : (!empty($existing['tanggal_lahir']) ? (string)$existing['tanggal_lahir'] : null);
            $thnMaper = !empty($data['tahun_maperca'])
                ? $data['tahun_maperca']
                : ($existing['tahun_maperca'] ?? date('Y'));

            $expectedBase = $this->generateMemberCode($nama, $tglLahir, $thnMaper, $id);
            $rawNim = isset($data['nim']) ? trim((string)$data['nim']) : '';
            $oldNim = trim((string)($existing['nim'] ?? ''));

            // Perbarui kode ID secara otomatis jika dikosongkan, masih memakai format lama, atau data pembentuk ID berubah
            if (
                $rawNim === '' ||
                $rawNim === $oldNim ||
                !preg_match('/^\d{4}-\d{2}-[A-Z0-9]+(-\d+)?$/i', $rawNim)
            ) {
                $data['nim'] = $expectedBase;
            } else {
                $data['nim'] = $this->ensureUniqueMemberCode($rawNim, $id);
            }
        }

        $fields = [];
        $params = [':id' => $id];

        $allowedColumns = [
            'nim', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'telepon', 'email', 'perguruan_tinggi', 'fakultas', 'jurusan',
            'komisariat', 'anggota_komisariat', 'tahun_maperca', 'tingkat_kaderisasi', 'status_keanggotaan',
            'alamat_padang', 'asal_civitas', 'foto_anggota', 'file_kta', 'catatan'
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
                       `tahun_maperca`, `tingkat_kaderisasi`, `status_keanggotaan`, `alamat_padang`, `asal_civitas`
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
