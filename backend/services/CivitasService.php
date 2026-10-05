<?php

namespace App\Services;

use App\Core\Auth;
use App\Repositories\CivitasRepository;

class CivitasService
{
    protected CivitasRepository $civitasRepo;
    protected StorageService $storageService;
    protected AuditLogService $auditLogService;

    public function __construct()
    {
        $this->civitasRepo = new CivitasRepository();
        $this->storageService = new StorageService();
        $this->auditLogService = new AuditLogService();
    }

    public function getList(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $items = $this->civitasRepo->paginate($filters, $page, $perPage);
        $total = $this->civitasRepo->count($filters);
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $items,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int)$totalPages),
            'komisariat_list' => $this->civitasRepo->getDistinctKomisariat(),
            'perguruan_tinggi_list' => $this->civitasRepo->getDistinctPerguruanTinggi(),
            'tahun_maperca_list' => $this->civitasRepo->getDistinctTahunMaperca(),
        ];
    }

    public function getById(int $id): ?array
    {
        return $this->civitasRepo->findById($id);
    }

    public function create(array $data, ?array $fotoFile = null): array
    {
        $data['created_by'] = Auth::id();

        // 1. Simpan Foto Anggota ke Private Storage
        if ($fotoFile && !empty($fotoFile['tmp_name'])) {
            $allowedImages = ['image/jpeg', 'image/png', 'image/webp'];
            $destDir = $this->storageService->getPrivateUploadDir('foto_anggota');
            $upload = upload_file($fotoFile, $destDir, $allowedImages, 3 * 1024 * 1024);
            if ($upload['success']) {
                $data['foto_anggota'] = $upload['filename'];
            }
        }

        $newId = $this->civitasRepo->create($data);

        $this->auditLogService->log('CREATE_CIVITAS', 'civitas', (string)$newId, [
            'nim' => $data['nim'],
            'nama_lengkap' => $data['nama_lengkap'],
            'komisariat' => $data['komisariat'] ?? null,
        ]);

        return ['success' => true, 'id' => $newId];
    }

    public function update(int $id, array $data, ?array $fotoFile = null): array
    {
        $existing = $this->civitasRepo->findById($id);
        if (!$existing) {
            return ['success' => false, 'message' => 'Data anggota tidak ditemukan.'];
        }

        // Unggah Foto Baru jika ada
        if ($fotoFile && !empty($fotoFile['tmp_name'])) {
            $allowedImages = ['image/jpeg', 'image/png', 'image/webp'];
            $destDir = $this->storageService->getPrivateUploadDir('foto_anggota');
            $upload = upload_file($fotoFile, $destDir, $allowedImages, 3 * 1024 * 1024);
            if ($upload['success']) {
                $this->storageService->deletePrivateFile('foto_anggota', $existing['foto_anggota'] ?? null);
                $data['foto_anggota'] = $upload['filename'];
            }
        }

        $this->civitasRepo->update($id, $data);

        $this->auditLogService->log('UPDATE_CIVITAS', 'civitas', (string)$id, [
            'nim' => $data['nim'] ?? $existing['nim'],
            'nama_lengkap' => $data['nama_lengkap'] ?? $existing['nama_lengkap'],
        ]);

        return ['success' => true];
    }

    public function delete(int $id): array
    {
        $existing = $this->civitasRepo->findById($id);
        if (!$existing) {
            return ['success' => false, 'message' => 'Data anggota tidak ditemukan.'];
        }

        // Hapus file fisik privat yang terkait
        $this->storageService->deletePrivateFile('foto_anggota', $existing['foto_anggota'] ?? null);
        $this->storageService->deletePrivateFile('kta', $existing['file_kta'] ?? null);

        $this->civitasRepo->delete($id);

        $this->auditLogService->log('DELETE_CIVITAS', 'civitas', (string)$id, [
            'nim' => $existing['nim'],
            'nama_lengkap' => $existing['nama_lengkap'],
        ]);

        return ['success' => true];
    }

    public function importCsv(string $filePath): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return ['success' => false, 'message' => 'Berkas CSV tidak dapat dibaca.'];
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return ['success' => false, 'message' => 'Gagal membuka berkas CSV.'];
        }

        $header = fgetcsv($handle, 1000, ',');
        if (!$header) {
            fclose($handle);
            return ['success' => false, 'message' => 'Format berkas CSV kosong.'];
        }

        // Normalisasi nama header kolom
        $cleanHeaders = array_map(function ($h) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
        }, $header);

        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle, 2000, ',')) !== false) {
            $rowNum++;
            if (empty(array_filter($row))) {
                continue;
            }

            $rowData = array_combine($cleanHeaders, array_pad($row, count($cleanHeaders), ''));
            $nim = trim($rowData['nim'] ?? '');
            $nama = trim($rowData['nama_lengkap'] ?? $rowData['nama'] ?? '');

            if (empty($nim) || empty($nama)) {
                $failedCount++;
                $errors[] = "Baris {$rowNum}: NIM dan Nama Lengkap wajib terisi.";
                continue;
            }

            // Cek apakah NIM sudah ada
            $existing = $this->civitasRepo->findByNim($nim);
            $komisariatInput = trim($rowData['komisariat'] ?? 'Komisariat Umum');
            $isCommissionMember = !in_array(mb_strtolower($komisariatInput), ['tidak', 'bukan anggota komisariat'], true);
            $dataToSave = [
                'nim' => $nim,
                'nama_lengkap' => $nama,
                'jenis_kelamin' => in_array(strtoupper(trim($rowData['jenis_kelamin'] ?? 'L')), ['L', 'P']) ? strtoupper(trim($rowData['jenis_kelamin'])) : 'L',
                'tempat_lahir' => trim($rowData['tempat_lahir'] ?? ''),
                'tanggal_lahir' => !empty($rowData['tanggal_lahir']) ? trim($rowData['tanggal_lahir']) : null,
                'telepon' => trim($rowData['telepon'] ?? $rowData['no_hp'] ?? ''),
                'email' => trim($rowData['email'] ?? ''),
                'perguruan_tinggi' => trim($rowData['perguruan_tinggi'] ?? 'Universitas Andalas'),
                'fakultas' => trim($rowData['fakultas'] ?? ''),
                'jurusan' => trim($rowData['jurusan'] ?? ''),
                'komisariat' => $isCommissionMember ? $komisariatInput : null,
                'anggota_komisariat' => $isCommissionMember ? 1 : 0,
                'tahun_maperca' => (int)($rowData['tahun_maperca'] ?? date('Y')),
                'tingkat_kaderisasi' => trim($rowData['tingkat_kaderisasi'] ?? 'Maperca'),
                'status_keanggotaan' => trim($rowData['status_keanggotaan'] ?? 'Aktif'),
                'alamat_padang' => trim($rowData['alamat_padang'] ?? ''),
                'alamat_asal' => trim($rowData['alamat_asal'] ?? ''),
                'created_by' => Auth::id(),
            ];

            if ($existing) {
                $this->civitasRepo->update($existing['id'], $dataToSave);
            } else {
                $this->civitasRepo->create($dataToSave);
            }

            $successCount++;
        }

        fclose($handle);

        $this->auditLogService->log('IMPORT_CIVITAS', 'civitas', null, [
            'success_count' => $successCount,
            'failed_count' => $failedCount,
        ]);

        return [
            'success' => true,
            'imported' => $successCount,
            'failed' => $failedCount,
            'errors' => $errors,
        ];
    }

    public function generateExportCsv(array $filters = []): void
    {
        $members = $this->civitasRepo->getAllForExport($filters);

        $filename = 'data_civitas_gmki_padang_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM untuk kompatibilitas Excel
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, [
            'NIM', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
            'Telepon/WA', 'Email', 'Perguruan Tinggi', 'Fakultas', 'Jurusan',
            'Komisariat', 'Tahun Maperca', 'Tingkat Kaderisasi', 'Status Keanggotaan',
            'Alamat di Padang', 'Alamat Asal'
        ]);

        foreach ($members as $row) {
            fputcsv($output, [
                $row['nim'],
                $row['nama_lengkap'],
                $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan',
                $row['tempat_lahir'] ?? '',
                $row['tanggal_lahir'] ?? '',
                $row['telepon'] ?? '',
                $row['email'] ?? '',
                $row['perguruan_tinggi'],
                $row['fakultas'] ?? '',
                $row['jurusan'] ?? '',
                !empty($row['anggota_komisariat']) ? ($row['komisariat'] ?? '') : 'Tidak',
                $row['tahun_maperca'],
                $row['tingkat_kaderisasi'],
                $row['status_keanggotaan'],
                $row['alamat_padang'] ?? '',
                $row['alamat_asal'] ?? '',
            ]);
        }

        fclose($output);
        exit;
    }
}
