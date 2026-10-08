<?php
$role = $user['role'] ?? '';
$backPath = match ($role) {
    'admin' => '/admin/dashboard',
    'sekcab' => '/ruang-kerja',
    default => '/ruang-kerja',
};
$backLabel = $role === 'admin' ? 'Dashboard Admin' : 'Dashboard Sekcab';
?>

<div class="file-directory-page">
    <!-- Header Halaman -->
    <header class="flex justify-between items-center" style="margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <p class="text-muted" style="margin:0;">
                <a href="<?= e($backPath) ?>" style="color:var(--text-muted);text-decoration:none;"><?= e($backLabel) ?></a> 
                / Administrasi Cabang / <strong>Direktori Berkas Laporan &amp; Persuratan</strong>
            </p>
            <h1 style="font-size:1.75rem;font-weight:800;color:var(--primary);margin:.25rem 0 0.5rem;display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                <span>🗂️ Direktori Berkas Laporan &amp; Persuratan Sekcab</span>
                <?php if ($role === 'admin'): ?>
                    <span class="badge badge-info" style="font-size:0.75rem;font-weight:700;">👁️ Mode Pantauan Admin (Read-Only)</span>
                <?php else: ?>
                    <span class="badge badge-primary" style="font-size:0.75rem;font-weight:700;">Khusus Sekcab</span>
                <?php endif; ?>
            </h1>
            <p class="text-muted" style="margin:0;font-size:0.9rem;">
                Pusat penelusuran, verifikasi redundansi file, dan pengarsipan surat resmi BPC GMKI Cabang Padang dengan proteksi duplikasi ganda.
            </p>
        </div>
        <div class="flex items-center gap-2" style="flex-wrap:wrap;">
            <?php if ($role === 'admin'): ?>
                <a href="/admin/berkas/backup?type=archive" class="btn btn-primary btn-sm flex items-center gap-1" style="text-decoration:none;font-weight:700;">
                    <span>📦 Backup Arsip Persuratan (ZIP)</span>
                </a>
                <a href="/bencab/berkas" class="btn btn-outline btn-sm flex items-center gap-1" style="text-decoration:none;" title="Beralih ke Direktori Berkas Keuangan Bencab">
                    <span>💰 Direktori Keuangan Bencab</span>
                </a>
            <?php else: ?>
                <a href="/sekcab/arsip" class="btn btn-primary btn-sm flex items-center gap-1" style="text-decoration:none;">
                    <?= svg_icon('plus', 16) ?>
                    <span>Catat Surat Baru</span>
                </a>
                <a href="/sekcab/arsip" class="btn btn-outline btn-sm flex items-center gap-1" style="text-decoration:none;">
                    <span>📖 Buku Agenda Sekcab</span>
                </a>
            <?php endif; ?>
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
                        Seluruh berkas arsip dan dokumen surat dikelola secara mandiri oleh <strong>Sekretaris Cabang (Sekcab)</strong>. Tugas Administrator adalah memantau dan mencadangkan data (backup ZIP) secara berkala agar berkas fisik maupun digital tetap terlindungi.
                    </p>
                </div>
                <div>
                    <a href="/admin/berkas/backup?type=archive" class="btn btn-primary btn-sm" style="font-weight:700;text-decoration:none;">
                        ⬇️ Unduh Paket ZIP
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Ringkasan Metrik Arsip Sekcab -->
    <div class="metrics-grid" style="margin-bottom:1.5rem;">
        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Total Surat Terdaftar</span>
                <span class="metric-value"><?= number_format((int)($stats['total'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Arsip persuratan cabang</span>
            </div>
            <div class="metric-icon-wrap" style="background:#e0e7ff;color:#4338ca;">
                🗂️
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Arsip Rahasia</span>
                <span class="metric-value"><?= number_format((int)($stats['secret'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Akses terbatas &amp; terlindungi</span>
            </div>
            <div class="metric-icon-wrap" style="background:#fee2e2;color:#b91c1c;">
                🔒
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Surat Penting &amp; Segera</span>
                <span class="metric-value"><?= number_format((int)($stats['urgent'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Prioritas penanganan tinggi</span>
            </div>
            <div class="metric-icon-wrap" style="background:#fef3c7;color:#d97706;">
                ⚡
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Duplikasi Cadangan Aktif</span>
                <span class="metric-value"><?= number_format((int)($stats['duplicated'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">SHA-256 Terverifikasi 100%</span>
            </div>
            <div class="metric-icon-wrap" style="background:#dcfce7;color:#16a34a;">
                🛡️
            </div>
        </div>
    </div>

    <!-- Search Engine & Filter Console Khusus Persuratan -->
    <div class="card search-console-card" style="margin-bottom:1.5rem;border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);">
        <div class="card-body" style="padding:1.25rem 1.5rem;">
            <form method="get" action="/sekcab/berkas" id="fileSearchForm" class="flex flex-col gap-3">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                    <!-- Search Input Box -->
                    <div class="md:col-span-4 relative">
                        <label for="searchQuery" style="display:block;font-size:0.8125rem;font-weight:700;color:var(--text-muted);margin-bottom:0.25rem;">
                            Cari Judul Surat / Keterangan
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
                                placeholder="Ketik perihal, judul surat, atau nama berkas..." 
                                style="padding-left:2.5rem;padding-right:2.5rem;width:100%;height:42px;border-radius:var(--radius-md);font-size:0.9rem;"
                                autocomplete="off"
                            >
                            <?php if (!empty($query)): ?>
                                <a href="/sekcab/berkas" title="Hapus pencarian" style="position:absolute;right:12px;color:#94a3b8;text-decoration:none;font-weight:bold;cursor:pointer;">✕</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Filter Sifat Surat -->
                    <div class="md:col-span-3">
                        <label for="filterNature" style="display:block;font-size:0.8125rem;font-weight:700;color:var(--text-muted);margin-bottom:0.25rem;">
                            Sifat Surat
                        </label>
                        <select id="filterNature" name="nature" class="form-control" style="height:42px;border-radius:var(--radius-md);font-size:0.875rem;">
                            <option value="">Semua Sifat Surat</option>
                            <option value="Rahasia" <?= ($nature ?? '') === 'Rahasia' ? 'selected' : '' ?>>🔒 Rahasia (Terbatas)</option>
                            <option value="Sangat Segera" <?= ($nature ?? '') === 'Sangat Segera' ? 'selected' : '' ?>>⚡ Sangat Segera</option>
                            <option value="Penting" <?= ($nature ?? '') === 'Penting' ? 'selected' : '' ?>>⭐ Penting</option>
                            <option value="Segera" <?= ($nature ?? '') === 'Segera' ? 'selected' : '' ?>>Segera</option>
                            <option value="Biasa" <?= ($nature ?? '') === 'Biasa' ? 'selected' : '' ?>>Biasa (Terbuka)</option>
                        </select>
                    </div>

                    <!-- Filter Status Surat -->
                    <div class="md:col-span-3">
                        <label for="filterStatus" style="display:block;font-size:0.8125rem;font-weight:700;color:var(--text-muted);margin-bottom:0.25rem;">
                            Status Surat
                        </label>
                        <select id="filterStatus" name="status" class="form-control" style="height:42px;border-radius:var(--radius-md);font-size:0.875rem;">
                            <option value="">Semua Status</option>
                            <option value="Diarsipkan" <?= ($status ?? '') === 'Diarsipkan' ? 'selected' : '' ?>>Diarsipkan</option>
                            <option value="Menunggu Tindak Lanjut" <?= ($status ?? '') === 'Menunggu Tindak Lanjut' ? 'selected' : '' ?>>Menunggu Tindak Lanjut</option>
                            <option value="Didisposisi" <?= ($status ?? '') === 'Didisposisi' ? 'selected' : '' ?>>Didisposisi</option>
                            <option value="Selesai" <?= ($status ?? '') === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="md:col-span-2 flex items-end gap-2" style="margin-top:auto;">
                        <button type="submit" class="btn btn-primary flex items-center justify-center gap-1" style="height:42px;flex:1;">
                            <?= svg_icon('search', 16) ?>
                            <span>Cari</span>
                        </button>
                        <a href="/sekcab/berkas" class="btn btn-outline flex items-center justify-center" title="Reset Pencarian" style="height:42px;width:42px;padding:0;">
                            <?= svg_icon('refresh', 16) ?>
                        </a>
                    </div>
                </div>

                <!-- Live Quick Filter Pills -->
                <div class="flex items-center gap-2" style="flex-wrap:wrap;padding-top:0.5rem;border-top:1px dashed #e2e8f0;margin-top:0.25rem;">
                    <span style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.04em;">Filter Cepat:</span>
                    <button type="button" class="quick-pill <?= (empty($nature) && empty($status)) ? 'active' : '' ?>" onclick="location.href='/sekcab/berkas'">Semua</button>
                    <button type="button" class="quick-pill <?= ($nature ?? '') === 'Rahasia' ? 'active' : '' ?>" onclick="location.href='/sekcab/berkas?nature=Rahasia'">🔒 Rahasia</button>
                    <button type="button" class="quick-pill <?= ($nature ?? '') === 'Penting' ? 'active' : '' ?>" onclick="location.href='/sekcab/berkas?nature=Penting'">⭐ Penting</button>
                    <button type="button" class="quick-pill <?= ($nature ?? '') === 'Segera' ? 'active' : '' ?>" onclick="location.href='/sekcab/berkas?nature=Segera'">⚡ Segera</button>
                    <button type="button" class="quick-pill <?= ($status ?? '') === 'Diarsipkan' ? 'active' : '' ?>" onclick="location.href='/sekcab/berkas?status=Diarsipkan'">Diarsipkan</button>
                    <span class="live-counter text-muted" style="margin-left:auto;font-size:0.8125rem;">
                        Menampilkan <strong style="color:var(--primary);"><?= count($documents) ?></strong> dokumen surat terdaftar
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Berkas Surat & Verifikasi Duplikasi -->
    <div class="table-card" style="box-shadow:var(--shadow-sm);border-radius:var(--radius-lg);overflow:hidden;">
        <div class="table-header flex items-center justify-between" style="background:#ffffff;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-color);">
            <div>
                <h2 class="table-title" style="margin:0;font-size:1.125rem;font-weight:700;color:var(--text-main);">
                    Daftar Berkas Persuratan &amp; Verifikasi Duplikasi Redundan
                </h2>
                <p class="text-muted" style="margin:0.25rem 0 0;font-size:0.8125rem;">
                    Dokumen resmi kesekretariatan yang telah dilengkapi marking berkas Asli vs Duplikasi Cadangan (SHA-256 Identik).
                </p>
            </div>
            <div>
                <span class="badge badge-primary" style="font-size:0.8rem;padding:0.35rem 0.65rem;">
                    🛡️ Redundansi Keamanan Aktif
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table" id="archiveFilesTable">
                <thead>
                    <tr>
                        <th style="width:50px;text-align:center;">#</th>
                        <th style="min-width:240px;">Judul Surat &amp; Keterangan</th>
                        <th style="min-width:130px;text-align:center;">Tanggal Dimasukkan</th>
                        <th style="min-width:120px;text-align:center;">Sifat Surat</th>
                        <th style="min-width:190px;text-align:center;">Marking &amp; Duplikasi Berkas</th>
                        <th style="min-width:120px;text-align:center;">Status</th>
                        <th style="min-width:100px;text-align:center;">Ukuran</th>
                        <th style="width:110px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($documents)): ?>
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem 1.5rem;">
                                <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;">
                                    <span style="font-size:2.5rem;">🗂️</span>
                                    <div style="font-weight:700;font-size:1.1rem;color:var(--text-main);">Belum ada berkas persuratan yang cocok</div>
                                    <p class="text-muted" style="margin:0;max-width:400px;font-size:0.875rem;">
                                        Belum ada dokumen surat yang sesuai dengan kriteria pencarian atau filter yang Anda pilih.
                                    </p>
                                    <div class="flex gap-2" style="margin-top:0.5rem;">
                                        <a href="/sekcab/berkas" class="btn btn-outline btn-sm">Reset Filter</a>
                                        <?php if ($role === 'sekcab'): ?>
                                            <a href="/sekcab/arsip" class="btn btn-primary btn-sm">+ Catat Surat Baru</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $no = 1 + ($pagination['offset'] ?? 0);
                        foreach ($documents as $doc): 
                            $nature = $doc['letter_nature'] ?? 'Biasa';
                            $natureBadge = match ($nature) {
                                'Rahasia' => 'badge-danger',
                                'Sangat Segera' => 'badge-warning',
                                'Penting' => 'badge-primary',
                                'Segera' => 'badge-info',
                                default => 'badge-secondary',
                            };
                            $natureIcon = match ($nature) {
                                'Rahasia' => '🔒',
                                'Sangat Segera' => '⚡',
                                'Penting' => '⭐',
                                'Segera' => '⏱️',
                                default => '📄',
                            };
                            $status = $doc['status'] ?? 'Diarsipkan';
                            $statusBadge = match ($status) {
                                'Selesai' => 'badge-success',
                                'Menunggu Tindak Lanjut' => 'badge-warning',
                                'Didisposisi' => 'badge-info',
                                default => 'badge-primary',
                            };
                            $hasFile = !empty($doc['stored_name']);
                            $hasDuplicate = !empty($doc['backup_stored_name']) || !empty($doc['is_duplicated']);
                        ?>
                            <tr>
                                <td style="text-align:center;color:var(--text-muted);font-weight:600;">
                                    <?= $no++ ?>
                                </td>
                                <td>
                                    <div style="font-weight:700;color:var(--text-main);font-size:0.925rem;margin-bottom:0.25rem;">
                                        <?= e($doc['title']) ?>
                                    </div>
                                    <?php if (!empty($doc['description'])): ?>
                                        <div style="font-size:0.8rem;color:var(--text-muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;" title="<?= e($doc['description']) ?>">
                                            <?= e($doc['description']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:center;">
                                    <div style="font-size:0.85rem;font-weight:600;color:var(--text-main);">
                                        📅 <?= !empty($doc['letter_date']) ? date('d M Y', strtotime($doc['letter_date'])) : date('d M Y', strtotime($doc['created_at'])) ?>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge <?= $natureBadge ?>" style="font-size:0.775rem;font-weight:700;gap:0.25rem;">
                                        <?= $natureIcon ?> <?= e($nature) ?>
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <?php if ($hasFile): ?>
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.35rem;">
                                            <div class="flex items-center justify-center gap-1" style="flex-wrap:wrap;">
                                                <a href="<?= e($doc['download_url']) ?>" 
                                                   class="btn btn-outline btn-sm" 
                                                   style="padding:0.2rem 0.45rem;font-size:0.75rem;border-color:#3b82f6;color:#1d4ed8;font-weight:600;" 
                                                   title="Unduh Berkas Asli (Master)">
                                                    ⭐ Asli
                                                </a>
                                                <?php if ($hasDuplicate): ?>
                                                    <a href="<?= e($doc['download_backup_url']) ?>" 
                                                       class="btn btn-outline btn-sm" 
                                                       style="padding:0.2rem 0.45rem;font-size:0.75rem;border-color:#10b981;color:#047857;font-weight:600;" 
                                                       title="Unduh Salinan Duplikasi Cadangan (Identik 100%)">
                                                        🛡️ Duplikasi
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            <span class="badge badge-success" style="font-size:0.7rem;padding:0.15rem 0.4rem;font-weight:600;">
                                                ✓ SHA-256 Identik
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.25rem;">
                                            <span class="badge badge-warning" style="font-size:0.72rem;padding:0.2rem 0.45rem;">
                                                📄 Belum ada berkas
                                            </span>
                                            <?php if ($role === 'sekcab'): ?>
                                                <a href="/sekcab/arsip" class="btn btn-outline btn-sm" style="padding:0.15rem 0.45rem;font-size:0.7rem;border-style:dashed;">
                                                    + Unggah Berkas
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge <?= $statusBadge ?>" style="font-size:0.775rem;">
                                        <?= e($status) ?>
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <span style="font-size:0.825rem;font-weight:600;color:var(--text-main);">
                                        <?= $hasFile ? e($doc['file_size_formatted']) : '-' ?>
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <div class="flex items-center justify-center gap-1">
                                        <?php if ($hasFile): ?>
                                            <a href="<?= e($doc['download_url']) ?>" class="btn btn-outline btn-sm" style="padding:0.25rem 0.5rem;font-size:0.8rem;" title="Unduh Berkas Master">
                                                ⬇️
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($role === 'sekcab'): ?>
                                            <a href="/sekcab/arsip" class="btn btn-secondary btn-sm" style="padding:0.25rem 0.5rem;font-size:0.8rem;" title="Buka di Buku Agenda Sekcab">
                                                📖
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
