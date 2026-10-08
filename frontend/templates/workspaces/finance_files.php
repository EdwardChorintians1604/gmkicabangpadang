<?php
$role = $user['role'] ?? '';
$backPath = match ($role) {
    'admin' => '/admin/dashboard',
    'bencab' => '/ruang-kerja',
    default => '/ruang-kerja',
};
$backLabel = $role === 'admin' ? 'Dashboard Admin' : 'Dashboard Bencab';
?>

<div class="file-directory-page">
    <!-- Header Halaman -->
    <header class="flex justify-between items-center" style="margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <p class="text-muted" style="margin:0;">
                <a href="<?= e($backPath) ?>" style="color:var(--text-muted);text-decoration:none;"><?= e($backLabel) ?></a> 
                / Keuangan Cabang / <strong>Direktori Berkas Laporan Keuangan</strong>
            </p>
            <h1 style="font-size:1.75rem;font-weight:800;color:var(--primary);margin:.25rem 0 0.5rem;display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                <span>💰 Direktori Berkas Laporan Keuangan</span>
                <?php if ($role === 'admin'): ?>
                    <span class="badge badge-info" style="font-size:0.75rem;font-weight:700;">👁️ Mode Pantauan Admin (Read-Only)</span>
                <?php else: ?>
                    <span class="badge badge-success" style="font-size:0.75rem;font-weight:700;">Khusus Bencab</span>
                <?php endif; ?>
            </h1>
            <p class="text-muted" style="margin:0;font-size:0.9rem;">
                Repositori terisolasi untuk seluruh berkas kas, bukti transaksi, dan laporan pertanggungjawaban keuangan BPC GMKI Cabang Padang.
            </p>
        </div>
        <div class="flex items-center gap-2" style="flex-wrap:wrap;">
            <?php if ($role === 'admin'): ?>
                <a href="/admin/berkas/backup?type=finance" class="btn btn-primary btn-sm flex items-center gap-1" style="text-decoration:none;font-weight:700;">
                    <span>📦 Backup Berkas Keuangan (ZIP)</span>
                </a>
                <a href="/sekcab/berkas" class="btn btn-outline btn-sm flex items-center gap-1" style="text-decoration:none;" title="Beralih ke Direktori Berkas Persuratan Sekcab">
                    <span>🗂️ Direktori Persuratan Sekcab</span>
                </a>
            <?php else: ?>
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

    <?php if ($role === 'admin'): ?>
        <div class="card" style="margin-bottom: 1.5rem; border-left: 4px solid #3b82f6; background: #eff6ff;">
            <div class="card-body flex items-center justify-between" style="padding: 1rem 1.25rem; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <div style="font-weight: 700; color: #1e40af; font-size: 0.95rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span>👁️ Mode Pantauan Administrator (Read-Only)</span>
                    </div>
                    <p style="margin: 0.25rem 0 0; font-size: 0.825rem; color: #1e3a8a;">
                        Seluruh berkas laporan keuangan, kas, dan nota dikelola penuh oleh <strong>Bendahara Cabang (Bencab)</strong>. Tugas Administrator adalah memantau dan mencadangkan data (backup ZIP) secara berkala agar tidak hilang saat terjadi gangguan sistem.
                    </p>
                </div>
                <div>
                    <a href="/admin/berkas/backup?type=finance" class="btn btn-primary btn-sm" style="font-weight:700;text-decoration:none;">
                        ⬇️ Unduh Paket ZIP
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Ringkasan Metrik Berkas Keuangan -->
    <div class="metrics-grid" style="margin-bottom:1.5rem;">
        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Total Berkas Keuangan</span>
                <span class="metric-value"><?= number_format((int)($stats['total'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">File laporan terunggah</span>
            </div>
            <div class="metric-icon-wrap" style="background:#dcfce7;color:#16a34a;">
                💰
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Berkas Spreadsheet Excel</span>
                <span class="metric-value"><?= number_format((int)($stats['excel'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Format .xlsx / .xls</span>
            </div>
            <div class="metric-icon-wrap" style="background:#e0f2fe;color:#0284c7;">
                📊
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Dokumen Format PDF</span>
                <span class="metric-value"><?= number_format((int)($stats['pdf'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Laporan resmi &amp; nota</span>
            </div>
            <div class="metric-icon-wrap" style="background:#fee2e2;color:#dc2626;">
                📄
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Ruang Disk Terpakai</span>
                <span class="metric-value" style="font-size:1.5rem;"><?= e($stats['storage_used'] ?? '0 B') ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Brankas privat keuangan</span>
            </div>
            <div class="metric-icon-wrap" style="background:#f3e8ff;color:#9333ea;">
                💾
            </div>
        </div>
    </div>

    <!-- Search Engine & Filter Console Khusus Keuangan -->
    <div class="card search-console-card" style="margin-bottom:1.5rem;border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);">
        <div class="card-body" style="padding:1.25rem 1.5rem;">
            <form method="get" action="/bencab/berkas" id="fileSearchForm" class="flex flex-col gap-3">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                    <!-- Search Input Box -->
                    <div class="md:col-span-5 relative">
                        <label for="searchQuery" style="display:block;font-size:0.8125rem;font-weight:700;color:var(--text-muted);margin-bottom:0.25rem;">
                            Cari Berkas Laporan Keuangan
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
                                placeholder="Cari judul laporan, keterangan, periode, atau nama berkas..." 
                                style="padding-left:2.5rem;padding-right:2.5rem;width:100%;height:42px;border-radius:var(--radius-md);font-size:0.9rem;"
                                autocomplete="off"
                            >
                            <?php if (!empty($query)): ?>
                                <a href="/bencab/berkas" title="Hapus pencarian" style="position:absolute;right:12px;color:#94a3b8;text-decoration:none;font-weight:bold;cursor:pointer;">✕</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Filter Periode Laporan -->
                    <div class="md:col-span-3">
                        <label for="filterPeriod" style="display:block;font-size:0.8125rem;font-weight:700;color:var(--text-muted);margin-bottom:0.25rem;">
                            Periode Laporan
                        </label>
                        <select id="filterPeriod" name="period" class="form-control" style="height:42px;border-radius:var(--radius-md);font-size:0.875rem;">
                            <option value="">Semua Periode</option>
                            <?php foreach ($periods as $p): ?>
                                <option value="<?= e($p) ?>" <?= ($period ?? '') === $p ? 'selected' : '' ?>>
                                    <?= e($p) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Format Berkas -->
                    <div class="md:col-span-2">
                        <label for="filterExt" style="display:block;font-size:0.8125rem;font-weight:700;color:var(--text-muted);margin-bottom:0.25rem;">
                            Format Berkas
                        </label>
                        <select id="filterExt" name="ext" class="form-control" style="height:42px;border-radius:var(--radius-md);font-size:0.875rem;">
                            <option value="all" <?= ($extension ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Format</option>
                            <option value="excel" <?= ($extension ?? '') === 'excel' ? 'selected' : '' ?>>Excel (.xlsx, .xls)</option>
                            <option value="pdf" <?= ($extension ?? '') === 'pdf' ? 'selected' : '' ?>>PDF (.pdf)</option>
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
                        <a href="/bencab/berkas" class="btn btn-outline flex items-center justify-center" title="Reset Pencarian" style="height:42px;width:42px;padding:0;">
                            <?= svg_icon('refresh', 16) ?>
                        </a>
                    </div>
                </div>

                <!-- Live Quick Filter Pills -->
                <div class="flex items-center gap-2" style="flex-wrap:wrap;padding-top:0.5rem;border-top:1px dashed #e2e8f0;margin-top:0.25rem;">
                    <span style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.04em;">Format Cepat:</span>
                    <button type="button" class="quick-pill <?= ($extension ?? 'all') === 'all' ? 'active' : '' ?>" onclick="location.href='/bencab/berkas'">Semua</button>
                    <button type="button" class="quick-pill <?= ($extension ?? '') === 'excel' ? 'active' : '' ?>" onclick="location.href='/bencab/berkas?ext=excel'">Excel (.xlsx)</button>
                    <button type="button" class="quick-pill <?= ($extension ?? '') === 'pdf' ? 'active' : '' ?>" onclick="location.href='/bencab/berkas?ext=pdf'">PDF</button>
                    <button type="button" class="quick-pill <?= ($extension ?? '') === 'word' ? 'active' : '' ?>" onclick="location.href='/bencab/berkas?ext=word'">Word</button>
                    <button type="button" class="quick-pill <?= ($extension ?? '') === 'images' ? 'active' : '' ?>" onclick="location.href='/bencab/berkas?ext=images'">Gambar</button>
                    <span class="live-counter text-muted" style="margin-left:auto;font-size:0.8125rem;">
                        Menampilkan <strong style="color:var(--primary);"><?= count($documents) ?></strong> berkas laporan keuangan
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Berkas Laporan Keuangan -->
    <div class="table-card" style="box-shadow:var(--shadow-sm);border-radius:var(--radius-lg);overflow:hidden;">
        <div class="table-header flex items-center justify-between" style="background:#ffffff;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-color);">
            <div>
                <h2 class="table-title" style="margin:0;font-size:1.125rem;font-weight:700;color:var(--text-main);">
                    Berkas Laporan Kas &amp; Pertanggungjawaban Finansial
                </h2>
                <p class="text-muted" style="margin:0.25rem 0 0;font-size:0.8125rem;">
                    Daftar dokumen fisik laporan keuangan yang terisolasi dan dikelola oleh Bendahara Cabang.
                </p>
            </div>
            <div>
                <span class="badge badge-success" style="font-size:0.8rem;padding:0.35rem 0.65rem;">
                    ✓ Terverifikasi Keuangan
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table" id="financeFilesTable">
                <thead>
                    <tr>
                        <th style="width:50px;text-align:center;">#</th>
                        <th style="min-width:240px;">Berkas Digital</th>
                        <th style="min-width:220px;">Judul Laporan &amp; Keterangan</th>
                        <th style="min-width:140px;text-align:center;">Periode</th>
                        <th style="min-width:110px;text-align:center;">Ukuran</th>
                        <th style="min-width:150px;">Pengunggah</th>
                        <th style="min-width:130px;text-align:center;">Tanggal Upload</th>
                        <th style="width:130px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($documents)): ?>
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem 1.5rem;">
                                <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;">
                                    <span style="font-size:2.5rem;">📊</span>
                                    <div style="font-weight:700;font-size:1.1rem;color:var(--text-main);">Belum ada berkas laporan keuangan yang cocok</div>
                                    <p class="text-muted" style="margin:0;max-width:400px;font-size:0.875rem;">
                                        Belum ada dokumen yang sesuai dengan kriteria pencarian atau filter yang Anda pilih.
                                    </p>
                                    <div class="flex gap-2" style="margin-top:0.5rem;">
                                        <a href="/bencab/berkas" class="btn btn-outline btn-sm">Reset Filter</a>
                                        <?php if ($role === 'bencab'): ?>
                                            <a href="/bencab/laporan" class="btn btn-primary btn-sm">+ Unggah Laporan Baru</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $no = 1 + ($pagination['offset'] ?? 0);
                        foreach ($documents as $doc): 
                            $ext = $doc['extension'];
                            $fileIcon = match ($ext) {
                                'pdf' => '📄',
                                'xls', 'xlsx' => '📊',
                                'doc', 'docx', 'odt' => '📝',
                                'jpg', 'jpeg', 'png', 'webp' => '🖼️',
                                default => '📎',
                            };
                            $badgeColor = match ($ext) {
                                'xlsx', 'xls' => 'background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;',
                                'pdf' => 'background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;',
                                'docx', 'doc' => 'background:#e0e7ff;color:#4338ca;border:1px solid #c7d2fe;',
                                default => 'background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;',
                            };
                        ?>
                            <tr>
                                <td style="text-align:center;color:var(--text-muted);font-weight:600;">
                                    <?= $no++ ?>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div style="font-size:1.6rem;line-height:1;"><?= $fileIcon ?></div>
                                        <div style="min-width:0;">
                                            <a href="<?= e($doc['download_url']) ?>" 
                                               style="font-weight:700;color:var(--primary);text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px;"
                                               title="Unduh <?= e($doc['original_name'] ?: 'Dokumen') ?>">
                                                <?= e($doc['original_name'] ?: 'dokumen-keuangan.' . $ext) ?>
                                            </a>
                                            <span style="display:inline-block;padding:0.1rem 0.4rem;border-radius:4px;font-size:0.7rem;font-weight:700;text-transform:uppercase;margin-top:0.2rem;<?= $badgeColor ?>">
                                                .<?= e($ext ?: 'file') ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight:700;color:var(--text-main);font-size:0.925rem;margin-bottom:0.2rem;">
                                        <?= e($doc['title']) ?>
                                    </div>
                                    <?php if (!empty($doc['description'])): ?>
                                        <div style="font-size:0.8rem;color:var(--text-muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;" title="<?= e($doc['description']) ?>">
                                            <?= e($doc['description']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php if (!empty($doc['period'])): ?>
                                        <span class="badge badge-info" style="font-weight:600;font-size:0.775rem;">
                                            📅 <?= e($doc['period']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size:0.8rem;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:center;">
                                    <span style="font-size:0.825rem;font-weight:600;color:var(--text-main);">
                                        <?= e($doc['file_size_formatted']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size:0.85rem;font-weight:600;color:var(--text-main);">
                                        <?= e($doc['nama_lengkap'] ?? 'Bendahara Cabang') ?>
                                    </div>
                                    <span style="font-size:0.725rem;color:var(--text-muted);text-transform:uppercase;">
                                        <?= e($doc['uploader_role'] ?? 'bencab') ?>
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <div style="font-size:0.825rem;color:var(--text-main);">
                                        <?= date('d M Y', strtotime($doc['created_at'])) ?>
                                    </div>
                                    <span style="font-size:0.725rem;color:var(--text-muted);">
                                        <?= date('H:i', strtotime($doc['created_at'])) ?> WIB
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="<?= e($doc['download_url']) ?>" 
                                           class="btn btn-outline btn-sm" 
                                           style="padding:0.25rem 0.55rem;font-size:0.775rem;font-weight:600;" 
                                           title="Unduh Berkas Laporan">
                                            ⬇️ Unduh
                                        </a>
                                        <?php if ($role === 'bencab'): ?>
                                            <a href="/bencab/laporan" 
                                               class="btn btn-secondary btn-sm" 
                                               style="padding:0.25rem 0.55rem;font-size:0.775rem;" 
                                               title="Buka Halaman Rekap Laporan">
                                                📊
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1): ?>
            <div style="padding:0.85rem 1.5rem;border-top:1px solid var(--border-color);background:#f8fafc;">
                <?= partial('partials.pagination', $pagination) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
