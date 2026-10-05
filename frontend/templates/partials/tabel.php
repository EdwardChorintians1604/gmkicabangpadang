<?php
/**
 * Reusable Partial: Standardized Responsive Table Container
 * Variabel:
 * - $tableContent: string (HTML table content)
 * - $emptyMessage: string (opsional jika data kosong)
 * - $isEmpty: bool (opsional)
 */
?>
<div class="table-responsive-wrapper card">
    <?php if (!empty($isEmpty)): ?>
        <div class="table-empty-state">
            <div class="empty-icon"><?= svg_icon('search', 40) ?></div>
            <p><?= e($emptyMessage ?? 'Tidak ada data yang ditemukan.') ?></p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <?= $tableContent ?? '' ?>
        </div>
    <?php endif; ?>
</div>
