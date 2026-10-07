<?php

namespace App\Services;

use App\Repositories\OrganizationRepository;

class OrganizationService
{
    protected OrganizationRepository $orgRepo;
    protected StorageService $storageService;
    protected AuditLogService $auditLogService;

    public function __construct()
    {
        $this->orgRepo = new OrganizationRepository();
        $this->storageService = new StorageService();
        $this->auditLogService = new AuditLogService();
    }

    public function getProfile(): array
    {
        return $this->orgRepo->getProfile();
    }

    public function updateProfile(array $data): array
    {
        $this->orgRepo->updateProfile($data);

        $this->auditLogService->log('UPDATE_ORGANIZATION_PROFILE', 'organization_profile', '1', [
            'nama_organisasi' => $data['nama_organisasi'] ?? null,
            'tema_periode' => $data['tema_periode'] ?? null,
        ]);

        return ['success' => true];
    }

    public function getStructure(bool $onlyActive = false): array
    {
        return $this->orgRepo->getStructureList($onlyActive);
    }

    public function getStructureMember(int $id): ?array
    {
        return $this->orgRepo->findStructureMember($id);
    }

    public function createStructure(array $data, ?array $fotoFile = null): array
    {
        if ($fotoFile && !empty($fotoFile['name'])) {
            if (isset($fotoFile['error']) && $fotoFile['error'] !== UPLOAD_ERR_OK && $fotoFile['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($fotoFile['error'] === UPLOAD_ERR_INI_SIZE || $fotoFile['error'] === UPLOAD_ERR_FORM_SIZE) {
                    throw new \RuntimeException('Ukuran berkas foto terlalu besar (maksimal 10MB).');
                }
                throw new \RuntimeException('Gagal mengunggah foto pengurus (Kode error: #' . $fotoFile['error'] . ').');
            }
            if (!empty($fotoFile['tmp_name']) && is_uploaded_file($fotoFile['tmp_name'])) {
                $allowedImages = ['image/jpeg', 'image/png', 'image/webp'];
                $destDir = $this->storageService->getPublicUploadDir('organisasi');
                $upload = upload_file($fotoFile, $destDir, $allowedImages, 10 * 1024 * 1024);
                if (!$upload['success']) {
                    throw new \RuntimeException($upload['error'] ?? 'Foto pengurus gagal diunggah.');
                }
                $data['foto'] = $upload['filename'];
            }
        }

        $newId = $this->orgRepo->createStructureMember($data);

        $this->auditLogService->log('CREATE_STRUCTURE_MEMBER', 'organization_structure', (string)$newId, [
            'nama' => $data['nama'],
            'jabatan' => $data['jabatan'],
        ]);

        return ['success' => true, 'id' => $newId];
    }

    public function updateStructure(int $id, array $data, ?array $fotoFile = null): array
    {
        $existing = $this->orgRepo->findStructureMember($id);
        if (!$existing) {
            return ['success' => false, 'message' => 'Pengurus tidak ditemukan.'];
        }

        if (!empty($data['hapus_foto'])) {
            $this->storageService->deletePublicFile('organisasi', $existing['foto'] ?? null);
            $data['foto'] = null;
            unset($data['hapus_foto']);
        }

        if ($fotoFile && !empty($fotoFile['name'])) {
            if (isset($fotoFile['error']) && $fotoFile['error'] !== UPLOAD_ERR_OK && $fotoFile['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($fotoFile['error'] === UPLOAD_ERR_INI_SIZE || $fotoFile['error'] === UPLOAD_ERR_FORM_SIZE) {
                    throw new \RuntimeException('Ukuran berkas foto terlalu besar (maksimal 10MB).');
                }
                throw new \RuntimeException('Gagal mengunggah foto pengurus (Kode error: #' . $fotoFile['error'] . ').');
            }
            if (!empty($fotoFile['tmp_name']) && is_uploaded_file($fotoFile['tmp_name'])) {
                $allowedImages = ['image/jpeg', 'image/png', 'image/webp'];
                $destDir = $this->storageService->getPublicUploadDir('organisasi');
                $upload = upload_file($fotoFile, $destDir, $allowedImages, 10 * 1024 * 1024);
                if (!$upload['success']) {
                    throw new \RuntimeException($upload['error'] ?? 'Foto pengurus gagal diunggah.');
                }
                $this->storageService->deletePublicFile('organisasi', $existing['foto'] ?? null);
                $data['foto'] = $upload['filename'];
            }
        }

        $this->orgRepo->updateStructureMember($id, $data);

        $this->auditLogService->log('UPDATE_STRUCTURE_MEMBER', 'organization_structure', (string)$id, [
            'nama' => $data['nama'] ?? $existing['nama'],
            'jabatan' => $data['jabatan'] ?? $existing['jabatan'],
        ]);

        return ['success' => true];
    }

    public function deleteStructure(int $id): array
    {
        $existing = $this->orgRepo->findStructureMember($id);
        if (!$existing) {
            return ['success' => false, 'message' => 'Pengurus tidak ditemukan.'];
        }

        $this->storageService->deletePublicFile('organisasi', $existing['foto'] ?? null);
        $this->orgRepo->deleteStructureMember($id);

        $this->auditLogService->log('DELETE_STRUCTURE_MEMBER', 'organization_structure', (string)$id, [
            'nama' => $existing['nama'],
            'jabatan' => $existing['jabatan'],
        ]);

        return ['success' => true];
    }
}
