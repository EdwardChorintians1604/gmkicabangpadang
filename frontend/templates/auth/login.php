<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) : 'Masuk Sistem - GMKI Cabang Padang' ?></title>
    <link rel="icon" type="image/png" href="<?= asset('images/GMKI-Logos.png') ?>">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="<?= asset('vendor/animate.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <!-- Tailwind CSS with GMKI tokens -->
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
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #071e33 0%, #0f3d64 60%, #1e5687 100%);
            padding: 2rem 1.25rem;
            position: relative;
            overflow: hidden;
        }
        .login-wrapper::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(217, 119, 6, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            top: -10%;
            right: -10%;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: var(--radius-lg);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            width: 100%;
            max-width: 440px;
            padding: 2.5rem 2rem;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo {
            width: 72px;
            height: 72px;
            margin: 0 auto 1rem;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card animate__animated animate__fadeInDown">
            <div class="login-brand">
                <a href="/">
                    <img src="<?= asset('images/GMKI-Logos.png') ?>" alt="Logo GMKI" class="login-logo">
                </a>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 0.25rem;">GMKI Cabang Padang</h2>
                <div style="font-size: 0.8125rem; font-weight: 600; color: var(--secondary); letter-spacing: 0.05em;">UT OMNES UNUM SINT</div>
                <p class="text-muted" style="font-size: 0.8125rem; margin-top: 0.25rem;">Sistem Informasi & Manajemen Keanggotaan Terintegrasi</p>
            </div>

            <?php require dirname(__DIR__) . '/layouts/flash-message.php'; ?>

            <form action="/login" method="POST" data-validate>
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="username">Username atau Email</label>
                    <input type="text" id="username" name="username" class="form-control" 
                           placeholder="Contoh: admin atau email@anda.com" 
                           value="<?= e(old('username')) ?>" required autofocus>
                </div>

                <div class="form-group">
                    <div class="flex justify-between items-center" style="margin-bottom: 0.5rem;">
                        <label class="form-label" for="password" style="margin-bottom: 0;">Kata Sandi</label>
                    </div>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Masukkan kata sandi Anda" required>
                </div>

                <div class="form-group" style="margin-top: 1.75rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.95rem; font-weight: 700;">
                        Masuk ke Sistem &rarr;
                    </button>
                </div>

                <div class="text-center" style="margin-top: 1.5rem; font-size: 0.8125rem;">
                    <a href="/" style="color: var(--text-muted); text-decoration: none; font-weight: 600;" class="hover:text-primary">&larr; Kembali ke Portal Publik</a>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/validation.js') ?>"></script>
</body>
</html>
