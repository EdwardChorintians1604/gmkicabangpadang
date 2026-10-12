<div class="container py-8 sm:py-12">
    <!-- Page Header with Animate.css -->
    <div class="animate__animated animate__fadeInDown mb-12 text-center">
        <div class="section-tag uppercase text-amber-600 font-bold tracking-wider text-xs mb-2">Kepemimpinan Cabang
        </div>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">Struktur BPC GMKI Cabang Padang
        </h1>
        <p class="text-slate-600 max-w-2xl mx-auto text-sm sm:text-base font-medium">
            Badan Pengurus Cabang (BPC) Gerakan Mahasiswa Kristen Indonesia Cabang Padang. Mengabdi dengan integritas,
            kerendahan hati, dan keteladanan.
        </p>
    </div>

    <?php if (!empty($structure)): ?>
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 animate__animated animate__fadeInUp">
            <?php foreach ($structure as $officer): ?>
                <div
                    class="card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 bg-white text-center border border-slate-200/80 flex flex-col justify-between">
                    <div class="p-6">
                        <div
                            class="w-28 h-28 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary/20 shadow-inner bg-slate-100 ring-2 ring-primary/10">
                            <?php if (!empty($officer['foto'])): ?>
                                <img src="/uploads/organisasi/<?= e($officer['foto']) ?>" alt="<?= e($officer['nama']) ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <img src="<?= asset('images/default-profile.png') ?>" alt="<?= e($officer['nama']) ?>"
                                    class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1 leading-snug">
                            <?= e($officer['nama']) ?>
                        </h3>
                        <div class="text-xs sm:text-sm font-bold text-amber-600 mb-3">
                            <?= e($officer['jabatan']) ?>
                        </div>
                        <div class="text-xs text-slate-400">
                            Masa Bakti: <span class="font-medium text-slate-600"><?= e($officer['periode']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card text-center p-12 rounded-2xl bg-white shadow-sm max-w-lg mx-auto">
            <div class="text-4xl mb-3">👥</div>
            <p class="text-slate-500 font-medium">Data struktur kepengurusan belum dimasukkan ke dalam sistem.</p>
        </div>
    <?php endif; ?>
    <!-- ================================================================= -->
    <!-- PENJELASAN TATA KELOLA BPC & PERAN FUNGSIONARIS -->
    <!-- ================================================================= -->
    <section class="mt-16 sm:mt-24 pt-12 border-t border-slate-200/80" id="detail-bpc">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-10 animate__animated animate__fadeIn">
            <div
                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200/80 mb-3 shadow-xs">
                <span>🏛️</span>
                <span>Tata Kelola & Kepemimpinan Cabang</span>
            </div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">
                Mengenal Tugas, Fungsi & Peran Fungsionaris BPC
            </h2>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                Badan Pengurus Cabang (BPC) GMKI Cabang Padang adalah lembaga kepemimpinan eksekutif tertinggi di
                tingkat cabang yang mengemban mandat Konperensi Cabang (Konpercab) dan Pengurus Pusat (PP) GMKI. BPC
                memimpin dinamika organisasi, membina ribuan kader mahasiswa Kristen, serta mewujudkan Tri Panji di Tiga
                Medan Pelayanan.
            </p>
        </div>

        <!-- TIGA MEDAN PELAYANAN BPC (TRI MEDAN LAYAN GMKI) -->
        <div class="mb-14">
            <div class="text-center mb-6">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900">
                    Tiga Medan Pelayanan BPC GMKI Padang
                </h3>
                <p class="text-xs sm:text-sm text-slate-500">
                    Seluruh gerak langkah dan program fungsionaris BPC bermuara pada kesaksian di tiga ranah historis
                    GMKI:
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div
                    class="card rounded-2xl p-6 bg-white border border-slate-200/80 shadow-xs hover:shadow-md transition-all">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center text-2xl mb-4 font-bold">
                        ⛪
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-2">1. Medan Gereja</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Membina persatuan oikumenis mahasiswa Kristen lintas denominasi, berkontribusi aktif dalam
                        pembaharuan spiritualitas gereja, serta memperkokoh amanat Kristus <em>Ut Omnes Unum
                            Sint</em> (Supaya Mereka Semua Menjadi Satu).
                    </p>
                </div>

                <div
                    class="card rounded-2xl p-6 bg-white border border-slate-200/80 shadow-xs hover:shadow-md transition-all">
                    <div
                        class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-2xl mb-4 font-bold">
                        🎓
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-2">2. Medan Perguruan Tinggi</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Menjadi laboratorium intelektual di kampus-kampus Kota Padang, membela kebebasan akademik,
                        memperjuangkan integritas ilmiah, serta mencetak pemimpin mahasiswa yang beretika, kritis,
                        dan berwawasan luas.
                    </p>
                </div>

                <div
                    class="card rounded-2xl p-6 bg-white border border-slate-200/80 shadow-xs hover:shadow-md transition-all">
                    <div
                        class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-2xl mb-4 font-bold">
                        🌍
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-2">3. Medan Masyarakat</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Hadir sebagai garam dan terang dunia lewat advokasi keadilan sosial, tanggap bencana
                        kemanusiaan di Sumatera Barat, pemeliharaan kerukunan antarumat beragama, dan pengabdian
                        bagi kaum tertindas.
                    </p>
                </div>
            </div>
        </div>

        <!-- EXPLORER PERAN FUNGSIONARIS BPC -->
        <div class="mb-14">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-1">
                        Struktur & Pembagian Tugas Fungsionaris BPC
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Klik tab bidang di bawah atau gunakan kotak pencarian untuk melihat fungsi utama dan tanggung
                        jawab masing-masing jabatan.
                    </p>
                </div>
                <!-- Live Search Filter Peran di Client -->
                <div class="w-full md:w-72">
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 text-xs">
                            🔎
                        </span>
                        <input type="text" id="roleFilterInput"
                            class="form-control w-full pl-8 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary/20 bg-white"
                            placeholder="Filter peran (mis: Sekretaris, Kader)..."
                            oninput="filterRoleCards(this.value)">
                    </div>
                </div>
            </div>

            <!-- Tab Buttons Navigasi Kategori Jabatan -->
            <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 scrollbar-thin" id="roleCategoryTabs">
                <button type="button" onclick="switchRoleTab('all')"
                    class="bpc-tab-btn active px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="all">
                    Semua Jabatan (13)
                </button>
                <button type="button" onclick="switchRoleTab('inti')"
                    class="bpc-tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="inti">
                    👑 Pimpinan Harian (3)
                </button>
                <button type="button" onclick="switchRoleTab('or')"
                    class="bpc-tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="or">
                    🧩 Organisasi & Komunikasi (4)
                </button>
                <button type="button" onclick="switchRoleTab('pkk')"
                    class="bpc-tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="pkk">
                    🎓 Kaderisasi & Kerohanian (3)
                </button>
                <button type="button" onclick="switchRoleTab('akspel')"
                    class="bpc-tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    data-tab="akspel">
                    🤝 Aksi & Pelayanan (3)
                </button>
            </div>

            <!-- Grid Kartu Penjelasan Peran BPC -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="roleCardsContainer">

                <!-- 1. KETUA CABANG -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="inti" data-search="ketua cabang ketcab pimpinan tertinggi mandataris konpercab">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center text-xl font-bold">
                                👑
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-blue-50 text-blue-800 border border-blue-200">
                                Pimpinan Harian
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Ketua Cabang</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Mandataris Konpercab & Pemimpin Tertinggi Cabang
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Pemimpin eksekutif tertinggi di Badan Pengurus Cabang yang memegang tanggung jawab penuh
                            atas kepemimpinan, arah kebijakan strategis, dan representasi resmi GMKI Cabang Padang.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Menetapkan arah kebijakan strategis organisasi sesuai AD/ART.</li>
                                <li>Mewakili GMKI Padang ke dalam maupun ke luar di hadapan mitra.</li>
                                <li>Memimpin rapat pleno, rapat harian, dan koordinasi antar bidang.</li>
                                <li>Menyampaikan Laporan Pertanggungjawaban (LPJ) pada Konpercab.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: KETCAB</span>
                    </div>
                </div>

                <!-- 2. SEKRETARIS CABANG -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="inti"
                    data-search="sekretaris cabang sekcab administrasi kesekretariatan kelembagaan">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center text-xl font-bold">
                                📜
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-blue-50 text-blue-800 border border-blue-200">
                                Pimpinan Harian
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Sekretaris Cabang</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Kepala Kesekretariatan & Tata Kelola Kelembagaan
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Pengendali utama ketatausahaan, korespondensi resmi organisasi, tata kelola kelembagaan, dan
                            koordinator kelancaran operasional seluruh bidang fungsional.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Memimpin tata kelola persuratan resmi, notulensi, dan arsip cabang.</li>
                                <li>Mengoordinasikan sinkronisasi timeline dan kinerja seluruh bidang.</li>
                                <li>Menyiapkan materi persidangan, rapat harian, dan pleno cabang.</li>
                                <li>Menjalin hubungan surat-menyurat resmi dengan Pengurus Pusat.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: SEKCAB</span>
                    </div>
                </div>

                <!-- 3. BENDAHARA CABANG -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="inti"
                    data-search="bendahara cabang bencab keuangan anggaran perbendaharaan kas apbc">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center text-xl font-bold">
                                💰
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-blue-50 text-blue-800 border border-blue-200">
                                Pimpinan Harian
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Bendahara Cabang</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Manajemen Keuangan, Aset & Perbendaharaan
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Penanggung jawab perbendaharaan organisasi, pencatatan arus kas cabang, akuntabilitas
                            anggaran pendapatan dan belanja, serta pengelolaan aset fisik kesekretariatan.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Menyusun Anggaran Pendapatan dan Belanja Cabang (APBC).</li>
                                <li>Mencatat dan mengelola pembukuan kas, iuran, dan donasi halal.</li>
                                <li>Menginventarisir dan merawat seluruh aset bergerak dan tidak bergerak.</li>
                                <li>Mempublikasikan laporan keuangan secara transparan pada rapat pleno.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: BENCAB</span>
                    </div>
                </div>

                <!-- 4. KABID OR -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="or" data-search="kabid or kepala bidang organisasi komisariat tata kelola">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center text-xl font-bold">
                                🏢
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-purple-50 text-purple-800 border border-purple-200">
                                Bidang Organisasi
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Kabid Organisasi (Kabid OR)</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Koordinator Tata Kelola Kelembagaan & Komisariat
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Memimpin pembenahan struktur internal, supervisi kelembagaan komisariat di universitas
                            se-Kota Padang, dan penegakan disiplin konstitusi organisasi.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Mengoordinasikan pembinaan dan pemekaran komisariat kampus.</li>
                                <li>Menegakkan konstitusi AD/ART dan disiplin internal anggota.</li>
                                <li>Mengawasi fungsionaris Litbang, Medko, dan Pemberdayaan Perempuan.</li>
                                <li>Menyusun grand design ekspansi dan pengembangan cabang.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: KABID OR</span>
                    </div>
                </div>

                <!-- 5. SEKFUNG OR-LITBANG -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="or"
                    data-search="sekfung or litbang penelitian pengembangan riset database data anggota">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center text-xl font-bold">
                                📊
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-purple-50 text-purple-800 border border-purple-200">
                                Bidang Organisasi
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Sekfung OR - Litbang</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Riset Organisasi & Analisis Data Civitas
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Bertanggung jawab atas penelitian strategis keorganisasian, pengelolaan database keanggotaan
                            cabang (SIM-KAD), dan evaluasi efektivitas program kerja.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Memutakhirkan basis data keanggotaan dan pemetaan civitas.</li>
                                <li>Mengadakan kajian ilmiah dan riset dinamika mahasiswa Kristen.</li>
                                <li>Mengevaluasi capaian kinerja program kerja fungsionaris BPC.</li>
                                <li>Menyiapkan rekomendasi kebijakan berbasis data (data-driven).</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: SEKFUNG OR-LITBANG</span>
                    </div>
                </div>

                <!-- 6. SEKFUNG MEDKO -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="or"
                    data-search="sekfung medko media komunikasi informasi humas publikasi website dokumentasi">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center text-xl font-bold">
                                💻
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-purple-50 text-purple-800 border border-purple-200">
                                Bidang Organisasi
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Sekfung Medko</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Media Komunikasi, Portal Digital & Dokumentasi Publik
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Ujung tombak keterbukaan informasi publik, pengelolaan media sosial dan kanal digital
                            cabang, dokumentasi multimedia, serta publikasi agenda resmi organisasi.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Mengelola kanal informasi resmi dan platform media sosial GMKI Padang.</li>
                                <li>Menerbitkan materi publikasi digital dan dokumentasi kegiatan cabang.</li>
                                <li>Mendokumentasikan foto/video seluruh agenda cabang.</li>
                                <li>Membangun citra positif gerakan yang progresif dan transparan.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: SEKFUNG MEDKO</span>
                    </div>
                </div>

                <!-- 7. SEKFUNG PEMBERDAYAAN PEREMPUAN -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="or"
                    data-search="sekfung pemberdayaan perempuan gender kader perempuan perlindungan anak">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center text-xl font-bold">
                                🌸
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-purple-50 text-purple-800 border border-purple-200">
                                Bidang Organisasi
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Sekfung Pemberdayaan Perempuan</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Advokasi Kesetaraan & Kepemimpinan Kader Perempuan
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Pengembang potensi kepemimpinan kader perempuan GMKI, pengawal ruang aman di kampus, serta
                            penggerak advokasi kesetaraan gender dan perlindungan perempuan & anak.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Menyelenggarakan pelatihan kepemimpinan khusus kader perempuan.</li>
                                <li>Mendorong ruang aman bebas pelecehan & kekerasan seksual di kampus.</li>
                                <li>Membangun jejaring advokasi perlindungan perempuan di Sumbar.</li>
                                <li>Mendorong representasi aktif perempuan dalam kepemimpinan publik.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: SEKFUNG PEREMPUAN</span>
                    </div>
                </div>

                <!-- 8. KABID PKK -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="pkk"
                    data-search="kabid pkk pendidikan kader kerohanian mabim lkko spiritualitas kurikulum">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl font-bold">
                                🎓
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Bidang Kaderisasi & Kerohanian
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Kabid PKK</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Koordinator Kurikulum Kaderisasi & Karakter Spiritual
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Mengoordinasikan seluruh proses kaderisasi berjenjang, penanaman nilai spiritualitas
                            oikumenis, dan pembinaan karakter kader yang takut akan Tuhan dan berintegritas.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Mengawal pelaksanaan kurikulum kaderisasi PDSPK GMKI di Padang.</li>
                                <li>Mengoordinasikan penyelenggaraan MABIM, LKKO, dan pelatihan kader.</li>
                                <li>Menjaga mutu pembinaan kerohanian civitas yang inklusif dan kokoh.</li>
                                <li>Memimpin koordinasi Sekfung Kader dan Sekfung Kerohanian.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: KABID PKK</span>
                    </div>
                </div>

                <!-- 9. SEKFUNG KADER -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="pkk"
                    data-search="sekfung kader rekrutmen anggota baru pembinaan bimbingan mahasiswa baru">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl font-bold">
                                👥
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Bidang Kaderisasi & Kerohanian
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Sekfung Kader</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Pelaksana Teknis Rekrutmen & Pendampingan Anggota
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Ujung tombak penerimaan mahasiswa baru, pengorganisasian Masa Bimbingan (MABIM), pelatihan
                            fasilitator, dan pendampingan mentoring civitas.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Merancang dan mengeksekusi tahapan rekrutmen Masa Bimbingan.</li>
                                <li>Membina dan mendampingi kelompok belajar calon anggota.</li>
                                <li>Menyiapkan tim fasilitator kaderisasi yang handal dan militan.</li>
                                <li>Menyelenggarakan forum diskusi intelektual dan literasi kader.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: SEKFUNG KADER</span>
                    </div>
                </div>

                <!-- 10. SEKFUNG KEROHANIAN -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="pkk"
                    data-search="sekfung kerohanian ibadah kebaktian persekutuan doa alkitab natal paskah">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl font-bold">
                                🕊️
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Bidang Kaderisasi & Kerohanian
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Sekfung Kerohanian</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Pembina Spiritualitas, Kebaktian & Doa Civitas
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Penanggung jawab kehidupan rohani civitas, peribadahan rutin cabang, persekutuan doa, studi
                            Alkitab kontekstual, dan perayaan hari besar gerejawi.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Menyelenggarakan kebaktian bulanan civitas dan persekutuan doa.</li>
                                <li>Memimpin pendalaman Alkitab (PA) yang kritis dan kontekstual.</li>
                                <li>Mengawal perayaan hari besar gerejawi (Natal, Paskah, Dies Natalis).</li>
                                <li>Menjalin hubungan persekutuan doa dengan gereja dan PMK kampus.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: SEKFUNG KEROHANIAN</span>
                    </div>
                </div>

                <!-- 11. KABID AKSPEL -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="akspel"
                    data-search="kabid akspel aksi pelayanan advokasi sosial kemasyarakatan kebangsaan">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold">
                                📢
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200">
                                Bidang Aksi & Pelayanan
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Kabid Akspel</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Koordinator Aksi Sosial, Advokasi & Kebangsaan
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Memimpin pengejawantahan Tri Panji "Tinggi Pengabdian" dalam merespons isu sosial
                            kemasyarakatan, advokasi kebijakan publik, dan kemitraan strategis eksternal.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Mengoordinasikan respons organisasi terhadap isu publik & kebangsaan.</li>
                                <li>Menginisiasi aksi solidaritas dan advokasi sosial bagi masyarakat.</li>
                                <li>Membangun kolaborasi dengan Cipayung Plus dan ormas pemuda se-Padang.</li>
                                <li>Mengoordinasikan fungsionaris Akspel dan Ekonomi Kreatif.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: KABID AKSPEL</span>
                    </div>
                </div>

                <!-- 12. SEKFUNG AKSPEL -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="akspel"
                    data-search="sekfung akspel tanggap bencana bakti sosial advokasi lingkungan dialog keagamaan">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold">
                                🤝
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200">
                                Bidang Aksi & Pelayanan
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Sekfung Akspel</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Pelaksana Tanggap Sosial, Bencana & Kemanusiaan
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Pelaksana aksi nyata di lapangan, tanggap bencana kemanusiaan, advokasi hak asasi manusia,
                            pelestarian lingkungan hidup, dan dialog kerukunan umat beragama di Sumatera Barat.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Mengorganisir bakti sosial, donor darah, dan relawan bencana alam.</li>
                                <li>Melakukan pendampingan advokasi masyarakat marginal dan rentan.</li>
                                <li>Menyelenggarakan dialog perdamaian dan kerukunan lintas agama.</li>
                                <li>Menggalang kampanye kepedulian lingkungan dan keutuhan ciptaan.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: SEKFUNG AKSPEL</span>
                    </div>
                </div>

                <!-- 13. SEKFUNG EKRAF -->
                <div class="card bpc-role-card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                    data-category="akspel"
                    data-search="sekfung ekraf ekonomi kreatif wirausaha kewirausahaan kemandirian merchandise">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold">
                                💡
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200">
                                Bidang Aksi & Pelayanan
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">Sekfung Ekonomi Kreatif</h4>
                        <div class="text-xs font-semibold text-amber-600 mb-3">
                            Kemandirian Finansial & Inkubasi Kewirausahaan
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Pengembang jiwa kewirausahaan sosial (socio-entrepreneurship), perintis unit merchandise
                            resmi cabang, dan pembina literasi bisnis digital di kalangan mahasiswa Kristen.
                        </p>
                        <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Tugas Pokok
                                & Wewenang:</div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                <li>Mengelola unit merchandise & usaha mandiri resmi GMKI Padang.</li>
                                <li>Mengadakan workshop bisnis, literasi finansial, dan ekonomi digital.</li>
                                <li>Membangun jejaring kolaborasi dengan pelaku UMKM dan wirausaha lokal.</li>
                                <li>Mendorong kemandirian ekonomi kader berbasis karya kreatif.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400">Kode Jabatan: SEKFUNG EKRAF</span>
                    </div>
                </div>

            </div>

            <!-- Pesan jika hasil filter peran kosong -->
            <div id="noRoleMatches"
                class="hidden text-center py-12 bg-white rounded-2xl border border-slate-200/80 shadow-xs mt-4">
                <div class="text-3xl mb-2">🔍</div>
                <h4 class="text-base font-bold text-slate-800 mb-1">Peran Tidak Ditemukan</h4>
                <p class="text-xs sm:text-sm text-slate-500 mb-4">Tidak ada jabatan atau tugas yang cocok dengan kata
                    kunci yang Anda masukkan.</p>
                <button type="button" onclick="resetRoleFilter()" class="btn btn-primary btn-sm rounded-xl px-4">
                    Reset Filter Pencarian
                </button>
            </div>
        </div>

        <!-- DUA KARTU FOOTER INFORMASI: KONSTITUSI AD/ART & HUBUNGI BPC -->
        <div class="mt-16 pt-12 border-t border-slate-200/80 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    class="card rounded-2xl p-6 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-white shadow-md flex flex-col justify-between">
                    <div>
                        <div
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-white/10 text-amber-300 text-xs font-bold mb-3">
                            <span>⚖️</span>
                            <span>Konstitusi Organisasi</span>
                        </div>
                        <h4 class="text-lg font-bold mb-2 text-white">Landasan AD/ART Mengenai BPC</h4>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-4">
                            Tata cara pemilihan, struktur pimpinan, masa bakti 2 tahun, dan pertanggungjawaban Badan
                            Pengurus Cabang diatur secara sah dalam Anggaran Rumah Tangga (ART) GMKI Pasal 5 dan Pasal
                            6.
                        </p>
                    </div>
                    <div>
                        <a href="/ad-art#art-pasal-6"
                            class="btn btn-sm inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl px-4 py-2 transition-all shadow-xs">
                            <span>Pelajari ART Pasal 6 Tentang BPC</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <div
                    class="card rounded-2xl p-6 bg-white border border-slate-200/90 shadow-md flex flex-col justify-between">
                    <div>
                        <div
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 text-xs font-bold mb-3">
                            <span>📬</span>
                            <span>Kemitraan & Audiensi</span>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-2">Ingin Berkolaborasi dengan BPC?</h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Gereja, lembaga mahasiswa, perguruan tinggi, instansi pemerintah, media massa, maupun
                            organisasi kepemudaan yang ingin mengajukan audiensi, kemitraan program, atau mengundang
                            fungsionaris BPC dapat menghubungi kami.
                        </p>
                    </div>
                    <div>
                        <a href="/kontak"
                            class="btn btn-primary btn-sm inline-flex items-center gap-1.5 rounded-xl px-4 py-2 font-bold transition-all shadow-xs">
                            <span>Hubungi Sekretariat BPC Cabang Padang</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
    </section>
</div>

<!-- ================================================================= -->
<!-- STYLE TAMBAHAN & LOGIKA INTERAKTIF CLIENT-SIDE -->
<!-- ================================================================= -->
<style>
    .bpc-tab-btn {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .bpc-tab-btn:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    .bpc-tab-btn.active {
        background-color: var(--primary, #0f3d64);
        color: #ffffff;
        border-color: var(--primary, #0f3d64);
        box-shadow: 0 2px 6px rgba(15, 61, 100, 0.25);
    }

    .bpc-role-card {
        border-color: #e2e8f0;
    }

    .bpc-role-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-3px);
    }
</style>

<script>
    let activeRoleCategory = 'all';

    function switchRoleTab(category) {
        activeRoleCategory = category;

        // Update visual tab buttons
        document.querySelectorAll('#roleCategoryTabs .bpc-tab-btn').forEach(btn => {
            if (btn.getAttribute('data-tab') === category) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        applyRoleFilters();
    }

    function filterRoleCards(query) {
        applyRoleFilters();
    }

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

            if (matchesCategory && matchesQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noMatchBox = document.getElementById('noRoleMatches');
        if (noMatchBox) {
            if (visibleCount === 0) {
                noMatchBox.classList.remove('hidden');
            } else {
                noMatchBox.classList.add('hidden');
            }
        }
    }

    function resetRoleFilter() {
        const queryInput = document.getElementById('roleFilterInput');
        if (queryInput) queryInput.value = '';
        switchRoleTab('all');
    }
</script>