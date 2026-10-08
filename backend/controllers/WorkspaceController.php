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
        $pagination = $this->pagination($request, "SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'archive'");
        $documents = Database::fetchAll(
            "SELECT d.*, u.nama_lengkap FROM office_documents d JOIN users u ON u.id = d.created_by
             WHERE d.document_type = 'archive' ORDER BY d.created_at DESC, d.id DESC
             LIMIT " . $pagination['perPage'] . ' OFFSET ' . $pagination['offset']
        );
        return $this->page('Arsip Surat', 'archive', ['documents' => $documents, 'pagination' => $pagination]);
    }

    public function saveArchive(Request $request): Response
    {
        Authorization::authorize('archive.manage');
        $id = (int) $request->post('id', 0);
        $title = trim((string) $request->post('title'));
        $description = trim((string) $request->post('description'));
        if ($title === '' || mb_strlen($title) > 180) {
            return $this->fail('/sekcab/arsip', 'Judul dokumen wajib diisi.');
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

        try {
            $file = $request->hasFile('document') ? $this->storeDocument($request, 'document') : null;
        } catch (\RuntimeException $exception) {
            return $this->fail('/sekcab/arsip', $exception->getMessage());
        }

        if ($existing) {
            $fields = [$title, $description ?: null];
            $sql = 'UPDATE office_documents SET title = ?, description = ?';
            if ($file) {
                $sql .= ', stored_name = ?, original_name = ?, mime_type = ?';
                array_push($fields, $file['stored_name'], $file['original_name'], $file['mime_type']);
            }
            $sql .= " WHERE id = ? AND document_type = 'archive'";
            $fields[] = $id;
            Database::execute($sql, $fields);
            if ($file) {
                $this->removeDocumentFile($existing['stored_name']);
            }
            $message = 'Dokumen arsip berhasil diperbarui.';
        } else {
            if (!$file) {
                return $this->fail('/sekcab/arsip', 'Pilih berkas dokumen yang akan diunggah.');
            }
            Database::execute(
                "INSERT INTO office_documents (document_type, title, description, stored_name, original_name, mime_type, created_by, created_at) VALUES ('archive', ?, ?, ?, ?, ?, ?, NOW())",
                [$title, $description ?: null, $file['stored_name'], $file['original_name'], $file['mime_type'], Auth::id()]
            );
            $message = 'Dokumen berhasil disimpan ke arsip privat.';
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
        $amount = $request->post('amount');
        $description = trim((string) $request->post('description'));
        if (
            $title === '' || mb_strlen($title) > 180
            || $period === '' || mb_strlen($period) > 40
            || !is_numeric($amount) || (float) $amount < 0 || (float) $amount > 9999999999999.99
        ) {
            return $this->fail('/bencab/laporan', 'Judul, periode, dan jumlah keuangan yang valid wajib diisi.');
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
            $fields = [$title, $description ?: null, $period, (float) $amount];
            $sql = 'UPDATE office_documents SET title = ?, description = ?, period = ?, amount = ?';
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
                "INSERT INTO office_documents (document_type, title, description, period, amount, stored_name, original_name, mime_type, created_by, created_at) VALUES ('finance', ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [$title, $description ?: null, $period, (float) $amount, $file['stored_name'], $file['original_name'], $file['mime_type'], Auth::id()]
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
        } else {
            Authorization::authorize('archive.view');
        }

        $path = $this->documentPath($document['stored_name']);
        if (!is_file($path)) {
            error_log('Berkas workspace hilang dari penyimpanan: ' . $document['stored_name']);
            http_response_code(404);
            return (new Response())->html('Berkas tidak tersedia di penyimpanan.', 404);
        }
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($document['original_name']));
        (new Response())->file($path, $safeName ?: 'dokumen', 'attachment');
        return new Response();
    }

    public function files(Request $request): Response
    {
        $role = Auth::role();
        if (!in_array($role, ['admin', 'ketcab', 'sekcab', 'bencab'], true)) {
            Authorization::authorizeRole(['admin', 'ketcab', 'sekcab', 'bencab']);
        }

        $query = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', 'all'));
        $extension = strtolower(trim((string) $request->query('ext', 'all')));
        $sort = trim((string) $request->query('sort', 'latest'));

        $where = [];
        $params = [];

        // Role-based visibility
        if ($role === 'bencab') {
            $where[] = "d.document_type = 'finance'";
        } elseif ($role === 'sekcab') {
            $where[] = "d.document_type = 'archive'";
        }

        // Category filter
        if ($category === 'finance') {
            $where[] = "d.document_type = 'finance'";
        } elseif ($category === 'archive') {
            $where[] = "d.document_type = 'archive'";
        }

        // Search engine filter (matches title, original_name, description, period, uploader name)
        if ($query !== '') {
            $where[] = "(d.title LIKE ? OR d.original_name LIKE ? OR d.description LIKE ? OR d.period LIKE ? OR u.nama_lengkap LIKE ?)";
            $term = '%' . $query . '%';
            array_push($params, $term, $term, $term, $term, $term);
        }

        // Extension filter
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

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $countSql = "SELECT COUNT(*) AS total 
                     FROM office_documents d 
                     JOIN users u ON u.id = d.created_by 
                     {$whereSql}";
        $pagination = $this->pagination($request, $countSql, $params);

        $orderSql = match ($sort) {
            'oldest' => 'ORDER BY d.created_at ASC, d.id ASC',
            'name_asc' => 'ORDER BY d.original_name ASC',
            'name_desc' => 'ORDER BY d.original_name DESC',
            'amount_desc' => 'ORDER BY d.amount DESC',
            'amount_asc' => 'ORDER BY d.amount ASC',
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
            $path = $this->documentPath($row['stored_name']);
            $fileExists = is_file($path);
            $fileSize = $fileExists ? (int) filesize($path) : 0;
            $ext = strtolower(pathinfo($row['original_name'], PATHINFO_EXTENSION));

            $row['file_exists'] = $fileExists;
            $row['file_size'] = $fileSize;
            $row['file_size_formatted'] = $this->formatBytes($fileSize);
            $row['extension'] = $ext;
            $row['download_url'] = '/dokumen/' . (int) $row['id'] . '/unduh';

            $documents[] = $row;
        }

        // Stats summary calculation
        $roleScope = '';
        $roleParams = [];
        if ($role === 'bencab') {
            $roleScope = "WHERE document_type = 'finance'";
        } elseif ($role === 'sekcab') {
            $roleScope = "WHERE document_type = 'archive'";
        }

        $totalCount = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents {$roleScope}", $roleParams)['total'] ?? 0);
        $financeCount = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'finance'")['total'] ?? 0);
        $archiveCount = (int) (Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'archive'")['total'] ?? 0);

        // Calculate total physical bytes stored on disk
        $allDocs = Database::fetchAll("SELECT stored_name FROM office_documents {$roleScope}", $roleParams);
        $totalStorageBytes = 0;
        foreach ($allDocs as $docItem) {
            $p = $this->documentPath($docItem['stored_name']);
            if (is_file($p)) {
                $totalStorageBytes += (int) filesize($p);
            }
        }

        $stats = [
            'total' => $totalCount,
            'finance' => $financeCount,
            'archive' => $archiveCount,
            'storage_used' => $this->formatBytes($totalStorageBytes),
            'storage_bytes' => $totalStorageBytes,
        ];

        $layout = Auth::role() === 'admin' ? 'admin' : 'dashboard';

        return view('workspaces.files', [
            'pageTitle' => 'Direktori & Pencarian Berkas Laporan - GMKI Cabang Padang',
            'documents' => $documents,
            'stats' => $stats,
            'query' => $query,
            'category' => $category,
            'extension' => $extension,
            'sort' => $sort,
            'pagination' => $pagination,
            'user' => Auth::user(),
        ], $layout);
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

    public function deleteArchive(Request $request, string $id): Response
    {
        Authorization::authorize('archive.manage');
        $document = Database::fetchOne(
            "SELECT * FROM office_documents WHERE id = ? AND document_type = 'archive'",
            [(int) $id]
        );
        if (!$document) {
            return $this->fail('/sekcab/arsip', 'Dokumen arsip tidak ditemukan.');
        }
        Database::execute('DELETE FROM office_documents WHERE id = ?', [(int) $id]);
        $this->removeDocumentFile($document['stored_name']);
        Session::flash('success', 'Dokumen arsip berhasil dihapus.');
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
        if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
            throw new \RuntimeException('Folder penyimpanan dokumen tidak dapat disiapkan.');
        }
        $storedName = bin2hex(random_bytes(16)) . '.' . $extension;
        if (!move_uploaded_file($upload['tmp_name'], $directory . DIRECTORY_SEPARATOR . $storedName)) {
            throw new \RuntimeException('Berkas gagal dipindahkan ke penyimpanan privat.');
        }

        $originalName = trim(str_replace(["\0", "\r", "\n"], '', basename((string) $upload['name'])));
        return [
            'stored_name' => $storedName,
            'original_name' => mb_strcut($originalName ?: 'dokumen.' . $extension, 0, 255, 'UTF-8'),
            'mime_type' => $mime,
        ];
    }

    private function documentPath(string $storedName): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR
            . 'private' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . basename($storedName);
    }

    private function removeDocumentFile(string $storedName): void
    {
        $path = $this->documentPath($storedName);
        if (is_file($path) && !unlink($path)) {
            error_log('Gagal menghapus berkas workspace: ' . basename($storedName));
        }
    }
}
