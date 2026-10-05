<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Manajemen Warta & Berita</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Publikasikan warta kegiatan cabang, opini kader, dan pengumuman resmi.
        </p>
    </div>
    <div>
        <a href="/admin/berita/create" class="btn btn-primary">➕ Tulis Berita Baru</a>
    </div>
</div>

<!-- Filter Bar -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="/admin/berita" method="GET" class="flex items-center justify-between gap-4" style="flex-wrap: wrap;">
        <div class="flex items-center gap-3" style="flex-grow: 1; min-width: 250px;">
            <input type="text" name="q" class="form-control" placeholder="Cari judul berita..." value="<?= e($search ?? '') ?>">
        </div>
        <div class="flex items-center gap-2">
            <select name="status" class="form-control" style="width: auto;">
                <option value="">Semua Status</option>
                <option value="published" <?= ($status === 'published') ? 'selected' : '' ?>>Diterbitkan</option>
                <option value="draft" <?= ($status === 'draft') ? 'selected' : '' ?>>Draft</option>
            </select>
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if (!empty($search) || !empty($status)): ?>
                <a href="/admin/berita" class="btn btn-outline">Reset</a>
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
                    <th>Sampul</th>
                    <th>Judul Berita</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Dibaca</th>
                    <th>Tanggal Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $item): ?>
                        <tr>
                            <td style="width: 80px;">
                                <?php if (!empty($item['gambar_sampul'])): ?>
                                    <img src="/uploads/berita/<?= e($item['gambar_sampul']) ?>" alt="Sampul" style="width: 64px; height: 44px; object-fit: cover; border-radius: var(--radius-sm);">
                                <?php else: ?>
                                    <div style="width: 64px; height: 44px; background: var(--bg-subtle); display:flex; align-items:center; justify-content:center; font-size:0.75rem; color:var(--text-muted); border-radius: var(--radius-sm);">
                                        No img
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/berita/<?= e($item['slug']) ?>" target="_blank" style="font-weight: 700; color: var(--primary);">
                                    <?= e($item['judul']) ?>
                                </a>
                            </td>
                            <td><span class="badge badge-primary"><?= e($item['kategori']) ?></span></td>
                            <td class="text-muted"><?= e($item['penulis_nama'] ?? 'BPC GMKI Padang') ?></td>
                            <td>
                                <?php if ($item['status'] === 'published'): ?>
                                    <span class="badge badge-success">Terbit</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>👁️ <?= (int)$item['views'] ?></td>
                            <td class="text-muted"><?= format_date($item['published_at'] ?? $item['created_at']) ?></td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="/admin/berita/<?= $item['id'] ?>/edit" class="btn btn-outline btn-sm">Edit</a>
                                    <?php if (can('news.delete')): ?>
                                        <form action="/admin/berita/<?= $item['id'] ?>/delete" method="POST" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus berita '<?= e($item['judul']) ?>'?">
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
                        <td colspan="8" class="text-center text-muted" style="padding: 3rem;">Belum ada berita yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <div class="text-muted">Menampilkan halaman <?= $currentPage ?> dari <?= $totalPages ?> (Total <?= $total ?> berita)</div>
            <div class="pagination-pages">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/admin/berita?page=<?= $i ?><?= !empty($status) ? '&status='.urlencode($status) : '' ?><?= !empty($search) ? '&q='.urlencode($search) : '' ?>" 
                       class="page-link <?= ($i === $currentPage) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
