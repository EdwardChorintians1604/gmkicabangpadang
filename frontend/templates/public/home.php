<!-- Hero Section -->
<section class="hero-section relative overflow-hidden">
    <div class="container relative z-10">
        <div class="hero-content">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                <!-- Left: Headline & Call-to-actions -->
                <div class="lg:col-span-7 animate__animated animate__fadeIn">
                    <div
                        class="hero-badge animate__animated animate__pulse animate__infinite animate__slower inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-amber-300 text-xs sm:text-sm font-semibold shadow-sm">
                        <i class="bi bi-peace text-base sm:text-lg text-amber-300 flex-shrink-0"></i>
                        <span><?= e($profile['tema_periode'] ?? 'Ut Omnes Unum Sint') ?></span>
                    </div>

                    <h1 class="hero-title animate__animated animate__fadeInUp font-extrabold tracking-tight">
                        Melahirkan Pemimpin <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-400">Berintegritas</span>
                        &amp; Berkarakter Kristus
                    </h1>

                    <p class="hero-subtitle animate__animated animate__fadeInUp text-slate-200">
                        Selamat datang di portal resmi <strong>GMKI Cabang Padang</strong>. Wadah persekutuan,
                        kesaksian, dan kaderisasi
                        mahasiswa Kristen di Kota Padang untuk melayani di tiga medan layan: Gereja, Perguruan Tinggi,
                        dan
                        Masyarakat.
                    </p>

                    <div class="hero-actions animate__animated animate__fadeInUp flex flex-wrap gap-3 sm:gap-4 mb-6">
                        <a href="/profil"
                            class="btn btn-secondary btn-lg w-full sm:w-auto text-center transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center justify-center gap-2 shadow-lg shadow-amber-950/30">
                            <i class="bi bi-info-circle text-lg"></i>
                            <span>Pelajari Profil Cabang</span>
                        </a>
                        <a href="/berita"
                            class="btn btn-lg hero-btn-glass w-full sm:w-auto text-center transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center justify-center gap-2 group">
                            <span>Warta Kegiatan</span>
                            <i
                                class="bi bi-arrow-right text-base transition-transform duration-200 group-hover:translate-x-1"></i>
                        </a>
                    </div>

                    <!-- Tri Medan Highlights Badge -->
                    <div class="pt-4 border-t border-white/10 flex flex-wrap items-center gap-2 text-xs text-slate-300">
                        <span class="text-slate-400 font-semibold mr-1 flex items-center gap-1.5">
                            <i class="bi bi-layers-fill text-amber-300"></i> Medan Layan:
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 backdrop-blur-xs border border-white/10 font-medium text-slate-200">
                            <i class="bi bi-building text-amber-300"></i> Gereja
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 backdrop-blur-xs border border-white/10 font-medium text-slate-200">
                            <i class="bi bi-mortarboard-fill text-sky-300"></i> Perguruan Tinggi
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 backdrop-blur-xs border border-white/10 font-medium text-slate-200">
                            <i class="bi bi-people-fill text-emerald-300"></i> Masyarakat
                        </span>
                    </div>
                </div>

                <!-- Right: Quick Portal Registrasi Civitas & Maper Card -->
                <div class="lg:col-span-5 animate__animated animate__fadeInRight">
                    <div class="relative group">
                        <!-- Glow Accent Behind Card -->
                        <div
                            class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-amber-500/20 via-blue-500/20 to-indigo-500/30 blur-xl opacity-75 group-hover:opacity-100 transition duration-500">
                        </div>

                        <div
                            class="relative rounded-3xl bg-slate-900/75 backdrop-blur-xl border border-white/20 p-5 sm:p-7 shadow-2xl text-white w-full">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-400/20 text-amber-300 border border-amber-300/30">
                                    <i class="bi bi-lightning-charge-fill text-amber-300"></i> Pendaftaran Online
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-300 bg-emerald-950/60 px-2.5 py-1 rounded-full border border-emerald-400/30">
                                    <span class="relative flex h-2 w-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    Pendaftaran Buka
                                </span>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white mb-1.5">
                                Portal Registrasi Mandiri
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 mb-5 leading-relaxed">
                                Pilih jalur pendaftaran resmi sesuai status keanggotaan Anda di GMKI Cabang Padang:
                            </p>

                            <!-- Dual Choices -->
                            <div class="space-y-3.5">
                                <!-- Option 1: Calon Anggota (Maperca) -->
                                <a href="/register_for_civitas/maperca"
                                    class="group/item flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/15 hover:border-amber-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-amber-500/10 no-underline">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/25 to-amber-600/35 border border-amber-400/40 flex items-center justify-center flex-shrink-0 text-amber-300 group-hover/item:scale-105 group-hover/item:bg-amber-500 group-hover/item:text-slate-950 transition-all duration-300 shadow-sm">
                                        <i class="bi bi-mortarboard-fill text-2xl"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-0.5">
                                            <h3
                                                class="text-sm sm:text-base font-bold text-white group-hover/item:text-amber-300 transition-colors">
                                                Calon Anggota (Maperca)
                                            </h3>
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30">Kader
                                                Baru</span>
                                        </div>
                                        <p class="text-xs text-slate-300 line-clamp-1 leading-relaxed">
                                            Untuk mahasiswa Kristen baru yang ingin mengikuti Masa Perkenalan.
                                        </p>
                                    </div>
                                    <div
                                        class="text-slate-400 group-hover/item:text-amber-300 group-hover/item:translate-x-1 transition-all flex-shrink-0 ml-1">
                                        <i class="bi bi-chevron-right text-base"></i>
                                    </div>
                                </a>

                                <!-- Option 2: Civitas & Anggota Terdaftar -->
                                <a href="/register_for_civitas/member"
                                    class="group/item flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/15 hover:border-sky-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-sky-500/10 no-underline">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/25 to-indigo-600/35 border border-sky-400/40 flex items-center justify-center flex-shrink-0 text-sky-300 group-hover/item:scale-105 group-hover/item:bg-sky-500 group-hover/item:text-slate-950 transition-all duration-300 shadow-sm">
                                        <i class="bi bi-person-vcard-fill text-2xl"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-0.5">
                                            <h3
                                                class="text-sm sm:text-base font-bold text-white group-hover/item:text-sky-300 transition-colors">
                                                Civitas &amp; Anggota Cabang
                                            </h3>
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-400/20 text-sky-300 border border-sky-400/30">Anggota
                                                Sah</span>
                                        </div>
                                        <p class="text-xs text-slate-300 line-clamp-1 leading-relaxed">
                                            Pemutakhiran data civitas, verifikasi KTA &amp; pangkalan data cabang.
                                        </p>
                                    </div>
                                    <div
                                        class="text-slate-400 group-hover/item:text-sky-300 group-hover/item:translate-x-1 transition-all flex-shrink-0 ml-1">
                                        <i class="bi bi-chevron-right text-base"></i>
                                    </div>
                                </a>
                            </div>

                            <!-- Card Footer -->
                            <div
                                class="mt-4 pt-3.5 border-t border-white/10 flex items-center justify-between text-[11px] text-slate-300 flex-wrap gap-2">
                                <span class="flex items-center gap-1.5 text-slate-300">
                                    <i class="bi bi-shield-check text-emerald-400 text-sm"></i>
                                    Basis Data BPC Resmi
                                </span>
                                <a href="/login"
                                    class="text-amber-300 hover:text-amber-200 font-semibold inline-flex items-center gap-1 transition-colors no-underline">
                                    <span>Sudah berakun? Masuk</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
</section>

<!-- Stats Strip -->
<div class="container">
    <div class="stats-strip animate__animated animate__fadeInUp shadow-2xl rounded-2xl">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 text-center">
            <div class="stat-item p-2 sm:p-3 flex flex-col items-center justify-center">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center mb-2 shadow-inner">
                    <i class="bi bi-people-fill text-xl"></i>
                </div>
                <div class="stat-num text-3xl md:text-4xl font-extrabold text-amber-300">
                    <?= (int) ($summary['total_civitas'] ?? 0) ?>+
                </div>
                <div class="stat-label text-xs sm:text-sm font-semibold text-slate-200 mt-1">Total Civitas & Kader</div>
            </div>
            <div class="stat-item p-2 sm:p-3 flex flex-col items-center justify-center">
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-400/20 text-emerald-400 flex items-center justify-center mb-2 shadow-inner">
                    <i class="bi bi-person-check-fill text-xl"></i>
                </div>
                <div class="stat-num text-3xl md:text-4xl font-extrabold text-emerald-400">
                    <?= (int) ($summary['active_civitas'] ?? 0) ?>
                </div>
                <div class="stat-label text-xs sm:text-sm font-semibold text-slate-200 mt-1">Anggota Aktif</div>
            </div>
            <div class="stat-item p-2 sm:p-3 flex flex-col items-center justify-center">
                <div
                    class="w-10 h-10 rounded-xl bg-sky-400/20 text-sky-300 flex items-center justify-center mb-2 shadow-inner">
                    <i class="bi bi-buildings-fill text-xl"></i>
                </div>
                <div class="stat-num text-3xl md:text-4xl font-extrabold text-sky-300">
                    <?= (int) ($summary['total_komisariat'] ?? 0) ?>
                </div>
                <div class="stat-label text-xs sm:text-sm font-semibold text-slate-200 mt-1">Komisariat Kampus</div>
            </div>
            <div class="stat-item p-2 sm:p-3 flex flex-col items-center justify-center">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center mb-2 shadow-inner">
                    <i class="bi bi-newspaper text-xl"></i>
                </div>
                <div class="stat-num text-3xl md:text-4xl font-extrabold text-amber-300">
                    <?= (int) ($summary['total_berita'] ?? 0) ?>
                </div>
                <div class="stat-label text-xs sm:text-sm font-semibold text-slate-200 mt-1">Warta Terbit</div>
            </div>
        </div>
    </div>
</div>

<!-- Section: Visi, Misi, & Tri Panji -->
<section class="section py-16">
    <div class="container">
        <div class="section-title-wrap text-center max-w-2xl mx-auto mb-12 animate-on-scroll" data-animate="fadeInDown">
            <div
                class="section-tag uppercase text-amber-600 font-bold tracking-wider text-xs mb-2 inline-flex items-center gap-1.5">
                <i class="bi bi-shield-shaded"></i>
                <span>Landasan Perjuangan</span>
            </div>
            <h2 class="section-title text-2xl md:text-4xl font-extrabold mb-3">Tri Panji & Panca Kegiatan</h2>
            <p class="section-desc text-slate-600 text-sm md:text-base">Membentuk kader mahasiswa Kristen yang berakar
                dalam iman dan berbuah dalam pengabdian nyata.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 animate-on-scroll group"
                data-animate="fadeInUp" data-delay="100ms" style="border-top: 5px solid #0f3d64;">
                <div class="card-body p-6">
                    <div
                        class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0f3d64] border border-blue-200 flex items-center justify-center mb-3 shadow-xs group-hover:bg-[#0f3d64] group-hover:text-amber-300 transition-all duration-300">
                        <i class="bi bi-book-half text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Tinggi Iman</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Ketaatan dan ketakutan akan Tuhan, memupuk spiritualitas oikumenis yang teguh di tengah dinamika
                        kampus dan masyarakat.
                    </p>
                </div>
            </div>
            <div class="card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 animate-on-scroll group"
                data-animate="fadeInUp" data-delay="250ms" style="border-top: 5px solid #d97706;">
                <div class="card-body p-6">
                    <div
                        class="w-12 h-12 rounded-2xl bg-amber-50 text-[#d97706] border border-amber-200 flex items-center justify-center mb-3 shadow-xs group-hover:bg-[#d97706] group-hover:text-white transition-all duration-300">
                        <i class="bi bi-mortarboard-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Tinggi Ilmu</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Keunggulan intelektual, daya kritis, pemikiran ilmiah, serta integritas moral dalam mengemban
                        tugas akademik di perguruan tinggi.
                    </p>
                </div>
            </div>
            <div class="card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 animate-on-scroll group"
                data-animate="fadeInUp" data-delay="400ms" style="border-top: 5px solid #10b981;">
                <div class="card-body p-6">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#10b981] border border-emerald-200 flex items-center justify-center mb-3 shadow-xs group-hover:bg-[#10b981] group-hover:text-white transition-all duration-300">
                        <i class="bi bi-globe2 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Tinggi Pengabdian</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Keberpihakan nyata pada keadilan, perdamaian, dan kemanusiaan melalui aksi nyata bagi gereja,
                        bangsa, dan persekutuan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Visi & Misi Card -->
        <div class="card rounded-2xl shadow-md overflow-hidden animate-on-scroll" data-animate="fadeInUp"
            data-delay="150ms"
            style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-left: 6px solid var(--secondary);">
            <div class="card-body p-6 sm:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-100 text-[#0f3d64] flex items-center justify-center shadow-xs">
                                <i class="bi bi-bullseye text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#0f3d64]">Visi GMKI</h3>
                        </div>
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                            <?= nl2br(e($profile['visi'] ?? 'Terwujudnya kedamaian, keadilan, kebenaran dan kesejahteraan bagi sesama manusia dan alam semesta berdasarkan kasih Yesus Kristus.')) ?>
                        </p>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-amber-100 text-[#d97706] flex items-center justify-center shadow-xs">
                                <i class="bi bi-rocket-takeoff-fill text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#0f3d64]">Misi GMKI Cabang Padang</h3>
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
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4 animate-on-scroll"
            data-animate="fadeInDown">
            <div>
                <div
                    class="section-tag uppercase text-amber-600 font-bold tracking-wider text-xs mb-1 inline-flex items-center gap-1.5">
                    <i class="bi bi-broadcast"></i>
                    <span>Kabar & Informasi</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Warta Cabang Terbaru</h2>
            </div>
            <a href="/berita"
                class="btn btn-outline hover:bg-slate-200 transition-all duration-200 inline-flex items-center gap-2 group">
                <span>Lihat Semua Berita</span>
                <i class="bi bi-arrow-right transition-transform duration-200 group-hover:translate-x-1"></i>
            </a>
        </div>

        <?php if (!empty($latestNews)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($latestNews as $idx => $item): ?>
                    <article
                        class="card news-card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex flex-col h-full bg-white animate-on-scroll"
                        data-animate="fadeInUp" data-delay="<?= (100 + ($idx * 150)) ?>ms">
                        <div class="news-card-thumb relative overflow-hidden rounded-t-2xl">
                            <?php if (!empty($item['gambar_sampul'])): ?>
                                <img src="/uploads/berita/<?= e($item['gambar_sampul']) ?>" alt="<?= e($item['judul']) ?>"
                                    class="w-full h-48 object-cover">
                            <?php else: ?>
                                <div
                                    class="w-full h-48 flex flex-col items-center justify-center bg-slate-800 text-white font-bold text-base gap-2">
                                    <i class="bi bi-newspaper text-3xl text-slate-400"></i>
                                    <span>GMKI Cabang Padang</span>
                                </div>
                            <?php endif; ?>
                            <span
                                class="news-card-category absolute top-3 left-3 bg-slate-900/90 text-white text-xs font-bold px-2.5 py-1 rounded shadow inline-flex items-center gap-1.5 backdrop-blur-xs">
                                <i class="bi bi-tag-fill text-[10px] text-amber-400"></i>
                                <span><?= e($item['kategori']) ?></span>
                            </span>
                        </div>
                        <div class="card-body p-5 flex flex-col flex-grow">
                            <div class="news-card-meta flex items-center gap-3 text-xs text-slate-500 mb-2 flex-wrap">
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="bi bi-calendar3 text-amber-600"></i>
                                    <span><?= format_date($item['published_at']) ?></span>
                                </span>
                                <span>•</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="bi bi-eye text-sky-600"></i>
                                    <span><?= (int) $item['views'] ?> kali dilihat</span>
                                </span>
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
                                <a href="/berita/<?= e($item['slug']) ?>"
                                    class="btn btn-outline btn-sm w-full sm:w-auto inline-flex items-center justify-center gap-1.5 group">
                                    <span>Baca Selengkapnya</span>
                                    <i
                                        class="bi bi-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card text-center p-12 rounded-2xl bg-white shadow-sm animate-on-scroll" data-animate="fadeInUp">
                <div
                    class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <i class="bi bi-newspaper text-3xl"></i>
                </div>
                <p class="text-slate-500">Belum ada warta berita yang diterbitkan saat ini.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="section py-16">
    <div class="container">
        <div class="card rounded-3xl shadow-2xl p-8 sm:p-12 text-center border-none animate-on-scroll"
            data-animate="fadeInUp"
            style="background: linear-gradient(135deg, #071e33 0%, #0f3d64 60%, #1e5687 100%); color: #ffffff;">
            <div
                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 text-amber-300 border border-white/15 text-xs font-semibold mb-4 backdrop-blur-md">
                <i class="bi bi-stars text-sm"></i>
                <span>Panggilan Pelayanan</span>
            </div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white mb-4">Bergabung Bersama GMKI Cabang
                Padang</h2>
            <p class="text-slate-200 max-w-2xl mx-auto mb-8 text-sm sm:text-base leading-relaxed">
                Apakah kamu mahasiswa Kristen di Kota Padang? Ayo bergabung dalam persekutuan, bertumbuh dalam iman dan
                pengetahuan melalui Masa Perkenalan Calon Anggota (Maperca).
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-4 max-w-xl mx-auto w-full">
                <a href="/kontak"
                    class="btn btn-secondary btn-lg w-full sm:w-auto min-w-0 sm:min-w-[180px] text-center transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center justify-center gap-2">
                    <i class="bi bi-telephone-fill"></i>
                    <span>Hubungi Sekretariat</span>
                </a>
                <a href="/struktur-organisasi"
                    class="btn btn-secondary btn-lg greetings w-full sm:w-auto min-w-0 sm:min-w-[180px] text-center transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center justify-center gap-2">
                    <i class="bi bi-people-fill"></i>
                    <span>Kenali Pengurus BPC</span>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .greetings {
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.4);
        background-color: #d97706;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .greetings:hover {
        background-color: #b45309;
    }
</style>