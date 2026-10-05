<?php
/**
 * Reusable Partial: Pagination UI
 * Variabel:
 * - $currentPage: int
 * - $totalPages: int
 * - $total: int
 */
if (!isset($totalPages) || $totalPages <= 1) {
    return;
}

$queryParams = $_GET;
function getPageUrl(int $p, array $params): string {
    $params['page'] = $p;
    return '?' . http_build_query($params);
}
?>
<div class="pagination-wrapper flex items-center justify-between">
    <div class="pagination-info">
        Halaman <strong><?= (int)$currentPage ?></strong> dari <strong><?= (int)$totalPages ?></strong>
        <?php if (isset($total)): ?>
            (Total <?= (int)$total ?> data)
        <?php endif; ?>
    </div>

    <ul class="pagination-nav flex items-center gap-1">
        <?php if ($currentPage > 1): ?>
            <li>
                <a href="<?= getPageUrl($currentPage - 1, $queryParams) ?>" class="page-link" aria-label="Sebelumnya">
                    <?= svg_icon('chevron-left', 16) ?>
                </a>
            </li>
        <?php endif; ?>

        <?php
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);

        if ($start > 1) {
            echo '<li><a href="' . getPageUrl(1, $queryParams) . '" class="page-link">1</a></li>';
            if ($start > 2) {
                echo '<li class="page-ellipsis">...</li>';
            }
        }

        for ($p = $start; $p <= $end; $p++):
        ?>
            <li>
                <a href="<?= getPageUrl($p, $queryParams) ?>" class="page-link <?= ($p === (int)$currentPage) ? 'active' : '' ?>">
                    <?= $p ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                echo '<li class="page-ellipsis">...</li>';
            }
            echo '<li><a href="' . getPageUrl($totalPages, $queryParams) . '" class="page-link">' . $totalPages . '</a></li>';
        }
        ?>

        <?php if ($currentPage < $totalPages): ?>
            <li>
                <a href="<?= getPageUrl($currentPage + 1, $queryParams) ?>" class="page-link" aria-label="Selanjutnya">
                    <?= svg_icon('chevron-right', 16) ?>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>
