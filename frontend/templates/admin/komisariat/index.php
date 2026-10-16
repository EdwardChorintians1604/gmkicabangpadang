<?php
$currentParams = $_GET;
$paramsExcel = array_merge($currentParams, ['format' => 'xlsx']);
$paramsCsv = array_merge($currentParams, ['format' => 'csv']);
$excelUrl = '/admin/komisariat/export?' . http_build_query($paramsExcel);
$csvUrl = '/admin/komisariat/export?' . http_build_query($paramsCsv);
?>
<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Komisariat GMKI Padang</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Daftar komisariat definitif dan pemetaan anggota di masing-masing basis perguruan tinggi.
        </p>
    </div>
    <div class="flex flex-wrap sm:flex-nowrap gap-2 w-full sm:w-auto">
        <a href="<?= $excelUrl ?>" class="btn btn-outline btn-sm flex-1 sm:flex-none justify-center" title="Unduh data komisariat dalam format Excel (.xlsx)">
            📊 Unduh Excel
        </a>
        <a href="<?= $csvUrl ?>" class="btn btn-outline btn-sm flex-1 sm:flex-none justify-center" title="Unduh data komisariat dalam format CSV (.csv)">
            📥 Unduh CSV
        </a>
        <?php if (can('civitas.create')): ?>
            <a href="/admin/komisariat/create" class="btn btn-primary btn-sm flex-1 sm:flex-none justify-center">
                ➕ Tambah Komisariat
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Search / Filter Card -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="/admin/komisariat" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="form-group sm:col-span-2" style="margin-bottom: 0;">
            <input type="text" name="q" class="form-control" placeholder="Cari nama komisariat, kampus perguruan tinggi, atau keterangan..." value="<?= e($search ?? '') ?>">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary" style="flex-grow: 1;">Filter / Cari</button>
            <?php if (!empty($search)): ?>
                <a href="/admin/komisariat" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Komisariat</th>
                    <th>Perguruan Tinggi / Basis Kampus</th>
                    <th>Keterangan</th>
                    <th>Jumlah Anggota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($komisariat)): ?>
                    <?php foreach ($komisariat as $k): ?>
                        <tr>
                            <td>
                                <a href="/admin/komisariat/<?= (int)$k['id'] ?>" style="font-weight: 700; color: var(--primary);">
                                    <?= e($k['nama']) ?>
                                </a>
                            </td>
                            <td><?= e($k['perguruan_tinggi'] ?? '-') ?></td>
                            <td><span class="text-muted" style="font-size: 0.85rem;"><?= e($k['keterangan'] ?? '-') ?></span></td>
                            <td><span class="badge badge-primary"><?= (int)$k['total_anggota'] ?> anggota</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="/admin/komisariat/<?= (int)$k['id'] ?>" class="btn btn-outline btn-sm" title="Lihat detail anggota komisariat">
                                        Lihat Anggota
                                    </a>
                                    <?php if (can('civitas.update')): ?>
                                        <a href="/admin/komisariat/<?= (int)$k['id'] ?>/edit" class="btn btn-outline btn-sm">Edit</a>
                                    <?php endif; ?>
                                    <?php if (can('civitas.delete')): ?>
                                        <form action="/admin/komisariat/<?= (int)$k['id'] ?>/delete" method="POST" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus komisariat <?= e($k['nama']) ?>?">✕</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding: 3rem;">
                            <?= !empty($search) ? 'Tidak ditemukan komisariat yang sesuai dengan kata kunci pencarian.' : 'Belum ada data komisariat.' ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
