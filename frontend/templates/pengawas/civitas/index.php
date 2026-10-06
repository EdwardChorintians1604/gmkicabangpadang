<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 m-0">Pantauan Database Civitas & Kader</h1>
            <span class="badge badge-warning text-xs">BACA-SAJA</span>
        </div>
        <p class="text-slate-500 text-xs sm:text-sm m-0">
            Pemantauan resmi data anggota, komisariat kampus, dan perkembangan jenjang kaderisasi civitas se-Kota Padang.
        </p>
    </div>
    <div class="flex gap-2 flex-shrink-0">
        <a href="/pengawas/civitas/export<?= !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '' ?>" class="btn btn-secondary btn-sm flex items-center justify-center gap-1 w-full sm:w-auto">
            <?= svg_icon('download', 16) ?>
            <span>Unduh Laporan (CSV)</span>
        </a>
    </div>
</div>

<!-- Filters Card -->
<div class="card mb-6 p-4 sm:p-5">
    <form action="/pengawas/civitas" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="form-group mb-0">
            <input type="text" name="q" class="form-control" placeholder="Cari nama, NIM, universitas..." value="<?= e($search ?? '') ?>">
        </div>

        <div class="form-group mb-0">
            <select name="komisariat" class="form-control">
                <option value="">Semua Komisariat</option>
                <?php foreach ($komisariatList as $kom): ?>
                    <option value="<?= e($kom) ?>" <?= ($selectedKomisariat === $kom) ? 'selected' : '' ?>><?= e($kom) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group mb-0">
            <select name="tahun_maperca" class="form-control">
                <option value="">Semua Tahun Maperca</option>
                <?php foreach ($tahunList as $th): ?>
                    <option value="<?= e($th) ?>" <?= ($selectedTahun == $th) ? 'selected' : '' ?>><?= e($th) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex gap-2 col-span-1 sm:col-span-2 lg:col-span-1">
            <button type="submit" class="btn btn-primary flex-1 justify-center">Terapkan Filter</button>
            <?php if (!empty($search) || !empty($selectedKomisariat) || !empty($selectedTahun) || !empty($selectedStatus)): ?>
                <a href="/pengawas/civitas" class="btn btn-outline justify-center">Reset</a>
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
                    <th style="min-width: 90px;">ID / NIM</th>
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
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <span class="badge badge-neutral" style="font-weight: 700; font-size: 0.75rem;">#<?= (int)$c['id'] ?></span>
                                    <?php if (!empty($c['nim']) && (string)$c['nim'] !== (string)$c['id']): ?>
                                        <code style="font-size: 0.78rem;"><?= e($c['nim']) ?></code>
                                    <?php endif; ?>
                                </div>
                            </td>
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
                                    'Anggota' => 'badge-primary',
                                    'Alumni' => 'badge-neutral',
                                    default => 'badge-secondary'
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
