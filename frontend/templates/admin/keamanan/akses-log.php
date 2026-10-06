<!-- Header Halaman Audit -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4 animate__animated animate__fadeInDown">
    <div>
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#0f3d64] m-0 mb-1 leading-tight">Audit Akses & Perangkat Pengunjung</h1>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Pemantauan langsung siapa saja, dari IP mana, dan menggunakan perangkat apa saja yang baru mengakses website GMKI Cabang Padang.
        </p>
    </div>
</div>

<!-- Tab Navigasi Mode Audit -->
<div class="flex items-center gap-2 mb-6 border-b border-slate-200 overflow-x-auto pb-1">
    <a href="/admin/keamanan/audit-log" class="px-4 py-2.5 font-bold text-sm text-slate-500 hover:text-primary transition-colors flex items-center gap-2 border-b-2 border-transparent">
        <?= svg_icon('shield', 16) ?>
        <span>Jejak Audit Aktivitas Data</span>
    </a>
    <a href="/admin/keamanan/akses-log" class="px-4 py-2.5 font-bold text-sm text-primary border-b-2 border-primary transition-colors flex items-center gap-2">
        <?= svg_icon('dashboard', 16) ?>
        <span>Audit Akses & Perangkat Pengunjung</span>
        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-bold">Baru</span>
    </a>
</div>

<!-- Kartu Ringkasan Statistik Kunjungan Hari Ini -->
<div class="metrics-grid mb-6 animate__animated animate__fadeInUp">
    <div class="stat-card stat-card-primary">
        <div class="stat-card-header">
            <span class="stat-card-title">Kunjungan Hari Ini</span>
            <div class="stat-card-icon icon-primary">
                <?= svg_icon('news', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summary['today_visits'] ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub">Total hit & permintaan halaman</span>
        </div>
    </div>

    <div class="stat-card stat-card-success">
        <div class="stat-card-header">
            <span class="stat-card-title">Pengunjung Unik</span>
            <div class="stat-card-icon icon-success">
                <?= svg_icon('users', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summary['unique_ip_today'] ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub">Berdasarkan Alamat IP berbeda</span>
        </div>
    </div>

    <div class="stat-card stat-card-secondary">
        <div class="stat-card-header">
            <span class="stat-card-title">Pengguna Login</span>
            <div class="stat-card-icon icon-secondary">
                <?= svg_icon('user', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summary['user_logged_in'] ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub">Admin / Fungsionaris terotentikasi</span>
        </div>
    </div>

    <div class="stat-card stat-card-info">
        <div class="stat-card-header">
            <span class="stat-card-title">Tamu Publik</span>
            <div class="stat-card-icon icon-info">
                <?= svg_icon('organization', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summary['guest_visitors'] ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub">Masyarakat & Pengunjung umum</span>
        </div>
    </div>
</div>

<!-- Filter Pencarian -->
<div class="card mb-6 p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm animate__animated animate__fadeIn">
    <form action="/admin/keamanan/akses-log" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">Cari IP Address</label>
            <input type="text" name="ip" class="form-control text-sm" placeholder="Contoh: 127.0.0.1..." value="<?= e($searchIp ?? '') ?>">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">Cari Pengguna</label>
            <input type="text" name="username" class="form-control text-sm" placeholder="Nama / Tamu Publik..." value="<?= e($searchUsername ?? '') ?>">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">Tipe Perangkat</label>
            <select name="device_type" class="form-control text-sm">
                <option value="">Semua Perangkat</option>
                <option value="Desktop / Laptop" <?= ($selectedDevice === 'Desktop / Laptop') ? 'selected' : '' ?>>💻 Desktop / Laptop</option>
                <option value="Smartphone (Mobile)" <?= ($selectedDevice === 'Smartphone (Mobile)') ? 'selected' : '' ?>>📱 Smartphone (Mobile)</option>
                <option value="Tablet" <?= ($selectedDevice === 'Tablet') ? 'selected' : '' ?>>📟 Tablet</option>
                <option value="Bot / Crawler" <?= ($selectedDevice === 'Bot / Crawler') ? 'selected' : '' ?>>🤖 Bot / Mesin Pencari</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">URL / Path Halaman</label>
            <input type="text" name="path" class="form-control text-sm" placeholder="Contoh: /berita..." value="<?= e($searchPath ?? '') ?>">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="btn btn-primary flex-1 py-2 text-sm justify-center">Filter Akses</button>
            <?php if (!empty($searchIp) || !empty($searchUsername) || !empty($selectedDevice) || !empty($searchPath)): ?>
                <a href="/admin/keamanan/akses-log" class="btn btn-outline py-2 text-sm px-3" title="Reset Filter">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tabel Daftar Akses Pengunjung -->
<div class="card table-card rounded-2xl shadow-sm border border-slate-200/80 animate__animated animate__fadeIn overflow-hidden">
    <div class="table-header flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-5 gap-3 border-b border-slate-200/80">
        <div>
            <h3 class="text-base sm:text-lg font-bold text-[#0f3d64] m-0">Riwayat Akses Terbaru Website</h3>
            <p class="text-slate-500 text-xs sm:text-sm m-0">Menampilkan <?= count($logs) ?> dari total <?= number_format($total) ?> catatan akses</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Waktu Akses</th>
                    <th class="whitespace-nowrap">Pengguna</th>
                    <th class="whitespace-nowrap">Alamat IP</th>
                    <th class="whitespace-nowrap">Perangkat & OS</th>
                    <th class="whitespace-nowrap">Browser</th>
                    <th class="whitespace-nowrap">Halaman / URL</th>
                    <th class="whitespace-nowrap">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-slate-500 whitespace-nowrap text-xs">
                                <strong><?= format_date($log['created_at'], 'd M Y') ?></strong><br>
                                <span class="text-slate-400 font-mono"><?= format_date($log['created_at'], 'H:i:s') ?> WIB</span>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <div class="flex items-center gap-1.5">
                                    <?php if ($log['role'] === 'guest'): ?>
                                        <span class="inline-block w-2 h-2 rounded-full bg-slate-300"></span>
                                        <span class="text-slate-600 font-medium">Tamu Publik</span>
                                    <?php else: ?>
                                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <strong><?= e($log['username']) ?></strong>
                                        <span class="badge badge-primary text-[10px] px-1.5 py-0.2"><?= e(strtoupper($log['role'])) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="whitespace-nowrap">
                                <code class="text-xs bg-slate-100 text-slate-800 px-2 py-1 rounded font-mono font-bold border border-slate-200">
                                    <?= e($log['ip_address']) ?>
                                </code>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <div class="flex items-center gap-1.5">
                                    <?php if (str_contains($log['device_type'], 'Smartphone')): ?>
                                        <span class="text-amber-600 font-bold" title="Smartphone">📱 HP</span>
                                    <?php elseif (str_contains($log['device_type'], 'Tablet')): ?>
                                        <span class="text-indigo-600 font-bold" title="Tablet">📟 Tab</span>
                                    <?php else: ?>
                                        <span class="text-blue-600 font-bold" title="Desktop / Laptop">💻 PC</span>
                                    <?php endif; ?>
                                    <span class="text-slate-700 font-medium"><?= e($log['platform']) ?></span>
                                </div>
                            </td>

                            <td class="whitespace-nowrap text-xs text-slate-600">
                                <span class="inline-flex items-center gap-1">
                                    🌐 <?= e($log['browser']) ?>
                                </span>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <div class="flex items-center gap-1.5">
                                    <span class="badge text-[10px] font-bold <?= $log['method'] === 'POST' ? 'badge-warning' : 'badge-neutral' ?>">
                                        <?= e($log['method']) ?>
                                    </span>
                                    <span class="font-mono text-slate-700 text-xs" title="<?= e($log['path']) ?>">
                                        <?= e($log['path']) ?>
                                    </span>
                                </div>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <?php if ((int)$log['status_code'] >= 200 && (int)$log['status_code'] < 300): ?>
                                    <span class="badge badge-success text-[10px]"><?= e($log['status_code']) ?> OK</span>
                                <?php elseif ((int)$log['status_code'] >= 300 && (int)$log['status_code'] < 400): ?>
                                    <span class="badge badge-info text-[10px]"><?= e($log['status_code']) ?> REDIRECT</span>
                                <?php else: ?>
                                    <span class="badge badge-danger text-[10px]"><?= e($log['status_code']) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-slate-400 py-12 text-sm">
                            Belum ada riwayat akses website yang sesuai dengan kriteria filter.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginasi -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination flex items-center justify-between p-4 border-t border-slate-200/80 text-xs">
            <span class="text-slate-500">
                Halaman <?= $currentPage ?> dari <?= $totalPages ?> (Total <?= number_format($total) ?> catatan)
            </span>
            <div class="pagination-pages flex gap-1">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?= $currentPage - 1 ?>&ip=<?= urlencode($searchIp ?? '') ?>&username=<?= urlencode($searchUsername ?? '') ?>&device_type=<?= urlencode($selectedDevice ?? '') ?>&path=<?= urlencode($searchPath ?? '') ?>" class="page-link">&laquo;</a>
                <?php endif; ?>

                <?php for ($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++): ?>
                    <a href="?page=<?= $p ?>&ip=<?= urlencode($searchIp ?? '') ?>&username=<?= urlencode($searchUsername ?? '') ?>&device_type=<?= urlencode($selectedDevice ?? '') ?>&path=<?= urlencode($searchPath ?? '') ?>" class="page-link <?= ($p === $currentPage) ? 'active' : '' ?>">
                        <?= $p ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?= $currentPage + 1 ?>&ip=<?= urlencode($searchIp ?? '') ?>&username=<?= urlencode($searchUsername ?? '') ?>&device_type=<?= urlencode($selectedDevice ?? '') ?>&path=<?= urlencode($searchPath ?? '') ?>" class="page-link">&raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
