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
            'total_pages' => max(1, (int) $totalPages),
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

        $this->auditLogService->log('CREATE_CIVITAS', 'civitas', (string) $newId, [
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

        $this->auditLogService->log('UPDATE_CIVITAS', 'civitas', (string) $id, [
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

        $this->auditLogService->log('DELETE_CIVITAS', 'civitas', (string) $id, [
            'nim' => $existing['nim'],
            'nama_lengkap' => $existing['nama_lengkap'],
        ]);

        return ['success' => true];
    }

    public function importFile(string $filePath, string $originalName, string $kelompok = 'anggota'): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return ['success' => false, 'message' => 'Berkas unggahan tidak dapat dibaca oleh server.'];
        }

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allRows = [];

        if ($extension === 'xlsx' || $extension === 'xls') {
            if (!class_exists('Shuchkin\SimpleXLSX')) {
                return ['success' => false, 'message' => 'Pustaka SimpleXLSX tidak ditemukan untuk membaca berkas Excel.'];
            }

            $xlsx = \Shuchkin\SimpleXLSX::parse($filePath);
            if (!$xlsx) {
                return ['success' => false, 'message' => 'Gagal membaca berkas Excel (.xlsx): ' . \Shuchkin\SimpleXLSX::parseError()];
            }

            $allRows = $xlsx->rows();
        } else {
            // Pemrosesan berkas CSV dengan auto-deteksi pemisah (, atau ;)
            $handle = fopen($filePath, 'r');
            if (!$handle) {
                return ['success' => false, 'message' => 'Gagal membuka berkas CSV.'];
            }

            // Baca baris pertama untuk deteksi delimiter
            $firstLine = fgets($handle);
            if ($firstLine === false) {
                fclose($handle);
                return ['success' => false, 'message' => 'Berkas CSV kosong.'];
            }

            $commaCount = substr_count($firstLine, ',');
            $semicolonCount = substr_count($firstLine, ';');
            $tabCount = substr_count($firstLine, "\t");

            $delimiter = ',';
            if ($semicolonCount > $commaCount && $semicolonCount > $tabCount) {
                $delimiter = ';';
            } elseif ($tabCount > $commaCount && $tabCount > $semicolonCount) {
                $delimiter = "\t";
            }

            rewind($handle);
            // Lewati BOM UTF-8 jika ada
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            while (($row = fgetcsv($handle, 4000, $delimiter)) !== false) {
                $allRows[] = $row;
            }
            fclose($handle);
        }

        if (empty($allRows)) {
            return ['success' => false, 'message' => 'Berkas tidak memiliki data.'];
        }

        // Baris pertama sebagai header
        $rawHeaders = array_shift($allRows);
        if (empty($rawHeaders)) {
            return ['success' => false, 'message' => 'Baris header berkas tidak valid atau kosong.'];
        }

        // Kamus alias nama kolom (fleksibel menerima berbagai penamaan kolom)
        $aliasMap = [
            'id_or_nim' => ['id / nim', 'id/nim', 'id nim', 'id_nim', 'nim / id', 'nim/id', 'nim id', 'nim_id'],
            'id' => ['id', 'id_anggota', 'no_id', 'nomor_id'],
            'nim' => ['nim', 'npm', 'no_mahasiswa', 'nomor_induk', 'no_induk'],
            'nama_lengkap' => ['nama_lengkap', 'nama', 'nama_anggota', 'nama_kader', 'fullname', 'name', 'nama lengkap'],
            'jenis_kelamin' => ['jenis_kelamin', 'jk', 'gender', 'sex', 'kelamin', 'jenis kelamin', 'jenis kelamin (l/p)', 'jenis kelamin l p'],
            'tempat_lahir' => ['tempat_lahir', 'tempat', 'tmp_lahir', 'kota_lahir', 'tempat lahir'],
            'tanggal_lahir' => ['tanggal_lahir', 'tgl_lahir', 'tgllahir', 'tgl', 'birth_date', 'birthdate', 'tanggal lahir', 'tanggal lahir (yyyy-mm-dd)', 'tanggal lahir yyyy mm dd'],
            'telepon' => ['telepon', 'no_hp', 'nohp', 'no_telp', 'notelp', 'telp', 'wa', 'whatsapp', 'phone', 'telepon/wa', 'telepon / wa', 'telepon wa'],
            'email' => ['email', 'surel', 'e_mail', 'mail'],
            'perguruan_tinggi' => ['perguruan_tinggi', 'kampus', 'universitas', 'univ', 'pt', 'perguruan tinggi', 'institusi'],
            'fakultas' => ['fakultas', 'fak', 'fakultas/fak'],
            'jurusan' => ['jurusan', 'prodi', 'program_studi', 'program studi', 'jurusan/prodi'],
            'komisariat' => ['komisariat', 'kom'],
            'tahun_maperca' => ['tahun_maperca', 'tahun', 'angkatan', 'thn_maperca', 'tahun maperca', 'tahun_masuk', 'tahun masuk'],
            'tingkat_kaderisasi' => ['tingkat_kaderisasi', 'tingkat', 'jenjang', 'kaderisasi', 'tingkat kaderisasi'],
            'status_keanggotaan' => ['status_keanggotaan', 'status', 'status keanggotaan'],
            'alamat_padang' => ['alamat_padang', 'alamat', 'alamat di padang', 'domisili', 'alamat_domisili', 'alamat padang'],
            'alamat_asal' => ['alamat_asal', 'alamat asal', 'asal', 'alamat_rumah'],
        ];

        $mappedHeaders = [];
        foreach ($rawHeaders as $idx => $h) {
            $cleanH = strtolower(trim((string) $h));
            $cleanH = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cleanH);
            $cleanH = str_replace(['_', '-', '/'], ' ', $cleanH);
            $cleanH = preg_replace('/\s+/', ' ', $cleanH);
            $cleanHUnderscore = str_replace(' ', '_', $cleanH);

            $matchedKey = null;
            foreach ($aliasMap as $standardKey => $candidates) {
                if (in_array($cleanH, $candidates, true) || in_array($cleanHUnderscore, $candidates, true)) {
                    $matchedKey = $standardKey;
                    break;
                }
            }
            $mappedHeaders[$idx] = $matchedKey ?? $cleanHUnderscore;
        }

        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $rowNum = 1;

        foreach ($allRows as $row) {
            $rowNum++;
            if (empty(array_filter($row, fn($c) => trim((string) $c) !== ''))) {
                continue; // lewati baris kosong
            }

            $rowData = [];
            foreach ($mappedHeaders as $idx => $key) {
                $rowData[$key] = isset($row[$idx]) ? trim((string) $row[$idx]) : '';
            }

            $nama = $rowData['nama_lengkap'] ?? '';
            if (empty($nama)) {
                $failedCount++;
                $errors[] = "Baris {$rowNum}: Kolom Nama Lengkap kosong.";
                continue;
            }

            $rawId = $rowData['id'] ?? '';
            $rawNim = $rowData['nim'] ?? '';
            $rawIdOrNim = $rowData['id_or_nim'] ?? '';

            if (empty($rawId) && empty($rawNim) && !empty($rawIdOrNim)) {
                if (is_numeric($rawIdOrNim)) {
                    $rawId = $rawIdOrNim;
                    $rawNim = $rawIdOrNim;
                } else {
                    $rawNim = $rawIdOrNim;
                }
            } elseif (empty($rawNim) && !empty($rawId) && !is_numeric($rawId)) {
                $rawNim = $rawId;
            } elseif (empty($rawId) && !empty($rawNim) && is_numeric($rawNim)) {
                $rawId = $rawNim;
            }

            // ID / NIM fleksibel
            $customId = null;
            if (!empty($rawId) && is_numeric($rawId)) {
                $customId = (int) $rawId;
            } elseif (!empty($rawNim) && is_numeric($rawNim)) {
                $customId = (int) $rawNim;
            }

            // Normalisasi Jenis Kelamin (Pria/Laki-laki -> L, Perempuan/Wanita -> P)
            $rawJkLower = strtolower(trim((string) ($rowData['jenis_kelamin'] ?? '')));
            $jk = 'L';
            if ($rawJkLower === 'p' || str_contains($rawJkLower, 'perempuan') || str_contains($rawJkLower, 'wanita')) {
                $jk = 'P';
            } elseif ($rawJkLower === 'l' || str_contains($rawJkLower, 'laki') || str_contains($rawJkLower, 'pria')) {
                $jk = 'L';
            }

            // Normalisasi Tanggal Lahir (Excel serial number atau string tanggal)
            $rawTgl = $rowData['tanggal_lahir'] ?? '';
            $tanggalLahir = null;
            if (!empty($rawTgl)) {
                if (is_numeric($rawTgl) && (float) $rawTgl > 1000) {
                    $days = (int) $rawTgl;
                    $unix = ($days - 25569) * 86400;
                    $tanggalLahir = date('Y-m-d', $unix);
                } else {
                    $dt = \DateTime::createFromFormat('Y-m-d', $rawTgl)
                        ?: \DateTime::createFromFormat('d/m/Y', $rawTgl)
                        ?: \DateTime::createFromFormat('d-m-Y', $rawTgl);
                    if ($dt) {
                        $tanggalLahir = $dt->format('Y-m-d');
                    } else {
                        $time = strtotime($rawTgl);
                        if ($time) {
                            $tanggalLahir = date('Y-m-d', $time);
                        }
                    }
                }
            }

            // Default Perguruan Tinggi
            $pt = !empty($rowData['perguruan_tinggi']) ? $rowData['perguruan_tinggi'] : 'Universitas Andalas';

            // Tahun Maperca
            $tahunMaperca = !empty($rowData['tahun_maperca']) && is_numeric($rowData['tahun_maperca'])
                ? (int) $rowData['tahun_maperca']
                : (int) date('Y');

            // Aturan Tingkat Kaderisasi & Komisariat sesuai kelompok modul
            if ($kelompok === 'maperca') {
                $tingkatKaderisasi = 'Maperca';
                $isCommissionMember = false;
                $komisariat = null;
                $statusKeanggotaan = !empty($rowData['status_keanggotaan']) ? $rowData['status_keanggotaan'] : 'Aktif';
            } else {
                $tk = $rowData['tingkat_kaderisasi'] ?? '';
                $tingkatKaderisasi = in_array($tk, ['Anggota', 'KK', 'Alumni'], true) ? $tk : 'Anggota';
                $komisariatInput = trim($rowData['komisariat'] ?? 'Komisariat Umum');
                $isCommissionMember = !in_array(mb_strtolower($komisariatInput), ['tidak', 'bukan anggota komisariat', '', '-'], true);
                $komisariat = $isCommissionMember ? $komisariatInput : null;
                $statusKeanggotaan = !empty($rowData['status_keanggotaan']) ? $rowData['status_keanggotaan'] : 'Aktif';
            }

            $dataToSave = [
                'nama_lengkap' => $nama,
                'jenis_kelamin' => $jk,
                'tempat_lahir' => $rowData['tempat_lahir'] ?? '',
                'tanggal_lahir' => $tanggalLahir,
                'telepon' => $rowData['telepon'] ?? '',
                'email' => $rowData['email'] ?? '',
                'perguruan_tinggi' => $pt,
                'fakultas' => $rowData['fakultas'] ?? '',
                'jurusan' => $rowData['jurusan'] ?? '',
                'komisariat' => $komisariat,
                'anggota_komisariat' => $isCommissionMember ? 1 : 0,
                'tahun_maperca' => $tahunMaperca,
                'tingkat_kaderisasi' => $tingkatKaderisasi,
                'status_keanggotaan' => $statusKeanggotaan,
                'alamat_padang' => $rowData['alamat_padang'] ?? '',
                'alamat_asal' => $rowData['alamat_asal'] ?? '',
                'created_by' => Auth::id(),
            ];

            if ($customId !== null) {
                $dataToSave['id'] = $customId;
            }
            if (!empty($rawNim)) {
                $dataToSave['nim'] = $rawNim;
            } elseif ($customId !== null) {
                $dataToSave['nim'] = (string) $customId;
            }

            // Cek apakah data sudah ada (berdasarkan custom ID jika ada, atau berdasarkan NIM)
            $existing = null;
            if ($customId !== null) {
                $existing = $this->civitasRepo->findById($customId);
            }
            if (!$existing && !empty($rawNim)) {
                $existing = $this->civitasRepo->findByNim($rawNim);
            }

            if ($existing) {
                $this->civitasRepo->update($existing['id'], $dataToSave);
            } else {
                $this->civitasRepo->create($dataToSave);
            }

            $successCount++;
        }

        $auditAction = ($kelompok === 'maperca') ? 'IMPORT_MAPERCA' : 'IMPORT_CIVITAS';
        $this->auditLogService->log($auditAction, 'civitas', null, [
            'format' => $extension,
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

    public function importCsv(string $filePath): array
    {
        return $this->importFile($filePath, 'import.csv', 'anggota');
    }

    public function generateTemplate(string $kelompok = 'anggota', string $format = 'xlsx'): void
    {
        $headers = [
            'ID / NIM',
            'Nama Lengkap',
            'Jenis Kelamin (L/P)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Telepon / WA',
            'Email',
            'Perguruan Tinggi',
            'Fakultas',
            'Jurusan',
            'Komisariat',
            'Tahun Maperca',
            'Tingkat Kaderisasi',
            'Status Keanggotaan',
            'Alamat Padang',
            'Alamat Asal'
        ];

        $sampleRow = [
            '101',
            'Yohanes Christian',
            'L',
            'Padang',
            '2004-05-12',
            '081234567890',
            'yohanes@example.com',
            'Universitas Andalas',
            'Teknik',
            'Teknik Elektro',
            $kelompok === 'maperca' ? '-' : 'Komisariat Unand',
            date('Y'),
            $kelompok === 'maperca' ? 'Maperca' : 'Anggota',
            'Aktif',
            'Jl. Belimbing No. 12, Padang',
            'Tapanuli Tengah'
        ];

        $filename = 'format_impor_' . ($kelompok === 'maperca' ? 'kader_baru_maperca' : 'civitas') . '_' . date('Ymd');

        if ($format === 'csv') {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($output, $headers);
            fputcsv($output, $sampleRow);
            fclose($output);
            exit;
        }

        // Format XLSX via SimpleXLSXGen
        $rows = [$headers, $sampleRow];
        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($rows);
        $xlsx->downloadAs($filename . '.xlsx');
        exit;
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
            'NIM',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Telepon/WA',
            'Email',
            'Perguruan Tinggi',
            'Fakultas',
            'Jurusan',
            'Komisariat',
            'Tahun Maperca',
            'Tingkat Kaderisasi',
            'Status Keanggotaan',
            'Alamat di Padang',
            'Alamat Asal'
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
