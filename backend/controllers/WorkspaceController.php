<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class WorkspaceController
{
    /** Kanal bersama untuk seluruh pengurus BPC. */
    private const COORDINATION_GROUP = 'bpc_group';

    private const DOCUMENT_EXTENSIONS = [
        'pdf' => ['application/pdf'],
        'doc' => ['application/msword', 'application/octet-stream'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/octet-stream'],
        'xls' => ['application/vnd.ms-excel', 'application/octet-stream'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/octet-stream'],
        'odt' => ['application/vnd.oasis.opendocument.text', 'application/zip', 'application/octet-stream'],
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
    ];

    public function dashboard(Request $request): Response
    {
        $role = Auth::role();
        $counts = ['members' => 0, 'inventory' => 0, 'archive' => 0, 'finance' => 0, 'messages' => 0, 'strategies' => 0];
        $queries = [
            'members' => ['civitas.view', 'SELECT COUNT(*) AS total FROM civitas', []],
            'inventory' => ['inventory.view', 'SELECT COUNT(*) AS total FROM inventory_items', []],
            'archive' => ['archive.view', "SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'archive'", []],
            'finance' => [
                can('reports.view') ? 'reports.view' : 'finance.view',
                $role === 'bencab'
                ? "SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'finance' AND created_by = ?"
                : "SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'finance'",
                $role === 'bencab' ? [Auth::id()] : [],
            ],
            'messages' => [
                'coordination.view',
                "SELECT COUNT(*) AS total FROM coordination_messages
                 WHERE recipient_role = ? OR (
                    sender_role IN ('ketcab', 'sekcab', 'bencab', 'sekfung_medko', 'admin')
                    AND recipient_role IN ('ketcab', 'sekcab', 'bencab', 'sekfung_medko', 'admin')
                 )",
                [self::COORDINATION_GROUP],
            ],
            'strategies' => ['strategy.view', 'SELECT COUNT(*) AS total FROM organization_strategies', []],
        ];
        foreach ($queries as $key => [$permission, $sql, $params]) {
            if (can($permission)) {
                $counts[$key] = (int) (Database::fetchOne($sql, $params)['total'] ?? 0);
            }
        }

        return view('workspaces.dashboard', [
            'pageTitle' => 'Ruang Kerja BPC - GMKI Cabang Padang',
            'counts' => $counts,
            'user' => Auth::user(),
        ], 'dashboard');
    }

    public function inventory(Request $request): Response
    {
        Authorization::authorize('inventory.view');
        $pagination = $this->pagination($request, 'SELECT COUNT(*) AS total FROM inventory_items');
        $items = Database::fetchAll(
            'SELECT * FROM inventory_items ORDER BY updated_at DESC, id DESC LIMIT ' . $pagination['perPage'] . ' OFFSET ' . $pagination['offset']
        );
        $summary = Database::fetchOne(
            "SELECT 
                COUNT(*) as total_items,
                COALESCE(SUM(quantity), 0) as total_qty,
                COALESCE(SUM(CASE WHEN item_condition = 'Baik' THEN 1 ELSE 0 END), 0) as total_baik,
                COALESCE(SUM(CASE WHEN item_condition = 'Perlu Perbaikan' THEN 1 ELSE 0 END), 0) as total_perbaikan,
                COALESCE(SUM(CASE WHEN item_condition = 'Rusak' THEN 1 ELSE 0 END), 0) as total_rusak
             FROM inventory_items"
        ) ?: [
            'total_items' => 0,
            'total_qty' => 0,
            'total_baik' => 0,
            'total_perbaikan' => 0,
            'total_rusak' => 0,
        ];

        return $this->page('Inventaris GMKI', 'inventory', [
            'items' => $items,
            'pagination' => $pagination,
            'summary' => $summary,
        ]);
    }

    public function saveInventory(Request $request): Response
    {
        Authorization::authorize('inventory.manage');
        $id = (int) $request->post('id', 0);
        $name = trim((string) $request->post('name'));
        $category = trim((string) $request->post('category'));
        $quantity = $request->post('quantity');
        $unit = trim((string) $request->post('unit'));
        $location = trim((string) $request->post('location'));
        $condition = (string) $request->post('item_condition', 'Baik');
        $notes = trim((string) $request->post('notes'));

        if (
            $name === '' || mb_strlen($name) > 180
            || $category === '' || mb_strlen($category) > 100
            || !is_numeric($quantity) || (float) $quantity < 0 || (float) $quantity > 99999999.99
            || $unit === '' || mb_strlen($unit) > 40
            || mb_strlen($location) > 180
        ) {
            return $this->fail('/sekcab/inventaris', 'Lengkapi nama, kategori, jumlah yang valid, dan satuan.');
        }
        if (!in_array($condition, ['Baik', 'Perlu Perbaikan', 'Rusak'], true)) {
            return $this->fail('/sekcab/inventaris', 'Kondisi inventaris tidak valid.');
        }

        if ($id > 0) {
            $exists = Database::fetchOne('SELECT id FROM inventory_items WHERE id = ?', [$id]);
            if (!$exists) {
                return $this->fail('/sekcab/inventaris', 'Data inventaris tidak ditemukan.');
            }
            Database::execute(
                'UPDATE inventory_items SET name = ?, category = ?, quantity = ?, unit = ?, location = ?, item_condition = ?, notes = ? WHERE id = ?',
                [$name, $category, (float) $quantity, $unit, $location ?: null, $condition, $notes ?: null, $id]
            );
            $message = 'Data inventaris berhasil diperbarui.';
        } else {
            Database::execute(
                'INSERT INTO inventory_items (name, category, quantity, unit, location, item_condition, notes, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
                [$name, $category, (float) $quantity, $unit, $location ?: null, $condition, $notes ?: null, Auth::id()]
            );
            $message = 'Inventaris berhasil ditambahkan.';
        }

        Session::flash('success', $message);
        redirect('/sekcab/inventaris');
    }

    public function deleteInventory(Request $request, string $id): Response
    {
        Authorization::authorize('inventory.manage');
        if (!Database::fetchOne('SELECT id FROM inventory_items WHERE id = ?', [(int) $id])) {
            return $this->fail('/sekcab/inventaris', 'Data inventaris tidak ditemukan.');
        }
        Database::execute('DELETE FROM inventory_items WHERE id = ?', [(int) $id]);
        Session::flash('success', 'Data inventaris berhasil dihapus.');
        redirect('/sekcab/inventaris');
    }

    public function archives(Request $request): Response
    {
        Authorization::authorize('archive.view');

        $summary = Database::fetchOne(
            "SELECT 
                COUNT(*) as total_arsip,
                COALESCE(SUM(CASE WHEN letter_nature IN ('Rahasia', 'Sangat Segera') THEN 1 ELSE 0 END), 0) as total_rahasia,
                COALESCE(SUM(CASE WHEN letter_nature = 'Penting' THEN 1 ELSE 0 END), 0) as total_penting,
                COALESCE(SUM(CASE WHEN is_duplicated = 1 THEN 1 ELSE 0 END), 0) as total_duplikasi,
                COALESCE(SUM(CASE WHEN status IN ('Diarsipkan', 'Selesai') THEN 1 ELSE 0 END), 0) as total_selesai
             FROM office_documents WHERE document_type = 'archive'"
        ) ?: [
            'total_arsip' => 0,
            'total_rahasia' => 0,
            'total_penting' => 0,
            'total_duplikasi' => 0,
            'total_selesai' => 0,
        ];

        $pagination = $this->pagination($request, "SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'archive'");
        $documents = Database::fetchAll(
            "SELECT d.*, u.nama_lengkap FROM office_documents d JOIN users u ON u.id = d.created_by
             WHERE d.document_type = 'archive' ORDER BY COALESCE(d.letter_date, d.created_at) DESC, d.id DESC
             LIMIT " . $pagination['perPage'] . ' OFFSET ' . $pagination['offset']
        );
        return $this->page('Arsip Surat & Perlindungan Dokumen Sekcab', 'archive', [
            'documents' => $documents,
            'pagination' => $pagination,
            'summary' => $summary,
        ]);
    }

    public function saveArchive(Request $request): Response
    {
        Authorization::authorize('archive.manage');
        if (Auth::role() !== 'sekcab') {
            return $this->fail('/sekcab/arsip', 'Pengelolaan data arsip surat merupakan wewenang khusus Sekretaris Cabang (Sekcab). Akun Admin berstatus read-only.');
        }
        $id = (int) $request->post('id', 0);
        $title = trim((string) $request->post('title'));
        $letterDate = $request->post('letter_date') ?: date('Y-m-d');
        $letterNature = trim((string) $request->post('letter_nature', 'Biasa'));
        $status = trim((string) $request->post('status', 'Diarsipkan'));
        $description = trim((string) $request->post('description', ''));

        if ($title === '' || mb_strlen($title) > 180) {
            return $this->fail('/sekcab/arsip', 'Judul surat wajib diisi (maksimal 180 karakter).');
        }

        $validNatures = ['Biasa', 'Penting', 'Segera', 'Sangat Segera', 'Rahasia'];
        if (!in_array($letterNature, $validNatures, true)) {
            $letterNature = 'Biasa';
        }

        $validStatuses = ['Diarsipkan', 'Menunggu Tindak Lanjut', 'Didisposisi', 'Selesai'];
        if (!in_array($status, $validStatuses, true)) {
            $status = 'Diarsipkan';
        }

        if ($letterDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$letterDate)) {
            $letterDate = date('Y-m-d');
        }

        $existing = null;
        if ($id > 0) {
            $existing = Database::fetchOne(
                "SELECT * FROM office_documents WHERE id = ? AND document_type = 'archive'",
                [$id]
            );
            if (!$existing) {
                return $this->fail('/sekcab/arsip', 'Dokumen arsip tidak ditemukan.');
            }
        }

        // Periksa apakah terjadi kesalahan teknis saat pengunggahan berkas oleh PHP
        if (isset($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_OK && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
            $errCode = (int) $_FILES['document']['error'];
            $errMsg = match ($errCode) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran berkas melebihi batas maksimum unggahan server (maksimal 10 MB). Silakan pilih berkas yang lebih kecil.',
                UPLOAD_ERR_PARTIAL => 'Berkas hanya terunggah sebagian. Silakan coba unggah kembali.',
                UPLOAD_ERR_NO_TMP_DIR => 'Direktori sementara penyimpanan berkas tidak ditemukan di server.',
                UPLOAD_ERR_CANT_WRITE => 'Gagal menulis berkas ke penyimpanan server.',
                default => 'Gagal mengunggah berkas (Kode status error: ' . $errCode . ').',
            };
            return $this->fail('/sekcab/arsip', $errMsg);
        }

        try {
            $file = $request->hasFile('document') ? $this->storeDocument($request, 'document') : null;
        } catch (\RuntimeException $exception) {
            return $this->fail('/sekcab/arsip', $exception->getMessage());
        }

        if ($existing) {
            $fields = [
                $title,
                $letterDate,
                $letterNature,
                $status,
                $description ?: null,
            ];
            $sql = 'UPDATE office_documents SET title = ?, letter_date = ?, letter_nature = ?, status = ?, description = ?';
            if ($file) {
                $sql .= ', stored_name = ?, backup_stored_name = ?, file_hash = ?, original_name = ?, mime_type = ?, is_duplicated = 1, duplicate_status = ?';
                array_push(
                    $fields,
                    $file['stored_name'],
                    $file['backup_stored_name'],
                    $file['file_hash'],
                    $file['original_name'],
                    $file['mime_type'],
                    'Duplikasi Terverifikasi (Identik)'
                );
            }
            $sql .= " WHERE id = ? AND document_type = 'archive'";
            $fields[] = $id;
            Database::execute($sql, $fields);
            if ($file && !empty($existing['stored_name'])) {
                $this->removeDocumentFile($existing['stored_name'], $existing['backup_stored_name'] ?? null);
            }
            $message = 'Data arsip surat berhasil diperbarui' . ($file ? ' beserta salinan duplikasi cadangannya.' : '.');
        } else {
            // Registrasi surat baru (Berkas bersifat opsional)
            Database::execute(
                "INSERT INTO office_documents (
                    document_type, title, letter_date, letter_nature, status, description,
                    stored_name, backup_stored_name, file_hash, original_name, mime_type,
                    is_duplicated, duplicate_status, created_by, created_at
                ) VALUES (
                    'archive', ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, NOW()
                )",
                [
                    $title,
                    $letterDate,
                    $letterNature,
                    $status,
                    $description ?: null,
                    $file['stored_name'] ?? null,
                    $file['backup_stored_name'] ?? null,
                    $file['file_hash'] ?? null,
                    $file['original_name'] ?? null,
                    $file['mime_type'] ?? null,
                    $file ? 1 : 0,
                    $file ? 'Duplikasi Terverifikasi (Identik)' : 'Menunggu Berkas Lampiran',
                    Auth::id()
                ]
            );
            $message = $file
                ? 'Arsip surat berhasil disimpan dengan salinan duplikasi cadangan terverifikasi (SHA-256 Identik).'
                : 'Arsip surat berhasil dicatat ke buku agenda. Anda dapat melampirkan berkas digital kapan saja via tombol Edit.';
        }
        Session::flash('success', $message);
        redirect('/sekcab/arsip');
    }

    public function finance(Request $request): Response
    {
        if (Auth::role() === 'ketcab') {
            Authorization::authorize('reports.view');
        } else {
            Authorization::authorize('finance.view');
        }
        $user = Auth::user();
        $isBendahara = ($user['role'] ?? '') === 'bencab';
        $listSql = "SELECT d.*, u.nama_lengkap FROM office_documents d JOIN users u ON u.id = d.created_by WHERE d.document_type = 'finance'";
        $countSql = "SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'finance'";
        $params = [];
        if ($isBendahara) {
            $listSql .= ' AND d.created_by = ?';
            $countSql .= ' AND created_by = ?';
            $params[] = Auth::id();
        }
        $pagination = $this->pagination($request, $countSql, $params);
        $listSql .= ' ORDER BY d.created_at DESC, d.id DESC LIMIT ' . $pagination['perPage'] . ' OFFSET ' . $pagination['offset'];
        $documents = Database::fetchAll($listSql, $params);
        return $this->page('Laporan Keuangan', 'finance', ['documents' => $documents, 'pagination' => $pagination]);
    }

    public function saveFinance(Request $request): Response
    {
        Authorization::authorize('finance.manage');
        $id = (int) $request->post('id', 0);
        $title = trim((string) $request->post('title'));
        $period = trim((string) $request->post('period'));
        $description = trim((string) $request->post('description'));
        if (
            $title === '' || mb_strlen($title) > 180
            || $period === '' || mb_strlen($period) > 40
        ) {
            return $this->fail('/bencab/laporan', 'Judul dan periode laporan keuangan wajib diisi.');
        }

        $existing = null;
        if ($id > 0) {
            $existing = Database::fetchOne(
                "SELECT * FROM office_documents WHERE id = ? AND document_type = 'finance' AND created_by = ?",
                [$id, Auth::id()]
            );
            if (!$existing) {
                return $this->fail('/bencab/laporan', 'Laporan tidak ditemukan atau bukan milik akun Anda.');
            }
        }

        try {
            $file = $request->hasFile('document') ? $this->storeDocument($request, 'document') : null;
        } catch (\RuntimeException $exception) {
            return $this->fail('/bencab/laporan', $exception->getMessage());
        }
        if (!$existing && !$file) {
            return $this->fail('/bencab/laporan', 'Lampirkan berkas laporan keuangan.');
        }

        if ($existing) {
            $fields = [$title, $description ?: null, $period];
            $sql = 'UPDATE office_documents SET title = ?, description = ?, period = ?';
            if ($file) {
                $sql .= ', stored_name = ?, original_name = ?, mime_type = ?';
                array_push($fields, $file['stored_name'], $file['original_name'], $file['mime_type']);
            }
            $sql .= ' WHERE id = ? AND created_by = ?';
            array_push($fields, $id, Auth::id());
            Database::execute($sql, $fields);
            if ($file) {
                $this->removeDocumentFile($existing['stored_name']);
            }
            $message = 'Laporan keuangan berhasil diperbarui.';
        } else {
            Database::execute(
                "INSERT INTO office_documents (document_type, title, description, period, amount, stored_name, original_name, mime_type, created_by, created_at) VALUES ('finance', ?, ?, ?, NULL, ?, ?, ?, ?, NOW())",
                [$title, $description ?: null, $period, $file['stored_name'], $file['original_name'], $file['mime_type'], Auth::id()]
            );
            $message = 'Laporan keuangan berhasil disimpan.';
        }

        Session::flash('success', $message);
        redirect('/bencab/laporan');
    }

    public function deleteFinance(Request $request, string $id): Response
    {
        Authorization::authorize('finance.manage');
        $document = Database::fetchOne(
            "SELECT * FROM office_documents WHERE id = ? AND document_type = 'finance' AND created_by = ?",
            [(int) $id, Auth::id()]
        );
        if (!$document) {
            return $this->fail('/bencab/laporan', 'Laporan tidak ditemukan atau bukan milik akun Anda.');
        }
        Database::execute('DELETE FROM office_documents WHERE id = ?', [(int) $id]);
        $this->removeDocumentFile($document['stored_name']);
        Session::flash('success', 'Laporan keuangan berhasil dihapus.');
        redirect('/bencab/laporan');
    }

    public function downloadDocument(Request $request, string $id): Response
    {
        $document = Database::fetchOne('SELECT * FROM office_documents WHERE id = ?', [(int) $id]);
        if (!$document) {
            http_response_code(404);
            return (new Response())->html('Dokumen tidak ditemukan.', 404);
        }
        if ($document['document_type'] === 'finance') {
            Authorization::authorize('reports.download');
        } elseif (Auth::role() === 'sekfung_medko') {
            Authorization::authorize('reports.download');
        } else {
            Authorization::authorize('archive.view');
        }

        $isDuplicate = ($request->query('tipe') === 'salinan' || $request->query('salinan') === '1');
        if ($isDuplicate && !empty($document['backup_stored_name'])) {
            $path = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR . basename($document['backup_stored_name']);
            $prefix = '[SALINAN-DUPLIKASI]_';
        } else {
            $path = $this->documentPath($document['stored_name']);
            $prefix = '[ASLI-MASTER]_';
        }

        // Self-healing: jika berkas asli hilang atau bermasalah, pulihkan otomatis dari salinan duplikasi cadangan
        if (!is_file($path) && !$isDuplicate && !empty($document['backup_stored_name'])) {
            $backupPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR . basename($document['backup_stored_name']);
            if (is_file($backupPath)) {
                copy($backupPath, $path);
            }
        }

        if (!is_file($path)) {
            error_log('Berkas workspace hilang dari penyimpanan: ' . $document['stored_name']);
            http_response_code(404);
            return (new Response())->html('Berkas tidak tersedia di penyimpanan.', 404);
        }
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($document['original_name']));
        (new Response())->file($path, $prefix . ($safeName ?: 'dokumen'), 'attachment');
        return new Response();
    }

    public function archiveFiles(Request $request): Response
    {
        $role = Auth::role();
        if (!in_array($role, ['admin', 'sekcab'], true)) {
            Authorization::authorize('archive.view');
        }

        $query = trim((string) $request->query('q', ''));
        $nature = trim((string) $request->query('nature', ''));
        $status = trim((string) $request->query('status', ''));
        $extension = strtolower(trim((string) $request->query('ext', 'all')));
        $sort = trim((string) $request->query('sort', 'latest'));

        $where = ["d.document_type = 'archive'"];
        $params = [];

        if ($query !== '') {
            $where[] = "(d.title LIKE ? OR d.original_name LIKE ? OR d.description LIKE ? OR u.nama_lengkap LIKE ?)";
            $term = '%' . $query . '%';
            array_push($params, $term, $term, $term, $term);
        }

        if ($nature !== '') {
            $where[] = "d.letter_nature = ?";
            $params[] = $nature;
        }

        if ($status !== '') {
            $where[] = "d.status = ?";
            $params[] = $status;
        }

        if ($extension !== '' && $extension !== 'all') {
            if ($extension === 'images') {
                $where[] = "LOWER(SUBSTRING_INDEX(d.original_name, '.', -1)) IN ('jpg', 'jpeg', 'png', 'webp')";
            } elseif ($extension === 'excel') {
                $where[] = "LOWER(SUBSTRING_INDEX(d.original_name, '.', -1)) IN ('xls', 'xlsx')";
            } elseif ($extension === 'word') {
                $where[] = "LOWER(SUBSTRING_INDEX(d.original_name, '.', -1)) IN ('doc', 'docx', 'odt')";
            } else {
                $where[] = "LOWER(SUBSTRING_INDEX(d.original_name, '.', -1)) = ?";
                $params[] = $extension;
            }
        }

        $whereSql = 'WHERE ' . implode(' AND ', $where);

        $countSql = "SELECT COUNT(*) AS total 
                     FROM office_documents d 
                     JOIN users u ON u.id = d.created_by 
                     {$whereSql}";
        $pagination = $this->pagination($request, $countSql, $params);

        $orderSql = match ($sort) {
            'oldest' => 'ORDER BY d.created_at ASC, d.id ASC',
            'letter_date_desc' => 'ORDER BY d.letter_date DESC, d.id DESC',
            'letter_date_asc' => 'ORDER BY d.letter_date ASC, d.id ASC',
            'name_asc' => 'ORDER BY d.title ASC',
            'name_desc' => 'ORDER BY d.title DESC',
            default => 'ORDER BY d.created_at DESC, d.id DESC',
        };

        $listSql = "SELECT d.*, u.nama_lengkap, u.role AS uploader_role 
                    FROM office_documents d 
                    JOIN users u ON u.id = d.created_by 
                    {$whereSql} 
                    {$orderSql} 
                    LIMIT {$pagination['perPage']} OFFSET {$pagination['offset']}";

        $rows = Database::fetchAll($listSql, $params);

        $documents = [];
        foreach ($rows as $row) {
            $path = $this->documentPath($row['stored_name'] ?? '');
            $fileExists = !empty($row['stored_name']) && is_file($path);
            $fileSize = $fileExists ? (int) filesize($path) : 0;
            $ext = strtolower(pathinfo($row['original_name'] ?? '', PATHINFO_EXTENSION));

            $backupPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR . basename($row['backup_stored_name'] ?? '');
            $backupExists = !empty($row['backup_stored_name']) && is_file($backupPath);

            $row['file_exists'] = $fileExists;
            $row['backup_exists'] = $backupExists;
            $row['file_size'] = $fileSize;
            $row['file_size_formatted'] = $this->formatBytes($fileSize);
            $row['extension'] = $ext;
            $row['download_url'] = '/dokumen/' . (int) $row['id'] . '/unduh';
            $row['download_backup_url'] = '/dokumen/' . (int) $row['id'] . '/unduh?salinan=1';

            $documents[] = $row;
        }

        // Stats summary calculation khusus archive
        $archiveTotal = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'archive'")['total'] ?? 0);
        $secretCount = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'archive' AND letter_nature = 'Rahasia'")['total'] ?? 0);
        $urgentCount = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'archive' AND letter_nature IN ('Penting', 'Segera', 'Sangat Segera')")['total'] ?? 0);
        $duplicatedCount = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'archive' AND is_duplicated = 1 AND stored_name IS NOT NULL")['total'] ?? 0);

        $allDocs = Database::fetchAll("SELECT stored_name, backup_stored_name FROM office_documents WHERE document_type = 'archive'");
        $totalStorageBytes = 0;
        foreach ($allDocs as $docItem) {
            if (!empty($docItem['stored_name'])) {
                $p = $this->documentPath($docItem['stored_name']);
                if (is_file($p)) {
                    $totalStorageBytes += (int) filesize($p);
                }
            }
            if (!empty($docItem['backup_stored_name'])) {
                $bp = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR . basename($docItem['backup_stored_name']);
                if (is_file($bp)) {
                    $totalStorageBytes += (int) filesize($bp);
                }
            }
        }

        $stats = [
            'total' => $archiveTotal,
            'secret' => $secretCount,
            'urgent' => $urgentCount,
            'duplicated' => $duplicatedCount,
            'storage_used' => $this->formatBytes($totalStorageBytes),
            'storage_bytes' => $totalStorageBytes,
        ];

        $layout = Auth::role() === 'admin' ? 'admin' : 'dashboard';

        return view('workspaces.archive_files', [
            'pageTitle' => 'Direktori Berkas Laporan & Persuratan Sekcab - GMKI Cabang Padang',
            'documents' => $documents,
            'stats' => $stats,
            'query' => $query,
            'nature' => $nature,
            'status' => $status,
            'extension' => $extension,
            'sort' => $sort,
            'pagination' => $pagination,
            'user' => Auth::user(),
        ], $layout);
    }

    public function financeFiles(Request $request): Response
    {
        $role = Auth::role();
        if (!in_array($role, ['admin', 'bencab'], true)) {
            Authorization::authorize('finance.view');
        }

        $query = trim((string) $request->query('q', ''));
        $period = trim((string) $request->query('period', ''));
        $extension = strtolower(trim((string) $request->query('ext', 'all')));
        $sort = trim((string) $request->query('sort', 'latest'));

        $where = ["d.document_type = 'finance'"];
        $params = [];

        if ($query !== '') {
            $where[] = "(d.title LIKE ? OR d.original_name LIKE ? OR d.description LIKE ? OR d.period LIKE ? OR u.nama_lengkap LIKE ?)";
            $term = '%' . $query . '%';
            array_push($params, $term, $term, $term, $term, $term);
        }

        if ($period !== '') {
            $where[] = "d.period = ?";
            $params[] = $period;
        }

        if ($extension !== '' && $extension !== 'all') {
            if ($extension === 'images') {
                $where[] = "LOWER(SUBSTRING_INDEX(d.original_name, '.', -1)) IN ('jpg', 'jpeg', 'png', 'webp')";
            } elseif ($extension === 'excel') {
                $where[] = "LOWER(SUBSTRING_INDEX(d.original_name, '.', -1)) IN ('xls', 'xlsx')";
            } elseif ($extension === 'word') {
                $where[] = "LOWER(SUBSTRING_INDEX(d.original_name, '.', -1)) IN ('doc', 'docx', 'odt')";
            } else {
                $where[] = "LOWER(SUBSTRING_INDEX(d.original_name, '.', -1)) = ?";
                $params[] = $extension;
            }
        }

        $whereSql = 'WHERE ' . implode(' AND ', $where);

        $countSql = "SELECT COUNT(*) AS total 
                     FROM office_documents d 
                     JOIN users u ON u.id = d.created_by 
                     {$whereSql}";
        $pagination = $this->pagination($request, $countSql, $params);

        $orderSql = match ($sort) {
            'oldest' => 'ORDER BY d.created_at ASC, d.id ASC',
            'name_asc' => 'ORDER BY d.original_name ASC',
            'name_desc' => 'ORDER BY d.original_name DESC',
            'period_desc' => 'ORDER BY d.period DESC, d.id DESC',
            'period_asc' => 'ORDER BY d.period ASC, d.id ASC',
            default => 'ORDER BY d.created_at DESC, d.id DESC',
        };

        $listSql = "SELECT d.*, u.nama_lengkap, u.role AS uploader_role 
                    FROM office_documents d 
                    JOIN users u ON u.id = d.created_by 
                    {$whereSql} 
                    {$orderSql} 
                    LIMIT {$pagination['perPage']} OFFSET {$pagination['offset']}";

        $rows = Database::fetchAll($listSql, $params);

        $documents = [];
        foreach ($rows as $row) {
            $path = $this->documentPath($row['stored_name'] ?? '');
            $fileExists = !empty($row['stored_name']) && is_file($path);
            $fileSize = $fileExists ? (int) filesize($path) : 0;
            $ext = strtolower(pathinfo($row['original_name'] ?? '', PATHINFO_EXTENSION));

            $row['file_exists'] = $fileExists;
            $row['file_size'] = $fileSize;
            $row['file_size_formatted'] = $this->formatBytes($fileSize);
            $row['extension'] = $ext;
            $row['download_url'] = '/dokumen/' . (int) $row['id'] . '/unduh';

            $documents[] = $row;
        }

        // Stats summary calculation khusus finance
        $financeTotal = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'finance'")['total'] ?? 0);
        $excelCount = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'finance' AND LOWER(SUBSTRING_INDEX(original_name, '.', -1)) IN ('xls', 'xlsx')")['total'] ?? 0);
        $pdfCount = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'finance' AND LOWER(SUBSTRING_INDEX(original_name, '.', -1)) = 'pdf'")['total'] ?? 0);

        $allDocs = Database::fetchAll("SELECT stored_name FROM office_documents WHERE document_type = 'finance' AND stored_name IS NOT NULL");
        $totalStorageBytes = 0;
        foreach ($allDocs as $docItem) {
            $p = $this->documentPath($docItem['stored_name']);
            if (is_file($p)) {
                $totalStorageBytes += (int) filesize($p);
            }
        }

        $allPeriods = Database::fetchAll("SELECT DISTINCT period FROM office_documents WHERE document_type = 'finance' AND period IS NOT NULL AND period != '' ORDER BY period DESC");

        $stats = [
            'total' => $financeTotal,
            'excel' => $excelCount,
            'pdf' => $pdfCount,
            'storage_used' => $this->formatBytes($totalStorageBytes),
            'storage_bytes' => $totalStorageBytes,
        ];

        $layout = Auth::role() === 'admin' ? 'admin' : 'dashboard';

        return view('workspaces.finance_files', [
            'pageTitle' => 'Direktori Berkas Laporan Keuangan - GMKI Cabang Padang',
            'documents' => $documents,
            'stats' => $stats,
            'query' => $query,
            'period' => $period,
            'periods' => array_column($allPeriods, 'period'),
            'extension' => $extension,
            'sort' => $sort,
            'pagination' => $pagination,
            'user' => Auth::user(),
        ], $layout);
    }

    public function files(Request $request): Response
    {
        $role = Auth::role();
        if ($role === 'bencab') {
            redirect('/bencab/berkas');
        } elseif ($role === 'sekcab') {
            redirect('/sekcab/berkas');
        } elseif ($role === 'admin') {
            redirect('/sekcab/berkas');
        } else {
            redirect('/ruang-kerja');
        }
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = (int) floor(log($bytes, 1024));
        $i = min($i, count($units) - 1);
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }

    public function downloadArchiveZip(Request $request): void
    {
        if (Auth::role() !== 'admin') {
            http_response_code(403);
            exit('Akses ditolak: Hanya Administrator yang berhak membuat dan mengunduh paket cadangan arsip berkas.');
        }

        $type = trim((string) $request->query('type', 'all'));
        if (!in_array($type, ['all', 'archive', 'finance'], true)) {
            $type = 'all';
        }

        $where = [];
        $params = [];
        if ($type === 'archive') {
            $where[] = "document_type = 'archive'";
        } elseif ($type === 'finance') {
            $where[] = "document_type = 'finance'";
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $docs = Database::fetchAll("SELECT * FROM office_documents {$whereSql} ORDER BY id ASC", $params);

        $tempDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'temp';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipFilename = 'backup_berkas_gmki_padang_' . $type . '_' . date('Ymd_His') . '.zip';
        $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipFilename;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            http_response_code(500);
            exit('Gagal membuat arsip ZIP untuk cadangan berkas.');
        }

        $baseDocDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'documents';
        $backupDocDir = $baseDocDir . DIRECTORY_SEPARATOR . 'backups';

        $manifestRows = [];
        $manifestRows[] = [
            'ID',
            'Tipe Dokumen',
            'Judul Dokumen',
            'Sifat / Periode',
            'Status',
            'Tanggal Dokumen',
            'Nama Berkas Asli',
            'Berkas Master Ditemukan',
            'SHA256 Master Terverifikasi',
            'Berkas Duplikasi Ditemukan',
            'SHA256 Duplikasi Terverifikasi',
            'Status Integritas Redundansi'
        ];

        $metadataExport = [];
        $fileCount = 0;

        foreach ($docs as $doc) {
            $id = (int) $doc['id'];
            $docType = $doc['document_type'];
            $title = $doc['title'];
            $natureOrPeriod = $doc['letter_nature'] ?: ($doc['period'] ?: '-');
            $status = $doc['status'] ?: '-';
            $letterDate = $doc['letter_date'] ?: ($doc['created_at'] ?: '-');
            $originalName = $doc['original_name'] ?: 'berkas_' . $id;
            $safeOriginalName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $originalName);

            $masterStored = $doc['stored_name'] ?? '';
            $masterPath = $masterStored ? ($baseDocDir . DIRECTORY_SEPARATOR . basename($masterStored)) : '';
            $masterExists = $masterPath && is_file($masterPath);

            $backupStored = $doc['backup_stored_name'] ?? '';
            $backupPath = $backupStored ? ($backupDocDir . DIRECTORY_SEPARATOR . basename($backupStored)) : '';
            $backupExists = $backupPath && is_file($backupPath);

            $masterSha = $masterExists ? (hash_file('sha256', $masterPath) ?: '') : '';
            $backupSha = $backupExists ? (hash_file('sha256', $backupPath) ?: '') : '';

            $folderPrefix = ($docType === 'archive' ? 'arsip_persuratan_sekcab' : 'laporan_keuangan_bencab');

            if ($masterExists) {
                $zipSubPath = $folderPrefix . '/master/' . sprintf('%04d', $id) . '_' . $safeOriginalName;
                $zip->addFile($masterPath, $zipSubPath);
                $fileCount++;
            }

            if ($backupExists) {
                $zipSubPathBackup = $folderPrefix . '/duplikasi_cadangan/' . sprintf('%04d', $id) . '_bak_' . $safeOriginalName;
                $zip->addFile($backupPath, $zipSubPathBackup);
                $fileCount++;
            }

            $integrityStatus = 'Tanpa Berkas Digital';
            if ($masterExists && $backupExists) {
                $integrityStatus = ($masterSha === $backupSha) ? '100% IDENTIK & VALID' : 'PERBEDAAN HASH (PERIKSA)';
            } elseif ($masterExists) {
                $integrityStatus = 'Hanya Master Tersedia';
            } elseif ($backupExists) {
                $integrityStatus = 'Hanya Duplikasi Tersedia';
            }

            $manifestRows[] = [
                $id,
                $docType,
                $title,
                $natureOrPeriod,
                $status,
                $letterDate,
                $originalName,
                $masterExists ? 'YA' : 'TIDAK',
                $masterSha,
                $backupExists ? 'YA' : 'TIDAK',
                $backupSha,
                $integrityStatus,
            ];

            $metadataExport[] = [
                'id' => $id,
                'document_type' => $docType,
                'title' => $title,
                'letter_nature' => $doc['letter_nature'] ?? null,
                'period' => $doc['period'] ?? null,
                'status' => $doc['status'] ?? null,
                'description' => $doc['description'] ?? null,
                'original_name' => $originalName,
                'file_hash' => $doc['file_hash'] ?? null,
                'master_hash_now' => $masterSha,
                'backup_hash_now' => $backupSha,
                'is_duplicated' => (int) ($doc['is_duplicated'] ?? 0),
                'duplicate_status' => $doc['duplicate_status'] ?? '',
                'created_at' => $doc['created_at'] ?? null,
            ];
        }

        // Tulis Manifest CSV ke dalam ZIP
        $csvBuffer = fopen('php://temp', 'r+');
        foreach ($manifestRows as $row) {
            fputcsv($csvBuffer, $row);
        }
        rewind($csvBuffer);
        $csvContent = stream_get_contents($csvBuffer);
        fclose($csvBuffer);
        $zip->addFromString('MANIFEST_VERIFIKASI_INTEGRITAS_SHA256.csv', $csvContent);

        // Tulis Metadata JSON untuk Full Disaster Recovery
        $jsonContent = json_encode([
            'system' => 'GMKI Cabang Padang - Sistem Informasi & Manajemen Keanggotaan',
            'backup_type' => $type,
            'generated_at' => date('Y-m-d H:i:s'),
            'generated_by' => Auth::user()['nama_lengkap'] ?? 'Administrator',
            'total_records' => count($docs),
            'total_files_packaged' => $fileCount,
            'documents' => $metadataExport,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $zip->addFromString('METADATA_LENGKAP_DISASTER_RECOVERY.json', $jsonContent);

        // Tulis README Pemulihan
        $readme = "=========================================================================\n"
            . "GMKI CABANG PADANG - PAKET PENCADANGAN BERKAS DOKUMEN & INTEGRITAS SHA-256\n"
            . "=========================================================================\n\n"
            . "Waktu Pembuatan : " . date('Y-m-d H:i:s') . "\n"
            . "Kategori Backup : " . strtoupper($type) . "\n"
            . "Total Dokumen   : " . count($docs) . " data\n"
            . "Total Berkas    : " . $fileCount . " berkas fisik\n\n"
            . "STRUKTUR DIREKTORI CADANGAN:\n"
            . "- master/              : Berkas asli (Master scan/laporan).\n"
            . "- duplikasi_cadangan/  : Salinan ganda redundan terverifikasi (SHA-256 Identik).\n"
            . "- MANIFEST_VERIFIKASI_INTEGRITAS_SHA256.csv : Log matriks hash cryptographic tiap file.\n"
            . "- METADATA_LENGKAP_DISASTER_RECOVERY.json   : Seluruh catatan database siap restore.\n\n"
            . "Simpan paket ZIP ini di media penyimpanan eksternal yang aman dan terenkripsi.\n";
        $zip->addFromString('README_PETUNJUK_PEMULIHAN.txt', $readme);

        $zip->close();

        // Catat jejak audit
        $auditService = new \App\Services\AuditLogService();
        $auditService->log('DOWNLOAD_BACKUP_DOCUMENTS_ZIP', 'workspace_documents', $zipFilename, [
            'type' => $type,
            'total_documents' => count($docs),
            'files_packaged' => $fileCount,
            'zip_size' => filesize($zipPath),
        ]);

        (new Response())->file($zipPath, $zipFilename, 'application/zip');
    }

    public function deleteArchive(Request $request, string $id): Response
    {
        Authorization::authorize('archive.manage');
        if (Auth::role() !== 'sekcab') {
            return $this->fail('/sekcab/arsip', 'Penghapusan data arsip surat merupakan wewenang khusus Sekretaris Cabang (Sekcab). Akun Admin berstatus read-only.');
        }
        $document = Database::fetchOne(
            "SELECT * FROM office_documents WHERE id = ? AND document_type = 'archive'",
            [(int) $id]
        );
        if (!$document) {
            return $this->fail('/sekcab/arsip', 'Dokumen arsip tidak ditemukan.');
        }
        Database::execute('DELETE FROM office_documents WHERE id = ?', [(int) $id]);
        $this->removeDocumentFile($document['stored_name'], $document['backup_stored_name'] ?? null);
        Session::flash('success', 'Dokumen arsip beserta salinan duplikasinya berhasil dihapus.');
        redirect('/sekcab/arsip');
    }

    public function strategies(Request $request): Response
    {
        Authorization::authorize('strategy.view');
        $pagination = $this->pagination($request, 'SELECT COUNT(*) AS total FROM organization_strategies');
        $strategies = Database::fetchAll(
            'SELECT * FROM organization_strategies ORDER BY target_date IS NULL, target_date, id DESC
             LIMIT ' . $pagination['perPage'] . ' OFFSET ' . $pagination['offset']
        );
        return $this->page('Strategi Organisasi', 'strategies', ['strategies' => $strategies, 'pagination' => $pagination]);
    }

    public function saveStrategy(Request $request): Response
    {
        Authorization::authorize('strategy.manage');
        $id = (int) $request->post('id', 0);
        $title = trim((string) $request->post('title'));
        $objective = trim((string) $request->post('objective'));
        $status = (string) $request->post('status', 'Rencana');
        $targetDate = trim((string) $request->post('target_date'));
        if ($title === '' || mb_strlen($title) > 180 || $objective === '' || !in_array($status, ['Rencana', 'Berjalan', 'Selesai'], true)) {
            return $this->fail('/ketcab/strategi', 'Judul, tujuan, dan status strategi wajib valid.');
        }
        if ($targetDate !== '') {
            $parsedDate = \DateTime::createFromFormat('!Y-m-d', $targetDate);
            if (!$parsedDate || $parsedDate->format('Y-m-d') !== $targetDate) {
                return $this->fail('/ketcab/strategi', 'Tanggal target tidak valid.');
            }
        }

        if ($id > 0) {
            $exists = Database::fetchOne('SELECT id FROM organization_strategies WHERE id = ?', [$id]);
            if (!$exists) {
                return $this->fail('/ketcab/strategi', 'Catatan strategi tidak ditemukan.');
            }
            Database::execute(
                'UPDATE organization_strategies SET title = ?, objective = ?, status = ?, target_date = ? WHERE id = ?',
                [$title, $objective, $status, $targetDate ?: null, $id]
            );
        } else {
            Database::execute(
                'INSERT INTO organization_strategies (title, objective, status, target_date, created_by, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
                [$title, $objective, $status, $targetDate ?: null, Auth::id()]
            );
        }
        Session::flash('success', 'Catatan strategi berhasil disimpan.');
        redirect('/ketcab/strategi');
    }

    public function deleteStrategy(Request $request, string $id): Response
    {
        Authorization::authorize('strategy.manage');
        if (!Database::fetchOne('SELECT id FROM organization_strategies WHERE id = ?', [(int) $id])) {
            return $this->fail('/ketcab/strategi', 'Catatan strategi tidak ditemukan.');
        }
        Database::execute('DELETE FROM organization_strategies WHERE id = ?', [(int) $id]);
        Session::flash('success', 'Catatan strategi berhasil dihapus.');
        redirect('/ketcab/strategi');
    }

    public function coordination(Request $request): Response
    {
        Authorization::authorize('coordination.view');
        $messages = Database::fetchAll(
            'SELECT m.*, u.nama_lengkap AS sender_name FROM coordination_messages m JOIN users u ON u.id = m.sender_id
             WHERE m.recipient_role = ? OR (
                m.sender_role IN (\'ketcab\', \'sekcab\', \'bencab\', \'sekfung_medko\', \'admin\')
                AND m.recipient_role IN (\'ketcab\', \'sekcab\', \'bencab\', \'sekfung_medko\', \'admin\')
             )
             ORDER BY m.id DESC LIMIT 101',
            [self::COORDINATION_GROUP]
        );
        $hasOlderMessages = count($messages) > 100;
        if ($hasOlderMessages) {
            array_pop($messages);
        }
        $messages = array_reverse($messages);

        return $this->page('Ruang Koordinasi BPC', 'coordination', [
            'messages' => $messages,
            'participants' => $this->coordinationParticipants(),
            'currentUserId' => Auth::id(),
            'messagesUrl' => $this->coordinationMessagesPath(),
            'hasOlderMessages' => $hasOlderMessages,
        ]);
    }

    public function coordinationMessages(Request $request): Response
    {
        Authorization::authorize('coordination.view');
        $beforeValue = $request->query('before');
        $afterId = filter_var($request->query('after', 0), FILTER_VALIDATE_INT);
        $beforeId = $beforeValue === null || $beforeValue === '' ? null : filter_var($beforeValue, FILTER_VALIDATE_INT);
        if (
            $afterId === false || $afterId < 0
            || ($beforeValue !== null && $beforeValue !== '' && ($beforeId === false || $beforeId < 1))
        ) {
            return (new Response())->json(['error' => 'Percakapan tidak valid.'], 400);
        }

        if ($beforeId !== null) {
            $messages = Database::fetchAll(
                'SELECT m.id, m.sender_id, m.sender_role, m.subject, m.body, m.created_at, u.nama_lengkap AS sender_name
                 FROM coordination_messages m JOIN users u ON u.id = m.sender_id
                 WHERE m.id < ? AND (m.recipient_role = ? OR (
                    m.sender_role IN (\'ketcab\', \'sekcab\', \'bencab\', \'sekfung_medko\', \'admin\')
                    AND m.recipient_role IN (\'ketcab\', \'sekcab\', \'bencab\', \'sekfung_medko\', \'admin\')
                 ))
                 ORDER BY m.id DESC LIMIT 101',
                [$beforeId, self::COORDINATION_GROUP]
            );
            $hasMore = count($messages) > 100;
            if ($hasMore) {
                array_pop($messages);
            }
            return (new Response())->json(['messages' => array_reverse($messages), 'has_more' => $hasMore]);
        }

        $messages = Database::fetchAll(
            'SELECT m.id, m.sender_id, m.sender_role, m.subject, m.body, m.created_at, u.nama_lengkap AS sender_name
             FROM coordination_messages m JOIN users u ON u.id = m.sender_id
             WHERE m.id > ? AND (m.recipient_role = ? OR (
                m.sender_role IN (\'ketcab\', \'sekcab\', \'bencab\', \'sekfung_medko\', \'admin\')
                AND m.recipient_role IN (\'ketcab\', \'sekcab\', \'bencab\', \'sekfung_medko\', \'admin\')
             ))
             ORDER BY m.id ASC LIMIT 100',
            [$afterId, self::COORDINATION_GROUP]
        );

        return (new Response())->json(['messages' => $messages, 'has_more' => false]);
    }

    public function sendMessage(Request $request): Response
    {
        Authorization::authorize('coordination.manage');
        $role = (string) Auth::role();
        $subject = trim((string) $request->post('subject', 'Koordinasi BPC'));
        $body = trim((string) $request->post('body'));
        $parentId = (int) $request->post('parent_id', 0);
        if ($subject === '' || mb_strlen($subject) > 180 || $body === '' || mb_strlen($body) > 5000) {
            return $this->coordinationFailure($request, 'Isi pesan wajib diisi dan tidak boleh lebih dari 5.000 karakter.');
        }

        if ($parentId > 0) {
            $parent = Database::fetchOne(
                'SELECT id FROM coordination_messages WHERE id = ?
                 AND (recipient_role = ? OR (
                    sender_role IN (\'ketcab\', \'sekcab\', \'bencab\', \'sekfung_medko\', \'admin\')
                    AND recipient_role IN (\'ketcab\', \'sekcab\', \'bencab\', \'sekfung_medko\', \'admin\')
                 ))',
                [$parentId, self::COORDINATION_GROUP]
            );
            if (!$parent) {
                return $this->coordinationFailure($request, 'Pesan yang ingin dibalas tidak ditemukan.');
            }
        }

        Database::execute(
            'INSERT INTO coordination_messages (parent_id, sender_id, sender_role, recipient_role, subject, body) VALUES (?, ?, ?, ?, ?, ?)',
            [$parentId ?: null, Auth::id(), $role, self::COORDINATION_GROUP, $subject, $body]
        );
        if ($request->isAjax()) {
            $message = Database::fetchOne(
                'SELECT m.id, m.sender_id, m.sender_role, m.subject, m.body, m.created_at, u.nama_lengkap AS sender_name
                 FROM coordination_messages m JOIN users u ON u.id = m.sender_id WHERE m.id = ?',
                [(int) Database::lastInsertId()]
            );
            return (new Response())->json(['message' => $message], 201);
        }
        Session::flash('success', 'Pesan koordinasi berhasil dikirim.');
        redirect($this->coordinationPath());
    }

    private function page(string $title, string $section, array $data): Response
    {
        $layout = Auth::role() === 'admin' ? 'admin' : 'dashboard';
        return view('workspaces.index', array_merge([
            'pageTitle' => $title . ' - GMKI Cabang Padang',
            'section' => $section,
            'user' => Auth::user(),
        ], $data), $layout);
    }

    private function pagination(Request $request, string $countSql, array $params = []): array
    {
        $total = (int) (Database::fetchOne($countSql, $params)['total'] ?? 0);
        $perPage = 25;
        $totalPages = max(1, (int) ceil($total / $perPage));
        $currentPage = max(1, min($totalPages, (int) $request->query('page', 1)));

        return [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'total' => $total,
            'perPage' => $perPage,
            'offset' => ($currentPage - 1) * $perPage,
        ];
    }

    private function fail(string $path, string $message): Response
    {
        Session::flash('error', $message);
        redirect($path);
        return new Response();
    }

    private function coordinationPath(): string
    {
        return match (Auth::role()) {
            'admin' => '/admin/koordinasi',
            'ketcab' => '/ketcab/koordinasi',
            default => '/ruang-kerja/koordinasi',
        };
    }

    private function coordinationMessagesPath(): string
    {
        return match (Auth::role()) {
            'admin' => '/admin/koordinasi/pesan',
            'ketcab' => '/ketcab/koordinasi/pesan',
            default => '/ruang-kerja/koordinasi/pesan',
        };
    }

    private function coordinationParticipants(): array
    {
        return [
            'ketcab' => 'Ketua Cabang',
            'sekcab' => 'Sekretaris Cabang',
            'bencab' => 'Bendahara Cabang',
            'sekfung_medko' => 'Sekfung Medko',
        ];
    }

    private function coordinationFailure(Request $request, string $message): Response
    {
        if ($request->isAjax()) {
            return (new Response())->json(['error' => $message], 422);
        }

        Session::flash('error', $message);
        redirect($this->coordinationPath());
        return new Response();
    }

    private function storeDocument(Request $request, string $field): array
    {
        if (!$request->hasFile($field)) {
            throw new \RuntimeException('Pilih berkas dokumen yang akan diunggah.');
        }
        $upload = $request->file($field);
        if (($upload['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || (int) $upload['size'] > 10 * 1024 * 1024) {
            throw new \RuntimeException('Unggahan gagal atau ukuran berkas melebihi 10 MB.');
        }

        $extension = strtolower(pathinfo((string) $upload['name'], PATHINFO_EXTENSION));
        if (!isset(self::DOCUMENT_EXTENSIONS[$extension])) {
            throw new \RuntimeException('Format yang didukung: PDF, Word, Excel, ODT, JPG, atau PNG.');
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($upload['tmp_name']);
        if (!is_string($mime) || !in_array($mime, self::DOCUMENT_EXTENSIONS[$extension], true)) {
            throw new \RuntimeException('Isi berkas tidak sesuai dengan ekstensi yang diunggah.');
        }

        $directory = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'documents';
        $backupDirectory = $directory . DIRECTORY_SEPARATOR . 'backups';
        if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
            throw new \RuntimeException('Folder penyimpanan dokumen tidak dapat disiapkan.');
        }
        if (!is_dir($backupDirectory) && !mkdir($backupDirectory, 0750, true) && !is_dir($backupDirectory)) {
            throw new \RuntimeException('Folder duplikasi dokumen cadangan tidak dapat disiapkan.');
        }

        $storedName = bin2hex(random_bytes(16)) . '.' . $extension;
        $destPath = $directory . DIRECTORY_SEPARATOR . $storedName;

        if (!move_uploaded_file($upload['tmp_name'], $destPath)) {
            if (!copy($upload['tmp_name'], $destPath)) {
                throw new \RuntimeException('Berkas gagal dipindahkan ke penyimpanan privat.');
            }
            @unlink($upload['tmp_name']);
        }

        // Duplikasi otomatis identik untuk redundansi keamanan
        $backupStoredName = 'bak_' . $storedName;
        $backupDestPath = $backupDirectory . DIRECTORY_SEPARATOR . $backupStoredName;
        if (!copy($destPath, $backupDestPath)) {
            throw new \RuntimeException('Sistem gagal membuat salinan duplikasi cadangan berkas.');
        }

        $fileHash = hash_file('sha256', $destPath) ?: '';

        $originalName = trim(str_replace(["\0", "\r", "\n"], '', basename((string) $upload['name'])));
        return [
            'stored_name' => $storedName,
            'backup_stored_name' => $backupStoredName,
            'file_hash' => $fileHash,
            'original_name' => mb_strcut($originalName ?: 'dokumen.' . $extension, 0, 255, 'UTF-8'),
            'mime_type' => $mime,
        ];
    }

    private function documentPath(string $storedName): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR
            . 'private' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . basename($storedName);
    }

    private function removeDocumentFile(string $storedName, ?string $backupStoredName = null): void
    {
        $path = $this->documentPath($storedName);
        if (is_file($path) && !unlink($path)) {
            error_log('Gagal menghapus berkas workspace: ' . basename($storedName));
        }

        $backupName = $backupStoredName ?: ('bak_' . basename($storedName));
        $backupPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR . basename($backupName);
        if (is_file($backupPath)) {
            @unlink($backupPath);
        }
    }
}
