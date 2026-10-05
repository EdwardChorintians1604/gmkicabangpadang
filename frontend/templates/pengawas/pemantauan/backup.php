<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div class="flex items-center gap-2" style="margin-bottom: 0.35rem;">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin: 0;">Pemantauan Integritas Cadangan Data</h1>
            <span class="badge badge-warning">BACA-SAJA</span>
        </div>
        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
            Pemeriksaan integritas arsip database terkompresi (.sql.gz) dan manifest verifikasi hash SHA-256 berkas unggahan cabang.
        </p>
    </div>
    <div class="flex gap-2">
        <span class="badge badge-success" style="padding: 8px 12px; font-size: 0.85rem;">
            Retensi Backup: <strong>3 Cadangan Terbaru</strong>
        </span>
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
                    <th>Nama Berkas Cadangan</th>
                    <th>Ukuran Terkompresi</th>
                    <th>Waktu Pembuatan (WIB)</th>
                    <th>Status Integritas</th>
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
                            <td><span class="badge badge-success">GZIP Archive Valid</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted" style="padding: 3rem;">
                            Belum ada berkas cadangan database yang tercatat di folder storage.
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
            <div class="table-title">Manifest Verifikasi Integritas Berkas (SHA-256)</div>
            <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                Arsip berkas foto, lampiran, dan warta di <code>storage/backup/uploads/manifest.csv</code> dengan hash kriptografis untuk mencegah pemalsuan file.
            </div>
        </div>
        <div>
            <span class="badge badge-info"><?= count($manifestEntries ?? []) ?> Berkas Terverifikasi</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Path Relatif Berkas</th>
                    <th>Ukuran</th>
                    <th>Checksum SHA-256</th>
                    <th>Status Integritas</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($manifestEntries)): ?>
                    <?php foreach ($manifestEntries as $entry): ?>
                        <tr>
                            <td><strong><?= e($entry['path']) ?></strong></td>
                            <td><?= e($entry['size_formatted'] ?? '-') ?></td>
                            <td><code style="font-size: 0.75rem;"><?= e($entry['checksum'] ?? '-') ?></code></td>
                            <td><span class="badge badge-success">Terverifikasi</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted" style="padding: 3rem;">
                            Belum ada entri berkas pada manifest saat ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
