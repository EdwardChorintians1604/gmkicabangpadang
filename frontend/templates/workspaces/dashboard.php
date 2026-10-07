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
?>
<header style="margin-bottom: 1.5rem;">
    <p class="text-muted" style="margin:0;">Ruang kerja sesuai tanggung jawab peran</p>
    <h1 style="font-size:1.8rem;font-weight:800;color:var(--primary);margin:.25rem 0;">Halo, <?= e($user['nama_lengkap'] ?? $user['username'] ?? '') ?></h1>
    <p class="text-muted" style="margin:0;"><?= e($roleNames[$role] ?? ucfirst($role)) ?> · GMKI Cabang Padang</p>
</header>

<div class="metrics-grid">
    <?php if (can('civitas.view')): ?>
        <div class="metric-card"><div class="metric-info"><span class="metric-label">Anggota terdata</span><span class="metric-value"><?= (int)$counts['members'] ?></span></div><div class="metric-icon-wrap metric-icon-primary">👥</div></div>
    <?php endif; ?>
    <?php if (can('inventory.view')): ?>
        <div class="metric-card"><div class="metric-info"><span class="metric-label">Catatan inventaris</span><span class="metric-value"><?= (int)$counts['inventory'] ?></span></div><div class="metric-icon-wrap metric-icon-secondary">📦</div></div>
    <?php endif; ?>
    <?php if (can('archive.view')): ?>
        <div class="metric-card"><div class="metric-info"><span class="metric-label">Arsip surat</span><span class="metric-value"><?= (int)$counts['archive'] ?></span></div><div class="metric-icon-wrap metric-icon-info">🗂️</div></div>
    <?php endif; ?>
    <?php if (can('finance.view') || can('reports.view')): ?>
        <div class="metric-card"><div class="metric-info"><span class="metric-label">Laporan keuangan</span><span class="metric-value"><?= (int)$counts['finance'] ?></span></div><div class="metric-icon-wrap metric-icon-success">💰</div></div>
    <?php endif; ?>
    <?php if (can('coordination.view')): ?>
        <div class="metric-card"><div class="metric-info"><span class="metric-label">Pesan untuk peran Anda</span><span class="metric-value"><?= (int)$counts['messages'] ?></span></div><div class="metric-icon-wrap metric-icon-info">💬</div></div>
    <?php endif; ?>
    <?php if (can('strategy.view')): ?>
        <div class="metric-card"><div class="metric-info"><span class="metric-label">Catatan strategi</span><span class="metric-value"><?= (int)$counts['strategies'] ?></span></div><div class="metric-icon-wrap metric-icon-secondary">🧭</div></div>
    <?php endif; ?>
</div>

<section class="card" style="margin-top:1.5rem;">
    <div class="card-body">
        <h2 style="font-size:1.2rem;font-weight:750;margin:0 0 .35rem;">Pintasan ruang kerja</h2>
        <p class="text-muted" style="margin:0 0 1rem;">Akses hanya menampilkan modul yang diizinkan untuk peran Anda.</p>
        <div class="flex gap-2" style="flex-wrap:wrap;">
            <?php if (can('civitas.view')): ?><a class="btn btn-outline" href="/admin/civitas">Data anggota</a><?php endif; ?>
            <?php if (can('inventory.view')): ?><a class="btn btn-outline" href="/sekcab/inventaris">Inventaris</a><?php endif; ?>
            <?php if (can('archive.view')): ?><a class="btn btn-outline" href="/sekcab/arsip">Arsip surat</a><?php endif; ?>
            <?php if (can('finance.view') || can('reports.view')): ?><a class="btn btn-outline" href="/bencab/laporan">Laporan keuangan</a><?php endif; ?>
            <?php if (can('strategy.view')): ?><a class="btn btn-outline" href="/ketcab/strategi">Strategi organisasi</a><?php endif; ?>
            <?php if (can('coordination.view')): ?>
                <?php $coordinationPath = $role === 'admin' ? '/admin/koordinasi' : ($role === 'ketcab' ? '/ketcab/koordinasi' : '/ruang-kerja/koordinasi'); ?>
                <a class="btn btn-primary" href="<?= e($coordinationPath) ?>">Koordinasi &amp; request</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($role === 'bencab'): ?>
    <p class="text-muted" style="margin-top:1rem;">Ruang Bendahara hanya dapat mengubah atau menghapus laporan yang dibuat oleh akun Anda. Berkas dibagikan melalui akses baca/unduh kepada Ketcab.</p>
<?php elseif ($role === 'ketcab'): ?>
    <p class="text-muted" style="margin-top:1rem;">Panel ini untuk pengawasan, strategi, laporan, dan koordinasi. Pengelolaan akun dan konfigurasi sistem tetap berada di luar wewenang Ketcab.</p>
<?php endif; ?>
