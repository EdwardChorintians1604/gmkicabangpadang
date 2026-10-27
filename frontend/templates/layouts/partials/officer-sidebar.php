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
            ['/ketcab/dashboard', 'bi-speedometer2', 'Dashboard Ketcab'],
            ['/ketcab/proker', 'bi-kanban', 'Program Kerja Sekfung'],
            ['/bencab/laporan', 'bi-cash-stack', 'Laporan Keuangan'],
            ['/ruang-kerja/antrian', 'bi-hourglass-split', 'Antrean Ekspor'],
            ['/admin/civitas', 'bi-people-fill', 'Data Civitas (Lihat & Unduh)'],
            ['/admin/maperca', 'bi-person-badge-fill', 'Data Maperca (Lihat & Unduh)'],
            ['/admin/komisariat', 'bi-buildings-fill', 'Data Komisariat (Lihat & Unduh)'],
            ['/admin/statistik', 'bi-bar-chart-line-fill', 'Statistik Cabang'],
        ]],
    ],
    'sekcab' => [
        ['Administrasi Cabang', [
            ['/ruang-kerja', 'bi-speedometer2', 'Dashboard Sekcab'],
            ['/admin/civitas', 'bi-people-fill', 'Data Civitas (Lihat & Unduh)'],
            ['/admin/maperca', 'bi-person-badge-fill', 'Data Maperca (Lihat & Unduh)'],
            ['/admin/komisariat', 'bi-buildings-fill', 'Data Komisariat (Lihat & Unduh)'],
            ['/sekcab/inventaris', 'bi-boxes', 'Inventaris'],
            ['/sekcab/arsip', 'bi-archive-fill', 'Arsip Surat'],
            ['/admin/statistik', 'bi-bar-chart-line-fill', 'Statistik Anggota'],
        ]],
        ['Tools & Berkas', [
            ['/sekcab/berkas', 'bi-folder2-open', 'Direktori Berkas Persuratan'],
        ]],
    ],
    'bencab' => [
        ['Keuangan Cabang', [
            ['/ruang-kerja', 'bi-speedometer2', 'Dashboard Bencab'],
            ['/bencab/laporan', 'bi-cash-stack', 'Laporan Keuangan'],
            ['/ruang-kerja/antrian', 'bi-hourglass-split', 'Antrean Ekspor'],
        ]],
        ['Tools & Berkas', [
            ['/bencab/berkas', 'bi-folder2-open', 'Direktori Berkas Keuangan'],
        ]],
    ],
    'sekfung_medko' => [
        ['Media & Komunikasi', [
            ['/ruang-kerja', 'bi-speedometer2', 'Dashboard Medko'],
            ['/admin/berita', 'bi-newspaper', 'Kelola Warta'],
            ['/admin/berita/create', 'bi-pencil-square', 'Tulis Warta'],
            ['/admin/statistik', 'bi-bar-chart-line-fill', 'Statistik Konten'],
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
        <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup menu">✕</button>
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
                    <a href="<?= e($path) ?>" class="sidebar-nav-link <?= $isActive ? 'active' : '' ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi <?= e($icon) ?>"></i></span>
                        <span><?= e($label) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <li class="nav-category">Koordinasi BPC</li>
        <li class="sidebar-nav-item">
            <a href="<?= e($coordinationPath) ?>" class="sidebar-nav-link <?= str_contains($currentPath, '/koordinasi') ? 'active' : '' ?> flex items-center gap-2">
                <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-chat-dots-fill"></i></span>
                <span>Chat Koordinasi</span>
            </a>
        </li>
        <li class="sidebar-nav-item" style="margin-top:1.5rem;border-top:1px solid rgba(255,255,255,.08);padding-top:1rem;">
            <a href="/ubah-password" class="sidebar-nav-link flex items-center gap-2">
                <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-shield-lock-fill"></i></span>
                <span>Ubah Kata Sandi</span>
            </a>
        </li>
    </ul>
</aside>
