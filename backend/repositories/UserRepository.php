<?php

namespace App\Repositories;

use App\Core\Database;

class UserRepository
{
    public function findById(int $id): ?array
    {
        $sql = "SELECT `id`, `username`, `email`, `password`, `nama_lengkap`, `role`, `status`, `remember_token`, `last_login_at`, `last_login_ip`, `created_at`, `updated_at`
                FROM `users` WHERE `id` = :id LIMIT 1";
        return Database::fetchOne($sql, [':id' => $id]);
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT `id`, `username`, `email`, `password`, `nama_lengkap`, `role`, `status`, `remember_token`, `last_login_at`, `last_login_ip`, `created_at`, `updated_at`
                FROM `users` WHERE `username` = :username LIMIT 1";
        return Database::fetchOne($sql, [':username' => $username]);
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT `id`, `username`, `email`, `password`, `nama_lengkap`, `role`, `status`, `remember_token`, `last_login_at`, `last_login_ip`, `created_at`, `updated_at`
                FROM `users` WHERE `email` = :email LIMIT 1";
        return Database::fetchOne($sql, [':email' => $email]);
    }

    public function getAll(int $limit = 100, int $offset = 0): array
    {
        $sql = "SELECT `id`, `username`, `email`, `nama_lengkap`, `role`, `status`, `last_login_at`, `last_login_ip`, `created_at`
                FROM `users` ORDER BY `id` ASC LIMIT :limit OFFSET :offset";
        
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM `users`";
        $row = Database::fetchOne($sql);
        return (int)($row['total'] ?? 0);
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO `users` (`username`, `email`, `password`, `nama_lengkap`, `role`, `status`, `created_at`)
                VALUES (:username, :email, :password, :nama_lengkap, :role, :status, NOW())";

        Database::execute($sql, [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':nama_lengkap' => $data['nama_lengkap'],
            ':role' => $data['role'] ?? 'operator',
            ':status' => $data['status'] ?? 'aktif',
        ]);

        return (int)Database::lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['nama_lengkap'])) {
            $fields[] = "`nama_lengkap` = :nama_lengkap";
            $params[':nama_lengkap'] = $data['nama_lengkap'];
        }
        if (isset($data['email'])) {
            $fields[] = "`email` = :email";
            $params[':email'] = $data['email'];
        }
        if (isset($data['role'])) {
            $fields[] = "`role` = :role";
            $params[':role'] = $data['role'];
        }
        if (isset($data['status'])) {
            $fields[] = "`status` = :status";
            $params[':status'] = $data['status'];
        }
        if (isset($data['password'])) {
            $fields[] = "`password` = :password";
            $params[':password'] = $data['password'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE `users` SET " . implode(', ', $fields) . ", `updated_at` = NOW() WHERE `id` = :id";
        return Database::execute($sql, $params);
    }

    public function updateLastLogin(int $id, string $ip): bool
    {
        $sql = "UPDATE `users` SET `last_login_at` = NOW(), `last_login_ip` = :ip WHERE `id` = :id";
        return Database::execute($sql, [':id' => $id, ':ip' => $ip]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM `users` WHERE `id` = :id";
        return Database::execute($sql, [':id' => $id]);
    }
}
