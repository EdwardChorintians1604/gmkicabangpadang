<?php
/**
 * =====================================================================
 * PANEL EKSEKUTIF KETUA CABANG (KETCAB)
 * Fokus: Komando Strategis, Amanat Tri Panji GMKI, Komposisi Civitas & Soliditas BPC
 * =====================================================================
 */
$totalCivitas = (int) ($summary['total_civitas'] ?? ($counts['members'] ?? 0));
$activeCivitas = (int) ($summary['active_civitas'] ?? 0);
$pasifCivitas = (int) ($summary['pasif_civitas'] ?? 0);
$alumniOnlyCivitas = (int) ($summary['alumni_only_civitas'] ?? 0);
$seniorCivitas = (int) ($summary['senior_civitas'] ?? 0);
$totalKomisariat = (int) ($summary['total_komisariat'] ?? 0);
$totalProker = (int) ($counts['strategies'] ?? 0);

$pctActive = $totalCivitas > 0 ? round(($activeCivitas / $totalCivitas) * 100) : 0;
$pctPasif = $totalCivitas > 0 ? round(($pasifCivitas / $totalCivitas) * 100) : 0;
$pctAlumni = $totalCivitas > 0 ? round(($alumniOnlyCivitas / $totalCivitas) * 100) : 0;
$pctSenior = $totalCivitas > 0 ? round(($seniorCivitas / $totalCivitas) * 100) : 0;
?>

<div class="exec-panel executive-tab-content">

    <!-- 1. Banner Amanat & Komando Strategis Ketua Cabang -->
    <div class="exec-role-banner banner-ketcab">
        <div class="exec-banner-inner">
            <div>
                <div class="exec-banner-eyebrow">
                    <i class="bi bi-award-fill" style="color: #fbbf24;"></i>
                    <span>AMANAT &amp; KOMANDO STRATEGIS KETUA CABANG</span>
                </div>
                <h2 class="exec-banner-title">Arah Kebijakan &amp; Tri Panji GMKI Cabang Padang</h2>
                <p class="exec-banner-desc">
                    Memastikan kesinambungan perarakan pelayanan di tiga medan layan — <strong>Gereja, Perguruan Tinggi, dan Masyarakat</strong> — melalui kaderisasi yang berakar pada iman Kristiani, ketajaman intelektual, dan keberpihakan sosial.
                </p>
                <div class="exec-banner-pills">
                    <span class="exec-banner-pill">
                        <i class="bi bi-calendar-check" style="color: #fbbf24;"></i>
                        <span>Masa Bakti BPC: <code>2026–2027</code></span>
                    </span>
                    <span class="exec-banner-pill">
                        <i class="bi bi-people-fill" style="color: #38bdf8;"></i>
                        <span>Basis Kader: <code><?= number_format($totalCivitas) ?> Civitas</code></span>
                    </span>
                    <span class="exec-banner-pill">
                        <i class="bi bi-buildings-fill" style="color: #34d399;"></i>
                        <span>Komisariat Aktif: <code><?= number_format($totalKomisariat) ?> Kampus</code></span>
                    </span>
                </div>
            </div>

            <div class="exec-banner-actions">
                <a href="/ketcab/proker" class="exec-btn exec-btn-gold">
                    <i class="bi bi-kanban-fill"></i>
                    <span>Pantau Proker Sekfung</span>
                </a>
                <a href="/bencab/laporan" class="exec-btn exec-btn-glass">
                    <i class="bi bi-wallet2"></i>
                    <span>Laporan Kas BPC</span>
                </a>
                <a href="/admin/statistik" class="exec-btn exec-btn-glass">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span>Analitik Cabang</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Tiga Pilar Tri Panji GMKI & Medan Layan (3 Feature Cards) -->
    <div class="exec-grid-3">
        <!-- Pilar 1: Tinggi Iman -->
        <div class="exec-feature-card accent-navy">
            <div>
                <div class="exec-feature-top">
                    <div class="exec-feature-icon" style="background: #e0f2fe; color: #0369a1;">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <span class="exec-feature-tag" style="background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd;">
                        <i class="bi bi-brightness-high-fill"></i> Medan Layan Gereja
                    </span>
                </div>
                <h3 class="exec-feature-title">Tinggi Iman</h3>
                <p class="exec-feature-desc">
                    Pembinaan spiritualitas kader melalui ibadah persekutuan cabang, penelaahan Alkitab (PA) kontekstual, serta keteladanan etika Kristiani di tengah kehidupan kampus dan masyarakat.
                </p>
            </div>
            <div class="exec-feature-footer" style="color: #0369a1;">
                <span><i class="bi bi-check2-circle"></i> Spiritualitas &amp; Oikumene</span>
                <span style="font-size: 0.7rem; color: #64748b;">Pilar I</span>
            </div>
        </div>

        <!-- Pilar 2: Tinggi Ilmu -->
        <div class="exec-feature-card accent-gold">
            <div>
                <div class="exec-feature-top">
                    <div class="exec-feature-icon" style="background: #fef3c7; color: #b45309;">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <span class="exec-feature-tag" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a;">
                        <i class="bi bi-book-half"></i> Medan Perguruan Tinggi
                    </span>
                </div>
                <h3 class="exec-feature-title">Tinggi Ilmu</h3>
                <p class="exec-feature-desc">
                    Pengembangan daya nalar kritis, pelaksanaan PDSK berjenjang, kajian isu strategis daerah &amp; nasional, serta pencetakan kader intelektual yang unggul di setiap komisariat.
                </p>
            </div>
            <div class="exec-feature-footer" style="color: #b45309;">
                <span><i class="bi bi-check2-circle"></i> Kaderisasi &amp; Intelektualitas</span>
                <span style="font-size: 0.7rem; color: #64748b;">Pilar II</span>
            </div>
        </div>

        <!-- Pilar 3: Tinggi Pengabdian -->
        <div class="exec-feature-card accent-emerald">
            <div>
                <div class="exec-feature-top">
                    <div class="exec-feature-icon" style="background: #d1fae5; color: #047857;">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <span class="exec-feature-tag" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                        <i class="bi bi-globe-asia-australia"></i> Medan Masyarakat
                    </span>
                </div>
                <h3 class="exec-feature-title">Tinggi Pengabdian</h3>
                <p class="exec-feature-desc">
                    Aksi nyata advokasi kebijakan publik, solidaritas kemanusiaan di Sumatera Barat, kepedulian ekologis, serta pembangunan jejaring lintas organisasi kepemudaan.
                </p>
            </div>
            <div class="exec-feature-footer" style="color: #047857;">
                <span><i class="bi bi-check2-circle"></i> Aksi Sosial &amp; Kemasyarakatan</span>
                <span style="font-size: 0.7rem; color: #64748b;">Pilar III</span>
            </div>
        </div>
    </div>

    <!-- 3. Ringkasan 4 Status Keanggotaan Civitas Cabang -->
    <div class="exec-grid-4">
        <a href="/admin/civitas?status=Aktif" class="exec-stat-box" style="border-top: 3px solid #10b981;">
            <div class="exec-stat-top">
                <span class="exec-stat-label"><i class="bi bi-check-circle-fill" style="color: #10b981;"></i> Anggota Aktif</span>
                <span class="exec-feature-tag" style="background: #ecfdf5; color: #047857;"><?= $pctActive ?>%</span>
            </div>
            <div class="exec-stat-num" style="color: #047857;"><?= number_format($activeCivitas) ?></div>
            <div class="exec-stat-sub">
                <span>Mahasiswa aktif berkegiatan</span>
                <i class="bi bi-arrow-right" style="color: #047857;"></i>
            </div>
        </a>

        <a href="/admin/civitas?status=Pasif" class="exec-stat-box" style="border-top: 3px solid #f59e0b;">
            <div class="exec-stat-top">
                <span class="exec-stat-label"><i class="bi bi-pause-circle-fill" style="color: #f59e0b;"></i> Anggota Pasif</span>
                <span class="exec-feature-tag" style="background: #fffbeb; color: #b45309;"><?= $pctPasif ?>%</span>
            </div>
            <div class="exec-stat-num" style="color: #b45309;"><?= number_format($pasifCivitas) ?></div>
            <div class="exec-stat-sub">
                <span>Perlu konsolidasi komisariat</span>
                <i class="bi bi-arrow-right" style="color: #b45309;"></i>
            </div>
        </a>

        <a href="/admin/civitas?status=Alumni" class="exec-stat-box" style="border-top: 3px solid #0284c7;">
            <div class="exec-stat-top">
                <span class="exec-stat-label"><i class="bi bi-mortarboard-fill" style="color: #0284c7;"></i> Alumni</span>
                <span class="exec-feature-tag" style="background: #f0f9ff; color: #0369a1;"><?= $pctAlumni ?>%</span>
            </div>
            <div class="exec-stat-num" style="color: #0369a1;"><?= number_format($alumniOnlyCivitas) ?></div>
            <div class="exec-stat-sub">
                <span>Kader telah wisuda / lulus</span>
                <i class="bi bi-arrow-right" style="color: #0369a1;"></i>
            </div>
        </a>

        <a href="/admin/civitas?status=Senior" class="exec-stat-box" style="border-top: 3px solid #6366f1;">
            <div class="exec-stat-top">
                <span class="exec-stat-label"><i class="bi bi-award-fill" style="color: #6366f1;"></i> Senior / BKS</span>
                <span class="exec-feature-tag" style="background: #eef2ff; color: #4338ca;"><?= $pctSenior ?>%</span>
            </div>
            <div class="exec-stat-num" style="color: #4338ca;"><?= number_format($seniorCivitas) ?></div>
            <div class="exec-stat-sub">
                <span>Senior &amp; mitra pelayanan</span>
                <i class="bi bi-arrow-right" style="color: #4338ca;"></i>
            </div>
        </a>
    </div>

    <!-- 4. Struktur Fungsionaris Badan Pengurus Cabang (BPC) Aktif -->
    <div class="exec-section-card">
        <div class="exec-section-header">
            <div class="exec-section-title-group">
                <div class="exec-section-icon" style="background: #e0f2fe; color: #0f3d64;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h3 class="exec-section-title">Fungsionaris Badan Pengurus Cabang (BPC) Aktif</h3>
                    <p class="exec-section-subtitle">Susunan pimpinan harian dan sekretaris fungsional GMKI Cabang Padang</p>
                </div>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <?php if (is_admin()): ?>
                    <a href="/admin/organisasi/struktur" class="btn btn-outline btn-sm inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 font-bold">
                        <i class="bi bi-gear-fill"></i>
                        <span>Kelola Struktur</span>
                    </a>
                <?php endif; ?>
                <a href="/struktur-organisasi" target="_blank" class="btn btn-outline btn-sm inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 font-bold">
                    <span>Halaman Struktur Publik</span>
                    <i class="bi bi-box-arrow-up-right"></i>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Urutan</th>
                        <th>Nama Fungsionaris</th>
                        <th>Jabatan Strategis</th>
                        <th>Periode</th>
                        <th>Kontak Koordinasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($structure)): ?>
                        <?php foreach (array_slice($structure, 0, 8) as $p):
                            $initial = strtoupper(substr(trim((string)($p['nama'] ?? 'P')), 0, 1));
                            ?>
                            <tr>
                                <td><span style="font-weight: 800; color: #64748b;">#<?= (int)$p['urutan'] ?></span></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                                        <span style="width: 32px; height: 32px; border-radius: 9px; background: linear-gradient(135deg, #0f3d64, #1e5687); color: #ffffff; display: inline-flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 800; flex-shrink: 0;">
                                            <?= e($initial) ?>
                                        </span>
                                        <strong style="color: #0f172a;"><?= e($p['nama']) ?></strong>
                                    </div>
                                </td>
                                <td><span class="badge badge-primary"><?= e($p['jabatan']) ?></span></td>
                                <td class="text-muted"><?= e($p['periode'] ?? '2026-2027') ?></td>
                                <td>
                                    <?php if (!empty($p['telepon'])): ?>
                                        <code style="background: #f8fafc; padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid #e2e8f0;"><?= e($p['telepon']) ?></code>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-success inline-flex items-center gap-1">
                                        <i class="bi bi-check-circle-fill"></i> Aktif
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 2.25rem;">
                                Belum ada data struktur fungsionaris BPC yang tercatat.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
