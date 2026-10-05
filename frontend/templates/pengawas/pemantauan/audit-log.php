<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div class="flex items-center gap-2" style="margin-bottom: 0.35rem;">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin: 0;">Pengawasan Jejak Audit Aktivitas</h1>
            <span class="badge badge-warning">BACA-SAJA</span>
        </div>
        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
            Pemantauan transparansi seluruh aktivitas, perubahan data anggota, otentikasi akun, dan aksi operasional pengurus.
        </p>
    </div>
    <div class="flex gap-2">
        <span class="badge badge-info" style="padding: 8px 12px; font-size: 0.85rem;">
            Total: <strong><?= number_format($total) ?></strong> Log Aktivitas
        </span>
    </div>
</div>

<!-- Filter Bar -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="/pengawas/pemantauan/audit-log" method="GET" class="grid grid-cols-4 gap-3">
        <div class="form-group" style="margin-bottom: 0;">
            <input type="text" name="username" class="form-control" placeholder="Cari nama pengguna..." value="<?= e($searchUsername ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
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

        <div class="form-group" style="margin-bottom: 0;">
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

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary" style="flex-grow: 1;">Filter Log</button>
            <?php if (!empty($selectedAction) || !empty($selectedEntity) || !empty($searchUsername)): ?>
                <a href="/pengawas/pemantauan/audit-log" class="btn btn-outline">Reset</a>
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
