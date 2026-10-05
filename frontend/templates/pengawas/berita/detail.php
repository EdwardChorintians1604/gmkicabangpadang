<div style="max-width: 900px; margin: 0 auto;">
    <!-- Breadcrumb & Back -->
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div class="flex items-center gap-2" style="font-size: 0.875rem; margin-bottom: 0.25rem;">
                <a href="/pengawas/berita" class="text-muted">&larr; Kembali ke Pantauan Warta</a>
            </div>
            <div class="flex items-center gap-2">
                <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin: 0;">
                    Rincian Warta Organisasi
                </h1>
                <span class="badge badge-warning">BACA-SAJA</span>
            </div>
        </div>
        <div class="flex gap-2">
            <?php if ($article['status'] === 'published'): ?>
                <a href="/berita/<?= e($article['slug']) ?>" target="_blank" class="btn btn-outline btn-sm flex items-center gap-1">
                    <?= svg_icon('external', 14) ?>
                    <span>Lihat di Website</span>
                </a>
            <?php endif; ?>
            <button onclick="window.print()" class="btn btn-secondary btn-sm flex items-center gap-1">
                <?= svg_icon('printer', 14) ?>
                <span>Cetak Warta</span>
            </button>
        </div>
    </div>

    <!-- Article Content Card -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-body" style="padding: 2.5rem;">
            <!-- Category & Status -->
            <div class="flex items-center gap-2" style="margin-bottom: 1rem;">
                <span class="badge badge-info"><?= e($article['kategori'] ?? 'Umum') ?></span>
                <?php if ($article['status'] === 'published'): ?>
                    <span class="badge badge-success">Diterbitkan</span>
                <?php else: ?>
                    <span class="badge badge-warning">Draft</span>
                <?php endif; ?>
                <span class="text-muted" style="font-size: 0.85rem;">
                    Dibaca <?= number_format((int)($article['views'] ?? 0)) ?> kali
                </span>
            </div>

            <!-- Title -->
            <h1 style="font-size: 2rem; font-weight: 800; line-height: 1.3; color: var(--text-main); margin-bottom: 1rem;">
                <?= e($article['judul']) ?>
            </h1>

            <!-- Meta info -->
            <div class="flex items-center gap-4 text-muted" style="font-size: 0.875rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    Penulis: <strong><?= e($article['nama_penulis'] ?? 'Admin') ?></strong>
                </div>
                <div>•</div>
                <div>
                    Terbit: <?= format_date($article['published_at'] ?? $article['created_at'], 'd F Y, H:i') ?> WIB
                </div>
            </div>

            <!-- Cover Image -->
            <?php if (!empty($article['gambar_sampul'])): ?>
                <div style="margin-bottom: 2rem; border-radius: var(--radius-md); overflow: hidden; max-height: 450px;">
                    <img src="/uploads/berita/<?= e($article['gambar_sampul']) ?>" alt="<?= e($article['judul']) ?>" style="width: 100%; height: auto; object-fit: cover;">
                </div>
            <?php endif; ?>

            <!-- Ringkasan -->
            <?php if (!empty($article['ringkasan'])): ?>
                <div style="font-size: 1.1rem; line-height: 1.6; color: var(--text-muted); font-style: italic; margin-bottom: 2rem; padding: 1rem 1.5rem; background: var(--bg-subtle); border-left: 4px solid var(--primary); border-radius: var(--radius-sm);">
                    <?= e($article['ringkasan']) ?>
                </div>
            <?php endif; ?>

            <!-- Konten Lengkap -->
            <div class="article-body-content" style="line-height: 1.8; font-size: 1rem; color: var(--text-main);">
                <?= nl2br(e($article['konten'])) ?>
            </div>
        </div>
    </div>
</div>
