<?php
$isMaperca = ($member['tingkat_kaderisasi'] ?? '') === 'Maperca'
    || !empty($member['calon_maperca'])
    || str_contains((string) ($member['catatan'] ?? ''), 'Registrasi Mandiri Maperca');

// Deteksi protokol & host dengan dukungan penuh reverse proxy / ngrok
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$protocol = $isHttps ? 'https://' : 'http://';
$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? '127.0.0.1';

// Token Keamanan Kriptografis Unik untuk Verifikasi Integritas Dokumen PDF
$securitySalt = 'GMKI_PADANG_SECURE_VERIF_SALT_2026';
$rawHash = hash('sha256', ($member['nim'] ?? '') . ($member['created_at'] ?? '2026') . $securitySalt);
$securityToken = 'GMKI-SEC-' . strtoupper(substr($rawHash, 0, 4)) . '-' . strtoupper(substr($rawHash, 4, 4)) . '-' . strtoupper(substr($rawHash, 8, 4));

// Tautan Verifikasi Resmi (langsung ke server utama)
$currentUrl = $protocol . $host . '/register_for_civitas/bukti/' . rawurlencode($member['nim']) . '?sec=' . urlencode($securityToken);
$qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=0&data=' . urlencode($currentUrl);

// URL Foto resmi publik civitas
$fotoUrl = '/register_for_civitas/foto/' . rawurlencode($member['nim']);

// Label status keanggotaan
$sk = $member['status_keanggotaan'] ?? 'Aktif';
$skLabel = match ($sk) {
    'Aktif' => 'Aktif (Mahasiswa aktif berkegiatan)',
    'Pasif' => 'Pasif (Tercatat anggota, tetapi jarang/tidak aktif)',
    'Alumni' => 'Alumni (Sudah wisuda / tamat kuliah)',
    'Senior' => 'Senior (Anggota senior sesuai ketentuan organisasi)',
    default => (string) $sk,
};
?>

<style>
    /* ==========================================================================
   TAMPILAN RESMI BUKTI REGISTRASI & VERIFIKASI KEAMANAN DOKUMEN GMKI PADANG
   ========================================================================== */

    .bukti-screen-wrapper {
        background: #f1f5f9;
        min-height: 100vh;
        padding-top: 1.25rem;
        padding-bottom: 3.5rem;
    }

    /* Lembar Dokumen Berstandar A4 */
    .official-sheet {
        background: #ffffff;
        max-width: 820px;
        margin: 0 auto;
        padding: 2.25rem 2.5rem 1.75rem 2.5rem;
        border-radius: 14px;
        border: 1px solid #cbd5e1;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
        position: relative;
        color: #0f172a;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Header Menengah */
    .sheet-header-center {
        text-align: center;
        margin-bottom: 1.25rem;
    }

    .sheet-logo {
        width: 78px;
        height: 78px;
        object-fit: contain;
        margin: 0 auto 0.6rem auto;
        display: block;
    }

    .sheet-title {
        font-size: 1.22rem;
        font-weight: 900;
        text-transform: uppercase;
        color: #0f3d64;
        letter-spacing: 0.04em;
        margin: 0;
        line-height: 1.3;
    }

    /* Layout 2 Kolom Profil (Foto Kiri, Tabel Data Kanan) */
    .data-profile-layout {
        display: flex;
        align-items: flex-start;
        gap: 1.35rem;
        margin-bottom: 1.25rem;
    }

    /* Sidebar Kiri: Pasfoto 3x4 Bersih & Rapi */
    .profile-sidebar {
        width: 145px;
        flex-shrink: 0;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .pasfoto-box {
        width: 140px;
        height: 180px;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        border: 2px solid #cbd5e1;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.6rem;
        position: relative;
    }

    .pasfoto-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .pasfoto-tag {
        position: absolute;
        bottom: 4px;
        left: 4px;
        right: 4px;
        background: rgba(15, 61, 100, 0.85);
        color: #ffffff;
        font-size: 8px;
        font-weight: 800;
        padding: 2px 0;
        border-radius: 3px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        backdrop-blur: 2px;
    }

    /* Kolom Kanan: Tabel Data (13 Butir Data Pokok) */
    .profile-table-wrapper {
        flex: 1;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #ffffff;
    }

    .profile-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.80rem;
        line-height: 1.35;
    }

    .profile-table tr {
        border-bottom: 1px solid #f1f5f9;
    }

    .profile-table tr:last-child {
        border-bottom: none;
    }

    .profile-table th {
        width: 33%;
        padding: 6.5px 12px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        background: #f8fafc;
        border-right: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .profile-table td {
        padding: 6.5px 12px;
        color: #1e293b;
        vertical-align: middle;
    }

    /* Badge Status Khusus */
    .badge-civitas-aktif {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 9px;
        font-size: 0.74rem;
        font-weight: 700;
        color: #065f46;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 9999px;
    }

    .badge-civitas-kader {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 9px;
        font-size: 0.74rem;
        font-weight: 700;
        color: #854d0e;
        background: #fefce8;
        border: 1px solid #fef08a;
        border-radius: 9999px;
    }

    .code-id-civitas {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 800;
        font-size: 0.86rem;
        color: #0f3d64;
        background: #eef4f9;
        border: 1px solid #d4e2ed;
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    /* ==========================================================================
   PANEL VERIFIKASI KEAMANAN DIGITAL & KODE QR DOKUMEN (HORIZONTAL & ELEGAN)
   ========================================================================== */
    .security-verification-card {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    /* Ikon perisai di kiri panel verifikasi (QR sudah pindah ke sidebar bawah pasfoto) */
    .security-icon-side {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #059669;
        font-size: 1.35rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ==== KARTU QR DI BAWAH PASFOTO (lebar sama persis dengan pasfoto) ==== */
    .qr-card {
        width: 140px;
        box-sizing: border-box;
        margin-top: 14px;
        padding: 8px 8px 7px 8px;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .qr-box {
        width: 100%;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* qrcode.js membuat <canvas> + <img>; tampilkan <img> saja supaya tidak dobel/meluber */
    .qr-box canvas {
        display: none !important;
    }

    .qr-box img {
        width: 100% !important;
        height: 100% !important;
        display: block;
        object-fit: contain;
    }

    .qr-caption {
        margin-top: 6px;
        font-size: 8.5px;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #0f3d64;
        line-height: 1.25;
    }

    .qr-sub {
        margin-top: 1px;
        font-size: 7.5px;
        color: #64748b;
        line-height: 1.25;
    }

    @media screen and (max-width: 600px) {
        .data-profile-layout {
            flex-direction: column;
            align-items: center;
        }

        .profile-table-wrapper {
            width: 100%;
        }
    }

    .security-info-side {
        flex: 1;
        font-size: 0.75rem;
        line-height: 1.4;
        color: #334155;
    }

    .security-badge-title {
        font-size: 0.78rem;
        font-weight: 800;
        color: #0f3d64;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 2px;
    }

    .security-token-pill {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 800;
        font-size: 0.75rem;
        color: #0f3d64;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        padding: 1px 7px;
        border-radius: 4px;
        display: inline-block;
    }

    .security-status-text {
        font-size: 0.72rem;
        font-weight: 700;
        color: #047857;
        margin-top: 2px;
    }

    .security-disclaimer {
        font-size: 0.67rem;
        color: #64748b;
        margin-top: 2px;
        line-height: 1.3;
    }

    /* Bagian Penutup Motto Khusus */
    .sheet-footer-motto {
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        text-align: center;
    }

    .motto-line-1 {
        font-size: 10pt;
        font-weight: 600;
        color: #475569;
        letter-spacing: 0.05em;
        margin: 0 0 4px 0;
        text-transform: capitalize;
    }

    .motto-line-2 {
        font-size: 15pt;
        font-weight: 800;
        font-style: italic;
        color: #0f3d64;
        letter-spacing: 0.02em;
        margin: 0 0 4px 0;
    }

    .motto-line-3 {
        font-size: 18pt;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.01em;
        margin: 0;
    }

    /* ==========================================================================
   ATURAN CETAK (@media print) - PRESISI 1 HALAMAN A4 PENUH
   ========================================================================== */
    @media print {

        .site-header,
        .site-footer,
        #navbar,
        .nav-toggle,
        .no-print,
        .screen-actions-bar,
        .screen-toast {
            display: none !important;
        }

        html,
        body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
            color: #000000 !important;
            font-size: 10.5pt !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .bukti-screen-wrapper {
            background: transparent !important;
            padding: 0 !important;
            min-height: auto !important;
        }

        .container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        @page {
            size: A4 portrait;
            margin: 8mm 14mm 8mm 14mm;
        }

        .official-sheet {
            max-width: 100% !important;
            width: 100% !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .profile-table th {
            background: #f1f5f9 !important;
            color: #334155 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .code-id-civitas,
        .security-token-pill {
            background: #e2e8f0 !important;
            color: #0f3d64 !important;
            border: 1px solid #94a3b8 !important;
            -webkit-print-color-adjust: exact !important;
        }

        .qr-card {
            break-inside: avoid !important;
            border: 1.5px solid #94a3b8 !important;
        }

        .security-verification-card {
            background: #f8fafc !important;
            border: 1.5px solid #94a3b8 !important;
            -webkit-print-color-adjust: exact !important;
        }
    }
</style>

<!-- QRCode.js untuk render QR Code instan berbasis Canvas/DOM -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<!-- html2pdf.js untuk pengunduhan berkas PDF langsung yang aman -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="bukti-screen-wrapper">
    <div class="container max-w-4xl px-4 sm:px-6">

        <!-- ================================================================
             BAR KONTROL AKSI (HANYA MUNCUL DI LAYAR MONITOR / TIDAK DICETAK)
             ================================================================ -->
        <div class="screen-actions-bar mb-6 no-print">

            <!-- 1. Navigasi & Status Verifikasi Atas -->
            <div class="flex items-center justify-between gap-3 mb-2.5">
                <a href="/register_for_civitas"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-primary transition-colors">
                    <i class="bi bi-arrow-left"></i> Kembali ke Portal Registrasi
                </a>
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Terverifikasi:</span>
                    <code
                        class="font-mono font-bold text-emerald-950 bg-white/90 px-1.5 py-0.5 rounded border border-emerald-300"><?= e($securityToken) ?></code>
                </div>
            </div>

            <!-- 2. Panel Kartu Aksi Terpadu (Single Unified Header Bar) -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm p-4 sm:p-5">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                    <!-- Sisi Kiri: Identitas Dokumen & Data Civitas -->
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div
                            class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#0f3d64] to-[#1e5687] text-white flex items-center justify-center text-xl shrink-0 shadow-xs">
                            <i class="bi bi-file-earmark-check-fill"></i>
                        </div>
                        <div class="min-w-0">
                            <h2
                                class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight leading-tight truncate">
                                <?= $isMaperca ? 'Bukti Registrasi Calon Anggota (Maperca)' : 'Surat Tanda Bukti Registrasi Masuk GMKI' ?>
                            </h2>
                            <p class="text-xs text-slate-500 flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-0.5">
                                <span>ID: <strong
                                        class="font-mono text-slate-800"><?= e($member['nim']) ?></strong></span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="truncate">Nama: <strong
                                        class="text-slate-800"><?= e($member['nama_lengkap']) ?></strong></span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="inline-flex items-center gap-1 text-emerald-700 font-bold">
                                    <i class="bi bi-shield-fill-check text-emerald-600"></i> Sah &amp; Asli
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Baris 4 Tombol Aksi yang Sejajar, Presisi & Senada -->
                    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
                        <button type="button" onclick="downloadOfficialPdf()" id="btnDownloadPdf"
                            class="h-10 px-4 rounded-xl font-bold text-xs inline-flex items-center justify-center gap-2 bg-[#0f3d64] hover:bg-[#0a2842] text-white shadow-xs hover:shadow transition-all active:scale-95 cursor-pointer border border-[#0f3d64]">
                            <i class="bi bi-download text-sm"></i>
                            <span id="btnDownloadPdfText">Unduh File PDF</span>
                        </button>

                        <button type="button" onclick="window.print()"
                            class="h-10 px-3.5 rounded-xl font-semibold text-xs inline-flex items-center justify-center gap-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 shadow-2xs hover:border-slate-400 transition-all active:scale-95 cursor-pointer">
                            <i class="bi bi-printer text-sm text-slate-500"></i>
                            <span>Cetak / Print</span>
                        </button>

                        <button type="button" onclick="copyVerificationUrl()" id="btnCopyLink"
                            class="h-10 px-3.5 rounded-xl font-semibold text-xs inline-flex items-center justify-center gap-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 shadow-2xs hover:border-slate-400 transition-all active:scale-95 cursor-pointer">
                            <i class="bi bi-link-45deg text-base text-slate-500"></i>
                            <span id="copyBtnText">Salin Tautan</span>
                        </button>

                        <a href="/"
                            class="h-10 px-3.5 rounded-xl font-semibold text-xs inline-flex items-center justify-center gap-1.5 bg-white hover:bg-slate-50 text-slate-600 border border-slate-300 shadow-2xs hover:border-slate-400 transition-all active:scale-95">
                            <i class="bi bi-house text-sm text-slate-500"></i>
                            <span>Beranda</span>
                        </a>
                    </div>

                </div>

                <!-- 3. Micro-Strip Jaminan Keamanan Terintegrasi -->
                <div
                    class="mt-3.5 pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-xs shrink-0">
                            <i class="bi bi-shield-check"></i>
                        </span>
                        <span>
                            Berkas resmi ini diverifikasi real-time pada <strong>Pangkalan Data BPC GMKI Padang</strong>
                            dan dilindungi token anti-manipulasi.
                        </span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 text-[11px] text-slate-400 font-mono">
                        <span>Format: A4 (Potret)</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================================================================
             LEMBAR DOKUMEN RESMI (OFFICIAL SHEET - TERSUSUN RAPI & ELEGAN)
             ================================================================ -->
        <article class="official-sheet" id="printable-certificate">

            <!-- 1. LOGO GMKI TERPAMPANG DI ATAS MENENGAH & JUDUL DOKUMEN -->
            <header class="sheet-header-center">
                <img src="<?= asset('images/GMKI-Logos.png') ?>" alt="Logo GMKI" class="sheet-logo">
                <h1 class="sheet-title">
                    <?= $isMaperca ? 'BUKTI REGISTRASI CALON ANGGOTA (MAPERCA)' : 'BUKTI REGISTRASI PANGKALAN DATA CIVITAS' ?>
                </h1>
            </header>

            <!-- 2. DATA IDENTITAS & PASFOTO RESMI (LAYOUT 2 KOLOM RAPI & SEIMBANG) -->
            <div class="data-profile-layout">

                <!-- Kolom Kiri: Pasfoto 3x4 Bersih dan Status -->
                <aside class="profile-sidebar">
                    <!-- Pasfoto Resmi Anggota -->
                    <div class="pasfoto-box">
                        <img src="<?= e($fotoUrl) ?>" alt="<?= e($member['nama_lengkap']) ?>" class="pasfoto-img"
                            onerror="this.onerror=null;this.src='<?= asset('images/default-profile.png') ?>';">
                        <div class="pasfoto-tag">Pasfoto Resmi</div>
                    </div>

                    <!-- Badge Status Keanggotaan -->
                    <div style="margin-top: 2px;">
                        <?php if ($isMaperca): ?>
                            <span class="badge-civitas-kader">
                                <i class="bi bi-mortarboard-fill"></i> Kader Baru (Maperca)
                            </span>
                        <?php elseif ($sk === 'Aktif'): ?>
                            <span class="badge-civitas-aktif">
                                <i class="bi bi-check-circle-fill"></i> Anggota Aktif
                            </span>
                        <?php elseif ($sk === 'Pasif'): ?>
                            <span class="badge-civitas-kader"
                                style="color: #92400e; background: #fef3c7; border-color: #fde68a;">
                                <i class="bi bi-hourglass-split"></i> Anggota Pasif
                            </span>
                        <?php elseif ($sk === 'Alumni'): ?>
                            <span class="badge-civitas-kader"
                                style="color: #1e40af; background: #eff6ff; border-color: #bfdbfe;">
                                <i class="bi bi-award-fill"></i> Alumni Cabang
                            </span>
                        <?php elseif ($sk === 'Senior'): ?>
                            <span class="badge-civitas-kader"
                                style="color: #5b21b6; background: #f5f3ff; border-color: #ddd6fe;">
                                <i class="bi bi-star-fill"></i> Senior Cabang
                            </span>
                        <?php else: ?>
                            <span class="badge-civitas-aktif"><?= e($sk) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Kode QR Verifikasi (di bawah pasfoto, lebar sejajar foto) -->
                    <div class="qr-card">
                        <a href="<?= e($currentUrl) ?>" target="_blank"
                            title="Klik atau scan untuk memverifikasi keaslian dokumen"
                            style="display:block;text-decoration:none;color:inherit;">
                            <div id="qrcode-target" class="qr-box">
                                <img src="<?= e($qrApiUrl) ?>" alt="QR Code Verifikasi" loading="eager">
                            </div>
                        </a>
                        <div class="qr-caption">Scan Verifikasi</div>
                        <div class="qr-sub">Keaslian dokumen</div>
                    </div>
                </aside>

                <!-- Kolom Kanan: 13 Baris Data Lengkap -->
                <div class="profile-table-wrapper">
                    <table class="profile-table">
                        <tbody>
                            <!-- Baris 1: ID ANGGOTA / CIVITAS / MAPERCA -->
                            <tr>
                                <th><?= $isMaperca ? 'ID CALON ANGGOTA (MAPERCA)' : 'ID ANGGOTA / CIVITAS' ?></th>
                                <td>
                                    <span class="code-id-civitas"><?= e($member['nim']) ?></span>
                                </td>
                            </tr>

                            <!-- Baris 2: NAMA LENGKAP -->
                            <tr>
                                <th>NAMA LENGKAP</th>
                                <td>
                                    <strong
                                        style="font-size: 0.95rem; color: #0f172a;"><?= e($member['nama_lengkap']) ?></strong>
                                </td>
                            </tr>

                            <!-- Baris 3: JENIS KELAMIN -->
                            <tr>
                                <th>JENIS KELAMIN</th>
                                <td>
                                    <?= ($member['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                </td>
                            </tr>

                            <!-- Baris 4: TEMPAT, TANGGAL LAHIR -->
                            <tr>
                                <th>TEMPAT, TANGGAL LAHIR</th>
                                <td>
                                    <?= e($member['tempat_lahir'] ?? '-') ?>,
                                    <?= !empty($member['tanggal_lahir']) ? format_date($member['tanggal_lahir'], 'd F Y') : '-' ?>
                                </td>
                            </tr>

                            <!-- Baris 5: PERGURUAN TINGGI -->
                            <tr>
                                <th><?= $isMaperca ? 'PERGURUAN TINGGI / KAMPUS' : 'PERGURUAN TINGGI' ?></th>
                                <td>
                                    <strong
                                        style="color: #0f3d64;"><?= e($member['perguruan_tinggi'] ?? '-') ?></strong>
                                </td>
                            </tr>

                            <!-- Baris 6: FAKULTAS / JURUSAN -->
                            <tr>
                                <th>FAKULTAS / JURUSAN</th>
                                <td>
                                    <?= e($member['fakultas'] ?? '-') ?> / <?= e($member['jurusan'] ?? '-') ?>
                                </td>
                            </tr>

                            <!-- Baris 7: KOMISARIAT / PENEMPATAN KOMISARIAT -->
                            <tr>
                                <th><?= $isMaperca ? 'PENEMPATAN KOMISARIAT' : 'KOMISARIAT' ?></th>
                                <td>
                                    <?php if ($isMaperca): ?>
                                        <?php if (!empty($member['komisariat'])): ?>
                                            <span style="font-weight: 700; color: #0f3d64;"><?= e($member['komisariat']) ?></span>
                                        <?php else: ?>
                                            <span style="color: #64748b; font-style: italic;">Ditetapkan setelah resmi dilantik menjadi Anggota</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if (!empty($member['komisariat'])): ?>
                                            <span style="font-weight: 700; color: #0f3d64;"><?= e($member['komisariat']) ?></span>
                                        <?php else: ?>
                                            <span style="color: #64748b;">Tidak pernah sama sekali</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Baris 8: TAHUN MAPERCA -->
                            <tr>
                                <th><?= $isMaperca ? 'TAHUN / ANGKATAN MAPERCA' : 'TAHUN MAPERCA' ?></th>
                                <td>
                                    <strong><?= e($member['tahun_maperca'] ?? '-') ?></strong>
                                </td>
                            </tr>

                            <!-- Baris 9: STATUS KEANGGOTAAN / STATUS TAHAPAN KADERISASI -->
                            <tr>
                                <th><?= $isMaperca ? 'STATUS TAHAPAN' : 'STATUS KEANGGOTAAN' ?></th>
                                <td>
                                    <?php if ($isMaperca): ?>
                                        <span class="badge-civitas-kader">
                                            <i class="bi bi-mortarboard-fill"></i> Kader Baru (Peserta Maperca Terdaftar)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-civitas-aktif">
                                            <?= e($skLabel) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Baris 10: TELEPON / WA -->
                            <tr>
                                <th><?= $isMaperca ? 'NO. WHATSAPP (AKTIF)' : 'TELEPON / WA' ?></th>
                                <td
                                    style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 600;">
                                    <?= e($member['telepon'] ?? '-') ?>
                                </td>
                            </tr>

                            <!-- Baris 11: EMAIL -->
                            <tr>
                                <th><?= $isMaperca ? 'ALAMAT EMAIL' : 'EMAIL' ?></th>
                                <td>
                                    <?= e($member['email'] ?? '-') ?>
                                </td>
                            </tr>

                            <!-- Baris 12: ALAMAT DI PADANG -->
                            <tr>
                                <th><?= $isMaperca ? 'ALAMAT KOST / TINGGAL DI PADANG' : 'ALAMAT DI PADANG' ?></th>
                                <td>
                                    <?= nl2br(e($member['alamat_padang'] ?? '-')) ?>
                                </td>
                            </tr>

                            <!-- Baris 13: ASAL CIVITAS / DAERAH ASAL -->
                            <tr>
                                <th><?= $isMaperca ? 'KOTA / DAERAH ASAL' : 'ASAL CIVITAS' ?></th>
                                <td>
                                    <strong><?= e($member['asal_civitas'] ?? $member['tempat_lahir'] ?? '-') ?></strong>
                                </td>
                            </tr>

                            <!-- Baris 14: MOTIVASI BERGABUNG (KHUSUS MAPERCA JIKA MENGISI) -->
                            <?php if ($isMaperca && !empty($member['catatan'])): 
                                $cleanCatatan = trim(preg_replace('/^\[Registrasi Mandiri[^\]]*\]\s*/i', '', (string)$member['catatan']));
                                if ($cleanCatatan !== ''):
                            ?>
                            <tr>
                                <th>MOTIVASI BERGABUNG</th>
                                <td style="font-style: italic; color: #334155; line-height: 1.4;">
                                    &ldquo;<?= nl2br(e($cleanCatatan)) ?>&rdquo;
                                </td>
                            </tr>
                            <?php endif; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. KOTAK VERIFIKASI KEAMANAN DIGITAL & KODE QR (POSISI BARU: INTEGRATIF & ELEGAN) -->
            <section class="security-verification-card">
                <div class="security-icon-side"><i class="bi bi-shield-lock-fill"></i></div>

                <!-- Sisi Kanan: Informasi Integritas & Token Keamanan Kriptografis -->
                <div class="security-info-side">
                    <div class="security-badge-title">
                        <span>Sistem Verifikasi Keaslian Dokumen Digital</span>
                    </div>
                    <div>
                        Kode Token Keamanan: <span class="security-token-pill"><?= e($securityToken) ?></span>
                    </div>
                    <div class="security-status-text">
                        <i class="bi bi-check-circle-fill"></i> Status Digital: Terverifikasi Sah di Pangkalan Data GMKI
                        Padang
                    </div>
                    <div class="security-disclaimer">
                        Pindai kode QR di bawah pasfoto untuk memvalidasi keaslian dokumen ini secara real-time. Dokumen
                        resmi ini dilindungi tanda tangan kriptografi anti-manipulasi server.
                    </div>
                </div>
            </section>

            <!-- 4. BAGIAN PENUTUP (MOTTO & SALAM SESUAI UKURAN YANG DIMINTA) -->
            <footer class="sheet-footer-motto">
                <!-- Tinggi Iman, Tinggi Ilmu, Tinggi Pengabdian (ukuran 9-11) -->
                <p class="motto-line-1">Tinggi Iman, Tinggi Ilmu, Tinggi Pengabdian</p>
                <!-- Ut Omnes Unum Sint (ukuran 14-16) -->
                <p class="motto-line-2">Ut Omnes Unum Sint</p>
                <!-- Shalom, Tuhan Yesus Memberkati (ukuran 17-19) -->
                <p class="motto-line-3">Shalom, Tuhan Yesus Memberkati</p>
            </footer>

        </article>

        <!-- Banner Tindakan Lanjutan Bawah (Hanya Layar Monitor) -->
        <div class="mt-6 text-center no-print">
            <p class="text-xs text-slate-500 mb-3">
                Ingin mendaftarkan rekan, kader baru, atau memperbarui data civitas lainnya?
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="/register_for_civitas/member"
                    class="btn btn-outline btn-sm bg-white text-slate-700 hover:text-primary">
                    <i class="bi bi-person-plus-fill"></i> Daftarkan Anggota / Civitas Lain
                </a>
                <a href="/register_for_civitas/maperca"
                    class="btn btn-outline btn-sm bg-white text-slate-700 hover:text-primary">
                    <i class="bi bi-mortarboard-fill"></i> Daftarkan Calon Maperca (Cadet)
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    // 1. Render QR Code menggunakan QRCode.js untuk ketajaman vektor tinggi
    document.addEventListener('DOMContentLoaded', function () {
        const target = document.getElementById('qrcode-target');
        const qrUrl = <?= json_encode($currentUrl) ?>;

        if (typeof QRCode !== 'undefined' && target && qrUrl) {
            try {
                target.innerHTML = '';
                new QRCode(target, {
                    text: qrUrl,
                    width: 240,
                    height: 240,
                    colorDark: "#0f3d64",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.M
                });
            } catch (e) {
                console.warn('Fallback to API QR:', e);
            }
        }
    });

    // 2. Fungsi Unduh File PDF Resmi Langsung (Aman & Presisi A4)
    function downloadOfficialPdf() {
        const element = document.getElementById('printable-certificate');
        const btn = document.getElementById('btnDownloadPdf');
        const btnText = document.getElementById('btnDownloadPdfText');
        const nim = <?= json_encode($member['nim']) ?>;
        const cleanNim = nim.replace(/[^a-zA-Z0-9_-]/g, '_');

        if (btn && btnText) {
            btn.disabled = true;
            btnText.innerHTML = 'Memproses PDF...';
        }

        if (typeof html2pdf !== 'undefined') {
            const opt = {
                margin: [8, 10, 8, 10],
                filename: 'Bukti_Registrasi_GMKI_' + cleanNim + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, logging: false },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                if (btn && btnText) {
                    btn.disabled = false;
                    btnText.innerHTML = 'Unduh File PDF';
                }
            }).catch((err) => {
                console.error('html2pdf error:', err);
                if (btn && btnText) {
                    btn.disabled = false;
                    btnText.innerHTML = 'Unduh File PDF';
                }
                window.print();
            });
        } else {
            window.print();
        }
    }

    // 3. Salin Tautan Verifikasi
    function copyVerificationUrl() {
        const url = <?= json_encode($currentUrl) ?>;
        const btnText = document.getElementById('copyBtnText');

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(() => {
                showCopySuccess(btnText);
            }).catch(() => {
                fallbackCopy(url, btnText);
            });
        } else {
            fallbackCopy(url, btnText);
        }
    }

    function fallbackCopy(text, btnText) {
        const input = document.createElement('input');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        try {
            document.execCommand('copy');
            showCopySuccess(btnText);
        } catch (err) {
            alert('Tautan verifikasi: ' + text);
        }
        document.body.removeChild(input);
    }

    function showCopySuccess(btnText) {
        if (!btnText) return;
        const original = btnText.innerHTML;
        btnText.innerHTML = 'Tersalin!';
        btnText.parentElement.classList.add('text-emerald-700', 'border-emerald-300', 'bg-emerald-50');
        setTimeout(() => {
            btnText.innerHTML = original;
            btnText.parentElement.classList.remove('text-emerald-700', 'border-emerald-300', 'bg-emerald-50');
        }, 2500);
    }
</script>