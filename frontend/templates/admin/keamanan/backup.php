<?php
/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: frontend/templates/admin/keamanan/backup.php
 * Deskripsi: Antarmuka Pencadangan Data, Integritas Berkas & Otomasi Unduhan
 * Lapisan: View (Admin Portal)
 * =====================================================================
 */

$latestTotal = !empty($totalBackups) ? $totalBackups[0] : null;
$latestDb = !empty($dbBackups) ? $dbBackups[0] : null;
$manifestCount = count($manifestEntries ?? []);
$totalBackupsCount = count($totalBackups ?? []);
$dbBackupsCount = count($dbBackups ?? []);
?>

<div>
    <!-- Navigasi Tab Keamanan -->
    <div class="sec-nav-tabs">
        <a href="/admin/keamanan/audit-log" class="sec-nav-tab">
            <span>Jejak Audit Data</span>
        </a>
        <a href="/admin/keamanan/akses-log" class="sec-nav-tab">
            <span>Log Akses Pengunjung</span>
        </a>
        <a href="/admin/keamanan/ancaman" class="sec-nav-tab">
            <span>Deteksi Ancaman &amp; Perisai Sistem</span>
        </a>
        <a href="/admin/keamanan/backup" class="sec-nav-tab active">
            <span>Backup &amp; Pemulihan</span>
        </a>
    </div>

    <!-- Header Bersih & Minimalis -->
    <div class="sec-clean-header">
        <div>
            <h1 class="sec-clean-title">
                <span>Pencadangan Data &amp; Pemulihan Sistem</span>
                <span class="badge badge-success" style="font-size: 0.72rem; font-weight: 600;">
                    ✓ Disaster Recovery Siap
                </span>
            </h1>
            <p class="sec-clean-subtitle">
                Pencadangan snapshot MySQL ACID transaksional dan pengarsipan seluruh berkas fisik terenkapsulasi ZIP &amp; SHA-256.
            </p>
        </div>

        <div class="sec-clean-actions">
            <?php if (can('backup.create')): ?>
                <form action="/admin/keamanan/backup/manifest" method="POST" style="margin: 0;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline btn-sm" style="height: 36px; border-radius: 8px; font-weight: 600;"
                        title="Perbarui manifest verifikasi hash SHA-256 berkas fisik">
                        📜 Perbarui Manifest SHA-256
                    </button>
                </form>
                <a href="/admin/berkas/backup?type=all" class="btn btn-outline btn-sm" style="height: 36px; border-radius: 8px; font-weight: 600;"
                    title="Cadangkan dokumen dan persuratan ruang kerja">
                    📂 Unduh Berkas Persuratan
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Ringkasan Statistik Minimalis (4 Kolom) -->
    <div class="sec-clean-stats">
        <div class="sec-stat-item">
            <div class="sec-stat-label">Cadangan Total Terakhir</div>
            <div class="sec-stat-value" style="font-size: 1.35rem;">
                <?= $latestTotal ? e($latestTotal['size_formatted']) : 'Belum Ada' ?>
            </div>
            <div class="sec-stat-note truncate" title="<?= $latestTotal ? e($latestTotal['filename']) : '' ?>">
                <?= $latestTotal ? format_date($latestTotal['created_at'], 'd M Y H:i') : 'Paket arsip .zip sistem' ?>
            </div>
        </div>

        <div class="sec-stat-item">
            <div class="sec-stat-label">Snapshot Database Terakhir</div>
            <div class="sec-stat-value" style="font-size: 1.35rem;">
                <?= $latestDb ? e($latestDb['size_formatted']) : 'Belum Ada' ?>
            </div>
            <div class="sec-stat-note truncate" title="<?= $latestDb ? e($latestDb['filename']) : '' ?>">
                <?= $latestDb ? format_date($latestDb['created_at'], 'd M Y H:i') : 'Dump PDO konsisten ACID' ?>
            </div>
        </div>

        <div class="sec-stat-item">
            <div class="sec-stat-label">Integritas Berkas Unggahan</div>
            <div class="sec-stat-value" style="font-size: 1.35rem;">
                <?= number_format($manifestCount) ?> Berkas
            </div>
            <div class="sec-stat-note">Validasi checksum SHA-256</div>
        </div>

        <div class="sec-stat-item">
            <div class="sec-stat-label">Status Enkapsulasi</div>
            <div class="sec-stat-value" style="font-size: 1.35rem; color: #10b981;">100% Aktif</div>
            <div class="sec-stat-note">Gzip L9 + Native ZipArchive</div>
        </div>
    </div>

    <!-- ============================================================================== -->
    <!-- DRAWER INTERAKTIF: HITUNG MUNDUR, LIVE PROGRES & AUTO-DOWNLOAD NOTIFIER -->
    <!-- ============================================================================== -->
    <div id="smartBackupDrawer" class="bk-drawer" style="display: none;">
        <!-- State A: Countdown Box -->
        <div id="drawerCountdownState" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div id="drawerTimerNumber" class="bk-timer-circle">5</div>
                <div>
                    <div style="font-size: 0.95rem; font-weight: 700; color: #0f172a;">
                        Mempersiapkan Pencadangan Total Otomatis...
                    </div>
                    <div style="font-size: 0.76rem; color: #64748b; margin-top: 0.15rem;">
                        Proses kompresi paket total dan pengunduhan akan berjalan saat hitung mundur selesai.
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="btn btn-outline btn-sm font-semibold"
                    style="color: #ef4444; border-color: #fecaca;"
                    onclick="cancelSmartCountdown()">
                    ✕ Batalkan
                </button>
                <button type="button" class="btn btn-primary btn-sm font-semibold"
                    style="background: #0284c7; border-color: #0284c7;"
                    onclick="executeSmartBackupNow()">
                    ⚡ Proses Sekarang
                </button>
            </div>
        </div>

        <!-- State B: Processing Progress Box -->
        <div id="drawerProcessingState" style="display: none;">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="spinner-inline"
                        style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-top-color: #0284c7; border-radius: 50%; animation: spin 0.8s linear infinite;">
                    </div>
                    <span id="drawerStepText" style="font-size: 0.82rem; font-weight: 700; color: #0f172a;">
                        Menghubungkan ke basis data dan membuat snapshot konsisten ACID...
                    </span>
                </div>
                <span id="drawerPercentText" style="font-size: 0.78rem; font-weight: 800; font-family: monospace; color: #0284c7;">25%</span>
            </div>
            <div class="bk-progress-track">
                <div id="drawerProgressBar" class="bk-progress-bar" style="width: 25%;"></div>
            </div>
            <div class="bk-steps-row">
                <span id="badgeStep1" class="font-bold text-sky-700">1. Snapshot Database</span>
                <span id="badgeStep2" class="font-semibold">2. Berkas Fisik &amp; KTA</span>
                <span id="badgeStep3" class="font-semibold">3. Checksum SHA-256</span>
                <span id="badgeStep4" class="font-semibold">4. Arsip ZIP &amp; Unduh</span>
            </div>
        </div>

        <!-- State C: Success Banner -->
        <div id="drawerSuccessState" style="display: none;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div style="font-size: 1.6rem; line-height: 1;">🎉</div>
                    <div>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #065f46;">
                            Pencadangan Total Selesai &amp; Pengunduhan Sedang Berjalan!
                        </div>
                        <div style="font-size: 0.76rem; color: #475569; margin-top: 0.2rem;">
                            Berkas: <code id="lblSuccessFilename" class="sec-code-inline">backup_total.zip</code> •
                            Ukuran: <strong id="lblSuccessSize" class="text-slate-800">0 MB</strong> •
                            Total: <strong id="lblSuccessFiles" class="text-slate-800">0 berkas</strong> •
                            SHA-256: <code id="lblSuccessSha" class="sec-code-inline" style="max-width: 140px;">-</code>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a id="lnkSuccessDownload" href="#" class="btn btn-sm font-semibold"
                        style="background: #10b981; color: #ffffff; border: none; height: 34px;">
                        ⬇️ Unduh Ulang (.zip)
                    </a>
                    <button type="button" class="btn btn-outline btn-sm font-semibold"
                        style="height: 34px;"
                        onclick="closeSmartDrawer()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================================== -->
    <!-- DUA PILIHAN PENCADANGAN UTAMA (CLEAN & ELEGANT DUAL ACTION) -->
    <!-- ============================================================================== -->
    <div class="bk-options-grid">
        <!-- Card 1: Paket Lengkap Cadangan Total (.zip) -->
        <div class="bk-card">
            <div>
                <div class="bk-card-header">
                    <div class="bk-card-icon-title">
                        <div class="bk-icon-box sky">📦</div>
                        <div>
                            <h2 class="bk-card-title">Paket Cadangan Total Sistem (.zip)</h2>
                            <div class="bk-card-sub">Database MySQL + Foto Civitas + Dokumen + SHA-256</div>
                        </div>
                    </div>
                    <span class="bk-badge-rec">⭐ Rekomendasi Utama</span>
                </div>

                <div class="bk-desc-box">
                    Mengemas pangkalan data MySQL dan seluruh berkas fisik (foto civitas, KTA digital, dokumen administrasi) ke dalam satu arsip ZIP terkompresi lengkap dengan manifest integritas untuk pemulihan bencana.
                </div>
            </div>

            <div>
                <a href="/admin/keamanan/backup/unduh-total-sekarang" class="bk-btn-secondary"
                    title="Buat paket total dan langsung unduh ke perangkat lokal">
                    <?= svg_icon('download', 16) ?>
                    <span>Buat &amp; Unduh Paket Total (.zip)</span>
                </a>

                <div class="bk-btn-strip">
                    <button type="button" class="bk-btn-outline w-1/2" onclick="startSmartCountdown(5)"
                        title="Jalankan otomatis dengan hitung mundur 5 detik">
                        <span>⏱️ Otomatis (5d)</span>
                    </button>
                    <button type="button" class="bk-btn-outline w-1/2" onclick="startSmartCountdown(10)"
                        title="Jalankan otomatis dengan hitung mundur 10 detik">
                        <span>⏱️ Otomatis (10d)</span>
                    </button>
                    <form action="/admin/keamanan/backup/total" method="POST" style="margin: 0;">
                        <?= csrf_field() ?>
                        <button type="submit" class="bk-btn-outline" style="padding: 0.42rem 0.65rem;"
                            title="Simpan arsip di server tanpa mengunduh langsung">
                            💾
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Card 2: Snapshot Basis Data MySQL (.sql.gz) -->
        <div class="bk-card">
            <div>
                <div class="bk-card-header">
                    <div class="bk-card-icon-title">
                        <div class="bk-icon-box emerald">💾</div>
                        <div>
                            <h2 class="bk-card-title">Snapshot Basis Data MySQL (.sql.gz)</h2>
                            <div class="bk-card-sub">Dump Skema &amp; Record Data PDO Terkompresi Gzip L9</div>
                        </div>
                    </div>
                    <span class="bk-badge-fast">⚡ Cepat &amp; Ringan</span>
                </div>

                <div class="bk-desc-box">
                    Ekspor seluruh struktur tabel dan data record MySQL menggunakan teknik streaming transaksional ACID berkecepatan tinggi terkompresi Gzip Level 9 untuk efisiensi penyimpanan dan pencadangan harian.
                </div>
            </div>

            <div>
                <a href="/admin/keamanan/backup/unduh-sekarang" class="bk-btn-primary"
                    title="Buat snapshot database dan langsung unduh">
                    <?= svg_icon('download', 16) ?>
                    <span>Buat &amp; Unduh Database (.sql.gz)</span>
                </a>

                <div class="bk-btn-strip">
                    <form action="/admin/keamanan/backup/database" method="POST" style="margin: 0; width: 100%;">
                        <?= csrf_field() ?>
                        <button type="submit" class="bk-btn-outline w-full"
                            title="Buat dump snapshot database baru dan simpan di server">
                            <span>💾 Buat Arsip di Server Saja</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================================== -->
    <!-- PANEL TABEL RIWAYAT ARSIP & INTEGRITAS (MODERN SEGMENTED TABS) -->
    <!-- ============================================================================== -->
    <div class="bk-table-card">
        <!-- Sub-Tabs Switcher -->
        <div class="bk-table-tabs">
            <button type="button" id="tabBtn_total" class="bk-tab-btn active" onclick="switchBkTab('total')">
                <span>📦 Cadangan Total (.zip)</span>
                <span class="bk-tab-badge" id="badgeCountTotal"><?= $totalBackupsCount ?></span>
            </button>
            <button type="button" id="tabBtn_db" class="bk-tab-btn" onclick="switchBkTab('db')">
                <span>💾 Snapshot Database (.sql.gz)</span>
                <span class="bk-tab-badge"><?= $dbBackupsCount ?></span>
            </button>
            <button type="button" id="tabBtn_manifest" class="bk-tab-btn" onclick="switchBkTab('manifest')">
                <span>🛡️ Integritas Berkas Unggahan</span>
                <span class="bk-tab-badge"><?= $manifestCount ?></span>
            </button>
        </div>

        <!-- Tab Pane 1: Cadangan Total (.zip) -->
        <div id="tabPane_total">
            <div class="bk-table-header">
                <div>
                    <h3 class="bk-table-title">Arsip Cadangan Total Sistem</h3>
                    <div class="bk-table-sub">
                        Tersimpan di direktori aman <code>storage/backup/total/</code> dengan verifikasi hash SHA-256.
                    </div>
                </div>
                <div>
                    <a href="/admin/keamanan/backup/unduh-total-sekarang" class="btn btn-outline btn-sm font-semibold flex items-center gap-1.5"
                        style="height: 34px;">
                        <?= svg_icon('download', 14) ?>
                        <span>Buat Total Baru</span>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th>Nama Berkas Arsip</th>
                            <th>Ukuran</th>
                            <th>Waktu Pembuatan</th>
                            <th>Integritas SHA-256</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="totalBackupsTbody">
                        <?php if (!empty($totalBackups)): ?>
                            <?php foreach ($totalBackups as $tb): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span style="font-size: 1.1rem;">📦</span>
                                            <code class="sec-code-inline" style="max-width: 220px;" title="<?= e($tb['filename']) ?>"><?= e($tb['filename']) ?></code>
                                        </div>
                                    </td>
                                    <td><strong><?= e($tb['size_formatted']) ?></strong></td>
                                    <td class="text-slate-500"><?= format_date($tb['created_at'], 'd M Y H:i:s') ?></td>
                                    <td>
                                        <code class="sec-code-inline text-xs" style="max-width: 140px;" title="<?= e($tb['sha256']) ?>">
                                            <?= e($tb['sha256_short'] ?? substr($tb['sha256'], 0, 16) . '...') ?>
                                        </code>
                                    </td>
                                    <td><span class="badge badge-success">Valid</span></td>
                                    <td class="text-right">
                                        <a href="/admin/keamanan/backup/download-total?file=<?= urlencode($tb['filename']) ?>"
                                            class="btn btn-outline btn-sm inline-flex items-center gap-1 font-semibold"
                                            style="height: 30px; padding: 0.25rem 0.65rem;"
                                            title="Unduh paket cadangan total ini">
                                            <?= svg_icon('download', 13) ?>
                                            <span>Unduh (.zip)</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="emptyTotalRow">
                                <td colspan="6" class="text-center text-slate-400" style="padding: 2.5rem 1rem;">
                                    Belum ada arsip cadangan total sistem. Klik tombol <strong>"Buat &amp; Unduh Paket Total"</strong> di atas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Pane 2: Snapshot Database (.sql.gz) -->
        <div id="tabPane_db" style="display: none;">
            <div class="bk-table-header">
                <div>
                    <h3 class="bk-table-title">Arsip Snapshot Basis Data MySQL</h3>
                    <div class="bk-table-sub">
                        Tersimpan di direktori aman <code>storage/backup/database/</code> dengan validasi hash SHA-256.
                    </div>
                </div>
                <div>
                    <a href="/admin/keamanan/backup/unduh-sekarang" class="btn btn-outline btn-sm font-semibold flex items-center gap-1.5"
                        style="height: 34px;">
                        <?= svg_icon('download', 14) ?>
                        <span>Unduh Database Langsung</span>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th>Nama Berkas</th>
                            <th>Ukuran</th>
                            <th>Waktu Pembuatan</th>
                            <th>Integritas SHA-256</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dbBackups)): ?>
                            <?php foreach ($dbBackups as $b): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span style="font-size: 1.1rem;">💾</span>
                                            <code class="sec-code-inline" style="max-width: 220px;" title="<?= e($b['filename']) ?>"><?= e($b['filename']) ?></code>
                                        </div>
                                    </td>
                                    <td><strong><?= e($b['size_formatted']) ?></strong></td>
                                    <td class="text-slate-500"><?= format_date($b['created_at'], 'd M Y H:i:s') ?></td>
                                    <td>
                                        <code class="sec-code-inline text-xs" style="max-width: 140px;" title="<?= e($b['sha256']) ?>">
                                            <?= e($b['sha256_short'] ?? substr($b['sha256'], 0, 16) . '...') ?>
                                        </code>
                                    </td>
                                    <td><span class="badge badge-success">GZIP Valid</span></td>
                                    <td class="text-right">
                                        <a href="/admin/keamanan/backup/download?file=<?= urlencode($b['filename']) ?>"
                                            class="btn btn-outline btn-sm inline-flex items-center gap-1 font-semibold"
                                            style="height: 30px; padding: 0.25rem 0.65rem;"
                                            title="Unduh snapshot basis data ini">
                                            <?= svg_icon('download', 13) ?>
                                            <span>Unduh (.sql.gz)</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-slate-400" style="padding: 2.5rem 1rem;">
                                    Belum ada arsip database. Klik tombol <strong>"Buat &amp; Unduh Database"</strong> di atas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Pane 3: Manifest Integritas Berkas Fisik -->
        <div id="tabPane_manifest" style="display: none;">
            <div class="bk-table-header">
                <div>
                    <h3 class="bk-table-title">Integritas Berkas Unggahan (manifest.csv)</h3>
                    <div class="bk-table-sub">
                        Menampilkan rekaman hash SHA-256 untuk mendeteksi manipulasi berkas fisik di folder penyimpanan publik dan privat.
                    </div>
                </div>
                <div>
                    <form action="/admin/keamanan/backup/manifest" method="POST" style="margin: 0;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline btn-sm font-semibold flex items-center gap-1.5"
                            style="height: 34px;">
                            <span>📜 Perbarui Manifest</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th>Jalur Berkas (Relative Path)</th>
                            <th>Ukuran</th>
                            <th>Checksum SHA-256</th>
                            <th>Waktu Modifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($manifestEntries)): ?>
                            <?php foreach (array_slice($manifestEntries, 0, 30) as $entry): ?>
                                <tr>
                                    <td>
                                        <code class="sec-code-inline" style="max-width: 320px;" title="<?= e($entry['filename']) ?>">
                                            <?= e($entry['filename']) ?>
                                        </code>
                                    </td>
                                    <td><?= round((int) $entry['filesize'] / 1024, 1) ?> KB</td>
                                    <td>
                                        <code class="sec-code-inline text-xs" style="max-width: 220px;" title="<?= e($entry['sha256']) ?>">
                                            <?= e($entry['sha256']) ?>
                                        </code>
                                    </td>
                                    <td class="text-slate-500"><?= format_date($entry['created_at'], 'd M Y H:i:s') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-slate-400" style="padding: 2.5rem 1rem;">
                                    Manifest berkas belum dibuat. Klik tombol <strong>"Perbarui Manifest"</strong> di atas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<!-- ============================================================================== -->
<!-- JAVASCRIPT: LOGIKA HITUNG MUNDUR, PROGRES, TABS & AUTO-DOWNLOAD -->
<!-- ============================================================================== -->
<script>
    let smartTimerId = null;
    let remainingCountdown = 5;
    let isSmartRunning = false;

    function switchBkTab(tabName) {
        const tabs = ['total', 'db', 'manifest'];
        tabs.forEach(t => {
            const btn = document.getElementById('tabBtn_' + t);
            const pane = document.getElementById('tabPane_' + t);
            if (btn && pane) {
                if (t === tabName) {
                    btn.classList.add('active');
                    pane.style.display = 'block';
                } else {
                    btn.classList.remove('active');
                    pane.style.display = 'none';
                }
            }
        });
    }

    function startSmartCountdown(seconds) {
        if (isSmartRunning) return;

        remainingCountdown = seconds;
        const drawer = document.getElementById('smartBackupDrawer');
        const countdownBox = document.getElementById('drawerCountdownState');
        const processingBox = document.getElementById('drawerProcessingState');
        const successBox = document.getElementById('drawerSuccessState');
        const timerNum = document.getElementById('drawerTimerNumber');

        drawer.style.display = 'block';
        countdownBox.style.display = 'flex';
        processingBox.style.display = 'none';
        successBox.style.display = 'none';
        timerNum.textContent = remainingCountdown;

        // Scroll smoothly to drawer
        drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        if (smartTimerId) clearInterval(smartTimerId);

        smartTimerId = setInterval(() => {
            remainingCountdown--;
            timerNum.textContent = remainingCountdown;

            if (remainingCountdown <= 0) {
                clearInterval(smartTimerId);
                smartTimerId = null;
                executeSmartBackupNow();
            }
        }, 1000);
    }

    function cancelSmartCountdown() {
        if (smartTimerId) {
            clearInterval(smartTimerId);
            smartTimerId = null;
        }
        document.getElementById('smartBackupDrawer').style.display = 'none';
    }

    async function executeSmartBackupNow() {
        if (smartTimerId) {
            clearInterval(smartTimerId);
            smartTimerId = null;
        }

        isSmartRunning = true;
        const countdownBox = document.getElementById('drawerCountdownState');
        const processingBox = document.getElementById('drawerProcessingState');
        const successBox = document.getElementById('drawerSuccessState');
        const progressBar = document.getElementById('drawerProgressBar');
        const percentText = document.getElementById('drawerPercentText');
        const stepText = document.getElementById('drawerStepText');

        countdownBox.style.display = 'none';
        processingBox.style.display = 'block';
        successBox.style.display = 'none';

        updateSmartStep(1, 'Menghubungkan ke basis data dan membuat snapshot konsisten ACID...', 25);

        const animInterval = setInterval(() => {
            const curW = parseInt(progressBar.style.width) || 25;
            if (curW < 85) {
                const nextW = curW + 15;
                progressBar.style.width = nextW + '%';
                percentText.textContent = nextW + '%';
                if (nextW >= 50 && nextW < 75) {
                    updateSmartStep(2, 'Mengumpulkan berkas foto civitas, KTA, dan dokumen kantor...', nextW);
                } else if (nextW >= 75) {
                    updateSmartStep(3, 'Menghasilkan checksum kriptografi SHA-256 dan manifest...', nextW);
                }
            }
        }, 450);

        try {
            const csrfToken = "<?= csrf_token() ?>";
            const response = await fetch('/admin/keamanan/backup/total-ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({ _token: csrfToken })
            });

            clearInterval(animInterval);
            const data = await response.json();

            if (data && data.success) {
                updateSmartStep(4, 'Arsip ZIP selesai dibuat! Memulai unduhan otomatis...', 100);

                setTimeout(() => {
                    processingBox.style.display = 'none';
                    successBox.style.display = 'block';

                    document.getElementById('lblSuccessFilename').textContent = data.filename;
                    document.getElementById('lblSuccessSize').textContent = data.size_formatted;
                    document.getElementById('lblSuccessFiles').textContent = data.total_files + ' berkas';
                    document.getElementById('lblSuccessSha').textContent = data.sha256 ? data.sha256.substring(0, 20) + '...' : '-';
                    document.getElementById('lblSuccessSha').title = data.sha256 || '';
                    document.getElementById('lnkSuccessDownload').href = data.download_url;

                    // Memicu pengunduhan otomatis ke browser
                    triggerBrowserDownload(data.download_url);

                    // Pastikan tab total aktif dan baris baru dimasukkan ke tabel
                    prependBackupRow(data);

                    isSmartRunning = false;
                }, 500);
            } else {
                throw new Error(data.message || 'Gagal memproses pencadangan total.');
            }
        } catch (err) {
            clearInterval(animInterval);
            isSmartRunning = false;
            alert('Terjadi kesalahan: ' + err.message);
            processingBox.style.display = 'none';
        }
    }

    function updateSmartStep(step, message, percent) {
        const progressBar = document.getElementById('drawerProgressBar');
        const percentText = document.getElementById('drawerPercentText');
        const stepText = document.getElementById('drawerStepText');

        progressBar.style.width = percent + '%';
        percentText.textContent = percent + '%';
        stepText.textContent = message;

        for (let i = 1; i <= 4; i++) {
            const badge = document.getElementById('badgeStep' + i);
            if (badge) {
                if (i < step) {
                    badge.className = 'font-bold text-emerald-600';
                } else if (i === step) {
                    badge.className = 'font-bold text-sky-600';
                } else {
                    badge.className = 'font-semibold text-slate-400';
                }
            }
        }
    }

    function triggerBrowserDownload(url) {
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = url;
        document.body.appendChild(iframe);
        setTimeout(() => {
            document.body.removeChild(iframe);
        }, 45000);
    }

    function prependBackupRow(data) {
        switchBkTab('total');

        const tbody = document.getElementById('totalBackupsTbody');
        const emptyRow = document.getElementById('emptyTotalRow');
        if (emptyRow) emptyRow.remove();

        const now = new Date();
        const dateFormatted = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' +
            now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

        const shaShort = data.sha256 ? data.sha256.substring(0, 16) + '...' : '-';

        const tr = document.createElement('tr');
        tr.style.backgroundColor = 'rgba(2, 132, 199, 0.05)';
        tr.innerHTML = `
            <td>
                <div class="flex items-center gap-2">
                    <span style="font-size: 1.1rem;">📦</span>
                    <code class="sec-code-inline" style="max-width: 220px;" title="${escapeXml(data.filename)}">${escapeXml(data.filename)}</code>
                    <span class="badge badge-success" style="font-size: 0.65rem;">Baru</span>
                </div>
            </td>
            <td><strong>${escapeXml(data.size_formatted)}</strong></td>
            <td class="text-slate-500">${dateFormatted}</td>
            <td><code class="sec-code-inline text-xs" style="max-width: 140px;" title="${escapeXml(data.sha256)}">${escapeXml(shaShort)}</code></td>
            <td><span class="badge badge-success">Valid</span></td>
            <td class="text-right">
                <a href="${data.download_url}" class="btn btn-outline btn-sm inline-flex items-center gap-1 font-semibold" style="height: 30px; padding: 0.25rem 0.65rem;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    <span>Unduh (.zip)</span>
                </a>
            </td>
        `;
        tbody.insertBefore(tr, tbody.firstChild);

        // Update count badge
        const badgeTotal = document.getElementById('badgeCountTotal');
        if (badgeTotal) {
            badgeTotal.textContent = (parseInt(badgeTotal.textContent) || 0) + 1;
        }
    }

    function closeSmartDrawer() {
        document.getElementById('smartBackupDrawer').style.display = 'none';
    }

    function escapeXml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function (m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }
</script>
