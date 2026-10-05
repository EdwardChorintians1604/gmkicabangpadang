<!-- Pengawas Header -->
<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Panel Majelis Pengawas Cabang</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Pemantauan data, statistik kaderisasi, dan pengawasan operasional BPC GMKI Cabang Padang.
        </p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/civitas/export" class="btn btn-outline btn-sm">📥 Ekspor Data Civitas</a>
        <a href="/admin/keamanan/backup" class="btn btn-primary btn-sm">💾 Status Cadangan</a>
    </div>
</div>

<!-- Metrics Cards -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Total Civitas Terdata</span>
            <span class="metric-value"><?= (int)($summary['total_civitas'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-primary">👥</div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Kader Aktif Berproses</span>
            <span class="metric-value"><?= (int)($summary['active_civitas'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-success">🟢</div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Senior / Alumni</span>
            <span class="metric-value"><?= (int)($summary['alumni_civitas'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-secondary">🎓</div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Komisariat Berdiri</span>
            <span class="metric-value"><?= (int)($summary['total_komisariat'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-info">🏛️</div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-2 gap-6" style="margin-bottom: 2rem;">
    <div class="card">
        <div class="table-header">
            <div class="table-title">Sebaran Perguruan Tinggi</div>
        </div>
        <div class="card-body">
            <canvas id="chartPT" height="150"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="table-header">
            <div class="table-title">Pertumbuhan Kader per Tahun Maperca</div>
        </div>
        <div class="card-body">
            <canvas id="chartMaperca" height="150"></canvas>
        </div>
    </div>
</div>

<!-- Pengawasan Audit Log -->
<div class="table-card">
    <div class="table-header">
        <div class="table-title">Pengawasan Aktivitas BPC (Jejak Audit Terbaru)</div>
        <a href="/admin/keamanan/audit-log" class="btn btn-outline btn-sm">Buka Log Lengkap &rarr;</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pelaksana</th>
                    <th>Peran</th>
                    <th>Tindakan / Aksi</th>
                    <th>Modul Terkait</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentLogs)): ?>
                    <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td class="text-muted"><?= format_date($log['created_at'], 'd M Y H:i:s') ?></td>
                            <td><strong><?= e($log['username']) ?></strong></td>
                            <td><span class="badge badge-neutral"><?= e($log['role']) ?></span></td>
                            <td><span class="badge badge-primary"><?= e($log['action']) ?></span></td>
                            <td><?= e($log['entity']) ?> <?= !empty($log['entity_id']) ? '#' . e($log['entity_id']) : '' ?></td>
                            <td><code><?= e($log['ip_address']) ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 2rem;">Belum ada aktivitas tercatat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    window.chartDataPayload = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
</script>
