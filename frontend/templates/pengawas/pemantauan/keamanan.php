<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 m-0">Pemantauan Keamanan Sistem & Integritas Data</h1>
            <span class="badge badge-warning text-xs">BACA-SAJA</span>
        </div>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Pengawasan parameter keamanan siber, status enkripsi, proteksi CSRF, serta deteksi serangan brute-force dan SQL injection.
        </p>
    </div>
    <div class="flex gap-2 flex-shrink-0">
        <span class="badge badge-success p-2 text-xs sm:text-sm">
            Perlindungan WAF: <strong>AKTIF</strong>
        </span>
    </div>
</div>

<!-- Security Health Status -->
<h3 style="font-size: 1.15rem; margin-bottom: 1rem; color: var(--primary);">Kondisi Kesehatan Keamanan Sistem</h3>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" style="margin-bottom: 2.5rem;">
    <?php foreach ($healthChecks as $check): ?>
        <div class="card" style="padding: 1.25rem;">
            <div class="flex items-center justify-between" style="margin-bottom: 0.5rem;">
                <strong style="font-size: 0.95rem;"><?= e($check['title']) ?></strong>
                <?php if ($check['status'] === 'ok'): ?>
                    <span class="badge badge-success">Aman</span>
                <?php elseif ($check['status'] === 'warning'): ?>
                    <span class="badge badge-warning">Perhatian</span>
                <?php else: ?>
                    <span class="badge badge-primary">Info</span>
                <?php endif; ?>
            </div>
            <p class="text-muted" style="font-size: 0.8125rem; margin: 0;"><?= e($check['description']) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Threats Table -->
<div class="table-card">
    <div class="table-header">
        <div class="table-title">Daftar Ancaman Terdeteksi & Log Mitigasi</div>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu Kejadian</th>
                    <th>Jenis Ancaman</th>
                    <th>Tingkat Keparahan</th>
                    <th>IP Penyerang</th>
                    <th>Payload / Percobaan</th>
                    <th>Status Mitigasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($threats)): ?>
                    <?php foreach ($threats as $t): ?>
                        <tr>
                            <td class="text-muted"><?= format_date($t['created_at'], 'd M Y H:i:s') ?></td>
                            <td><strong><?= e($t['threat_type']) ?></strong></td>
                            <td>
                                <?php if ($t['severity'] === 'critical' || $t['severity'] === 'high'): ?>
                                    <span class="badge badge-danger"><?= strtoupper(e($t['severity'])) ?></span>
                                <?php elseif ($t['severity'] === 'medium'): ?>
                                    <span class="badge badge-warning"><?= strtoupper(e($t['severity'])) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-neutral"><?= strtoupper(e($t['severity'])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><code><?= e($t['ip_address']) ?></code></td>
                            <td style="max-width: 280px; font-size: 0.8125rem;">
                                <code><?= e($t['payload'] ?? '-') ?></code>
                            </td>
                            <td>
                                <?php if ($t['status'] === 'resolved'): ?>
                                    <span class="badge badge-success">Terselesaikan</span>
                                <?php elseif ($t['status'] === 'blocked'): ?>
                                    <span class="badge badge-danger">Diblokir Otomatis</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Terdeteksi</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 3rem;">
                            Tidak ada ancaman keamanan yang terdeteksi saat ini. Sistem dalam kondisi aman.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <?= partial('pagination', [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'baseUrl' => '/pengawas/pemantauan/keamanan'
        ]) ?>
    <?php endif; ?>
</div>
