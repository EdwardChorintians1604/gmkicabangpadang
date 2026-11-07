<?php

namespace App\Repositories;

use App\Core\Database;

class SecurityRepository
{
    public function recordThreat(array $data): int
    {
        $sql = "INSERT INTO `security_threats` (`threat_type`, `ip_address`, `user_agent`, `payload`, `severity`, `status`, `created_at`)
                VALUES (:type, :ip, :ua, :payload, :severity, :status, NOW())";

        Database::execute($sql, [
            ':type' => $data['threat_type'],
            ':ip' => $data['ip_address'],
            ':ua' => substr($data['user_agent'] ?? '', 0, 255),
            ':payload' => isset($data['payload']) ? substr($data['payload'], 0, 1000) : null,
            ':severity' => $data['severity'] ?? 'medium',
            ':status' => $data['status'] ?? 'blocked',
        ]);

        return (int)Database::lastInsertId();
    }

    protected function buildThreatFilterClause(array $filters, array &$params): string
    {
        $where = [];

        if (!empty($filters['q'])) {
            $where[] = "(`threat_type` LIKE :q OR `ip_address` LIKE :q2 OR `payload` LIKE :q3 OR `user_agent` LIKE :q4)";
            $kw = '%' . trim((string)$filters['q']) . '%';
            $params[':q'] = $kw;
            $params[':q2'] = $kw;
            $params[':q3'] = $kw;
            $params[':q4'] = $kw;
        }

        if (!empty($filters['severity'])) {
            $where[] = "`severity` = :severity";
            $params[':severity'] = (string)$filters['severity'];
        }

        if (!empty($filters['status'])) {
            $where[] = "`status` = :status";
            $params[':status'] = (string)$filters['status'];
        }

        return !empty($where) ? (' WHERE ' . implode(' AND ', $where)) : '';
    }

    public function paginateThreats(int $page = 1, int $perPage = 25, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $params = [];
        $whereSql = $this->buildThreatFilterClause($filters, $params);

        $sql = "SELECT * FROM `security_threats`{$whereSql} ORDER BY `created_at` DESC, `id` DESC LIMIT :limit OFFSET :offset";

        $stmt = Database::getConnection()->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countThreats(array $filters = []): int
    {
        $params = [];
        $whereSql = $this->buildThreatFilterClause($filters, $params);
        $sql = "SELECT COUNT(*) as total FROM `security_threats`{$whereSql}";
        $row = Database::fetchOne($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    public function getThreatById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM `security_threats` WHERE `id` = :id LIMIT 1", [':id' => $id]);
    }

    public function getThreatSummaryStats(): array
    {
        $stats = [
            'total' => 0,
            'critical' => 0,
            'blocked' => 0,
            'detected' => 0,
            'resolved' => 0,
            'recent_24h' => 0,
            'failed_logins_24h' => 0,
            'top_ips' => [],
        ];

        try {
            $row = Database::fetchOne("
                SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN `severity` IN ('critical', 'high') THEN 1 ELSE 0 END) AS critical_count,
                    SUM(CASE WHEN `status` = 'blocked' THEN 1 ELSE 0 END) AS blocked_count,
                    SUM(CASE WHEN `status` = 'detected' THEN 1 ELSE 0 END) AS detected_count,
                    SUM(CASE WHEN `status` = 'resolved' THEN 1 ELSE 0 END) AS resolved_count,
                    SUM(CASE WHEN `created_at` >= DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN 1 ELSE 0 END) AS recent_24h
                FROM `security_threats`
            ");

            if ($row) {
                $stats['total'] = (int)($row['total'] ?? 0);
                $stats['critical'] = (int)($row['critical_count'] ?? 0);
                $stats['blocked'] = (int)($row['blocked_count'] ?? 0);
                $stats['detected'] = (int)($row['detected_count'] ?? 0);
                $stats['resolved'] = (int)($row['resolved_count'] ?? 0);
                $stats['recent_24h'] = (int)($row['recent_24h'] ?? 0);
            }

            $loginRow = Database::fetchOne("
                SELECT COUNT(*) AS failed_24h 
                FROM `login_attempts` 
                WHERE `is_success` = 0 AND `attempted_at` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            $stats['failed_logins_24h'] = (int)($loginRow['failed_24h'] ?? 0);

            $stats['top_ips'] = Database::fetchAll("
                SELECT 
                    `ip_address`,
                    COUNT(*) AS hit_count,
                    MAX(`threat_type`) AS last_threat_type,
                    MAX(`severity`) AS max_severity,
                    MAX(`created_at`) AS last_seen
                FROM `security_threats`
                GROUP BY `ip_address`
                ORDER BY hit_count DESC, last_seen DESC
                LIMIT 6
            ");
        } catch (\Throwable $e) {
            error_log("Threat summary query error: " . $e->getMessage());
        }

        return $stats;
    }

    public function recordLoginAttempt(string $ip, string $username, bool $isSuccess, string $userAgent): void
    {
        $sql = "INSERT INTO `login_attempts` (`ip_address`, `username`, `is_success`, `user_agent`, `attempted_at`)
                VALUES (:ip, :username, :success, :ua, NOW())";

        Database::execute($sql, [
            ':ip' => $ip,
            ':username' => $username,
            ':success' => $isSuccess ? 1 : 0,
            ':ua' => substr($userAgent, 0, 255),
        ]);
    }

    public function countRecentFailedAttempts(string $ip, int $seconds = 900): int
    {
        $sql = "SELECT COUNT(*) as total FROM `login_attempts`
                WHERE `ip_address` = :ip 
                  AND `is_success` = 0 
                  AND `attempted_at` >= DATE_SUB(NOW(), INTERVAL :sec SECOND)";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':ip', $ip);
        $stmt->bindValue(':sec', $seconds, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return (int)($row['total'] ?? 0);
    }

    public function clearLoginAttempts(string $ip): void
    {
        $sql = "DELETE FROM `login_attempts` WHERE `ip_address` = :ip";
        Database::execute($sql, [':ip' => $ip]);
    }

    public function clearAllFailedLoginAttempts(): bool
    {
        return Database::execute("DELETE FROM `login_attempts` WHERE `is_success` = 0");
    }

    public function resolveThreat(int $id): bool
    {
        $sql = "UPDATE `security_threats` SET `status` = 'resolved' WHERE `id` = :id";
        return Database::execute($sql, [':id' => $id]);
    }

    public function resolveAllThreats(): bool
    {
        return Database::execute("UPDATE `security_threats` SET `status` = 'resolved' WHERE `status` != 'resolved'");
    }

    public function deleteThreat(int $id): bool
    {
        return Database::execute("DELETE FROM `security_threats` WHERE `id` = :id", [':id' => $id]);
    }
}
