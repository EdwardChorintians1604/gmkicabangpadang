<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 m-0">Pantauan Warta & Publikasi Cabang</h1>
            <span class="badge badge-warning text-xs">BACA-SAJA</span>
        </div>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Pemantauan artikel berita, press release organisasi, dan publikasi resmi GMKI Cabang Padang.
        </p>
    </div>
    <div class="flex gap-2 flex-shrink-0">
        <span class="badge badge-info p-2 text-xs sm:text-sm">
            Total: <strong><?= number_format($total) ?></strong> Artikel
        </span>
    </div>
</div>

<!-- Filter Bar -->
<div class="card mb-6 p-4 sm:p-5">
    <form action="/pengawas/berita" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <div class="flex-grow min-w-0">
            <input type="text" name="q" class="form-control w-full" placeholder="Cari judul warta atau artikel..." value="<?= e($search ?? '') ?>">
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <select name="status" class="form-control flex-1 sm:w-auto">
                <option value="">Semua Status Publikasi</option>
                <option value="published" <?= ($status === 'published') ? 'selected' : '' ?>>Diterbitkan</option>
                <option value="draft" <?= ($status === 'draft') ? 'selected' : '' ?>>Draft Pengurus</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <?php if (!empty($search) || !empty($status)): ?>
                <a href="/pengawas/berita" class="btn btn-outline">Reset</a>
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
                    <th style="width: 80px;">Sampul</th>
                    <th>Judul Warta</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Dibaca</th>
                    <th>Tanggal Terbit</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['gambar_sampul'])): ?>
                                    <img src="/uploads/berita/<?= e($item['gambar_sampul']) ?>" alt="Sampul" style="width: 64px; height: 44px; object-fit: cover; border-radius: var(--radius-sm);">
                                <?php else: ?>
                                    <div style="width: 64px; height: 44px; background: var(--bg-subtle); display:flex; align-items:center; justify-content:center; font-size:0.75rem; color:var(--text-muted); border-radius: var(--radius-sm);">
                                        No img
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong style="color: var(--primary);"><?= e($item['judul']) ?></strong>
                            </td>
                            <td><span class="badge badge-neutral"><?= e($item['kategori'] ?? 'Umum') ?></span></td>
                            <td><?= e($item['nama_penulis'] ?? 'Admin') ?></td>
                            <td>
                                <?php if ($item['status'] === 'published'): ?>
                                    <span class="badge badge-success">Terbit</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td><?= number_format((int)($item['views'] ?? 0)) ?>x</td>
                            <td class="text-muted" style="font-size: 0.8125rem;">
                                <?= format_date($item['published_at'] ?? $item['created_at'], 'd M Y') ?>
                            </td>
                            <td style="text-align: right;">
                                <a href="/pengawas/berita/<?= $item['id'] ?>" class="btn btn-outline btn-sm flex items-center gap-1" style="display: inline-flex;" title="Baca Rincian Warta">
                                    <?= svg_icon('eye', 14) ?>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem 1rem;" class="text-muted">
                            Tidak ada warta atau berita yang sesuai dengan filter pencarian.
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
            'baseUrl' => '/pengawas/berita',
            'queryParams' => array_filter(['q' => $search, 'status' => $status])
        ]) ?>
    <?php endif; ?>
</div>
