<?php
$roleNames = [
    'admin' => 'Administrator Sistem',
    'ketcab' => 'Ketua Cabang',
    'sekcab' => 'Sekretaris Cabang',
    'bencab' => 'Bendahara Cabang',
    'sekfung_medko' => 'Sekfung Medko',
    'operator' => 'Operator Data & Warta',
];
$role = $user['role'] ?? '';
$namaLengkap = $user['nama_lengkap'] ?? $user['username'] ?? 'Fungsionaris BPC';
$heroClass = match ($role) {
    'bencab' => 'workspace-hero-bencab',
    'sekcab' => 'workspace-hero-sekcab',
    'ketcab' => 'workspace-hero-ketcab',
    default => '',
};

$roleBadges = [
    'ketcab' => '👑 PUSAT KOMANDO & PENGAWASAN KETCAB',
    'sekcab' => '🗂️ TATA USAHA & KESEKRETARIATAN SEKCAB',
    'bencab' => '💰 AKUNTABILITAS & PEMBUKUAN KEUANGAN BENCAB',
    'sekfung_medko' => '📡 MEDIA, KOMUNIKASI & MULTIMEDIA CABANG',
    'admin' => '🛡️ ADMINISTRATOR SISTEM CABANG',
    'operator' => '📝 OPERATOR DATA & WARTA',
];

$roleMottos = [
    'ketcab' => 'Memastikan kesinambungan perarakan di tiga medan layan: Gereja, Perguruan Tinggi, dan Masyarakat melalui perumusan arah kebijakan strategis organisasi.',
    'sekcab' => 'Ketertiban tata persuratan, integritas duplikasi arsip SHA-256, manajemen aset inventaris cabang, dan pemeliharaan pangkalan data civitas.',
    'bencab' => 'Tata kelola kas, transparansi pembukuan laporan pertanggungjawaban, isolasi berkas keuangan resmi, dan pemrosesan antrean ekspor latar belakang.',
    'sekfung_medko' => 'Pengelolaan portal warta, publikasi kegiatan, integrasi sistem informasi cabang, dan koordinasi multimedia.',
    'default' => 'Melayani dengan amanat Tri Panji GMKI: Tinggi Iman, Tinggi Ilmu, Tinggi Pengabdian di Kota Padang.',
];
?>

<!-- 1. Executive Hero Header -->
<div class="workspace-hero <?= e($heroClass) ?>">
    <div class="workspace-hero-content">
        <div>
            <div class="workspace-hero-badge">
                <span><?= $roleBadges[$role] ?? $roleBadges['default'] ?></span>
                <span>•</span>
                <span>Periode <?= in_array($role, ['ketcab', 'sekcab', 'bencab'], true) ? '2026–2027' : '2024–2026' ?></span>
            </div>
            <h1 class="workspace-hero-title">
                Halo, <?= e($namaLengkap) ?>
            </h1>
            <p class="workspace-hero-desc">
                <?= e($roleMottos[$role] ?? $roleMottos['default']) ?>
            </p>
        </div>
        <div class="workspace-hero-actions">
            <?php if ($role === 'ketcab'): ?>
                <a href="/ketcab/strategi" class="btn btn-primary" style="background:#f59e0b;border-color:#d97706;color:#0f2d4a;font-weight:750;">
                    🧭 Catat Strategi
                </a>
                <a href="/ketcab/koordinasi" class="btn btn-outline" style="background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.3);color:#ffffff;">
                    💬 Ruang Koordinasi
                </a>
            <?php elseif ($role === 'sekcab'): ?>
                <a href="/sekcab/arsip" class="btn btn-primary" style="background:#3b82f6;border-color:#2563eb;color:#ffffff;font-weight:750;">
                    + Catat Surat Baru
                </a>
                <a href="/sekcab/inventaris" class="btn btn-outline" style="background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.3);color:#ffffff;">
                    📦 Input Inventaris
                </a>
            <?php elseif ($role === 'bencab'): ?>
                <a href="/bencab/laporan" class="btn btn-primary" style="background:#10b981;border-color:#059669;color:#ffffff;font-weight:750;">
                    + Unggah Laporan
                </a>
                <a href="/ruang-kerja/antrian" class="btn btn-outline" style="background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.3);color:#ffffff;">
                    📥 Antrean Ekspor
                </a>
            <?php else: ?>
                <a href="/ruang-kerja/koordinasi" class="btn btn-primary">
                    💬 Koordinasi BPC
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- 2. Balanced 4-Stat Metric Grid -->
<div class="metrics-grid">
    <?php if ($role === 'bencab'): ?>
        <!-- Bencab Metric Set (4 Cards) -->
        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Laporan Keuangan</span>
                <span class="metric-value"><?= (int)$counts['finance'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Berkas kas resmi terbit</span>
            </div>
            <div class="metric-icon-wrap metric-icon-success">💰</div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Format Dokumen</span>
                <span class="metric-value" style="font-size:1.35rem;">PDF, XLS, DOC</span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Berkas pembukuan &amp; nota</span>
            </div>
            <div class="metric-icon-wrap metric-icon-primary">📊</div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Pesan Koordinasi</span>
                <span class="metric-value"><?= (int)$counts['messages'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Diskusi anggaran BPC</span>
            </div>
            <div class="metric-icon-wrap metric-icon-info">💬</div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Status Tata Kelola</span>
                <span class="metric-value" style="font-size:1.35rem;color:#059669;">Teruji &amp; Transparan</span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Sinkron Pengawasan Ketcab</span>
            </div>
            <div class="metric-icon-wrap" style="background:#e0f2fe;color:#0284c7;">⚖️</div>
        </div>

    <?php elseif ($role === 'ketcab'): ?>
        <!-- Ketcab Metric Set (4 Cards) -->
        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Civitas &amp; Anggota</span>
                <span class="metric-value"><?= (int)$counts['members'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Kader se-Kota Padang</span>
            </div>
            <div class="metric-icon-wrap metric-icon-primary">👥</div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Laporan Keuangan</span>
                <span class="metric-value"><?= (int)$counts['finance'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Akses pantau kas BPC</span>
            </div>
            <div class="metric-icon-wrap metric-icon-success">💰</div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Pesan Koordinasi</span>
                <span class="metric-value"><?= (int)$counts['messages'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Arahan pimpinan aktif</span>
            </div>
            <div class="metric-icon-wrap metric-icon-info">💬</div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Catatan Strategi</span>
                <span class="metric-value"><?= (int)$counts['strategies'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Target arah kebijakan</span>
            </div>
            <div class="metric-icon-wrap metric-icon-secondary">🧭</div>
        </div>

    <?php elseif ($role === 'sekcab'): ?>
        <!-- Sekcab Metric Set (4 Cards) -->
        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Civitas &amp; Anggota</span>
                <span class="metric-value"><?= (int)$counts['members'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Database keanggotaan</span>
            </div>
            <div class="metric-icon-wrap metric-icon-primary">👥</div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Catatan Inventaris</span>
                <span class="metric-value"><?= (int)$counts['inventory'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Aset fisik &amp; perlengkapan</span>
            </div>
            <div class="metric-icon-wrap metric-icon-secondary">📦</div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Arsip Surat Resmi</span>
                <span class="metric-value"><?= (int)$counts['archive'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Duplikasi SHA-256 aktif</span>
            </div>
            <div class="metric-icon-wrap" style="background:#e0e7ff;color:#4338ca;">🗂️</div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Pesan Koordinasi</span>
                <span class="metric-value"><?= (int)$counts['messages'] ?></span>
                <span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Komunikasi dinas BPC</span>
            </div>
            <div class="metric-icon-wrap metric-icon-info">💬</div>
        </div>

    <?php else: ?>
        <!-- Generic / Operator / Medko Metric Set -->
        <?php if (can('civitas.view')): ?>
            <div class="metric-card"><div class="metric-info"><span class="metric-label">Anggota terdata</span><span class="metric-value"><?= (int)$counts['members'] ?></span><span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Kader cabang</span></div><div class="metric-icon-wrap metric-icon-primary">👥</div></div>
        <?php endif; ?>
        <?php if (can('inventory.view')): ?>
            <div class="metric-card"><div class="metric-info"><span class="metric-label">Catatan inventaris</span><span class="metric-value"><?= (int)$counts['inventory'] ?></span><span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Logistik cabang</span></div><div class="metric-icon-wrap metric-icon-secondary">📦</div></div>
        <?php endif; ?>
        <?php if (can('archive.view')): ?>
            <div class="metric-card"><div class="metric-info"><span class="metric-label">Arsip surat</span><span class="metric-value"><?= (int)$counts['archive'] ?></span><span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Dokumen resmi</span></div><div class="metric-icon-wrap metric-icon-info">🗂️</div></div>
        <?php endif; ?>
        <?php if (can('finance.view') || can('reports.view')): ?>
            <div class="metric-card"><div class="metric-info"><span class="metric-label">Laporan keuangan</span><span class="metric-value"><?= (int)$counts['finance'] ?></span><span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Pembukuan kas</span></div><div class="metric-icon-wrap metric-icon-success">💰</div></div>
        <?php endif; ?>
        <?php if (can('coordination.view')): ?>
            <div class="metric-card"><div class="metric-info"><span class="metric-label">Pesan koordinasi</span><span class="metric-value"><?= (int)$counts['messages'] ?></span><span class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">Komunikasi peran</span></div><div class="metric-icon-wrap metric-icon-info">💬</div></div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- 3. Executive Quick Launchpad Tiles -->
<section style="margin-top: 2rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;flex-wrap:wrap;gap:0.5rem;">
        <div>
            <h2 style="font-size:1.25rem;font-weight:800;color:var(--primary);margin:0;">Pusat Modul &amp; Pintasan Kerja</h2>
            <p class="text-muted" style="margin:0.2rem 0 0;font-size:0.85rem;">Akses instan ke seluruh modul operasional yang diotorisasi untuk peran Anda.</p>
        </div>
        <span class="badge badge-primary" style="font-size:0.75rem;">Otoritas Terverifikasi</span>
    </div>

    <div class="launchpad-grid">
        <?php if ($role === 'bencab'): ?>
            <a href="/bencab/laporan" class="launchpad-card">
                <div class="launchpad-icon" style="background:#dcfce7;color:#15803d;">💰</div>
                <div class="launchpad-info">
                    <h4>Laporan Keuangan</h4>
                    <p>Terbitkan &amp; kelola berkas pembukuan, LPJ kegiatan, serta kas resmi BPC.</p>
                </div>
            </a>
            <a href="/bencab/berkas" class="launchpad-card">
                <div class="launchpad-icon" style="background:#e0f2fe;color:#0284c7;">📊</div>
                <div class="launchpad-info">
                    <h4>Direktori Berkas Keuangan</h4>
                    <p>Penelusuran arsip spreadsheet Excel, PDF, dan bukti transaksi kas.</p>
                </div>
            </a>
            <a href="/ruang-kerja/antrian" class="launchpad-card">
                <div class="launchpad-icon" style="background:#fef3c7;color:#b45309;">📥</div>
                <div class="launchpad-info">
                    <h4>Antrean Ekspor CSV</h4>
                    <p>Proses &amp; unduh rekapitulasi data keuangan cabang di latar belakang.</p>
                </div>
            </a>
            <a href="/ruang-kerja/koordinasi" class="launchpad-card">
                <div class="launchpad-icon" style="background:#eff6ff;color:#2563eb;">💬</div>
                <div class="launchpad-info">
                    <h4>Ruang Koordinasi BPC</h4>
                    <p>Saluran komunikasi permohonan anggaran dan arahan bersama pimpinan.</p>
                </div>
            </a>

        <?php elseif ($role === 'ketcab'): ?>
            <a href="/ketcab/strategi" class="launchpad-card">
                <div class="launchpad-icon" style="background:#fef3c7;color:#b45309;">🧭</div>
                <div class="launchpad-info">
                    <h4>Strategi Organisasi</h4>
                    <p>Rumuskan target, sasaran kebijakan, dan langkah taktis BPC periode aktif.</p>
                </div>
            </a>
            <a href="/bencab/laporan" class="launchpad-card">
                <div class="launchpad-icon" style="background:#dcfce7;color:#15803d;">💰</div>
                <div class="launchpad-info">
                    <h4>Pantauan Laporan Keuangan</h4>
                    <p>Akses pemantauan berkas pembukuan kas resmi cabang dari Bendahara.</p>
                </div>
            </a>
            <a href="/admin/civitas" class="launchpad-card">
                <div class="launchpad-icon" style="background:#e0f2fe;color:#0284c7;">👥</div>
                <div class="launchpad-info">
                    <h4>Pangkalan Data Civitas</h4>
                    <p>Tinjau persebaran kader, komisariat kampus, dan mutasi anggota.</p>
                </div>
            </a>
            <a href="/ketcab/koordinasi" class="launchpad-card">
                <div class="launchpad-icon" style="background:#eff6ff;color:#2563eb;">💬</div>
                <div class="launchpad-info">
                    <h4>Ruang Koordinasi BPC</h4>
                    <p>Berikan instruksi langsung dan bimbingan lintas fungsionaris cabang.</p>
                </div>
            </a>
            <a href="/admin/statistik" class="launchpad-card">
                <div class="launchpad-icon" style="background:#f3e8ff;color:#7e22ce;">📈</div>
                <div class="launchpad-info">
                    <h4>Statistik &amp; Grafik</h4>
                    <p>Grafik rasio gender, pertumbuhan kader Maperca, dan komisariat.</p>
                </div>
            </a>

        <?php elseif ($role === 'sekcab'): ?>
            <a href="/sekcab/inventaris" class="launchpad-card">
                <div class="launchpad-icon" style="background:#fef3c7;color:#b45309;">📦</div>
                <div class="launchpad-info">
                    <h4>Inventaris &amp; Aset Cabang</h4>
                    <p>Catat barang milik cabang, kondisi pemeliharaan, dan lokasi penyimpanan.</p>
                </div>
            </a>
            <a href="/sekcab/arsip" class="launchpad-card">
                <div class="launchpad-icon" style="background:#e0e7ff;color:#4338ca;">🗂️</div>
                <div class="launchpad-info">
                    <h4>Buku Agenda Arsip Surat</h4>
                    <p>Registrasi surat resmi dengan pembuatan cadangan duplikasi otomatis.</p>
                </div>
            </a>
            <a href="/sekcab/berkas" class="launchpad-card">
                <div class="launchpad-icon" style="background:#e0f2fe;color:#0284c7;">📁</div>
                <div class="launchpad-info">
                    <h4>Direktori Berkas Persuratan</h4>
                    <p>Penelusuran berkas master vs backup dengan checksum SHA-256 identik.</p>
                </div>
            </a>
            <a href="/admin/civitas" class="launchpad-card">
                <div class="launchpad-icon" style="background:#dcfce7;color:#15803d;">👥</div>
                <div class="launchpad-info">
                    <h4>Pangkalan Data Civitas</h4>
                    <p>Pantau data anggota aktif, telusuri profil, dan unduh rekapitulasi (Excel &amp; CSV).</p>
                </div>
            </a>
            <a href="/admin/maperca" class="launchpad-card">
                <div class="launchpad-icon" style="background:#fef3c7;color:#b45309;">🎓</div>
                <div class="launchpad-info">
                    <h4>Data Kader Baru (Maperca)</h4>
                    <p>Melihat daftar calon kader baru Maperca, pencarian, dan unduh rekapitulasi data.</p>
                </div>
            </a>
            <a href="/admin/komisariat" class="launchpad-card">
                <div class="launchpad-icon" style="background:#e0f2fe;color:#0369a1;">🏛️</div>
                <div class="launchpad-info">
                    <h4>Data Komisariat</h4>
                    <p>Melihat persebaran komisariat di perguruan tinggi, pantau anggota, dan unduh data.</p>
                </div>
            </a>
            <a href="/ruang-kerja/koordinasi" class="launchpad-card">
                <div class="launchpad-icon" style="background:#eff6ff;color:#2563eb;">💬</div>
                <div class="launchpad-info">
                    <h4>Ruang Koordinasi BPC</h4>
                    <p>Koordinasi dinas persuratan dan disposisi tugas bersama Ketcab &amp; Bencab.</p>
                </div>
            </a>

        <?php else: ?>
            <?php if (can('civitas.view')): ?>
                <a href="/admin/civitas" class="launchpad-card">
                    <div class="launchpad-icon" style="background:#e0f2fe;color:#0284c7;">👥</div>
                    <div class="launchpad-info"><h4>Data Anggota</h4><p>Pangkalan data civitas dan kader GMKI Padang.</p></div>
                </a>
            <?php endif; ?>
            <?php if (can('inventory.view')): ?>
                <a href="/sekcab/inventaris" class="launchpad-card">
                    <div class="launchpad-icon" style="background:#fef3c7;color:#b45309;">📦</div>
                    <div class="launchpad-info"><h4>Inventaris</h4><p>Pencatatan aset dan logistik cabang.</p></div>
                </a>
            <?php endif; ?>
            <?php if (can('archive.view')): ?>
                <a href="/sekcab/arsip" class="launchpad-card">
                    <div class="launchpad-icon" style="background:#e0e7ff;color:#4338ca;">🗂️</div>
                    <div class="launchpad-info"><h4>Arsip Surat</h4><p>Buku agenda dan penyimpanan dokumen resmi.</p></div>
                </a>
            <?php endif; ?>
            <?php if (can('finance.view') || can('reports.view')): ?>
                <a href="/bencab/laporan" class="launchpad-card">
                    <div class="launchpad-icon" style="background:#dcfce7;color:#15803d;">💰</div>
                    <div class="launchpad-info"><h4>Laporan Keuangan</h4><p>Dokumen kas dan transparansi keuangan cabang.</p></div>
                </a>
            <?php endif; ?>
            <?php if (can('coordination.view')): ?>
                <?php $coordPath = $role === 'admin' ? '/admin/koordinasi' : ($role === 'ketcab' ? '/ketcab/koordinasi' : '/ruang-kerja/koordinasi'); ?>
                <a href="<?= e($coordPath) ?>" class="launchpad-card">
                    <div class="launchpad-icon" style="background:#eff6ff;color:#2563eb;">💬</div>
                    <div class="launchpad-info"><h4>Koordinasi BPC</h4><p>Diskusi dan permohonan antar fungsionaris.</p></div>
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- 4. Role-Specific Guidance & Status Panel -->
<?php if ($role === 'ketcab'): ?>
    <!-- Tri Panji GMKI Padang untuk Ketcab -->
    <section style="margin-top: 2rem;">
        <div class="card" style="border-top: 4px solid var(--secondary);">
            <div class="card-body" style="padding: 1.5rem 1.75rem;">
                <div class="flex items-center justify-between" style="flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <div>
                        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 0;">Pilar Tri Panji GMKI Cabang Padang</h3>
                        <p class="text-muted" style="margin: 0.2rem 0 0; font-size: 0.85rem;">Landasan perarakan dan arah kepemimpinan di Gereja, Perguruan Tinggi, dan Masyarakat.</p>
                    </div>
                    <span class="badge badge-warning" style="font-size: 0.75rem; font-weight: 700;">Amanat Pelayanan</span>
                </div>

                <div class="tripanji-grid">
                    <div class="card" style="background:#f8fafc;border:1px solid #e2e8f0;">
                        <div class="card-body" style="padding:1.15rem;">
                            <div style="font-weight:750;color:var(--primary);margin-bottom:0.35rem;display:flex;align-items:center;gap:0.4rem;">
                                <span>✝️</span> <span>Tinggi Iman</span>
                            </div>
                            <p class="text-muted" style="font-size:0.8rem;margin:0;line-height:1.5;">
                                Spiritualitas yang teguh, keteladanan moral Kristiani, dan pemahaman firman kontekstual di tengah pergumulan kampus.
                            </p>
                        </div>
                    </div>

                    <div class="card" style="background:#f8fafc;border:1px solid #e2e8f0;">
                        <div class="card-body" style="padding:1.15rem;">
                            <div style="font-weight:750;color:var(--secondary);margin-bottom:0.35rem;display:flex;align-items:center;gap:0.4rem;">
                                <span>📖</span> <span>Tinggi Ilmu</span>
                            </div>
                            <p class="text-muted" style="font-size:0.8rem;margin:0;line-height:1.5;">
                                Nalar kritis, wawasan kebangsaan, keunggulan akademis, serta kesiapan memimpin masa depan gereja dan bangsa.
                            </p>
                        </div>
                    </div>

                    <div class="card" style="background:#f8fafc;border:1px solid #e2e8f0;">
                        <div class="card-body" style="padding:1.15rem;">
                            <div style="font-weight:750;color:#10b981;margin-bottom:0.35rem;display:flex;align-items:center;gap:0.4rem;">
                                <span>🤝</span> <span>Tinggi Pengabdian</span>
                            </div>
                            <p class="text-muted" style="font-size:0.8rem;margin:0;line-height:1.5;">
                                Advokasi keadilan sosial, aksi nyata pembelaan kaum marjinal, dan kesaksian hidup berintegritas di Kota Padang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php elseif ($role === 'sekcab'): ?>
    <section style="margin-top: 2rem;">
        <div class="card" style="border-left: 4px solid #3b82f6; background: #eff6ff;">
            <div class="card-body" style="padding: 1.25rem 1.5rem;">
                <div class="flex items-center gap-2" style="font-weight: 750; color: #1e40af; font-size: 0.95rem; margin-bottom: 0.35rem;">
                    <span>🛡️</span>
                    <span>Protokol Keamanan &amp; Perlindungan Duplikasi Arsip Sekcab</span>
                </div>
                <p style="margin: 0; font-size: 0.85rem; color: #1e3a8a; line-height: 1.55;">
                    Setiap berkas surat yang diunggah otomatis digandakan menjadi <strong>Salinan Cadangan Redundan (Backup)</strong> dengan <em>checksum SHA-256 identik 100%</em> guna melindungi data resmi organisasi dari risiko kerusakan atau manipulasi file.
                </p>
            </div>
        </div>
    </section>

<?php elseif ($role === 'bencab'): ?>
    <section style="margin-top: 2rem;">
        <div class="card" style="border-left: 4px solid #10b981; background: #ecfdf5;">
            <div class="card-body" style="padding: 1.25rem 1.5rem;">
                <div class="flex items-center gap-2" style="font-weight: 750; color: #065f46; font-size: 0.95rem; margin-bottom: 0.35rem;">
                    <span>⚖️</span>
                    <span>Protokol Akuntabilitas &amp; Isolasi Dokumen Keuangan Bencab</span>
                </div>
                <p style="margin: 0; font-size: 0.85rem; color: #047857; line-height: 1.55;">
                    Ruang kerja Bendahara Cabang memiliki isolasi penulisan penuh (hanya pembuat yang dapat mengedit/menghapus laporannya). Berkas dibagikan secara transparan kepada Ketua Cabang (Ketcab) dengan hak baca dan unduh. Pembuatan rekapitulasi data diproses melalui sistem antrean latar belakang.
                </p>
            </div>
        </div>
    </section>
<?php endif; ?>
