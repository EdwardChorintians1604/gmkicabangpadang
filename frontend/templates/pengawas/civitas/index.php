<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div class="flex items-center gap-2" style="margin-bottom: 0.35rem;">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin: 0;">Pantauan Database Civitas & Kader</h1>
            <span class="badge badge-warning">BACA-SAJA</span>
        </div>
        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
            Pemantauan resmi data anggota, komisariat kampus, dan perkembangan jenjang kaderisasi civitas se-Kota Padang.
        </p>
    </div>
    <div class="flex gap-2">
        <a href="/pengawas/civitas/export<?= !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '' ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
            <?= svg_icon('download', 16) ?>
            <span>Unduh Laporan (CSV)</span>
        </a>
    </div>
</div>

<!-- Filters Card -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="/pengawas/civitas" method="GET" class="grid grid-cols-4 gap-3">
        <div class="form-group" style="margin-bottom: 0;">
            <input type="text" name="q" class="form-control" placeholder="Cari nama, NIM, universitas..." value="<?= e($search ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <select name="komisariat" class="form-control">
                <option value="">Semua Komisariat</option>
                <?php foreach ($komisariatList as $kom): ?>
                    <option value="<?= e($kom) ?>" <?= ($selectedKomisariat === $kom) ? 'selected' : '' ?>><?= e($kom) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <select name="tahun_maperca" class="form-control">
                <option value="">Semua Tahun Maperca</option>
                <?php foreach ($tahunList as $th): ?>
                    <option value="<?= e($th) ?>" <?= ($selectedTahun == $th) ? 'selected' : '' ?>><?= e($th) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary" style="flex-grow: 1;">Terapkan Filter</button>
            <?php if (!empty($search) || !empty($selectedKomisariat) || !empty($selectedTahun) || !empty($selectedStatus)): ?>
                <a href="/pengawas/civitas" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Data Table Card -->
<div class="card table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIM / Identitas</th>
                    <th>Nama Anggota</th>
                    <th>Komisariat & Kampus</th>
                    <th>Maperca</th>
                    <th>Jenjang Kader</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($civitas)): ?>
                    <?php foreach ($civitas as $c): ?>
                        <tr>
                            <td><code><?= e($c['nim']) ?></code></td>
                            <td>
                                <strong><?= e($c['nama_lengkap']) ?></strong>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= ($c['jenis_kelamin'] === 'L') ? 'Putra (L)' : 'Putri (P)' ?></div>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?= e($c['komisariat']) ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= e($c['perguruan_tinggi']) ?></div>
                            </td>
                            <td><span class="badge badge-neutral"><?= (int)$c['tahun_maperca'] ?></span></td>
                            <td>
                                <?php
                                $badgeColor = match($c['tingkat_kaderisasi']) {
                                    'KK' => 'badge-success',
                                    'KTB' => 'badge-secondary',
                                    default => 'badge-primary'
                                };
                                ?>
                                <span class="badge <?= $badgeColor ?>"><?= e($c['tingkat_kaderisasi']) ?></span>
                            </td>
                            <td>
                                <?php if ($c['status_keanggotaan'] === 'Aktif'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral"><?= e($c['status_keanggotaan']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <a href="/pengawas/civitas/<?= $c['id'] ?>" class="btn btn-outline btn-sm flex items-center gap-1" style="display: inline-flex;">
                                    <?= svg_icon('eye', 14) ?>
                                    <span>Lihat Rincian</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 3rem;">
                            Tidak ditemukan data civitas yang sesuai filter pencarian.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Reusable Pagination Partial -->
    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border);">
        <?= partial('partials.pagination', [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'total' => $total
        ]) ?>
    </div>
</div>
