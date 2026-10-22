<?php
/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: frontend/templates/admin/keamanan/akses-log.php
 * Deskripsi: Audit Akses & Perangkat Pengunjung Web (Forensik & Telemetri)
 * Lapisan: View (Admin)
 * =====================================================================
 */

// Helper URL query builder untuk ekspor dan paginasi
$currentFilters = [
    'ip' => $searchIp ?? '',
    'username' => $searchUsername ?? '',
    'device_type' => $selectedDevice ?? '',
    'path' => $searchPath ?? '',
    'status_code' => $selectedStatus ?? '',
];
$filterQueryString = http_build_query(array_filter($currentFilters));
$exportUrl = '/admin/keamanan/akses-log/export' . ($filterQueryString ? '?' . $filterQueryString : '');

// Hitung persentase respon sehat
$totalHitsForHealth = ($summary['success_hits'] ?? 0) + ($summary['error_hits'] ?? 0);
$successRate = $totalHitsForHealth > 0 ? round((($summary['success_hits'] ?? 0) / $totalHitsForHealth) * 100, 1) : 100;
?>

<!-- Header Halaman Audit -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4 animate__animated animate__fadeInDown">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="badge badge-primary text-xs px-2.5 py-0.5 font-bold uppercase tracking-wider">Telemetri & Forensik</span>
            <span class="text-xs text-slate-400 font-medium">Realtime Web Traffic</span>
        </div>
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#0f3d64] m-0 mb-1 leading-tight">
            Audit Akses & Perangkat Pengunjung
        </h1>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Pemantauan langsung siapa saja, dari IP mana, menggunakan perangkat apa, dan halaman apa yang diakses pada sistem GMKI Cabang Padang.
        </p>
    </div>
    <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
        <a href="<?= e($exportUrl) ?>" class="btn btn-outline flex items-center gap-2 text-xs sm:text-sm py-2 px-3.5 shadow-sm hover:border-emerald-600 hover:text-emerald-700 transition-all bg-white" title="Unduh data log akses sesuai filter aktif ke CSV">
            <?= svg_icon('download', 16, 'text-emerald-600') ?>
            <span>Ekspor CSV</span>
        </a>
        <a href="/admin/keamanan/akses-log" class="btn btn-primary flex items-center gap-2 text-xs sm:text-sm py-2 px-3.5 shadow-sm" title="Muat ulang data terkini">
            <?= svg_icon('refresh', 16) ?>
            <span>Segarkan</span>
        </a>
    </div>
</div>

<!-- Tab Navigasi Mode Audit -->
<div class="flex items-center gap-2 mb-6 border-b border-slate-200 overflow-x-auto pb-1 text-sm font-semibold">
    <a href="/admin/keamanan/audit-log" class="px-4 py-2.5 text-slate-500 hover:text-primary transition-colors flex items-center gap-2 border-b-2 border-transparent whitespace-nowrap">
        <?= svg_icon('shield', 16) ?>
        <span>Jejak Audit Aktivitas Data</span>
    </a>
    <a href="/admin/keamanan/akses-log" class="px-4 py-2.5 text-primary border-b-2 border-primary transition-colors flex items-center gap-2 whitespace-nowrap">
        <?= svg_icon('dashboard', 16) ?>
        <span>Audit Akses & Perangkat Pengunjung</span>
        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-bold">Aktif</span>
    </a>
    <a href="/admin/keamanan/ancaman" class="px-4 py-2.5 text-slate-500 hover:text-primary transition-colors flex items-center gap-2 border-b-2 border-transparent whitespace-nowrap">
        <?= svg_icon('alert', 16) ?>
        <span>Deteksi Ancaman & Keamanan</span>
    </a>
</div>

<!-- Kartu Ringkasan Eksekutif KPI Kunjungan -->
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
            <span class="stat-card-sub text-slate-500">Dari total <strong><?= number_format($summary['all_time_total'] ?? 0) ?></strong> permintaan tercatat</span>
        </div>
    </div>

    <div class="stat-card stat-card-success">
        <div class="stat-card-header">
            <span class="stat-card-title">Pengunjung Unik (IP)</span>
            <div class="stat-card-icon icon-success">
                <?= svg_icon('users', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summary['unique_ip_today'] ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub text-slate-500">Entitas alamat IP berbeda hari ini</span>
        </div>
    </div>

    <div class="stat-card stat-card-secondary">
        <div class="stat-card-header">
            <span class="stat-card-title">Sesi Pengguna Login</span>
            <div class="stat-card-icon icon-secondary">
                <?= svg_icon('user', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summary['user_logged_in'] ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub text-slate-500">Banding <strong><?= number_format($summary['guest_visitors'] ?? 0) ?></strong> tamu publik hari ini</span>
        </div>
    </div>

    <div class="stat-card stat-card-info">
        <div class="stat-card-header">
            <span class="stat-card-title">Integritas Respons Web</span>
            <div class="stat-card-icon icon-info">
                <?= svg_icon('check', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= $successRate ?>%</div>
        <div class="stat-card-footer">
            <span class="stat-card-sub text-slate-500">
                <?= number_format($summary['success_hits'] ?? 0) ?> sukses 2xx vs <?= number_format($summary['error_hits'] ?? 0) ?> error
            </span>
        </div>
    </div>
</div>

<!-- Strip Analitik Mendalam Lalu Lintas & Perangkat -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Card 1: Distribusi Perangkat & Platform -->
    <div class="card p-4 rounded-2xl border border-slate-200/80 shadow-sm bg-white">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
            <h4 class="font-bold text-sm text-[#0f3d64] m-0 flex items-center gap-2">
                <?= svg_icon('sidebar', 16, 'text-primary') ?>
                <span>Sebaran Perangkat</span>
            </h4>
            <span class="text-[11px] text-slate-400 font-mono">Semua Waktu</span>
        </div>
        <div class="space-y-3">
            <?php 
            $allDevicesTotal = 0;
            foreach ($summary['device_breakdown'] as $d) { $allDevicesTotal += (int)$d['total']; }
            $allDevicesTotal = max(1, $allDevicesTotal);
            ?>
            <?php if (!empty($summary['device_breakdown'])): ?>
                <?php foreach ($summary['device_breakdown'] as $dev): ?>
                    <?php 
                        $pct = round(($dev['total'] / $allDevicesTotal) * 100, 1);
                        $iconDev = '💻';
                        $barColor = 'bg-blue-600';
                        if (str_contains($dev['device_type'], 'Smartphone')) { $iconDev = '📱'; $barColor = 'bg-amber-500'; }
                        elseif (str_contains($dev['device_type'], 'Tablet')) { $iconDev = '📟'; $barColor = 'bg-indigo-500'; }
                        elseif (str_contains($dev['device_type'], 'Bot')) { $iconDev = '🤖'; $barColor = 'bg-rose-500'; }
                    ?>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-slate-700 flex items-center gap-1">
                                <span><?= $iconDev ?></span>
                                <span><?= e($dev['device_type']) ?></span>
                            </span>
                            <span class="font-bold text-slate-900"><?= number_format($dev['total']) ?> <span class="text-slate-400 font-normal">(<?= $pct ?>%)</span></span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="<?= $barColor ?> h-2 rounded-full transition-all duration-500" style="width: <?= $pct ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-slate-400 text-xs py-2">Belum ada data perangkat tercatat.</p>
            <?php endif; ?>

            <!-- Top Platforms Mini Pills -->
            <div class="pt-2 border-t border-slate-100 flex flex-wrap gap-1.5 items-center">
                <span class="text-[11px] text-slate-400 font-bold mr-1">OS:</span>
                <?php foreach (($summary['platform_breakdown'] ?? []) as $plat): ?>
                    <span class="inline-flex items-center text-[11px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-medium border border-slate-200">
                        <?= e($plat['platform']) ?> <span class="ml-1 text-slate-400 font-mono text-[10px]">(<?= $plat['total'] ?>)</span>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Card 2: Top 5 Halaman / Endpoint Paling Sering Diakses -->
    <div class="card p-4 rounded-2xl border border-slate-200/80 shadow-sm bg-white">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
            <h4 class="font-bold text-sm text-[#0f3d64] m-0 flex items-center gap-2">
                <?= svg_icon('file', 16, 'text-emerald-600') ?>
                <span>Top 5 Endpoint Terpopuler</span>
            </h4>
            <span class="text-[11px] text-slate-400">Total Hit</span>
        </div>
        <div class="space-y-2">
            <?php if (!empty($summary['top_paths'])): ?>
                <?php $rank = 1; foreach ($summary['top_paths'] as $tp): ?>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 hover:bg-slate-100/80 transition-colors border border-slate-100 text-xs">
                        <div class="flex items-center gap-2 min-w-0 pr-2">
                            <span class="w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-extrabold <?= $rank === 1 ? 'bg-amber-100 text-amber-800' : ($rank === 2 ? 'bg-slate-200 text-slate-700' : 'bg-slate-100 text-slate-600') ?>">
                                <?= $rank++ ?>
                            </span>
                            <a href="/admin/keamanan/akses-log?path=<?= urlencode($tp['path']) ?>" class="font-mono text-slate-800 hover:text-primary truncate font-medium" title="Klik untuk filter halaman: <?= e($tp['path']) ?>">
                                <?= e($tp['path']) ?>
                            </a>
                        </div>
                        <span class="badge badge-primary text-[11px] px-2 py-0.5 font-bold whitespace-nowrap">
                            <?= number_format($tp['hits']) ?> hit
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-slate-400 text-xs py-2">Belum ada catatan endpoint populer.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Card 3: Kode Status HTTP & Browser -->
    <div class="card p-4 rounded-2xl border border-slate-200/80 shadow-sm bg-white">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
            <h4 class="font-bold text-sm text-[#0f3d64] m-0 flex items-center gap-2">
                <?= svg_icon('chart', 16, 'text-indigo-600') ?>
                <span>Status Respon & Browser</span>
            </h4>
            <span class="text-[11px] text-slate-400 font-mono">Distribusi</span>
        </div>
        <div>
            <div class="text-xs font-bold text-slate-500 mb-1.5">Distribusi Status HTTP:</div>
            <div class="grid grid-cols-2 gap-2 mb-3">
                <?php foreach (($summary['status_distribution'] ?? []) as $st): ?>
                    <?php 
                        $code = (int)$st['status_code'];
                        $badgeBg = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                        if ($code >= 300 && $code < 400) { $badgeBg = 'bg-blue-50 text-blue-800 border-blue-200'; }
                        elseif ($code >= 400 && $code < 500) { $badgeBg = 'bg-amber-50 text-amber-800 border-amber-200'; }
                        elseif ($code >= 500) { $badgeBg = 'bg-rose-50 text-rose-800 border-rose-200'; }
                    ?>
                    <a href="/admin/keamanan/akses-log?status_code=<?= $code ?>" class="flex items-center justify-between p-2 rounded-lg border text-xs <?= $badgeBg ?> hover:opacity-85 transition-opacity" title="Filter HTTP <?= $code ?>">
                        <span class="font-bold font-mono">HTTP <?= $code ?></span>
                        <span class="font-bold"><?= number_format($st['total']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Top Browsers -->
            <div class="pt-2 border-t border-slate-100">
                <div class="text-xs font-bold text-slate-500 mb-1.5">Top Peramban / Browser:</div>
                <div class="flex flex-wrap gap-1.5">
                    <?php foreach (($summary['browser_breakdown'] ?? []) as $br): ?>
                        <span class="inline-flex items-center gap-1 text-[11px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-medium border border-slate-200">
                            <span>🌐</span>
                            <span><?= e($br['browser']) ?></span>
                            <span class="text-slate-400 font-mono text-[10px]">(<?= $br['total'] ?>)</span>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Pencarian Fleksibel -->
<div class="card mb-6 p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm animate__animated animate__fadeIn bg-white">
    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
            <?= svg_icon('filter', 14, 'text-primary') ?>
            <span>Filter Forensik Log Akses</span>
        </span>
        <?php if (!empty($searchIp) || !empty($searchUsername) || !empty($selectedDevice) || !empty($searchPath) || !empty($selectedStatus)): ?>
            <a href="/admin/keamanan/akses-log" class="text-xs text-rose-600 hover:text-rose-800 font-bold flex items-center gap-1">
                <span>Reset Semua Filter</span>
            </a>
        <?php endif; ?>
    </div>

    <form action="/admin/keamanan/akses-log" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Alamat IP</label>
            <input type="text" name="ip" class="form-control text-xs sm:text-sm" placeholder="Contoh: 127.0.0.1..." value="<?= e($searchIp ?? '') ?>">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Pengguna / Akun</label>
            <input type="text" name="username" class="form-control text-xs sm:text-sm" placeholder="Nama / Tamu Publik..." value="<?= e($searchUsername ?? '') ?>">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Tipe Perangkat</label>
            <select name="device_type" class="form-control text-xs sm:text-sm">
                <option value="">Semua Perangkat</option>
                <option value="Desktop / Laptop" <?= ($selectedDevice === 'Desktop / Laptop') ? 'selected' : '' ?>>💻 Desktop / Laptop</option>
                <option value="Smartphone (Mobile)" <?= ($selectedDevice === 'Smartphone (Mobile)') ? 'selected' : '' ?>>📱 Smartphone (Mobile)</option>
                <option value="Tablet" <?= ($selectedDevice === 'Tablet') ? 'selected' : '' ?>>📟 Tablet</option>
                <option value="Bot / Crawler" <?= ($selectedDevice === 'Bot / Crawler') ? 'selected' : '' ?>>🤖 Bot / Crawler</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Status HTTP</label>
            <select name="status_code" class="form-control text-xs sm:text-sm">
                <option value="">Semua Kode Status</option>
                <option value="200" <?= ($selectedStatus === '200') ? 'selected' : '' ?>>200 OK (Berhasil)</option>
                <option value="301" <?= ($selectedStatus === '301') ? 'selected' : '' ?>>301 Moved Permanently</option>
                <option value="302" <?= ($selectedStatus === '302') ? 'selected' : '' ?>>302 Found (Redirect)</option>
                <option value="400" <?= ($selectedStatus === '400') ? 'selected' : '' ?>>400 Bad Request</option>
                <option value="401" <?= ($selectedStatus === '401') ? 'selected' : '' ?>>401 Unauthorized</option>
                <option value="403" <?= ($selectedStatus === '403') ? 'selected' : '' ?>>403 Forbidden</option>
                <option value="404" <?= ($selectedStatus === '404') ? 'selected' : '' ?>>404 Not Found</option>
                <option value="429" <?= ($selectedStatus === '429') ? 'selected' : '' ?>>429 Too Many Requests</option>
                <option value="500" <?= ($selectedStatus === '500') ? 'selected' : '' ?>>500 Internal Server Error</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">URL Path</label>
            <input type="text" name="path" class="form-control text-xs sm:text-sm" placeholder="Contoh: /berita..." value="<?= e($searchPath ?? '') ?>">
        </div>

        <div class="sm:col-span-2 lg:col-span-5 flex justify-end gap-2 pt-2 border-t border-slate-100">
            <?php if (!empty($searchIp) || !empty($searchUsername) || !empty($selectedDevice) || !empty($searchPath) || !empty($selectedStatus)): ?>
                <a href="/admin/keamanan/akses-log" class="btn btn-outline py-2 text-xs sm:text-sm px-4">Reset</a>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary py-2 text-xs sm:text-sm px-6 font-bold flex items-center gap-2">
                <?= svg_icon('search', 16) ?>
                <span>Terapkan Filter Akses</span>
            </button>
        </div>
    </form>
</div>

<!-- Tabel Daftar Akses Pengunjung -->
<div class="card table-card rounded-2xl shadow-sm border border-slate-200/80 animate__animated animate__fadeIn overflow-hidden bg-white mb-6">
    <div class="table-header flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-5 gap-3 border-b border-slate-200/80">
        <div>
            <h3 class="text-base sm:text-lg font-bold text-[#0f3d64] m-0 flex items-center gap-2">
                <span>Riwayat Akses & Kunjungan Real-time</span>
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse" title="Live Log stream aktif"></span>
            </h3>
            <p class="text-slate-500 text-xs sm:text-sm m-0">
                Menampilkan <?= count($logs) ?> entri dari total <?= number_format($total) ?> catatan akses sistem
            </p>
        </div>
        <div class="text-xs text-slate-400 font-mono">
            Urutan: Waktu Terbaru (DESC)
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Waktu Akses</th>
                    <th class="whitespace-nowrap">Identitas Pengguna</th>
                    <th class="whitespace-nowrap">Alamat IP</th>
                    <th class="whitespace-nowrap">Perangkat & OS</th>
                    <th class="whitespace-nowrap">Browser</th>
                    <th class="whitespace-nowrap">Metode & URL</th>
                    <th class="whitespace-nowrap text-center">Status</th>
                    <th class="whitespace-nowrap text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <?php 
                            // JSON payload untuk inspector modal
                            $jsonData = json_encode([
                                'id' => $log['id'],
                                'ip_address' => $log['ip_address'],
                                'username' => $log['username'] ?? 'Tamu Publik',
                                'role' => $log['role'] ?? 'guest',
                                'user_id' => $log['user_id'] ?? null,
                                'method' => $log['method'],
                                'path' => $log['path'],
                                'status_code' => $log['status_code'],
                                'device_type' => $log['device_type'],
                                'platform' => $log['platform'],
                                'browser' => $log['browser'],
                                'referer' => $log['referer'] ?? '-',
                                'user_agent' => $log['user_agent'] ?? '-',
                                'created_at' => $log['created_at'],
                                'created_at_fmt' => format_date($log['created_at'], 'd M Y, H:i:s') . ' WIB',
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="text-slate-500 whitespace-nowrap text-xs">
                                <strong class="text-slate-800"><?= format_date($log['created_at'], 'd M Y') ?></strong><br>
                                <span class="text-slate-400 font-mono text-[11px]"><?= format_date($log['created_at'], 'H:i:s') ?> WIB</span>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <div class="flex items-center gap-1.5">
                                    <?php if ($log['role'] === 'guest'): ?>
                                        <span class="inline-block w-2 h-2 rounded-full bg-slate-300"></span>
                                        <span class="text-slate-600 font-medium">Tamu Publik</span>
                                    <?php else: ?>
                                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <strong class="text-slate-900"><?= e($log['username']) ?></strong>
                                        <span class="badge badge-primary text-[10px] px-1.5 py-0.2"><?= e(strtoupper($log['role'])) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <code class="text-xs bg-slate-100 text-slate-800 px-2 py-0.5 rounded font-mono font-bold border border-slate-200">
                                        <?= e($log['ip_address']) ?>
                                    </code>
                                    <button type="button" class="text-slate-400 hover:text-slate-700 p-1" title="Salin IP" onclick="copyToClipboard('<?= e($log['ip_address']) ?>', this)">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    </button>
                                </div>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <div class="flex items-center gap-1.5">
                                    <?php if (str_contains($log['device_type'], 'Smartphone')): ?>
                                        <span class="text-amber-600 font-bold" title="Smartphone">📱 HP</span>
                                    <?php elseif (str_contains($log['device_type'], 'Tablet')): ?>
                                        <span class="text-indigo-600 font-bold" title="Tablet">📟 Tab</span>
                                    <?php elseif (str_contains($log['device_type'], 'Bot')): ?>
                                        <span class="text-rose-600 font-bold" title="Bot">🤖 Bot</span>
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

                            <td class="whitespace-nowrap text-xs max-w-[280px]">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="badge text-[10px] font-bold <?= $log['method'] === 'POST' ? 'badge-warning' : 'badge-neutral' ?>">
                                        <?= e($log['method']) ?>
                                    </span>
                                    <span class="font-mono text-slate-700 text-xs truncate" title="<?= e($log['path']) ?>">
                                        <?= e($log['path']) ?>
                                    </span>
                                </div>
                            </td>

                            <td class="whitespace-nowrap text-xs text-center">
                                <?php $statusCode = (int)$log['status_code']; ?>
                                <?php if ($statusCode >= 200 && $statusCode < 300): ?>
                                    <span class="badge badge-success text-[10px] font-bold"><?= $statusCode ?> OK</span>
                                <?php elseif ($statusCode >= 300 && $statusCode < 400): ?>
                                    <span class="badge badge-info text-[10px] font-bold"><?= $statusCode ?> REDIRECT</span>
                                <?php elseif ($statusCode === 404): ?>
                                    <span class="badge badge-warning text-[10px] font-bold">404 NOT FOUND</span>
                                <?php elseif ($statusCode === 403): ?>
                                    <span class="badge badge-danger text-[10px] font-bold">403 FORBIDDEN</span>
                                <?php else: ?>
                                    <span class="badge badge-danger text-[10px] font-bold"><?= $statusCode ?> ERROR</span>
                                <?php endif; ?>
                            </td>

                            <td class="whitespace-nowrap text-center">
                                <button type="button" 
                                        class="btn btn-outline py-1 px-2.5 text-xs text-primary font-bold hover:bg-primary hover:text-white transition-all rounded-lg flex items-center gap-1 mx-auto"
                                        onclick='openAccessModal(<?= $jsonData ?>)'>
                                    <?= svg_icon('eye', 13) ?>
                                    <span>Detail</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-slate-400 py-12 text-sm">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <?= svg_icon('search', 32, 'text-slate-300') ?>
                                <p class="m-0 font-medium">Tidak ada riwayat akses website yang sesuai kriteria pencarian.</p>
                                <a href="/admin/keamanan/akses-log" class="text-primary text-xs underline">Muat semua data tanpa filter</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginasi Lengkap Preserving Query Parameters -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination flex flex-col sm:flex-row items-center justify-between p-4 border-t border-slate-200/80 text-xs gap-3">
            <span class="text-slate-500 text-center sm:text-left">
                Halaman <strong><?= $currentPage ?></strong> dari <strong><?= $totalPages ?></strong> (Total <strong><?= number_format($total) ?></strong> catatan)
            </span>
            <div class="pagination-pages flex gap-1 flex-wrap justify-center">
                <?php 
                    $params = $currentFilters;
                ?>
                <?php if ($currentPage > 1): ?>
                    <?php $params['page'] = 1; ?>
                    <a href="?<?= http_build_query(array_filter($params)) ?>" class="page-link" title="Halaman Pertama">&laquo;&laquo;</a>
                    <?php $params['page'] = $currentPage - 1; ?>
                    <a href="?<?= http_build_query(array_filter($params)) ?>" class="page-link" title="Sebelumnya">&laquo;</a>
                <?php endif; ?>

                <?php for ($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++): ?>
                    <?php $params['page'] = $p; ?>
                    <a href="?<?= http_build_query(array_filter($params)) ?>" class="page-link <?= ($p === $currentPage) ? 'active' : '' ?>">
                        <?= $p ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <?php $params['page'] = $currentPage + 1; ?>
                    <a href="?<?= http_build_query(array_filter($params)) ?>" class="page-link" title="Selanjutnya">&raquo;</a>
                    <?php $params['page'] = $totalPages; ?>
                    <a href="?<?= http_build_query(array_filter($params)) ?>" class="page-link" title="Halaman Terakhir">&raquo;&raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- ============================================================== -->
<!-- MODAL INSPECTOR FORENSIK DETAIL AKSES PENGUNJUNG                -->
<!-- ============================================================== -->
<div id="modalDetailAkses" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden animate__animated animate__fadeIn">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200 animate__animated animate__zoomIn animate__faster" style="animation-duration: 0.2s;">
        <!-- Modal Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                    <?= svg_icon('eye', 20) ?>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-[#0f3d64] m-0 leading-tight">Detail Forensik Akses Klien</h3>
                    <p class="text-xs text-slate-500 m-0">Catatan telemetri nomor ID: <span id="modalAksesId" class="font-mono font-bold text-slate-800">#</span></p>
                </div>
            </div>
            <button type="button" onclick="closeAccessModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-lg transition-colors font-bold">&times;</button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-4 sm:p-6 overflow-y-auto space-y-4 text-xs sm:text-sm">
            <!-- Grid Identitas & Waktu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Waktu Akses</span>
                    <span id="modalAksesWaktu" class="font-semibold text-slate-800">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Status HTTP</span>
                    <span id="modalAksesStatusBadge" class="inline-block font-mono font-bold">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Pengguna & Peran</span>
                    <div id="modalAksesUser" class="font-semibold text-slate-800 flex items-center gap-1.5">-</div>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Metode HTTP & Endpoint</span>
                    <div class="flex items-center gap-1.5 font-mono">
                        <span id="modalAksesMethod" class="badge badge-primary text-[10px] font-bold">GET</span>
                        <span id="modalAksesPath" class="text-slate-800 break-all font-semibold">/</span>
                    </div>
                </div>
            </div>

            <!-- Bagian IP & Geo Intelligence -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                <span class="text-slate-500 block text-[11px] font-bold uppercase tracking-wider">Alamat IP & Investigasi Jaringan</span>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <code id="modalAksesIp" class="text-sm sm:text-base font-mono font-bold bg-white text-slate-900 px-3 py-1 rounded-lg border border-slate-200">127.0.0.1</code>
                        <button type="button" id="btnCopyModalIp" class="btn btn-outline py-1 px-2.5 text-xs flex items-center gap-1 bg-white">
                            <?= svg_icon('file', 13) ?>
                            <span>Salin IP</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <a id="linkWhoisIp" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-outline py-1 px-2.5 text-xs flex items-center gap-1 text-blue-600 hover:text-blue-700 bg-white">
                            <?= svg_icon('external', 13) ?>
                            <span>Cek Whois / ISP</span>
                        </a>
                        <a id="linkFilterIp" href="#" class="btn btn-outline py-1 px-2.5 text-xs flex items-center gap-1 text-emerald-600 hover:text-emerald-700 bg-white">
                            <?= svg_icon('filter', 13) ?>
                            <span>Filter IP Ini</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Perangkat, OS & Browser -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Tipe Perangkat & OS</span>
                    <div class="flex items-center gap-1.5 font-medium text-slate-800">
                        <span id="modalAksesDeviceIcon">💻</span>
                        <span id="modalAksesDeviceType">-</span>
                        <span class="text-slate-400">/</span>
                        <span id="modalAksesPlatform" class="font-bold">-</span>
                    </div>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Peramban (Browser)</span>
                    <div class="font-medium text-slate-800 flex items-center gap-1.5">
                        <span>🌐</span>
                        <span id="modalAksesBrowser">-</span>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">URL Rujukan (HTTP Referer)</span>
                    <div id="modalAksesReferer" class="font-mono text-xs text-slate-700 break-all bg-white p-2 rounded border border-slate-200">
                        -
                    </div>
                </div>
            </div>

            <!-- Full Raw User Agent String -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-slate-500 font-bold text-xs uppercase tracking-wider">String Lengkap HTTP User-Agent:</span>
                    <button type="button" id="btnCopyModalUA" class="text-xs text-primary hover:underline font-bold flex items-center gap-1">
                        <?= svg_icon('file', 12) ?>
                        <span>Salin User-Agent</span>
                    </button>
                </div>
                <div id="modalAksesUA" class="bg-slate-900 text-emerald-400 font-mono text-[11px] p-3 rounded-xl overflow-x-auto break-all leading-relaxed border border-slate-800 select-all">
                    -
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-3 sm:p-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
            <button type="button" onclick="closeAccessModal()" class="btn btn-outline py-2 px-5 text-xs sm:text-sm font-bold bg-white">
                Tutup Jendela
            </button>
        </div>
    </div>
</div>

<!-- Script Interaktif Modal & Copy Tool -->
<script>
function openAccessModal(data) {
    document.getElementById('modalAksesId').textContent = '#' + data.id;
    document.getElementById('modalAksesWaktu').textContent = data.created_at_fmt;
    
    // Status Code Badge
    const code = parseInt(data.status_code);
    let badgeClass = 'badge badge-success';
    let statusText = code + ' OK';
    if (code >= 300 && code < 400) { badgeClass = 'badge badge-info'; statusText = code + ' REDIRECT'; }
    else if (code === 404) { badgeClass = 'badge badge-warning'; statusText = '404 NOT FOUND'; }
    else if (code === 403) { badgeClass = 'badge badge-danger'; statusText = '403 FORBIDDEN'; }
    else if (code >= 400) { badgeClass = 'badge badge-danger'; statusText = code + ' ERROR'; }
    
    const badgeEl = document.getElementById('modalAksesStatusBadge');
    badgeEl.className = badgeClass + ' text-xs px-2 py-0.5';
    badgeEl.textContent = statusText;

    // User details
    const userEl = document.getElementById('modalAksesUser');
    if (data.role === 'guest') {
        userEl.innerHTML = '<span class="inline-block w-2 h-2 rounded-full bg-slate-300"></span><span class="text-slate-600 font-medium">Tamu Publik (Unauthenticated)</span>';
    } else {
        userEl.innerHTML = '<span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span><strong>' + escapeHtml(data.username) + '</strong><span class="badge badge-primary text-[10px] uppercase">' + escapeHtml(data.role) + '</span>';
    }

    // Method & Path
    document.getElementById('modalAksesMethod').textContent = data.method;
    document.getElementById('modalAksesMethod').className = 'badge text-[10px] font-bold ' + (data.method === 'POST' ? 'badge-warning' : 'badge-neutral');
    document.getElementById('modalAksesPath').textContent = data.path;

    // IP & Lookup Links
    document.getElementById('modalAksesIp').textContent = data.ip_address;
    document.getElementById('linkWhoisIp').href = 'https://ipinfo.io/' + encodeURIComponent(data.ip_address);
    document.getElementById('linkFilterIp').href = '/admin/keamanan/akses-log?ip=' + encodeURIComponent(data.ip_address);

    // Copy IP button
    document.getElementById('btnCopyModalIp').onclick = function() {
        copyToClipboard(data.ip_address, this);
    };

    // Device
    let devIcon = '💻';
    if (data.device_type.includes('Smartphone')) devIcon = '📱';
    else if (data.device_type.includes('Tablet')) devIcon = '📟';
    else if (data.device_type.includes('Bot')) devIcon = '🤖';
    
    document.getElementById('modalAksesDeviceIcon').textContent = devIcon;
    document.getElementById('modalAksesDeviceType').textContent = data.device_type;
    document.getElementById('modalAksesPlatform').textContent = data.platform;
    document.getElementById('modalAksesBrowser').textContent = data.browser;
    
    // Referer & User-Agent
    document.getElementById('modalAksesReferer').textContent = (data.referer && data.referer !== '-') ? data.referer : 'Akses Langsung (Direct / Tanpa Referer)';
    document.getElementById('modalAksesUA').textContent = data.user_agent || 'Tidak ada user agent';

    // Copy UA button
    document.getElementById('btnCopyModalUA').onclick = function() {
        copyToClipboard(data.user_agent, this);
    };

    // Tampilkan modal
    const modal = document.getElementById('modalDetailAkses');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAccessModal() {
    const modal = document.getElementById('modalDetailAkses');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

// Close on backdrop click
document.getElementById('modalDetailAkses').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAccessModal();
    }
});

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAccessModal();
    }
});

function copyToClipboard(text, btnElement) {
    if (!text || text === '-') return;
    navigator.clipboard.writeText(text).then(function() {
        const originalContent = btnElement.innerHTML;
        btnElement.innerHTML = '<span>Tersalin!</span>';
        btnElement.classList.add('text-emerald-600', 'font-bold');
        setTimeout(function() {
            btnElement.innerHTML = originalContent;
            btnElement.classList.remove('text-emerald-600', 'font-bold');
        }, 1800);
    }).catch(function(err) {
        console.error('Gagal menyalin:', err);
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>
