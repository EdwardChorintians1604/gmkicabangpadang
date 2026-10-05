<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Manajemen Pengguna Sistem</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Kelola akun administrator, majelis pengawas, dan operator sistem.
        </p>
    </div>
    <div>
        <a href="/admin/users/create" class="btn btn-primary">➕ Tambah Pengguna Baru</a>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Hak Akses (Role)</th>
                    <th>Status</th>
                    <th>Masuk Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <strong><?= e($u['username']) ?></strong>
                            </td>
                            <td><?= e($u['nama_lengkap']) ?></td>
                            <td class="text-muted"><?= e($u['email']) ?></td>
                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="badge badge-primary">Administrator</span>
                                <?php elseif ($u['role'] === 'pengawas'): ?>
                                    <span class="badge badge-warning">Pengawas</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral">Operator</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($u['status'] === 'aktif'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted">
                                <?= !empty($u['last_login_at']) ? format_date($u['last_login_at'], 'd M Y H:i') : 'Belum pernah' ?>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="/admin/users/<?= $u['id'] ?>/edit" class="btn btn-outline btn-sm">
                                        Edit
                                    </a>
                                    <?php if ($u['id'] !== \App\Core\Auth::id()): ?>
                                        <form action="/admin/users/<?= $u['id'] ?>/delete" method="POST" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus akun pengguna <?= e($u['username']) ?>?">
                                                Hapus
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 3rem;">Belum ada pengguna.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <div class="text-muted">Halaman <?= $currentPage ?> dari <?= $totalPages ?> (Total <?= $total ?> pengguna)</div>
            <div class="pagination-pages">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/admin/users?page=<?= $i ?>" class="page-link <?= ($i === $currentPage) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
