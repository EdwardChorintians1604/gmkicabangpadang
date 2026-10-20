<div class="container py-8 sm:py-12">

    <!-- PAGE HEADER -->
    <div class="animate__animated animate__fadeInDown mb-14 text-center relative">
        <div class="mx-auto mb-5 flex items-center justify-center gap-3">
            <span class="block h-px w-12 bg-gradient-to-r from-transparent to-amber-400 rounded-full"></span>
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest bg-gradient-to-r from-amber-50 to-amber-100 text-amber-700 border border-amber-200/80 shadow-sm">
                <span>⚜️</span>
                <span>Kepemimpinan Cabang</span>
            </div>
            <span class="block h-px w-12 bg-gradient-to-l from-transparent to-amber-400 rounded-full"></span>
        </div>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-3">
            Struktur BPC <span style="color: var(--primary);">GMKI Cabang Padang</span>
        </h1>
        <p class="text-slate-500 max-w-xl mx-auto text-sm sm:text-base font-medium leading-relaxed">
            Badan Pengurus Cabang (BPC) — mengabdi dengan integritas,
            kerendahan hati, dan keteladanan di setiap medan pelayanan.
        </p>
    </div>

    <!-- OFFICER CARDS -->
    <?php if (!empty($structure)): ?>
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 animate__animated animate__fadeInUp">
            <?php foreach ($structure as $officer): ?>
                <div
                    class="bpc-officer-card card rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 bg-white text-center border border-slate-200/70 flex flex-col items-center justify-between overflow-hidden">
                    <div class="w-full h-1.5 bg-gradient-to-r from-primary via-blue-500 to-amber-400"></div>
                    <div class="p-6 flex flex-col items-center flex-1">
                        <div
                            class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-white shadow-md ring-2 ring-primary/20 bg-slate-100">
                            <?php if (!empty($officer['foto'])): ?>
                                <img src="/uploads/organisasi/<?= e($officer['foto']) ?>" alt="<?= e($officer['nama']) ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <img src="<?= asset('images/default-profile.png') ?>" alt="<?= e($officer['nama']) ?>"
                                    class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-1 leading-snug"><?= e($officer['nama']) ?>
                        </h3>
                        <div class="text-[11px] sm:text-xs font-bold text-amber-600 mb-3 uppercase tracking-wide">
                            <?= e($officer['jabatan']) ?>
                        </div>
                        <div
                            class="mt-auto inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-semibold">
                            <span>🗓️</span>
                            <span><?= e($officer['periode']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card text-center p-14 rounded-2xl bg-white shadow-sm max-w-lg mx-auto border border-slate-200/70">
            <div class="text-5xl mb-4">👥</div>
            <h3 class="text-base font-bold text-slate-700 mb-1">Belum Ada Data Pengurus</h3>
            <p class="text-sm text-slate-400 font-medium">Data struktur kepengurusan belum dimasukkan ke dalam sistem.</p>
        </div>
    <?php endif; ?>

    <!-- DETAIL BPC SECTION -->
    <section class="mt-20 sm:mt-28" id="detail-bpc">

        <!-- Section divider -->
        <div class="flex items-center gap-4 mb-12 animate-on-scroll" data-animate="fadeIn">
            <span class="flex-1 h-px bg-gradient-to-r from-slate-200 to-transparent"></span>
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-extrabold uppercase tracking-widest bg-slate-900 text-white shadow-md">
                <span>🏛️</span>
                <span>Tata Kelola &amp; Kepemimpinan</span>
            </div>
            <span class="flex-1 h-px bg-gradient-to-l from-slate-200 to-transparent"></span>
        </div>

        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10 animate-on-scroll" data-animate="fadeInDown">
            <h2
                class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 mb-2.5 tracking-tight leading-snug">
                Mengenal Tugas, Fungsi &amp; Peran<br>
                <span class="bg-gradient-to-r from-primary to-blue-500 bg-clip-text text-transparent">Fungsionaris
                    BPC</span>
            </h2>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed mt-2">
                Badan Pengurus Cabang (BPC) GMKI Cabang Padang adalah lembaga kepemimpinan eksekutif
                tertinggi di tingkat cabang yang mengemban mandat <em>Konperensi Cabang</em> (Konpercab)
                dan Pengurus Pusat (PP) GMKI. BPC memimpin dinamika organisasi, membina kader mahasiswa Kristen,
                serta mewujudkan Tri Panji di Tiga Medan Pelayanan.
            </p>
        </div>

        <!-- TIGA MEDAN PELAYANAN -->
        <div class="mb-16">
            <div class="text-center mb-8 animate-on-scroll" data-animate="fadeInDown">
                <span
                    class="inline-block px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider border border-blue-100 mb-3">Tri
                    Medan Layan GMKI</span>
                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">Tiga Medan Pelayanan BPC GMKI Padang</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Seluruh gerak langkah dan program fungsionaris BPC
                    bermuara pada kesaksian di tiga ranah historis GMKI.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div
                    class="bpc-medan-card group relative rounded-2xl overflow-hidden border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-slate-50 p-6 shadow-sm hover:shadow-lg transition-all duration-300 animate-on-scroll"
                    data-animate="fadeInUp" data-delay="100ms">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-blue-300"></div>
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl mb-4 shadow-sm group-hover:scale-110 transition-transform duration-300">
                        ⛪</div>
                    <h4 class="text-base sm:text-lg font-extrabold text-slate-900 mb-2">1. Medan Gereja</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">Membina persatuan oikumenis mahasiswa
                        Kristen lintas denominasi, berkontribusi aktif dalam pembaharuan spiritualitas gereja, serta
                        memperkokoh amanat Kristus <em>Ut Omnes Unum Sint</em> (Supaya Mereka Semua Menjadi Satu).</p>
                </div>
                <div
                    class="bpc-medan-card group relative rounded-2xl overflow-hidden border border-amber-100 bg-gradient-to-br from-amber-50 via-white to-slate-50 p-6 shadow-sm hover:shadow-lg transition-all duration-300 animate-on-scroll"
                    data-animate="fadeInUp" data-delay="250ms">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-amber-300"></div>
                    <div
                        class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl mb-4 shadow-sm group-hover:scale-110 transition-transform duration-300">
                        🎓</div>
                    <h4 class="text-base sm:text-lg font-extrabold text-slate-900 mb-2">2. Medan Perguruan Tinggi</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">Menjadi laboratorium intelektual di
                        kampus-kampus Kota Padang, membela kebebasan akademik, memperjuangkan integritas ilmiah, serta
                        mencetak pemimpin mahasiswa yang beretika, kritis, dan berwawasan luas.</p>
                </div>
                <div
                    class="bpc-medan-card group relative rounded-2xl overflow-hidden border border-emerald-100 bg-gradient-to-br from-emerald-50 via-white to-slate-50 p-6 shadow-sm hover:shadow-lg transition-all duration-300 animate-on-scroll"
                    data-animate="fadeInUp" data-delay="400ms">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-emerald-300">
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl mb-4 shadow-sm group-hover:scale-110 transition-transform duration-300">
                        🌍</div>
                    <h4 class="text-base sm:text-lg font-extrabold text-slate-900 mb-2">3. Medan Masyarakat</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">Hadir sebagai garam dan terang dunia
                        lewat advokasi keadilan sosial, tanggap bencana kemanusiaan di Sumatera Barat, pemeliharaan
                        kerukunan antarumat beragama, dan pengabdian bagi kaum tertindas.</p>
                </div>
            </div>
        </div>

        <!-- EXPLORER PERAN FUNGSIONARIS -->
        <div class="mb-14">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 animate-on-scroll" data-animate="fadeInDown">
                <div>
                    <span class="inline-block text-xs font-bold uppercase tracking-wider text-primary mb-1">Direktori
                        Jabatan</span>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">Struktur &amp; Pembagian Tugas
                        Fungsionaris BPC</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Klik tab bidang atau gunakan pencarian untuk
                        melihat fungsi dan tanggung jawab masing-masing jabatan.</p>
                </div>
                <div class="w-full md:w-72 shrink-0">
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400 text-sm">🔎</span>
                        <input type="text" id="roleFilterInput"
                            class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/15 bg-white shadow-xs outline-none transition-all"
                            placeholder="Cari jabatan atau tugas..." oninput="filterRoleCards(this.value)">
                    </div>
                </div>
            </div>

            <!-- Tab Buttons -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-7 scrollbar-thin animate-on-scroll" data-animate="fadeIn" id="roleCategoryTabs">
                <button type="button" onclick="switchRoleTab('all')"
                    class="bpc-tab-btn active flex-shrink-0 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="all">Semua (13)</button>
                <button type="button" onclick="switchRoleTab('inti')"
                    class="bpc-tab-btn flex-shrink-0 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="inti">👑 Pimpinan Harian (3)</button>
                <button type="button" onclick="switchRoleTab('or')"
                    class="bpc-tab-btn flex-shrink-0 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="or">🧩 Organisasi &amp; Komunikasi (3)</button>
                <button type="button" onclick="switchRoleTab('pkk')"
                    class="bpc-tab-btn flex-shrink-0 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="pkk">🎓 Kaderisasi &amp; Kerohanian (4)</button>
                <button type="button" onclick="switchRoleTab('akspel')"
                    class="bpc-tab-btn flex-shrink-0 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="akspel">🤝 Aksi &amp; Pelayanan (3)</button>
            </div>

            <!-- Role Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="roleCardsContainer">

                <!-- 1. KETUA CABANG -->
                <div class="bpc-role-card bpc-role-inti rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="inti" data-search="ketua cabang ketcab pimpinan tertinggi mandataris konpercab">
                    <div class="bpc-role-card-top bpc-top-inti"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl shadow-xs">👑</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-100 tracking-wide uppercase">Pimpinan
                                Harian</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Ketua Cabang</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Mandataris
                            Konpercab &amp; Pemimpin Tertinggi</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Pemimpin eksekutif tertinggi di Badan
                            Pengurus Cabang yang memegang tanggung jawab penuh atas kepemimpinan, arah kebijakan
                            strategis, dan representasi resmi GMKI Cabang Padang.</p>
                        <div class="bpc-duty-box rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-blue-400">▸</span>Menetapkan arah kebijakan strategis
                                    organisasi sesuai AD/ART.</li>
                                <li class="flex items-start gap-1.5"><span class="mt-0.5 text-blue-400">▸</span>Mewakili
                                    GMKI Padang ke dalam maupun ke luar di hadapan mitra.</li>
                                <li class="flex items-start gap-1.5"><span class="mt-0.5 text-blue-400">▸</span>Memimpin
                                    rapat pleno, rapat harian, dan koordinasi antar bidang.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-blue-400">▸</span>Menyampaikan Laporan Pertanggungjawaban
                                    (LPJ) pada Konpercab.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-blue-300 inline-block"></span>KETCAB</span>
                        </div>
                    </div>
                </div>

                <!-- 2. SEKRETARIS CABANG -->
                <div class="bpc-role-card bpc-role-inti rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="inti"
                    data-search="sekretaris cabang sekcab administrasi kesekretariatan kelembagaan">
                    <div class="bpc-role-card-top bpc-top-inti"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl shadow-xs">📜</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-100 tracking-wide uppercase">Pimpinan
                                Harian</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Sekretaris Cabang</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Kepala
                            Kesekretariatan &amp; Tata Kelola</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Pengendali utama ketatausahaan,
                            korespondensi resmi organisasi, tata kelola kelembagaan, dan koordinator kelancaran
                            operasional seluruh bidang fungsional.</p>
                        <div class="bpc-duty-box rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span class="mt-0.5 text-blue-400">▸</span>Memimpin
                                    tata kelola persuratan resmi, notulensi, dan arsip cabang.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-blue-400">▸</span>Mengoordinasikan sinkronisasi timeline dan
                                    kinerja seluruh bidang.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-blue-400">▸</span>Menyiapkan materi persidangan, rapat
                                    harian, dan pleno cabang.</li>
                                <li class="flex items-start gap-1.5"><span class="mt-0.5 text-blue-400">▸</span>Menjalin
                                    hubungan surat-menyurat resmi dengan Pengurus Pusat.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-blue-300 inline-block"></span>SEKCAB</span>
                        </div>
                    </div>
                </div>

                <!-- 3. BENDAHARA CABANG -->
                <div class="bpc-role-card bpc-role-inti rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="inti"
                    data-search="bendahara cabang bencab keuangan anggaran perbendaharaan kas apbc">
                    <div class="bpc-role-card-top bpc-top-inti"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl shadow-xs">💰</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-100 tracking-wide uppercase">Pimpinan
                                Harian</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Bendahara Cabang</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Manajemen
                            Keuangan, Aset &amp; Perbendaharaan</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Penanggung jawab perbendaharaan
                            organisasi, pencatatan arus kas cabang, akuntabilitas anggaran pendapatan dan belanja, serta
                            pengelolaan aset fisik kesekretariatan.</p>
                        <div class="bpc-duty-box rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span class="mt-0.5 text-blue-400">▸</span>Menyusun
                                    Anggaran Pendapatan dan Belanja Cabang (APBC).</li>
                                <li class="flex items-start gap-1.5"><span class="mt-0.5 text-blue-400">▸</span>Mencatat
                                    dan mengelola pembukuan kas, iuran, dan donasi halal.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-blue-400">▸</span>Menginventarisir dan merawat seluruh aset
                                    bergerak dan tidak bergerak.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-blue-400">▸</span>Mempublikasikan laporan keuangan secara
                                    transparan pada rapat pleno.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-blue-300 inline-block"></span>BENCAB</span>
                        </div>
                    </div>
                </div>

                <!-- 4. KABID OR -->
                <div class="bpc-role-card bpc-role-or rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="or" data-search="kabid or kepala bidang organisasi komisariat tata kelola">
                    <div class="bpc-role-card-top bpc-top-or"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shadow-xs">🏢</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-100 tracking-wide uppercase">Bid.
                                Organisasi</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Kabid Organisasi</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Koordinator Tata
                            Kelola &amp; Komisariat</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Memimpin pembenahan struktur internal,
                            supervisi kelembagaan komisariat di universitas se-Kota Padang, dan penegakan disiplin
                            konstitusi organisasi.</p>
                        <div class="bpc-duty-box bpc-duty-or rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Mengoordinasikan pembinaan dan pemekaran
                                    komisariat kampus.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Menegakkan konstitusi AD/ART dan disiplin
                                    internal anggota.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Mengawasi fungsionaris Litbang dan Medko.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Menyusun grand design ekspansi dan
                                    pengembangan cabang.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-purple-300 inline-block"></span>KABID OR</span>
                        </div>
                    </div>
                </div>

                <!-- 5. SEKFUNG OR-LITBANG -->
                <div class="bpc-role-card bpc-role-or rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="or"
                    data-search="sekfung or litbang penelitian pengembangan riset database data anggota">
                    <div class="bpc-role-card-top bpc-top-or"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shadow-xs">📊</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-100 tracking-wide uppercase">Bid.
                                Organisasi</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Sekfung OR — Litbang</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Riset Organisasi
                            &amp; Analisis Data Civitas</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Bertanggung jawab atas penelitian
                            strategis keorganisasian, pengelolaan database keanggotaan cabang (SIM-KAD), dan evaluasi
                            efektivitas program kerja.</p>
                        <div class="bpc-duty-box bpc-duty-or rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Memutakhirkan basis data keanggotaan dan
                                    pemetaan civitas.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Mengadakan kajian ilmiah dan riset
                                    dinamika mahasiswa Kristen.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Mengevaluasi capaian kinerja program
                                    kerja fungsionaris BPC.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Menyiapkan rekomendasi kebijakan berbasis
                                    data (data-driven).</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-purple-300 inline-block"></span>SEKFUNG
                                OR-LITBANG</span>
                        </div>
                    </div>
                </div>

                <!-- 6. SEKFUNG MEDKO -->
                <div class="bpc-role-card bpc-role-or rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="or"
                    data-search="sekfung medko media komunikasi informasi humas publikasi website dokumentasi">
                    <div class="bpc-role-card-top bpc-top-or"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shadow-xs">💻</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-100 tracking-wide uppercase">Bid.
                                Organisasi</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Sekfung Medko</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Media Komunikasi
                            &amp; Dokumentasi Publik</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Ujung tombak keterbukaan informasi
                            publik, pengelolaan media sosial dan kanal digital cabang, dokumentasi multimedia, serta
                            publikasi agenda resmi organisasi.</p>
                        <div class="bpc-duty-box bpc-duty-or rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Mengelola kanal informasi resmi dan
                                    platform media sosial GMKI Padang.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Menerbitkan materi publikasi digital dan
                                    dokumentasi kegiatan cabang.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Mendokumentasikan foto/video seluruh
                                    agenda cabang.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-purple-400">▸</span>Membangun citra positif gerakan yang
                                    progresif dan transparan.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-purple-300 inline-block"></span>SEKFUNG
                                MEDKO</span>
                        </div>
                    </div>
                </div>

                <!-- 7. KABID PKK -->
                <div class="bpc-role-card bpc-role-pkk rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="pkk"
                    data-search="kabid pkk pendidikan kader kerohanian mabim lkko spiritualitas kurikulum">
                    <div class="bpc-role-card-top bpc-top-pkk"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shadow-xs">🎓</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-100 tracking-wide uppercase">Bid.
                                PKK</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Kabid PKK</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Koordinator
                            Kurikulum Kaderisasi &amp; Spiritual</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Mengoordinasikan seluruh proses
                            kaderisasi berjenjang, penanaman nilai spiritualitas oikumenis, dan pembinaan karakter kader
                            yang takut akan Tuhan dan berintegritas.</p>
                        <div class="bpc-duty-box bpc-duty-pkk rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Mengawal pelaksanaan kurikulum
                                    kaderisasi PDSPK GMKI di Padang.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Mengoordinasikan penyelenggaraan MABIM,
                                    LKKO, dan pelatihan kader.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Menjaga mutu pembinaan kerohanian
                                    civitas yang inklusif dan kokoh.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Memimpin koordinasi Sekfung Kader,
                                    Sekfung Kerohanian, dan Sekfung Ekonomi Kreatif (Ekraf).</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>KABID PKK</span>
                        </div>
                    </div>
                </div>

                <!-- 8. SEKFUNG KADER -->
                <div class="bpc-role-card bpc-role-pkk rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="pkk"
                    data-search="sekfung kader rekrutmen anggota baru pembinaan bimbingan mahasiswa baru">
                    <div class="bpc-role-card-top bpc-top-pkk"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shadow-xs">👥</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-100 tracking-wide uppercase">Bid.
                                PKK</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Sekfung Kader</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Pelaksana Teknis
                            Rekrutmen &amp; Pendampingan</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Ujung tombak penerimaan mahasiswa baru,
                            pengorganisasian Masa Bimbingan (MABIM), pelatihan fasilitator, dan pendampingan mentoring
                            civitas.</p>
                        <div class="bpc-duty-box bpc-duty-pkk rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Merancang dan mengeksekusi tahapan
                                    rekrutmen Masa Bimbingan.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Membina dan mendampingi kelompok belajar
                                    calon anggota.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Menyiapkan tim fasilitator kaderisasi
                                    yang handal dan militan.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Menyelenggarakan forum diskusi
                                    intelektual dan literasi kader.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>SEKFUNG
                                KADER</span>
                        </div>
                    </div>
                </div>

                <!-- 9. SEKFUNG KEROHANIAN -->
                <div class="bpc-role-card bpc-role-pkk rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="pkk"
                    data-search="sekfung kerohanian ibadah kebaktian persekutuan doa alkitab natal paskah">
                    <div class="bpc-role-card-top bpc-top-pkk"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shadow-xs">🕊️</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-100 tracking-wide uppercase">Bid.
                                PKK</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Sekfung Kerohanian</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Pembina
                            Spiritualitas, Kebaktian &amp; Doa</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Penanggung jawab kehidupan rohani
                            civitas, peribadahan rutin cabang, persekutuan doa, studi Alkitab kontekstual, dan perayaan
                            hari besar gerejawi.</p>
                        <div class="bpc-duty-box bpc-duty-pkk rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Menyelenggarakan kebaktian bulanan
                                    civitas dan persekutuan doa.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Memimpin pendalaman Alkitab (PA) yang
                                    kritis dan kontekstual.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Mengawal perayaan hari besar gerejawi
                                    (Natal, Paskah, Dies Natalis).</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Menjalin hubungan persekutuan doa dengan
                                    gereja dan PMK kampus.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>SEKFUNG
                                KEROHANIAN</span>
                        </div>
                    </div>
                </div>

                <!-- 10. SEKFUNG EKRAF -->
                <div class="bpc-role-card bpc-role-pkk rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="pkk"
                    data-search="sekfung ekraf ekonomi kreatif wirausaha kewirausahaan kemandirian merchandise">
                    <div class="bpc-role-card-top bpc-top-pkk"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shadow-xs">💡</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-100 tracking-wide uppercase">Bid.
                                PKK</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Sekfung Ekonomi Kreatif</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Kemandirian
                            Finansial &amp; Inkubasi Kewirausahaan</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Pengembang jiwa kewirausahaan sosial
                            (socio-entrepreneurship), perintis unit merchandise resmi cabang, dan pembina literasi
                            bisnis digital di kalangan mahasiswa Kristen.</p>
                        <div class="bpc-duty-box bpc-duty-pkk rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Mengelola unit merchandise &amp; usaha
                                    mandiri resmi GMKI Padang.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Mengadakan workshop bisnis, literasi
                                    finansial, dan ekonomi digital.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Membangun jejaring kolaborasi dengan
                                    pelaku UMKM dan wirausaha lokal.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-emerald-500">▸</span>Mendorong kemandirian ekonomi kader
                                    berbasis karya kreatif.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>SEKFUNG
                                EKRAF</span>
                        </div>
                    </div>
                </div>

                <!-- 11. KABID AKSPEL -->
                <div class="bpc-role-card bpc-role-akspel rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="akspel"
                    data-search="kabid akspel aksi pelayanan advokasi sosial kemasyarakatan kebangsaan">
                    <div class="bpc-role-card-top bpc-top-akspel"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl shadow-xs">📢</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-100 tracking-wide uppercase">Bid.
                                Akspel</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Kabid Akspel</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Koordinator Aksi
                            Sosial, Advokasi &amp; Kebangsaan</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Memimpin pengejawantahan Tri Panji
                            "Tinggi Pengabdian" dalam merespons isu sosial kemasyarakatan, advokasi kebijakan publik,
                            dan kemitraan strategis eksternal.</p>
                        <div class="bpc-duty-box bpc-duty-akspel rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Mengoordinasikan respons organisasi
                                    terhadap isu publik &amp; kebangsaan.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Menginisiasi aksi solidaritas dan advokasi
                                    sosial bagi masyarakat.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Membangun kolaborasi dengan Cipayung Plus
                                    dan ormas pemuda se-Padang.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Mengoordinasikan fungsionaris Akspel dan
                                    Sekfung Pemberdayaan Perempuan.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>KABID
                                AKSPEL</span>
                        </div>
                    </div>
                </div>

                <!-- 12. SEKFUNG AKSPEL -->
                <div class="bpc-role-card bpc-role-akspel rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="akspel"
                    data-search="sekfung akspel tanggap bencana bakti sosial advokasi lingkungan dialog keagamaan">
                    <div class="bpc-role-card-top bpc-top-akspel"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl shadow-xs">🤝</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-100 tracking-wide uppercase">Bid.
                                Akspel</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Sekfung Akspel</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Pelaksana Tanggap
                            Sosial, Bencana &amp; Kemanusiaan</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Pelaksana aksi nyata di lapangan, tanggap
                            bencana kemanusiaan, advokasi hak asasi manusia, pelestarian lingkungan hidup, dan dialog
                            kerukunan umat beragama di Sumatera Barat.</p>
                        <div class="bpc-duty-box bpc-duty-akspel rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Mengorganisir bakti sosial, donor darah,
                                    dan relawan bencana alam.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Melakukan pendampingan advokasi masyarakat
                                    marginal dan rentan.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Menyelenggarakan dialog perdamaian dan
                                    kerukunan lintas agama.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Menggalang kampanye kepedulian lingkungan
                                    dan keutuhan ciptaan.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>SEKFUNG
                                AKSPEL</span>
                        </div>
                    </div>
                </div>

                <!-- 13. SEKFUNG PEREMPUAN -->
                <div class="bpc-role-card bpc-role-akspel rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col"
                    data-category="akspel"
                    data-search="sekfung pemberdayaan perempuan gender kader perempuan perlindungan anak">
                    <div class="bpc-role-card-top bpc-top-akspel"></div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl shadow-xs">🌸</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-100 tracking-wide uppercase">Bid.
                                Akspel</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mb-0.5">Sekfung Pemberdayaan Perempuan</h4>
                        <div class="text-[11px] font-bold text-amber-600 mb-3 uppercase tracking-wide">Advokasi
                            Kesetaraan &amp; Kepemimpinan Perempuan</div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Pengembang potensi kepemimpinan kader
                            perempuan GMKI, pengawal ruang aman di kampus, serta penggerak advokasi kesetaraan gender
                            dan perlindungan perempuan &amp; anak.</p>
                        <div class="bpc-duty-box bpc-duty-akspel rounded-xl p-3 mb-4">
                            <div class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-2">Tugas
                                &amp; Wewenang</div>
                            <ul class="text-xs text-slate-600 space-y-1.5">
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Menyelenggarakan pelatihan kepemimpinan
                                    khusus kader perempuan.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Mendorong ruang aman bebas pelecehan
                                    &amp; kekerasan seksual di kampus.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Membangun jejaring advokasi perlindungan
                                    perempuan di Sumbar.</li>
                                <li class="flex items-start gap-1.5"><span
                                        class="mt-0.5 text-amber-500">▸</span>Mendorong representasi aktif perempuan
                                    dalam kepemimpinan publik.</li>
                            </ul>
                        </div>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest"><span
                                    class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>SEKFUNG
                                PEREMPUAN</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Empty state -->
            <div id="noRoleMatches"
                class="hidden text-center py-14 bg-white rounded-2xl border border-slate-200/80 shadow-xs mt-4">
                <div class="text-4xl mb-3">🔍</div>
                <h4 class="text-base font-bold text-slate-800 mb-1">Peran Tidak Ditemukan</h4>
                <p class="text-xs sm:text-sm text-slate-400 mb-4">Tidak ada jabatan yang cocok dengan kata kunci yang
                    Anda masukkan.</p>
                <button type="button" onclick="resetRoleFilter()"
                    class="btn btn-primary btn-sm rounded-xl px-5 font-bold">Reset Pencarian</button>
            </div>
        </div>

        <!-- FOOTER CARDS -->
        <div class="mt-16 pt-10 border-t border-slate-200/70 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div
                class="bpc-footer-card relative rounded-2xl overflow-hidden bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-white shadow-lg flex flex-col justify-between p-7">
                <div class="absolute inset-0 opacity-5"
                    style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 24px 24px;">
                </div>
                <div class="relative">
                    <div
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 text-amber-300 text-xs font-bold mb-4 border border-white/10">
                        <span>⚖️</span><span>Konstitusi Organisasi</span>
                    </div>
                    <h4 class="text-lg font-extrabold mb-2 text-white">Landasan AD/ART Mengenai BPC</h4>
                    <p class="text-sm text-slate-300 leading-relaxed mb-6">Tata cara pemilihan, struktur pimpinan, masa
                        bakti 2 tahun, dan pertanggungjawaban Badan Pengurus Cabang diatur secara sah dalam Anggaran
                        Rumah Tangga (ART) GMKI Pasal 5 &amp; Pasal 6.</p>
                </div>
                <div class="relative">
                    <a href="/ad-art#art-pasal-6"
                        class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold rounded-xl px-5 py-2.5 text-sm transition-all shadow-md">
                        <span>Pelajari ART Pasal 6 Tentang BPC</span><span>&rarr;</span>
                    </a>
                </div>
            </div>
            <div
                class="bpc-footer-card rounded-2xl bg-white border border-slate-200/80 shadow-md flex flex-col justify-between p-7">
                <div>
                    <div
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold mb-4 border border-emerald-100">
                        <span>📬</span><span>Kemitraan &amp; Audiensi</span>
                    </div>
                    <h4 class="text-lg font-extrabold text-slate-900 mb-2">Ingin Berkolaborasi dengan BPC?</h4>
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">Gereja, lembaga mahasiswa, perguruan tinggi,
                        instansi pemerintah, media massa, maupun organisasi kepemudaan yang ingin mengajukan audiensi,
                        kemitraan program, atau mengundang fungsionaris BPC dapat menghubungi kami.</p>
                </div>
                <div>
                    <a href="/kontak"
                        class="inline-flex items-center gap-2 btn btn-primary rounded-xl px-5 py-2.5 text-sm font-extrabold transition-all shadow-md">
                        <span>Hubungi Sekretariat BPC Padang</span><span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

    </section>
</div>

<style>
    .bpc-officer-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .bpc-officer-card:hover {
        transform: translateY(-4px);
    }

    .bpc-medan-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .bpc-medan-card:hover {
        transform: translateY(-4px);
    }

    .bpc-tab-btn {
        background-color: #f1f5f9;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        transition: background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .bpc-tab-btn:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    .bpc-tab-btn.active {
        background-color: var(--primary, #0f3d64);
        color: #ffffff;
        border-color: var(--primary, #0f3d64);
        box-shadow: 0 3px 10px rgba(15, 61, 100, 0.3);
    }

    .bpc-role-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        overflow: hidden;
    }

    .bpc-role-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px -4px rgba(15, 23, 42, 0.12);
    }

    .bpc-role-card-top {
        height: 4px;
        width: 100%;
    }

    .bpc-top-inti {
        background: linear-gradient(to right, #3b82f6, #93c5fd);
    }

    .bpc-top-or {
        background: linear-gradient(to right, #a855f7, #d8b4fe);
    }

    .bpc-top-pkk {
        background: linear-gradient(to right, #10b981, #6ee7b7);
    }

    .bpc-top-akspel {
        background: linear-gradient(to right, #f59e0b, #fcd34d);
    }

    .bpc-duty-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .bpc-duty-or {
        background: #faf5ff;
        border-color: #ede9fe;
    }

    .bpc-duty-pkk {
        background: #f0fdf4;
        border-color: #dcfce7;
    }

    .bpc-duty-akspel {
        background: #fffbeb;
        border-color: #fef3c7;
    }

    .bpc-role-inti:hover {
        border-color: #93c5fd;
    }

    .bpc-role-or:hover {
        border-color: #d8b4fe;
    }

    .bpc-role-pkk:hover {
        border-color: #6ee7b7;
    }

    .bpc-role-akspel:hover {
        border-color: #fcd34d;
    }

    .bpc-footer-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .bpc-footer-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 40px -6px rgba(15, 23, 42, 0.15);
    }
</style>

<script>
    let activeRoleCategory = 'all';
    function switchRoleTab(category) {
        activeRoleCategory = category;
        document.querySelectorAll('#roleCategoryTabs .bpc-tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-tab') === category);
        });
        applyRoleFilters();
    }
    function filterRoleCards(query) { applyRoleFilters(); }
    function applyRoleFilters() {
        const queryInput = document.getElementById('roleFilterInput');
        const query = (queryInput ? queryInput.value : '').trim().toLowerCase();
        const cards = document.querySelectorAll('#roleCardsContainer .bpc-role-card');
        let visibleCount = 0;
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            const searchHaystack = (card.getAttribute('data-search') + ' ' + card.innerText).toLowerCase();
            const matchesCategory = (activeRoleCategory === 'all' || cardCat === activeRoleCategory);
            const matchesQuery = (!query || searchHaystack.includes(query));
            if (matchesCategory && matchesQuery) { card.style.display = 'flex'; visibleCount++; }
            else { card.style.display = 'none'; }
        });
        const noMatchBox = document.getElementById('noRoleMatches');
        if (noMatchBox) { noMatchBox.classList.toggle('hidden', visibleCount > 0); }
    }
    function resetRoleFilter() {
        const queryInput = document.getElementById('roleFilterInput');
        if (queryInput) queryInput.value = '';
        switchRoleTab('all');
    }
</script>