<!-- Dashboard Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 sm:mb-8 gap-4 animate__animated animate__fadeInDown">
    <div>
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#0f3d64] m-0 mb-1 leading-tight">Dashboard Kendali Administrator</h1>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Selamat bertugas, <strong><?= e($user['nama_lengkap']) ?></strong> (<?= e(ucfirst($user['role'])) ?>). Ut Omnes Unum Sint!
        </p>
    </div>
    <div class="flex flex-wrap sm:flex-nowrap gap-2 w-full sm:w-auto">
        <a href="/admin/civitas/create" class="btn btn-primary btn-sm flex-1 sm:flex-none justify-center flex items-center gap-1.5 shadow-sm hover:shadow transition-all text-xs sm:text-sm py-2 px-3">
            <?= svg_icon('plus', 16) ?>
            <span>Tambah Anggota</span>
        </a>
        <a href="/admin/berita/create" class="btn btn-secondary btn-sm flex-1 sm:flex-none justify-center flex items-center gap-1.5 shadow-sm hover:shadow transition-all text-xs sm:text-sm py-2 px-3">
            <?= svg_icon('edit', 16) ?>
            <span>Tulis Berita</span>
        </a>
    </div>
</div>

<!-- Metrics Cards using Reusable Partial -->
<div class="metrics-grid mb-6 sm:mb-8 animate__animated animate__fadeInUp">
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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 sm:mb-8 animate__animated animate__fadeIn">
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
<div class="card table-card rounded-2xl shadow-sm border border-slate-200/80 animate__animated animate__fadeIn overflow-hidden">
    <div class="table-header flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-5 gap-3 border-b border-slate-200/80">
        <div>
            <h3 class="text-base sm:text-lg font-bold text-[#0f3d64] m-0">Jejak Audit Aktivitas Terakhir</h3>
            <p class="text-slate-500 text-xs sm:text-sm m-0">Pencatatan otomatis seluruh interaksi dan modifikasi data</p>
        </div>
        <a href="/admin/keamanan/audit-log" class="btn btn-outline btn-sm flex items-center justify-center gap-1 rounded-xl w-full sm:w-auto text-xs sm:text-sm">
            <span>Lihat Semua Jejak</span>
            <?= svg_icon('chevron-right', 14) ?>
        </a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Waktu</th>
                    <th class="whitespace-nowrap">Pengguna</th>
                    <th class="whitespace-nowrap">Aksi</th>
                    <th class="whitespace-nowrap">Objek / Modul</th>
                    <th class="whitespace-nowrap">Alamat IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentLogs)): ?>
                    <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td class="text-muted whitespace-nowrap text-xs sm:text-sm"><?= format_date($log['created_at'], 'd M Y H:i') ?></td>
                            <td class="whitespace-nowrap text-xs sm:text-sm">
                                <strong><?= e($log['username']) ?></strong> 
                                <span class="badge badge-neutral text-xs"><?= e($log['role']) ?></span>
                            </td>
                            <td class="whitespace-nowrap"><span class="badge badge-primary text-xs"><?= e($log['action']) ?></span></td>
                            <td class="whitespace-nowrap text-xs sm:text-sm"><?= e($log['entity']) ?> <?= !empty($log['entity_id']) ? '#' . e($log['entity_id']) : '' ?></td>
                            <td class="whitespace-nowrap"><code class="text-xs bg-slate-100 px-1.5 py-0.5 rounded text-slate-700"><?= e($log['ip_address']) ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted p-8 text-xs sm:text-sm">Belum ada catatan aktivitas sistem.</td>
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
