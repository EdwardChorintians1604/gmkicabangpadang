<?php
// Hitung rasio dan metrik pembantu
$totalCivitas = (int) ($summary['total_civitas'] ?? 0);
$activeCivitas = (int) ($summary['active_civitas'] ?? 0);
$totalMaperca = (int) ($summary['total_maperca'] ?? 0);
$totalKomisariat = (int) ($summary['total_komisariat'] ?? 0);
$totalBerita = (int) ($summary['total_berita'] ?? 0);
$totalViews = (int) ($summary['total_views'] ?? 0);

$activePercent = $totalCivitas > 0 ? round(($activeCivitas / $totalCivitas) * 100) : 0;
$mapercaCount = (int) ($summary['maperca_count'] ?? $totalMaperca);
$anggotaCount = (int) ($summary['anggota_count'] ?? 0);
$kkCount = (int) ($summary['kk_count'] ?? 0);
$alumniCount = (int) ($summary['alumni_count'] ?? ($summary['alumni_civitas'] ?? 0));

$sumKader = max(1, $mapercaCount + $anggotaCount + $kkCount + $alumniCount);
$pctMaperca = round(($mapercaCount / $sumKader) * 100);
$pctAnggota = round(($anggotaCount / $sumKader) * 100);
$pctKK = round(($kkCount / $sumKader) * 100);
$pctAlumni = max(0, 100 - ($pctMaperca + $pctAnggota + $pctKK));
?>

<!-- 1. Executive Hero Header Banner -->
<div class="admin-hero-banner animate__animated animate__fadeInDown">
    <div class="admin-hero-inner">
        <div>
            <div class="admin-hero-tagline">
                <span class="pulse-dot"></span>
                <span>PUSAT KENDALI OPERASIONAL CABANG</span>
                <span class="opacity-40">•</span>
                <span>MASA BAKTI 2026-2027</span>
            </div>
            <h1 class="admin-hero-title text-white font-extrabold" style="color: #ffffff !important;">Dashboard Kendali Administrator</h1>
            <p class="admin-hero-desc">
                Selamat bertugas, <strong class="text-white font-bold underline decoration-amber-400 decoration-2 underline-offset-4"><?= e($user['nama_lengkap']) ?></strong>.
                Sistem pangkalan data keanggotaan dan portal informasi terpadu BPC GMKI Cabang Padang.
                <span class="text-amber-300 font-semibold italic">Ut Omnes Unum Sint!</span>
            </p>
        </div>
        <div class="admin-hero-actions">
            <a href="/admin/civitas/create" class="btn-hero-primary">
                <?= svg_icon('plus', 16) ?>
                <span>Tambah Anggota</span>
            </a>
            <a href="/admin/berita/create" class="btn-hero-glass">
                <?= svg_icon('edit', 16) ?>
                <span>Tulis Warta</span>
            </a>
            <a href="/admin/koordinasi" class="btn-hero-glass">
                <?= svg_icon('users', 16) ?>
                <span>Chat Koordinasi</span>
            </a>
        </div>
    </div>
</div>

<!-- 2. Executive KPI Metrics Grid (5 Responsive Cards) -->
<div class="executive-kpi-grid animate__animated animate__fadeInUp">
    <!-- Card 1: Total Civitas -->
    <div class="executive-metric-card metric-card-blue">
        <div class="metric-header">
            <div class="metric-title-group">
                <span class="metric-label-text">Total Civitas</span>
                <span class="metric-badge-pill metric-badge-blue">Basis Data Lengkap</span>
            </div>
            <div class="metric-icon-box icon-box-blue">
                <?= svg_icon('users', 20) ?>
            </div>
        </div>
        <div class="metric-value-num"><?= number_format($totalCivitas) ?></div>
        <div class="metric-sub-detail">
            <span class="text-sky-600 font-bold">✓</span>
            <span>Kader & civitas terdaftar</span>
        </div>
    </div>

    <!-- Card 2: Anggota Aktif -->
    <div class="executive-metric-card metric-card-emerald">
        <div class="metric-header">
            <div class="metric-title-group">
                <span class="metric-label-text">Anggota Aktif</span>
                <span class="metric-badge-pill metric-badge-emerald"><?= $activePercent ?>% Rasio Aktif</span>
            </div>
            <div class="metric-icon-box icon-box-emerald">
                <?= svg_icon('check', 20) ?>
            </div>
        </div>
        <div class="metric-value-num text-emerald-600"><?= number_format($activeCivitas) ?></div>
        <div class="metric-sub-detail">
            <span class="text-emerald-600 font-bold">●</span>
            <span>Hak suara & partisipasi aktif</span>
        </div>
    </div>

    <!-- Card 3: Kader Baru Maperca -->
    <div class="executive-metric-card metric-card-amber">
        <div class="metric-header">
            <div class="metric-title-group">
                <span class="metric-label-text">Kader Maperca</span>
                <span class="metric-badge-pill metric-badge-amber">Masa Binaan</span>
            </div>
            <div class="metric-icon-box icon-box-amber">
                <?= svg_icon('user', 20) ?>
            </div>
        </div>
        <div class="metric-value-num text-amber-600"><?= number_format($totalMaperca) ?></div>
        <div class="metric-sub-detail">
            <span class="text-amber-600 font-bold">🌱</span>
            <span>Menunggu proses peneguhan</span>
        </div>
    </div>

    <!-- Card 4: Komisariat Kampus -->
    <div class="executive-metric-card metric-card-indigo">
        <div class="metric-header">
            <div class="metric-title-group">
                <span class="metric-label-text">Komisariat</span>
                <span class="metric-badge-pill metric-badge-indigo">Perguruan Tinggi</span>
            </div>
            <div class="metric-icon-box icon-box-indigo">
                <?= svg_icon('organization', 20) ?>
            </div>
        </div>
        <div class="metric-value-num text-indigo-700"><?= number_format($totalKomisariat) ?></div>
        <div class="metric-sub-detail">
            <span class="text-indigo-600 font-bold">🏛️</span>
            <span>Komisariat definitif se-Padang</span>
        </div>
    </div>

    <!-- Card 5: Publikasi Berita -->
    <div class="executive-metric-card metric-card-rose">
        <div class="metric-header">
            <div class="metric-title-group">
                <span class="metric-label-text">Warta & Berita</span>
                <span class="metric-badge-pill metric-badge-rose"><?= number_format($totalViews) ?> Pembaca</span>
            </div>
            <div class="metric-icon-box icon-box-rose">
                <?= svg_icon('news', 20) ?>
            </div>
        </div>
        <div class="metric-value-num text-rose-600"><?= number_format($totalBerita) ?></div>
        <div class="metric-sub-detail">
            <span class="text-rose-600 font-bold">📰</span>
            <span>Artikel & siaran pers tayang</span>
        </div>
    </div>
</div>

<!-- 3. Balanced Operational & Visual Analytics (2-Column Grid) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6 animate__animated animate__fadeIn">
    <!-- Left Column (8 cols): Bar Chart & Kaderisasi Progress -->
    <div class="lg:col-span-8 flex flex-col gap-6">
        <!-- Chart: Persebaran Civitas per Komisariat -->
        <div class="card chart-card rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden bg-white">
            <div class="chart-card-header flex items-center justify-between p-4 sm:p-5 border-b border-slate-100 bg-white">
                <div>
                    <h3 class="chart-card-title text-base sm:text-lg font-bold text-[#0f3d64] m-0">Persebaran Civitas per Komisariat</h3>
                    <p class="chart-card-subtitle text-xs sm:text-sm text-slate-500 m-0">Distribusi kader aktif pada tiap basis kampus binaan di Kota Padang</p>
                </div>
                <span class="badge text-xs px-3 py-1 rounded-full bg-sky-50 text-sky-800 border border-sky-200 font-bold">
                    🏛️ Komisariat
                </span>
            </div>
            <div class="chart-card-body p-4 sm:p-5" style="position: relative; height: 260px; width: 100%;">
                <canvas id="chartKomisariat"></canvas>
            </div>
        </div>

        <!-- Pipeline Kaderisasi (Jenjang Pembinaan) -->
        <div class="pipeline-widget bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-[#0f3d64] m-0 flex items-center gap-2">
                        <span>Jenjang Pembinaan & Kaderisasi Organisasi</span>
                        <span class="text-xs bg-slate-100 text-slate-600 font-semibold px-2 py-0.5 rounded-full">Siklus Anggota</span>
                    </h3>
                    <p class="text-xs text-slate-500 m-0 mt-0.5">Distribusi berjenjang dari Maperca, Anggota Biasa, Kader Tingkat Lanjut, hingga Alumni</p>
                </div>
                <a href="/admin/statistik" class="text-xs font-bold text-sky-700 hover:text-sky-900 flex items-center gap-1">
                    <span>Analisis Lengkap</span>
                    <?= svg_icon('chevron-right', 14) ?>
                </a>
            </div>

            <!-- Segmented Visual Progress Bar -->
            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden flex mb-4 shadow-inner">
                <div style="width: <?= $pctMaperca ?>%;" class="bg-amber-400 transition-all duration-500" title="Maperca: <?= $mapercaCount ?>"></div>
                <div style="width: <?= $pctAnggota ?>%;" class="bg-sky-500 transition-all duration-500" title="Anggota: <?= $anggotaCount ?>"></div>
                <div style="width: <?= $pctKK ?>%;" class="bg-indigo-600 transition-all duration-500" title="Kader Konsultan: <?= $kkCount ?>"></div>
                <div style="width: <?= $pctAlumni ?>%;" class="bg-slate-400 transition-all duration-500" title="Alumni/Senior: <?= $alumniCount ?>"></div>
            </div>

            <!-- Step Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="pipeline-step border-l-4 border-l-amber-400 p-3 bg-slate-50/70 rounded-xl">
                    <div class="pipeline-step-title flex items-center justify-between text-xs font-bold text-slate-600 mb-1">
                        <span>1. Maperca</span>
                        <span class="text-amber-600"><?= $pctMaperca ?>%</span>
                    </div>
                    <div class="pipeline-step-val text-lg font-extrabold text-amber-600"><?= $mapercaCount ?> <span class="text-xs font-normal text-slate-500">org</span></div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Calon Kader Baru</div>
                </div>
                <div class="pipeline-step border-l-4 border-l-sky-500 p-3 bg-slate-50/70 rounded-xl">
                    <div class="pipeline-step-title flex items-center justify-between text-xs font-bold text-slate-600 mb-1">
                        <span>2. Anggota</span>
                        <span class="text-sky-600"><?= $pctAnggota ?>%</span>
                    </div>
                    <div class="pipeline-step-val text-lg font-extrabold text-sky-600"><?= $anggotaCount ?> <span class="text-xs font-normal text-slate-500">org</span></div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Telah Diteguhkan</div>
                </div>
                <div class="pipeline-step border-l-4 border-l-indigo-600 p-3 bg-slate-50/70 rounded-xl">
                    <div class="pipeline-step-title flex items-center justify-between text-xs font-bold text-slate-600 mb-1">
                        <span>3. KK / Pengurus</span>
                        <span class="text-indigo-600"><?= $pctKK ?>%</span>
                    </div>
                    <div class="pipeline-step-val text-lg font-extrabold text-indigo-600"><?= $kkCount ?> <span class="text-xs font-normal text-slate-500">org</span></div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Kader Konsultan</div>
                </div>
                <div class="pipeline-step border-l-4 border-l-slate-400 p-3 bg-slate-50/70 rounded-xl">
                    <div class="pipeline-step-title flex items-center justify-between text-xs font-bold text-slate-600 mb-1">
                        <span>4. Alumni</span>
                        <span class="text-slate-500"><?= $pctAlumni ?>%</span>
                    </div>
                    <div class="pipeline-step-val text-lg font-extrabold text-slate-700"><?= $alumniCount ?> <span class="text-xs font-normal text-slate-500">org</span></div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Senior Member</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column (4 cols): Gender Donut & Quick Shortcuts -->
    <div class="lg:col-span-4 flex flex-col gap-6">
        <!-- Chart: Rasio Gender Civitas -->
        <div class="card chart-card rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden bg-white">
            <div class="chart-card-header flex items-center justify-between p-4 sm:p-5 border-b border-slate-100 bg-white">
                <div>
                    <h3 class="chart-card-title text-base sm:text-lg font-bold text-[#0f3d64] m-0">Rasio Gender Civitas</h3>
                    <p class="chart-card-subtitle text-xs sm:text-sm text-slate-500 m-0">Keseimbangan kader Putra & Putri</p>
                </div>
                <span class="badge text-xs px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 font-bold">
                    🚻 Gender
                </span>
            </div>
            <div class="chart-card-body p-4 sm:p-5" style="position: relative; height: 260px; width: 100%;">
                <canvas id="chartGender"></canvas>
            </div>
        </div>

        <!-- Pintasan Cepat Operasional (Compact Grid) -->
        <div class="card bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                <h3 class="text-sm sm:text-base font-bold text-[#0f3d64] m-0 flex items-center gap-1.5">
                    <span>Pintasan Operasional</span>
                    <span class="text-xs bg-sky-50 text-sky-700 font-bold px-2 py-0.5 rounded-full border border-sky-200">Aksi Cepat</span>
                </h3>
            </div>
            <div class="grid grid-cols-2 gap-2.5">
                <a href="/admin/civitas" class="quick-action-card p-2.5 rounded-xl border border-slate-200 hover:border-sky-400 hover:bg-sky-50/40 transition-all flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center flex-shrink-0">
                        <?= svg_icon('users', 16) ?>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-slate-800 truncate">Civitas</div>
                        <div class="text-[10px] text-slate-500 truncate">Pangkalan data</div>
                    </div>
                </a>

                <a href="/admin/maperca" class="quick-action-card p-2.5 rounded-xl border border-slate-200 hover:border-amber-400 hover:bg-amber-50/40 transition-all flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                        <?= svg_icon('user', 16) ?>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-slate-800 truncate">Maperca</div>
                        <div class="text-[10px] text-slate-500 truncate">Calon kader</div>
                    </div>
                </a>

                <a href="/admin/berita" class="quick-action-card p-2.5 rounded-xl border border-slate-200 hover:border-rose-400 hover:bg-rose-50/40 transition-all flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center flex-shrink-0">
                        <?= svg_icon('news', 16) ?>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-slate-800 truncate">Warta</div>
                        <div class="text-[10px] text-slate-500 truncate">Publikasi</div>
                    </div>
                </a>

                <a href="/admin/civitas/impor" class="quick-action-card p-2.5 rounded-xl border border-slate-200 hover:border-emerald-400 hover:bg-emerald-50/40 transition-all flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                        <?= svg_icon('upload', 16) ?>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-slate-800 truncate">Impor Excel</div>
                        <div class="text-[10px] text-slate-500 truncate">Migrasi massal</div>
                    </div>
                </a>

                <a href="/admin/keamanan/audit-log" class="quick-action-card p-2.5 rounded-xl border border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/40 transition-all flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center flex-shrink-0">
                        <?= svg_icon('shield', 16) ?>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-slate-800 truncate">Jejak Audit</div>
                        <div class="text-[10px] text-slate-500 truncate">Log sistem</div>
                    </div>
                </a>

                <a href="/admin/keamanan/backup" class="quick-action-card p-2.5 rounded-xl border border-slate-200 hover:border-slate-400 hover:bg-slate-100 transition-all flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                        <?= svg_icon('backup', 16) ?>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-slate-800 truncate">Cadangan</div>
                        <div class="text-[10px] text-slate-500 truncate">Backup DB</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 4. Executive Activity Stream & Operation Feed (Bukan Tabel Monolitik Kaku!) -->
<div class="activity-feed-card mb-6 animate__animated animate__fadeIn">
    <div class="activity-feed-header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                <?= svg_icon('shield', 18) ?>
            </div>
            <div>
                <h3 class="text-sm sm:text-base font-bold text-[#0f3d64] m-0">Aktivitas & Jejak Operasional Terkini</h3>
                <p class="text-slate-500 text-xs m-0">Pencatatan real-time autentikasi pengguna dan pembaruan data sistem</p>
            </div>
        </div>
        <a href="/admin/keamanan/audit-log" class="btn btn-outline btn-sm flex items-center gap-1.5 px-3 py-1.5 text-xs rounded-xl font-bold hover:bg-slate-50">
            <span>Lihat Semua Jejak</span>
            <?= svg_icon('chevron-right', 13) ?>
        </a>
    </div>

    <div class="divide-y divide-slate-100">
        <?php if (!empty($recentLogs)): ?>
            <?php foreach ($recentLogs as $log):
                $action = strtoupper($log['action'] ?? '');
                $badgeClass = 'badge-action-default';
                $actionLabel = $log['action'] ?? '';
                
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
                        <div>
                            <div class="activity-main-text">
                                <span class="activity-user-name"><?= e($log['username']) ?></span>
                                <span class="text-xs text-slate-500 font-normal">(<?= e(strtoupper($log['role'])) ?>)</span>
                                <span class="mx-1 text-slate-300">•</span>
                                <span class="<?= $badgeClass ?>"><?= e($log['action']) ?></span>
                                <span class="mx-1 text-slate-300">•</span>
                                <span class="activity-badge-entity"><?= e($log['entity']) ?><?php if (!empty($log['entity_id'])): ?> #<?= e($log['entity_id']) ?><?php endif; ?></span>
                            </div>
                            <div class="activity-meta">
                                <span>📅 <?= $dateString ?></span>
                                <span>•</span>
                                <span>⏰ <?= $timeString ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                        <span class="activity-ip-pill" title="Alamat IP Klien">🌐 <?= e($log['ip_address']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-slate-400 p-8 text-xs sm:text-sm">
                Belum ada catatan aktivitas sistem hari ini.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- 5. System Diagnostics / Telemetry Strip -->
<div class="flex flex-wrap items-center justify-between gap-3 p-3 px-4 rounded-xl bg-white border border-slate-200/80 text-xs text-slate-500 animate__animated animate__fadeIn shadow-sm">
    <div class="flex flex-wrap items-center gap-4">
        <span class="flex items-center gap-1.5 font-medium text-slate-700">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Basis Data: MariaDB (Terhubung)</span>
        </span>
        <span class="flex items-center gap-1.5 font-medium text-slate-700">
            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
            <span>Arsitektur: Clean PSR-4 MVC</span>
        </span>
        <span class="flex items-center gap-1.5 font-medium text-slate-700">
            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
            <span>Keamanan: CSRF & Session Guard Aktif</span>
        </span>
    </div>
    <div class="font-mono text-[11px] text-slate-400">
        Waktu Server: <?= date('d M Y - H:i') ?> WIB
    </div>
</div>

<script>
    // Injeksi payload data statistik ke Chart.js
    window.chartDataPayload = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
</script>