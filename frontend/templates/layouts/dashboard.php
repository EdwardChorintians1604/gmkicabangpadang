<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) : 'Panel Sistem - GMKI Cabang Padang' ?></title>
    <link rel="icon" type="image/png" href="<?= asset('images/favicon.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
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
<body>

    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-brand flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="<?= asset('images/logo-gmki.png') ?>" alt="Logo GMKI" style="width: 36px; height: 36px; object-fit: contain;">
                    <div>
                        <div class="sidebar-brand-title">GMKI Padang</div>
                        <div class="sidebar-brand-sub">Sistem Manajemen Cabang</div>
                    </div>
                </div>
                <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup Menu">
                    ✕
                </button>
            </div>

            <ul class="sidebar-nav">
                <li class="nav-category">Navigasi Utama</li>
                
                <?php if (can('dashboard.view')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard" class="sidebar-nav-link <?= ($_SERVER['REQUEST_URI'] === '/dashboard') ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">📊</span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('dashboard.pengawas')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/pengawas" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/dashboard/pengawas')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">👁️</span>
                            <span>Panel Pengawas</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-category">Database & Kaderisasi</li>

                <?php if (can('civitas.view')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/civitas" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/civitas') && !str_contains($_SERVER['REQUEST_URI'], '/create') && !str_contains($_SERVER['REQUEST_URI'], '/impor')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">👥</span>
                            <span>Data Civitas</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('civitas.create')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/civitas/create" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/civitas/create')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">➕</span>
                            <span>Tambah Anggota</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('civitas.import')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/civitas/impor" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/civitas/impor')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">📥</span>
                            <span>Impor Data (CSV)</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('statistics.view')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/statistik" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/statistik')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">📈</span>
                            <span>Statistik & Grafik</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-category">Publikasi & Cabang</li>

                <?php if (can('news.view')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/berita" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/berita')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">📰</span>
                            <span>Warta & Berita</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('organization.view')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/organisasi/profil" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/organisasi/profil')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">🏛️</span>
                            <span>Profil Cabang</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/admin/organisasi/struktur" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/organisasi/struktur')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">🪪</span>
                            <span>Struktur BPC</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-category">Sistem & Keamanan</li>

                <?php if (can('users.view')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/users" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/users')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">👤</span>
                            <span>Kelola Pengguna</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('security.audit_log')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/keamanan/audit-log" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/keamanan/audit-log')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">📋</span>
                            <span>Jejak Audit</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/admin/keamanan/akses-log" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/keamanan/akses-log')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">🌐</span>
                            <span>Audit Akses Web</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('security.threats')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/keamanan/ancaman" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/keamanan/ancaman')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">🛡️</span>
                            <span>Deteksi Ancaman</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('backup.view')): ?>
                    <li class="sidebar-nav-item">
                        <a href="/admin/keamanan/backup" class="sidebar-nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/keamanan/backup')) ? 'active' : '' ?>">
                            <span class="sidebar-nav-icon">💾</span>
                            <span>Cadangan (Backup)</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="sidebar-nav-item" style="margin-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1rem;">
                    <a href="/" target="_blank" class="sidebar-nav-link">
                        <span class="sidebar-nav-icon">🌐</span>
                        <span>Lihat Website</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Dashboard Column -->
        <div class="dashboard-main">
            <!-- Topbar -->
            <header class="dashboard-topbar">
                <div class="topbar-left">
                    <button type="button" class="navbar-sidebar-toggle sidebar-toggle-btn" id="navbarSidebarToggle" aria-label="Buka / Tutup Sidebar" title="Buka / Tutup Sidebar (Ctrl+B)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="20" y1="12" y2="12"></line>
                            <line x1="4" x2="20" y1="6" y2="6"></line>
                            <line x1="4" x2="20" y1="18" y2="18"></line>
                        </svg>
                    </button>
                    <div class="topbar-title hidden sm:block">GMKI Cabang Padang</div>
                </div>

                <div class="topbar-right">
                    <div class="user-profile-badge">
                        <img src="<?= asset('images/default-profile.png') ?>" alt="Foto Pengguna" class="user-avatar-sm">
                        <div class="hidden md:block">
                            <div style="font-weight: 700; line-height: 1.2; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?= e(auth()['nama_lengkap'] ?? auth()['username']) ?>
                            </div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: capitalize;">
                                <?= e(auth()['role'] ?? 'Pengguna') ?>
                            </div>
                        </div>
                    </div>

                    <a href="/ubah-password" class="btn btn-outline btn-sm px-2.5 sm:px-3" title="Ubah Kata Sandi">
                        🔑 <span class="hidden sm:inline">Sandi</span>
                    </a>

                    <form action="/logout" method="POST" style="display: inline; margin: 0;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm px-2.5 sm:px-3" data-confirm="Apakah Anda yakin ingin keluar dari sistem?">
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Dashboard Content Container -->
            <main class="dashboard-content">
                <?php require dirname(__DIR__) . '/layouts/flash-message.php'; ?>
                <?= $content ?>
            </main>
        </div>
    </div>

    <!-- Mobile Backdrop Overlay -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Scripts -->
    <script src="<?= asset('vendor/chart.umd.min.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/validation.js') ?>"></script>
    <script src="<?= asset('js/statistik.js') ?>"></script>
</body>
</html>
