<?php

namespace App\Services;

use App\Core\Auth;
use App\Repositories\AccessLogRepository;

class AccessLogService
{
    protected AccessLogRepository $accessRepo;

    public function __construct()
    {
        $this->accessRepo = new AccessLogRepository();
    }

    /**
     * Catat request yang baru saja mengakses website
     */
    public function logCurrentRequest(int $statusCode = 200): ?int
    {
        try {
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            $path = parse_url($uri, PHP_URL_PATH) ?: '/';

            // Abaikan request untuk file statis jika ada yang lolos ke router
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($extension, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'map'])) {
                return null;
            }

            $ip = $this->getClientIp();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
            $deviceInfo = DeviceDetector::parse($userAgent);

            $currentUser = Auth::user();
            $userId = $currentUser['id'] ?? null;
            $username = $currentUser['nama_lengkap'] ?? ($currentUser['username'] ?? 'Tamu Publik');
            $role = $currentUser['role'] ?? 'guest';

            $data = [
                'ip_address' => $ip,
                'user_id' => $userId,
                'username' => $username,
                'role' => $role,
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
                'path' => $path,
                'status_code' => $statusCode,
                'device_type' => $deviceInfo['device_type'],
                'platform' => $deviceInfo['platform'],
                'browser' => $deviceInfo['browser'],
                'user_agent' => $userAgent,
                'referer' => $_SERVER['HTTP_REFERER'] ?? null,
            ];

            return $this->accessRepo->record($data);
        } catch (\Throwable $e) {
            // Catat ke error log tanpa mengganggu eksekusi aplikasi utama
            error_log("Gagal merekam audit log akses website: " . $e->getMessage());
            return null;
        }
    }

    public function getLogs(array $filters = [], int $page = 1, int $perPage = 25): array
    {
        $items = $this->accessRepo->paginate($filters, $page, $perPage);
        $total = $this->accessRepo->count($filters);
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $items,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int)$totalPages),
        ];
    }

    public function getSummaryStats(): array
    {
        return $this->accessRepo->getSummaryStats();
    }

    /**
     * Dapatkan IP asli pengunjung termasuk dibelakang reverse proxy / Ngrok / Cloudflare
     */
    protected function getClientIp(): string
    {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return trim($_SERVER['HTTP_CF_CONNECTING_IP']);
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return trim($_SERVER['HTTP_CLIENT_IP']);
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}
