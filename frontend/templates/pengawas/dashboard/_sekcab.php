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
                    <a href="/pengawas/civitas/export" class="btn btn-secondary btn-sm inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-bold shadow-sm">
                        <i class="bi bi-download"></i>
                        <span>Unduh Laporan Civitas (CSV)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Kaderisasi & Jenjang -->
    <div class="sekcab-summary-grid">
        <div class="card sekcab-summary-card rounded-2xl shadow-sm border border-slate-200/80">
            <div class="card-header sekcab-summary-header flex items-center justify-between p-4 border-b border-slate-100">
                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--primary);" class="flex items-center gap-2">
                    <i class="bi bi-diagram-3-fill text-sky-700"></i>
                    <span>Sebaran Kader Menurut Jenjang</span>
                </h4>
                <span class="badge badge-primary">Kaderisasi</span>
            </div>
            <div class="card-body p-4 sm:p-5">
                <div class="sekcab-stage-list">
                    <div class="sekcab-stage-row">
                        <span class="flex items-center gap-2">
                            <i class="bi bi-mortarboard-fill text-amber-500"></i>
                            <span>Masa Perkenalan Calon Anggota (Maperca)</span>
                        </span>
                        <strong class="text-primary"><?= (int)($summary['maperca_count'] ?? 0) ?> Kader</strong>
                    </div>
                    <div class="sekcab-stage-row">
                        <span class="flex items-center gap-2">
                            <i class="bi bi-person-check-fill text-sky-600"></i>
                            <span>Anggota Biasa (Sah Dilantik)</span>
                        </span>
                        <strong class="text-secondary"><?= (int)($summary['anggota_count'] ?? 0) ?> Anggota</strong>
                    </div>
                    <div class="sekcab-stage-row">
                        <span class="flex items-center gap-2">
                            <i class="bi bi-award-fill text-emerald-600"></i>
                            <span>Kursus Kepemimpinan (KK)</span>
                        </span>
                        <strong style="color: #10b981;"><?= (int)($summary['kk_count'] ?? 0) ?> Kader</strong>
                    </div>
                    <div class="sekcab-stage-row">
                        <span class="flex items-center gap-2">
                            <i class="bi bi-person-lines-fill text-slate-400"></i>
                            <span>Senior / Alumni Cabang</span>
                        </span>
                        <strong class="text-muted"><?= (int)($summary['alumni_count'] ?? 0) ?> Senior</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card sekcab-summary-card rounded-2xl shadow-sm border border-slate-200/80">
            <div class="card-header sekcab-summary-header flex items-center justify-between p-4 border-b border-slate-100">
                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--primary);" class="flex items-center gap-2">
                    <i class="bi bi-file-earmark-lock2-fill text-emerald-600"></i>
                    <span>Kesiapan Berkas Privat &amp; KTA</span>
                </h4>
                <span class="badge badge-success">Dokumentasi</span>
            </div>
            <div class="card-body p-4 sm:p-5">
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.6; margin-bottom: 1rem;">
                    Seluruh berkas pas foto anggota dan berkas KTA Canva tersimpan secara aman di direktori privat (<code>storage/private/</code>) dan hanya dapat diakses melalui verifikasi autentikasi pengurus.
                </p>
                <div class="sekcab-document-actions flex flex-wrap gap-2">
                    <a href="/pengawas/civitas" class="btn btn-outline btn-sm inline-flex items-center gap-1.5 font-bold rounded-xl px-3 py-1.5">
                        <i class="bi bi-people"></i>
                        <span>Periksa Data Civitas</span>
                    </a>
                    <a href="/pengawas/berita" class="btn btn-outline btn-sm inline-flex items-center gap-1.5 font-bold rounded-xl px-3 py-1.5">
                        <i class="bi bi-newspaper"></i>
                        <span>Periksa Warta Cabang</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
