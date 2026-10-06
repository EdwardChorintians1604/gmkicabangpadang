<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) : 'Panel Administrator - GMKI Cabang Padang' ?></title>
    <link rel="icon" type="image/png" href="<?= asset('images/GMKI-Logos.png') ?>">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="<?= asset('vendor/animate.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/leaflet/leaflet.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
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

<body class="admin-body">

    <div class="dashboard-wrapper">
        <!-- Sidebar Administrator -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-brand flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="<?= asset('images/GMKI-Logos.png') ?>" alt="Logo GMKI" class="brand-logo"
                        style="width: 36px; height: 36px; object-fit: contain;">
                    <div>
                        <div class="sidebar-brand-title">GMKI Padang</div>
                        <div class="sidebar-brand-sub">Panel Administrator</div>
                    </div>
                </div>
                <button type="button" class="sidebar-close-btn" id="adminSidebarCloseBtn" aria-label="Tutup Menu">
                    ✕
                </button>
            </div>

            <ul class="sidebar-nav">
                <li class="nav-category">Navigasi Utama</li>
                <li class="sidebar-nav-item">
                    <a href="/admin/dashboard" class="sidebar-nav-link <?= active_nav('/admin/dashboard') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('dashboard', 18) ?></span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-category">Civitas & Kaderisasi</li>
                <li class="sidebar-nav-item">
                    <a href="/admin/civitas"
                        class="sidebar-nav-link <?= (active_nav('/admin/civitas') && !str_contains($_SERVER['REQUEST_URI'], '/create') && !str_contains($_SERVER['REQUEST_URI'], '/impor')) ? 'active' : '' ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                        <span>Kelola Civitas</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/civitas/create" class="sidebar-nav-link <?= active_nav('/admin/civitas/create') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('plus', 18) ?></span>
                        <span>Tambah Anggota</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/maperca"
                        class="sidebar-nav-link <?= (active_nav('/admin/maperca') && !str_contains($_SERVER['REQUEST_URI'], '/create')) ? 'active' : '' ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                        <span>Kader Baru (Maperca)</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/maperca/create" class="sidebar-nav-link <?= active_nav('/admin/maperca/create') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('plus', 18) ?></span>
                        <span>Tambah Kader Baru</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/komisariat" class="sidebar-nav-link <?= active_nav('/admin/komisariat') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('organization', 18) ?></span>
                        <span>Komisariat</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/civitas/impor" class="sidebar-nav-link <?= active_nav('/admin/civitas/impor') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('upload', 18) ?></span>
                        <span>Impor CSV Civitas</span>
                    </a>
                </li>

                <li class="nav-category">Publikasi & Cabang</li>
                <li class="sidebar-nav-item">
                    <a href="/admin/berita" class="sidebar-nav-link <?= active_nav('/admin/berita') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('news', 18) ?></span>
                        <span>Warta & Berita</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/organisasi/profil"
                        class="sidebar-nav-link <?= active_nav('/admin/organisasi/profil') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('organization', 18) ?></span>
                        <span>Profil Cabang</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/organisasi/struktur"
                        class="sidebar-nav-link <?= active_nav('/admin/organisasi/struktur') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('users', 18) ?></span>
                        <span>Struktur BPC</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/statistik" class="sidebar-nav-link <?= active_nav('/admin/statistik') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('chart', 18) ?></span>
                        <span>Statistik & Grafik</span>
                    </a>
                </li>

                <li class="nav-category">Sistem & Keamanan</li>
                <li class="sidebar-nav-item">
                    <a href="/admin/akun" class="sidebar-nav-link <?= active_nav('/admin/akun') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('user', 18) ?></span>
                        <span>Akun Pengguna</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/keamanan/audit-log"
                        class="sidebar-nav-link <?= active_nav('/admin/keamanan/audit-log') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('shield', 18) ?></span>
                        <span>Jejak Audit</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/keamanan/akses-log"
                        class="sidebar-nav-link <?= active_nav('/admin/keamanan/akses-log') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('dashboard', 18) ?></span>
                        <span>Audit Akses Web</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/keamanan/ancaman"
                        class="sidebar-nav-link <?= active_nav('/admin/keamanan/ancaman') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('alert', 18) ?></span>
                        <span>Deteksi Ancaman</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/keamanan/backup"
                        class="sidebar-nav-link <?= active_nav('/admin/keamanan/backup') ?>">
                        <span class="sidebar-nav-icon"><?= svg_icon('backup', 18) ?></span>
                        <span>Cadangan Data</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="sidebar-user flex items-center gap-3">
                    <div class="user-avatar-circle">
                        <?= strtoupper(substr(auth()['nama_lengkap'] ?? 'Admin', 0, 1)) ?>
                    </div>
                    <div class="user-meta">
                        <div class="user-name"><?= e(auth()['nama_lengkap'] ?? 'Administrator') ?></div>
                        <span class="badge badge-primary"><?= strtoupper(e(auth()['role'] ?? 'ADMIN')) ?></span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="dashboard-main">
            <!-- Top Navbar -->
            <header class="dashboard-header flex items-center justify-between gap-2 px-3 sm:px-6">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <button type="button" class="sidebar-mobile-toggle p-1.5 rounded-lg border border-slate-200 text-slate-700 hover:text-primary hover:bg-slate-100 transition-colors cursor-pointer" aria-label="Toggle Menu">
                        <?= svg_icon('dashboard', 22) ?>
                    </button>
                    <div class="header-breadcrumb flex items-center gap-1.5 text-xs sm:text-sm text-slate-500 overflow-hidden text-ellipsis whitespace-nowrap">
                        <span class="font-medium text-slate-400 hidden sm:inline">GMKI Padang</span>
                        <span class="text-slate-300 hidden sm:inline">/</span>
                        <strong class="text-slate-800 font-bold overflow-hidden text-ellipsis whitespace-nowrap"><?= e(explode(' - ', $pageTitle ?? 'Panel Admin')[0]) ?></strong>
                    </div>
                </div>

                <div class="header-actions flex items-center gap-1.5 sm:gap-3 flex-shrink-0">
                    <a href="/" target="_blank" class="btn btn-outline btn-sm flex items-center gap-1 px-2 sm:px-3"
                        title="Lihat Portal Pengunjung">
                        <?= svg_icon('external', 14) ?>
                        <span class="hidden sm:inline">Lihat Web</span>
                    </a>

                    <?php if (is_admin()): ?>
                        <a href="/pengawas/dashboard" class="btn btn-secondary btn-sm flex items-center gap-1 px-2 sm:px-3"
                            title="Buka Mode Pantauan Pengawas">
                            <?= svg_icon('eye', 14) ?>
                            <span class="hidden md:inline">Mode Pengawas</span>
                        </a>
                    <?php endif; ?>

                    <div class="user-dropdown">
                        <a href="/ubah-password" class="btn btn-outline btn-sm px-2 sm:px-2.5" title="Ubah Password">
                            <?= svg_icon('lock', 14) ?>
                        </a>
                    </div>

                    <form action="/logout" method="POST" style="margin: 0;"
                        onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
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

            <!-- Admin Footer -->
            <footer class="dashboard-footer">
                <div>&copy; 2026 BPC GMKI Cabang Padang. Sistem Informasi & Manajemen Keanggotaan Terintegrasi.</div>
                <div class="text-muted">Versi 2.0 (Clean Architecture PSR-4)</div>
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