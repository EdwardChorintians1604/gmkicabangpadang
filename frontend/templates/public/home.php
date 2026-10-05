<!-- Hero Section -->
<section class="hero-section relative overflow-hidden">
    <div class="container relative z-10">
        <div class="hero-content animate__animated animate__fadeIn">
            <div class="hero-badge animate__animated animate__pulse animate__infinite animate__slower inline-flex items-center gap-2">
                <span>🕊️</span>
                <span><?= e($profile['tema_periode'] ?? 'Ut Omnes Unum Sint') ?></span>
            </div>
            <h1 class="hero-title animate__animated animate__fadeInUp text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">
                Mempersiapkan Pemimpin Berintegritas & Berkarakter Kristus
            </h1>
            <p class="hero-subtitle animate__animated animate__fadeInUp text-base sm:text-lg text-slate-200">
                Selamat datang di portal resmi <strong>GMKI Cabang Padang</strong>. Wadah persekutuan dan kaderisasi mahasiswa Kristen di Kota Padang untuk melayani di tiga medan layan: Gereja, Perguruan Tinggi, dan Masyarakat.
            </p>
            <div class="hero-actions animate__animated animate__fadeInUp flex flex-wrap gap-4">
                <a href="/profil" class="btn btn-secondary btn-lg transform hover:-translate-y-1 transition-all duration-300">Pelajari Profil Cabang</a>
                <a href="/berita" class="btn btn-outline btn-lg transform hover:-translate-y-1 transition-all duration-300" style="color: #ffffff; border-color: rgba(255,255,255,0.4);">Warta Kegiatan &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Strip -->
<div class="container">
    <div class="stats-strip animate__animated animate__fadeInUp shadow-2xl rounded-2xl">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="stat-item p-2">
                <div class="stat-num text-3xl md:text-4xl font-extrabold text-amber-300"><?= (int)($summary['total_civitas'] ?? 0) ?>+</div>
                <div class="stat-label text-xs sm:text-sm font-semibold text-slate-200 mt-1">Total Civitas & Kader</div>
            </div>
            <div class="stat-item p-2">
                <div class="stat-num text-3xl md:text-4xl font-extrabold text-emerald-400"><?= (int)($summary['active_civitas'] ?? 0) ?></div>
                <div class="stat-label text-xs sm:text-sm font-semibold text-slate-200 mt-1">Anggota Aktif</div>
            </div>
            <div class="stat-item p-2">
                <div class="stat-num text-3xl md:text-4xl font-extrabold text-sky-300"><?= (int)($summary['total_komisariat'] ?? 0) ?></div>
                <div class="stat-label text-xs sm:text-sm font-semibold text-slate-200 mt-1">Komisariat Kampus</div>
            </div>
            <div class="stat-item p-2">
                <div class="stat-num text-3xl md:text-4xl font-extrabold text-amber-300"><?= (int)($summary['total_berita'] ?? 0) ?></div>
                <div class="stat-label text-xs sm:text-sm font-semibold text-slate-200 mt-1">Warta Terbit</div>
            </div>
        </div>
    </div>
</div>

<!-- Section: Visi, Misi, & Tri Panji -->
<section class="section py-16">
    <div class="container">
        <div class="section-title-wrap text-center max-w-2xl mx-auto mb-12">
            <div class="section-tag uppercase text-amber-600 font-bold tracking-wider text-xs mb-2">Landasan Perjuangan</div>
            <h2 class="section-title text-2xl md:text-4xl font-extrabold mb-3">Tri Panji & Panca Kegiatan</h2>
            <p class="section-desc text-slate-600 text-sm md:text-base">Membentuk kader mahasiswa Kristen yang berakar dalam iman dan berbuah dalam pengabdian nyata.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300" style="border-top: 5px solid #0f3d64;">
                <div class="card-body p-6">
                    <div style="font-size: 2.25rem; margin-bottom: 0.75rem;">⛪</div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Tinggi Iman</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Ketaatan dan ketakutan akan Tuhan, memupuk spiritualitas oikumenis yang teguh di tengah dinamika kampus dan masyarakat.
                    </p>
                </div>
            </div>
            <div class="card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300" style="border-top: 5px solid #d97706;">
                <div class="card-body p-6">
                    <div style="font-size: 2.25rem; margin-bottom: 0.75rem;">🎓</div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Tinggi Ilmu</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Keunggulan intelektual, daya kritis, pemikiran ilmiah, serta integritas moral dalam mengemban tugas akademik di perguruan tinggi.
                    </p>
                </div>
            </div>
            <div class="card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300" style="border-top: 5px solid #10b981;">
                <div class="card-body p-6">
                    <div style="font-size: 2.25rem; margin-bottom: 0.75rem;">🤝</div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Tinggi Pengabdian</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Keberpihakan nyata pada keadilan, perdamaian, dan kemanusiaan melalui aksi nyata bagi gereja, bangsa, dan persekutuan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Visi & Misi Card -->
        <div class="card rounded-2xl shadow-md overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-left: 6px solid var(--secondary);">
            <div class="card-body p-6 sm:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-2xl">🎯</span>
                            <h3 class="text-xl font-bold" style="color: var(--primary);">Visi GMKI</h3>
                        </div>
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                            <?= nl2br(e($profile['visi'] ?? 'Terwujudnya kedamaian, keadilan, kebenaran dan kesejahteraan bagi sesama manusia dan alam semesta berdasarkan kasih Yesus Kristus.')) ?>
                        </p>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-2xl">🚀</span>
                            <h3 class="text-xl font-bold" style="color: var(--primary);">Misi GMKI Cabang Padang</h3>
                        </div>
                        <div class="text-slate-700 text-sm sm:text-base leading-relaxed">
                            <?= nl2br(e($profile['misi'] ?? '1. Menumbuhkan kesadaran iman dan karakter Kristiani.')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section: Warta Terbaru -->
<section class="section py-16" style="background: #f1f5f9;">
    <div class="container">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
            <div>
                <div class="section-tag uppercase text-amber-600 font-bold tracking-wider text-xs mb-1">Kabar & Informasi</div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Warta Cabang Terbaru</h2>
            </div>
            <a href="/berita" class="btn btn-outline hover:bg-slate-200 transition-all duration-200">Lihat Semua Berita &rarr;</a>
        </div>

        <?php if (!empty($latestNews)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($latestNews as $item): ?>
                    <article class="card news-card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex flex-col h-full bg-white">
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
                                <span>👁️ <?= (int)$item['views'] ?> kali dilihat</span>
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
                                <a href="/berita/<?= e($item['slug']) ?>" class="btn btn-outline btn-sm w-full sm:w-auto">Baca Selengkapnya &rarr;</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card text-center p-12 rounded-2xl bg-white shadow-sm">
                <p class="text-slate-500">Belum ada warta berita yang diterbitkan saat ini.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="section py-16">
    <div class="container">
        <div class="card rounded-3xl shadow-2xl p-8 sm:p-12 text-center border-none animate__animated animate__fadeInUp" style="background: linear-gradient(135deg, #071e33 0%, #0f3d64 60%, #1e5687 100%); color: #ffffff;">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white mb-4">Bergabung Bersama GMKI Cabang Padang</h2>
            <p class="text-slate-200 max-w-2xl mx-auto mb-8 text-sm sm:text-base leading-relaxed">
                Apakah kamu mahasiswa Kristen di Kota Padang? Ayo bergabung dalam persekutuan, bertumbuh dalam iman dan pengetahuan melalui Masa Perkenalan Calon Anggota (Maperca).
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/kontak" class="btn btn-secondary btn-lg transform hover:-translate-y-1 transition-all duration-300">Hubungi Sekretariat</a>
                <a href="/struktur-organisasi" class="btn btn-outline btn-lg transform hover:-translate-y-1 transition-all duration-300" style="color: #ffffff; border-color: rgba(255,255,255,0.4);">Kenali Pengurus BPC</a>
            </div>
        </div>
    </div>
</section>
