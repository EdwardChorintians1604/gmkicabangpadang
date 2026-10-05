<?php

namespace App\Services;

use App\Core\Auth;
use App\Core\Hash;
use App\Repositories\UserRepository;

class UserService
{
    protected UserRepository $userRepo;
    protected AuditLogService $auditLogService;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->auditLogService = new AuditLogService();
    }

    public function getUsersList(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $users = $this->userRepo->getAll($perPage, $offset);
        $total = $this->userRepo->count();
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $users,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int)$totalPages),
        ];
    }

    public function getUser(int $id): ?array
    {
        return $this->userRepo->findById($id);
    }

    public function createUser(array $data): array
    {
        // Enkripsi kata sandi
        $data['password'] = Hash::make($data['password']);
        $newId = $this->userRepo->create($data);

        $this->auditLogService->log('CREATE_USER', 'user', (string)$newId, [
            'username' => $data['username'],
            'role' => $data['role'],
        ]);

        return ['success' => true, 'id' => $newId];
    }

    public function updateUser(int $id, array $data): array
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->userRepo->update($id, $data);

        $this->auditLogService->log('UPDATE_USER', 'user', (string)$id, [
            'username' => $data['username'] ?? null,
            'role' => $data['role'] ?? null,
            'status' => $data['status'] ?? null,
        ]);

        return ['success' => true];
    }

    public function deleteUser(int $id): array
    {
        $currentUserId = Auth::id();
        if ($currentUserId === $id) {
            return ['success' => false, 'message' => 'Anda tidak dapat menghapus akun Anda sendiri saat ini.'];
        }

        $target = $this->userRepo->findById($id);
        if (!$target) {
            return ['success' => false, 'message' => 'Pengguna tidak ditemukan.'];
        }

        $this->userRepo->delete($id);

        $this->auditLogService->log('DELETE_USER', 'user', (string)$id, [
            'username' => $target['username'],
        ]);

        return ['success' => true];
    }
}
