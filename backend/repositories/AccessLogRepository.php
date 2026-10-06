<?php

namespace App\Repositories;

use App\Core\Database;

class AccessLogRepository
{
    public function record(array $data): int
    {
        $sql = "INSERT INTO `website_access_logs` 
                (`ip_address`, `user_id`, `username`, `role`, `method`, `path`, `status_code`, `device_type`, `platform`, `browser`, `user_agent`, `referer`, `created_at`)
                VALUES 
                (:ip, :uid, :uname, :role, :method, :path, :status, :device, :platform, :browser, :ua, :ref, NOW())";

        Database::execute($sql, [
            ':ip' => $data['ip_address'] ?? '127.0.0.1',
            ':uid' => $data['user_id'] ?? null,
            ':uname' => $data['username'] ?? 'Tamu Publik',
            ':role' => $data['role'] ?? 'guest',
            ':method' => substr($data['method'] ?? 'GET', 0, 10),
            ':path' => substr($data['path'] ?? '/', 0, 255),
            ':status' => (int)($data['status_code'] ?? 200),
            ':device' => substr($data['device_type'] ?? 'Desktop', 0, 30),
            ':platform' => substr($data['platform'] ?? 'Unknown OS', 0, 60),
            ':browser' => substr($data['browser'] ?? 'Unknown Browser', 0, 60),
            ':ua' => $data['user_agent'] ?? null,
            ':ref' => isset($data['referer']) ? substr($data['referer'], 0, 255) : null,
        ]);

        return (int)Database::lastInsertId();
    }

    public function paginate(array $filters = [], int $page = 1, int $perPage = 25): array
    {
        $offset = ($page - 1) * $perPage;
        [$whereClause, $params] = $this->buildFilterConditions($filters);

        $sql = "SELECT `id`, `ip_address`, `user_id`, `username`, `role`, `method`, `path`, `status_code`, 
                       `device_type`, `platform`, `browser`, `user_agent`, `referer`, `created_at`
                FROM `website_access_logs`
                {$whereClause}
                ORDER BY `created_at` DESC, `id` DESC
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
        $sql = "SELECT COUNT(*) as total FROM `website_access_logs` {$whereClause}";
        $row = Database::fetchOne($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    public function getSummaryStats(): array
    {
        $db = Database::getConnection();

        // 1. Total kunjungan hari ini
        $stmt = $db->query("SELECT COUNT(*) FROM `website_access_logs` WHERE DATE(`created_at`) = CURDATE()");
        $todayVisits = (int)$stmt->fetchColumn();

        // 2. IP Unik hari ini
        $stmt = $db->query("SELECT COUNT(DISTINCT `ip_address`) FROM `website_access_logs` WHERE DATE(`created_at`) = CURDATE()");
        $uniqueIpToday = (int)$stmt->fetchColumn();

        // 3. Distribusi Perangkat (Mobile vs Desktop vs Tablet)
        $stmt = $db->query("SELECT `device_type`, COUNT(*) as total FROM `website_access_logs` GROUP BY `device_type` ORDER BY total DESC");
        $deviceBreakdown = $stmt->fetchAll();

        // 4. Pengguna Login vs Tamu Publik hari ini
        $stmt = $db->query("SELECT 
            SUM(CASE WHEN `user_id` IS NOT NULL THEN 1 ELSE 0 END) as user_logged_in,
            SUM(CASE WHEN `user_id` IS NULL THEN 1 ELSE 0 END) as guest_visitors
            FROM `website_access_logs` WHERE DATE(`created_at`) = CURDATE()");
        $userDistribution = $stmt->fetch() ?: ['user_logged_in' => 0, 'guest_visitors' => 0];

        // 5. Total keseluruhan log
        $stmt = $db->query("SELECT COUNT(*) FROM `website_access_logs`");
        $allTimeTotal = (int)$stmt->fetchColumn();

        return [
            'today_visits' => $todayVisits,
            'unique_ip_today' => $uniqueIpToday,
            'device_breakdown' => $deviceBreakdown,
            'user_logged_in' => (int)($userDistribution['user_logged_in'] ?? 0),
            'guest_visitors' => (int)($userDistribution['guest_visitors'] ?? 0),
            'all_time_total' => $allTimeTotal,
        ];
    }

    protected function buildFilterConditions(array $filters): array
    {
        $clauses = [];
        $params = [];

        if (!empty($filters['ip'])) {
            $clauses[] = "`ip_address` LIKE :ip";
            $params[':ip'] = '%' . $filters['ip'] . '%';
        }

        if (!empty($filters['username'])) {
            $clauses[] = "`username` LIKE :username";
            $params[':username'] = '%' . $filters['username'] . '%';
        }

        if (!empty($filters['device_type'])) {
            $clauses[] = "`device_type` = :device_type";
            $params[':device_type'] = $filters['device_type'];
        }

        if (!empty($filters['role'])) {
            $clauses[] = "`role` = :role";
            $params[':role'] = $filters['role'];
        }

        if (!empty($filters['path'])) {
            $clauses[] = "`path` LIKE :path";
            $params[':path'] = '%' . $filters['path'] . '%';
        }

        $whereClause = !empty($clauses) ? 'WHERE ' . implode(' AND ', $clauses) : '';
        return [$whereClause, $params];
    }
}
