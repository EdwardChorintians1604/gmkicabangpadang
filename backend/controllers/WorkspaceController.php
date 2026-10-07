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
        $counts = [
            'members' => (int)(Database::fetchOne('SELECT COUNT(*) AS total FROM civitas')['total'] ?? 0),
            'inventory' => (int)(Database::fetchOne('SELECT COUNT(*) AS total FROM inventory_items')['total'] ?? 0),
            'archive' => (int)(Database::fetchOne("SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'archive'")['total'] ?? 0),
            'finance' => (int)(Database::fetchOne(
                ($role === 'bencab'
                    ? "SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'finance' AND created_by = ?"
                    : "SELECT COUNT(*) AS total FROM office_documents WHERE document_type = 'finance'"),
                $role === 'bencab' ? [Auth::id()] : []
            )['total'] ?? 0),
            'messages' => (int)(Database::fetchOne(
                'SELECT COUNT(*) AS total FROM coordination_messages WHERE recipient_role = ?',
                [$role]
            )['total'] ?? 0),
            'strategies' => (int)(Database::fetchOne('SELECT COUNT(*) AS total FROM organization_strategies')['total'] ?? 0),
        ];

        return view('workspaces.dashboard', [
            'pageTitle' => 'Ruang Kerja BPC - GMKI Cabang Padang',
            'counts' => $counts,
            'user' => Auth::user(),
        ], 'dashboard');
    }

    public function inventory(Request $request): Response
    {
        Authorization::authorize('inventory.view');
        $items = Database::fetchAll('SELECT * FROM inventory_items ORDER BY updated_at DESC, id DESC');
        return $this->page('Inventaris GMKI', 'inventory', ['items' => $items]);
    }

    public function saveInventory(Request $request): Response
    {
        Authorization::authorize('inventory.manage');
        $id = (int)$request->post('id', 0);
        $name = trim((string)$request->post('name'));
        $category = trim((string)$request->post('category'));
        $quantity = $request->post('quantity');
        $unit = trim((string)$request->post('unit'));
        $location = trim((string)$request->post('location'));
        $condition = (string)$request->post('item_condition', 'Baik');
        $notes = trim((string)$request->post('notes'));

        if (
            $name === '' || mb_strlen($name) > 180
            || $category === '' || mb_strlen($category) > 100
            || !is_numeric($quantity) || (float)$quantity < 0 || (float)$quantity > 99999999.99
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
                [$name, $category, (float)$quantity, $unit, $location ?: null, $condition, $notes ?: null, $id]
            );
            $message = 'Data inventaris berhasil diperbarui.';
        } else {
            Database::execute(
                'INSERT INTO inventory_items (name, category, quantity, unit, location, item_condition, notes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                [$name, $category, (float)$quantity, $unit, $location ?: null, $condition, $notes ?: null, Auth::id()]
            );
            $message = 'Inventaris berhasil ditambahkan.';
        }

        Session::flash('success', $message);
        redirect('/sekcab/inventaris');
    }

    public function deleteInventory(Request $request, string $id): Response
    {
        Authorization::authorize('inventory.manage');
        Database::execute('DELETE FROM inventory_items WHERE id = ?', [(int)$id]);
        Session::flash('success', 'Data inventaris berhasil dihapus.');
        redirect('/sekcab/inventaris');
    }

    public function archives(Request $request): Response
    {
        Authorization::authorize('archive.view');
        $documents = Database::fetchAll(
            "SELECT d.*, u.nama_lengkap FROM office_documents d JOIN users u ON u.id = d.created_by WHERE d.document_type = 'archive' ORDER BY d.created_at DESC"
        );
        return $this->page('Arsip Surat', 'archive', ['documents' => $documents]);
    }

    public function saveArchive(Request $request): Response
    {
        Authorization::authorize('archive.manage');
        $id = (int)$request->post('id', 0);
        $title = trim((string)$request->post('title'));
        $description = trim((string)$request->post('description'));
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
                "INSERT INTO office_documents (document_type, title, description, stored_name, original_name, mime_type, created_by) VALUES ('archive', ?, ?, ?, ?, ?, ?)",
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
        $sql = "SELECT d.*, u.nama_lengkap FROM office_documents d JOIN users u ON u.id = d.created_by WHERE d.document_type = 'finance'";
        $params = [];
        if ($isBendahara) {
            $sql .= ' AND d.created_by = ?';
            $params[] = Auth::id();
        }
        $sql .= ' ORDER BY d.created_at DESC';
        $documents = Database::fetchAll($sql, $params);
        return $this->page('Laporan Keuangan', 'finance', ['documents' => $documents]);
    }

    public function saveFinance(Request $request): Response
    {
        Authorization::authorize('finance.manage');
        $id = (int)$request->post('id', 0);
        $title = trim((string)$request->post('title'));
        $period = trim((string)$request->post('period'));
        $amount = $request->post('amount');
        $description = trim((string)$request->post('description'));
        if (
            $title === '' || mb_strlen($title) > 180
            || $period === '' || mb_strlen($period) > 40
            || !is_numeric($amount) || (float)$amount < 0 || (float)$amount > 9999999999999.99
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
            $fields = [$title, $description ?: null, $period, (float)$amount];
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
                "INSERT INTO office_documents (document_type, title, description, period, amount, stored_name, original_name, mime_type, created_by) VALUES ('finance', ?, ?, ?, ?, ?, ?, ?, ?)",
                [$title, $description ?: null, $period, (float)$amount, $file['stored_name'], $file['original_name'], $file['mime_type'], Auth::id()]
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
            [(int)$id, Auth::id()]
        );
        if (!$document) {
            return $this->fail('/bencab/laporan', 'Laporan tidak ditemukan atau bukan milik akun Anda.');
        }
        Database::execute('DELETE FROM office_documents WHERE id = ?', [(int)$id]);
        $this->removeDocumentFile($document['stored_name']);
        Session::flash('success', 'Laporan keuangan berhasil dihapus.');
        redirect('/bencab/laporan');
    }

    public function downloadDocument(Request $request, string $id): Response
    {
        $document = Database::fetchOne('SELECT * FROM office_documents WHERE id = ?', [(int)$id]);
        if (!$document) {
            http_response_code(404);
            return (new Response())->html('Dokumen tidak ditemukan.', 404);
        }
        if ($document['document_type'] === 'finance') {
            Authorization::authorize('reports.download');
            if (Auth::role() === 'bencab' && (int)$document['created_by'] !== Auth::id()) {
                Authorization::authorizeRole('admin');
            }
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

    public function deleteArchive(Request $request, string $id): Response
    {
        Authorization::authorize('archive.manage');
        $document = Database::fetchOne(
            "SELECT * FROM office_documents WHERE id = ? AND document_type = 'archive'",
            [(int)$id]
        );
        if (!$document) {
            return $this->fail('/sekcab/arsip', 'Dokumen arsip tidak ditemukan.');
        }
        Database::execute('DELETE FROM office_documents WHERE id = ?', [(int)$id]);
        $this->removeDocumentFile($document['stored_name']);
        Session::flash('success', 'Dokumen arsip berhasil dihapus.');
        redirect('/sekcab/arsip');
    }

    public function strategies(Request $request): Response
    {
        Authorization::authorize('strategy.view');
        $strategies = Database::fetchAll('SELECT * FROM organization_strategies ORDER BY target_date IS NULL, target_date, id DESC');
        return $this->page('Strategi Organisasi', 'strategies', ['strategies' => $strategies]);
    }

    public function saveStrategy(Request $request): Response
    {
        Authorization::authorize('strategy.manage');
        $id = (int)$request->post('id', 0);
        $title = trim((string)$request->post('title'));
        $objective = trim((string)$request->post('objective'));
        $status = (string)$request->post('status', 'Rencana');
        $targetDate = trim((string)$request->post('target_date'));
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
            Database::execute(
                'UPDATE organization_strategies SET title = ?, objective = ?, status = ?, target_date = ? WHERE id = ?',
                [$title, $objective, $status, $targetDate ?: null, $id]
            );
        } else {
            Database::execute(
                'INSERT INTO organization_strategies (title, objective, status, target_date, created_by) VALUES (?, ?, ?, ?, ?)',
                [$title, $objective, $status, $targetDate ?: null, Auth::id()]
            );
        }
        Session::flash('success', 'Catatan strategi berhasil disimpan.');
        redirect('/ketcab/strategi');
    }

    public function deleteStrategy(Request $request, string $id): Response
    {
        Authorization::authorize('strategy.manage');
        Database::execute('DELETE FROM organization_strategies WHERE id = ?', [(int)$id]);
        Session::flash('success', 'Catatan strategi berhasil dihapus.');
        redirect('/ketcab/strategi');
    }

    public function coordination(Request $request): Response
    {
        Authorization::authorize('coordination.view');
        $role = Auth::role();
        $messages = Database::fetchAll(
            'SELECT m.*, u.nama_lengkap AS sender_name FROM coordination_messages m JOIN users u ON u.id = m.sender_id WHERE m.recipient_role = ? OR m.sender_id = ? ORDER BY m.created_at DESC LIMIT 200',
            [$role, Auth::id()]
        );
        return $this->page('Ruang Koordinasi BPC', 'coordination', ['messages' => $messages]);
    }

    public function sendMessage(Request $request): Response
    {
        Authorization::authorize('coordination.manage');
        $role = Auth::role();
        $subject = trim((string)$request->post('subject'));
        $body = trim((string)$request->post('body'));
        $parentId = (int)$request->post('parent_id', 0);
        if ($subject === '' || mb_strlen($subject) > 180 || $body === '') {
            return $this->fail($this->coordinationPath(), 'Subjek dan isi pesan wajib diisi.');
        }

        $recipientRole = (string)$request->post('recipient_role');
        if ($parentId > 0) {
            $parent = Database::fetchOne(
                'SELECT * FROM coordination_messages WHERE id = ? AND (recipient_role = ? OR sender_id = ?)',
                [$parentId, $role, Auth::id()]
            );
            if (!$parent) {
                return $this->fail($this->coordinationPath(), 'Pesan yang ingin dibalas tidak ditemukan.');
            }
            $recipientRole = (int)$parent['sender_id'] === Auth::id()
                ? $parent['recipient_role']
                : $parent['sender_role'];
        } elseif ($role === 'ketcab' || $role === 'admin') {
            if (!in_array($recipientRole, ['sekcab', 'bencab', 'sekfung_medko'], true)) {
                return $this->fail($this->coordinationPath(), 'Pilih penerima BPC yang valid.');
            }
        } else {
            $recipientRole = 'ketcab';
        }

        Database::execute(
            'INSERT INTO coordination_messages (parent_id, sender_id, sender_role, recipient_role, subject, body) VALUES (?, ?, ?, ?, ?, ?)',
            [$parentId ?: null, Auth::id(), $role, $recipientRole, $subject, $body]
        );
        Session::flash('success', 'Pesan koordinasi berhasil dikirim.');
        redirect($this->coordinationPath());
    }

    private function page(string $title, string $section, array $data): Response
    {
        return view('workspaces.index', array_merge([
            'pageTitle' => $title . ' - GMKI Cabang Padang',
            'section' => $section,
            'user' => Auth::user(),
        ], $data), 'dashboard');
    }

    private function fail(string $path, string $message): Response
    {
        Session::flash('error', $message);
        redirect($path);
        return new Response();
    }

    private function coordinationPath(): string
    {
        return Auth::role() === 'ketcab' || Auth::role() === 'admin'
            ? '/ketcab/koordinasi'
            : '/ruang-kerja/koordinasi';
    }

    private function storeDocument(Request $request, string $field): array
    {
        if (!$request->hasFile($field)) {
            throw new \RuntimeException('Pilih berkas dokumen yang akan diunggah.');
        }
        $upload = $request->file($field);
        if (($upload['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || (int)$upload['size'] > 10 * 1024 * 1024) {
            throw new \RuntimeException('Unggahan gagal atau ukuran berkas melebihi 10 MB.');
        }

        $extension = strtolower(pathinfo((string)$upload['name'], PATHINFO_EXTENSION));
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

        $originalName = trim(str_replace(["\0", "\r", "\n"], '', basename((string)$upload['name'])));
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
