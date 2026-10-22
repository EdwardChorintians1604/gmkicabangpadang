<?php

namespace App\Repositories;

use App\Core\Database;

class AuditLogRepository
{
    public function paginate(array $filters = [], int $page = 1, int $perPage = 25): array
    {
        $offset = ($page - 1) * $perPage;
        [$whereClause, $params] = $this->buildFilterConditions($filters);

        $sql = "SELECT `id`, `user_id`, `username`, `role`, `action`, `entity`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`
                FROM `audit_logs`
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
        $sql = "SELECT COUNT(*) as total FROM `audit_logs` {$whereClause}";
        $row = Database::fetchOne($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    protected function buildFilterConditions(array $filters): array
    {
        $clauses = [];
        $params = [];

        if (!empty($filters['action'])) {
            $clauses[] = "`action` = :action";
            $params[':action'] = $filters['action'];
        }

        if (!empty($filters['entity'])) {
            $clauses[] = "`entity` = :entity";
            $params[':entity'] = $filters['entity'];
        }

        if (!empty($filters['username'])) {
            $clauses[] = "`username` LIKE :username";
            $params[':username'] = '%' . $filters['username'] . '%';
        }

        if (!empty($filters['ip'])) {
            $clauses[] = "`ip_address` LIKE :ip";
            $params[':ip'] = '%' . $filters['ip'] . '%';
        }

        if (!empty($filters['date'])) {
            $clauses[] = "DATE(`created_at`) = :date";
            $params[':date'] = $filters['date'];
        }

        $whereClause = !empty($clauses) ? 'WHERE ' . implode(' AND ', $clauses) : '';
        return [$whereClause, $params];
    }

    public function getSummaryStats(): array
    {
        $db = Database::getConnection();

        // 1. Total semua log audit
        $stmt = $db->query("SELECT COUNT(*) FROM `audit_logs`");
        $totalLogs = (int)$stmt->fetchColumn();

        // 2. Aktivitas hari ini
        $stmt = $db->query("SELECT COUNT(*) FROM `audit_logs` WHERE DATE(`created_at`) = CURDATE()");
        $todayLogs = (int)$stmt->fetchColumn();

        // 3. Operasi sensitif (Mutasi data: Create, Update, Delete, Import, Backup)
        $stmt = $db->query("SELECT COUNT(*) FROM `audit_logs` 
            WHERE `action` LIKE '%CREATE%' 
               OR `action` LIKE '%UPDATE%' 
               OR `action` LIKE '%DELETE%' 
               OR `action` LIKE '%IMPORT%' 
               OR `action` LIKE '%BACKUP%'");
        $sensitiveOps = (int)$stmt->fetchColumn();

        // 4. Peristiwa Autentikasi (Login, Logout, Password)
        $stmt = $db->query("SELECT COUNT(*) FROM `audit_logs` 
            WHERE `action` IN ('LOGIN', 'LOGOUT', 'CHANGE_PASSWORD', 'AUTO_LOGOUT')");
        $authEvents = (int)$stmt->fetchColumn();

        // 5. Pengguna paling aktif (Top 4 actors)
        $stmt = $db->query("SELECT `username`, `role`, COUNT(*) as total 
            FROM `audit_logs` 
            WHERE `username` IS NOT NULL AND `username` != 'guest'
            GROUP BY `username`, `role` 
            ORDER BY total DESC LIMIT 4");
        $topActors = $stmt->fetchAll();

        // 6. Distribusi aksi terpopuler
        $stmt = $db->query("SELECT `action`, COUNT(*) as total 
            FROM `audit_logs` 
            GROUP BY `action` 
            ORDER BY total DESC LIMIT 6");
        $actionStats = $stmt->fetchAll();

        return [
            'total_logs' => $totalLogs,
            'today_logs' => $todayLogs,
            'sensitive_ops' => $sensitiveOps,
            'auth_events' => $authEvents,
            'top_actors' => $topActors,
            'action_stats' => $actionStats,
        ];
    }

    public function getForExport(array $filters = [], int $limit = 2000): array
    {
        [$whereClause, $params] = $this->buildFilterConditions($filters);

        $sql = "SELECT `id`, `created_at`, `username`, `role`, `action`, `entity`, `entity_id`, `details`, `ip_address`, `user_agent`
                FROM `audit_logs`
                {$whereClause}
                ORDER BY `created_at` DESC, `id` DESC
                LIMIT :limit";

        $stmt = Database::getConnection()->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getRecent(int $limit = 10): array
    {
        $sql = "SELECT `id`, `user_id`, `username`, `role`, `action`, `entity`, `entity_id`, `ip_address`, `created_at`
                FROM `audit_logs`
                ORDER BY `created_at` DESC
                LIMIT :limit";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function record(array $data): int
    {
        $sql = "INSERT INTO `audit_logs` (`user_id`, `username`, `role`, `action`, `entity`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`)
                VALUES (:uid, :uname, :role, :action, :entity, :eid, :details, :ip, :ua, NOW())";

        Database::execute($sql, [
            ':uid' => $data['user_id'] ?? null,
            ':uname' => $data['username'] ?? 'guest',
            ':role' => $data['role'] ?? 'guest',
            ':action' => $data['action'],
            ':entity' => $data['entity'] ?? 'system',
            ':eid' => $data['entity_id'] ?? null,
            ':details' => isset($data['details']) && is_array($data['details']) ? json_encode($data['details'], JSON_UNESCAPED_UNICODE) : ($data['details'] ?? null),
            ':ip' => $data['ip_address'] ?? '127.0.0.1',
            ':ua' => substr($data['user_agent'] ?? '', 0, 255),
        ]);

        return (int)Database::lastInsertId();
    }

    public function clearOlderThan(int $days = 90): int
    {
        $sql = "DELETE FROM `audit_logs` WHERE `created_at` < DATE_SUB(NOW(), INTERVAL :days DAY)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':days', $days, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
