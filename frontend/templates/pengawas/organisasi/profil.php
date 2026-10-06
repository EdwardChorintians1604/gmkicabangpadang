<div style="max-width: 900px; margin: 0 auto;">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 m-0">
                    Profil & Amanat Pelayanan Cabang
                </h1>
                <span class="badge badge-warning text-xs">BACA-SAJA</span>
            </div>
            <p class="text-slate-500 text-xs sm:text-sm m-0">
                Dokumen identitas organisasi, Tri Panji, visi-misi, dan legalitas GMKI Cabang Padang.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="/profil" target="_blank" class="btn btn-outline btn-sm flex items-center justify-center gap-1 w-full sm:w-auto">
                <?= svg_icon('external', 14) ?>
                <span>Lihat Profil Publik</span>
            </a>
            <button onclick="window.print()" class="btn btn-secondary btn-sm flex items-center justify-center gap-1 w-full sm:w-auto">
                <?= svg_icon('printer', 14) ?>
                <span>Cetak Profil</span>
            </button>
        </div>
    </div>

    <!-- Main Profile Overview -->
    <div class="card mb-6">
        <div class="card-body p-4 sm:p-6 md:p-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4 mb-6 pb-6 border-b border-slate-200">
                <img src="<?= asset('images/logo-gmki.png') ?>" alt="Logo GMKI" style="width: 72px; height: 72px; object-fit: contain;">
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--primary); margin: 0;">
                        <?= e($profile['nama_organisasi'] ?? 'GMKI Cabang Padang') ?>
                    </h2>
                    <div style="color: var(--secondary); font-weight: 700; font-size: 1rem; margin-top: 0.25rem;">
                        <?= e($profile['slogan'] ?? 'Ut Omnes Unum Sint - Syalom!') ?>
                    </div>
                </div>
            </div>

            <!-- Tema & Sub-tema -->
            <div class="card" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border-left: 4px solid var(--primary); margin-bottom: 2rem; padding: 1.25rem;">
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem;">
                    Tema Kepengurusan Periode Aktif
                </div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">
                    "<?= e($profile['tema_periode'] ?? 'Bangkitlah, Menjadi Teranglah!') ?>"
                </h3>
                <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">
                    <strong>Sub-Tema:</strong> <?= e($profile['sub_tema'] ?? 'Mewujudkan Kader GMKI yang Berintegritas, Inklusif, dan Berdampak di Tengah Medan Pelayanan.') ?>
                </p>
            </div>

            <!-- Visi & Misi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="card" style="border: 1px solid var(--border-color); padding: 1.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 0.75rem;">
                        Visi Cabang
                    </h3>
                    <p style="margin: 0; line-height: 1.7; color: var(--text-main); font-size: 0.925rem;">
                        <?= nl2br(e($profile['visi'] ?? 'Terwujudnya kedamaian, keadilan, kebenaran dan kesejahteraan di tengah gereja, perguruan tinggi, dan masyarakat.')) ?>
                    </p>
                </div>
                <div class="card" style="border: 1px solid var(--border-color); padding: 1.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 0.75rem;">
                        Misi Cabang
                    </h3>
                    <p style="margin: 0; line-height: 1.7; color: var(--text-main); font-size: 0.925rem;">
                        <?= nl2br(e($profile['misi'] ?? '1. Mengajak mahasiswa untuk mengenal Yesus Kristus sebagai Tuhan dan Juruselamat.\n2. Menanamkan kesadaran oikumenis dan nasionalis.\n3. Mempersiapkan pemimpin dan pelayan yang berwawasan luas.')) ?>
                    </p>
                </div>
            </div>

            <!-- Tri Panji & Sejarah -->
            <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin-bottom: 0.75rem;">
                    Tri Panji GMKI
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="card text-center" style="background: var(--bg-subtle); padding: 1.25rem;">
                        <div style="font-size: 1.5rem; margin-bottom: 0.25rem;">✝️</div>
                        <strong style="color: var(--primary);">TINGGI IMAN</strong>
                        <p class="text-muted" style="margin: 0.25rem 0 0; font-size: 0.8rem;">Spiritualitas & Takut Akan Tuhan</p>
                    </div>
                    <div class="card text-center" style="background: var(--bg-subtle); padding: 1.25rem;">
                        <div style="font-size: 1.5rem; margin-bottom: 0.25rem;">📚</div>
                        <strong style="color: var(--primary);">TINGGI ILMU</strong>
                        <p class="text-muted" style="margin: 0.25rem 0 0; font-size: 0.8rem;">Intelektualitas & Kapabilitas Akademik</p>
                    </div>
                    <div class="card text-center" style="background: var(--bg-subtle); padding: 1.25rem;">
                        <div style="font-size: 1.5rem; margin-bottom: 0.25rem;">🤝</div>
                        <strong style="color: var(--primary);">TINGGI PENGABDIAN</strong>
                        <p class="text-muted" style="margin: 0.25rem 0 0; font-size: 0.8rem;">Aksi Nyata di Tiga Medan Pelayanan</p>
                    </div>
                </div>
            </div>

            <!-- Sejarah Singkat -->
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin-bottom: 0.75rem;">
                    Sejarah Singkat GMKI Cabang Padang
                </h3>
                <div style="line-height: 1.8; color: var(--text-main); font-size: 0.95rem;">
                    <?= nl2br(e($profile['sejarah'] ?? 'Gerakan Mahasiswa Kristen Indonesia (GMKI) Cabang Padang hadir sebagai wadah persekutuan, pembinaan, dan perjuangan bagi mahasiswa Kristen di Kota Padang untuk melayani Gereja, Perguruan Tinggi, dan Masyarakat.')) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Kontak & Sekretariat -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1rem;">Sekretariat & Komunikasi Resmi</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <div class="text-muted" style="font-size: 0.8rem; margin-bottom: 0.25rem;">ALAMAT SEKRETARIAT</div>
                    <p style="margin: 0; font-weight: 600;"><?= e($profile['alamat_sekretariat'] ?? 'Jl. Gereja No. 1, Kota Padang') ?></p>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.8rem; margin-bottom: 0.25rem;">EMAIL RESMI</div>
                    <p style="margin: 0; font-weight: 600;"><?= e($profile['email_resmi'] ?? 'gmkicabangpadang@gmail.com') ?></p>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.8rem; margin-bottom: 0.25rem;">TELEPON / WHATSAPP</div>
                    <p style="margin: 0; font-weight: 600;"><?= e($profile['no_telp_resmi'] ?? '+62 812-3456-7890') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
