<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) : 'Panel Pemantauan Pengawas - GMKI Cabang Padang' ?></title>
    <link rel="icon" type="image/png" href="<?= asset('images/GMKI-Logos.png') ?>">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="<?= asset('vendor/animate.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/leaflet/leaflet.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/pengawas.css') ?>">
    <!-- Tailwind CSS with custom GMKI palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    colors: {
                        'gmki-blue': '#0f3d64',
                        'gmki-light': '#1e5687',
                        'gmki-dark': '#0a263f',
                        'gmki-gold': '#d97706',
                        'gmki-amber': '#f59e0b',
                    }
                }
            }
        }
    </script>
</head>
<body class="pengawas-body">

    <div class="dashboard-wrapper">
        <!-- Sidebar Pengawas (Oversight & Monitoring) -->
        <aside class="dashboard-sidebar pengawas-sidebar">
            <div class="sidebar-brand flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="<?= asset('images/GMKI-Logos.png') ?>" alt="Logo GMKI" class="brand-logo" style="width: 36px; height: 36px; object-fit: contain;">
                    <div>
                        <div class="sidebar-brand-title">GMKI Padang</div>
                        <div class="sidebar-brand-sub" style="color: var(--secondary); font-weight: 700;">PANEL PENGAWAS BPC</div>
                    </div>
                </div>
                <button type="button" class="sidebar-close-btn" id="pengawasSidebarCloseBtn" aria-label="Tutup Menu">
                    ✕
                </button>
            </div>

            <!-- Read Only Notice Badge -->
            <div class="readonly-badge-container">
                <div class="badge badge-warning flex items-center justify-center gap-1" style="width: 100%; padding: 6px; font-size: 0.72rem; letter-spacing: 0.04em;">
                    <?= svg_icon('eye', 13) ?>
                    <span>PANTAUAN + PENCATATAN ANGGOTA</span>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li class="nav-category">Pusat Komando BPC</li>
                <li class="sidebar-nav-item">
                    <a href="/pengawas/dashboard" class="sidebar-nav-link <?= active_nav('/pengawas/dashboard') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('dashboard', 18) ?></span>
                        <span>Dashboard Eksekutif</span>
                    </a>
                </li>

                <li class="nav-category">Pencatatan Anggota</li>
                <li class="sidebar-nav-item">
                    <a href="/admin/civitas" class="sidebar-nav-link <?= active_nav('/admin/civitas') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                        <span>Kelola Data Civitas</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/civitas/create" class="sidebar-nav-link">
                        <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                        <span>Tambah Anggota Civitas</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/maperca" class="sidebar-nav-link <?= active_nav('/admin/maperca') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                        <span>Kader Baru (Maperca)</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/maperca/create" class="sidebar-nav-link">
                        <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                        <span>Tambah Kader Baru</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/komisariat" class="sidebar-nav-link <?= active_nav('/admin/komisariat') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('organization', 18) ?></span>
                        <span>Komisariat</span>
                    </a>
                </li>

                <li class="nav-category">Pengawasan Data</li>
                <li class="sidebar-nav-item">
                    <a href="/pengawas/civitas" class="sidebar-nav-link <?= active_nav('/pengawas/civitas') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                        <span>Pantauan Civitas</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/pengawas/berita" class="sidebar-nav-link <?= active_nav('/pengawas/berita') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('news', 18) ?></span>
                        <span>Pantauan Warta Cabang</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/pengawas/statistik" class="sidebar-nav-link <?= active_nav('/pengawas/statistik') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('chart', 18) ?></span>
                        <span>Analitik Statistik</span>
                    </a>
                </li>

                <li class="nav-category">Aset & Tata Kelola</li>
                <li class="sidebar-nav-item">
                    <a href="/pengawas/organisasi/profil" class="sidebar-nav-link <?= active_nav('/pengawas/organisasi/profil') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('organization', 18) ?></span>
                        <span>Profil & Tri Panji</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/pengawas/organisasi/struktur" class="sidebar-nav-link <?= active_nav('/pengawas/organisasi/struktur') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                        <span>Struktur Pengurus BPC</span>
                    </a>
                </li>

                <li class="nav-category">Transparansi & Keamanan</li>
                <li class="sidebar-nav-item">
                    <a href="/pengawas/pemantauan/audit-log" class="sidebar-nav-link <?= active_nav('/pengawas/pemantauan/audit-log') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('shield', 18) ?></span>
                        <span>Audit Log Aktivitas</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/pengawas/pemantauan/keamanan" class="sidebar-nav-link <?= active_nav('/pengawas/pemantauan/keamanan') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('alert', 18) ?></span>
                        <span>Keamanan & Ancaman</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/pengawas/pemantauan/backup" class="sidebar-nav-link <?= active_nav('/pengawas/pemantauan/backup') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('backup', 18) ?></span>
                        <span>Integritas Cadangan Data</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="sidebar-user flex items-center gap-3">
                    <div class="user-avatar-circle" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%);">
                        <?= strtoupper(substr(auth()['nama_lengkap'] ?? 'Pengawas', 0, 1)) ?>
                    </div>
                    <div class="user-meta">
                        <div class="user-name"><?= e(auth()['nama_lengkap'] ?? 'Pengawas') ?></div>
                        <span class="badge badge-warning"><?= strtoupper(e(auth()['role'] ?? 'PENGAWAS')) ?></span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="dashboard-main">
            <!-- Top Navbar -->
            <header class="dashboard-header flex items-center justify-between gap-2 px-3 sm:px-6">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <button type="button" class="navbar-sidebar-toggle sidebar-toggle-btn" id="navbarSidebarToggle" aria-label="Buka / Tutup Sidebar" title="Buka / Tutup Sidebar (Ctrl+B)">
                        <?= svg_icon('menu', 20) ?>
                    </button>
                    <div class="header-breadcrumb flex items-center gap-1.5 text-xs sm:text-sm text-slate-500 overflow-hidden text-ellipsis whitespace-nowrap">
                        <span class="font-medium text-slate-400 hidden sm:inline">GMKI Padang</span>
                        <span class="text-slate-300 hidden sm:inline">/</span>
                        <span class="badge badge-warning text-[10px] sm:text-xs hidden md:inline">Pengawas BPC</span>
                        <span class="text-slate-300 hidden md:inline">/</span>
                        <strong class="text-slate-800 font-bold overflow-hidden text-ellipsis whitespace-nowrap"><?= e(explode(' - ', $pageTitle ?? 'Panel Pengawas')[0]) ?></strong>
                    </div>
                </div>

                <div class="header-actions flex items-center gap-1.5 sm:gap-3 flex-shrink-0">
                    <a href="/" target="_blank" class="btn btn-outline btn-sm flex items-center gap-1 px-2 sm:px-3" title="Lihat Portal Pengunjung">
                        <?= svg_icon('external', 14) ?>
                        <span class="hidden sm:inline">Portal Web</span>
                    </a>

                    <?php if (is_admin()): ?>
                        <a href="/admin/dashboard" class="btn btn-primary btn-sm flex items-center gap-1 px-2 sm:px-3" title="Kembali ke Panel Pengelolaan Penuh">
                            <?= svg_icon('settings', 14) ?>
                            <span class="hidden md:inline">Panel Admin</span>
                        </a>
                    <?php endif; ?>

                    <div class="user-dropdown">
                        <a href="/ubah-password" class="btn btn-outline btn-sm px-2 sm:px-2.5" title="Ubah Password">
                            <?= svg_icon('lock', 14) ?>
                        </a>
                    </div>

                    <form action="/logout" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm flex items-center gap-1 px-2 sm:px-3">
                            <?= svg_icon('logout', 14) ?>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="dashboard-content">
                <!-- Flash Message Notification -->
                <?php require __DIR__ . '/flash-message.php'; ?>

                <!-- View Content -->
                <?= $content ?? '' ?>
            </main>

            <!-- Pengawas Footer -->
            <footer class="dashboard-footer">
                <div>&copy; 2026 BPC GMKI Cabang Padang. Modul Pengawasan Eksekutif (KETCAB - SEKCAB - BENCAB - MPPC).</div>
                <div class="text-muted">Amanat Pelayanan: Tinggi Iman, Tinggi Ilmu, Tinggi Pengabdian</div>
            </footer>
        </div>
    </div>

    <!-- Mobile Backdrop Overlay -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Scripts -->
    <script src="<?= asset('vendor/chart.umd.min.js') ?>"></script>
    <script src="<?= asset('vendor/leaflet/leaflet.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/validation.js') ?>"></script>
    <script src="<?= asset('js/admin.js') ?>"></script>
    <script src="<?= asset('js/statistik.js') ?>"></script>
</body>
</html>
