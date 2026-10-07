<?php

declare(strict_types=1);

namespace App\Core;

class Queue
{
    public static function dispatchFinanceExport(int $ownerId, string $scope): int
    {
        if (!in_array($scope, ['ketcab', 'bencab', 'admin'], true)) {
            throw new \InvalidArgumentException('Cakupan ekspor laporan tidak valid.');
        }

        Database::execute(
            "INSERT INTO background_jobs (owner_id, job_type, payload, available_at, created_at) VALUES (?, 'finance_export', ?, NOW(), NOW())",
            [$ownerId, json_encode(['scope' => $scope], JSON_THROW_ON_ERROR)]
        );

        return (int)Database::lastInsertId();
    }
}
