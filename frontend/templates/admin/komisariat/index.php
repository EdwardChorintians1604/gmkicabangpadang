<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Komisariat GMKI Padang</h1>
        <p class="text-muted" style="font-size: 0.875rem;">Kelola daftar komisariat dan lihat anggota di setiap komisariat.</p>
    </div>
    <?php if (can('civitas.create')): ?>
        <a href="/admin/komisariat/create" class="btn btn-primary btn-sm">➕ Tambah Komisariat</a>
    <?php endif; ?>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Komisariat</th>
                    <th>Perguruan Tinggi</th>
                    <th>Jumlah Anggota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($komisariat)): ?>
                    <?php foreach ($komisariat as $k): ?>
                        <tr>
                            <td>
                                <a href="/admin/komisariat/<?= (int)$k['id'] ?>" style="font-weight: 700; color: var(--primary);">
                                    <?= e($k['nama']) ?>
                                </a>
                            </td>
                            <td><?= e($k['perguruan_tinggi'] ?? '-') ?></td>
                            <td><span class="badge badge-primary"><?= (int)$k['total_anggota'] ?> anggota</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="/admin/komisariat/<?= (int)$k['id'] ?>" class="btn btn-outline btn-sm">Anggota</a>
                                    <?php if (can('civitas.update')): ?>
                                        <a href="/admin/komisariat/<?= (int)$k['id'] ?>/edit" class="btn btn-outline btn-sm">Edit</a>
                                    <?php endif; ?>
                                    <?php if (can('civitas.delete')): ?>
                                        <form action="/admin/komisariat/<?= (int)$k['id'] ?>/delete" method="POST" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus komisariat <?= e($k['nama']) ?>?">✕</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted" style="padding: 3rem;">Belum ada komisariat. Tambahkan komisariat pertama.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
