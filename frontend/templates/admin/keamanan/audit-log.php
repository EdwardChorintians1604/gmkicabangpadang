<?php
/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: frontend/templates/admin/keamanan/audit-log.php
 * Deskripsi: Jejak Audit Aktivitas Sistem & Forensik Mutasi Data
 * Lapisan: View (Admin)
 * =====================================================================
 */

// Helper URL query builder untuk ekspor dan paginasi
$currentFilters = [
    'username' => $searchUsername ?? '',
    'action' => $selectedAction ?? '',
    'entity' => $selectedEntity ?? '',
    'ip' => $searchIp ?? '',
    'date' => $searchDate ?? '',
];
$filterQueryString = http_build_query(array_filter($currentFilters));
$exportUrl = '/admin/keamanan/audit-log/export' . ($filterQueryString ? '?' . $filterQueryString : '');
?>

<!-- Header Halaman Audit -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4 animate__animated animate__fadeInDown">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="badge badge-primary text-xs px-2.5 py-0.5 font-bold uppercase tracking-wider">Kepatuhan & Akuntabilitas</span>
            <span class="text-xs text-slate-400 font-medium">Audit Trail Forensik</span>
        </div>
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#0f3d64] m-0 mb-1 leading-tight">
            Jejak Audit Aktivitas Sistem (Audit Trail)
        </h1>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Pencatatan kronologis seluruh interaksi, mutasi data keanggotaan, perubahan berkas, dan aktivitas fungsionaris.
        </p>
    </div>
    <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
        <a href="<?= e($exportUrl) ?>" class="btn btn-outline flex items-center gap-2 text-xs sm:text-sm py-2 px-3.5 shadow-sm hover:border-emerald-600 hover:text-emerald-700 transition-all bg-white" title="Unduh seluruh rekaman jejak audit ke file CSV">
            <?= svg_icon('download', 16, 'text-emerald-600') ?>
            <span>Ekspor CSV</span>
        </a>
        <a href="/admin/keamanan/audit-log" class="btn btn-primary flex items-center gap-2 text-xs sm:text-sm py-2 px-3.5 shadow-sm" title="Segarkan data audit">
            <?= svg_icon('refresh', 16) ?>
            <span>Segarkan</span>
        </a>
    </div>
</div>

<!-- Tab Navigasi Mode Audit -->
<div class="flex items-center gap-2 mb-6 border-b border-slate-200 overflow-x-auto pb-1 text-sm font-semibold">
    <a href="/admin/keamanan/audit-log" class="px-4 py-2.5 text-primary border-b-2 border-primary transition-colors flex items-center gap-2 whitespace-nowrap">
        <?= svg_icon('shield', 16) ?>
        <span>Jejak Audit Aktivitas Data</span>
        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-bold">Aktif</span>
    </a>
    <a href="/admin/keamanan/akses-log" class="px-4 py-2.5 text-slate-500 hover:text-primary transition-colors flex items-center gap-2 border-b-2 border-transparent whitespace-nowrap">
        <?= svg_icon('dashboard', 16) ?>
        <span>Audit Akses & Perangkat Pengunjung</span>
    </a>
    <a href="/admin/keamanan/ancaman" class="px-4 py-2.5 text-slate-500 hover:text-primary transition-colors flex items-center gap-2 border-b-2 border-transparent whitespace-nowrap">
        <?= svg_icon('alert', 16) ?>
        <span>Deteksi Ancaman & Keamanan</span>
    </a>
</div>

<!-- Kartu Ringkasan Eksekutif KPI Audit Log -->
<div class="metrics-grid mb-6 animate__animated animate__fadeInUp">
    <div class="stat-card stat-card-primary">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Jejak Audit</span>
            <div class="stat-card-icon icon-primary">
                <?= svg_icon('database', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summaryStats['total_logs'] ?? $total ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub text-slate-500">Histori kumulatif mutasi & aksi sistem</span>
        </div>
    </div>

    <div class="stat-card stat-card-success">
        <div class="stat-card-header">
            <span class="stat-card-title">Aktivitas Hari Ini</span>
            <div class="stat-card-icon icon-success">
                <?= svg_icon('news', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summaryStats['today_logs'] ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub text-slate-500">Aksi tercatat sejak pukul 00:00 WIB</span>
        </div>
    </div>

    <div class="stat-card stat-card-secondary">
        <div class="stat-card-header">
            <span class="stat-card-title">Mutasi Data Sensitif</span>
            <div class="stat-card-icon icon-secondary">
                <?= svg_icon('edit', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summaryStats['sensitive_ops'] ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub text-slate-500">Operasi Create, Update, Delete & Backup</span>
        </div>
    </div>

    <div class="stat-card stat-card-info">
        <div class="stat-card-header">
            <span class="stat-card-title">Peristiwa Otentikasi</span>
            <div class="stat-card-icon icon-info">
                <?= svg_icon('lock', 20) ?>
            </div>
        </div>
        <div class="stat-card-value"><?= number_format($summaryStats['auth_events'] ?? 0) ?></div>
        <div class="stat-card-footer">
            <span class="stat-card-sub text-slate-500">Login, logout, & verifikasi keamanan</span>
        </div>
    </div>
</div>

<!-- Strip Informasi Aktor Teraktif & Sebaran Aksi Populer -->
<?php if (!empty($summaryStats['top_actors']) || !empty($summaryStats['action_stats'])): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Top Actors -->
        <div class="card p-4 rounded-2xl border border-slate-200/80 shadow-sm bg-white">
            <div class="flex items-center justify-between pb-2.5 mb-2.5 border-b border-slate-100">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-600 m-0 flex items-center gap-1.5">
                    <?= svg_icon('users', 15, 'text-primary') ?>
                    <span>Fungsionaris / Aktor Paling Aktif</span>
                </h4>
                <span class="text-[11px] text-slate-400">Total Operasi</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php foreach (($summaryStats['top_actors'] ?? []) as $actor): ?>
                    <a href="/admin/keamanan/audit-log?username=<?= urlencode($actor['username']) ?>" class="inline-flex items-center gap-2 p-1.5 pr-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200/80 text-xs transition-colors" title="Filter log aktor ini">
                        <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-[10px]">
                            <?= strtoupper(substr($actor['username'], 0, 2)) ?>
                        </span>
                        <div class="text-left">
                            <span class="font-bold text-slate-800 block leading-tight"><?= e($actor['username']) ?></span>
                            <span class="text-[10px] text-slate-400 uppercase font-semibold"><?= e($actor['role']) ?> &bull; <?= $actor['total'] ?> log</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Top Actions -->
        <div class="card p-4 rounded-2xl border border-slate-200/80 shadow-sm bg-white">
            <div class="flex items-center justify-between pb-2.5 mb-2.5 border-b border-slate-100">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-600 m-0 flex items-center gap-1.5">
                    <?= svg_icon('chart', 15, 'text-emerald-600') ?>
                    <span>Distribusi Tindakan Terbanyak</span>
                </h4>
                <span class="text-[11px] text-slate-400">Aksi Sistem</span>
            </div>
            <div class="flex flex-wrap gap-1.5">
                <?php foreach (($summaryStats['action_stats'] ?? []) as $act): ?>
                    <a href="/admin/keamanan/audit-log?action=<?= urlencode($act['action']) ?>" class="inline-flex items-center gap-1 text-[11px] bg-slate-100 hover:bg-slate-200 text-slate-800 px-2.5 py-1 rounded-lg font-mono font-medium border border-slate-200 transition-colors" title="Filter tindakan: <?= e($act['action']) ?>">
                        <span><?= e($act['action']) ?></span>
                        <span class="badge badge-primary text-[10px] py-0 px-1.5"><?= $act['total'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Filter Pencarian Fleksibel -->
<div class="card mb-6 p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm animate__animated animate__fadeIn bg-white">
    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
            <?= svg_icon('filter', 14, 'text-primary') ?>
            <span>Filter Kriteria Jejak Audit</span>
        </span>
        <?php if (!empty($selectedAction) || !empty($selectedEntity) || !empty($searchUsername) || !empty($searchIp) || !empty($searchDate)): ?>
            <a href="/admin/keamanan/audit-log" class="text-xs text-rose-600 hover:text-rose-800 font-bold flex items-center gap-1">
                <span>Reset Semua Filter</span>
            </a>
        <?php endif; ?>
    </div>

    <form action="/admin/keamanan/audit-log" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Nama Pengguna</label>
            <input type="text" name="username" class="form-control text-xs sm:text-sm" placeholder="Cari nama akun..." value="<?= e($searchUsername ?? '') ?>">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Jenis Tindakan / Aksi</label>
            <select name="action" class="form-control text-xs sm:text-sm">
                <option value="">Semua Tindakan</option>
                <option value="LOGIN" <?= ($selectedAction === 'LOGIN') ? 'selected' : '' ?>>LOGIN (Masuk Sistem)</option>
                <option value="LOGOUT" <?= ($selectedAction === 'LOGOUT') ? 'selected' : '' ?>>LOGOUT (Keluar Sistem)</option>
                <option value="CREATE_CIVITAS" <?= ($selectedAction === 'CREATE_CIVITAS') ? 'selected' : '' ?>>CREATE_CIVITAS (Tambah Anggota)</option>
                <option value="UPDATE_CIVITAS" <?= ($selectedAction === 'UPDATE_CIVITAS') ? 'selected' : '' ?>>UPDATE_CIVITAS (Ubah Anggota)</option>
                <option value="DELETE_CIVITAS" <?= ($selectedAction === 'DELETE_CIVITAS') ? 'selected' : '' ?>>DELETE_CIVITAS (Hapus Anggota)</option>
                <option value="IMPORT_CIVITAS" <?= ($selectedAction === 'IMPORT_CIVITAS') ? 'selected' : '' ?>>IMPORT_CIVITAS (Impor Massal)</option>
                <option value="CREATE_NEWS" <?= ($selectedAction === 'CREATE_NEWS') ? 'selected' : '' ?>>CREATE_NEWS (Buat Berita)</option>
                <option value="UPDATE_NEWS" <?= ($selectedAction === 'UPDATE_NEWS') ? 'selected' : '' ?>>UPDATE_NEWS (Ubah Berita)</option>
                <option value="DELETE_NEWS" <?= ($selectedAction === 'DELETE_NEWS') ? 'selected' : '' ?>>DELETE_NEWS (Hapus Berita)</option>
                <option value="BACKUP_DATABASE" <?= ($selectedAction === 'BACKUP_DATABASE') ? 'selected' : '' ?>>BACKUP_DATABASE (Cadangkan)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Entitas / Modul</label>
            <select name="entity" class="form-control text-xs sm:text-sm">
                <option value="">Semua Modul Entitas</option>
                <option value="auth" <?= ($selectedEntity === 'auth') ? 'selected' : '' ?>>Otentikasi (auth)</option>
                <option value="civitas" <?= ($selectedEntity === 'civitas') ? 'selected' : '' ?>>Data Anggota (civitas)</option>
                <option value="berita" <?= ($selectedEntity === 'berita') ? 'selected' : '' ?>>Warta Berita (berita)</option>
                <option value="organization_profile" <?= ($selectedEntity === 'organization_profile') ? 'selected' : '' ?>>Profil Organisasi</option>
                <option value="organization_structure" <?= ($selectedEntity === 'organization_structure') ? 'selected' : '' ?>>Struktur BPC</option>
                <option value="user" <?= ($selectedEntity === 'user') ? 'selected' : '' ?>>Pengguna (user)</option>
                <option value="system" <?= ($selectedEntity === 'system') ? 'selected' : '' ?>>Sistem (system)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Alamat IP Asal</label>
            <input type="text" name="ip" class="form-control text-xs sm:text-sm" placeholder="Contoh: 127.0.0.1..." value="<?= e($searchIp ?? '') ?>">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Tanggal Kejadian</label>
            <input type="date" name="date" class="form-control text-xs sm:text-sm" value="<?= e($searchDate ?? '') ?>">
        </div>

        <div class="sm:col-span-2 lg:col-span-5 flex justify-end gap-2 pt-2 border-t border-slate-100">
            <?php if (!empty($selectedAction) || !empty($selectedEntity) || !empty($searchUsername) || !empty($searchIp) || !empty($searchDate)): ?>
                <a href="/admin/keamanan/audit-log" class="btn btn-outline py-2 text-xs sm:text-sm px-4">Reset</a>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary py-2 text-xs sm:text-sm px-6 font-bold flex items-center gap-2">
                <?= svg_icon('search', 16) ?>
                <span>Terapkan Filter Audit</span>
            </button>
        </div>
    </form>
</div>

<!-- Tabel Riwayat Jejak Audit -->
<div class="card table-card rounded-2xl shadow-sm border border-slate-200/80 animate__animated animate__fadeIn overflow-hidden bg-white mb-6">
    <div class="table-header flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-5 gap-3 border-b border-slate-200/80">
        <div>
            <h3 class="text-base sm:text-lg font-bold text-[#0f3d64] m-0 flex items-center gap-2">
                <span>Daftar Jejak Rekaman Aktivitas</span>
            </h3>
            <p class="text-slate-500 text-xs sm:text-sm m-0">
                Menampilkan <?= count($logs) ?> entri dari total <?= number_format($total) ?> histori audit yang ditemukan
            </p>
        </div>
        <div class="text-xs text-slate-400 font-mono">
            Urutan: Kejadian Terkini (DESC)
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Waktu Kejadian</th>
                    <th class="whitespace-nowrap">Pelaku (Aktor)</th>
                    <th class="whitespace-nowrap">Peran</th>
                    <th class="whitespace-nowrap">Tindakan / Aksi</th>
                    <th class="whitespace-nowrap">Entitas Target</th>
                    <th class="whitespace-nowrap">Rincian Perubahan</th>
                    <th class="whitespace-nowrap">Alamat IP</th>
                    <th class="whitespace-nowrap text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <?php 
                            // Tentukan warna badge aksi
                            $actionName = $log['action'];
                            $badgeActionClass = 'bg-slate-100 text-slate-800 border-slate-200';
                            if (str_contains($actionName, 'CREATE') || str_contains($actionName, 'IMPORT')) {
                                $badgeActionClass = 'bg-emerald-100 text-emerald-800 border-emerald-300 font-bold';
                            } elseif (str_contains($actionName, 'UPDATE')) {
                                $badgeActionClass = 'bg-blue-100 text-blue-800 border-blue-300 font-bold';
                            } elseif (str_contains($actionName, 'DELETE')) {
                                $badgeActionClass = 'bg-rose-100 text-rose-800 border-rose-300 font-bold';
                            } elseif (str_contains($actionName, 'LOGIN') || str_contains($actionName, 'AUTH')) {
                                $badgeActionClass = 'bg-purple-100 text-purple-800 border-purple-300 font-bold';
                            } elseif (str_contains($actionName, 'LOGOUT')) {
                                $badgeActionClass = 'bg-slate-200 text-slate-700 border-slate-300';
                            } elseif (str_contains($actionName, 'BACKUP')) {
                                $badgeActionClass = 'bg-amber-100 text-amber-800 border-amber-300 font-bold';
                            }

                            // Siapkan JSON aman untuk modal inspector
                            $auditPayloadJson = json_encode([
                                'id' => $log['id'],
                                'created_at' => $log['created_at'],
                                'created_at_fmt' => format_date($log['created_at'], 'd M Y, H:i:s') . ' WIB',
                                'username' => $log['username'],
                                'role' => $log['role'],
                                'action' => $log['action'],
                                'entity' => $log['entity'],
                                'entity_id' => $log['entity_id'] ?? '-',
                                'ip_address' => $log['ip_address'],
                                'user_agent' => $log['user_agent'] ?? '-',
                                'details' => $log['details'] ?? null,
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="text-slate-500 whitespace-nowrap text-xs">
                                <strong class="text-slate-800"><?= format_date($log['created_at'], 'd M Y') ?></strong><br>
                                <span class="text-slate-400 font-mono text-[11px]"><?= format_date($log['created_at'], 'H:i:s') ?> WIB</span>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <div class="flex items-center gap-1.5 font-bold text-slate-900">
                                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-[10px]">
                                        <?= strtoupper(substr($log['username'], 0, 2)) ?>
                                    </span>
                                    <span><?= e($log['username']) ?></span>
                                </div>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <span class="badge badge-neutral text-[10px] font-bold uppercase px-2 py-0.5">
                                    <?= e($log['role']) ?>
                                </span>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-mono border <?= $badgeActionClass ?>">
                                    <?= e($log['action']) ?>
                                </span>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <div class="font-medium text-slate-800 flex items-center gap-1">
                                    <span class="text-slate-400">📁</span>
                                    <span><?= e($log['entity']) ?></span>
                                    <?php if (!empty($log['entity_id'])): ?>
                                        <code class="text-[10px] bg-slate-100 text-slate-600 px-1 rounded font-bold">#<?= e($log['entity_id']) ?></code>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="text-xs max-w-[280px]">
                                <?php if (!empty($log['details'])): ?>
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" 
                                                class="btn btn-outline py-0.5 px-2 text-[11px] font-bold text-blue-700 bg-blue-50/60 hover:bg-blue-100 border-blue-200 rounded flex items-center gap-1"
                                                onclick='openAuditModal(<?= $auditPayloadJson ?>)'>
                                            <?= svg_icon('eye', 11) ?>
                                            <span>Periksa Detail</span>
                                        </button>
                                        <span class="text-[11px] text-slate-400 font-mono truncate max-w-[150px]" title="<?= e($log['details']) ?>">
                                            <?= e(substr($log['details'], 0, 45)) ?>...
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-400 text-xs italic">- Tidak ada rincian -</span>
                                <?php endif; ?>
                            </td>

                            <td class="whitespace-nowrap text-xs">
                                <div class="flex items-center gap-1">
                                    <code class="bg-slate-100 text-slate-800 px-1.5 py-0.5 rounded font-mono text-[11px] border border-slate-200">
                                        <?= e($log['ip_address']) ?>
                                    </code>
                                    <button type="button" class="text-slate-400 hover:text-slate-700 p-0.5" title="Salin IP" onclick="copyToClipboard('<?= e($log['ip_address']) ?>', this)">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    </button>
                                </div>
                            </td>

                            <td class="whitespace-nowrap text-center text-xs">
                                <button type="button" 
                                        class="btn btn-outline py-1 px-2.5 text-xs text-primary font-bold hover:bg-primary hover:text-white transition-all rounded-lg flex items-center gap-1 mx-auto"
                                        onclick='openAuditModal(<?= $auditPayloadJson ?>)'>
                                    <?= svg_icon('shield', 13) ?>
                                    <span>Forensik</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-slate-400 py-12 text-sm">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <?= svg_icon('search', 32, 'text-slate-300') ?>
                                <p class="m-0 font-medium">Tidak ada rekaman jejak audit yang sesuai kriteria pencarian Anda.</p>
                                <a href="/admin/keamanan/audit-log" class="text-primary text-xs underline">Muat semua jejak audit</a>
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
                Halaman <strong><?= $currentPage ?></strong> dari <strong><?= $totalPages ?></strong> (Total <strong><?= number_format($total) ?></strong> rekaman audit)
            </span>
            <div class="pagination-pages flex gap-1 flex-wrap justify-center">
                <?php $params = $currentFilters; ?>
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
<!-- MODAL INSPECTOR FORENSIK DETAIL AUDIT LOG                      -->
<!-- ============================================================== -->
<div id="modalDetailAudit" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden animate__animated animate__fadeIn">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200 animate__animated animate__zoomIn animate__faster" style="animation-duration: 0.2s;">
        <!-- Modal Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                    <?= svg_icon('shield', 20) ?>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-[#0f3d64] m-0 leading-tight">Detail Forensik Rekaman Audit</h3>
                    <p class="text-xs text-slate-500 m-0">No Tiket Log: <span id="modalAuditId" class="font-mono font-bold text-slate-800">#</span></p>
                </div>
            </div>
            <button type="button" onclick="closeAuditModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-lg transition-colors font-bold">&times;</button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-4 sm:p-6 overflow-y-auto space-y-4 text-xs sm:text-sm">
            <!-- Grid Identitas & Meta -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Waktu Kejadian</span>
                    <span id="modalAuditWaktu" class="font-semibold text-slate-800">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Tindakan / Aksi</span>
                    <span id="modalAuditActionBadge" class="inline-block font-mono font-bold">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Aktor Pengguna & Role</span>
                    <div id="modalAuditUser" class="font-semibold text-slate-800 flex items-center gap-1.5">-</div>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Entitas Target & ID</span>
                    <div id="modalAuditTarget" class="font-semibold text-slate-800 flex items-center gap-1.5">-</div>
                </div>
            </div>

            <!-- Alamat IP & Lokasi -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">IP:</span>
                    <code id="modalAuditIp" class="text-sm font-mono font-bold bg-white text-slate-900 px-2.5 py-0.5 rounded border border-slate-200">127.0.0.1</code>
                    <button type="button" id="btnCopyAuditIp" class="btn btn-outline py-1 px-2 text-xs flex items-center gap-1 bg-white">
                        <?= svg_icon('file', 12) ?>
                        <span>Salin</span>
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <a id="linkAuditWhois" href="#" target="_blank" rel="noopener noreferrer" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                        <?= svg_icon('external', 12) ?>
                        <span>Cek Jaringan IP</span>
                    </a>
                    <a id="linkAuditFilterIp" href="#" class="text-xs text-emerald-600 hover:underline flex items-center gap-1 font-bold">
                        <?= svg_icon('filter', 12) ?>
                        <span>Filter Log IP Ini</span>
                    </a>
                </div>
            </div>

            <!-- Tab Tampilan Rincian Data (Terstruktur vs Mentah JSON) -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-slate-700 font-bold text-xs uppercase tracking-wider flex items-center gap-1.5">
                        <?= svg_icon('database', 14, 'text-primary') ?>
                        <span>Rincian Payload Perubahan:</span>
                    </span>
                    <button type="button" id="btnCopyAuditDetails" class="text-xs text-primary hover:underline font-bold flex items-center gap-1">
                        <?= svg_icon('file', 12) ?>
                        <span>Salin JSON</span>
                    </button>
                </div>

                <!-- Structured Key-Value or Diff Table -->
                <div id="modalAuditStructuredBox" class="rounded-xl border border-slate-200 overflow-hidden mb-2 hidden">
                    <table class="w-full text-xs">
                        <thead class="bg-slate-100 border-b border-slate-200 text-slate-600">
                            <tr>
                                <th class="p-2 text-left font-bold">Kolom / Data</th>
                                <th class="p-2 text-left font-bold">Nilai Tercatat</th>
                            </tr>
                        </thead>
                        <tbody id="modalAuditStructuredBody" class="divide-y divide-slate-100 bg-white">
                        </tbody>
                    </table>
                </div>

                <!-- Raw JSON viewer fallback -->
                <pre id="modalAuditRawBox" class="bg-slate-900 text-emerald-400 font-mono text-[11px] p-3 rounded-xl overflow-x-auto leading-relaxed border border-slate-800 select-all max-h-56 m-0">-</pre>
            </div>

            <!-- User Agent Client -->
            <div>
                <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-1">Klien Web / User-Agent:</span>
                <div id="modalAuditUA" class="bg-slate-50 text-slate-700 font-mono text-[11px] p-2.5 rounded-lg border border-slate-200 break-all select-all">
                    -
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-3 sm:p-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
            <button type="button" onclick="closeAuditModal()" class="btn btn-outline py-2 px-5 text-xs sm:text-sm font-bold bg-white">
                Tutup Jendela
            </button>
        </div>
    </div>
</div>

<!-- Script Interaktif Modal & Copy Tool -->
<script>
function openAuditModal(data) {
    document.getElementById('modalAuditId').textContent = '#' + data.id;
    document.getElementById('modalAuditWaktu').textContent = data.created_at_fmt;
    
    // Action Badge
    const actBadge = document.getElementById('modalAuditActionBadge');
    actBadge.textContent = data.action;
    actBadge.className = 'inline-block px-2 py-0.5 rounded text-xs font-mono font-bold bg-primary/10 text-primary border border-primary/20';

    // User details
    document.getElementById('modalAuditUser').innerHTML = '<strong>' + escapeHtml(data.username) + '</strong> <span class="badge badge-neutral text-[10px] uppercase">' + escapeHtml(data.role) + '</span>';

    // Entity & Target
    document.getElementById('modalAuditTarget').innerHTML = '<span class="text-slate-700 font-mono">' + escapeHtml(data.entity) + '</span>' + (data.entity_id && data.entity_id !== '-' ? ' <code class="text-xs font-bold text-primary bg-slate-100 px-1.5 rounded">#' + escapeHtml(data.entity_id) + '</code>' : '');

    // IP details & links
    document.getElementById('modalAuditIp').textContent = data.ip_address;
    document.getElementById('linkAuditWhois').href = 'https://ipinfo.io/' + encodeURIComponent(data.ip_address);
    document.getElementById('linkAuditFilterIp').href = '/admin/keamanan/audit-log?ip=' + encodeURIComponent(data.ip_address);
    document.getElementById('btnCopyAuditIp').onclick = function() {
        copyToClipboard(data.ip_address, this);
    };

    // User Agent
    document.getElementById('modalAuditUA').textContent = data.user_agent || '-';

    // Parse Details (Structured table or Raw JSON)
    const structuredBox = document.getElementById('modalAuditStructuredBox');
    const structuredBody = document.getElementById('modalAuditStructuredBody');
    const rawBox = document.getElementById('modalAuditRawBox');
    structuredBody.innerHTML = '';

    let rawText = '-';
    let isStructured = false;

    if (data.details) {
        try {
            const parsed = JSON.parse(data.details);
            rawText = JSON.stringify(parsed, null, 2);

            if (typeof parsed === 'object' && parsed !== null) {
                // If it's a simple key-value object
                for (const [key, val] of Object.entries(parsed)) {
                    isStructured = true;
                    const tr = document.createElement('tr');
                    let displayVal = (typeof val === 'object') ? JSON.stringify(val) : String(val);
                    tr.innerHTML = '<td class="p-2 font-mono font-bold text-slate-700 bg-slate-50/50">' + escapeHtml(key) + '</td><td class="p-2 font-mono text-slate-900 break-all">' + escapeHtml(displayVal) + '</td>';
                    structuredBody.appendChild(tr);
                }
            }
        } catch (e) {
            rawText = data.details;
        }
    }

    rawBox.textContent = rawText;
    if (isStructured) {
        structuredBox.classList.remove('hidden');
    } else {
        structuredBox.classList.add('hidden');
    }

    // Copy Details button
    document.getElementById('btnCopyAuditDetails').onclick = function() {
        copyToClipboard(rawText, this);
    };

    // Open Modal
    const modal = document.getElementById('modalDetailAudit');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAuditModal() {
    const modal = document.getElementById('modalDetailAudit');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

// Close on backdrop click
document.getElementById('modalDetailAudit').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAuditModal();
    }
});

// Close on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAuditModal();
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
