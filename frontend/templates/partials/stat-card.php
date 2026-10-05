<?php
/**
 * Reusable Partial: Stat Card Widget
 * Variabel:
 * - $title: string
 * - $value: string|int
 * - $icon: string (nama ikon SVG)
 * - $subtitle: string (opsional)
 * - $color: string (primary, secondary, success, warning, danger, info)
 * - $trend: string (opsional)
 */
$color = $color ?? 'primary';
$iconName = $icon ?? 'bar-chart';
?>
<div class="stat-card stat-card-<?= e($color) ?>">
    <div class="stat-card-header">
        <span class="stat-card-title"><?= e($title ?? 'Statistik') ?></span>
        <div class="stat-card-icon icon-<?= e($color) ?>">
            <?= svg_icon($iconName, 22) ?>
        </div>
    </div>
    <div class="stat-card-value"><?= e((string)($value ?? '0')) ?></div>
    <?php if (!empty($subtitle) || !empty($trend)): ?>
        <div class="stat-card-footer">
            <?php if (!empty($trend)): ?>
                <span class="stat-card-trend"><?= e($trend) ?></span>
            <?php endif; ?>
            <?php if (!empty($subtitle)): ?>
                <span class="stat-card-sub"><?= e($subtitle) ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
