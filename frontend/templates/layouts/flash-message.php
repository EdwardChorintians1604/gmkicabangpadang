<?php if (\App\Core\Session::hasFlash('success')): ?>
    <div class="alert alert-success animate__animated animate__fadeInDown">
        <div class="flex items-center gap-2">
            <span>✓</span>
            <div><?= e(\App\Core\Session::getFlash('success')) ?></div>
        </div>
        <button type="button" class="alert-close" aria-label="Tutup">&times;</button>
    </div>
<?php endif; ?>

<?php if (\App\Core\Session::hasFlash('error')): ?>
    <div class="alert alert-danger animate__animated animate__fadeInDown">
        <div class="flex items-center gap-2">
            <span>✕</span>
            <div><?= e(\App\Core\Session::getFlash('error')) ?></div>
        </div>
        <button type="button" class="alert-close" aria-label="Tutup">&times;</button>
    </div>
<?php endif; ?>

<?php if (\App\Core\Session::hasFlash('warning')): ?>
    <div class="alert alert-warning animate__animated animate__fadeInDown">
        <div class="flex items-center gap-2">
            <span>⚠</span>
            <div><?= e(\App\Core\Session::getFlash('warning')) ?></div>
        </div>
        <button type="button" class="alert-close" aria-label="Tutup">&times;</button>
    </div>
<?php endif; ?>

<?php if (\App\Core\Session::hasFlash('info')): ?>
    <div class="alert alert-info animate__animated animate__fadeInDown">
        <div class="flex items-center gap-2">
            <span>ℹ</span>
            <div><?= e(\App\Core\Session::getFlash('info')) ?></div>
        </div>
        <button type="button" class="alert-close" aria-label="Tutup">&times;</button>
    </div>
<?php endif; ?>
