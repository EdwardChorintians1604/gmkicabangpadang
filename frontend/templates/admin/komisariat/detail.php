<div style="max-width: 900px; margin: 0 auto;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <a href="/admin/komisariat" class="text-muted" style="font-size: 0.875rem;">&larr; Kembali ke Komisariat</a>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);"><?= e($item['nama']) ?></h1>
            <p class="text-muted" style="font-size: 0.875rem;">
                <?= e($item['perguruan_tinggi'] ?? '') ?>
                <?= !empty($item['keterangan']) ? ' &middot; ' . e($item['keterangan']) : '' ?>
            </p>
        </div>
        <?php if (can('civitas.update')): ?>
            <a href="/admin/komisariat/<?= (int)$item['id'] ?>/edit" class="btn btn-outline btn-sm">✏️ Edit Komisariat</a>
        <?php endif; ?>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Perguruan Tinggi</th>
                        <th>Kaderisasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $m): ?>
                            <tr>
                                <td><code><?= e($m['nim']) ?></code></td>
                                <td><a href="/admin/civitas/<?= (int)$m['id'] ?>" style="font-weight: 700; color: var(--primary);"><?= e($m['nama_lengkap']) ?></a></td>
                                <td><?= e($m['perguruan_tinggi']) ?></td>
                                <td><span class="badge badge-neutral"><?= e($m['tingkat_kaderisasi']) ?></span></td>
                                <td><?= e($m['status_keanggotaan']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 3rem;">Belum ada anggota di komisariat ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
