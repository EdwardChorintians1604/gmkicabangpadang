<?php
// Hitung rasio dan metrik pembantu
$totalCivitas = (int) ($summary['total_civitas'] ?? 0);
$activeCivitas = (int) ($summary['active_civitas'] ?? 0);
$pasifCivitas = (int) ($summary['pasif_civitas'] ?? 0);
$alumniOnlyCivitas = (int) ($summary['alumni_only_civitas'] ?? 0);
$seniorCivitas = (int) ($summary['senior_civitas'] ?? 0);
$totalMaperca = (int) ($summary['total_maperca'] ?? 0);
$totalKomisariat = (int) ($summary['total_komisariat'] ?? 0);
$totalBerita = (int) ($summary['total_berita'] ?? 0);
$totalViews = (int) ($summary['total_views'] ?? 0);

$activePercent = $totalCivitas > 0 ? round(($activeCivitas / $totalCivitas) * 100) : 0;
$pasifPercent = $totalCivitas > 0 ? round(($pasifCivitas / $totalCivitas) * 100) : 0;
$alumniOnlyPercent = $totalCivitas > 0 ? round(($alumniOnlyCivitas / $totalCivitas) * 100) : 0;
$seniorPercent = $totalCivitas > 0 ? round(($seniorCivitas / $totalCivitas) * 100) : 0;

$mapercaCount = (int) ($summary['maperca_count'] ?? $totalMaperca);
$anggotaCount = (int) ($summary['anggota_count'] ?? 0);
$kkCount = (int) ($summary['kk_count'] ?? 0);
$alumniCount = (int) ($summary['alumni_count'] ?? ($summary['alumni_civitas'] ?? 0));

$sumKader = max(1, $mapercaCount + $anggotaCount + $kkCount + $alumniCount);
$pctMaperca = round(($mapercaCount / $sumKader) * 100);
$pctAnggota = round(($anggotaCount / $sumKader) * 100);
$pctKK = round(($kkCount / $sumKader) * 100);
$pctAlumni = max(0, 100 - ($pctMaperca + $pctAnggota + $pctKK));

// Hitung rasio gender dari chartData
$genderData = $chartData['gender']['data'] ?? [0, 0];
$genderMale = (int) ($genderData[0] ?? 0);
$genderFemale = (int) ($genderData[1] ?? 0);
$genderTotal = max(1, $genderMale + $genderFemale);
$pctMale = ($genderMale + $genderFemale) > 0 ? round(($genderMale / $genderTotal) * 100) : 0;
$pctFemale = ($genderMale + $genderFemale) > 0 ? (100 - $pctMale) : 0;
?>

<div class="superadmin-dashboard">

    <!-- 1. Executive Hero Header Banner -->
    <div class="admin-hero-banner animate__animated animate__fadeInDown">
        <div class="admin-hero-inner">
            <div>
                <div class="admin-hero-tagline">
                    <span class="pulse-dot"></span>
                    <span>PUSAT KENDALI SUPERADMIN</span>
                    <span style="opacity: 0.4;">•</span>
                    <span>BPC GMKI CABANG PADANG</span>
                </div>
                <h1 class="admin-hero-title" style="color: #ffffff !important;">Dashboard Kendali &amp; Pengelolaan
                    Terpadu</h1>
                <p class="admin-hero-desc">
                    Selamat bertugas, <strong
                        style="color: #ffffff; text-decoration: underline; text-decoration-color: #fbbf24; text-underline-offset: 4px;"><?= e($user['nama_lengkap']) ?></strong>.
                    Kelola pangkalan data civitas, kaderisasi Maperca, komisariat, publikasi warta, dan keamanan sistem
                    secara terpusat.
                    <span style="color: #fcd34d; font-weight: 700; font-style: italic;">Ut Omnes Unum Sint!</span>
                </p>
                <div class="hero-meta-pills">
                    <span class="hero-meta-pill">
                        <i class="bi bi-upc-scan" style="color: #38bdf8;"></i>
                        <span>Format ID Otomatis: <code>{TahunMaper}-{YY}-{Inisial}</code></span>
                    </span>
                    <span class="hero-meta-pill">
                        <i class="bi bi-people-fill" style="color: #34d399;"></i>
                        <span>Status Civitas: Aktif • Pasif • Alumni • Senior</span>
                    </span>
                </div>
            </div>
            <div class="admin-hero-actions">
                <a href="/admin/civitas/create" class="btn-hero-primary">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Daftarkan Civitas</span>
                </a>
                <a href="/admin/maperca" class="btn-hero-glass">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Kelola Kader Maper</span>
                </a>
                <a href="/admin/berita/create" class="btn-hero-glass">
                    <i class="bi bi-pencil-square"></i>
                    <span>Tulis Warta</span>
                </a>
                <a href="/admin/keamanan/backup" class="btn-hero-glass">
                    <i class="bi bi-cloud-arrow-down-fill"></i>
                    <span>Cadangan Data</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Executive KPI Metrics Grid (5 Interactive Cards) -->
    <div class="executive-kpi-grid animate__animated animate__fadeInUp">
        <!-- Card 1: Total Civitas -->
        <a href="/admin/civitas" class="executive-metric-card metric-card-blue" title="Kelola seluruh data Civitas">
            <div class="metric-header">
                <div class="metric-title-group">
                    <span class="metric-label-text">Total Civitas</span>
                    <span class="metric-badge-pill metric-badge-blue">Basis Data Cabang</span>
                </div>
                <div class="metric-icon-box icon-box-blue">
                    <i class="bi bi-people-fill" style="font-size: 1.2rem;"></i>
                </div>
            </div>
            <div class="metric-value-num"><?= number_format($totalCivitas) ?></div>
            <div class="metric-sub-detail">
                <span class="metric-sub-left">
                    <i class="bi bi-check-circle-fill" style="color: #0284c7;"></i>
                    <span>Kader &amp; civitas terdaftar</span>
                </span>
                <span class="metric-sub-action">Kelola <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>

        <!-- Card 2: Anggota Aktif -->
        <a href="/admin/civitas?status=Aktif" class="executive-metric-card metric-card-emerald"
            title="Filter anggota berstatus Aktif">
            <div class="metric-header">
                <div class="metric-title-group">
                    <span class="metric-label-text">Anggota Aktif</span>
                    <span class="metric-badge-pill metric-badge-emerald"><?= $activePercent ?>% Rasio Aktif</span>
                </div>
                <div class="metric-icon-box icon-box-emerald">
                    <i class="bi bi-person-check-fill" style="font-size: 1.2rem;"></i>
                </div>
            </div>
            <div class="metric-value-num" style="color: #059669;"><?= number_format($activeCivitas) ?></div>
            <div class="metric-sub-detail">
                <span class="metric-sub-left">
                    <i class="bi bi-shield-check" style="color: #059669;"></i>
                    <span>Mahasiswa aktif berkegiatan</span>
                </span>
                <span class="metric-sub-action">Filter <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>

        <!-- Card 3: Kader Baru Maperca -->
        <a href="/admin/maperca" class="executive-metric-card metric-card-amber" title="Kelola peserta Maperca">
            <div class="metric-header">
                <div class="metric-title-group">
                    <span class="metric-label-text">Kader Maper</span>
                    <span class="metric-badge-pill metric-badge-amber">Masa Perkenalan</span>
                </div>
                <div class="metric-icon-box icon-box-amber">
                    <i class="bi bi-mortarboard-fill" style="font-size: 1.2rem;"></i>
                </div>
            </div>
            <div class="metric-value-num" style="color: #d97706;"><?= number_format($totalMaperca) ?></div>
            <div class="metric-sub-detail">
                <span class="metric-sub-left">
                    <i class="bi bi-hourglass-split" style="color: #d97706;"></i>
                    <span>Calon anggota siap lantik</span>
                </span>
                <span class="metric-sub-action">Buka <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>

        <!-- Card 4: Komisariat Kampus -->
        <a href="/admin/komisariat" class="executive-metric-card metric-card-indigo" title="Kelola daftar Komisariat">
            <div class="metric-header">
                <div class="metric-title-group">
                    <span class="metric-label-text">Komisariat</span>
                    <span class="metric-badge-pill metric-badge-indigo">Basis Kampus</span>
                </div>
                <div class="metric-icon-box icon-box-indigo">
                    <i class="bi bi-buildings-fill" style="font-size: 1.2rem;"></i>
                </div>
            </div>
            <div class="metric-value-num" style="color: #4338ca;"><?= number_format($totalKomisariat) ?></div>
            <div class="metric-sub-detail">
                <span class="metric-sub-left">
                    <i class="bi bi-bank" style="color: #4f46e5;"></i>
                    <span>Komisariat aktif se-Padang</span>
                </span>
                <span class="metric-sub-action">Atur <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>

        <!-- Card 5: Publikasi Berita -->
        <a href="/admin/berita" class="executive-metric-card metric-card-rose" title="Kelola Warta &amp; Berita Cabang">
            <div class="metric-header">
                <div class="metric-title-group">
                    <span class="metric-label-text">Warta &amp; Berita</span>
                    <span class="metric-badge-pill metric-badge-rose"><?= number_format($totalViews) ?> Pembaca</span>
                </div>
                <div class="metric-icon-box icon-box-rose">
                    <i class="bi bi-newspaper" style="font-size: 1.2rem;"></i>
                </div>
            </div>
            <div class="metric-value-num" style="color: #e11d48;"><?= number_format($totalBerita) ?></div>
            <div class="metric-sub-detail">
                <span class="metric-sub-left">
                    <i class="bi bi-broadcast" style="color: #e11d48;"></i>
                    <span>Artikel &amp; siaran pers tayang</span>
                </span>
                <span class="metric-sub-action">Redaksi <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>
    </div>

    <!-- 3. Pusat Kendali & Manajemen Cepat (8-Card Operational Hub) -->
    <div class="dash-section-card animate__animated animate__fadeIn">
        <div class="dash-section-header">
            <div class="dash-section-title-wrap">
                <div class="dash-section-icon" style="background: #fef3c7; color: #d97706;">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>
                <div>
                    <h2 class="dash-section-title">
                        <span>Pusat Kendali &amp; Pengelolaan Cepat</span>
                        <span class="metric-badge-pill metric-badge-blue">Superadmin</span>
                    </h2>
                    <p class="dash-section-subtitle">Navigasi langsung ke seluruh modul utama agar pengaturan organisasi
                        lebih cepat dan terarah</p>
                </div>
            </div>
            <a href="/admin/statistik" class="dash-header-link">
                <i class="bi bi-bar-chart-line-fill"></i>
                <span>Lihat Statistik Penuh</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="dash-section-body">
            <div class="dash-control-grid">
                <!-- 1. Database Civitas -->
                <a href="/admin/civitas" class="dash-control-item">
                    <div class="dash-control-icon" style="background: #e0f2fe; color: #0369a1;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="dash-control-text">
                        <div class="dash-control-title">
                            <span>Database Civitas</span>
                            <i class="bi bi-chevron-right dash-control-arrow"></i>
                        </div>
                        <p class="dash-control-desc">Kelola seluruh anggota, filter status, detail biodata &amp; cetak
                            KTA</p>
                    </div>
                </a>

                <!-- 2. Tambah Civitas Manual -->
                <a href="/admin/civitas/create" class="dash-control-item">
                    <div class="dash-control-icon" style="background: #d1fae5; color: #047857;">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div class="dash-control-text">
                        <div class="dash-control-title">
                            <span>Input Civitas Baru</span>
                            <i class="bi bi-chevron-right dash-control-arrow"></i>
                        </div>
                        <p class="dash-control-desc">Daftarkan anggota/civitas secara langsung dengan ID otomatis</p>
                    </div>
                </a>

                <!-- 3. Kaderisasi Maperca -->
                <a href="/admin/maperca" class="dash-control-item">
                    <div class="dash-control-icon" style="background: #fef3c7; color: #b45309;">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div class="dash-control-text">
                        <div class="dash-control-title">
                            <span>Kader Baru (Maper)</span>
                            <i class="bi bi-chevron-right dash-control-arrow"></i>
                        </div>
                        <p class="dash-control-desc">Pantau peserta Maper &amp; proses pelantikan menjadi anggota</p>
                    </div>
                </a>

                <!-- 4. Impor & Ekspor Excel -->
                <a href="/admin/civitas/impor" class="dash-control-item">
                    <div class="dash-control-icon" style="background: #ecfccb; color: #3f6212;">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                    </div>
                    <div class="dash-control-text">
                        <div class="dash-control-title">
                            <span>Impor &amp; Ekspor Data</span>
                            <i class="bi bi-chevron-right dash-control-arrow"></i>
                        </div>
                        <p class="dash-control-desc">Migrasi massal data civitas via template Excel (.xlsx / .csv)</p>
                    </div>
                </a>

                <!-- 5. Komisariat & Kampus -->
                <a href="/admin/komisariat" class="dash-control-item">
                    <div class="dash-control-icon" style="background: #e0e7ff; color: #4338ca;">
                        <i class="bi bi-buildings-fill"></i>
                    </div>
                    <div class="dash-control-text">
                        <div class="dash-control-title">
                            <span>Basis Komisariat</span>
                            <i class="bi bi-chevron-right dash-control-arrow"></i>
                        </div>
                        <p class="dash-control-desc">Atur daftar komisariat &amp; pemetaan perguruan tinggi binaan</p>
                    </div>
                </a>

                <!-- 6. Warta & Publikasi -->
                <a href="/admin/berita" class="dash-control-item">
                    <div class="dash-control-icon" style="background: #ffe4e6; color: #be123c;">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <div class="dash-control-text">
                        <div class="dash-control-title">
                            <span>Warta &amp; Publikasi</span>
                            <i class="bi bi-chevron-right dash-control-arrow"></i>
                        </div>
                        <p class="dash-control-desc">Kelola rilis berita, artikel kader, &amp; pengumuman cabang</p>
                    </div>
                </a>

                <!-- 7. Struktur & Profil BPC -->
                <a href="/admin/struktur" class="dash-control-item">
                    <div class="dash-control-icon" style="background: #f3e8ff; color: #6b21a8;">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <div class="dash-control-text">
                        <div class="dash-control-title">
                            <span>Struktur &amp; Profil BPC</span>
                            <i class="bi bi-chevron-right dash-control-arrow"></i>
                        </div>
                        <p class="dash-control-desc">Perbarui susunan pengurus BPC &amp; informasi profil cabang</p>
                    </div>
                </a>

                <!-- 8. Cadangan & Keamanan -->
                <a href="/admin/keamanan/backup" class="dash-control-item">
                    <div class="dash-control-icon" style="background: #f1f5f9; color: #334155;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div class="dash-control-text">
                        <div class="dash-control-title">
                            <span>Cadangan &amp; Keamanan</span>
                            <i class="bi bi-chevron-right dash-control-arrow"></i>
                        </div>
                        <p class="dash-control-desc">Unduh arsip backup (.zip / .sql.gz) &amp; verifikasi integritas</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Visualisasi & Komposisi Keanggotaan (Balanced 2-Column Grid: 7fr / 5fr) -->
    <div class="dash-analytics-grid animate__animated animate__fadeIn">
        <!-- Left Card (7fr): Bar Chart Persebaran Civitas per Komisariat -->
        <div class="dash-section-card">
            <div class="dash-section-header">
                <div class="dash-section-title-wrap">
                    <div class="dash-section-icon" style="background: #e0f2fe; color: #0369a1;">
                        <i class="bi bi-bar-chart-steps"></i>
                    </div>
                    <div>
                        <h3 class="dash-section-title">Persebaran Civitas per Komisariat</h3>
                        <p class="dash-section-subtitle">Distribusi anggota &amp; kader pada tiap basis komisariat di
                            Kota Padang</p>
                    </div>
                </div>
                <a href="/admin/komisariat" class="dash-header-link">
                    <i class="bi bi-buildings-fill"></i>
                    <span>Kelola Komisariat</span>
                </a>
            </div>
            <div class="dash-section-body">
                <div class="chart-card-body" style="height: 275px;">
                    <canvas id="chartKomisariat"></canvas>
                </div>
                <div class="dash-chart-footer">
                    <span><i class="bi bi-info-circle text-sky-600"></i> Menampilkan komisariat aktif dengan anggota
                        terdaftar</span>
                    <a href="/admin/civitas" style="font-weight: 700; color: #0f3d64; text-decoration: none;">Lihat
                        Daftar Anggota →</a>
                </div>
            </div>
        </div>

        <!-- Right Card (5fr): Donut Chart Rasio Gender Civitas -->
        <div class="dash-section-card">
            <div class="dash-section-header">
                <div class="dash-section-title-wrap">
                    <div class="dash-section-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                    <div>
                        <h3 class="dash-section-title">Rasio Gender Civitas</h3>
                        <p class="dash-section-subtitle">Proporsi keseimbangan kader Putra (L) &amp; Putri (P)</p>
                    </div>
                </div>
                <span class="metric-badge-pill metric-badge-amber">Total:
                    <?= number_format($genderMale + $genderFemale) ?> Civitas</span>
            </div>
            <div class="dash-section-body">
                <div class="chart-card-body" style="height: 225px;">
                    <canvas id="chartGender"></canvas>
                </div>
                <div class="dash-gender-summary">
                    <div class="dash-gender-pill">
                        <span class="dash-gender-label">
                            <span class="dash-gender-dot" style="background: #0f3d64;"></span>
                            <span>Putra (L)</span>
                        </span>
                        <span class="dash-gender-val"><?= number_format($genderMale) ?> <small
                                style="font-weight: 600; color: #64748b;">(<?= $pctMale ?>%)</small></span>
                    </div>
                    <div class="dash-gender-pill">
                        <span class="dash-gender-label">
                            <span class="dash-gender-dot" style="background: #f59e0b;"></span>
                            <span>Putri (P)</span>
                        </span>
                        <span class="dash-gender-val"><?= number_format($genderFemale) ?> <small
                                style="font-weight: 600; color: #64748b;">(<?= $pctFemale ?>%)</small></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Siklus Keanggotaan & Aktivitas Operasional (Balanced 2-Column Grid: 6fr / 6fr) -->
    <div class="dash-bottom-grid animate__animated animate__fadeIn">
        <!-- Left Card (6fr): Status Keanggotaan (4 Status) & Jenjang Kaderisasi -->
        <div class="dash-section-card">
            <div class="dash-section-header">
                <div class="dash-section-title-wrap">
                    <div class="dash-section-icon" style="background: #e0e7ff; color: #4338ca;">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <div>
                        <h3 class="dash-section-title">Status Keanggotaan &amp; Jenjang Kaderisasi</h3>
                        <p class="dash-section-subtitle">Klik kartu status di bawah untuk memfilter langsung pangkalan
                            data civitas</p>
                    </div>
                </div>
                <a href="/admin/civitas" class="dash-header-link">
                    <i class="bi bi-funnel-fill"></i>
                    <span>Filter Civitas</span>
                </a>
            </div>
            <div class="dash-section-body" style="justify-content: flex-start;">
                <!-- Bagian A: 4 Status Keanggotaan Resmi -->
                <div class="dash-subblock-label">
                    <span><i class="bi bi-person-badge-fill" style="color: #0284c7;"></i> 4 Status Keanggotaan
                        Civitas</span>
                    <span style="font-weight: 600; color: #64748b; text-transform: none;">Klik untuk lihat daftar</span>
                </div>
                <div class="dash-status-grid">
                    <a href="/admin/civitas?status=Aktif" class="dash-status-card"
                        style="border-top: 3px solid #10b981;">
                        <div class="dash-status-top">
                            <span class="dash-status-name">
                                <i class="bi bi-check-circle-fill" style="color: #10b981;"></i> Aktif
                            </span>
                            <span class="metric-badge-pill metric-badge-emerald"><?= $activePercent ?>%</span>
                        </div>
                        <div class="dash-status-count" style="color: #047857;"><?= number_format($activeCivitas) ?>
                        </div>
                        <div class="dash-status-meta">
                            <span>Mahasiswa aktif</span>
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>

                    <a href="/admin/civitas?status=Pasif" class="dash-status-card"
                        style="border-top: 3px solid #f59e0b;">
                        <div class="dash-status-top">
                            <span class="dash-status-name">
                                <i class="bi bi-pause-circle-fill" style="color: #f59e0b;"></i> Pasif
                            </span>
                            <span class="metric-badge-pill metric-badge-amber"><?= $pasifPercent ?>%</span>
                        </div>
                        <div class="dash-status-count" style="color: #b45309;"><?= number_format($pasifCivitas) ?></div>
                        <div class="dash-status-meta">
                            <span>Jarang aktif</span>
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>

                    <a href="/admin/civitas?status=Alumni" class="dash-status-card"
                        style="border-top: 3px solid #0284c7;">
                        <div class="dash-status-top">
                            <span class="dash-status-name">
                                <i class="bi bi-mortarboard-fill" style="color: #0284c7;"></i> Alumni
                            </span>
                            <span class="metric-badge-pill metric-badge-blue"><?= $alumniOnlyPercent ?>%</span>
                        </div>
                        <div class="dash-status-count" style="color: #0369a1;"><?= number_format($alumniOnlyCivitas) ?>
                        </div>
                        <div class="dash-status-meta">
                            <span>Telah wisuda</span>
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>

                    <a href="/admin/civitas?status=Senior" class="dash-status-card"
                        style="border-top: 3px solid #6366f1;">
                        <div class="dash-status-top">
                            <span class="dash-status-name">
                                <i class="bi bi-award-fill" style="color: #6366f1;"></i> Senior
                            </span>
                            <span class="metric-badge-pill metric-badge-indigo"><?= $seniorPercent ?>%</span>
                        </div>
                        <div class="dash-status-count" style="color: #4338ca;"><?= number_format($seniorCivitas) ?>
                        </div>
                        <div class="dash-status-meta">
                            <span>Senior / BKS</span>
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                </div>

                <!-- Bagian B: Jenjang Pembinaan & Kaderisasi -->
                <div class="dash-subblock-label" style="margin-top: 0.25rem;">
                    <span><i class="bi bi-layers-fill" style="color: #4f46e5;"></i> Tahapan Jenjang Kaderisasi</span>
                    <span style="font-weight: 600; color: #64748b; text-transform: none;">Total Siklus:
                        <?= number_format($sumKader) ?> orang</span>
                </div>

                <div class="dash-pipeline-bar">
                    <div style="width: <?= $pctMaperca ?>%; background: #f59e0b;" title="Maperca: <?= $mapercaCount ?>">
                    </div>
                    <div style="width: <?= $pctAnggota ?>%; background: #0284c7;" title="Anggota: <?= $anggotaCount ?>">
                    </div>
                    <div style="width: <?= $pctKK ?>%; background: #4f46e5;" title="KK / Pengurus: <?= $kkCount ?>">
                    </div>
                    <div style="width: <?= $pctAlumni ?>%; background: #64748b;" title="Alumni: <?= $alumniCount ?>">
                    </div>
                </div>

                <div class="dash-pipeline-grid">
                    <div class="dash-pipeline-card" style="border-left-color: #f59e0b;">
                        <div class="dash-pipeline-card-top">
                            <span>1. Maperca</span>
                            <span style="color: #d97706;"><?= $pctMaperca ?>%</span>
                        </div>
                        <div class="dash-pipeline-card-val" style="color: #b45309;"><?= number_format($mapercaCount) ?>
                            <small style="font-weight: 500; font-size: 0.72rem; color: #64748b;">org</small>
                        </div>
                        <div class="dash-pipeline-card-sub">Calon Anggota</div>
                    </div>

                    <div class="dash-pipeline-card" style="border-left-color: #0284c7;">
                        <div class="dash-pipeline-card-top">
                            <span>2. Anggota</span>
                            <span style="color: #0284c7;"><?= $pctAnggota ?>%</span>
                        </div>
                        <div class="dash-pipeline-card-val" style="color: #0369a1;"><?= number_format($anggotaCount) ?>
                            <small style="font-weight: 500; font-size: 0.72rem; color: #64748b;">org</small>
                        </div>
                        <div class="dash-pipeline-card-sub">Telah Dilantik</div>
                    </div>

                    <div class="dash-pipeline-card" style="border-left-color: #4f46e5;">
                        <div class="dash-pipeline-card-top">
                            <span>3. KK / BPC</span>
                            <span style="color: #4f46e5;"><?= $pctKK ?>%</span>
                        </div>
                        <div class="dash-pipeline-card-val" style="color: #4338ca;"><?= number_format($kkCount) ?>
                            <small style="font-weight: 500; font-size: 0.72rem; color: #64748b;">org</small>
                        </div>
                        <div class="dash-pipeline-card-sub">Kader Lanjut</div>
                    </div>

                    <div class="dash-pipeline-card" style="border-left-color: #64748b;">
                        <div class="dash-pipeline-card-top">
                            <span>4. Purna</span>
                            <span style="color: #475569;"><?= $pctAlumni ?>%</span>
                        </div>
                        <div class="dash-pipeline-card-val" style="color: #334155;"><?= number_format($alumniCount) ?>
                            <small style="font-weight: 500; font-size: 0.72rem; color: #64748b;">org</small>
                        </div>
                        <div class="dash-pipeline-card-sub">Alumni &amp; Senior</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Card (6fr): Aktivitas & Jejak Operasional Terkini -->
        <div class="dash-section-card">
            <div class="dash-section-header">
                <div class="dash-section-title-wrap">
                    <div class="dash-section-icon" style="background: #f1f5f9; color: #0f3d64;">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div>
                        <h3 class="dash-section-title">Aktivitas &amp; Jejak Operasional Terkini</h3>
                        <p class="dash-section-subtitle">Pencatatan real-time autentikasi pengguna dan pembaruan data
                            sistem</p>
                    </div>
                </div>
                <a href="/admin/keamanan/audit-log" class="dash-header-link">
                    <span>Semua Jejak</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div style="flex: 1 1 auto; overflow-y: auto; max-height: 360px;">
                <?php if (!empty($recentLogs)): ?>
                    <?php foreach ($recentLogs as $log):
                        $action = strtoupper($log['action'] ?? '');
                        $badgeClass = 'badge-action-default';

                        if (str_contains($action, 'TAMBAH') || str_contains($action, 'CREATE') || str_contains($action, 'SIMPAN')) {
                            $badgeClass = 'badge-action-create';
                        } elseif (str_contains($action, 'UBAH') || str_contains($action, 'UPDATE') || str_contains($action, 'EDIT')) {
                            $badgeClass = 'badge-action-update';
                        } elseif (str_contains($action, 'HAPUS') || str_contains($action, 'DELETE')) {
                            $badgeClass = 'badge-action-delete';
                        } elseif (str_contains($action, 'LOGIN') || str_contains($action, 'MASUK') || str_contains($action, 'LOGOUT')) {
                            $badgeClass = 'badge-action-auth';
                        }

                        $initial = strtoupper(substr($log['username'] ?? 'U', 0, 1));
                        $timeString = date('H:i:s', strtotime($log['created_at'])) . ' WIB';
                        $dateString = format_date($log['created_at'], 'd M Y');
                        ?>
                        <div class="activity-feed-item">
                            <div class="activity-feed-left">
                                <div class="activity-avatar"><?= $initial ?></div>
                                <div style="min-width: 0;">
                                    <div class="activity-main-text">
                                        <span class="activity-user-name"><?= e($log['username']) ?></span>
                                        <span
                                            style="font-size: 0.72rem; color: #64748b;">(<?= e(strtoupper($log['role'])) ?>)</span>
                                        <span style="margin: 0 0.25rem; color: #cbd5e1;">•</span>
                                        <span class="<?= $badgeClass ?>"><?= e($log['action']) ?></span>
                                        <span style="margin: 0 0.25rem; color: #cbd5e1;">•</span>
                                        <span
                                            class="activity-badge-entity"><?= e($log['entity']) ?><?php if (!empty($log['entity_id'])): ?>
                                                #<?= e($log['entity_id']) ?><?php endif; ?></span>
                                    </div>
                                    <div class="activity-meta">
                                        <span><i class="bi bi-calendar3" style="color: #94a3b8;"></i> <?= $dateString ?></span>
                                        <span>•</span>
                                        <span><i class="bi bi-clock" style="color: #94a3b8;"></i> <?= $timeString ?></span>
                                    </div>
                                </div>
                            </div>
                            <div style="flex-shrink: 0;">
                                <span class="activity-ip-pill" title="Alamat IP Klien">
                                    <i class="bi bi-globe2" style="color: #94a3b8;"></i>
                                    <span><?= e($log['ip_address']) ?></span>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; color: #94a3b8; padding: 2.5rem 1rem; font-size: 0.85rem;">
                        Belum ada catatan aktivitas sistem hari ini.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 6. System Diagnostics / Telemetry Strip -->
    <div class="dash-telemetry-bar animate__animated animate__fadeIn">
        <div class="dash-telemetry-items">
            <span class="dash-telemetry-item">
                <i class="bi bi-database-fill-check" style="color: #10b981; font-size: 0.9rem;"></i>
                <span>Basis Data: MariaDB / MySQL (Terhubung)</span>
            </span>
            <span class="dash-telemetry-item">
                <i class="bi bi-upc-scan" style="color: #0284c7; font-size: 0.9rem;"></i>
                <span>Standar ID: {TahunMaper}-{YY}-{Inisial}</span>
            </span>
            <span class="dash-telemetry-item">
                <i class="bi bi-shield-fill-check" style="color: #4f46e5; font-size: 0.9rem;"></i>
                <span>Keamanan: WAF Anti-WebShell, CSRF &amp; Session Guard Aktif</span>
            </span>
        </div>
        <div
            style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 0.72rem; color: #64748b; display: inline-flex; align-items: center; gap: 0.4rem;">
            <i class="bi bi-clock-history"></i>
            <span>Waktu Server: <?= date('d M Y - H:i') ?> WIB</span>
        </div>
    </div>

</div>

<script>
    // Injeksi payload data statistik ke Chart.js
    window.chartDataPayload = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
</script>