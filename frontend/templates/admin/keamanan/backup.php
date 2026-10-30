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
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-black m-0 mb-1 leading-tight"
            style="color: #0f3d64 !important; letter-spacing: -0.02em;">
            Pencadangan Data &amp; Integritas Berkas
        </h1>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Lakukan pencadangan basis data MySQL secara menyeluruh untuk melindungi data dari ancaman SQl-Injection
        </p>
    </div>
    <div class="flex flex-wrap gap-2 flex-shrink-0">
        <?php if (can('backup.create')): ?>
            <form action="/admin/keamanan/backup/manifest" method="POST" style="display:inline; margin: 0;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline btn-sm flex items-center justify-center gap-1.5 shadow-sm"
                    title="Perbarui manifest verifikasi hash SHA-256 berkas fisik">
                    <span>📜 Perbarui Manifest SHA-256</span>
                </button>
            </form>
            <a href="/admin/berkas/backup?type=all"
                class="btn btn-outline btn-sm flex items-center justify-center gap-1.5 shadow-sm"
                title="Cadangkan dokumen dan persuratan ruang kerja">
                <span>📂 Unduh Berkas Persuratan (ZIP)</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- ================================================================================== -->
<!-- KPI METRIC HIGHLIGHTS (3 CARDS) -->
<!-- ================================================================================== -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <!-- Card 1: Cadangan Total Terakhir -->
    <div class="card p-4 flex items-center gap-4 border border-slate-200/80 shadow-sm"
        style="background: #ffffff; border-radius: 14px;">
        <div
            style="width: 48px; height: 48px; border-radius: 12px; background: rgba(2, 132, 199, 0.1); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
            📦
        </div>
        <div class="overflow-hidden">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Cadangan Total (.zip)</div>
            <div class="text-lg font-black text-slate-800" style="color: #0f3d64;">
                <?= $latestTotal ? e($latestTotal['size_formatted']) : 'Belum Ada' ?>
            </div>
            <div class="text-xs text-slate-500 truncate" title="<?= $latestTotal ? e($latestTotal['filename']) : '' ?>">
                <?= $latestTotal ? format_date($latestTotal['created_at'], 'd M Y H:i') : 'Klik tombol di bawah untuk membuat' ?>
            </div>
        </div>
    </div>

    <!-- Card 2: Database Snapshot Terakhir -->
    <div class="card p-4 flex items-center gap-4 border border-slate-200/80 shadow-sm"
        style="background: #ffffff; border-radius: 14px;">
        <div
            style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
            💾
        </div>
        <div class="overflow-hidden">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Snapshot Database (.sql.gz)
            </div>
            <div class="text-lg font-black text-slate-800" style="color: #0f3d64;">
                <?= $latestDb ? e($latestDb['size_formatted']) : 'Belum Ada' ?>
            </div>
            <div class="text-xs text-slate-500 truncate" title="<?= $latestDb ? e($latestDb['filename']) : '' ?>">
                <?= $latestDb ? format_date($latestDb['created_at'], 'd M Y H:i') : 'Dump PDO konsisten ACID' ?>
            </div>
        </div>
    </div>

    <!-- Card 3: Integritas Berkas Fisik -->
    <div class="card p-4 flex items-center gap-4 border border-slate-200/80 shadow-sm"
        style="background: #ffffff; border-radius: 14px;">
        <div
            style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
            🛡️
        </div>
        <div class="overflow-hidden">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Verifikasi Integritas SHA-256
            </div>
            <div class="text-lg font-black text-slate-800" style="color: #0f3d64;">
                <?= number_format($manifestCount) ?> Berkas
            </div>
            <div class="text-xs text-emerald-600 font-semibold">
                ✓ Checksum manifest terproteksi
            </div>
        </div>
    </div>
</div>

<!-- ================================================================================== -->
<!-- PUSAT PENCADANGAN KILAT & OTOMASI UNDUHAN (CLEAN & LUXURY DESIGN) -->
<!-- ================================================================================== -->
<div class="card shadow-md border border-slate-200 mb-8"
    style="background: #ffffff; border-radius: 16px; overflow: hidden;">
    <!-- Card Header Banner -->
    <div style="padding: 1.25rem 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0;"
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold mb-1"
                style="background: rgba(2, 132, 199, 0.12); color: #0284c7; border: 1px solid rgba(2, 132, 199, 0.25);">
                <span>⚡ PUSAT PENCADANGAN CEPAT &amp; OTOMATIS</span>
            </div>
            <h2 class="text-base sm:text-lg font-black m-0" style="color: #0f3d64;">
                Pencadangan Instan &amp; Pengunduhan Otomatis Tanpa Ribet
            </h2>
            <p class="text-slate-500 text-xs sm:text-sm m-0 mt-0.5">
                Pilih paket cadangan di bawah. Anda dapat mengunduh langsung dalam 1 klik atau mengatur hitung mundur
                otomatis beberapa detik.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span
                class="text-xs text-slate-500 font-semibold bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                🔒 Enkapsulasi ZIP + SHA-256
            </span>
        </div>
    </div>

    <!-- Dual Actions Grid (2 Columns) -->
    <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kolom 1: Cadangan Total Sistem (Database + Berkas Fisik) -->
        <div
            style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between; position: relative;">
            <div
                style="position: absolute; top: -10px; right: 16px; background: #0284c7; color: #ffffff; font-size: 0.68rem; font-weight: 800; padding: 0.2rem 0.65rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.35);">
                ⭐ Sangat Direkomendasikan
            </div>

            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div
                        style="width: 40px; height: 40px; border-radius: 10px; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                        📦
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold m-0" style="color: #0f3d64;">
                            Cadangan Total Sistem (.zip)
                        </h3>
                        <div class="text-xs text-slate-500">
                            Dump Database MySQL + Foto Civitas + KTA + Dokumen Kantor + Manifest
                        </div>
                    </div>
                </div>

                <p class="text-xs text-slate-600 mb-4 leading-relaxed"
                    style="background: #ffffff; padding: 0.65rem 0.85rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    Mengemas seluruh aset fisik dan pangkalan data ke dalam satu arsip <code>.zip</code> terenkapsulasi
                    lengkap dengan petunjuk pemulihan bencana (<em>disaster recovery</em>).
                </p>
            </div>

            <div>
                <!-- Tombol 1 Klik Langsung Unduh -->
                <div class="mb-3">
                    <a href="/admin/keamanan/backup/unduh-total-sekarang"
                        class="btn w-full flex items-center justify-center gap-2 shadow-sm font-bold text-sm"
                        style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; border: none; padding: 0.75rem 1rem; border-radius: 10px;">
                        <?= svg_icon('download', 16) ?>
                        <span>⚡ Buat &amp; Unduh Total Langsung Sekarang</span>
                    </a>
                </div>

                <!-- Tombol Otomatis Hitung Mundur -->
                <div class="flex items-center gap-2">
                    <button type="button"
                        class="btn btn-outline btn-sm w-1/2 flex items-center justify-center gap-1 font-bold text-xs"
                        style="color: #0284c7; border-color: #7dd3fc; background: #ffffff;"
                        onclick="startSmartCountdown(5)">
                        <span>⏱️ Otomatis (5 Detik)</span>
                    </button>
                    <button type="button"
                        class="btn btn-outline btn-sm w-1/2 flex items-center justify-center gap-1 font-bold text-xs"
                        style="color: #0369a1; border-color: #cbd5e1; background: #ffffff;"
                        onclick="startSmartCountdown(10)">
                        <span>⏱️ Otomatis (10 Detik)</span>
                    </button>
                    <form action="/admin/keamanan/backup/total" method="POST" style="margin: 0;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline btn-sm flex items-center justify-center"
                            style="background: #ffffff; border-color: #cbd5e1; padding: 0.45rem 0.65rem;"
                            title="Simpan arsip di server tanpa mengunduh">
                            💾
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom 2: Cadangan Database MySQL (.sql.gz) -->
        <div
            style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div
                        style="width: 40px; height: 40px; border-radius: 10px; background: #ecfdf5; color: #047857; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                        💾
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold m-0" style="color: #0f3d64;">
                            Snapshot Database MySQL (.sql.gz)
                        </h3>
                        <div class="text-xs text-slate-500">
                            Dump Transaksional ACID Streaming Native PDO Terkompresi Gzip
                        </div>
                    </div>
                </div>

                <p class="text-xs text-slate-600 mb-4 leading-relaxed"
                    style="background: #ffffff; padding: 0.65rem 0.85rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    Pencadangan ringkas khusus skema tabel dan seluruh data record MySQL terkompresi Gzip Level 9 untuk
                    kemudahan restorasi cepat harian.
                </p>
            </div>

            <div>
                <!-- Tombol 1 Klik Langsung Unduh DB -->
                <div class="mb-3">
                    <a href="/admin/keamanan/backup/unduh-sekarang"
                        class="btn w-full flex items-center justify-center gap-2 shadow-sm font-bold text-sm"
                        style="background: #0f3d64; color: #ffffff; border: none; padding: 0.75rem 1rem; border-radius: 10px;">
                        <?= svg_icon('download', 16) ?>
                        <span>⚡ Buat &amp; Unduh Database (.sql.gz)</span>
                    </a>
                </div>

                <!-- Aksi Tambahan DB -->
                <div class="flex items-center gap-2">
                    <form action="/admin/keamanan/backup/database" method="POST" style="margin: 0; width: 100%;">
                        <?= csrf_field() ?>
                        <button type="submit"
                            class="btn btn-outline btn-sm w-full flex items-center justify-center gap-1.5 font-bold text-xs"
                            style="background: #ffffff; border-color: #cbd5e1; color: #334155;">
                            <span>💾 Buat Arsip Baru di Server Saja</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================================== -->
    <!-- DRAWER INTERAKTIF: HITUNG MUNDUR, LIVE PROGRES & AUTO-DOWNLOAD NOTIFIER -->
    <!-- ============================================================================== -->
    <div id="smartBackupDrawer"
        style="display: none; border-top: 1px solid #e2e8f0; background: #f0fdf4; padding: 1.25rem 1.5rem;">
        <!-- State A: Countdown Box -->
        <div id="drawerCountdownState" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div id="drawerTimerNumber"
                    style="width: 52px; height: 52px; border-radius: 50%; background: #0284c7; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; font-weight: 900; font-family: monospace; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35); flex-shrink: 0;">
                    5
                </div>
                <div>
                    <div class="font-extrabold text-sm sm:text-base text-slate-800" style="color: #0f3d64;">
                        Mempersiapkan Pencadangan Otomatis...
                    </div>
                    <div class="text-xs text-slate-500">
                        Proses pengemasan total dan pengunduhan akan berjalan otomatis saat hitung mundur mencapai 0.
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline font-bold text-xs"
                    style="color: #dc2626; border-color: #fca5a5; background: #ffffff;"
                    onclick="cancelSmartCountdown()">
                    ✕ Batalkan
                </button>
                <button type="button" class="btn btn-sm font-bold text-xs" style="background: #0284c7; color: #ffffff;"
                    onclick="executeSmartBackupNow()">
                    ⚡ Lewati &amp; Proses Sekarang
                </button>
            </div>
        </div>

        <!-- State B: Processing Progress Box -->
        <div id="drawerProcessingState" style="display: none;">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="spinner-inline"
                        style="width: 18px; height: 18px; border: 2.5px solid #cbd5e1; border-top-color: #0284c7; border-radius: 50%; animation: spin 0.8s linear infinite;">
                    </div>
                    <span id="drawerStepText" class="font-extrabold text-xs sm:text-sm text-slate-800"
                        style="color: #0f3d64;">
                        Menghubungkan ke basis data dan membuat snapshot konsisten ACID...
                    </span>
                </div>
                <span id="drawerPercentText" class="text-xs font-black font-mono text-sky-700">25%</span>
            </div>
            <div
                style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 999px; overflow: hidden; margin-bottom: 0.65rem;">
                <div id="drawerProgressBar"
                    style="width: 25%; height: 100%; background: linear-gradient(90deg, #0284c7, #10b981); transition: width 0.3s ease;">
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between text-xs text-slate-500">
                <span id="badgeStep1" class="font-bold text-sky-700">1. Snapshot Database</span>
                <span id="badgeStep2" class="font-semibold text-slate-400">2. Berkas Fisik &amp; KTA</span>
                <span id="badgeStep3" class="font-semibold text-slate-400">3. Checksum SHA-256</span>
                <span id="badgeStep4" class="font-semibold text-slate-400">4. Arsip ZIP &amp; Unduh</span>
            </div>
        </div>

        <!-- State C: Success Banner -->
        <div id="drawerSuccessState" style="display: none;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div style="font-size: 1.75rem; line-height: 1;">🎉</div>
                    <div>
                        <div class="font-black text-sm text-emerald-800 mb-0.5">
                            Pencadangan Total Selesai &amp; Pengunduhan Sedang Berjalan!
                        </div>
                        <div class="text-xs text-slate-600">
                            Berkas: <code id="lblSuccessFilename"
                                class="font-bold text-sky-800 bg-white px-1.5 py-0.5 rounded border border-slate-200">backup_total.zip</code>
                            •
                            Ukuran: <strong id="lblSuccessSize" class="text-slate-800">0 MB</strong> •
                            Total: <strong id="lblSuccessFiles" class="text-slate-800">0 berkas</strong> •
                            SHA-256: <code id="lblSuccessSha" class="text-slate-500 text-xs">-</code>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a id="lnkSuccessDownload" href="#" class="btn btn-sm font-bold text-xs shadow-sm"
                        style="background: #10b981; color: #ffffff; border: none;">
                        ⬇️ Unduh Ulang Berkas (.zip)
                    </a>
                    <button type="button" class="btn btn-sm btn-outline font-bold text-xs"
                        style="background: #ffffff; border-color: #cbd5e1; color: #64748b;"
                        onclick="closeSmartDrawer()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================================================================================== -->
<!-- TABEL 1: DAFTAR ARSIP CADANGAN TOTAL SISTEM (DATABASE + BERKAS FISIK .ZIP) -->
<!-- ================================================================================== -->
<div class="table-card mb-8" style="border-top: 3px solid #0284c7;">
    <div class="table-header flex items-center justify-between" style="gap: 1rem; flex-wrap: wrap;">
        <div>
            <div class="table-title" style="display: flex; align-items: center; gap: 0.5rem; color: #0f3d64;">
                <span>📦 Daftar Cadangan Total Sistem (Database MySQL + Berkas Fisik .zip)</span>
            </div>
            <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                Arsip cadangan terkompresi lengkap di folder aman <code>storage/backup/total/</code> (disimpan maksimal
                5 arsip mutakhir dengan verifikasi kriptografi SHA-256).
            </div>
        </div>
        <div>
            <a href="/admin/keamanan/backup/unduh-total-sekarang"
                class="btn btn-primary btn-sm flex items-center gap-1 shadow-sm font-bold">
                <?= svg_icon('download', 14) ?>
                <span>⚡ Buat Cadangan Total Baru</span>
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Berkas Arsip</th>
                    <th>Ukuran Total</th>
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
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.15rem;">📦</span>
                                    <code><?= e($tb['filename']) ?></code>
                                </div>
                            </td>
                            <td><strong><?= e($tb['size_formatted']) ?></strong></td>
                            <td class="text-muted"><?= format_date($tb['created_at'], 'd M Y H:i:s') ?></td>
                            <td>
                                <code class="text-xs"
                                    title="<?= e($tb['sha256']) ?>"><?= e($tb['sha256_short'] ?? substr($tb['sha256'], 0, 16) . '...') ?></code>
                            </td>
                            <td><span class="badge badge-success">ZIP &amp; SHA-256 Valid</span></td>
                            <td class="text-right">
                                <a href="/admin/keamanan/backup/download-total?file=<?= urlencode($tb['filename']) ?>"
                                    class="btn btn-outline btn-sm flex items-center gap-1 shadow-2xs font-semibold"
                                    style="display: inline-flex;" title="Unduh berkas cadangan total ini ke komputer lokal">
                                    <?= svg_icon('download', 14) ?>
                                    <span>Unduh Paket (.zip)</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="emptyTotalRow">
                        <td colspan="6" class="text-center text-muted" style="padding: 2.5rem;">
                            Belum ada paket cadangan total sistem. Klik tombol <strong>"⚡ Buat &amp; Unduh Total Langsung
                                Sekarang"</strong> di atas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ================================================================================== -->
<!-- TABEL 2: DAFTAR ARSIP BASIS DATA MYSQL (.SQL.GZ) -->
<!-- ================================================================================== -->
<div class="table-card mb-8">
    <div class="table-header flex items-center justify-between" style="gap: 1rem; flex-wrap: wrap;">
        <div>
            <div class="table-title" style="color: #0f3d64;">
                Cadangan Database MySQL (Dump .sql.gz Terkompresi)
            </div>
            <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                Arsip database native PDO di folder terproteksi <code>storage/backup/database/</code> dengan validasi
                integritas hash SHA-256.
            </div>
        </div>
        <div>
            <a href="/admin/keamanan/backup/unduh-sekarang"
                class="btn btn-secondary btn-sm flex items-center gap-1 shadow-sm font-bold">
                <?= svg_icon('download', 14) ?>
                <span>⚡ Unduh Database Langsung</span>
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Berkas</th>
                    <th>Ukuran Terkompresi</th>
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
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.1rem;">💾</span>
                                    <code><?= e($b['filename']) ?></code>
                                </div>
                            </td>
                            <td><strong><?= e($b['size_formatted']) ?></strong></td>
                            <td class="text-muted"><?= format_date($b['created_at'], 'd M Y H:i:s') ?></td>
                            <td>
                                <code class="text-xs"
                                    title="<?= e($b['sha256']) ?>"><?= e($b['sha256_short'] ?? substr($b['sha256'], 0, 16) . '...') ?></code>
                            </td>
                            <td><span class="badge badge-success">GZIP Valid</span></td>
                            <td class="text-right">
                                <a href="/admin/keamanan/backup/download?file=<?= urlencode($b['filename']) ?>"
                                    class="btn btn-outline btn-sm flex items-center gap-1 shadow-2xs font-semibold"
                                    style="display: inline-flex;" title="Unduh berkas cadangan ini ke komputer lokal">
                                    <?= svg_icon('download', 14) ?>
                                    <span>Unduh .sql.gz</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 2.5rem;">
                            Belum ada berkas cadangan database. Klik tombol "Buat &amp; Unduh Database" di atas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ================================================================================== -->
<!-- TABEL 3: INTEGRITAS BERKAS UNGGAHAN (MANIFEST.CSV SHA-256) -->
<!-- ================================================================================== -->
<div class="table-card">
    <div class="table-header">
        <div>
            <div class="table-title" style="color: #0f3d64;">Integritas Berkas Unggahan (manifest.csv SHA-256)</div>
            <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                Daftar berkas publik dan privat beserta ringkasan hash SHA-256 untuk mendeteksi manipulasi berkas fisik
                di folder <code>storage/backup/uploads/manifest.csv</code>.
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="data-table">
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
                    <?php foreach (array_slice($manifestEntries, 0, 25) as $entry): ?>
                        <tr>
                            <td><code><?= e($entry['filename']) ?></code></td>
                            <td><?= round((int) $entry['filesize'] / 1024, 1) ?> KB</td>
                            <td style="font-family: monospace; font-size: 0.75rem;"><?= e($entry['sha256']) ?></td>
                            <td class="text-muted"><?= format_date($entry['created_at'], 'd M Y H:i:s') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted" style="padding: 2.5rem;">
                            Manifest berkas belum dibuat. Klik tombol "Perbarui Manifest SHA-256" di bagian atas halaman.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<!-- ================================================================================== -->
<!-- JAVASCRIPT: LOGIKA HITUNG MUNDUR, PROGRES & AUTO-DOWNLOAD -->
<!-- ================================================================================== -->
<script>
    let smartTimerId = null;
    let remainingCountdown = 5;
    let isSmartRunning = false;

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
                updateSmartStep(4, 'Arsip ZIP selesai dibuat! Memulai unduhan otomatis ke komputer Anda...', 100);

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

                    // Tambahkan baris baru ke tabel
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
                    badge.className = 'font-bold text-emerald-700';
                } else if (i === step) {
                    badge.className = 'font-extrabold text-sky-700';
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
        const tbody = document.getElementById('totalBackupsTbody');
        const emptyRow = document.getElementById('emptyTotalRow');
        if (emptyRow) emptyRow.remove();

        const now = new Date();
        const dateFormatted = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' +
            now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

        const shaShort = data.sha256 ? data.sha256.substring(0, 16) + '...' : '-';

        const tr = document.createElement('tr');
        tr.style.backgroundColor = 'rgba(2, 132, 199, 0.06)';
        tr.innerHTML = `
        <td>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.15rem;">📦</span>
                <code>${escapeXml(data.filename)}</code>
                <span class="badge badge-success" style="font-size: 0.65rem;">Baru</span>
            </div>
        </td>
        <td><strong>${escapeXml(data.size_formatted)}</strong></td>
        <td class="text-muted">${dateFormatted}</td>
        <td><code class="text-xs" title="${escapeXml(data.sha256)}">${escapeXml(shaShort)}</code></td>
        <td><span class="badge badge-success">ZIP &amp; SHA-256 Valid</span></td>
        <td class="text-right">
            <a href="${data.download_url}" class="btn btn-outline btn-sm flex items-center gap-1 shadow-2xs font-semibold" style="display: inline-flex;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                <span>Unduh Paket (.zip)</span>
            </a>
        </td>
    `;
        tbody.insertBefore(tr, tbody.firstChild);
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