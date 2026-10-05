<!-- Dashboard Header -->
<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Dashboard Manajemen Cabang</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Selamat bekerja, <strong><?= e($user['nama_lengkap']) ?></strong> (<?= e(ucfirst($user['role'])) ?>).
        </p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/civitas/create" class="btn btn-primary btn-sm">➕ Tambah Anggota</a>
        <a href="/admin/berita/create" class="btn btn-secondary btn-sm">✍️ Tulis Berita</a>
    </div>
</div>

<!-- Metrics Cards -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Total Civitas</span>
            <span class="metric-value"><?= (int)($summary['total_civitas'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-primary">👥</div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Anggota Aktif</span>
            <span class="metric-value"><?= (int)($summary['active_civitas'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-success">🟢</div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Komisariat Kampus</span>
            <span class="metric-value"><?= (int)($summary['total_komisariat'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-secondary">🏛️</div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Warta & Berita</span>
            <span class="metric-value"><?= (int)($summary['total_berita'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-info">📰</div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-3 gap-6" style="margin-bottom: 2rem;">
    <div class="card" style="grid-column: span 2;">
        <div class="table-header">
            <div class="table-title">Persebaran Civitas per Komisariat</div>
            <a href="/admin/statistik" class="text-muted" style="font-size: 0.8125rem;">Lihat Detail &rarr;</a>
        </div>
        <div class="card-body">
            <canvas id="chartKomisariat" height="120"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="table-header">
            <div class="table-title">Rasio Gender</div>
        </div>
        <div class="card-body flex justify-center items-center" style="min-height: 250px;">
            <div style="width: 100%; max-width: 220px;">
                <canvas id="chartGender"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity / Audit Log Table -->
<div class="table-card">
    <div class="table-header">
        <div class="table-title">Aktivitas Terakhir Sistem (Jejak Audit)</div>
        <a href="/admin/keamanan/audit-log" class="btn btn-outline btn-sm">Semua Jejak Audit &rarr;</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Objek / Modul</th>
                    <th>Alamat IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentLogs)): ?>
                    <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td class="text-muted"><?= format_date($log['created_at'], 'd M Y H:i') ?></td>
                            <td><strong><?= e($log['username']) ?></strong> <span class="badge badge-neutral"><?= e($log['role']) ?></span></td>
                            <td><span class="badge badge-primary"><?= e($log['action']) ?></span></td>
                            <td><?= e($log['entity']) ?> <?= !empty($log['entity_id']) ? '#' . e($log['entity_id']) : '' ?></td>
                            <td><code><?= e($log['ip_address']) ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding: 2rem;">Belum ada catatan aktivitas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Injeksi payload data statistik ke JavaScript
    window.chartDataPayload = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
</script>
