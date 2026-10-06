<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 m-0">Pemantauan Integritas Cadangan Data</h1>
            <span class="badge badge-warning text-xs">BACA-SAJA</span>
        </div>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Pemeriksaan integritas arsip database terkompresi (.sql.gz) dan manifest verifikasi hash SHA-256 berkas unggahan cabang.
        </p>
    </div>
    <div class="flex gap-2 flex-shrink-0">
        <span class="badge badge-success p-2 text-xs sm:text-sm">
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
                                <a href="/pengawas/pemantauan/backup/download?file=<?= urlencode($b['filename']) ?>" class="btn btn-outline btn-sm flex items-center gap-1" style="display: inline-flex;" title="Unduh arsip cadangan basis data">
                                    <?= svg_icon('download', 14) ?>
                                    <span>Unduh .sql.gz</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 3rem;">
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
