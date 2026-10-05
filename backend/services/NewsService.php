<?php

namespace App\Services;

use App\Core\Auth;
use App\Repositories\NewsRepository;

class NewsService
{
    protected NewsRepository $newsRepo;
    protected StorageService $storageService;
    protected AuditLogService $auditLogService;

    public function __construct()
    {
        $this->newsRepo = new NewsRepository();
        $this->storageService = new StorageService();
        $this->auditLogService = new AuditLogService();
    }

    public function getPublicList(array $filters = [], int $page = 1, int $perPage = 6): array
    {
        $filters['status'] = 'published';
        $items = $this->newsRepo->paginate($filters, $page, $perPage);
        $total = $this->newsRepo->count($filters);
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $items,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int)$totalPages),
        ];
    }

    public function getAdminList(array $filters = [], int $page = 1, int $perPage = 15): array
    {
        $items = $this->newsRepo->paginate($filters, $page, $perPage);
        $total = $this->newsRepo->count($filters);
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $items,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int)$totalPages),
        ];
    }

    public function getBySlug(string $slug, bool $incrementViews = false): ?array
    {
        $news = $this->newsRepo->findBySlug($slug);
        if ($news && $incrementViews) {
            $this->newsRepo->incrementViews($news['id']);
            $news['views']++;
        }
        return $news;
    }

    public function getById(int $id): ?array
    {
        return $this->newsRepo->findById($id);
    }

    public function getLatest(int $limit = 5, ?int $excludeId = null): array
    {
        return $this->newsRepo->getLatestPublished($limit, $excludeId);
    }

    public function create(array $data, ?array $coverFile = null): array
    {
        $user = Auth::user();
        $data['user_id'] = $user['id'] ?? null;
        if (empty($data['penulis_nama'])) {
            $data['penulis_nama'] = $user['nama_lengkap'] ?? 'BPC GMKI Padang';
        }

        // Slug otomatis
        $data['slug'] = $this->generateUniqueSlug($data['judul']);

        // Upload Gambar Sampul
        if ($coverFile && !empty($coverFile['tmp_name'])) {
            $allowedImages = ['image/jpeg', 'image/png', 'image/webp'];
            $destDir = $this->storageService->getPublicUploadDir('berita');
            $upload = upload_file($coverFile, $destDir, $allowedImages, 4 * 1024 * 1024);
            if ($upload['success']) {
                $data['gambar_sampul'] = $upload['filename'];
            }
        }

        if (empty($data['ringkasan'])) {
            $plain = strip_tags($data['konten']);
            $data['ringkasan'] = mb_substr($plain, 0, 180) . '...';
        }

        $newId = $this->newsRepo->create($data);

        $this->auditLogService->log('CREATE_NEWS', 'berita', (string)$newId, [
            'judul' => $data['judul'],
            'kategori' => $data['kategori'],
        ]);

        return ['success' => true, 'id' => $newId];
    }

    public function update(int $id, array $data, ?array $coverFile = null): array
    {
        $existing = $this->newsRepo->findById($id);
        if (!$existing) {
            return ['success' => false, 'message' => 'Berita tidak ditemukan.'];
        }

        if (!empty($data['judul']) && $data['judul'] !== $existing['judul']) {
            $data['slug'] = $this->generateUniqueSlug($data['judul'], $id);
        }

        // Upload Gambar Sampul Baru jika ada
        if ($coverFile && !empty($coverFile['tmp_name'])) {
            $allowedImages = ['image/jpeg', 'image/png', 'image/webp'];
            $destDir = $this->storageService->getPublicUploadDir('berita');
            $upload = upload_file($coverFile, $destDir, $allowedImages, 4 * 1024 * 1024);
            if ($upload['success']) {
                $this->storageService->deletePublicFile('berita', $existing['gambar_sampul'] ?? null);
                $data['gambar_sampul'] = $upload['filename'];
            }
        }

        if (empty($data['ringkasan']) && !empty($data['konten'])) {
            $plain = strip_tags($data['konten']);
            $data['ringkasan'] = mb_substr($plain, 0, 180) . '...';
        }

        $this->newsRepo->update($id, $data);

        $this->auditLogService->log('UPDATE_NEWS', 'berita', (string)$id, [
            'judul' => $data['judul'] ?? $existing['judul'],
        ]);

        return ['success' => true];
    }

    public function delete(int $id): array
    {
        $existing = $this->newsRepo->findById($id);
        if (!$existing) {
            return ['success' => false, 'message' => 'Berita tidak ditemukan.'];
        }

        $this->storageService->deletePublicFile('berita', $existing['gambar_sampul'] ?? null);
        $this->newsRepo->delete($id);

        $this->auditLogService->log('DELETE_NEWS', 'berita', (string)$id, [
            'judul' => $existing['judul'],
        ]);

        return ['success' => true];
    }

    protected function generateUniqueSlug(string $title, ?int $exceptId = null): string
    {
        $baseSlug = slugify($title);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $existing = $this->newsRepo->findBySlug($slug);
            if (!$existing || ($exceptId && (int)$existing['id'] === $exceptId)) {
                break;
            }
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
