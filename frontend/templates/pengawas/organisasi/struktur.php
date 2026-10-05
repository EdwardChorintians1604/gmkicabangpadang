<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div class="flex items-center gap-2" style="margin-bottom: 0.35rem;">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin: 0;">Struktur Fungsionaris BPC</h1>
            <span class="badge badge-warning">BACA-SAJA</span>
        </div>
        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
            Daftar fungsionaris Badan Pengurus Cabang (BPC) GMKI Cabang Padang yang berwenang memimpin roda organisasi.
        </p>
    </div>
    <div class="flex gap-2">
        <a href="/struktur-organisasi" target="_blank" class="btn btn-outline btn-sm flex items-center gap-1">
            <?= svg_icon('external', 14) ?>
            <span>Lihat Tampilan Publik</span>
        </a>
        <button onclick="window.print()" class="btn btn-secondary btn-sm flex items-center gap-1">
            <?= svg_icon('printer', 14) ?>
            <span>Cetak Bagan</span>
        </button>
    </div>
</div>

<!-- Group Officers by Department -->
<?php
$departments = [
    'Badan Pengurus Harian' => [],
    'Bidang Organisasi' => [],
    'Bidang Kaderisasi' => [],
    'Bidang Akpel' => [],
    'Bidang Media & Komunikasi' => [],
    'Biro Khusus' => [],
    'Lainnya' => [],
];

if (!empty($structure)) {
    foreach ($structure as $item) {
        $found = false;
        foreach (array_keys($departments) as $deptKey) {
            if (stripos($item['bidang'] ?? '', $deptKey) !== false) {
                $departments[$deptKey][] = $item;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $departments['Lainnya'][] = $item;
        }
    }
}
?>

<?php foreach ($departments as $deptName => $members): ?>
    <?php if (!empty($members)): ?>
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-header flex justify-between items-center" style="background: var(--bg-subtle);">
                <h3 class="card-title" style="font-size: 1.1rem; color: var(--primary); margin: 0;">
                    <?= e($deptName) ?>
                </h3>
                <span class="badge badge-info"><?= count($members) ?> Fungsionaris</span>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-3 gap-4">
                    <?php foreach ($members as $m): ?>
                        <div class="card" style="border: 1px solid var(--border-color); padding: 1.25rem; display: flex; gap: 1rem; align-items: center;">
                            <div style="width: 60px; height: 70px; border-radius: var(--radius-sm); overflow: hidden; background: var(--bg-subtle); flex-shrink: 0;">
                                <?php if (!empty($m['foto'])): ?>
                                    <img src="/uploads/organisasi/<?= e($m['foto']) ?>" alt="<?= e($m['nama']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <img src="<?= asset('images/default-profile.png') ?>" alt="Default" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 700; margin: 0 0 0.25rem; color: var(--text-main);">
                                    <?= e($m['nama']) ?>
                                </h4>
                                <div style="font-size: 0.825rem; font-weight: 600; color: var(--primary); margin-bottom: 0.25rem;">
                                    <?= e($m['jabatan']) ?>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-muted" style="font-size: 0.75rem;">Periode <?= e($m['periode'] ?? '-') ?></span>
                                    <?php if (($m['status_aktif'] ?? 1) == 1): ?>
                                        <span class="badge badge-success" style="font-size: 0.65rem;">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-neutral" style="font-size: 0.65rem;">Demisioner</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php if (empty($structure)): ?>
    <div class="card" style="text-align: center; padding: 3rem 1rem;">
        <p class="text-muted" style="margin: 0;">Belum ada data struktur pengurus BPC yang terdaftar.</p>
    </div>
<?php endif; ?>
