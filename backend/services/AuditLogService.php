<?php

namespace App\Services;

use App\Core\Auth;
use App\Repositories\AuditLogRepository;

class AuditLogService
{
    protected AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->auditRepo = new AuditLogRepository();
    }

    public function log(
        string $action,
        string $entity,
        ?string $entityId = null,
        ?array $details = null,
        ?int $userId = null,
        ?string $username = null,
        ?string $role = null,
        ?string $ip = null,
        ?string $userAgent = null
    ): int {
        $currentUser = Auth::user();

        $data = [
            'user_id' => $userId ?? ($currentUser['id'] ?? null),
            'username' => $username ?? ($currentUser['username'] ?? 'guest'),
            'role' => $role ?? ($currentUser['role'] ?? 'guest'),
            'action' => strtoupper($action),
            'entity' => $entity,
            'entity_id' => $entityId,
            'details' => $details,
            'ip_address' => $ip ?? ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'),
            'user_agent' => $userAgent ?? ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'),
        ];

        return $this->auditRepo->record($data);
    }

    public function getLogs(array $filters = [], int $page = 1, int $perPage = 25): array
    {
        $items = $this->auditRepo->paginate($filters, $page, $perPage);
        $total = $this->auditRepo->count($filters);
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $items,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int)$totalPages),
        ];
    }

    public function getRecent(int $limit = 10): array
    {
        return $this->auditRepo->getRecent($limit);
    }
}
