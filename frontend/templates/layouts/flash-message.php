<?php if (\App\Core\Session::hasFlash('success')): ?>
    <div class="alert alert-success">
        <div class="flex items-center gap-2">
            <span>✓</span>
            <div><?= e(\App\Core\Session::getFlash('success')) ?></div>
        </div>
        <button type="button" class="alert-close" aria-label="Tutup">&times;</button>
    </div>
<?php endif; ?>

<?php if (\App\Core\Session::hasFlash('error')): ?>
    <div class="alert alert-danger">
        <div class="flex items-center gap-2">
            <span>✕</span>
            <div><?= e(\App\Core\Session::getFlash('error')) ?></div>
        </div>
        <button type="button" class="alert-close" aria-label="Tutup">&times;</button>
    </div>
<?php endif; ?>

<?php if (\App\Core\Session::hasFlash('warning')): ?>
    <div class="alert alert-warning">
        <div class="flex items-center gap-2">
            <span>⚠</span>
            <div><?= e(\App\Core\Session::getFlash('warning')) ?></div>
        </div>
        <button type="button" class="alert-close" aria-label="Tutup">&times;</button>
    </div>
<?php endif; ?>

<?php if (\App\Core\Session::hasFlash('info')): ?>
    <div class="alert alert-info">
        <div class="flex items-center gap-2">
            <span>ℹ</span>
            <div><?= e(\App\Core\Session::getFlash('info')) ?></div>
        </div>
        <button type="button" class="alert-close" aria-label="Tutup">&times;</button>
    </div>
<?php endif; ?>
