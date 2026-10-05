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
            ':status' => $data['status'] ?? 'detected',
        ]);

        return (int)Database::lastInsertId();
    }

    public function paginateThreats(int $page = 1, int $perPage = 25): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM `security_threats` ORDER BY `created_at` DESC LIMIT :limit OFFSET :offset";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countThreats(): int
    {
        $sql = "SELECT COUNT(*) as total FROM `security_threats`";
        $row = Database::fetchOne($sql);
        return (int)($row['total'] ?? 0);
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

    public function resolveThreat(int $id): bool
    {
        $sql = "UPDATE `security_threats` SET `status` = 'resolved' WHERE `id` = :id";
        return Database::execute($sql, [':id' => $id]);
    }
}
