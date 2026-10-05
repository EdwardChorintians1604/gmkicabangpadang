<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Database Civitas GMKI Padang</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Daftar seluruh anggota, kader aktif, dan alumni GMKI Cabang Padang.
        </p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/civitas/export<?= !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '' ?>" class="btn btn-outline btn-sm">
            📥 Unduh CSV
        </a>
        <?php if (can('civitas.import')): ?>
            <a href="/admin/civitas/impor" class="btn btn-outline btn-sm">
                📂 Impor Data
            </a>
        <?php endif; ?>
        <?php if (can('civitas.create')): ?>
            <a href="/admin/civitas/create" class="btn btn-primary btn-sm">
                ➕ Tambah Anggota
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filters Card -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="/admin/civitas" method="GET" class="grid grid-cols-4 gap-3">
        <div class="form-group" style="margin-bottom: 0;">
            <input type="text" name="q" class="form-control" placeholder="Cari nama, NIM, kampus..." value="<?= e($search ?? '') ?>">
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
            <button type="submit" class="btn btn-primary" style="flex-grow: 1;">Filter</button>
            <?php if (!empty($search) || !empty($selectedKomisariat) || !empty($selectedTahun) || !empty($selectedStatus)): ?>
                <a href="/admin/civitas" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Data Table Card -->
<div class="table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Lengkap</th>
                    <th>L/P</th>
                    <th>Perguruan Tinggi</th>
                    <th>Komisariat</th>
                    <th>Maperca</th>
                    <th>Kaderisasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($civitas)): ?>
                    <?php foreach ($civitas as $row): ?>
                        <tr>
                            <td><code><?= e($row['nim']) ?></code></td>
                            <td>
                                <a href="/admin/civitas/<?= $row['id'] ?>" style="font-weight: 700; color: var(--primary);">
                                    <?= e($row['nama_lengkap']) ?>
                                </a>
                            </td>
                            <td><?= e($row['jenis_kelamin']) ?></td>
                            <td><?= e($row['perguruan_tinggi']) ?></td>
                            <td>
                                <?php if (isset($row['anggota_komisariat']) && (int)$row['anggota_komisariat'] === 0): ?>
                                    <span class="text-muted">Bukan anggota komisariat</span>
                                <?php elseif (!empty($row['komisariat'])): ?>
                                    <span class="badge badge-primary"><?= e($row['komisariat']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Nama komisariat belum diisi</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($row['tahun_maperca']) ?></td>
                            <td><span class="badge badge-neutral"><?= e($row['tingkat_kaderisasi']) ?></span></td>
                            <td>
                                <?php if ($row['status_keanggotaan'] === 'Aktif'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php elseif ($row['status_keanggotaan'] === 'Alumni/Senior'): ?>
                                    <span class="badge badge-warning">Alumni</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral"><?= e($row['status_keanggotaan']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="/admin/civitas/<?= $row['id'] ?>" class="btn btn-outline btn-sm" title="Lihat Profil">
                                        Detail
                                    </a>
                                    <?php if (can('civitas.update')): ?>
                                        <a href="/admin/civitas/<?= $row['id'] ?>/edit" class="btn btn-outline btn-sm">
                                            Edit
                                        </a>
                                    <?php endif; ?>
                                    <?php if (can('civitas.delete')): ?>
                                        <form action="/admin/civitas/<?= $row['id'] ?>/delete" method="POST" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus data anggota <?= e($row['nama_lengkap']) ?>?">
                                                ✕
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted" style="padding: 3rem;">
                            Tidak ada data civitas yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <div class="text-muted">
                Halaman <?= $currentPage ?> dari <?= $totalPages ?> (Total <?= $total ?> anggota)
            </div>
            <div class="pagination-pages">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/admin/civitas?page=<?= $i ?><?= !empty($search) ? '&q='.urlencode($search) : '' ?><?= !empty($selectedKomisariat) ? '&komisariat='.urlencode($selectedKomisariat) : '' ?>" 
                       class="page-link <?= ($i === $currentPage) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
