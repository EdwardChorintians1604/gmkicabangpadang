<?php
/**
 * =====================================================================
 * PANEL EKSEKUTIF SEKRETARIS CABANG (SEKCAB)
 * Fokus: Tata Usaha, Arsip Persuratan SHA-256, Inventaris, Data Civitas & Kaderisasi
 * =====================================================================
 */
$mapercaCount = (int) ($summary['maperca_count'] ?? ($summary['total_maperca'] ?? 0));
$anggotaCount = (int) ($summary['anggota_count'] ?? 0);
$kkCount = (int) ($summary['kk_count'] ?? 0);
$alumniCount = (int) ($summary['alumni_count'] ?? ($summary['alumni_civitas'] ?? 0));

$sumKader = max(1, $mapercaCount + $anggotaCount + $kkCount + $alumniCount);
$pctMaperca = round(($mapercaCount / $sumKader) * 100);
$pctAnggota = round(($anggotaCount / $sumKader) * 100);
$pctKK = round(($kkCount / $sumKader) * 100);
$pctAlumni = max(0, 100 - ($pctMaperca + $pctAnggota + $pctKK));

$activeCivitas = (int) ($summary['active_civitas'] ?? 0);
$pasifCivitas = (int) ($summary['pasif_civitas'] ?? 0);
$alumniOnlyCivitas = (int) ($summary['alumni_only_civitas'] ?? 0);
$seniorCivitas = (int) ($summary['senior_civitas'] ?? 0);
?>

<div class="exec-panel executive-tab-content">

    <!-- 1. Banner Tata Usaha & Kesekretariatan Sekcab -->
    <div class="exec-role-banner banner-sekcab">
        <div class="exec-banner-inner">
            <div>
                <div class="exec-banner-eyebrow">
                    <i class="bi bi-journal-bookmark-fill" style="color: #7dd3fc;"></i>
                    <span>PUSAT TATA USAHA &amp; KESEKRETARIATAN SEKCAB</span>
                </div>
                <h2 class="exec-banner-title">Ketertiban Administrasi, Persuratan &amp; Pangkalan Data Civitas</h2>
                <p class="exec-banner-desc">
                    Mengelola buku agenda persuratan resmi dengan duplikasi cadangan <strong>SHA-256</strong>, pencatatan aset inventaris cabang, pemeliharaan dokumen KTA privat, serta pemutakhiran data kader se-Kota Padang.
                </p>
                <div class="exec-banner-pills">
                    <span class="exec-banner-pill">
                        <i class="bi bi-upc-scan" style="color: #7dd3fc;"></i>
                        <span>Standar ID Civitas: <code>{TahunMaper}-{YY}-{Inisial}</code></span>
                    </span>
                    <span class="exec-banner-pill">
                        <i class="bi bi-shield-lock-fill" style="color: #34d399;"></i>
                        <span>Arsip Surat: <code>Redundansi SHA-256 Aktif</code></span>
                    </span>
                </div>
            </div>

            <div class="exec-banner-actions">
                <a href="/sekcab/arsip" class="exec-btn exec-btn-sky">
                    <i class="bi bi-archive-fill"></i>
                    <span>Buku Agenda Arsip</span>
                </a>
                <a href="/sekcab/inventaris" class="exec-btn exec-btn-glass">
                    <i class="bi bi-boxes"></i>
                    <span>Inventaris Cabang</span>
                </a>
                <a href="/admin/civitas/export" class="exec-btn exec-btn-glass">
                    <i class="bi bi-download"></i>
                    <span>Unduh Data Civitas (CSV)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Empat Indikator Jenjang Kaderisasi Cabang (4 Stat Boxes) -->
    <div class="exec-grid-4">
        <a href="/admin/maperca" class="exec-stat-box" style="border-top: 3px solid #f59e0b;">
            <div class="exec-stat-top">
                <span class="exec-stat-label"><i class="bi bi-mortarboard-fill" style="color: #f59e0b;"></i> 1. Kader Maperca</span>
                <span class="exec-feature-tag" style="background: #fffbeb; color: #b45309;"><?= $pctMaperca ?>%</span>
            </div>
            <div class="exec-stat-num" style="color: #b45309;"><?= number_format($mapercaCount) ?></div>
            <div class="exec-stat-sub">
                <span>Masa perkenalan calon anggota</span>
                <i class="bi bi-arrow-right" style="color: #b45309;"></i>
            </div>
        </a>

        <a href="/admin/civitas" class="exec-stat-box" style="border-top: 3px solid #0284c7;">
            <div class="exec-stat-top">
                <span class="exec-stat-label"><i class="bi bi-person-check-fill" style="color: #0284c7;"></i> 2. Anggota Biasa</span>
                <span class="exec-feature-tag" style="background: #f0f9ff; color: #0369a1;"><?= $pctAnggota ?>%</span>
            </div>
            <div class="exec-stat-num" style="color: #0369a1;"><?= number_format($anggotaCount) ?></div>
            <div class="exec-stat-sub">
                <span>Anggota sah telah dilantik</span>
                <i class="bi bi-arrow-right" style="color: #0369a1;"></i>
            </div>
        </a>

        <a href="/admin/civitas" class="exec-stat-box" style="border-top: 3px solid #4f46e5;">
            <div class="exec-stat-top">
                <span class="exec-stat-label"><i class="bi bi-award-fill" style="color: #4f46e5;"></i> 3. Kader Lanjut (KK)</span>
                <span class="exec-feature-tag" style="background: #eef2ff; color: #4338ca;"><?= $pctKK ?>%</span>
            </div>
            <div class="exec-stat-num" style="color: #4338ca;"><?= number_format($kkCount) ?></div>
            <div class="exec-stat-sub">
                <span>Kursus kepemimpinan &amp; pengurus</span>
                <i class="bi bi-arrow-right" style="color: #4338ca;"></i>
            </div>
        </a>

        <a href="/admin/civitas?status=Alumni" class="exec-stat-box" style="border-top: 3px solid #10b981;">
            <div class="exec-stat-top">
                <span class="exec-stat-label"><i class="bi bi-person-lines-fill" style="color: #10b981;"></i> 4. Alumni &amp; Senior</span>
                <span class="exec-feature-tag" style="background: #ecfdf5; color: #047857;"><?= $pctAlumni ?>%</span>
            </div>
            <div class="exec-stat-num" style="color: #047857;"><?= number_format($alumniCount) ?></div>
            <div class="exec-stat-sub">
                <span>Purna bakti &amp; senior cabang</span>
                <i class="bi bi-arrow-right" style="color: #047857;"></i>
            </div>
        </a>
    </div>

    <!-- 3. Detail Sebaran Kaderisasi & Tata Kelola Dokumen Sekcab (Balanced 2-Column Grid) -->
    <div class="exec-grid-2">
        <!-- Left Card: Sebaran Kader Menurut Jenjang & Status -->
        <div class="exec-section-card">
            <div class="exec-section-header">
                <div class="exec-section-title-group">
                    <div class="exec-section-icon" style="background: #e0f2fe; color: #0369a1;">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <div>
                        <h3 class="exec-section-title">Sebaran Kader Menurut Jenjang &amp; Status</h3>
                        <p class="exec-section-subtitle">Rekapitulasi berjenjang struktur kaderisasi GMKI Cabang Padang</p>
                    </div>
                </div>
                <span class="badge badge-primary">Siklus Kader</span>
            </div>
            <div class="exec-section-body">
                <div>
                    <!-- Segmented Visual Bar -->
                    <div style="width: 100%; height: 10px; background: #f1f5f9; border-radius: 9999px; overflow: hidden; display: flex; margin-bottom: 1.1rem;">
                        <div style="width: <?= $pctMaperca ?>%; background: #f59e0b;" title="Maperca: <?= $mapercaCount ?>"></div>
                        <div style="width: <?= $pctAnggota ?>%; background: #0284c7;" title="Anggota Biasa: <?= $anggotaCount ?>"></div>
                        <div style="width: <?= $pctKK ?>%; background: #4f46e5;" title="Kursus Kepemimpinan: <?= $kkCount ?>"></div>
                        <div style="width: <?= $pctAlumni ?>%; background: #10b981;" title="Alumni &amp; Senior: <?= $alumniCount ?>"></div>
                    </div>

                    <div class="exec-stage-item">
                        <div class="exec-stage-left">
                            <div class="exec-stage-badge" style="background: #fef3c7; color: #d97706;">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div>
                                <div class="exec-stage-name">Masa Perkenalan Calon Anggota (Maperca)</div>
                                <div class="exec-stage-desc">Peserta baru siap mengikuti pembinaan dan peneguhan</div>
                            </div>
                        </div>
                        <span class="exec-stage-val" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a;">
                            <?= number_format($mapercaCount) ?> Kader
                        </span>
                    </div>

                    <div class="exec-stage-item">
                        <div class="exec-stage-left">
                            <div class="exec-stage-badge" style="background: #e0f2fe; color: #0284c7;">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <div>
                                <div class="exec-stage-name">Anggota Biasa (Sah Dilantik)</div>
                                <div class="exec-stage-desc">Memiliki hak bicara, hak suara, dan terdaftar di komisariat</div>
                            </div>
                        </div>
                        <span class="exec-stage-val" style="background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd;">
                            <?= number_format($anggotaCount) ?> Anggota
                        </span>
                    </div>

                    <div class="exec-stage-item">
                        <div class="exec-stage-left">
                            <div class="exec-stage-badge" style="background: #e0e7ff; color: #4338ca;">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div>
                                <div class="exec-stage-name">Kursus Kepemimpinan (KK) &amp; Fungsionaris</div>
                                <div class="exec-stage-desc">Kader tingkat lanjut penggerak komisariat dan BPC</div>
                            </div>
                        </div>
                        <span class="exec-stage-val" style="background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe;">
                            <?= number_format($kkCount) ?> Kader
                        </span>
                    </div>

                    <div class="exec-stage-item">
                        <div class="exec-stage-left">
                            <div class="exec-stage-badge" style="background: #d1fae5; color: #047857;">
                                <i class="bi bi-person-lines-fill"></i>
                            </div>
                            <div>
                                <div class="exec-stage-name">Alumni &amp; Senior Cabang</div>
                                <div class="exec-stage-desc">Terdiri atas <?= number_format($alumniOnlyCivitas) ?> Alumni dan <?= number_format($seniorCivitas) ?> Senior</div>
                            </div>
                        </div>
                        <span class="exec-stage-val" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                            <?= number_format($alumniCount) ?> Civitas
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Card: Kesiapan Berkas Privat, Persuratan & KTA -->
        <div class="exec-section-card">
            <div class="exec-section-header">
                <div class="exec-section-title-group">
                    <div class="exec-section-icon" style="background: #d1fae5; color: #047857;">
                        <i class="bi bi-file-earmark-lock2-fill"></i>
                    </div>
                    <div>
                        <h3 class="exec-section-title">Protokol Arsip Persuratan &amp; Dokumen KTA</h3>
                        <p class="exec-section-subtitle">Keamanan penyimpanan dokumen privat dan kendali kesekretariatan</p>
                    </div>
                </div>
                <span class="badge badge-success">Terenkapsulasi</span>
            </div>
            <div class="exec-section-body">
                <div>
                    <div class="exec-stage-item">
                        <div class="exec-stage-left">
                            <div class="exec-stage-badge" style="background: #ecfdf5; color: #059669;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <div class="exec-stage-name">Isolasi Direktori Privat (Pas Foto &amp; KTA)</div>
                                <div class="exec-stage-desc">Tersimpan di <code>storage/private/</code> dan hanya dapat diakses via stream terautentikasi</div>
                            </div>
                        </div>
                        <span class="badge badge-success">Aman</span>
                    </div>

                    <div class="exec-stage-item">
                        <div class="exec-stage-left">
                            <div class="exec-stage-badge" style="background: #eff6ff; color: #2563eb;">
                                <i class="bi bi-files"></i>
                            </div>
                            <div>
                                <div class="exec-stage-name">Salinan Redundan Arsip Surat (Master + Backup)</div>
                                <div class="exec-stage-desc">Setiap dokumen surat otomatis digandakan dengan verifikasi hash SHA-256</div>
                            </div>
                        </div>
                        <span class="badge badge-primary">SHA-256</span>
                    </div>

                    <div class="exec-stage-item">
                        <div class="exec-stage-left">
                            <div class="exec-stage-badge" style="background: #fef3c7; color: #d97706;">
                                <i class="bi bi-upc-scan"></i>
                            </div>
                            <div>
                                <div class="exec-stage-name">Generator ID Anggota Otomatis</div>
                                <div class="exec-stage-desc">Format <code>TahunMaper-2DigitTahunLahir-Inisial</code> (contoh: <code>2025-04-EPS</code>)</div>
                            </div>
                        </div>
                        <span class="badge badge-warning">Aktif</span>
                    </div>
                </div>

                <div style="margin-top: 1.15rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; display: flex; flex-wrap: wrap; gap: 0.6rem;">
                    <a href="/admin/civitas" class="btn btn-primary btn-sm inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 font-bold">
                        <i class="bi bi-people-fill"></i>
                        <span>Buka Database Civitas</span>
                    </a>
                    <a href="/sekcab/berkas" class="btn btn-outline btn-sm inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 font-bold">
                        <i class="bi bi-folder2-open"></i>
                        <span>Direktori Berkas Surat</span>
                    </a>
                    <a href="/admin/komisariat" class="btn btn-outline btn-sm inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 font-bold">
                        <i class="bi bi-buildings-fill"></i>
                        <span>Data Komisariat</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
