<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | GMKI Cabang Padang</title>
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            background: linear-gradient(135deg, #0a263f 0%, #0f3d64 100%);
            color: #ffffff;
        }
        .error-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 3rem 2.5rem;
            border-radius: var(--radius-lg);
            max-width: 500px;
            width: 100%;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 800;
            color: #f59e0b;
            line-height: 1;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-card">
            <div class="error-code">403</div>
            <h2 style="margin-bottom: 0.75rem; color:#ffffff;">Akses Terlarang</h2>
            <p style="color: #cbd5e1; margin-bottom: 2rem; font-size: 0.95rem;">
                <?= isset($message) ? e($message) : 'Anda tidak memiliki hak otorisasi yang cukup untuk mengakses halaman atau fungsi ini.' ?>
            </p>
            <div class="flex justify-center gap-3">
                <a href="/" class="btn btn-outline" style="border-color: rgba(255,255,255,0.3); color: #ffffff;">Ke Beranda</a>
                <a href="/dashboard" class="btn btn-primary">Ke Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
