<?php

namespace App\Repositories;

use App\Core\Database;

class NewsRepository
{
    public function paginate(array $filters = [], int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        [$whereClause, $params] = $this->buildFilterConditions($filters);

        $sql = "SELECT `id`, `judul`, `slug`, `kategori`, `ringkasan`, `gambar_sampul`, 
                       `user_id`, `penulis_nama`, `status`, `views`, `published_at`, `created_at`
                FROM `berita`
                {$whereClause}
                ORDER BY `published_at` DESC, `id` DESC
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
        $sql = "SELECT COUNT(*) as total FROM `berita` {$whereClause}";
        $row = Database::fetchOne($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    protected function buildFilterConditions(array $filters): array
    {
        $clauses = [];
        $params = [];

        if (!empty($filters['status'])) {
            $clauses[] = "`status` = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['kategori'])) {
            $clauses[] = "`kategori` = :kategori";
            $params[':kategori'] = $filters['kategori'];
        }

        if (!empty($filters['search'])) {
            $clauses[] = "(`judul` LIKE :search1 OR `ringkasan` LIKE :search2 OR `konten` LIKE :search3)";
            $term = '%' . $filters['search'] . '%';
            $params[':search1'] = $term;
            $params[':search2'] = $term;
            $params[':search3'] = $term;
        }

        $whereClause = !empty($clauses) ? 'WHERE ' . implode(' AND ', $clauses) : '';
        return [$whereClause, $params];
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM `berita` WHERE `id` = :id LIMIT 1";
        return Database::fetchOne($sql, [':id' => $id]);
    }

    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM `berita` WHERE `slug` = :slug LIMIT 1";
        return Database::fetchOne($sql, [':slug' => $slug]);
    }

    public function getLatestPublished(int $limit = 6, ?int $excludeId = null): array
    {
        $sql = "SELECT `id`, `judul`, `slug`, `kategori`, `ringkasan`, `gambar_sampul`, 
                       `penulis_nama`, `views`, `published_at`
                FROM `berita`
                WHERE `status` = 'published'";

        $params = [];
        if ($excludeId !== null) {
            $sql .= " AND `id` != :excludeId";
            $params[':excludeId'] = $excludeId;
        }

        $sql .= " ORDER BY `published_at` DESC LIMIT :limit";

        $stmt = Database::getConnection()->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO `berita` (
                    `judul`, `slug`, `kategori`, `ringkasan`, `konten`,
                    `gambar_sampul`, `user_id`, `penulis_nama`, `status`,
                    `views`, `published_at`, `created_at`
                ) VALUES (
                    :judul, :slug, :kategori, :ringkasan, :konten,
                    :gambar_sampul, :user_id, :penulis_nama, :status,
                    0, :published_at, NOW()
                )";

        $publishedAt = ($data['status'] === 'published') ? ($data['published_at'] ?? date('Y-m-d H:i:s')) : null;

        Database::execute($sql, [
            ':judul' => $data['judul'],
            ':slug' => $data['slug'],
            ':kategori' => $data['kategori'] ?? 'Warta Cabang',
            ':ringkasan' => $data['ringkasan'] ?? null,
            ':konten' => $data['konten'],
            ':gambar_sampul' => $data['gambar_sampul'] ?? null,
            ':user_id' => $data['user_id'] ?? null,
            ':penulis_nama' => $data['penulis_nama'] ?? 'BPC GMKI Padang',
            ':status' => $data['status'] ?? 'published',
            ':published_at' => $publishedAt,
        ]);

        return (int)Database::lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        $allowedColumns = ['judul', 'slug', 'kategori', 'ringkasan', 'konten', 'gambar_sampul', 'penulis_nama', 'status', 'published_at'];

        foreach ($allowedColumns as $col) {
            if (array_key_exists($col, $data)) {
                $fields[] = "`{$col}` = :{$col}";
                $params[":{$col}"] = $data[$col];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE `berita` SET " . implode(', ', $fields) . ", `updated_at` = NOW() WHERE `id` = :id";
        return Database::execute($sql, $params);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM `berita` WHERE `id` = :id";
        return Database::execute($sql, [':id' => $id]);
    }

    public function incrementViews(int $id): bool
    {
        $sql = "UPDATE `berita` SET `views` = `views` + 1 WHERE `id` = :id";
        return Database::execute($sql, [':id' => $id]);
    }

    public function getCategories(): array
    {
        $sql = "SELECT DISTINCT `kategori`, COUNT(*) as total FROM `berita` WHERE `status` = 'published' GROUP BY `kategori`";
        return Database::fetchAll($sql);
    }
}
