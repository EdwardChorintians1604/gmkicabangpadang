<!-- Portal Pendaftaran Terpadu Civitas & Kader Baru GMKI Cabang Padang -->
<section
    class="py-12 lg:py-16 bg-gradient-to-br from-slate-950 via-[#0a263f] to-[#0f3d64] text-white relative overflow-hidden">
    <!-- Ambient glow decorative circles -->
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-blue-500/20 blur-3xl pointer-events-none"></div>

    <div class="container relative z-10">
        <!-- Top Badge & Header -->
        <div class="max-w-3xl mx-auto text-center mb-10 animate__animated animate__fadeInDown">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-amber-300 text-xs sm:text-sm font-semibold mb-4 shadow-sm">
                <i class="bi bi-shield-check text-amber-300"></i>
                <span>Sistem Pendaftaran Hybrid &bull; <?= e($profile['tema_periode'] ?? 'Ut Omnes Unum Sint') ?></span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-4">
                Portal Registrasi Mandiri <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-400">Civitas
                    &amp; Calon Anggota</span>
            </h1>
            <p class="text-slate-200 text-sm sm:text-base lg:text-lg leading-relaxed max-w-2xl mx-auto">
                Sistem pendaftaran terbuka terpadu bagi mahasiswa Kristen di Kota Padang untuk bergabung dalam
                kaderisasi Maperca, serta pangkalan pemutakhiran data civitas resmi GMKI Cabang Padang.
            </p>
        </div>

        <!-- KPI Strip: Kondisi Basis Data Cabang Terkini -->
        <div class="max-w-4xl mx-auto mb-12">
            <div
                class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 p-4 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-center">
                <div class="p-3 rounded-xl bg-white/5 border border-white/5">
                    <div class="text-2xl sm:text-3xl font-extrabold text-amber-300">
                        <?= number_format((int) ($summary['total_civitas'] ?? 0)) ?>+
                    </div>
                    <div class="text-[11px] sm:text-xs font-semibold text-slate-300 mt-1">Total Civitas &amp; Kader
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-white/5 border border-white/5">
                    <div class="text-2xl sm:text-3xl font-extrabold text-emerald-400">
                        <?= number_format((int) ($summary['active_civitas'] ?? 0)) ?>
                    </div>
                    <div class="text-[11px] sm:text-xs font-semibold text-slate-300 mt-1">Anggota Aktif</div>
                </div>
                <div class="p-3 rounded-xl bg-white/5 border border-white/5">
                    <div class="text-2xl sm:text-3xl font-extrabold text-sky-300">
                        <?= number_format((int) ($summary['total_komisariat'] ?? 0)) ?>
                    </div>
                    <div class="text-[11px] sm:text-xs font-semibold text-slate-300 mt-1">Komisariat Kampus</div>
                </div>
                <div class="p-3 rounded-xl bg-white/5 border border-white/5 flex flex-col items-center justify-center">
                    <span
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-300 bg-emerald-950/60 px-2.5 py-1 rounded-full border border-emerald-400/30">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Aktif Online
                    </span>
                    <div class="text-[11px] sm:text-xs text-slate-400 mt-1">Sinkronisasi BPC</div>
                </div>
            </div>
        </div>

        <!-- Dua Jalur Pendaftaran (Grid 2 Kolom) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-16">
            <!-- Jalur 1: Calon Anggota (Maperca) -->
            <div
                class="group relative rounded-3xl bg-slate-900/80 backdrop-blur-xl border <?= !empty($isMapercaOpen) ? 'border-white/15 hover:border-amber-400/50' : 'border-amber-500/30' ?> p-7 sm:p-8 shadow-2xl transition-all duration-300 flex flex-col justify-between transform hover:-translate-y-1">
                <div
                    class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-amber-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                </div>
                <div>
                    <div class="flex items-center justify-between gap-3 mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-amber-500/20 border border-amber-400/40 text-amber-300 flex items-center justify-center shadow-inner group-hover:scale-105 group-hover:bg-amber-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="bi bi-mortarboard-fill text-3xl"></i>
                        </div>
                        <?php if (!empty($isMapercaOpen)): ?>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30 inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Jalur Kader Baru &bull; Dibuka
                            </span>
                        <?php else: ?>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 inline-flex items-center gap-1.5">
                                <i class="bi bi-lock-fill"></i> Pendaftaran Ditutup
                            </span>
                        <?php endif; ?>
                    </div>

                    <h2 class="text-2xl font-extrabold text-white mb-2 group-hover:text-amber-300 transition-colors">
                        Calon Anggota (Maperca)
                    </h2>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Khusus bagi mahasiswa Kristen baru di Kota Padang yang ingin bergabung ke GMKI dan mengikuti
                        Masa Perkenalan Calon Anggota (Maperca).
                    </p>

                    <?php if (empty($isMapercaOpen)): ?>
                        <div class="p-3.5 rounded-2xl bg-amber-500/15 border border-amber-400/30 text-amber-200 text-xs mb-6 flex items-start gap-2.5">
                            <i class="bi bi-info-circle-fill text-amber-400 text-base shrink-0 mt-0.5"></i>
                            <div class="leading-relaxed">
                                <strong>Pendaftaran Ditutup Sementara:</strong> <?= e($mapercaClosedMessage ?? 'Pendaftaran calon anggota baru (Maperca) saat ini sedang ditutup oleh BPC GMKI Cabang Padang. Nantikan pembukaan periode selanjutnya.') ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-3 mb-8 text-xs text-slate-200">
                        <div class="flex items-start gap-2.5">
                            <i class="bi bi-check-circle-fill text-amber-400 text-sm mt-0.5 flex-shrink-0"></i>
                            <span><strong>Kalkulasi ID Otomatis:</strong> Langsung mendapatkan ID Calon Anggota format
                                <code>[TahunMaper]-[ThnLahir]-[Inisial]</code>.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="bi bi-check-circle-fill text-amber-400 text-sm mt-0.5 flex-shrink-0"></i>
                            <span><strong>Kaderisasi Berjenjang:</strong> Terdaftar langsung dalam antrean Maperca dan
                                pembagian kelompok perkenalan.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="bi bi-check-circle-fill text-amber-400 text-sm mt-0.5 flex-shrink-0"></i>
                            <span><strong>Pelantikan Menjadi Anggota Sah:</strong> Setelah mengikuti Maperca, pengurus
                                melantik akun menjadi anggota biasa di panel BPC.</span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($isMapercaOpen)): ?>
                    <a href="/register_for_civitas/maperca"
                        class="btn btn-secondary w-full py-3.5 text-center font-bold text-base shadow-lg shadow-amber-950/40 inline-flex items-center justify-center gap-2 group-hover:shadow-amber-500/20 transition-all">
                        <span>Mulai Pendaftaran Maperca</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                <?php else: ?>
                    <div class="space-y-2">
                        <button type="button" disabled
                            class="w-full py-3.5 px-4 text-center font-bold text-sm bg-slate-800/90 text-slate-400 border border-white/10 rounded-2xl inline-flex items-center justify-center gap-2 cursor-not-allowed shadow-none">
                            <i class="bi bi-lock-fill text-amber-400"></i>
                            <span>Pendaftaran Ditutup Sementara</span>
                        </button>
                        <a href="https://wa.me/6281334427069" target="_blank" rel="noopener noreferrer"
                            class="text-center text-xs text-amber-300/90 hover:text-amber-300 hover:underline block font-semibold">
                            Hubungi Sekretariat untuk Jadwal Maperca &rarr;
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Jalur 2: Civitas / Anggota Terdaftar (Selalu Dibuka) -->
            <div
                class="group relative rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-white/15 p-7 sm:p-8 shadow-2xl hover:border-sky-400/50 transition-all duration-300 flex flex-col justify-between transform hover:-translate-y-1">
                <div
                    class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-blue-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                </div>
                <div>
                    <div class="flex items-center justify-between gap-3 mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-sky-500/20 border border-sky-400/40 text-sky-300 flex items-center justify-center shadow-inner group-hover:scale-105 group-hover:bg-sky-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="bi bi-person-vcard-fill text-3xl"></i>
                        </div>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Jalur Anggota Sah &bull; Selalu Dibuka
                        </span>
                    </div>

                    <h2 class="text-2xl font-extrabold text-white mb-2 group-hover:text-sky-300 transition-colors">
                        Civitas &amp; Anggota Cabang
                    </h2>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Bagi civitas aktif, anggota sah, atau senior/alumni untuk pemutakhiran data diri, verifikasi
                        KTA, dan integrasi pangkalan data cabang.
                    </p>

                    <div class="space-y-3 mb-8 text-xs text-slate-200">
                        <div class="flex items-start gap-2.5">
                            <i class="bi bi-check-circle-fill text-sky-400 text-sm mt-0.5 flex-shrink-0"></i>
                            <span><strong>Sinkronisasi Komisariat:</strong> Pemilihan komisariat kampus aktif dari basis
                                data resmi cabang.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="bi bi-check-circle-fill text-sky-400 text-sm mt-0.5 flex-shrink-0"></i>
                            <span><strong>Status Keanggotaan Lengkap:</strong> Mendukung status Aktif, Pasif, Alumni,
                                dan Senior sesuai ketentuan AD/ART.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="bi bi-check-circle-fill text-sky-400 text-sm mt-0.5 flex-shrink-0"></i>
                            <span><strong>Penyimpanan Berkas Privat:</strong> Pasfoto dan data pribadi disimpan dalam
                                storage privat terproteksi.</span>
                        </div>
                    </div>
                </div>

                <a href="/register_for_civitas/member"
                    class="btn btn-outline w-full py-3.5 text-center font-bold text-base text-black border-white/30 hover:bg-white hover:text-slate-900 hover:border-white inline-flex items-center justify-center gap-2 transition-all">
                    <span>Mulai Registrasi Civitas</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Alur Sistem Hybrid (Infografis 3 Langkah) -->
        <div class="max-w-4xl mx-auto mb-16">
            <div class="text-center mb-8">
                <span class="text-amber-400 font-bold uppercase tracking-wider text-xs">Arsitektur Alur Sistem</span>
                <h3 class="text-xl sm:text-2xl font-bold text-white mt-1">Bagaimana Sistem Registrasi Hybrid Bekerja?
                </h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 relative">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 font-extrabold flex items-center justify-center mb-4">
                        1
                    </div>
                    <h4 class="text-base font-bold text-white mb-2">Pengisian Mandiri (Self-Service)</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Calon anggota atau civitas mengisi formulir pendaftaran secara online. Algoritma otomatis
                        membentuk kode ID unik berbasis tahun maper, tanggal lahir, dan inisial nama.
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 relative">
                    <div
                        class="w-10 h-10 rounded-xl bg-sky-400/20 text-sky-300 font-extrabold flex items-center justify-center mb-4">
                        2
                    </div>
                    <h4 class="text-base font-bold text-white mb-2">Verifikasi Data oleh BPC</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Data langsung masuk ke panel administrasi pengurus (Superadmin / Sekcab / Bidang Kaderisasi).
                        Pengurus memeriksa kelengkapan data dan foto diri yang diunggah.
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 relative">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-400/20 text-emerald-300 font-extrabold flex items-center justify-center mb-4">
                        3
                    </div>
                    <h4 class="text-base font-bold text-white mb-2">Pelantikan &amp; Integrasi Penuh</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Kader baru dilantik secara resmi menjadi anggota cabang melalui tombol pelantikan di panel
                        admin, menetapkan komisariat kampus, dan mengaktifkan status keanggotaan penuh.
                    </p>
                </div>
            </div>
        </div>

        <!-- Bantuan & Narahubung -->
        <div
            class="max-w-3xl mx-auto rounded-2xl bg-white/5 border border-white/10 p-6 sm:p-8 text-center text-slate-300">
            <h3 class="text-lg font-bold text-white mb-2">Perlu Bantuan atau Konfirmasi Status Keanggotaan?</h3>
            <p class="text-xs sm:text-sm text-slate-300 mb-5 max-w-xl mx-auto">
                Jika Anda mengalami kendala teknis dalam pengisian data atau ingin menanyakan jadwal Masa Perkenalan
                (Maperca) terdekat, silakan hubungi Sekretariat BPC GMKI Cabang Padang.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 text-xs font-semibold">
                <a href="https://wa.me/6281334427069" target="_blank" rel="noopener noreferrer"
                    class="btn btn-outline btn-sm text-amber-300 border-amber-400/40 hover:bg-amber-400/10 inline-flex items-center gap-1.5">
                    <i class="bi bi-chat-dots-fill"></i> Hubungi Sekretaris Cabang
                </a>
                <a href="https://wa.me/6282169762552" target="_blank" rel="noopener noreferrer"
                    class="btn btn-outline btn-sm text-sky-300 border-sky-400/40 hover:bg-sky-400/10 inline-flex items-center gap-1.5">
                    <i class="bi bi-whatsapp"></i> Hubungi Administrator Langsung
                </a>
            </div>
        </div>
    </div>
</section>