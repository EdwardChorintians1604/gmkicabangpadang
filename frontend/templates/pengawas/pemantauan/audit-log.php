<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 m-0">Pengawasan Jejak Audit Aktivitas</h1>
            <span class="badge badge-warning text-xs">BACA-SAJA</span>
        </div>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Pemantauan transparansi seluruh aktivitas, perubahan data anggota, otentikasi akun, dan aksi operasional pengurus.
        </p>
    </div>
    <div class="flex gap-2 flex-shrink-0">
        <span class="badge badge-info p-2 text-xs sm:text-sm">
            Total: <strong><?= number_format($total) ?></strong> Log Aktivitas
        </span>
    </div>
</div>

<!-- Filter Bar -->
<div class="card mb-6 p-4 sm:p-5">
    <form action="/pengawas/pemantauan/audit-log" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="form-group mb-0">
            <input type="text" name="username" class="form-control" placeholder="Cari nama pengguna..." value="<?= e($searchUsername ?? '') ?>">
        </div>

        <div class="form-group mb-0">
            <select name="action" class="form-control">
                <option value="">Semua Tindakan / Aksi</option>
                <option value="LOGIN" <?= ($selectedAction === 'LOGIN') ? 'selected' : '' ?>>LOGIN</option>
                <option value="LOGOUT" <?= ($selectedAction === 'LOGOUT') ? 'selected' : '' ?>>LOGOUT</option>
                <option value="CREATE_CIVITAS" <?= ($selectedAction === 'CREATE_CIVITAS') ? 'selected' : '' ?>>CREATE_CIVITAS</option>
                <option value="UPDATE_CIVITAS" <?= ($selectedAction === 'UPDATE_CIVITAS') ? 'selected' : '' ?>>UPDATE_CIVITAS</option>
                <option value="DELETE_CIVITAS" <?= ($selectedAction === 'DELETE_CIVITAS') ? 'selected' : '' ?>>DELETE_CIVITAS</option>
                <option value="IMPORT_CIVITAS" <?= ($selectedAction === 'IMPORT_CIVITAS') ? 'selected' : '' ?>>IMPORT_CIVITAS</option>
                <option value="CREATE_NEWS" <?= ($selectedAction === 'CREATE_NEWS') ? 'selected' : '' ?>>CREATE_NEWS</option>
                <option value="UPDATE_NEWS" <?= ($selectedAction === 'UPDATE_NEWS') ? 'selected' : '' ?>>UPDATE_NEWS</option>
                <option value="BACKUP_DATABASE" <?= ($selectedAction === 'BACKUP_DATABASE') ? 'selected' : '' ?>>BACKUP_DATABASE</option>
            </select>
        </div>

        <div class="form-group mb-0">
            <select name="entity" class="form-control">
                <option value="">Semua Modul / Entitas</option>
                <option value="auth" <?= ($selectedEntity === 'auth') ? 'selected' : '' ?>>Otentikasi (auth)</option>
                <option value="civitas" <?= ($selectedEntity === 'civitas') ? 'selected' : '' ?>>Data Anggota (civitas)</option>
                <option value="berita" <?= ($selectedEntity === 'berita') ? 'selected' : '' ?>>Warta Berita (berita)</option>
                <option value="organization_profile" <?= ($selectedEntity === 'organization_profile') ? 'selected' : '' ?>>Profil Organisasi</option>
                <option value="organization_structure" <?= ($selectedEntity === 'organization_structure') ? 'selected' : '' ?>>Struktur BPC</option>
                <option value="user" <?= ($selectedEntity === 'user') ? 'selected' : '' ?>>Pengguna (user)</option>
                <option value="system" <?= ($selectedEntity === 'system') ? 'selected' : '' ?>>Sistem (system)</option>
            </select>
        </div>

        <div class="flex gap-2 col-span-1 sm:col-span-2 lg:col-span-1">
            <button type="submit" class="btn btn-primary flex-1 justify-center">Filter Log</button>
            <?php if (!empty($selectedAction) || !empty($selectedEntity) || !empty($searchUsername)): ?>
                <a href="/pengawas/pemantauan/audit-log" class="btn btn-outline justify-center">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu (WIB)</th>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Aksi</th>
                    <th>Entitas</th>
                    <th>Rincian Aktivitas</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-muted" style="white-space: nowrap;">
                                <?= format_date($log['created_at'], 'd M Y H:i:s') ?>
                            </td>
                            <td><strong><?= e($log['username']) ?></strong></td>
                            <td><span class="badge badge-neutral"><?= e($log['role']) ?></span></td>
                            <td><span class="badge badge-primary"><?= e($log['action']) ?></span></td>
                            <td><?= e($log['entity']) ?> <?= !empty($log['entity_id']) ? '#' . e($log['entity_id']) : '' ?></td>
                            <td style="max-width: 320px; font-size: 0.8125rem;">
                                <?php if (!empty($log['details'])): ?>
                                    <pre style="background:var(--bg-subtle); padding:4px 8px; border-radius:4px; font-size:0.75rem; overflow-x:auto; margin:0;"><?= e($log['details']) ?></pre>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><code><?= e($log['ip_address']) ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 3rem;">
                            Tidak ada catatan jejak audit yang sesuai filter.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <?= partial('pagination', [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'baseUrl' => '/pengawas/pemantauan/audit-log',
            'queryParams' => array_filter([
                'action' => $selectedAction,
                'entity' => $selectedEntity,
                'username' => $searchUsername
            ])
        ]) ?>
    <?php endif; ?>
</div>
