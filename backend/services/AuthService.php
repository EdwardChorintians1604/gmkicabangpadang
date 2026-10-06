<?php

namespace App\Services;

use App\Core\Auth;
use App\Core\Hash;
use App\Repositories\SecurityRepository;
use App\Repositories\UserRepository;

class AuthService
{
    protected UserRepository $userRepo;
    protected SecurityRepository $securityRepo;
    protected AuditLogService $auditLogService;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->securityRepo = new SecurityRepository();
        $this->auditLogService = new AuditLogService();
    }

    public function authenticate(string $username, string $password, string $ip, string $userAgent): array
    {
        // 1. Cek proteksi Brute Force (Rate Limit)
        $failedAttempts = $this->securityRepo->countRecentFailedAttempts($ip, 900);
        if ($failedAttempts >= 5) {
            $this->securityRepo->recordThreat([
                'threat_type' => 'BRUTE_FORCE_LOGIN',
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'payload' => "Username attempted: {$username}",
                'severity' => 'high',
            ]);

            return [
                'success' => false,
                'message' => 'Terlalu banyak percobaan masuk yang gagal. Akses dibatasi selama 15 menit.',
            ];
        }

        // 2. Cari pengguna berdasarkan username atau email via ORM / QueryBuilder
        $user = $this->userRepo->findForAuthentication($username);

        // 3. Verifikasi pengguna dan kata sandi
        if (!$user || !Hash::check($password, $user['password'])) {
            $this->securityRepo->recordLoginAttempt($ip, $username, false, $userAgent);
            return [
                'success' => false,
                'message' => 'Username atau kata sandi tidak cocok.',
            ];
        }

        // 4. Periksa apakah akun berstatus aktif
        if ($user['status'] !== 'aktif') {
            return [
                'success' => false,
                'message' => 'Akun Anda dinonaktifkan oleh administrator.',
            ];
        }

        // 5. Login berhasil: perbarui catatan sesi & bersihkan rate limit
        $this->securityRepo->recordLoginAttempt($ip, $username, true, $userAgent);
        $this->securityRepo->clearLoginAttempts($ip);
        $this->userRepo->updateLastLogin($user['id'], $ip);

        // Jika hash memerlukan rehash otomatis
        if (Hash::needsRehash($user['password'])) {
            $this->userRepo->update($user['id'], ['password' => Hash::make($password)]);
        }

        Auth::login($user);

        // Catat Audit Trail
        $this->auditLogService->log(
            'LOGIN',
            'auth',
            (string)$user['id'],
            ['username' => $user['username'], 'role' => $user['role']],
            $user['id'],
            $user['username'],
            $user['role'],
            $ip,
            $userAgent
        );

        return [
            'success' => true,
            'user' => $user,
        ];
    }

    public function logout(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->auditLogService->log(
                'LOGOUT',
                'auth',
                (string)$user['id'],
                ['username' => $user['username']]
            );
        }
        Auth::logout();
    }

    public function changePassword(int $userId, string $oldPassword, string $newPassword): array
    {
        $user = $this->userRepo->findById($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'Pengguna tidak ditemukan.'];
        }

        if (!Hash::check($oldPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Kata sandi lama tidak sesuai.'];
        }

        $this->userRepo->update($userId, [
            'password' => Hash::make($newPassword),
        ]);

        $this->auditLogService->log('CHANGE_PASSWORD', 'user', (string)$userId);

        return ['success' => true, 'message' => 'Kata sandi berhasil diperbarui.'];
    }
}
