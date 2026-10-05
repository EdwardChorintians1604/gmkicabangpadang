<?php $isMaperca = ($kelompok ?? 'anggota') === 'maperca'; ?>
<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">
            <?= $isMaperca ? 'Pendaftaran Anggota Baru GMKI Padang' : 'Database Civitas GMKI Padang' ?>
        </h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            <?= $isMaperca
                ? 'Data awal anggota baru. Komisariat ditentukan saat pelantikan ke Data Civitas.'
                : 'Daftar anggota resmi (KTB, KK) dan alumni GMKI Cabang Padang. Kader baru Maperca dikelola di menu terpisah.' ?>
        </p>
    </div>
    <div class="flex gap-2">
        <a href="<?= e($basePath) ?>/export<?= !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '' ?>" class="btn btn-outline btn-sm">
            📥 Unduh CSV
        </a>
        <?php if (!$isMaperca && can('civitas.import')): ?>
            <a href="/admin/civitas/impor" class="btn btn-outline btn-sm">
                📂 Impor Data
            </a>
        <?php endif; ?>
        <?php if (can('civitas.create')): ?>
            <a href="<?= e($basePath) ?>/create" class="btn btn-primary btn-sm">
                ➕ <?= $isMaperca ? 'Daftarkan Anggota Baru' : 'Tambah Anggota' ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="<?= e($basePath) ?>" method="GET" class="grid grid-cols-5 gap-3">
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
                <option value="">Semua Tahun</option>
                <?php foreach ($tahunList as $th): ?>
                    <option value="<?= e($th) ?>" <?= ($selectedTahun == $th) ? 'selected' : '' ?>><?= e($th) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!$isMaperca): ?>
        <div class="form-group" style="margin-bottom: 0;">
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="Aktif" <?= ($selectedStatus === 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                <option value="Pasif" <?= ($selectedStatus === 'Pasif') ? 'selected' : '' ?>>Pasif (Jarang Aktif)</option>
                <option value="Alumni/Senior" <?= ($selectedStatus === 'Alumni/Senior') ? 'selected' : '' ?>>Alumni / Senior</option>
                <option value="Pindah Cabang" <?= ($selectedStatus === 'Pindah Cabang') ? 'selected' : '' ?>>Pindah Cabang</option>
                <option value="Dicabut" <?= ($selectedStatus === 'Dicabut') ? 'selected' : '' ?>>Dicabut</option>
            </select>
        </div>
        <?php endif; ?>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary" style="flex-grow: 1;">Filter</button>
            <?php if (!empty($search) || !empty($selectedKomisariat) || !empty($selectedTahun) || !empty($selectedStatus)): ?>
                <a href="<?= e($basePath) ?>" class="btn btn-outline">Reset</a>
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
                    <th><?= $isMaperca ? 'Tahun Maperca' : 'Maperca' ?></th>
                    <th>Status Keanggotaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($civitas)): ?>
                    <?php foreach ($civitas as $row): ?>
                        <tr>
                            <td><code><?= e($row['nim']) ?></code></td>
                            <td>
                                <a href="<?= e($basePath) ?>/<?= $row['id'] ?>" style="font-weight: 700; color: var(--primary);">
                                    <?= e($row['nama_lengkap']) ?>
                                </a>
                            </td>
                            <td><?= e($row['jenis_kelamin']) ?></td>
                            <td><?= e($row['perguruan_tinggi']) ?></td>
                            <td>
                                <?php if (!empty($row['komisariat'])): ?>
                                    <span class="badge badge-primary"><?= e($row['komisariat']) ?></span>
                                <?php elseif ($isMaperca): ?>
                                    <span class="text-muted">Kader Baru</span>
                                <?php else: ?>
                                    <span class="text-muted">Tanpa Komisariat</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($row['tahun_maperca'] ?? '-') ?></td>
                            <td>
                                <?php if ($row['status_keanggotaan'] === 'Aktif'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php elseif ($row['status_keanggotaan'] === 'Pasif'): ?>
                                    <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">Pasif</span>
                                <?php elseif ($row['status_keanggotaan'] === 'Alumni/Senior'): ?>
                                    <span class="badge badge-warning">Alumni / Senior</span>
                                <?php elseif ($row['status_keanggotaan'] === 'Pindah Cabang'): ?>
                                    <span class="badge badge-neutral">Pindah Cabang</span>
                                <?php elseif ($row['status_keanggotaan'] === 'Dicabut'): ?>
                                    <span class="badge badge-danger">Dicabut</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral"><?= e($row['status_keanggotaan']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="<?= e($basePath) ?>/<?= $row['id'] ?>" class="btn btn-outline btn-sm" title="Lihat Profil">
                                        Detail
                                    </a>
                                    <?php if (can('civitas.update')): ?>
                                        <a href="<?= e($basePath) ?>/<?= $row['id'] ?>/edit" class="btn btn-outline btn-sm">
                                            Edit
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($isMaperca && can('civitas.update')): ?>
                                        <form action="/admin/maperca/<?= $row['id'] ?>/lantik" method="POST" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-primary btn-sm" data-confirm="Lantik <?= e($row['nama_lengkap']) ?> menjadi anggota resmi (KTB)?">
                                                Lantik
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if (can('civitas.delete')): ?>
                                        <form action="<?= e($basePath) ?>/<?= $row['id'] ?>/delete" method="POST" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus data <?= e($row['nama_lengkap']) ?>?">
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
                            <?= $isMaperca ? 'Belum ada data kader baru (Maperca).' : 'Tidak ada data civitas yang sesuai kriteria pencarian.' ?>
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
                    <a href="<?= e($basePath) ?>?page=<?= $i ?><?= !empty($search) ? '&q='.urlencode($search) : '' ?><?= !empty($selectedKomisariat) ? '&komisariat='.urlencode($selectedKomisariat) : '' ?>" 
                       class="page-link <?= ($i === $currentPage) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
