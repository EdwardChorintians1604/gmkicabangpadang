<!-- Dashboard Header -->
<div class="flex justify-between items-center mb-8 flex-wrap gap-4 animate__animated animate__fadeInDown">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin: 0 0 0.35rem 0;">Dashboard Kendali Administrator</h1>
        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
            Selamat bertugas, <strong><?= e($user['nama_lengkap']) ?></strong> (<?= e(ucfirst($user['role'])) ?>). Ut Omnes Unum Sint!
        </p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/civitas/create" class="btn btn-primary btn-sm flex items-center gap-1 shadow-sm hover:shadow transition-all">
            <?= svg_icon('plus', 16) ?>
            <span>Tambah Anggota</span>
        </a>
        <a href="/admin/berita/create" class="btn btn-secondary btn-sm flex items-center gap-1 shadow-sm hover:shadow transition-all">
            <?= svg_icon('edit', 16) ?>
            <span>Tulis Berita</span>
        </a>
    </div>
</div>

<!-- Metrics Cards using Reusable Partial -->
<div class="metrics-grid mb-8 animate__animated animate__fadeInUp">
    <?= partial('partials.stat-card', [
        'title' => 'Total Civitas',
        'value' => (int)($summary['total_civitas'] ?? 0),
        'icon' => 'users',
        'color' => 'primary',
        'subtitle' => 'Terdaftar dalam basis data'
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
        'subtitle' => 'Perguruan Tinggi di Kota Padang'
    ]) ?>

    <?= partial('partials.stat-card', [
        'title' => 'Warta & Berita',
        'value' => (int)($summary['total_berita'] ?? 0),
        'icon' => 'news',
        'color' => 'info',
        'subtitle' => 'Publikasi kegiatan & warta'
    ]) ?>
</div>

<!-- Charts Row with Offline Chart.js -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 animate__animated animate__fadeIn">
    <div class="lg:col-span-2">
        <?= partial('partials.chart-card', [
            'id' => 'chartKomisariat',
            'title' => 'Persebaran Civitas per Komisariat',
            'subtitle' => 'Distribusi kader aktif pada tiap komisariat kampus se-Kota Padang',
            'badge' => 'Komisariat',
            'height' => 320
        ]) ?>
    </div>

    <div class="col-span-1">
        <?= partial('partials.chart-card', [
            'id' => 'chartGender',
            'title' => 'Rasio Gender Civitas',
            'subtitle' => 'Perbandingan kader Putra & Putri',
            'badge' => 'Gender',
            'height' => 320
        ]) ?>
    </div>
</div>

<!-- Recent Activity / Audit Log Table -->
<div class="card table-card rounded-2xl shadow-sm border border-slate-200/80 animate__animated animate__fadeIn">
    <div class="table-header flex items-center justify-between" style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);">
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin: 0;">Jejak Audit Aktivitas Terakhir</h3>
            <p class="text-muted" style="margin: 0; font-size: 0.8rem;">Pencatatan otomatis seluruh interaksi dan modifikasi data</p>
        </div>
        <a href="/admin/keamanan/audit-log" class="btn btn-outline btn-sm flex items-center gap-1 rounded-xl">
            <span>Lihat Semua Jejak</span>
            <?= svg_icon('chevron-right', 14) ?>
        </a>
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
                        <td colspan="5" class="text-center text-muted" style="padding: 2.5rem;">Belum ada catatan aktivitas sistem.</td>
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
