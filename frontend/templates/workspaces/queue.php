<header style="margin-bottom:1.25rem;">
    <p class="text-muted" style="margin:0;"><a href="/bencab/laporan">Ruang kerja</a> / Antrean ekspor</p>
    <h1 style="font-size:1.75rem;font-weight:800;color:var(--primary);margin:.25rem 0;">Antrean Ekspor Laporan</h1>
    <p class="text-muted">Pekerjaan ekspor diproses terpisah dari permintaan halaman supaya tidak menahan pengguna lain.</p>
</header>
<section class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr><th>Nomor</th><th>Status</th><th>Dibuat</th><th>Selesai</th><th>Hasil</th></tr></thead>
            <tbody>
            <?php foreach ($jobs as $job): ?>
                <?php
                $statusLabels = [
                    'queued' => 'Menunggu antrean',
                    'processing' => 'Sedang diproses',
                    'completed' => 'Selesai',
                    'failed' => 'Gagal',
                ];
                ?>
                <tr>
                    <td>#<?= (int)$job['id'] ?></td>
                    <td>
                        <span class="badge <?= $job['status'] === 'completed' ? 'badge-success' : ($job['status'] === 'failed' ? 'badge-danger' : 'badge-neutral') ?>">
                            <?= e($statusLabels[$job['status']] ?? $job['status']) ?>
                        </span>
                        <?php if ($job['error_message']): ?><div class="text-muted"><?= e($job['error_message']) ?></div><?php endif; ?>
                    </td>
                    <td><?= e($job['created_at']) ?></td>
                    <td><?= e($job['finished_at'] ?? '-') ?></td>
                    <td>
                        <?php if ($job['status'] === 'completed'): ?>
                            <a class="btn btn-outline btn-sm" href="/ruang-kerja/antrian/<?= (int)$job['id'] ?>/unduh">Unduh CSV</a>
                        <?php else: ?>-<?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$jobs): ?><tr><td colspan="5" class="text-muted">Belum ada pekerjaan ekspor. Buat ekspor melalui menu Laporan Keuangan.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <p class="text-muted" style="margin:0;">Jika status tetap menunggu, jalankan <code>tools\queue_worker.php</code> di terminal terpisah.</p>
    </div>
</section>
