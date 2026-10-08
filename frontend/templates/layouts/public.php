<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Sistem Informasi & Portal Resmi Gerakan Mahasiswa Kristen Indonesia (GMKI) Cabang Padang. Ut Omnes Unum Sint.">
    <title>Selamat datang di Platform Digital GMKI Cabang Padang - Organisasi Mahasiswa Kristen</title>
    <link rel="icon" type="image/png" href="<?= asset('images/GMKI-Logos.png') ?>">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="<?= asset('vendor/animate.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/leaflet/leaflet.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/public.css') ?>?v=5">
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

    <!-- Public Header & Navigation -->
    <header class="site-header">
        <div class="container">
            <nav class="navbar">
                <a href="/" class="brand-link">
                    <img src="<?= asset('images/GMKI-Logos.png') ?>" alt="Logo GMKI Padang" class="brand-logo">
                    <div>
                        <div>GMKI Cabang Padang</div>
                        <div
                            style="font-size: 0.65rem; font-weight: 600; color: var(--secondary); letter-spacing: 0.05em;">
                            UT OMNES UNUM SINT</div>
                    </div>
                </a>

                <button class="nav-toggle" aria-label="Buka Menu Navigasi" id="navToggleBtn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                <?php
                $currentUser = auth();
                $isLoggedIn = !empty($currentUser['id']);
                $dashboardRoute = match ($currentUser['role'] ?? '') {
                    'admin' => '/admin/dashboard',
                    'ketcab' => '/ketcab/dashboard',
                    default => '/ruang-kerja',
                };
                $dashboardLabel = 'Panel Kendali';
                $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
                ?>

                <ul class="nav-menu" id="publicNavMenu">
                    <li><a href="/" class="nav-link <?= ($requestUri === '/' || $requestUri === '') ? 'active' : '' ?>">Beranda</a></li>
                    <li><a href="/profil" class="nav-link <?= (str_starts_with($requestUri, '/profil')) ? 'active' : '' ?>">Profil</a></li>
                    <li><a href="/struktur-organisasi" class="nav-link <?= (str_starts_with($requestUri, '/struktur-organisasi')) ? 'active' : '' ?>">Struktur BPC</a></li>
                    <li><a href="/ad-art" class="nav-link <?= (str_starts_with($requestUri, '/ad-art')) ? 'active' : '' ?>">AD / ART</a></li>
                    <li><a href="/berita" class="nav-link <?= (str_starts_with($requestUri, '/berita')) ? 'active' : '' ?>">Warta & Berita</a></li>
                    <li><a href="/kontak" class="nav-link <?= (str_starts_with($requestUri, '/kontak')) ? 'active' : '' ?>">Kontak</a></li>

                    <?php if ($isLoggedIn): ?>
                        <li class="nav-auth-item flex items-center gap-2">
                            <a href="<?= e($dashboardRoute) ?>" class="btn btn-primary btn-sm flex items-center gap-1 whitespace-nowrap">
                                <?= svg_icon('dashboard', 14) ?>
                                <span><?= e($dashboardLabel) ?></span>
                            </a>
                            <form action="/logout" method="POST" style="margin: 0; display: inline;"
                                onsubmit="sessionStorage.clear(); return confirm('Apakah Anda yakin ingin keluar dari sesi akun ini?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline btn-sm flex items-center justify-center"
                                    title="Keluar / Logout Aman" style="padding: 6px 10px; line-height: 1;">
                                    <?= svg_icon('logout', 14) ?>
                                </button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li class="nav-auth-item">
                            <a href="/login" class="btn btn-outline btn-sm flex items-center gap-1 whitespace-nowrap">
                                <?= svg_icon('lock', 13) ?>
                                <span>Masuk Sistem</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Global Flash Message Container -->
    <div class="container" style="margin-top: 1.5rem;">
        <?php require dirname(__DIR__) . '/layouts/flash-message.php'; ?>
    </div>

    <!-- Main Content Slot -->
    <main>
        <?= $content ?>
    </main>

    <!-- Public Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="flex items-center gap-2" style="margin-bottom: 1rem;">
                        <img src="<?= asset('images/GMKI-Logos.png') ?>" alt="Logo GMKI"
                            style="width: 36px; height: 36px; object-fit: contain;">
                        <h4 style="color: #ffffff; font-size: 1.1rem; margin: 0;">GMKI Cabang Padang</h4>
                    </div>
                    <p style="font-size: 0.875rem; line-height: 1.6; margin-bottom: 1rem; color: #94a3b8;">
                        Wadah persekutuan, kesaksian, dan pelayanan mahasiswa Kristen di Padang. Mengabdi di tiga medan
                        layan: Gereja, Perguruan Tinggi, dan Masyarakat.
                    </p>
                    <div style="font-size: 0.8125rem; color: #fde68a; font-weight: 700;">
                        "Ut Omnes Unum Sint" — Supaya mereka semua menjadi satu.
                    </div>
                </div>

                <div>
                    <h5 class="footer-heading">Navigasi</h5>
                    <ul class="footer-links">
                        <li><a href="/">Beranda</a></li>
                        <li><a href="/profil">Profil & Sejarah</a></li>
                        <li><a href="/struktur-organisasi">Struktur BPC</a></li>
                        <li><a href="/ad-art">AD / ART Organisasi</a></li>
                        <li><a href="/berita">Warta & Kegiatan</a></li>
                        <li><a href="/kontak">Hubungi Sekretariat</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="footer-heading">Tri Panji GMKI</h5>
                    <ul class="footer-links">
                        <li><span style="color: #cbd5e1;">1. Tinggi Iman</span></li>
                        <li><span style="color: #cbd5e1;">2. Tinggi Ilmu</span></li>
                        <li><span style="color: #cbd5e1;">3. Tinggi Pengabdian</span></li>
                    </ul>
                </div>

                <div>
                    <h5 class="footer-heading">Sekretariat Cabang</h5>
                    <p style="font-size: 0.875rem; line-height: 1.5; margin-bottom: 0.75rem;">
                        Jl. Tanah Beroyo No.2c, Belakang Tangsi, Kec. Padang Bar., kodya padang, Sumatera Barat.
                    </p>
                    <p style="font-size: 0.875rem; color: #cbd5e1; margin-bottom: 0.35rem;">
                        <strong>Email:</strong> sekretariat@gmkicabangpadang.or.id
                    </p>
                    <p style="font-size: 0.875rem; color: #cbd5e1;">
                        <strong>Instagram:</strong> @gmkicabangpadang
                    </p>
                </div>
            </div>

            <div class="footer-bottom">
                <div>&copy; <?= date('Y') ?> Gerakan Mahasiswa Kristen Indonesia (GMKI) Cabang Padang. Hak Cipta
                    Dilindungi.</div>
                <div><a href="/login" style="color: #64748b; font-size: 0.75rem;">Akses Pengurus</a></div>
            </div>
        </div>
    </footer>

    <!-- Scripts: Vendor & Core -->
    <script src="<?= asset('vendor/chart.umd.min.js') ?>"></script>
    <script src="<?= asset('vendor/leaflet/leaflet.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/validation.js') ?>"></script>
    <script src="<?= asset('js/statistik.js') ?>"></script>
    <script>
        window.__AUTH_USER_ID__ = <?= !empty($currentUser['id']) ? (int)$currentUser['id'] : 'null' ?>;
    </script>
    <script src="<?= asset('js/session-guard.js') ?>?v=1"></script>
</body>

</html>
