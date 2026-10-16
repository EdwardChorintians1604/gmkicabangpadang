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
                Selamat bertugas, <strong
                    class="text-white font-bold underline decoration-amber-400 decoration-2 underline-offset-4"><?= e($user['nama_lengkap']) ?></strong>.
                Sistem pangkalan data keanggotaan dan portal informasi terpadu BPC GMKI Cabang Padang.
                <span class="text-amber-300 font-semibold italic">Ut Omnes Unum Sint!</span>
            </p>
        </div>
        <div class="admin-hero-actions">
            <a href="/admin/civitas/create" class="btn-hero-primary">
                <?= svg_icon('plus', 18) ?>
                <span>Tambah Anggota</span>
            </a>
            <a href="/admin/berita/create" class="btn-hero-glass">
                <?= svg_icon('edit', 18) ?>
                <span>Tulis Warta</span>
            </a>
            <a href="/admin/koordinasi" class="btn-hero-glass">
                <?= svg_icon('users', 18) ?>
                <span>Chat Koordinasi</span>
            </a>
        </div>
    </div>
</div>

<!-- 2. Executive KPI Metrics Grid (5 Responsive Cards) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-5 mb-8 animate__animated animate__fadeInUp">
    <!-- Card 1: Total Civitas -->
    <div class="executive-metric-card metric-card-blue">
        <div class="metric-header">
            <div class="metric-title-group">
                <span class="metric-label-text">Total Civitas</span>
                <span class="metric-badge-pill metric-badge-blue">Basis Data Lengkap</span>
            </div>
            <div class="metric-icon-box icon-box-blue">
                <?= svg_icon('users', 22) ?>
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
                <?= svg_icon('check', 22) ?>
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
                <?= svg_icon('user', 22) ?>
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
                <?= svg_icon('organization', 22) ?>
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
                <?= svg_icon('news', 22) ?>
            </div>
        </div>
        <div class="metric-value-num text-rose-600"><?= number_format($totalBerita) ?></div>
        <div class="metric-sub-detail">
            <span class="text-rose-600 font-bold">📰</span>
            <span>Artikel & siaran pers tayang</span>
        </div>
    </div>
</div>

<!-- 3. Pipeline Kaderisasi (Jenjang Pembinaan Kader GMKI) -->
<div class="pipeline-widget mb-8 animate__animated animate__fadeIn">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
        <div>
            <h3 class="text-sm sm:text-base font-bold text-[#0f3d64] m-0 flex items-center gap-2">
                <span>Jenjang Pembinaan & Kaderisasi Organisasi</span>
                <span class="text-xs bg-slate-100 text-slate-600 font-semibold px-2 py-0.5 rounded-full">Siklus
                    Anggota</span>
            </h3>
            <p class="text-xs text-slate-500 m-0 mt-0.5">Distribusi berjenjang dari Maperca, Anggota Biasa, Kader
                Tingkat Lanjut, hingga Alumni</p>
        </div>
        <a href="/admin/statistik" class="text-xs font-bold text-sky-700 hover:text-sky-900 flex items-center gap-1">
            <span>Analisis Lengkap</span>
            <?= svg_icon('chevron-right', 14) ?>
        </a>
    </div>

    <!-- Segmented Visual Progress Bar -->
    <?php
    $sumKader = max(1, $mapercaCount + $anggotaCount + $kkCount + $alumniCount);
    $pctMaperca = round(($mapercaCount / $sumKader) * 100);
    $pctAnggota = round(($anggotaCount / $sumKader) * 100);
    $pctKK = round(($kkCount / $sumKader) * 100);
    $pctAlumni = 100 - ($pctMaperca + $pctAnggota + $pctKK);
    ?>
    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden flex mb-4 shadow-inner">
        <div style="width: <?= $pctMaperca ?>%;" class="bg-amber-400 transition-all duration-500"
            title="Maperca: <?= $mapercaCount ?>"></div>
        <div style="width: <?= $pctAnggota ?>%;" class="bg-sky-500 transition-all duration-500"
            title="Anggota: <?= $anggotaCount ?>"></div>
        <div style="width: <?= $pctKK ?>%;" class="bg-indigo-600 transition-all duration-500"
            title="Kader Konsultan: <?= $kkCount ?>"></div>
        <div style="width: <?= $pctAlumni ?>%;" class="bg-slate-400 transition-all duration-500"
            title="Alumni/Senior: <?= $alumniCount ?>"></div>
    </div>

    <!-- Step Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="pipeline-step border-l-4 border-l-amber-400">
            <div class="pipeline-step-title flex items-center justify-between">
                <span>1. Maperca</span>
                <span class="text-xs text-amber-600 font-bold"><?= $pctMaperca ?>%</span>
            </div>
            <div class="pipeline-step-val text-amber-600"><?= $mapercaCount ?> <span
                    class="text-xs font-normal text-slate-500">orang</span></div>
            <div class="text-[11px] text-slate-500 mt-1">Calon Kader Baru</div>
        </div>
        <div class="pipeline-step border-l-4 border-l-sky-500">
            <div class="pipeline-step-title flex items-center justify-between">
                <span>2. Anggota Biasa</span>
                <span class="text-xs text-sky-600 font-bold"><?= $pctAnggota ?>%</span>
            </div>
            <div class="pipeline-step-val text-sky-600"><?= $anggotaCount ?> <span
                    class="text-xs font-normal text-slate-500">orang</span></div>
            <div class="text-[11px] text-slate-500 mt-1">Telah Diteguhkan</div>
        </div>
        <div class="pipeline-step border-l-4 border-l-indigo-600">
            <div class="pipeline-step-title flex items-center justify-between">
                <span>3. KK / Pengurus</span>
                <span class="text-xs text-indigo-600 font-bold"><?= $pctKK ?>%</span>
            </div>
            <div class="pipeline-step-val text-indigo-600"><?= $kkCount ?> <span
                    class="text-xs font-normal text-slate-500">orang</span></div>
            <div class="text-[11px] text-slate-500 mt-1">Kader Konsultan</div>
        </div>
        <div class="pipeline-step border-l-4 border-l-slate-400">
            <div class="pipeline-step-title flex items-center justify-between">
                <span>4. Alumni / Senior</span>
                <span class="text-xs text-slate-500 font-bold"><?= $pctAlumni ?>%</span>
            </div>
            <div class="pipeline-step-val text-slate-700"><?= $alumniCount ?> <span
                    class="text-xs font-normal text-slate-500">orang</span></div>
            <div class="text-[11px] text-slate-500 mt-1">Senior Member</div>
        </div>
    </div>
</div>

<!-- 4. Pintasan Cepat Operasional (Quick Administrative Actions) -->
<div class="mb-8 animate__animated animate__fadeIn">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm sm:text-base font-bold text-[#0f3d64] m-0 flex items-center gap-2">
            <span>Pintasan Operasional Administrasi</span>
            <span class="text-xs bg-sky-50 text-sky-700 font-bold px-2 py-0.5 rounded-full border border-sky-200">Aksi
                Cepat</span>
        </h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        <a href="/admin/civitas" class="quick-action-card">
            <div class="quick-action-icon">
                <?= svg_icon('users', 20) ?>
            </div>
            <div class="min-w-0">
                <div class="quick-action-title truncate">Kelola Civitas</div>
                <div class="quick-action-sub truncate">Pangkalan data</div>
            </div>
        </a>

        <a href="/admin/maperca" class="quick-action-card">
            <div class="quick-action-icon">
                <?= svg_icon('user', 20) ?>
            </div>
            <div class="min-w-0">
                <div class="quick-action-title truncate">Kader Maperca</div>
                <div class="quick-action-sub truncate">Penerimaan kader</div>
            </div>
        </a>

        <a href="/admin/berita" class="quick-action-card">
            <div class="quick-action-icon">
                <?= svg_icon('news', 20) ?>
            </div>
            <div class="min-w-0">
                <div class="quick-action-title truncate">Warta & Berita</div>
                <div class="quick-action-sub truncate">Publikasi tulisan</div>
            </div>
        </a>

        <a href="/admin/civitas/impor" class="quick-action-card">
            <div class="quick-action-icon">
                <?= svg_icon('upload', 20) ?>
            </div>
            <div class="min-w-0">
                <div class="quick-action-title truncate">Impor Excel</div>
                <div class="quick-action-sub truncate">Migrasi massal</div>
            </div>
        </a>

        <a href="/admin/keamanan/audit-log" class="quick-action-card">
            <div class="quick-action-icon">
                <?= svg_icon('shield', 20) ?>
            </div>
            <div class="min-w-0">
                <div class="quick-action-title truncate">Jejak Audit</div>
                <div class="quick-action-sub truncate">Log keamanan</div>
            </div>
        </a>

        <a href="/admin/keamanan/backup" class="quick-action-card">
            <div class="quick-action-icon">
                <?= svg_icon('backup', 20) ?>
            </div>
            <div class="min-w-0">
                <div class="quick-action-title truncate">Cadangan Data</div>
                <div class="quick-action-sub truncate">Backup database</div>
            </div>
        </a>
    </div>
</div>

<!-- 5. Charts Row with Chart.js -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 animate__animated animate__fadeIn">
    <div class="lg:col-span-2">
        <div class="card chart-card rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div
                class="chart-card-header flex items-center justify-between p-4 sm:p-5 border-b border-slate-100 bg-white">
                <div>
                    <h3 class="chart-card-title text-base sm:text-lg font-bold text-[#0f3d64] m-0">Persebaran Civitas
                        per Komisariat</h3>
                    <p class="chart-card-subtitle text-xs sm:text-sm text-slate-500 m-0">Distribusi kader aktif pada
                        tiap basis kampus binaan di Kota Padang</p>
                </div>
                <span
                    class="badge badge-primary text-xs px-3 py-1 rounded-full bg-sky-50 text-sky-800 border border-sky-200 font-bold">
                    🏛️ Komisariat
                </span>
            </div>
            <div class="chart-card-body p-4 sm:p-6" style="position: relative; height: 320px; width: 100%;">
                <canvas id="chartKomisariat"></canvas>
            </div>
        </div>
    </div>

    <div class="col-span-1">
        <div class="card chart-card rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div
                class="chart-card-header flex items-center justify-between p-4 sm:p-5 border-b border-slate-100 bg-white">
                <div>
                    <h3 class="chart-card-title text-base sm:text-lg font-bold text-[#0f3d64] m-0">Rasio Gender Civitas
                    </h3>
                    <p class="chart-card-subtitle text-xs sm:text-sm text-slate-500 m-0">Keseimbangan kader Putra &
                        Putri</p>
                </div>
                <span
                    class="badge badge-primary text-xs px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 font-bold">
                    🚻 Gender
                </span>
            </div>
            <div class="chart-card-body p-4 sm:p-6" style="position: relative; height: 320px; width: 100%;">
                <canvas id="chartGender"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- 6. Recent Activity / Audit Log Table -->
<div
    class="card table-card rounded-2xl shadow-sm border border-slate-200/80 animate__animated animate__fadeIn overflow-hidden mb-6">
    <div
        class="table-header flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-5 gap-3 border-b border-slate-200/80 bg-white">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 flex-shrink-0">
                <?= svg_icon('shield', 20) ?>
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-bold text-[#0f3d64] m-0">Jejak Audit Aktivitas Terkini</h3>
                <p class="text-slate-500 text-xs sm:text-sm m-0">Pencatatan real-time seluruh interaksi, autentikasi,
                    dan modifikasi data</p>
            </div>
        </div>
        <a href="/admin/keamanan/audit-log"
            class="btn btn-outline btn-sm flex items-center justify-center gap-1.5 rounded-xl w-full sm:w-auto text-xs sm:text-sm px-4 py-2 hover:bg-slate-50">
            <span>Lihat Semua Jejak</span>
            <?= svg_icon('chevron-right', 14) ?>
        </a>
    </div>
    <div class="table-responsive">
        <table class="data-table w-full">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider">
                    <th class="py-3 px-4 font-bold text-left whitespace-nowrap">Waktu</th>
                    <th class="py-3 px-4 font-bold text-left whitespace-nowrap">Pengguna</th>
                    <th class="py-3 px-4 font-bold text-left whitespace-nowrap">Aksi</th>
                    <th class="py-3 px-4 font-bold text-left whitespace-nowrap">Objek / Modul</th>
                    <th class="py-3 px-4 font-bold text-left whitespace-nowrap">Alamat IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
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
                        ?>
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap text-xs sm:text-sm">
                                <div class="font-medium text-slate-700"><?= format_date($log['created_at'], 'd M Y') ?></div>
                                <div class="text-[11px] text-slate-400"><?= date('H:i:s', strtotime($log['created_at'])) ?> WIB
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-xs sm:text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="user-avatar-badge"><?= $initial ?></div>
                                    <div>
                                        <div class="font-bold text-slate-800"><?= e($log['username']) ?></div>
                                        <span class="text-[11px] text-slate-500"><?= e($log['role']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="<?= $badgeClass ?>"><?= e($log['action']) ?></span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-xs sm:text-sm">
                                <span class="font-medium text-slate-800"><?= e($log['entity']) ?></span>
                                <?php if (!empty($log['entity_id'])): ?>
                                    <code
                                        class="text-[11px] bg-slate-100 text-slate-600 px-1 py-0.5 rounded">#<?= e($log['entity_id']) ?></code>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <code
                                    class="text-xs bg-slate-100/80 px-2 py-0.5 rounded-md text-slate-600 font-mono border border-slate-200"><?= e($log['ip_address']) ?></code>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-slate-400 p-8 text-xs sm:text-sm">Belum ada catatan
                            aktivitas sistem.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 7. System Diagnostics / Telemetry Strip -->
<div
    class="flex flex-wrap items-center justify-between gap-3 p-3.5 px-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-500 animate__animated animate__fadeIn">
    <div class="flex flex-wrap items-center gap-4">
        <span class="flex items-center gap-1.5 font-medium text-slate-700">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
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