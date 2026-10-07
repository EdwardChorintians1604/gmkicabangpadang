<?php
$role = auth()['role'] ?? '';
$roleNames = [
    'ketcab' => 'Ketua Cabang',
    'sekcab' => 'Sekretaris Cabang',
    'bencab' => 'Bendahara Cabang',
    'sekfung_medko' => 'Sekfung Medko',
];
$coordinationPath = match ($role) {
    'admin' => '/admin/koordinasi',
    'ketcab' => '/ketcab/koordinasi',
    default => '/ruang-kerja/koordinasi',
};
$navigation = [
    'ketcab' => [
        ['Pusat Pengawasan', [
            ['/ketcab/dashboard', 'dashboard', 'Dashboard Ketcab'],
            ['/ketcab/strategi', 'chart', 'Strategi Organisasi'],
            ['/bencab/laporan', 'download', 'Laporan Keuangan'],
            ['/ruang-kerja/antrian', 'chart', 'Antrean Ekspor'],
            ['/admin/civitas', 'users', 'Pantauan Anggota'],
            ['/admin/statistik', 'chart', 'Statistik Cabang'],
        ]],
    ],
    'sekcab' => [
        ['Administrasi Cabang', [
            ['/ruang-kerja', 'dashboard', 'Dashboard Sekcab'],
            ['/admin/civitas', 'users', 'Data Anggota'],
            ['/admin/maperca', 'users', 'Kaderisasi Maperca'],
            ['/admin/komisariat', 'organization', 'Komisariat'],
            ['/sekcab/inventaris', 'organization', 'Inventaris'],
            ['/sekcab/arsip', 'backup', 'Arsip Surat'],
            ['/admin/statistik', 'chart', 'Statistik Anggota'],
        ]],
    ],
    'bencab' => [
        ['Keuangan Cabang', [
            ['/ruang-kerja', 'dashboard', 'Dashboard Bencab'],
            ['/bencab/laporan', 'download', 'Laporan Keuangan'],
            ['/ruang-kerja/antrian', 'chart', 'Antrean Ekspor'],
        ]],
    ],
    'sekfung_medko' => [
        ['Media & Komunikasi', [
            ['/ruang-kerja', 'dashboard', 'Dashboard Medko'],
            ['/admin/berita', 'news', 'Kelola Warta'],
            ['/admin/berita/create', 'plus', 'Tulis Warta'],
            ['/admin/statistik', 'chart', 'Statistik Konten'],
        ]],
    ],
];
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
<aside class="dashboard-sidebar officer-sidebar">
    <div class="sidebar-brand flex items-center justify-between">
        <div class="flex items-center gap-2">
            <img src="<?= asset('images/GMKI-Logos.png') ?>" alt="Logo GMKI" class="brand-logo" style="width:36px;height:36px;object-fit:contain;">
            <div>
                <div class="sidebar-brand-title">GMKI Padang</div>
                <div class="sidebar-brand-sub"><?= e($roleNames[$role] ?? 'Pengurus BPC') ?></div>
            </div>
        </div>
        <button type="button" class="sidebar-close-btn" aria-label="Tutup menu">✕</button>
    </div>
    <ul class="sidebar-nav">
        <?php foreach ($navigation[$role] ?? [] as [$category, $items]): ?>
            <li class="nav-category"><?= e($category) ?></li>
            <?php foreach ($items as [$path, $icon, $label]): ?>
                <?php
                $isActive = $currentPath === $path
                    || ($path !== '/ruang-kerja' && str_starts_with($currentPath, $path . '/'))
                    || ($path === '/ruang-kerja' && $currentPath === '/ruang-kerja');
                ?>
                <li class="sidebar-nav-item">
                    <a href="<?= e($path) ?>" class="sidebar-nav-link <?= $isActive ? 'active' : '' ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon($icon, 18) ?></span>
                        <span><?= e($label) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <li class="nav-category">Koordinasi BPC</li>
        <li class="sidebar-nav-item">
            <a href="<?= e($coordinationPath) ?>" class="sidebar-nav-link <?= str_contains($currentPath, '/koordinasi') ? 'active' : '' ?>">
                <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                <span>Chat Koordinasi</span>
            </a>
        </li>
        <li class="sidebar-nav-item" style="margin-top:1.5rem;border-top:1px solid rgba(255,255,255,.08);padding-top:1rem;">
            <a href="/ubah-password" class="sidebar-nav-link">
                <span class="sidebar-nav-icon"><?= svg_icon('lock', 18) ?></span>
                <span>Ubah Kata Sandi</span>
            </a>
        </li>
    </ul>
</aside>
