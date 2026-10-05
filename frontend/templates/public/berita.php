<div class="container py-8 sm:py-12">
    <!-- Page Header with Animate.css -->
    <div class="animate__animated animate__fadeInDown mb-10 text-center">
        <div class="section-tag uppercase text-amber-600 font-bold tracking-wider text-xs mb-2">Warta & Berita</div>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">Kabar GMKI Cabang Padang</h1>
        <p class="text-slate-600 max-w-2xl mx-auto text-sm sm:text-base font-medium">
            Informasi terkini seputar kegiatan kaderisasi, aksi pelayanan tiga medan layan, dan dinamika civitas cabang.
        </p>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card rounded-2xl shadow-sm bg-white mb-10 p-5 border border-slate-200/80 animate__animated animate__fadeIn">
        <form action="/berita" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <div class="flex-grow">
                <input type="text" name="q" class="form-control w-full rounded-xl" placeholder="Cari judul atau topik warta..." value="<?= e($search ?? '') ?>">
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                <select name="kategori" class="form-control rounded-xl">
                    <option value="">Semua Kategori</option>
                    <option value="Warta Cabang" <?= ($kategori === 'Warta Cabang') ? 'selected' : '' ?>>Warta Cabang</option>
                    <option value="Kaderisasi" <?= ($kategori === 'Kaderisasi') ? 'selected' : '' ?>>Kaderisasi</option>
                    <option value="Opini" <?= ($kategori === 'Opini') ? 'selected' : '' ?>>Opini</option>
                    <option value="Pengumuman" <?= ($kategori === 'Pengumuman') ? 'selected' : '' ?>>Pengumuman</option>
                </select>
                <button type="submit" class="btn btn-primary rounded-xl px-5">Cari</button>
                <?php if (!empty($search) || !empty($kategori)): ?>
                    <a href="/berita" class="btn btn-outline rounded-xl">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Articles Grid -->
    <?php if (!empty($news)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12 animate__animated animate__fadeInUp">
            <?php foreach ($news as $item): ?>
                <article class="card news-card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex flex-col h-full bg-white border border-slate-200/80">
                    <div class="news-card-thumb relative overflow-hidden rounded-t-2xl">
                        <?php if (!empty($item['gambar_sampul'])): ?>
                            <img src="/uploads/berita/<?= e($item['gambar_sampul']) ?>" alt="<?= e($item['judul']) ?>" class="w-full h-48 object-cover">
                        <?php else: ?>
                            <div class="w-full h-48 flex items-center justify-center bg-slate-800 text-white font-bold text-base">
                                GMKI Cabang Padang
                            </div>
                        <?php endif; ?>
                        <span class="news-card-category absolute top-3 left-3 bg-slate-900/90 text-white text-xs font-bold px-2.5 py-1 rounded shadow">
                            <?= e($item['kategori']) ?>
                        </span>
                    </div>
                    <div class="card-body p-5 flex flex-col flex-grow">
                        <div class="news-card-meta flex items-center gap-2 text-xs text-slate-500 mb-2">
                            <span>📅 <?= format_date($item['published_at']) ?></span>
                            <span>•</span>
                            <span>👁️ <?= (int)$item['views'] ?> views</span>
                        </div>
                        <h3 class="news-card-title text-base sm:text-lg font-bold mb-2 leading-snug">
                            <a href="/berita/<?= e($item['slug']) ?>" class="hover:text-primary transition-colors">
                                <?= e($item['judul']) ?>
                            </a>
                        </h3>
                        <p class="news-card-desc text-slate-600 text-sm mb-4 line-clamp-3 flex-grow leading-relaxed">
                            <?= e($item['ringkasan']) ?>
                        </p>
                        <div class="mt-auto pt-2">
                            <a href="/berita/<?= e($item['slug']) ?>" class="btn btn-outline btn-sm w-full sm:w-auto rounded-xl">Baca Selengkapnya &rarr;</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center gap-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/berita?page=<?= $i ?><?= !empty($kategori) ? '&kategori='.urlencode($kategori) : '' ?><?= !empty($search) ? '&q='.urlencode($search) : '' ?>" 
                       class="page-link rounded-lg <?= ($i === $currentPage) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="card text-center p-12 rounded-2xl bg-white shadow-sm max-w-lg mx-auto">
            <div class="text-4xl mb-3">📰</div>
            <p class="text-slate-500 font-medium">Tidak ada warta berita yang sesuai dengan kriteria pencarian.</p>
        </div>
    <?php endif; ?>
</div>
