<?php
$role = $user['role'] ?? '';
$backPath = match ($role) {
    'admin' => '/admin/dashboard',
    'ketcab' => '/ketcab/dashboard',
    default => '/ruang-kerja',
};
$backLabel = $role === 'admin' ? 'Dashboard Admin' : 'Ruang Kerja';
?>

<div class="file-directory-page">
    <!-- Header Halaman -->
    <header class="flex justify-between items-center" style="margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <p class="text-muted" style="margin:0;"><a href="<?= e($backPath) ?>" style="color:var(--text-muted);text-decoration:none;"><?= e($backLabel) ?></a> / Tools / Direktori Berkas Laporan</p>
            <h1 style="font-size:1.75rem;font-weight:800;color:var(--primary);margin:.25rem 0 0.5rem;">
                Direktori &amp; Pencarian Berkas Laporan
            </h1>
            <p class="text-muted" style="margin:0;font-size:0.9rem;">
                Pusat penelusuran, pencarian cepat, dan pemantauan seluruh file berkas laporan serta dokumen yang tersimpan dalam sistem.
            </p>
        </div>
        <div class="flex items-center gap-2" style="flex-wrap:wrap;">
            <?php if (in_array($role, ['bencab', 'admin'], true)): ?>
                <a href="/bencab/laporan" class="btn btn-primary btn-sm flex items-center gap-1" style="text-decoration:none;">
                    <?= svg_icon('plus', 16) ?>
                    <span>Unggah Laporan Baru</span>
                </a>
            <?php endif; ?>
            <a href="/ruang-kerja/antrian" class="btn btn-outline btn-sm flex items-center gap-1" style="text-decoration:none;">
                <?= svg_icon('chart', 16) ?>
                <span>Antrean Ekspor CSV</span>
            </a>
        </div>
    </header>

    <!-- Ringkasan Metrik Berkas -->
    <div class="metrics-grid" style="margin-bottom:1.5rem;">
        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Total Berkas Tersimpan</span>
                <span class="metric-value"><?= number_format((int)($stats['total'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">File dokumen di sistem</span>
            </div>
            <div class="metric-icon-wrap metric-icon-primary">
                <?= svg_icon('file', 24) ?>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Laporan Keuangan</span>
                <span class="metric-value"><?= number_format((int)($stats['finance'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Laporan Kas &amp; Finansial</span>
            </div>
            <div class="metric-icon-wrap" style="background:#dcfce7;color:#16a34a;">
                💰
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Arsip Dokumen / Surat</span>
                <span class="metric-value"><?= number_format((int)($stats['archive'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Arsip Administrasi Cabang</span>
            </div>
            <div class="metric-icon-wrap" style="background:#fef3c7;color:#d97706;">
                🗂️
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Ruang Disk Terpakai</span>
                <span class="metric-value" style="font-size:1.5rem;"><?= e($stats['storage_used'] ?? '0 B') ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Penyimpanan lokal aman</span>
            </div>
            <div class="metric-icon-wrap" style="background:#f3e8ff;color:#9333ea;">
                💾
            </div>
        </div>
    </div>

    <!-- Search Engine & Filter Console -->
    <div class="card search-console-card" style="margin-bottom:1.5rem;border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);">
        <div class="card-body" style="padding:1.25rem 1.5rem;">
            <form method="get" action="/ruang-kerja/berkas" id="fileSearchForm" class="flex flex-col gap-3">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                    <!-- Search Input Box -->
                    <div class="md:col-span-6 relative">
                        <label for="searchQuery" style="display:block;font-size:0.8125rem;font-weight:700;color:var(--text-muted);margin-bottom:0.25rem;">
                            Cari Berkas Laporan / Dokumen
                        </label>
                        <div style="position:relative;display:flex;align-items:center;">
                            <span style="position:absolute;left:12px;color:#94a3b8;pointer-events:none;display:flex;align-items:center;">
                                <?= svg_icon('search', 18) ?>
                            </span>
                            <input 
                                type="text" 
                                id="searchQuery" 
                                name="q" 
                                value="<?= e($query ?? '') ?>" 
                                class="form-control" 
                                placeholder="Ketik nama berkas, judul laporan, periode, atau nama pengunggah..." 
                                style="padding-left:2.5rem;padding-right:2.5rem;width:100%;height:42px;border-radius:var(--radius-md);font-size:0.9rem;"
                                autocomplete="off"
                            >
                            <?php if (!empty($query)): ?>
                                <a href="/ruang-kerja/berkas" title="Hapus pencarian" style="position:absolute;right:12px;color:#94a3b8;text-decoration:none;font-weight:bold;cursor:pointer;">✕</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="md:col-span-2">
                        <label for="filterCategory" style="display:block;font-size:0.8125rem;font-weight:700;color:var(--text-muted);margin-bottom:0.25rem;">
                            Kategori Dokumen
                        </label>
                        <select id="filterCategory" name="category" class="form-control" style="height:42px;border-radius:var(--radius-md);font-size:0.875rem;">
                            <option value="all" <?= ($category ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Kategori</option>
                            <option value="finance" <?= ($category ?? '') === 'finance' ? 'selected' : '' ?>>Laporan Keuangan</option>
                            <?php if ($role !== 'bencab'): ?>
                                <option value="archive" <?= ($category ?? '') === 'archive' ? 'selected' : '' ?>>Arsip Surat</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Filter Format Berkas -->
                    <div class="md:col-span-2">
                        <label for="filterExt" style="display:block;font-size:0.8125rem;font-weight:700;color:var(--text-muted);margin-bottom:0.25rem;">
                            Format Berkas
                        </label>
                        <select id="filterExt" name="ext" class="form-control" style="height:42px;border-radius:var(--radius-md);font-size:0.875rem;">
                            <option value="all" <?= ($extension ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Format</option>
                            <option value="pdf" <?= ($extension ?? '') === 'pdf' ? 'selected' : '' ?>>PDF (.pdf)</option>
                            <option value="excel" <?= ($extension ?? '') === 'excel' ? 'selected' : '' ?>>Excel (.xlsx, .xls)</option>
                            <option value="word" <?= ($extension ?? '') === 'word' ? 'selected' : '' ?>>Word (.docx, .doc)</option>
                            <option value="images" <?= ($extension ?? '') === 'images' ? 'selected' : '' ?>>Gambar (.jpg, .png)</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="md:col-span-2 flex items-end gap-2" style="margin-top:auto;">
                        <button type="submit" class="btn btn-primary flex items-center justify-center gap-1" style="height:42px;flex:1;">
                            <?= svg_icon('search', 16) ?>
                            <span>Cari</span>
                        </button>
                        <a href="/ruang-kerja/berkas" class="btn btn-outline flex items-center justify-center" title="Reset Pencarian" style="height:42px;width:42px;padding:0;">
                            <?= svg_icon('refresh', 16) ?>
                        </a>
                    </div>
                </div>

                <!-- Live Quick Filter Pills -->
                <div class="flex items-center gap-2" style="flex-wrap:wrap;padding-top:0.5rem;border-top:1px dashed #e2e8f0;margin-top:0.25rem;">
                    <span style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.04em;">Pintasan Cepat:</span>
                    <button type="button" class="quick-pill active" data-filter="all">Semua</button>
                    <button type="button" class="quick-pill" data-filter="finance">Laporan Keuangan</button>
                    <?php if ($role !== 'bencab'): ?>
                        <button type="button" class="quick-pill" data-filter="archive">Arsip Surat</button>
                    <?php endif; ?>
                    <button type="button" class="quick-pill" data-filter="pdf">PDF</button>
                    <button type="button" class="quick-pill" data-filter="excel">Excel</button>
                    <button type="button" class="quick-pill" data-filter="word">Word</button>
                    <span class="live-counter text-muted" style="margin-left:auto;font-size:0.8125rem;">
                        Menampilkan <strong id="visibleCount" style="color:var(--primary);"><?= count($documents) ?></strong> dari <span id="totalCount"><?= (int)($stats['total'] ?? count($documents)) ?></span> berkas
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Berkas yang Disimpan -->
    <div class="table-card" style="box-shadow:var(--shadow-sm);border-radius:var(--radius-lg);overflow:hidden;">
        <div class="table-header flex items-center justify-between" style="background:#ffffff;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-color);">
            <div>
                <h2 class="table-title" style="margin:0;font-size:1.125rem;font-weight:700;color:var(--text-main);">
                    Daftar Berkas Terarsip
                </h2>
                <p class="text-muted" style="margin:0.25rem 0 0;font-size:0.8125rem;">
                    Daftar seluruh dokumen fisik dan metadata laporan yang telah disimpan di direktori internal.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span id="instantFilterBadge" class="badge badge-primary" style="display:none;font-size:0.75rem;">Filter Aktif</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table" id="filesDataTable">
                <thead>
                    <tr>
                        <th style="width:50px;text-align:center;">#</th>
                        <th style="min-width:240px;">Berkas &amp; Format</th>
                        <th style="min-width:200px;">Judul &amp; Keterangan</th>
                        <th style="min-width:140px;">Kategori</th>
                        <th style="min-width:100px;">Ukuran</th>
                        <th style="min-width:150px;">Pengunggah</th>
                        <th style="min-width:140px;">Waktu Simpan</th>
                        <th style="min-width:110px;text-align:center;">Status</th>
                        <th style="min-width:130px;text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="filesTableBody">
                    <?php if (!empty($documents)): ?>
                        <?php foreach ($documents as $index => $doc): ?>
                            <?php
                            $ext = strtolower($doc['extension'] ?? '');
                            $extBadgeColor = match ($ext) {
                                'pdf' => ['bg' => '#fee2e2', 'color' => '#dc2626', 'label' => 'PDF'],
                                'xls', 'xlsx' => ['bg' => '#dcfce7', 'color' => '#16a34a', 'label' => 'EXCEL'],
                                'doc', 'docx', 'odt' => ['bg' => '#dbeafe', 'color' => '#2563eb', 'label' => 'WORD'],
                                'jpg', 'jpeg', 'png' => ['bg' => '#f3e8ff', 'color' => '#9333ea', 'label' => 'GAMBAR'],
                                'csv' => ['bg' => '#fef3c7', 'color' => '#d97706', 'label' => 'CSV'],
                                default => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => strtoupper($ext ?: 'FILE')],
                            };
                            $categoryLabel = ($doc['document_type'] === 'finance') ? 'Laporan Keuangan' : 'Arsip Surat';
                            $categoryClass = ($doc['document_type'] === 'finance') ? 'badge-primary' : 'badge-neutral';
                            $rowNum = ($pagination['offset'] ?? 0) + $index + 1;
                            ?>
                            <tr class="file-row" 
                                data-title="<?= e(strtolower($doc['title'])) ?>" 
                                data-filename="<?= e(strtolower($doc['original_name'])) ?>" 
                                data-category="<?= e($doc['document_type']) ?>" 
                                data-ext="<?= e($ext) ?>"
                                data-uploader="<?= e(strtolower($doc['nama_lengkap'])) ?>"
                                data-period="<?= e(strtolower($doc['period'] ?? '')) ?>"
                                data-description="<?= e(strtolower($doc['description'] ?? '')) ?>"
                            >
                                <td style="text-align:center;font-weight:600;color:var(--text-muted);font-size:0.8125rem;">
                                    <?= $rowNum ?>
                                </td>
                                <td>
                                    <div class="flex items-start gap-2">
                                        <span class="file-type-pill" style="background:<?= $extBadgeColor['bg'] ?>;color:<?= $extBadgeColor['color'] ?>;">
                                            <?= $extBadgeColor['label'] ?>
                                        </span>
                                        <div style="min-width:0;max-width:240px;">
                                            <a href="<?= e($doc['download_url']) ?>" class="file-link-title font-semibold text-truncate" title="<?= e($doc['original_name']) ?>" style="display:block;color:var(--primary);text-decoration:none;font-size:0.875rem;">
                                                <?= e($doc['original_name']) ?>
                                            </a>
                                            <div class="text-muted text-xs text-truncate" title="Nama sistem: <?= e($doc['stored_name']) ?>" style="font-family:monospace;font-size:0.75rem;margin-top:2px;">
                                                <?= e($doc['stored_name']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-semibold" style="color:var(--text-main);font-size:0.875rem;">
                                        <?= e($doc['title']) ?>
                                    </div>
                                    <?php if (!empty($doc['period'])): ?>
                                        <div class="text-xs" style="color:var(--secondary);font-weight:600;margin-top:2px;">
                                            🗓️ <?= e($doc['period']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($doc['description'])): ?>
                                        <div class="text-muted text-xs text-truncate" style="max-width:260px;margin-top:2px;" title="<?= e($doc['description']) ?>">
                                            <?= e($doc['description']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $categoryClass ?>" style="font-size:0.75rem;padding:0.25rem 0.5rem;">
                                        <?= $categoryLabel ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted font-medium" style="font-size:0.8125rem;">
                                        <?= e($doc['file_size_formatted'] ?? '0 B') ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight:600;font-size:0.8125rem;color:var(--text-main);">
                                        <?= e($doc['nama_lengkap']) ?>
                                    </div>
                                    <div class="text-muted text-xs" style="text-transform:capitalize;">
                                        <?= e($doc['uploader_role'] ?? 'Pengurus') ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:0.8125rem;color:var(--text-main);">
                                        <?= date('d M Y', strtotime($doc['created_at'])) ?>
                                    </div>
                                    <div class="text-muted text-xs">
                                        <?= date('H:i', strtotime($doc['created_at'])) ?> WIB
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    <?php if (!empty($doc['file_exists'])): ?>
                                        <span class="badge badge-success" title="Berkas fisik tersedia di server" style="font-size:0.75rem;">
                                            ✓ Ada
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger" title="Berkas fisik tidak ditemukan di folder storage" style="font-size:0.75rem;">
                                            ✕ Hilang
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="<?= e($doc['download_url']) ?>" class="btn btn-outline btn-sm action-btn" title="Unduh Berkas Ini" style="padding:4px 8px;">
                                            <?= svg_icon('download', 14) ?>
                                        </a>
                                        <button 
                                            type="button" 
                                            class="btn btn-outline btn-sm action-btn btn-inspect-file" 
                                            title="Inspeksi Metadata Berkas"
                                            style="padding:4px 8px;"
                                            data-id="<?= (int)$doc['id'] ?>"
                                            data-title="<?= e($doc['title']) ?>"
                                            data-originalname="<?= e($doc['original_name']) ?>"
                                            data-storedname="<?= e($doc['stored_name']) ?>"
                                            data-category="<?= e($categoryLabel) ?>"
                                            data-mimetype="<?= e($doc['mime_type']) ?>"
                                            data-filesize="<?= e($doc['file_size_formatted'] ?? '0 B') ?>"
                                            data-rawsize="<?= (int)($doc['file_size'] ?? 0) ?>"
                                            data-uploader="<?= e($doc['nama_lengkap']) ?>"
                                            data-createdat="<?= e($doc['created_at']) ?>"
                                            data-period="<?= e($doc['period'] ?? '-') ?>"
                                            data-downloadurl="<?= e($doc['download_url']) ?>"
                                        >
                                            <?= svg_icon('eye', 14) ?>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="emptyTableRow">
                            <td colspan="9" style="text-align:center;padding:3rem 1.5rem;">
                                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.75rem;">
                                    <div style="font-size:2.5rem;background:#f1f5f9;width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                        📂
                                    </div>
                                    <div style="font-size:1.1rem;font-weight:700;color:var(--text-main);">
                                        <?= !empty($query) ? 'Tidak ada berkas yang cocok dengan pencarian' : 'Belum Ada Berkas yang Disimpan' ?>
                                    </div>
                                    <p class="text-muted" style="margin:0;max-width:400px;font-size:0.875rem;">
                                        <?= !empty($query) 
                                            ? 'Kata kunci "' . e($query) . '" tidak menghasilkan berkas laporan. Coba periksa ejaan atau gunakan filter lain.' 
                                            : 'Saat ini belum ada berkas laporan keuangan atau dokumen arsip yang diunggah ke dalam sistem.' ?>
                                    </p>
                                    <div class="flex gap-2" style="margin-top:0.5rem;">
                                        <?php if (!empty($query) || ($category ?? 'all') !== 'all' || ($extension ?? 'all') !== 'all'): ?>
                                            <a href="/ruang-kerja/berkas" class="btn btn-outline btn-sm">
                                                Reset Filter Pencarian
                                            </a>
                                        <?php endif; ?>
                                        <?php if (in_array($role, ['bencab', 'admin'], true)): ?>
                                            <a href="/bencab/laporan" class="btn btn-primary btn-sm">
                                                Unggah Laporan Sekarang
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <!-- Baris jika client-side filter tidak menemukan hasil -->
                    <tr id="noMatchClientRow" style="display:none;">
                        <td colspan="9" style="text-align:center;padding:3rem 1.5rem;">
                            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.75rem;">
                                <div style="font-size:2rem;">🔍</div>
                                <div style="font-size:1.05rem;font-weight:700;color:var(--text-main);">
                                    Tidak ada berkas yang cocok dengan filter saat ini
                                </div>
                                <p class="text-muted" style="margin:0;font-size:0.875rem;">
                                    Ubah kata kunci pencarian atau reset filter untuk melihat semua berkas.
                                </p>
                                <button type="button" class="btn btn-outline btn-sm" id="btnResetClientSearch">
                                    Reset Pencarian
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1): ?>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border-color);background:#fafafa;">
                <?= partial('partials.pagination', $pagination) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Dialog Inspeksi Metadata Berkas -->
<div id="fileDetailModal" class="file-modal-backdrop" style="display:none;">
    <div class="file-modal-dialog">
        <div class="file-modal-header flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span style="font-size:1.25rem;">📄</span>
                <div>
                    <h3 id="modalFileTitle" style="margin:0;font-size:1.1rem;font-weight:700;color:var(--text-main);">
                        Detail Informasi Berkas
                    </h3>
                    <span id="modalFileCategory" class="badge badge-primary" style="font-size:0.7rem;margin-top:2px;"></span>
                </div>
            </div>
            <button type="button" class="modal-close-btn" id="modalCloseBtn" aria-label="Tutup Modal">✕</button>
        </div>

        <div class="file-modal-body">
            <div class="file-meta-item">
                <span class="file-meta-label">Nama Berkas Asli</span>
                <span class="file-meta-value font-semibold" id="modalOriginalName" style="color:var(--primary);word-break:break-all;"></span>
            </div>

            <div class="file-meta-item">
                <span class="file-meta-label">Nama Penyimpanan di Sistem</span>
                <span class="file-meta-value" id="modalStoredName" style="font-family:monospace;background:#f1f5f9;padding:2px 6px;border-radius:4px;word-break:break-all;font-size:0.8rem;"></span>
            </div>

            <div class="grid grid-cols-2 gap-3" style="margin-top:0.75rem;">
                <div class="file-meta-item">
                    <span class="file-meta-label">Ukuran Berkas</span>
                    <span class="file-meta-value font-semibold" id="modalFileSize"></span>
                </div>
                <div class="file-meta-item">
                    <span class="file-meta-label">Tipe MIME</span>
                    <span class="file-meta-value" id="modalMimeType" style="font-family:monospace;font-size:0.8rem;"></span>
                </div>
            </div>

            <div class="file-meta-item" style="margin-top:0.75rem;">
                <span class="file-meta-label">Periode</span>
                <span class="file-meta-value font-semibold" id="modalPeriod"></span>
            </div>

            <div class="grid grid-cols-2 gap-3" style="margin-top:0.75rem;">
                <div class="file-meta-item">
                    <span class="file-meta-label">Diunggah Oleh</span>
                    <span class="file-meta-value font-semibold" id="modalUploader"></span>
                </div>
                <div class="file-meta-item">
                    <span class="file-meta-label">Waktu Pengunggahan</span>
                    <span class="file-meta-value" id="modalCreatedAt"></span>
                </div>
            </div>

            <div class="file-meta-item" style="margin-top:0.75rem;">
                <span class="file-meta-label">Lokasi Folder Server</span>
                <span class="file-meta-value" style="font-family:monospace;font-size:0.75rem;background:#f8fafc;padding:4px 8px;border-radius:4px;display:block;border:1px solid #e2e8f0;word-break:break-all;">
                    storage/private/documents/<span id="modalPathFilename"></span>
                </span>
            </div>
        </div>

        <div class="file-modal-footer flex items-center justify-between" style="padding:1rem 1.25rem;border-top:1px solid var(--border-color);background:#f8fafc;">
            <button type="button" class="btn btn-outline btn-sm" id="modalCancelBtn">
                Tutup
            </button>
            <a href="#" id="modalDownloadBtn" class="btn btn-primary btn-sm flex items-center gap-1" style="text-decoration:none;">
                <?= svg_icon('download', 16) ?>
                <span>Unduh Berkas Sekarang</span>
            </a>
        </div>
    </div>
</div>

<!-- Gaya Khusus Direktori Berkas Laporan -->
<style>
.file-directory-page {
    animation: fadeIn 0.25s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

.quick-pill {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    padding: 3px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}
.quick-pill:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.quick-pill.active {
    background: var(--primary);
    color: #ffffff;
    border-color: var(--primary);
    box-shadow: 0 2px 6px rgba(15, 61, 100, 0.2);
}

.file-type-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6875rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    padding: 3px 6px;
    border-radius: 4px;
    min-width: 44px;
    text-transform: uppercase;
    flex-shrink: 0;
}

.file-link-title:hover {
    text-decoration: underline;
    color: var(--primary-light, #1e5687);
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-sm);
    transition: all 0.15s ease;
}
.action-btn:hover {
    transform: translateY(-1px);
}

/* Modal Styling */
.file-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 1rem;
    animation: modalFadeIn 0.2s ease-out;
}
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
.file-modal-dialog {
    background: #ffffff;
    border-radius: var(--radius-lg);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    width: 100%;
    max-width: 540px;
    overflow: hidden;
    animation: modalSlideUp 0.25s ease-out;
}
@keyframes modalSlideUp {
    from { transform: translateY(12px) scale(0.98); }
    to { transform: translateY(0) scale(1); }
}
.file-modal-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
}
.file-modal-body {
    padding: 1.25rem 1.5rem;
    max-height: 70vh;
    overflow-y: auto;
}
.modal-close-btn {
    background: transparent;
    border: none;
    font-size: 1.25rem;
    color: #94a3b8;
    cursor: pointer;
    line-height: 1;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.15s ease;
}
.modal-close-btn:hover {
    color: #0f172a;
    background: #f1f5f9;
}
.file-meta-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}
.file-meta-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.file-meta-value {
    font-size: 0.875rem;
    color: var(--text-main);
}
.search-highlight {
    background-color: #fef08a;
    color: #854d0e;
    font-weight: 700;
    padding: 0 2px;
    border-radius: 2px;
}
</style>

<!-- Interaktivitas Pencarian Berkas Real-time & Modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchQuery');
    const tableBody = document.getElementById('filesTableBody');
    const rows = tableBody ? tableBody.querySelectorAll('.file-row') : [];
    const visibleCountEl = document.getElementById('visibleCount');
    const noMatchRow = document.getElementById('noMatchClientRow');
    const emptyTableRow = document.getElementById('emptyTableRow');
    const instantBadge = document.getElementById('instantFilterBadge');
    const quickPills = document.querySelectorAll('.quick-pill');
    const btnReset = document.getElementById('btnResetClientSearch');

    let activeFilter = 'all';

    function filterTable() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;

        rows.forEach(function(row) {
            const title = row.getAttribute('data-title') || '';
            const filename = row.getAttribute('data-filename') || '';
            const category = row.getAttribute('data-category') || '';
            const ext = row.getAttribute('data-ext') || '';
            const uploader = row.getAttribute('data-uploader') || '';
            const period = row.getAttribute('data-period') || '';
            const description = row.getAttribute('data-description') || '';

            const matchesText = !query || 
                title.includes(query) || 
                filename.includes(query) || 
                uploader.includes(query) || 
                period.includes(query) || 
                description.includes(query);

            let matchesCategory = true;
            if (activeFilter === 'finance') {
                matchesCategory = (category === 'finance');
            } else if (activeFilter === 'archive') {
                matchesCategory = (category === 'archive');
            } else if (activeFilter === 'pdf') {
                matchesCategory = (ext === 'pdf');
            } else if (activeFilter === 'excel') {
                matchesCategory = (ext === 'xls' || ext === 'xlsx');
            } else if (activeFilter === 'word') {
                matchesCategory = (ext === 'doc' || ext === 'docx' || ext === 'odt');
            }

            if (matchesText && matchesCategory) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (visibleCountEl) {
            visibleCountEl.textContent = visibleCount;
        }

        if (rows.length > 0) {
            if (visibleCount === 0) {
                if (noMatchRow) noMatchRow.style.display = '';
            } else {
                if (noMatchRow) noMatchRow.style.display = 'none';
            }
        }

        if (instantBadge) {
            instantBadge.style.display = (query || activeFilter !== 'all') ? 'inline-block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }

    quickPills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            quickPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            activeFilter = this.getAttribute('data-filter') || 'all';
            filterTable();
        });
    });

    if (btnReset) {
        btnReset.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            activeFilter = 'all';
            quickPills.forEach(p => p.classList.remove('active'));
            const allPill = document.querySelector('.quick-pill[data-filter="all"]');
            if (allPill) allPill.classList.add('active');
            filterTable();
        });
    }

    // Modal Handler
    const modal = document.getElementById('fileDetailModal');
    const closeBtn = document.getElementById('modalCloseBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');

    function closeModal() {
        if (modal) modal.style.display = 'none';
    }

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.style.display !== 'none') {
            closeModal();
        }
    });

    document.querySelectorAll('.btn-inspect-file').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const title = this.getAttribute('data-title') || 'Dokumen';
            const category = this.getAttribute('data-category') || 'Dokumen';
            const originalName = this.getAttribute('data-originalname') || '-';
            const storedName = this.getAttribute('data-storedname') || '-';
            const fileSize = this.getAttribute('data-filesize') || '-';
            const mimeType = this.getAttribute('data-mimetype') || '-';
            const uploader = this.getAttribute('data-uploader') || '-';
            const createdAt = this.getAttribute('data-createdat') || '-';
            const period = this.getAttribute('data-period') || '-';
            const downloadUrl = this.getAttribute('data-downloadurl') || '#';

            document.getElementById('modalFileTitle').textContent = title;
            document.getElementById('modalFileCategory').textContent = category;
            document.getElementById('modalOriginalName').textContent = originalName;
            document.getElementById('modalStoredName').textContent = storedName;
            document.getElementById('modalPathFilename').textContent = storedName;
            document.getElementById('modalFileSize').textContent = fileSize;
            document.getElementById('modalMimeType').textContent = mimeType;
            document.getElementById('modalUploader').textContent = uploader;
            document.getElementById('modalCreatedAt').textContent = createdAt;
            document.getElementById('modalPeriod').textContent = period;

            const dlBtn = document.getElementById('modalDownloadBtn');
            if (dlBtn) dlBtn.setAttribute('href', downloadUrl);

            if (modal) modal.style.display = 'flex';
        });
    });
});
</script>
