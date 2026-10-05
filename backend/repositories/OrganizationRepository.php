<?php

namespace App\Repositories;

use App\Core\Database;

class OrganizationRepository
{
    public function getProfile(): array
    {
        $sql = "SELECT * FROM `profil_organisasi` ORDER BY `id` ASC LIMIT 1";
        $profile = Database::fetchOne($sql);

        if (!$profile) {
            // Default profile jika tabel kosong
            return [
                'id' => 1,
                'nama_organisasi' => 'GMKI Cabang Padang',
                'slogan' => 'Ut Omnes Unum Sint - Syalom!',
                'tema_periode' => 'Bangkitlah, Menjadi Teranglah! (Yesaya 60:1)',
                'sub_tema' => 'Memperkokoh Ketahanan Civitas dan Pelayanan di Tiga Medan Layan GMKI',
                'sejarah' => 'GMKI Cabang Padang berdiri untuk melayani gereja, perguruan tinggi, dan masyarakat.',
                'visi' => 'Terwujudnya kedamaian, keadilan, kebenaran dan kesejahteraan bagi sesama manusia.',
                'misi' => 'Mempersiapkan kader pemimpin yang berintegritas dan takut akan Tuhan.',
                'tri_panji' => "1. Tinggi Iman\n2. Tinggi Ilmu\n3. Tinggi Pengabdian",
                'panca_kegiatan' => "1. Berdoa/Beribadah\n2. Belajar\n3. Bersaksi\n4. Bersosialisasi\n5. Berjuang",
                'alamat_sekretariat' => 'Jl. Ksatria No. 12, Tarandam, Kec. Padang Timur, Padang',
                'telepon' => '+62 812-3456-7890',
                'email' => 'sekretariat@gmkicabangpadang.or.id',
                'instagram' => '@gmkicabangpadang',
                'youtube' => 'GMKI Cabang Padang Official',
                'facebook' => 'GMKI Cabang Padang',
            ];
        }

        return $profile;
    }

    public function updateProfile(array $data): bool
    {
        $existing = Database::fetchOne("SELECT id FROM `profil_organisasi` LIMIT 1");

        if (!$existing) {
            $sql = "INSERT INTO `profil_organisasi` (
                        `nama_organisasi`, `slogan`, `tema_periode`, `sub_tema`, `sejarah`,
                        `visi`, `misi`, `tri_panji`, `panca_kegiatan`, `alamat_sekretariat`,
                        `telepon`, `email`, `instagram`, `youtube`, `facebook`, `updated_at`
                    ) VALUES (
                        :nama_organisasi, :slogan, :tema_periode, :sub_tema, :sejarah,
                        :visi, :misi, :tri_panji, :panca_kegiatan, :alamat_sekretariat,
                        :telepon, :email, :instagram, :youtube, :facebook, NOW()
                    )";
            return Database::execute($sql, [
                ':nama_organisasi' => $data['nama_organisasi'] ?? 'GMKI Cabang Padang',
                ':slogan' => $data['slogan'] ?? '',
                ':tema_periode' => $data['tema_periode'] ?? '',
                ':sub_tema' => $data['sub_tema'] ?? '',
                ':sejarah' => $data['sejarah'] ?? '',
                ':visi' => $data['visi'] ?? '',
                ':misi' => $data['misi'] ?? '',
                ':tri_panji' => $data['tri_panji'] ?? '',
                ':panca_kegiatan' => $data['panca_kegiatan'] ?? '',
                ':alamat_sekretariat' => $data['alamat_sekretariat'] ?? '',
                ':telepon' => $data['telepon'] ?? '',
                ':email' => $data['email'] ?? '',
                ':instagram' => $data['instagram'] ?? '',
                ':youtube' => $data['youtube'] ?? '',
                ':facebook' => $data['facebook'] ?? '',
            ]);
        }

        $sql = "UPDATE `profil_organisasi` SET 
                    `nama_organisasi` = :nama_organisasi,
                    `slogan` = :slogan,
                    `tema_periode` = :tema_periode,
                    `sub_tema` = :sub_tema,
                    `sejarah` = :sejarah,
                    `visi` = :visi,
                    `misi` = :misi,
                    `tri_panji` = :tri_panji,
                    `panca_kegiatan` = :panca_kegiatan,
                    `alamat_sekretariat` = :alamat_sekretariat,
                    `telepon` = :telepon,
                    `email` = :email,
                    `instagram` = :instagram,
                    `youtube` = :youtube,
                    `facebook` = :facebook,
                    `updated_at` = NOW()
                WHERE `id` = :id";

        return Database::execute($sql, [
            ':id' => $existing['id'],
            ':nama_organisasi' => $data['nama_organisasi'],
            ':slogan' => $data['slogan'],
            ':tema_periode' => $data['tema_periode'],
            ':sub_tema' => $data['sub_tema'],
            ':sejarah' => $data['sejarah'],
            ':visi' => $data['visi'],
            ':misi' => $data['misi'],
            ':tri_panji' => $data['tri_panji'],
            ':panca_kegiatan' => $data['panca_kegiatan'],
            ':alamat_sekretariat' => $data['alamat_sekretariat'],
            ':telepon' => $data['telepon'],
            ':email' => $data['email'],
            ':instagram' => $data['instagram'],
            ':youtube' => $data['youtube'],
            ':facebook' => $data['facebook'],
        ]);
    }

    public function getStructureList(bool $onlyActive = false): array
    {
        $sql = "SELECT * FROM `struktur_organisasi`";
        if ($onlyActive) {
            $sql .= " WHERE `status_aktif` = 1";
        }
        $sql .= " ORDER BY `urutan` ASC, `id` ASC";
        return Database::fetchAll($sql);
    }

    public function findStructureMember(int $id): ?array
    {
        $sql = "SELECT * FROM `struktur_organisasi` WHERE `id` = :id LIMIT 1";
        return Database::fetchOne($sql, [':id' => $id]);
    }

    public function createStructureMember(array $data): int
    {
        $sql = "INSERT INTO `struktur_organisasi` (
                    `nama`, `jabatan`, `bidang`, `periode`, `urutan`, `foto`, `telepon`, `status_aktif`, `created_at`
                ) VALUES (
                    :nama, :jabatan, :bidang, :periode, :urutan, :foto, :telepon, :status_aktif, NOW()
                )";

        Database::execute($sql, [
            ':nama' => $data['nama'],
            ':jabatan' => $data['jabatan'],
            ':bidang' => $data['bidang'] ?? 'Badan Pengurus Harian',
            ':periode' => $data['periode'] ?? '2024-2026',
            ':urutan' => (int)($data['urutan'] ?? 0),
            ':foto' => $data['foto'] ?? null,
            ':telepon' => $data['telepon'] ?? null,
            ':status_aktif' => isset($data['status_aktif']) ? (int)$data['status_aktif'] : 1,
        ]);

        return (int)Database::lastInsertId();
    }

    public function updateStructureMember(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        $allowedColumns = ['nama', 'jabatan', 'bidang', 'periode', 'urutan', 'foto', 'telepon', 'status_aktif'];

        foreach ($allowedColumns as $col) {
            if (array_key_exists($col, $data)) {
                $fields[] = "`{$col}` = :{$col}";
                $params[":{$col}"] = $data[$col];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE `struktur_organisasi` SET " . implode(', ', $fields) . ", `updated_at` = NOW() WHERE `id` = :id";
        return Database::execute($sql, $params);
    }

    public function deleteStructureMember(int $id): bool
    {
        $sql = "DELETE FROM `struktur_organisasi` WHERE `id` = :id";
        return Database::execute($sql, [':id' => $id]);
    }
}
