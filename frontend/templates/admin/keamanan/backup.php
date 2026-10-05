<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Pencadangan Data & Berkas</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Cadangan database otomatis (.sql.gz) dan verifikasi integritas berkas upload dengan checksum SHA-256.
        </p>
    </div>
    <div class="flex gap-2">
        <?php if (can('backup.create')): ?>
            <form action="/admin/keamanan/backup/database" method="POST" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary btn-sm">
                    💾 Buat Backup Database Baru (.sql.gz)
                </button>
            </form>
            <form action="/admin/keamanan/backup/manifest" method="POST" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-secondary btn-sm">
                    📜 Perbarui Manifest SHA-256
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<!-- Database Backups Card -->
<div class="table-card" style="margin-bottom: 2.5rem;">
    <div class="table-header">
        <div>
            <div class="table-title">Cadangan Database MySQL (Dump .sql.gz)</div>
            <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                Sistem secara otomatis mempertahankan 3 berkas cadangan terkompresi paling mutakhir di folder <code>storage/backup/database/</code>.
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
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dbBackups)): ?>
                    <?php foreach ($dbBackups as $b): ?>
                        <tr>
                            <td>
                                <code><?= e($b['filename']) ?></code>
                            </td>
                            <td><?= e($b['size_formatted']) ?></td>
                            <td class="text-muted"><?= format_date($b['created_at'], 'd M Y H:i:s') ?></td>
                            <td><span class="badge badge-success">GZIP Valid</span></td>
                            <td>
                                <a href="/admin/keamanan/backup/database/<?= urlencode($b['filename']) ?>" class="btn btn-outline btn-sm">
                                    ⬇️ Unduh Berkas .sql.gz
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding: 2.5rem;">
                            Belum ada berkas cadangan database. Klik tombol "Buat Backup Database Baru" di atas untuk membuat cadangan pertama Anda.
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
