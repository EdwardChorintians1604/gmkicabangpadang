<div class="container py-8 sm:py-12">
    <div class="max-w-4xl mx-auto animate__animated animate__fadeIn">
        <!-- Breadcrumb & Category -->
        <div class="flex items-center gap-2 mb-4 text-xs sm:text-sm">
            <a href="/berita" class="text-slate-500 hover:text-primary font-medium">&larr; Kembali ke Warta & Berita</a>
            <span class="text-slate-300">•</span>
            <span class="badge badge-primary"><?= e($article['kategori']) ?></span>
        </div>

        <!-- Article Title -->
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold leading-tight mb-4 text-slate-900" style="color: var(--primary);">
            <?= e($article['judul']) ?>
        </h1>

        <!-- Meta Info -->
        <div class="flex flex-wrap items-center gap-3 sm:gap-6 text-slate-500 mb-8 pb-4 border-b border-slate-200 text-xs sm:text-sm">
            <div>✍️ Ditulis oleh: <strong><?= e($article['penulis_nama'] ?? 'BPC GMKI Padang') ?></strong></div>
            <div>📅 <?= format_date($article['published_at'], 'd F Y') ?></div>
            <div>👁️ <?= (int)$article['views'] ?> kali dibaca</div>
        </div>

        <!-- Cover Image -->
        <?php if (!empty($article['gambar_sampul'])): ?>
            <div class="mb-10 rounded-2xl overflow-hidden shadow-lg border border-slate-200/80">
                <img src="/uploads/berita/<?= e($article['gambar_sampul']) ?>" alt="<?= e($article['judul']) ?>" class="w-full max-h-[480px] object-cover">
            </div>
        <?php endif; ?>

        <!-- Content Body -->
        <div class="article-body text-base sm:text-lg leading-relaxed text-slate-800 mb-12 space-y-4">
            <?= $article['konten'] ?>
        </div>

        <!-- Share & Tags Bar -->
        <div class="card rounded-2xl p-6 mb-12 bg-slate-50 border border-slate-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="font-bold text-slate-800" style="color: var(--primary);">
                    Ut Omnes Unum Sint — GMKI Cabang Padang
                </div>
                <div>
                    <a href="/berita" class="btn btn-outline btn-sm rounded-xl">Lihat Berita Lainnya &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Related News -->
        <?php if (!empty($related)): ?>
            <div>
                <h3 class="text-xl sm:text-2xl font-bold mb-6 text-slate-900">Berita Terkait Lainnya</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <?php foreach ($related as $rel): ?>
                        <div class="card news-card rounded-2xl shadow-sm hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 bg-white border border-slate-200/80 flex flex-col">
                            <div class="news-card-thumb relative overflow-hidden rounded-t-2xl">
                                <?php if (!empty($rel['gambar_sampul'])): ?>
                                    <img src="/uploads/berita/<?= e($rel['gambar_sampul']) ?>" alt="<?= e($rel['judul']) ?>" class="w-full h-36 object-cover">
                                <?php else: ?>
                                    <div class="w-full h-36 flex items-center justify-center bg-slate-800 text-white font-bold text-sm">
                                        GMKI Padang
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-4 flex flex-col flex-grow">
                                <div class="text-xs text-slate-400 mb-1">
                                    <?= format_date($rel['published_at']) ?>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-slate-800 hover:text-primary transition-colors leading-snug">
                                    <a href="/berita/<?= e($rel['slug']) ?>"><?= e($rel['judul']) ?></a>
                                </h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
