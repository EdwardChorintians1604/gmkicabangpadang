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
        if ($fotoFile && !empty($fotoFile['tmp_name'])) {
            $allowedImages = ['image/jpeg', 'image/png', 'image/webp'];
            $destDir = $this->storageService->getPublicUploadDir('organisasi');
            $upload = upload_file($fotoFile, $destDir, $allowedImages, 4 * 1024 * 1024);
            if ($upload['success']) {
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

        if ($fotoFile && !empty($fotoFile['tmp_name'])) {
            $allowedImages = ['image/jpeg', 'image/png', 'image/webp'];
            $destDir = $this->storageService->getPublicUploadDir('organisasi');
            $upload = upload_file($fotoFile, $destDir, $allowedImages, 4 * 1024 * 1024);
            if ($upload['success']) {
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
