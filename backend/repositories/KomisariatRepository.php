<?php

namespace App\Repositories;

use App\Core\Database;

class KomisariatRepository
{
    public function allWithCounts(): array
    {
        $sql = "SELECT k.*,
                       (SELECT COUNT(*) FROM `civitas` c
                        WHERE c.`komisariat` = k.`nama` AND c.`tingkat_kaderisasi` <> 'Maperca') AS total_anggota
                FROM `komisariat` k
                ORDER BY k.`nama` ASC";
        return Database::fetchAll($sql);
    }

    public function names(): array
    {
        return array_column(Database::fetchAll("SELECT `nama` FROM `komisariat` ORDER BY `nama` ASC"), 'nama');
    }

    public function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM `komisariat` WHERE `id` = :id LIMIT 1", [':id' => $id]);
    }

    public function findByName(string $nama, ?int $exceptId = null): ?array
    {
        $sql = "SELECT * FROM `komisariat` WHERE `nama` = :nama";
        $params = [':nama' => $nama];
        if ($exceptId !== null) {
            $sql .= " AND `id` <> :id";
            $params[':id'] = $exceptId;
        }
        return Database::fetchOne($sql . " LIMIT 1", $params);
    }

    public function members(string $nama): array
    {
        return Database::fetchAll(
            "SELECT `id`, `nim`, `nama_lengkap`, `perguruan_tinggi`, `tingkat_kaderisasi`, `status_keanggotaan`
             FROM `civitas`
             WHERE `komisariat` = :nama AND `tingkat_kaderisasi` <> 'Maperca'
             ORDER BY `nama_lengkap` ASC",
            [':nama' => $nama]
        );
    }

    public function countMembers(string $nama): int
    {
        $row = Database::fetchOne("SELECT COUNT(*) AS c FROM `civitas` WHERE `komisariat` = :nama", [':nama' => $nama]);
        return (int)($row['c'] ?? 0);
    }

    public function create(array $data): int
    {
        Database::execute(
            "INSERT INTO `komisariat` (`nama`, `perguruan_tinggi`, `keterangan`, `created_at`)
             VALUES (:nama, :pt, :ket, NOW())",
            [':nama' => $data['nama'], ':pt' => $data['perguruan_tinggi'] ?: null, ':ket' => $data['keterangan'] ?: null]
        );
        return (int)Database::lastInsertId();
    }

    /** Ubah komisariat; nama baru ikut diterapkan ke anggota. */
    public function update(int $id, string $oldName, array $data): void
    {
        Database::transaction(function () use ($id, $oldName, $data) {
            Database::execute(
                "UPDATE `komisariat` SET `nama` = :nama, `perguruan_tinggi` = :pt, `keterangan` = :ket, `updated_at` = NOW() WHERE `id` = :id",
                [':nama' => $data['nama'], ':pt' => $data['perguruan_tinggi'] ?: null, ':ket' => $data['keterangan'] ?: null, ':id' => $id]
            );
            if ($oldName !== $data['nama']) {
                Database::execute(
                    "UPDATE `civitas` SET `komisariat` = :new WHERE `komisariat` = :old",
                    [':new' => $data['nama'], ':old' => $oldName]
                );
            }
        });
    }

    public function delete(int $id): bool
    {
        return Database::execute("DELETE FROM `komisariat` WHERE `id` = :id", [':id' => $id]);
    }
}
