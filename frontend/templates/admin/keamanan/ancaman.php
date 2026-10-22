<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Deteksi Ancaman & Keamanan</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Pemantauan anomali lalu lintas, percobaan serangan brute-force, dan integritas sistem.
        </p>
    </div>
</div>

<!-- Tab Navigasi Mode Audit -->
<div class="flex items-center gap-2 mb-6 border-b border-slate-200 overflow-x-auto pb-1 text-sm font-semibold">
    <a href="/admin/keamanan/audit-log" class="px-4 py-2.5 text-slate-500 hover:text-primary transition-colors flex items-center gap-2 border-b-2 border-transparent whitespace-nowrap">
        <?= svg_icon('shield', 16) ?>
        <span>Jejak Audit Aktivitas Data</span>
    </a>
    <a href="/admin/keamanan/akses-log" class="px-4 py-2.5 text-slate-500 hover:text-primary transition-colors flex items-center gap-2 border-b-2 border-transparent whitespace-nowrap">
        <?= svg_icon('dashboard', 16) ?>
        <span>Audit Akses & Perangkat Pengunjung</span>
    </a>
    <a href="/admin/keamanan/ancaman" class="px-4 py-2.5 text-primary border-b-2 border-primary transition-colors flex items-center gap-2 whitespace-nowrap">
        <?= svg_icon('alert', 16) ?>
        <span>Deteksi Ancaman & Keamanan</span>
        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-bold">Aktif</span>
    </a>
</div>

<!-- Security Health Status -->
<h3 style="font-size: 1.15rem; margin-bottom: 1rem; color: var(--primary);">Kondisi Kesehatan Keamanan Sistem</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4" style="margin-bottom: 2.5rem;">
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
            <p class="text-muted" style="font-size: 0.8125rem;"><?= e($check['description']) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Threats Table -->
<div class="table-card">
    <div class="table-header">
        <div class="table-title">Daftar Ancaman Terdeteksi</div>
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
                    <th>Status</th>
                    <th>Aksi</th>
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
                            <td style="max-width: 250px; font-size: 0.8125rem;">
                                <code><?= e($t['payload'] ?? '-') ?></code>
                            </td>
                            <td>
                                <?php if ($t['status'] === 'resolved'): ?>
                                    <span class="badge badge-success">Terselesaikan</span>
                                <?php elseif ($t['status'] === 'blocked'): ?>
                                    <span class="badge badge-danger">Diblokir</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Terdeteksi</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($t['status'] !== 'resolved'): ?>
                                    <form action="/admin/keamanan/ancaman/<?= $t['id'] ?>/resolve" method="POST">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline btn-sm">
                                            Tandai Selesai
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 0.75rem;">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 3rem;">
                            Tidak ada ancaman keamanan yang terdeteksi saat ini. Sistem berjalan aman.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <div class="text-muted">Halaman <?= $currentPage ?> dari <?= $totalPages ?> (Total <?= $total ?> catatan)</div>
            <div class="pagination-pages">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/admin/keamanan/ancaman?page=<?= $i ?>" class="page-link <?= ($i === $currentPage) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
