<?php
$role = $user['role'] ?? '';
$isAdmin = ($role === 'admin' || !empty($isAdminMode));
$basePath = $basePath ?? ($isAdmin ? '/admin/keuangan/berkas' : '/bencab/berkas');
$backupZipUrl = '/admin/keuangan/berkas/backup';
$backPath = match ($role) {
    'admin' => '/admin/dashboard',
    'ketcab' => '/ketcab/dashboard',
    'bencab' => '/ruang-kerja',
    'sekcab' => '/ruang-kerja',
    default => '/ruang-kerja',
};
$backLabel = match ($role) {
    'admin' => 'Dashboard Admin',
    'ketcab' => 'Dashboard Ketcab',
    'bencab' => 'Dashboard Bencab',
    'sekcab' => 'Dashboard Sekcab',
    default => 'Ruang Kerja',
};
$modeLabel = match ($role) {
    'admin' => 'Pantauan Admin (Read-Only)',
    'ketcab' => 'Pantauan Ketua Cabang',
    'bencab' => 'Kelola Bendahara Cabang',
    'sekcab' => 'Pantauan Sekretaris Cabang',
    default => 'Pengurus Cabang',
};
?>

<div class="ws-page-wrap file-directory-page">
    <!-- 1. Executive Hero Banner Direktori Berkas Keuangan Bencab / Admin -->
    <div class="exec-role-banner banner-bencab">
        <div class="exec-banner-inner">
            <div class="exec-banner-content">
                <div class="exec-banner-eyebrow">
                    <i class="bi bi-wallet2"></i>
                    <span>Brankas Pembukuan &amp; Repositori Finansial Cabang</span>
                </div>
                <h1 class="exec-banner-title">Direktori Berkas Laporan Keuangan BPC</h1>
                <p class="exec-banner-desc">
                    Pusat arsip pembukuan digital, lembar kerja kas (.xlsx), kwitansi/LPJ PDF, serta sistem verifikasi integritas cadangan berkas keuangan BPC GMKI Cabang Padang.
                </p>
                <div class="exec-banner-pills">
                    <span class="exec-banner-pill">
                        <i class="bi bi-shield-fill-check" style="color: #34d399;"></i>
                        Proteksi Berkas: <code>Master + Duplikasi Cadangan</code>
                    </span>
                    <span class="exec-banner-pill">
                        <i class="bi bi-person-badge" style="color: #fde68a;"></i>
                        Mode Akses: <code><?= e($modeLabel) ?></code>
                    </span>
                    <span class="exec-banner-pill">
                        <i class="bi bi-file-earmark-spreadsheet-fill" style="color: #38bdf8;"></i>
                        Format: <code>Excel, PDF, Kwitansi Resmi</code>
                    </span>
                </div>
            </div>
            <div class="exec-banner-actions">
                <?php if ($isAdmin): ?>
                    <a href="<?= e($backupZipUrl) ?>" class="exec-btn exec-btn-gold" title="Unduh seluruh berkas keuangan dalam paket ZIP terenkripsi">
                        <i class="bi bi-file-earmark-zip-fill"></i>
                        <span>Backup Berkas Keuangan (ZIP)</span>
                    </a>
                    <a href="/admin/persuratan/berkas" class="exec-btn exec-btn-sky" title="Beralih ke Direktori Berkas Persuratan Sekcab">
                        <i class="bi bi-folder2-open"></i>
                        <span>Direktori Persuratan</span>
                    </a>
                    <a href="/admin/keamanan/backup" class="exec-btn exec-btn-glass" title="Buka Pusat Cadangan Data & Database">
                        <i class="bi bi-database-fill-gear"></i>
                        <span>Pusat Cadangan</span>
                    </a>
                    <a href="/bencab/laporan" class="exec-btn exec-btn-emerald" title="Buka Buku Rekapitulasi Kas Bencab">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Rekap Kas</span>
                    </a>
                <?php else: ?>
                    <a href="/sekcab/berkas" class="exec-btn exec-btn-sky" title="Beralih ke Direktori Berkas Persuratan Sekcab">
                        <i class="bi bi-folder2-open"></i>
                        <span>Direktori Persuratan</span>
                    </a>
                    <?php if ($role === 'bencab'): ?>
                        <a href="/bencab/laporan" class="exec-btn exec-btn-emerald">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span>Unggah Laporan Baru</span>
                        </a>
                    <?php endif; ?>
                    <a href="/bencab/laporan" class="exec-btn exec-btn-glass">
                        <i class="bi bi-journal-text"></i>
                        <span>Buku Kas</span>
                    </a>
                    <a href="/ruang-kerja/antrian" class="exec-btn exec-btn-glass">
                        <i class="bi bi-clock-history"></i>
                        <span>Antrean Ekspor CSV</span>
                    </a>
                    <a href="<?= e($backPath) ?>" class="exec-btn exec-btn-glass">
                        <i class="bi bi-arrow-left"></i>
                        <span><?= e($backLabel) ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 2. Notice Card Khusus Superadmin (Address Khusus & Akses Backup) -->
    <?php if ($isAdmin): ?>
        <div class="ws-card" style="margin-bottom: 1.5rem; border-left: 4px solid #f59e0b; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
            <div class="ws-card-body" style="padding: 1.15rem 1.4rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="max-width: 740px;">
                    <div style="font-weight: 800; color: #92400e; font-size: 0.95rem; display: flex; align-items: center; gap: 0.45rem;">
                        <i class="bi bi-shield-lock-fill" style="color: #d97706; font-size: 1.1rem;"></i>
                        <span>Address Khusus Superadmin &amp; Manajemen Cadangan Finansial</span>
                    </div>
                    <p style="margin: 0.25rem 0 0; font-size: 0.825rem; color: #78350f; line-height: 1.5;">
                        Halaman ini adalah portal terisolasi Superadmin (<code><?= e($basePath) ?></code>). Berkas operasional kas dikelola oleh <strong>Bendahara Cabang (Bencab)</strong>. Anda dapat memantau pergerakan berkas, mengunduh arsip secara mandiri, atau membuat paket arsip lengkap (ZIP) secara instan untuk kebutuhan audit BPK/MPPC maupun proteksi bencana data.
                    </p>
                </div>
                <div class="flex items-center gap-2" style="flex-wrap: wrap;">
                    <a href="<?= e($backupZipUrl) ?>" class="btn btn-warning btn-sm font-bold flex items-center gap-1" style="background: #f59e0b; border-color: #d97706; color: #fff; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3); text-decoration: none;">
                        <i class="bi bi-download"></i>
                        <span>Unduh Paket ZIP Keuangan</span>
                    </a>
                    <a href="/admin/keamanan/backup" class="btn btn-outline btn-sm font-bold flex items-center gap-1" style="background: #fff; text-decoration: none;">
                        <i class="bi bi-database-check"></i>
                        <span>Pusat Cadangan Sistem</span>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- 3. Ringkasan Metrik Keuangan (4 KPI Cards) -->
    <div class="ws-kpi-grid">
        <div class="ws-kpi-card accent-emerald">
            <div>
                <div class="ws-kpi-top">
                    <div>
                        <div class="ws-kpi-label">Total Berkas Keuangan</div>
                        <span class="badge badge-success" style="font-size: 0.66rem; margin-top: 0.25rem;">Repositori</span>
                    </div>
                    <div class="ws-kpi-icon" style="background: #d1fae5; color: #059669;">
                        <i class="bi bi-wallet-fill"></i>
                    </div>
                </div>
                <div class="ws-kpi-val" style="color: #059669;"><?= number_format((int)($stats['total'] ?? 0)) ?></div>
            </div>
            <div class="ws-kpi-sub">
                <span>Berkas laporan terverifikasi</span>
                <i class="bi bi-check2-circle" style="color: #059669;"></i>
            </div>
        </div>

        <div class="ws-kpi-card accent-blue">
            <div>
                <div class="ws-kpi-top">
                    <div>
                        <div class="ws-kpi-label">Lembar Kerja Excel</div>
                        <span class="badge badge-primary" style="font-size: 0.66rem; margin-top: 0.25rem;">Spreadsheet</span>
                    </div>
                    <div class="ws-kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                    </div>
                </div>
                <div class="ws-kpi-val" style="color: #0284c7;"><?= number_format((int)($stats['excel'] ?? 0)) ?></div>
            </div>
            <div class="ws-kpi-sub">
                <span>Format .xlsx / .xls</span>
                <i class="bi bi-file-earmark-spreadsheet" style="color: #0284c7;"></i>
            </div>
        </div>

        <div class="ws-kpi-card accent-rose">
            <div>
                <div class="ws-kpi-top">
                    <div>
                        <div class="ws-kpi-label">Dokumen Bukti / LPJ PDF</div>
                        <span class="badge badge-danger" style="font-size: 0.66rem; margin-top: 0.25rem;">Resmi</span>
                    </div>
                    <div class="ws-kpi-icon" style="background: #ffe4e6; color: #e11d48;">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                </div>
                <div class="ws-kpi-val" style="color: #e11d48;"><?= number_format((int)($stats['pdf'] ?? 0)) ?></div>
            </div>
            <div class="ws-kpi-sub">
                <span>Kwitansi, nota &amp; laporan resmi</span>
                <i class="bi bi-file-earmark-text" style="color: #e11d48;"></i>
            </div>
        </div>

        <div class="ws-kpi-card accent-purple">
            <div>
                <div class="ws-kpi-top">
                    <div>
                        <div class="ws-kpi-label">Kapasitas Brankas Kas</div>
                        <span class="badge badge-neutral" style="font-size: 0.66rem; margin-top: 0.25rem;">Storage</span>
                    </div>
                    <div class="ws-kpi-icon" style="background: #f3e8ff; color: #9333ea;">
                        <i class="bi bi-hdd-network-fill"></i>
                    </div>
                </div>
                <div class="ws-kpi-val" style="color: #9333ea; font-size: 1.55rem;"><?= e($stats['storage_used'] ?? '0 B') ?></div>
            </div>
            <div class="ws-kpi-sub">
                <span><?= number_format((int)($stats['duplicated'] ?? 0)) ?> berkas cadangan aman</span>
                <i class="bi bi-shield-check" style="color: #9333ea;"></i>
            </div>
        </div>
    </div>

    <!-- 4. Search Engine & Filter Console Khusus Keuangan -->
    <div class="ws-card search-console-card" style="margin-bottom: 1.5rem;">
        <div class="ws-card-body">
            <form method="get" action="<?= e($basePath) ?>" id="fileSearchForm" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="ws-search-grid ws-finance-search-grid">
                    <!-- Search Input Box -->
                    <div class="ws-search-main-col">
                        <label for="searchQuery" style="display: block; font-size: 0.78rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.03em;">
                            Cari Berkas Laporan Keuangan
                        </label>
                        <div class="ws-search-wrap" style="position: relative; display: flex; align-items: center;">
                            <i class="bi bi-search" style="position: absolute; left: 14px; color: #94a3b8; pointer-events: none;"></i>
                            <input 
                                type="text" 
                                id="searchQuery" 
                                name="q" 
                                value="<?= e($query ?? '') ?>" 
                                class="ws-search-input form-control" 
                                placeholder="Cari judul kas, uraian transaksi, periode, atau nama berkas..." 
                                style="width: 100%; height: 42px; padding-left: 2.5rem; padding-right: 2.2rem; border-radius: 12px; font-size: 0.875rem;"
                                autocomplete="off"
                            >
                            <?php if (!empty($query)): ?>
                                <a href="<?= e($basePath) ?>" title="Hapus pencarian" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; text-decoration: none; font-weight: bold;">✕</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Filter Periode Laporan -->
                    <div>
                        <label for="filterPeriod" style="display: block; font-size: 0.78rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.03em;">
                            Periode Laporan
                        </label>
                        <select id="filterPeriod" name="period" class="ws-filter-select form-control" style="width: 100%; height: 42px; border-radius: 12px; font-size: 0.85rem;">
                            <option value="">Semua Periode</option>
                            <?php foreach ($periods as $p): ?>
                                <option value="<?= e($p) ?>" <?= ($period ?? '') === $p ? 'selected' : '' ?>>
                                    <?= e($p) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Format Berkas -->
                    <div>
                        <label for="filterExt" style="display: block; font-size: 0.78rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.03em;">
                            Format Berkas
                        </label>
                        <select id="filterExt" name="ext" class="ws-filter-select form-control" style="width: 100%; height: 42px; border-radius: 12px; font-size: 0.85rem;">
                            <option value="all" <?= ($extension ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Format</option>
                            <option value="excel" <?= ($extension ?? '') === 'excel' ? 'selected' : '' ?>>Excel (.xlsx, .xls)</option>
                            <option value="pdf" <?= ($extension ?? '') === 'pdf' ? 'selected' : '' ?>>PDF (.pdf)</option>
                            <option value="word" <?= ($extension ?? '') === 'word' ? 'selected' : '' ?>>Word (.docx, .doc)</option>
                            <option value="images" <?= ($extension ?? '') === 'images' ? 'selected' : '' ?>>Gambar Bukti (.jpg, .png)</option>
                        </select>
                    </div>

                    <!-- Filter Urutan -->
                    <div>
                        <label for="filterSort" style="display: block; font-size: 0.78rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.03em;">
                            Urutan
                        </label>
                        <select id="filterSort" name="sort" class="ws-filter-select form-control" style="width: 100%; height: 42px; border-radius: 12px; font-size: 0.85rem;">
                            <option value="latest" <?= ($sort ?? 'latest') === 'latest' ? 'selected' : '' ?>>Terbaru Diunggah</option>
                            <option value="oldest" <?= ($sort ?? '') === 'oldest' ? 'selected' : '' ?>>Terlama Diunggah</option>
                            <option value="name_asc" <?= ($sort ?? '') === 'name_asc' ? 'selected' : '' ?>>Nama Berkas (A-Z)</option>
                            <option value="name_desc" <?= ($sort ?? '') === 'name_desc' ? 'selected' : '' ?>>Nama Berkas (Z-A)</option>
                            <option value="period_desc" <?= ($sort ?? '') === 'period_desc' ? 'selected' : '' ?>>Periode Terbaru</option>
                            <option value="period_asc" <?= ($sort ?? '') === 'period_asc' ? 'selected' : '' ?>>Periode Terlama</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div style="display: flex; align-items: flex-end; gap: 0.5rem; margin-top: auto;">
                        <button type="submit" class="btn btn-primary flex items-center justify-center gap-1" style="height: 42px; flex: 1; border-radius: 12px; font-weight: 700;">
                            <i class="bi bi-search"></i>
                            <span>Cari</span>
                        </button>
                        <a href="<?= e($basePath) ?>" class="btn btn-outline flex items-center justify-center" title="Reset Pencarian" style="height: 42px; width: 42px; padding: 0; border-radius: 12px;">
                            <i class="bi bi-arrow-clockwise" style="font-size: 1.1rem;"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Filter Pills -->
                <div class="flex items-center gap-2" style="flex-wrap: wrap; padding-top: 0.65rem; border-top: 1px dashed #e2e8f0; margin-top: 0.25rem;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Format Cepat:</span>
                    <button type="button" class="quick-pill <?= ($extension ?? 'all') === 'all' ? 'active' : '' ?>" onclick="location.href='<?= e($basePath) ?>'">Semua</button>
                    <button type="button" class="quick-pill <?= ($extension ?? '') === 'excel' ? 'active' : '' ?>" style="<?= ($extension ?? '') === 'excel' ? 'background:#0284c7;color:#fff;border-color:#0284c7;' : '' ?>" onclick="location.href='<?= e($basePath) ?>?ext=excel'"><i class="bi bi-file-earmark-spreadsheet-fill"></i> Excel (.xlsx)</button>
                    <button type="button" class="quick-pill <?= ($extension ?? '') === 'pdf' ? 'active' : '' ?>" style="<?= ($extension ?? '') === 'pdf' ? 'background:#e11d48;color:#fff;border-color:#e11d48;' : '' ?>" onclick="location.href='<?= e($basePath) ?>?ext=pdf'"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</button>
                    <button type="button" class="quick-pill <?= ($extension ?? '') === 'word' ? 'active' : '' ?>" style="<?= ($extension ?? '') === 'word' ? 'background:#4338ca;color:#fff;border-color:#4338ca;' : '' ?>" onclick="location.href='<?= e($basePath) ?>?ext=word'"><i class="bi bi-file-earmark-word-fill"></i> Word</button>
                    <button type="button" class="quick-pill <?= ($extension ?? '') === 'images' ? 'active' : '' ?>" style="<?= ($extension ?? '') === 'images' ? 'background:#d97706;color:#fff;border-color:#d97706;' : '' ?>" onclick="location.href='<?= e($basePath) ?>?ext=images'"><i class="bi bi-image-fill"></i> Gambar</button>
                    <span class="live-counter text-muted" style="margin-left: auto; font-size: 0.78rem; font-weight: 600;">
                        Menampilkan <strong style="color: #042825;"><?= count($documents) ?></strong> berkas laporan keuangan terdaftar
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. Tabel Daftar Berkas Laporan Keuangan Eksekutif -->
    <div class="ws-card">
        <div class="ws-card-header">
            <div class="ws-card-header-left">
                <div class="ws-card-icon" style="background: #d1fae5; color: #059669;">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div>
                    <h2 class="ws-card-title">Daftar Berkas Pembukuan &amp; Laporan Pertanggungjawaban Keuangan</h2>
                    <p class="ws-card-subtitle">Dokumen fisik pembukuan Bencab dengan verifikasi redundansi Master dan Salinan Cadangan (SHA-256)</p>
                </div>
            </div>
            <div>
                <span class="badge badge-success" style="font-size: 0.74rem; font-weight: 700; padding: 0.35rem 0.7rem;">
                    <i class="bi bi-shield-fill-check"></i> Terverifikasi Keuangan
                </span>
            </div>
        </div>

        <div class="ws-table-wrap">
            <table class="ws-table" id="financeFilesTable">
                <thead>
                    <tr>
                        <th style="width: 48px; text-align: center;">No</th>
                        <th style="min-width: 250px;">Berkas Digital</th>
                        <th style="min-width: 230px;">Judul Laporan &amp; Uraian Kas</th>
                        <th style="min-width: 140px; text-align: center;">Periode</th>
                        <th style="min-width: 100px; text-align: center;">Ukuran</th>
                        <th style="min-width: 160px;">Pengunggah</th>
                        <th style="min-width: 130px; text-align: center;">Waktu Unggah</th>
                        <th style="width: 130px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($documents)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3.5rem 1.5rem;">
                                <div style="width: 56px; height: 56px; border-radius: 16px; background: #ecfdf5; color: #059669; display: inline-flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 0.75rem;">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <div style="font-weight: 800; font-size: 1rem; color: #0f172a; margin-bottom: 0.25rem;">Belum ada berkas laporan keuangan yang cocok</div>
                                <p class="text-muted" style="margin: 0 auto 0.9rem; max-width: 420px; font-size: 0.82rem;">
                                    Belum ada dokumen yang sesuai dengan kriteria pencarian atau filter yang Anda pilih.
                                </p>
                                <div style="display: inline-flex; gap: 0.5rem;">
                                    <a href="<?= e($basePath) ?>" class="btn btn-outline btn-sm" style="text-decoration: none;">Reset Filter</a>
                                    <?php if (!$isAdmin): ?>
                                        <a href="/bencab/laporan" class="btn btn-primary btn-sm" style="text-decoration: none;">+ Unggah Laporan Baru</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $no = 1 + ($pagination['offset'] ?? 0);
                        foreach ($documents as $doc): 
                            $ext = $doc['extension'];
                            $fileIconClass = match ($ext) {
                                'pdf' => 'bi-file-earmark-pdf-fill',
                                'xls', 'xlsx' => 'bi-file-earmark-spreadsheet-fill',
                                'doc', 'docx', 'odt' => 'bi-file-earmark-word-fill',
                                'jpg', 'jpeg', 'png', 'webp' => 'bi-file-earmark-image-fill',
                                default => 'bi-file-earmark-fill',
                            };
                            $iconBg = match ($ext) {
                                'xlsx', 'xls' => 'background:#ecfdf5;color:#059669;',
                                'pdf' => 'background:#fff1f2;color:#e11d48;',
                                'docx', 'doc' => 'background:#eef2ff;color:#4f46e5;',
                                default => 'background:#f8fafc;color:#64748b;',
                            };
                            $badgeColor = match ($ext) {
                                'xlsx', 'xls' => 'background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;',
                                'pdf' => 'background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;',
                                'docx', 'doc' => 'background:#e0e7ff;color:#4338ca;border:1px solid #c7d2fe;',
                                default => 'background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;',
                            };
                            $isDuplicated = !empty($doc['backup_stored_name']);
                        ?>
                            <tr>
                                <td style="text-align: center; color: #94a3b8; font-weight: 700; font-size: 0.8rem;">
                                    <?= $no++ ?>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; <?= $iconBg ?>">
                                            <i class="bi <?= $fileIconClass ?>"></i>
                                        </div>
                                        <div style="min-width: 0;">
                                            <a href="<?= e($doc['download_url']) ?>" 
                                               style="font-weight: 700; color: #0f3d64; text-decoration: none; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px; font-size: 0.85rem;"
                                               title="Unduh <?= e($doc['original_name'] ?: 'Dokumen') ?>">
                                                <?= e($doc['original_name'] ?: 'dokumen-keuangan.' . $ext) ?>
                                            </a>
                                            <div style="display: flex; align-items: center; gap: 0.35rem; margin-top: 0.2rem; flex-wrap: wrap;">
                                                <span style="display: inline-block; padding: 0.1rem 0.4rem; border-radius: 4px; font-size: 0.675rem; font-weight: 800; text-transform: uppercase; <?= $badgeColor ?>">
                                                    .<?= e($ext ?: 'file') ?>
                                                </span>
                                                <?php if ($isDuplicated): ?>
                                                    <span style="display: inline-flex; align-items: center; gap: 2px; padding: 0.1rem 0.4rem; border-radius: 4px; font-size: 0.65rem; font-weight: 700; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;" title="Salinan cadangan otomatis tersedia">
                                                        <i class="bi bi-shield-check"></i> Duplikasi OK
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 800; color: #1e293b; font-size: 0.875rem; margin-bottom: 0.2rem; line-height: 1.3;">
                                        <?= e($doc['title']) ?>
                                    </div>
                                    <?php if (!empty($doc['description'])): ?>
                                        <div style="font-size: 0.775rem; color: #64748b; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;" title="<?= e($doc['description']) ?>">
                                            <?= e($doc['description']) ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="font-size: 0.75rem; color: #94a3b8; font-style: italic;">Tanpa catatan tambahan</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if (!empty($doc['period'])): ?>
                                        <span class="badge badge-info" style="font-weight: 700; font-size: 0.75rem; padding: 0.25rem 0.6rem; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="bi bi-calendar3"></i>
                                            <?= e($doc['period']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.8rem;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-size: 0.8rem; font-weight: 700; color: #334155;">
                                        <?= e($doc['file_size_formatted']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.825rem; font-weight: 700; color: #1e293b;">
                                        <?= e($doc['nama_lengkap'] ?? 'Bendahara Cabang') ?>
                                    </div>
                                    <span style="display: inline-block; font-size: 0.675rem; font-weight: 700; color: #0d9488; text-transform: uppercase; margin-top: 2px;">
                                        <i class="bi bi-person-check-fill"></i> <?= e($doc['uploader_role'] ?? 'bencab') ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="font-size: 0.8rem; font-weight: 600; color: #334155;">
                                        <?= date('d M Y', strtotime($doc['created_at'])) ?>
                                    </div>
                                    <span style="font-size: 0.7rem; color: #94a3b8;">
                                        <?= date('H:i', strtotime($doc['created_at'])) ?> WIB
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; align-items: center; gap: 0.35rem; justify-content: flex-end;">
                                        <a href="<?= e($doc['download_url']) ?>" 
                                           class="btn btn-outline btn-sm" 
                                           style="padding: 0.3rem 0.6rem; font-size: 0.75rem; font-weight: 700; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 3px;" 
                                           title="Unduh Berkas Laporan">
                                            <i class="bi bi-cloud-arrow-down-fill" style="font-size: 0.9rem;"></i>
                                            <span>Unduh</span>
                                        </a>
                                        <?php if (!$isAdmin): ?>
                                            <a href="/bencab/laporan" 
                                               class="btn btn-secondary btn-sm" 
                                               style="padding: 0.3rem 0.5rem; font-size: 0.75rem; text-decoration: none; border-radius: 8px;" 
                                               title="Buka Halaman Rekap Laporan">
                                                <i class="bi bi-journal-check"></i>
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
            <div style="padding: 0.85rem 1.4rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <?= partial('partials.pagination', $pagination) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
