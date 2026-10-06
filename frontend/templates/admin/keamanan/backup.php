<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 m-0">Pencadangan Data & Berkas</h1>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Cadangan database otomatis (.sql.gz) streaming native PDO, isolasi konsisten ACID, dan verifikasi checksum SHA-256.
        </p>
    </div>
    <div class="flex flex-wrap gap-2 flex-shrink-0">
        <?php if (can('backup.create')): ?>
            <a href="/admin/keamanan/backup/unduh-sekarang" class="btn btn-secondary btn-sm flex items-center justify-center gap-1 shadow-sm w-full sm:w-auto" title="Buat cadangan database terbaru lalu unduh langsung ke perangkat">
                <?= svg_icon('download', 15) ?>
                <span>⚡ Buat & Unduh Langsung (.sql.gz)</span>
            </a>
            <form action="/admin/keamanan/backup/database" method="POST" style="display:inline; margin: 0;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary btn-sm flex items-center justify-center gap-1 w-full sm:w-auto">
                    <span>💾 Buat Arsip Baru</span>
                </button>
            </form>
            <form action="/admin/keamanan/backup/manifest" method="POST" style="display:inline; margin: 0;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline btn-sm flex items-center justify-center gap-1 w-full sm:w-auto">
                    <span>📜 Perbarui Manifest SHA-256</span>
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<!-- Database Backups Card -->
<div class="table-card" style="margin-bottom: 2.5rem;">
    <div class="table-header">
        <div>
            <div class="table-title">Cadangan Database MySQL (Dump .sql.gz Terkompresi)</div>
            <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                Sistem mempertahankan 5 arsip cadangan terkompresi mutakhir di folder terproteksi <code>storage/backup/database/</code> dengan validasi integritas hash SHA-256.
            </div>
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
                                <code><?= e($b['filename']) ?></code>
                            </td>
                            <td><strong><?= e($b['size_formatted']) ?></strong></td>
                            <td class="text-muted"><?= format_date($b['created_at'], 'd M Y H:i:s') ?></td>
                            <td>
                                <code class="text-xs" title="<?= e($b['sha256']) ?>"><?= e($b['sha256_short'] ?? substr($b['sha256'], 0, 16) . '...') ?></code>
                            </td>
                            <td><span class="badge badge-success">GZIP Valid</span></td>
                            <td class="text-right">
                                <a href="/admin/keamanan/backup/download?file=<?= urlencode($b['filename']) ?>" class="btn btn-outline btn-sm flex items-center gap-1" style="display: inline-flex;" title="Unduh berkas cadangan ini ke komputer lokal">
                                    <?= svg_icon('download', 14) ?>
                                    <span>Unduh .sql.gz</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 2.5rem;">
                            Belum ada berkas cadangan database. Klik tombol "Buat Arsip Baru" atau "Buat & Unduh Langsung" di atas untuk membuat cadangan pertama Anda.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Uploads Manifest Card -->
<div class="table-card">
    <div class="table-header">
        <div>
            <div class="table-title">Integritas Berkas Unggahan (manifest.csv SHA-256)</div>
            <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                Daftar berkas publik dan privat beserta ringkasan hash SHA-256 untuk mendeteksi manipulasi berkas fisik di folder <code>storage/backup/uploads/manifest.csv</code>.
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
                    <?php foreach (array_slice($manifestEntries, 0, 30) as $entry): ?>
                        <tr>
                            <td><code><?= e($entry['filename']) ?></code></td>
                            <td><?= round((int)$entry['filesize'] / 1024, 1) ?> KB</td>
                            <td style="font-family: monospace; font-size: 0.75rem;"><?= e($entry['sha256']) ?></td>
                            <td class="text-muted"><?= format_date($entry['created_at'], 'd M Y H:i:s') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted" style="padding: 2.5rem;">
                            Manifest berkas belum dibuat. Klik tombol "Perbarui Manifest SHA-256" di atas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
