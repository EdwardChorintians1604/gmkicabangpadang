<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 m-0">Struktur Fungsionaris BPC</h1>
            <span class="badge badge-warning text-xs">BACA-SAJA</span>
        </div>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Daftar fungsionaris Badan Pengurus Cabang (BPC) GMKI Cabang Padang yang berwenang memimpin roda organisasi.
        </p>
    </div>
    <div class="flex gap-2 flex-shrink-0">
        <a href="/struktur-organisasi" target="_blank" class="btn btn-outline btn-sm flex items-center justify-center gap-1 w-full sm:w-auto">
            <?= svg_icon('external', 14) ?>
            <span>Lihat Tampilan Publik</span>
        </a>
        <button onclick="window.print()" class="btn btn-secondary btn-sm flex items-center justify-center gap-1 w-full sm:w-auto">
            <?= svg_icon('printer', 14) ?>
            <span>Cetak Bagan</span>
        </button>
    </div>
</div>

<!-- Daftar Seluruh Fungsionaris Sesuai Urutan Tampil -->
<?php if (!empty($structure)): ?>
    <div class="card mb-6">
        <div class="card-header flex justify-between items-center bg-slate-50 p-4 border-b border-slate-100">
            <div>
                <h3 class="card-title text-base font-bold text-slate-800 m-0">
                    Fungsionaris GMKI Cabang Padang
                </h3>
                <span class="text-slate-400 text-xs">Disusun berdasarkan nomor urut resmi jabatan</span>
            </div>
            <span class="badge badge-info"><?= count($structure) ?> Pengurus</span>
        </div>
        <div class="card-body p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($structure as $m): ?>
                    <div class="card p-4 border border-slate-200/80 rounded-xl flex items-center gap-3.5 hover:shadow-sm transition-shadow">
                        <div style="width: 56px; height: 64px; border-radius: var(--radius-sm); overflow: hidden; background: var(--bg-subtle); flex-shrink: 0; border: 1px solid var(--border-color);">
                            <?php if (!empty($m['foto'])): ?>
                                <img src="/uploads/organisasi/<?= e($m['foto']) ?>" alt="<?= e($m['nama']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <img src="<?= asset('images/default-profile.png') ?>" alt="Default" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="text-xs font-bold text-slate-400">#<?= (int)$m['urutan'] ?></span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 m-0 truncate">
                                <?= e($m['nama']) ?>
                            </h4>
                            <div class="text-xs font-bold text-primary mb-1">
                                <?= e($m['jabatan']) ?>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400 text-xs">Periode <?= e($m['periode'] ?? '-') ?></span>
                                <?php if (($m['status_aktif'] ?? 1) == 1): ?>
                                    <span class="badge badge-success text-[10px] py-0 px-1.5">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral text-[10px] py-0 px-1.5">Demisioner</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (empty($structure)): ?>
    <div class="card" style="text-align: center; padding: 3rem 1rem;">
        <p class="text-muted" style="margin: 0;">Belum ada data struktur pengurus BPC yang terdaftar.</p>
    </div>
<?php endif; ?>
