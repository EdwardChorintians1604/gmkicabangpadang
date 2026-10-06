<!-- Executive Header -->
<div class="executive-dashboard-header animate__animated animate__fadeInDown">
    <div>
        <span class="executive-dashboard-eyebrow">Pusat pemantauan</span>
        <h1 class="executive-dashboard-title">Panel Pemantauan Eksekutif Cabang</h1>
        <p class="executive-dashboard-description">
            Akses pemantauan pimpinan BPC (KETCAB, SEKCAB, BENCAB) & Majelis Pertimbangan / Pengawas Cabang (MPPC).
        </p>
    </div>
    <div class="executive-dashboard-actions">
        <a href="/pengawas/civitas/export" class="btn btn-secondary btn-sm flex items-center justify-center gap-1 shadow-sm hover:shadow transition-all w-full sm:w-auto">
            <?= svg_icon('download', 16) ?>
            <span>Ekspor Laporan Civitas</span>
        </a>
        <a href="/pengawas/statistik" class="btn btn-outline btn-sm flex items-center justify-center gap-1 shadow-sm hover:shadow transition-all w-full sm:w-auto">
            <?= svg_icon('chart', 16) ?>
            <span>Analisis Grafik</span>
        </a>
    </div>
</div>

<!-- Executive Metric Cards -->
<div class="metrics-grid mb-8 animate__animated animate__fadeInUp">
    <?= partial('partials.stat-card', [
        'title' => 'Total Civitas Terdata',
        'value' => (int)($summary['total_civitas'] ?? 0),
        'icon' => 'users',
        'color' => 'primary',
        'subtitle' => 'Kader & Anggota terdaftar'
    ]) ?>

    <?= partial('partials.stat-card', [
        'title' => 'Anggota Aktif',
        'value' => (int)($summary['active_civitas'] ?? 0),
        'icon' => 'check',
        'color' => 'success',
        'subtitle' => 'Status keanggotaan aktif'
    ]) ?>

    <?= partial('partials.stat-card', [
        'title' => 'Komisariat Kampus',
        'value' => (int)($summary['total_komisariat'] ?? 0),
        'icon' => 'organization',
        'color' => 'secondary',
        'subtitle' => 'Perguruan Tinggi se-Padang'
    ]) ?>

    <?= partial('partials.stat-card', [
        'title' => 'Warta Terpublikasi',
        'value' => (int)($summary['total_berita'] ?? 0),
        'icon' => 'news',
        'color' => 'info',
        'subtitle' => 'Kabar kegiatan & pelayanan'
    ]) ?>
</div>

<!-- Executive Roles Tab Navigation -->
<div class="card rounded-2xl shadow-sm border border-slate-200/80 mb-8 overflow-hidden animate__animated animate__fadeIn">
    <nav class="executive-tabs" aria-label="Bagian pemantauan eksekutif">
        <a href="?tab=ketcab" class="executive-tab-btn <?= ($activeTab === 'ketcab') ? 'active' : '' ?>" <?= ($activeTab === 'ketcab') ? 'aria-current="page"' : '' ?>>
            <span class="executive-tab-icon" aria-hidden="true">🏛️</span>
            <span class="executive-tab-copy">
                <strong>KETUA CABANG</strong>
                <small>Arah strategis & BPC</small>
            </span>
        </a>
        <a href="?tab=sekcab" class="executive-tab-btn <?= ($activeTab === 'sekcab') ? 'active' : '' ?>" <?= ($activeTab === 'sekcab') ? 'aria-current="page"' : '' ?>>
            <span class="executive-tab-icon" aria-hidden="true">📋</span>
            <span class="executive-tab-copy">
                <strong>SEKRETARIS CABANG</strong>
                <small>Kaderisasi & administrasi</small>
            </span>
        </a>
        <a href="?tab=bencab" class="executive-tab-btn <?= ($activeTab === 'bencab') ? 'active' : '' ?>" <?= ($activeTab === 'bencab') ? 'aria-current="page"' : '' ?>>
            <span class="executive-tab-icon" aria-hidden="true">💼</span>
            <span class="executive-tab-copy">
                <strong>BENDAHARA CABANG</strong>
                <small>Logistik & aset digital</small>
            </span>
        </a>
    </nav>

    <div class="card-body p-4 sm:p-6">
        <?php
        if ($activeTab === 'sekcab') {
            require __DIR__ . '/_sekcab.php';
        } elseif ($activeTab === 'bencab') {
            require __DIR__ . '/_bencab.php';
        } else {
            require __DIR__ . '/_ketcab.php';
        }
        ?>
    </div>
</div>

<!-- Charts Row with Chart.js -->
<div class="executive-dashboard-charts animate__animated animate__fadeIn">
    <div>
        <?= partial('partials.chart-card', [
            'id' => 'chartKomisariat',
            'title' => 'Pantauan Persebaran Kader per Komisariat Kampus',
            'subtitle' => 'Data riil keanggotaan aktif GMKI di tiap universitas',
            'badge' => 'Komisariat',
            'height' => 320
        ]) ?>
    </div>

    <div>
        <?= partial('partials.chart-card', [
            'id' => 'chartGender',
            'title' => 'Komposisi Gender Kader',
            'subtitle' => 'Keseimbangan gender civitas GMKI Padang',
            'badge' => 'Demografi',
            'height' => 320
        ]) ?>
    </div>
</div>

<!-- Audit Activity Table (Monitoring) -->
<div class="card table-card rounded-2xl shadow-sm border border-slate-200/80 executive-dashboard-audit animate__animated animate__fadeIn">
    <div class="table-header executive-dashboard-audit-header">
        <div>
            <h3 class="executive-dashboard-audit-title">Pengawasan Jejak Audit Terakhir</h3>
            <p class="executive-dashboard-audit-description">Pantauan transparansi dan integritas aktivitas pengguna sistem</p>
        </div>
        <a href="/pengawas/pemantauan/audit-log" class="btn btn-outline btn-sm flex items-center justify-center gap-1 rounded-xl w-full sm:w-auto">
            <span>Seluruh Catatan Audit</span>
            <?= svg_icon('chevron-right', 14) ?>
        </a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu Kejadian</th>
                    <th>Nama Pengguna</th>
                    <th>Tindakan / Aksi</th>
                    <th>Modul Terkait</th>
                    <th>Alamat IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentLogs)): ?>
                    <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td class="text-muted"><?= format_date($log['created_at'], 'd M Y H:i') ?></td>
                            <td>
                                <strong><?= e($log['username']) ?></strong>
                                <span class="badge badge-neutral"><?= e($log['role']) ?></span>
                            </td>
                            <td><span class="badge badge-primary"><?= e($log['action']) ?></span></td>
                            <td><?= e($log['entity']) ?> <?= !empty($log['entity_id']) ? '#' . e($log['entity_id']) : '' ?></td>
                            <td><code><?= e($log['ip_address']) ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding: 2.5rem;">Belum ada catatan aktivitas pengurus.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Injeksi payload data statistik ke Chart.js
    window.chartDataPayload = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
</script>
