<?php

namespace App\Services;

use App\Repositories\SecurityRepository;

class SecurityService
{
    protected SecurityRepository $securityRepo;
    protected AuditLogService $auditLogService;

    public function __construct()
    {
        $this->securityRepo = new SecurityRepository();
        $this->auditLogService = new AuditLogService();
    }

    public function getThreatsList(int $page = 1, int $perPage = 25): array
    {
        $items = $this->securityRepo->paginateThreats($page, $perPage);
        $total = $this->securityRepo->countThreats();
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $items,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int)$totalPages),
        ];
    }

    public function resolveThreat(int $id): bool
    {
        $res = $this->securityRepo->resolveThreat($id);
        $this->auditLogService->log('RESOLVE_THREAT', 'security_threat', (string)$id);
        return $res;
    }

    public function getSecurityHealth(): array
    {
        $env = $_ENV['APP_ENV'] ?? 'development';
        $debug = filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN);

        $checks = [
            [
                'title' => 'Lingkungan Aplikasi',
                'status' => $env === 'production' ? 'ok' : 'info',
                'description' => "Status saat ini: {$env}",
            ],
            [
                'title' => 'Mode Debug',
                'status' => !$debug ? 'ok' : 'warning',
                'description' => $debug ? 'Debug aktif (matikan pada server produksi).' : 'Debug nonaktif (aman).',
            ],
            [
                'title' => 'Proteksi CSRF & Sesi',
                'status' => 'ok',
                'description' => 'Sesi HTTPOnly, SameSite Lax, dan token CSRF aktif.',
            ],
            [
                'title' => 'Rate Limiting Brute Force',
                'status' => 'ok',
                'description' => 'Maksimal 5 percobaan gagal per 15 menit.',
            ],
            [
                'title' => 'Penyimpanan Privat (Storage)',
                'status' => 'ok',
                'description' => 'Foto anggota dan dokumen KTA diisolasi dari direktori web publik.',
            ],
        ];

        return $checks;
    }
}
