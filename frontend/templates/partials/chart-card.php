<?php
/**
 * Reusable Partial: Chart Card Container
 * Variabel:
 * - $id: string (ID elemen canvas HTML)
 * - $title: string (Judul grafik)
 * - $subtitle: string (Keterangan grafik opsional)
 * - $badge: string (Label status / kategori opsional)
 * - $height: int (Tinggi canvas dalam px, default 280)
 */
$chartId = $id ?? 'chartCanvas';
$chartHeight = $height ?? 280;
?>
<div class="card chart-card">
    <div class="chart-card-header flex items-center justify-between">
        <div>
            <h3 class="chart-card-title"><?= e($title ?? 'Grafik Statistik') ?></h3>
            <?php if (!empty($subtitle)): ?>
                <p class="chart-card-subtitle"><?= e($subtitle) ?></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($badge)): ?>
            <span class="badge badge-primary"><?= e($badge) ?></span>
        <?php endif; ?>
    </div>
    <div class="chart-card-body" style="position: relative; height: <?= (int)$chartHeight ?>px; width: 100%;">
        <canvas id="<?= e($chartId) ?>"></canvas>
    </div>
</div>
