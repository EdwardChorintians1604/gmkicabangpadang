<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server | GMKI Cabang Padang</title>
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
            color: #ef4444;
            line-height: 1;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-card">
            <div class="error-code">500</div>
            <h2 style="margin-bottom: 0.75rem; color:#ffffff;">Terjadi Kesalahan Server</h2>
            <p style="color: #cbd5e1; margin-bottom: 2rem; font-size: 0.95rem;">
                Sistem mendeteksi kendala pada server saat memproses permintaan Anda. Administrator telah mencatat peristiwa ini.
            </p>
            <div class="flex justify-center gap-3">
                <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
