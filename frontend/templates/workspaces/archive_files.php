<?php
$role = $user['role'] ?? '';
$isSekcab = ($role === 'sekcab');
$backPath = match ($role) {
    'admin' => '/admin/dashboard',
    'sekcab' => '/ruang-kerja',
    default => '/ruang-kerja',
};
$backLabel = $role === 'admin' ? 'Dashboard Admin' : 'Dashboard Sekcab';
?>

<div class="ws-page-wrap file-directory-page">
    <!-- 1. Executive Hero Banner Direktori Berkas Sekcab -->
    <div class="exec-role-banner banner-sekcab">
        <div class="exec-banner-inner">
            <div class="exec-banner-content">
                <div class="exec-banner-eyebrow">
                    <i class="bi bi-folder2-open"></i>
                    <span>Brankas Dokumen &amp; Repositori Persuratan Cabang</span>
                </div>
                <h1 class="exec-banner-title">Direktori Berkas Laporan &amp; Persuratan Sekcab</h1>
                <p class="exec-banner-desc">
                    Pusat penelusuran dokumen resmi organisasi, audit integritas berkas digital, serta verifikasi salinan duplikasi cadangan otomatis berstandar <strong>SHA-256</strong>.
                </p>
                <div class="exec-banner-pills">
                    <span class="exec-banner-pill">
                        <i class="bi bi-shield-fill-check" style="color: #34d399;"></i>
                        Proteksi Berkas: <code>Master + Duplikasi</code>
                    </span>
                    <span class="exec-banner-pill">
                        <i class="bi bi-person-badge" style="color: #fde68a;"></i>
                        Mode Akses: <code><?= $role === 'admin' ? 'Pantauan Admin (Read-Only)' : 'Kelola Sekcab' ?></code>
                    </span>
                </div>
            </div>
            <div class="exec-banner-actions">
                <?php if ($role === 'admin'): ?>
                    <a href="/admin/berkas/backup?type=archive" class="exec-btn exec-btn-gold">
                        <i class="bi bi-file-earmark-zip-fill"></i>
                        <span>Backup Arsip (ZIP)</span>
                    </a>
                    <a href="/admin/keuangan/berkas" class="exec-btn exec-btn-emerald">
                        <i class="bi bi-wallet2"></i>
                        <span>Direktori Keuangan</span>
                    </a>
                    <a href="/sekcab/arsip" class="exec-btn exec-btn-glass">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>Buku Agenda</span>
                    </a>
                <?php else: ?>
                    <a href="/sekcab/arsip" class="exec-btn exec-btn-sky">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Catat &amp; Unggah Surat</span>
                    </a>
                    <a href="<?= e($backPath) ?>" class="exec-btn exec-btn-glass">
                        <i class="bi bi-arrow-left"></i>
                        <span><?= e($backLabel) ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 2. Ringkasan Metrik Arsip Sekcab (4 KPI Cards) -->
    <div class="ws-kpi-grid">
        <div class="ws-kpi-card accent-blue">
            <div>
                <div class="ws-kpi-top">
                    <div>
                        <div class="ws-kpi-label">Total Surat Terdaftar</div>
                        <span class="badge badge-primary" style="font-size: 0.66rem; margin-top: 0.25rem;">Repositori</span>
                    </div>
                    <div class="ws-kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-folder-check"></i>
                    </div>
                </div>
                <div class="ws-kpi-val"><?= number_format((int)($stats['total'] ?? 0)) ?></div>
            </div>
            <div class="ws-kpi-sub">
                <span>Arsip persuratan cabang terdaftar</span>
                <i class="bi bi-check2-circle" style="color: #0284c7;"></i>
            </div>
        </div>

        <div class="ws-kpi-card accent-rose">
            <div>
                <div class="ws-kpi-top">
                    <div>
                        <div class="ws-kpi-label">Arsip Rahasia</div>
                        <span class="badge badge-danger" style="font-size: 0.66rem; margin-top: 0.25rem;">Terbatas</span>
                    </div>
                    <div class="ws-kpi-icon" style="background: #ffe4e6; color: #e11d48;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
                <div class="ws-kpi-val" style="color: #e11d48;"><?= number_format((int)($stats['secret'] ?? 0)) ?></div>
            </div>
            <div class="ws-kpi-sub">
                <span>Akses terbatas &amp; terlindungi</span>
                <i class="bi bi-lock-fill" style="color: #e11d48;"></i>
            </div>
        </div>

        <div class="ws-kpi-card accent-amber">
            <div>
                <div class="ws-kpi-top">
                    <div>
                        <div class="ws-kpi-label">Penting &amp; Segera</div>
                        <span class="badge badge-warning" style="font-size: 0.66rem; margin-top: 0.25rem;">Prioritas</span>
                    </div>
                    <div class="ws-kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                </div>
                <div class="ws-kpi-val" style="color: #d97706;"><?= number_format((int)($stats['urgent'] ?? 0)) ?></div>
            </div>
            <div class="ws-kpi-sub">
                <span>Prioritas penanganan tinggi</span>
                <i class="bi bi-clock-history" style="color: #d97706;"></i>
            </div>
        </div>

        <div class="ws-kpi-card accent-emerald">
            <div>
                <div class="ws-kpi-top">
                    <div>
                        <div class="ws-kpi-label">Duplikasi Cadangan</div>
                        <span class="badge badge-success" style="font-size: 0.66rem; margin-top: 0.25rem;">SHA-256 OK</span>
                    </div>
                    <div class="ws-kpi-icon" style="background: #d1fae5; color: #059669;">
                        <i class="bi bi-hdd-network-fill"></i>
                    </div>
                </div>
                <div class="ws-kpi-val" style="color: #059669;"><?= number_format((int)($stats['duplicated'] ?? 0)) ?></div>
            </div>
            <div class="ws-kpi-sub">
                <span>SHA-256 Terverifikasi 100%</span>
                <i class="bi bi-shield-check" style="color: #059669;"></i>
            </div>
        </div>
    </div>

    <!-- 3. Search Engine & Filter Console Khusus Persuratan -->
    <div class="ws-card search-console-card">
        <div class="ws-card-body">
            <form method="get" action="/sekcab/berkas" id="fileSearchForm" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="ws-search-grid">
                    <!-- Search Input Box -->
                    <div>
                        <label for="searchQuery" style="display: block; font-size: 0.78rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.03em;">
                            Cari Judul Surat / Keterangan
                        </label>
                        <div class="ws-search-wrap" style="min-width: 0;">
                            <i class="bi bi-search"></i>
                            <input 
                                type="text" 
                                id="searchQuery" 
                                name="q" 
                                value="<?= e($query ?? '') ?>" 
                                class="ws-search-input" 
                                placeholder="Ketik perihal, judul surat, atau nama berkas..." 
                                style="height: 42px; padding-right: 2.2rem !important;"
                                autocomplete="off"
                            >
                            <?php if (!empty($query)): ?>
                                <a href="/sekcab/berkas" title="Hapus pencarian" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; text-decoration: none; font-weight: bold;">✕</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Filter Sifat Surat -->
                    <div>
                        <label for="filterNature" style="display: block; font-size: 0.78rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.03em;">
                            Sifat Surat
                        </label>
                        <select id="filterNature" name="nature" class="ws-filter-select" style="width: 100%; height: 42px;">
                            <option value="">Semua Sifat Surat</option>
                            <option value="Rahasia" <?= ($nature ?? '') === 'Rahasia' ? 'selected' : '' ?>>Rahasia (Terbatas)</option>
                            <option value="Sangat Segera" <?= ($nature ?? '') === 'Sangat Segera' ? 'selected' : '' ?>>Sangat Segera</option>
                            <option value="Penting" <?= ($nature ?? '') === 'Penting' ? 'selected' : '' ?>>Penting</option>
                            <option value="Segera" <?= ($nature ?? '') === 'Segera' ? 'selected' : '' ?>>Segera</option>
                            <option value="Biasa" <?= ($nature ?? '') === 'Biasa' ? 'selected' : '' ?>>Biasa (Terbuka)</option>
                        </select>
                    </div>

                    <!-- Filter Status Surat -->
                    <div>
                        <label for="filterStatus" style="display: block; font-size: 0.78rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.03em;">
                            Status Surat
                        </label>
                        <select id="filterStatus" name="status" class="ws-filter-select" style="width: 100%; height: 42px;">
                            <option value="">Semua Status</option>
                            <option value="Diarsipkan" <?= ($status ?? '') === 'Diarsipkan' ? 'selected' : '' ?>>Diarsipkan</option>
                            <option value="Menunggu Tindak Lanjut" <?= ($status ?? '') === 'Menunggu Tindak Lanjut' ? 'selected' : '' ?>>Menunggu Tindak Lanjut</option>
                            <option value="Didisposisi" <?= ($status ?? '') === 'Didisposisi' ? 'selected' : '' ?>>Didisposisi</option>
                            <option value="Selesai" <?= ($status ?? '') === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div style="display: flex; align-items: flex-end; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary" style="height: 42px; flex: 1; justify-content: center; border-radius: 10px; font-weight: 700;">
                            <i class="bi bi-funnel-fill"></i>
                            <span>Terapkan</span>
                        </button>
                        <a href="/sekcab/berkas" class="btn btn-outline" title="Reset Pencarian" style="height: 42px; width: 42px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px;">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>

                <!-- Live Quick Filter Pills -->
                <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap; padding-top: 0.85rem; border-top: 1px dashed #e2e8f0;">
                    <span style="font-size: 0.72rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; margin-right: 0.25rem;">
                        <i class="bi bi-lightning-charge"></i> Filter Cepat:
                    </span>
                    <button type="button" class="quick-pill <?= (empty($nature) && empty($status)) ? 'active' : '' ?>" style="<?= (empty($nature) && empty($status)) ? 'background:#0f3d64;color:#fff;border-color:#0f3d64;' : '' ?>" onclick="location.href='/sekcab/berkas'">Semua</button>
                    <button type="button" class="quick-pill <?= ($nature ?? '') === 'Rahasia' ? 'active' : '' ?>" style="<?= ($nature ?? '') === 'Rahasia' ? 'background:#e11d48;color:#fff;border-color:#e11d48;' : '' ?>" onclick="location.href='/sekcab/berkas?nature=Rahasia'"><i class="bi bi-shield-lock-fill"></i> Rahasia</button>
                    <button type="button" class="quick-pill <?= ($nature ?? '') === 'Penting' ? 'active' : '' ?>" style="<?= ($nature ?? '') === 'Penting' ? 'background:#d97706;color:#fff;border-color:#d97706;' : '' ?>" onclick="location.href='/sekcab/berkas?nature=Penting'"><i class="bi bi-star-fill"></i> Penting</button>
                    <button type="button" class="quick-pill <?= ($nature ?? '') === 'Segera' ? 'active' : '' ?>" style="<?= ($nature ?? '') === 'Segera' ? 'background:#0284c7;color:#fff;border-color:#0284c7;' : '' ?>" onclick="location.href='/sekcab/berkas?nature=Segera'"><i class="bi bi-lightning-fill"></i> Segera</button>
                    <button type="button" class="quick-pill <?= ($status ?? '') === 'Diarsipkan' ? 'active' : '' ?>" style="<?= ($status ?? '') === 'Diarsipkan' ? 'background:#059669;color:#fff;border-color:#059669;' : '' ?>" onclick="location.href='/sekcab/berkas?status=Diarsipkan'"><i class="bi bi-archive-fill"></i> Diarsipkan</button>
                    <span class="live-counter text-muted" style="margin-left: auto; font-size: 0.78rem; font-weight: 600;">
                        Menampilkan <strong style="color: #0f3d64;"><?= count($documents) ?></strong> berkas surat terdaftar
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. Tabel Daftar Berkas Surat & Verifikasi Duplikasi -->
    <div class="ws-card">
        <div class="ws-card-header">
            <div class="ws-card-header-left">
                <div class="ws-card-icon" style="background: #e0f2fe; color: #0284c7;">
                    <i class="bi bi-files"></i>
                </div>
                <div>
                    <h2 class="ws-card-title">Daftar Berkas Persuratan &amp; Verifikasi Duplikasi Redundan</h2>
                    <p class="ws-card-subtitle">Dokumen resmi kesekretariatan dengan penanda Berkas Asli (Master) dan Salinan Cadangan (SHA-256 Identik)</p>
                </div>
            </div>
            <div>
                <span class="badge badge-success" style="font-size: 0.74rem; font-weight: 700; padding: 0.35rem 0.7rem;">
                    <i class="bi bi-shield-fill-check"></i> Redundansi Keamanan Aktif
                </span>
            </div>
        </div>

        <div class="ws-table-wrap">
            <table class="ws-table" id="archiveFilesTable">
                <thead>
                    <tr>
                        <th style="width: 48px; text-align: center;">No</th>
                        <th style="min-width: 240px;">Perihal Surat &amp; Keterangan</th>
                        <th style="min-width: 125px; text-align: center;">Tgl Registrasi</th>
                        <th style="min-width: 115px; text-align: center;">Sifat Surat</th>
                        <th style="min-width: 215px; text-align: center;">Brankas Berkas &amp; Duplikasi</th>
                        <th style="min-width: 120px; text-align: center;">Status</th>
                        <th style="min-width: 95px; text-align: center;">Ukuran</th>
                        <th style="width: 105px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($documents)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3.5rem 1.5rem;">
                                <div style="width: 56px; height: 56px; border-radius: 16px; background: #f0f9ff; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; font-size: 1.65rem; margin-bottom: 0.75rem;">
                                    <i class="bi bi-folder2-open"></i>
                                </div>
                                <div style="font-weight: 800; font-size: 1rem; color: #0f172a; margin-bottom: 0.25rem;">Belum ada berkas persuratan yang cocok</div>
                                <p class="text-muted" style="margin: 0 auto 0.9rem; max-width: 420px; font-size: 0.82rem;">
                                    Belum ada dokumen surat yang sesuai dengan kriteria pencarian atau filter yang Anda pilih.
                                </p>
                                <div style="display: inline-flex; gap: 0.5rem;">
                                    <a href="/sekcab/berkas" class="btn btn-outline btn-sm" style="text-decoration: none;">Reset Filter</a>
                                    <?php if ($isSekcab): ?>
                                        <a href="/sekcab/arsip" class="btn btn-primary btn-sm" style="text-decoration: none;">+ Catat Surat Baru</a>
                                    <?php endif; ?>
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
                                'Sangat Segera' => 'badge-danger',
                                'Penting' => 'badge-warning',
                                'Segera' => 'badge-primary',
                                default => 'badge-neutral',
                            };
                            $natureIcon = match ($nature) {
                                'Rahasia' => 'bi-shield-lock-fill',
                                'Sangat Segera' => 'bi-exclamation-octagon-fill',
                                'Penting' => 'bi-exclamation-triangle-fill',
                                'Segera' => 'bi-lightning-charge-fill',
                                default => 'bi-file-earmark-text',
                            };
                            $status = $doc['status'] ?? 'Diarsipkan';
                            $statusBadge = match ($status) {
                                'Selesai' => 'badge-neutral',
                                'Menunggu Tindak Lanjut' => 'badge-warning',
                                'Didisposisi' => 'badge-primary',
                                default => 'badge-success',
                            };
                            $hasFile = !empty($doc['stored_name']);
                            $hasDuplicate = !empty($doc['backup_stored_name']) || !empty($doc['is_duplicated']);
                            $shortHash = !empty($doc['file_hash']) ? substr((string)$doc['file_hash'], 0, 10) . '…' : 'SHA-256 OK';
                        ?>
                            <tr>
                                <td style="text-align: center; color: #64748b; font-weight: 700;">
                                    <?= $no++ ?>
                                </td>
                                <td>
                                    <div style="font-weight: 800; color: #0f172a; font-size: 0.9rem; margin-bottom: 0.22rem; line-height: 1.35;">
                                        <?= e($doc['title']) ?>
                                    </div>
                                    <?php if (!empty($doc['description'])): ?>
                                        <div style="font-size: 0.77rem; color: #64748b; line-height: 1.45; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= e($doc['description']) ?>">
                                            <?= e($doc['description']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <div style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.79rem; font-weight: 700; color: #1e293b; background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.25rem 0.6rem; border-radius: 8px;">
                                        <i class="bi bi-calendar3" style="color: #0284c7;"></i>
                                        <span><?= !empty($doc['letter_date']) ? date('d M Y', strtotime($doc['letter_date'])) : date('d M Y', strtotime($doc['created_at'])) ?></span>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge <?= $natureBadge ?>" style="font-size: 0.73rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem;">
                                        <i class="bi <?= $natureIcon ?>"></i>
                                        <span><?= e($nature) ?></span>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($hasFile): ?>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <div class="ws-doc-pills" style="justify-content: center;">
                                                <a href="<?= e($doc['download_url']) ?>" 
                                                   class="ws-doc-pill ws-doc-primary" 
                                                   title="Unduh Berkas Asli (Master)">
                                                    <i class="bi bi-download"></i>
                                                    <span>Asli</span>
                                                </a>
                                                <?php if ($hasDuplicate): ?>
                                                    <a href="<?= e($doc['download_backup_url']) ?>" 
                                                       class="ws-doc-pill ws-doc-backup" 
                                                       title="Unduh Salinan Duplikasi Cadangan (Identik 100%)">
                                                        <i class="bi bi-shield-check"></i>
                                                        <span>Duplikasi</span>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ws-hash-badge">
                                                <i class="bi bi-patch-check-fill"></i>
                                                <span><?= e($shortHash) ?></span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.3rem;">
                                            <span class="badge badge-warning" style="font-size: 0.7rem; padding: 0.22rem 0.55rem;">
                                                <i class="bi bi-file-earmark-excel"></i> Tanpa Lampiran
                                            </span>
                                            <?php if ($isSekcab): ?>
                                                <a href="/sekcab/arsip" class="ws-icon-btn ws-btn-edit" style="font-size: 0.68rem; padding: 0.2rem 0.5rem; border-style: dashed; text-decoration: none;">
                                                    <i class="bi bi-cloud-upload"></i> Unggah
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge <?= $statusBadge ?>" style="font-size: 0.73rem; font-weight: 700;">
                                        <?= e($status) ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-size: 0.78rem; font-weight: 700; color: #334155;">
                                        <?= $hasFile ? e($doc['file_size_formatted']) : '—' ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="ws-action-group">
                                        <?php if ($hasFile): ?>
                                            <a href="<?= e($doc['download_url']) ?>" class="ws-icon-btn ws-btn-edit" style="text-decoration: none;" title="Unduh Berkas Master">
                                                <i class="bi bi-cloud-arrow-down-fill"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($isSekcab): ?>
                                            <a href="/sekcab/arsip" class="ws-icon-btn ws-btn-edit" style="text-decoration: none;" title="Kelola di Buku Agenda Sekcab">
                                                <i class="bi bi-journal-bookmark-fill"></i>
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
            <div style="padding: 0.85rem 1.25rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <?= partial('partials.pagination', $pagination) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
