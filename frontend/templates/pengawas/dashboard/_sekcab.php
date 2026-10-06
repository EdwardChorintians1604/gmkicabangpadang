<?php
/**
 * Tab Pemantauan: SEKRETARIS CABANG (SEKCAB)
 * Fokus: Tata Kelola Administrasi, Data Keanggotaan, Jenjang Kaderisasi, Persuratan & Warta
 */
?>
<div class="executive-tab-content">
    <div class="card" style="margin-bottom: 1.5rem; background: #ffffff; border-left: 4px solid var(--secondary);">
        <div class="card-body" style="padding: 1.5rem 1.75rem;">
            <div class="flex items-center justify-between" style="flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="margin: 0 0 0.35rem 0; font-size: 1.25rem; font-weight: 800; color: var(--primary);">Pantauan Tata Usaha & Arus Kaderisasi</h3>
                    <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
                        Pengawasan ketertiban berkas identitas anggota (KTA Canva), mutasi cabang, dan jenjang kaderisasi formal civitas se-Kota Padang.
                    </p>
                </div>
                <div>
                    <a href="/pengawas/civitas/export" class="btn btn-secondary btn-sm flex items-center gap-1">
                        <?= svg_icon('download', 15) ?>
                        <span>Unduh Laporan Civitas (CSV)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Kaderisasi & Jenjang -->
    <div class="sekcab-summary-grid">
        <div class="card sekcab-summary-card">
            <div class="card-header sekcab-summary-header">
                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--primary);">Sebaran Kader Menurut Jenjang</h4>
                <span class="badge badge-primary">Kaderisasi</span>
            </div>
            <div class="card-body">
                <div class="sekcab-stage-list">
                    <div class="sekcab-stage-row">
                        <span>Masa Perkenalan Calon Anggota (Maperca)</span>
                        <strong class="text-primary"><?= (int)($summary['maperca_count'] ?? 0) ?> Kader</strong>
                    </div>
                    <div class="sekcab-stage-row">
                        <span>Anggota Biasa (Sah Dilantik)</span>
                        <strong class="text-secondary"><?= (int)($summary['anggota_count'] ?? 0) ?> Anggota</strong>
                    </div>
                    <div class="sekcab-stage-row">
                        <span>Kursus Kepemimpinan (KK)</span>
                        <strong style="color: #10b981;"><?= (int)($summary['kk_count'] ?? 0) ?> Kader</strong>
                    </div>
                    <div class="sekcab-stage-row">
                        <span>Senior / Alumni Cabang</span>
                        <strong class="text-muted"><?= (int)($summary['alumni_count'] ?? 0) ?> Senior</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card sekcab-summary-card">
            <div class="card-header sekcab-summary-header">
                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--primary);">Kesiapan Berkas Privat & KTA</h4>
                <span class="badge badge-success">Dokumentasi</span>
            </div>
            <div class="card-body">
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.6; margin-bottom: 1rem;">
                    Seluruh berkas pas foto anggota dan berkas KTA Canva tersimpan secara aman di direktori privat (<code>storage/private/</code>) dan hanya dapat diakses melalui verifikasi autentikasi pengurus.
                </p>
                <div class="sekcab-document-actions">
                    <a href="/pengawas/civitas" class="btn btn-outline btn-sm">Periksa Data Civitas &rarr;</a>
                    <a href="/pengawas/berita" class="btn btn-outline btn-sm">Periksa Warta Cabang &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
