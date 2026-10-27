<!DOCTYPE html>
<html lang="id" class="admin-html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) : 'Panel Administrator - GMKI Cabang Padang' ?></title>
    <link rel="icon" type="image/png" href="<?= asset('images/GMKI-Logos.png') ?>">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="<?= asset('vendor/animate.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/leaflet/leaflet.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>?v=5">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>?v=13">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php if (($section ?? '') === 'coordination'): ?>
        <link rel="stylesheet" href="<?= asset('css/coordination.css') ?>?v=7">
    <?php endif; ?>
    <!-- Tailwind CSS with custom GMKI palette -->
    <script defer src="https://cdn.tailwindcss.com"></script>
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
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
        <?php if (in_array(auth()['role'] ?? '', ['ketcab', 'sekcab', 'bencab', 'sekfung_medko'], true)): ?>
            <?= partial('layouts.partials.officer-sidebar') ?>
        <?php else: ?>
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
                    <a href="/admin/dashboard" class="sidebar-nav-link <?= active_nav('/admin/dashboard') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-speedometer2"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/koordinasi" class="sidebar-nav-link <?= active_nav('/admin/koordinasi') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-chat-dots-fill"></i></span>
                        <span>Chat Koordinasi BPC</span>
                    </a>
                </li>

                <li class="nav-category">Civitas &amp; Kaderisasi</li>
                <li class="sidebar-nav-item">
                    <a href="/admin/civitas"
                        class="sidebar-nav-link <?= (active_nav('/admin/civitas') && !str_contains($_SERVER['REQUEST_URI'], '/create') && !str_contains($_SERVER['REQUEST_URI'], '/impor')) ? 'active' : '' ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-people-fill"></i></span>
                        <span>Kelola Civitas</span>
                    </a>
                </li>
                <?php if (can('civitas.create')): ?>
                <li class="sidebar-nav-item">
                    <a href="/admin/civitas/create" class="sidebar-nav-link <?= active_nav('/admin/civitas/create') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-person-plus-fill"></i></span>
                        <span>Tambah Anggota</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="sidebar-nav-item">
                    <a href="/admin/maperca"
                        class="sidebar-nav-link <?= (active_nav('/admin/maperca') && !str_contains($_SERVER['REQUEST_URI'], '/create')) ? 'active' : '' ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-mortarboard-fill"></i></span>
                        <span>Kader Baru (Maperca)</span>
                    </a>
                </li>
                <?php if (can('civitas.create')): ?>
                <li class="sidebar-nav-item">
                    <a href="/admin/maperca/create" class="sidebar-nav-link <?= active_nav('/admin/maperca/create') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-plus-circle-fill"></i></span>
                        <span>Tambah Kader Baru</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="sidebar-nav-item">
                    <a href="/admin/komisariat" class="sidebar-nav-link <?= active_nav('/admin/komisariat') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-buildings-fill"></i></span>
                        <span>Komisariat</span>
                    </a>
                </li>
                <?php if (can('civitas.import')): ?>
                <li class="sidebar-nav-item">
                    <a href="/admin/civitas/impor" class="sidebar-nav-link <?= active_nav('/admin/civitas/impor') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-file-earmark-excel-fill"></i></span>
                        <span>Impor Civitas (CSV/Excel)</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-category">Publikasi &amp; Cabang</li>
                <li class="sidebar-nav-item">
                    <a href="/admin/berita" class="sidebar-nav-link <?= active_nav('/admin/berita') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-newspaper"></i></span>
                        <span>Warta &amp; Berita</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/organisasi/profil"
                        class="sidebar-nav-link <?= active_nav('/admin/organisasi/profil') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-building"></i></span>
                        <span>Profil Cabang</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/organisasi/struktur"
                        class="sidebar-nav-link <?= active_nav('/admin/organisasi/struktur') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-diagram-3-fill"></i></span>
                        <span>Struktur BPC</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/statistik" class="sidebar-nav-link <?= active_nav('/admin/statistik') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-bar-chart-line-fill"></i></span>
                        <span>Statistik &amp; Grafik</span>
                    </a>
                </li>

                <li class="nav-category">Sistem &amp; Keamanan</li>
                <li class="sidebar-nav-item">
                    <a href="/admin/akun" class="sidebar-nav-link <?= active_nav('/admin/akun') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-person-gear"></i></span>
                        <span>Akun Pengguna</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/keamanan/audit-log"
                        class="sidebar-nav-link <?= active_nav('/admin/keamanan/audit-log') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-shield-lock-fill"></i></span>
                        <span>Jejak Audit</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/keamanan/akses-log"
                        class="sidebar-nav-link <?= active_nav('/admin/keamanan/akses-log') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-activity"></i></span>
                        <span>Audit Akses Web</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/keamanan/ancaman"
                        class="sidebar-nav-link <?= active_nav('/admin/keamanan/ancaman') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-shield-exclamation"></i></span>
                        <span>Deteksi Ancaman</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/admin/keamanan/backup"
                        class="sidebar-nav-link <?= active_nav('/admin/keamanan/backup') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-database-fill-gear"></i></span>
                        <span>Cadangan Data</span>
                    </a>
                </li>

                <li class="nav-category">Tools &amp; Berkas</li>
                <li class="sidebar-nav-item">
                    <a href="/sekcab/berkas"
                        class="sidebar-nav-link <?= active_nav('/sekcab/berkas') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-folder2-open"></i></span>
                        <span>Direktori Berkas Persuratan</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="/bencab/berkas"
                        class="sidebar-nav-link <?= active_nav('/bencab/berkas') ?> flex items-center gap-2">
                        <span class="sidebar-nav-icon flex items-center justify-center text-base"><i class="bi bi-file-earmark-spreadsheet-fill"></i></span>
                        <span>Direktori Berkas Keuangan</span>
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
        <?php endif; ?>

        <!-- Main Content Area -->
        <div class="dashboard-main">
            <!-- Top Navbar -->
            <header class="dashboard-header flex items-center justify-between gap-2 px-3 sm:px-6">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                    <button type="button" class="navbar-sidebar-toggle sidebar-toggle-btn" id="navbarSidebarToggle" aria-label="Buka / Tutup Sidebar" title="Buka / Tutup Sidebar (Ctrl+B)">
                        <?= svg_icon('menu', 20) ?>
                    </button>
                    <div class="header-breadcrumb flex items-center gap-1.5 text-xs sm:text-sm text-slate-500 overflow-hidden text-ellipsis whitespace-nowrap min-w-0 flex-1">
                        <span class="header-breadcrumb-root font-medium text-slate-400 hidden sm:inline">GMKI Padang</span>
                        <span class="header-breadcrumb-sep text-slate-300 hidden sm:inline">/</span>
                        <strong class="text-slate-800 font-bold overflow-hidden text-ellipsis whitespace-nowrap block min-w-0 flex-1 text-xs sm:text-sm"><?= e(explode(' - ', $pageTitle ?? 'Panel Admin')[0]) ?></strong>
                    </div>
                </div>

                <div class="header-actions flex items-center gap-1.5 sm:gap-2.5 flex-shrink-0">
                    <a href="/" target="_blank" class="btn btn-outline btn-sm flex items-center gap-1 px-2 sm:px-3"
                        title="Lihat Portal Pengunjung">
                        <?= svg_icon('external', 14) ?>
                        <span class="btn-label hidden sm:inline">Lihat Web</span>
                    </a>

                    <div class="user-dropdown">
                        <a href="/ubah-password" class="btn btn-outline btn-sm px-2 sm:px-2.5" title="Ubah Password">
                            <?= svg_icon('lock', 14) ?>
                        </a>
                    </div>

                    <form action="/logout" method="POST" style="margin: 0;"
                        onsubmit="sessionStorage.clear(); return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm flex items-center gap-1 px-2 sm:px-3" title="Keluar dari sistem">
                            <?= svg_icon('logout', 14) ?>
                            <span class="btn-label hidden sm:inline">Keluar</span>
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

                <!-- Admin Footer -->
                <footer class="dashboard-footer">
                    <div>&copy; 2026 BPC GMKI Cabang Padang. Sistem Informasi & Manajemen Keanggotaan Terintegrasi.</div>
                    <div class="text-muted">Versi 2.0 (Clean Architecture PSR-4)</div>
                </footer>
            </main>
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
    <?php if (($section ?? '') === 'coordination'): ?>
        <script src="<?= asset('js/coordination.js') ?>?v=6"></script>
    <?php endif; ?>
    <script>
        window.__AUTH_USER_ID__ = <?= !empty(auth()['id']) ? (int)auth()['id'] : 'null' ?>;
    </script>
    <script src="<?= asset('js/session-guard.js') ?>?v=1"></script>
</body>

</html>