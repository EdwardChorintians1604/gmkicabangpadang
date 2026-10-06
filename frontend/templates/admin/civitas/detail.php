<?php $isMaperca = ($kelompok ?? 'anggota') === 'maperca'; ?>
<div style="max-width: 900px; margin: 0 auto;">
    <!-- Header -->
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <div>
            <div class="flex items-center gap-2" style="font-size: 0.875rem; margin-bottom: 0.25rem;">
                <a href="<?= e($basePath) ?>" class="text-muted">&larr; Kembali ke <?= $isMaperca ? 'Data Kader Baru' : 'Data Civitas' ?></a>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">
                <?= $isMaperca ? 'Profil Kader Baru' : 'Profil Civitas' ?>: <?= e($member['nama_lengkap']) ?>
            </h1>
        </div>
        <div class="flex gap-2">
            <?php if (can('civitas.update')): ?>
                <a href="<?= e($basePath) ?>/<?= $member['id'] ?>/edit" class="btn btn-outline btn-sm">
                    ✏️ Edit Data
                </a>
            <?php endif; ?>
            <?php if ($isMaperca && can('civitas.update')): ?>
                <form action="/admin/maperca/<?= $member['id'] ?>/lantik" method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary btn-sm" data-confirm="Lantik <?= e($member['nama_lengkap']) ?> menjadi Anggota sah GMKI Cabang Padang?">
                        Lantik jadi Anggota
                    </button>
                </form>
            <?php endif; ?>
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                🖨️ Cetak Profil
            </button>
        </div>
    </div>

    <!-- Main Member Card -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-body" style="padding: 2.5rem;">
            <div class="flex items-start gap-6" style="flex-wrap: wrap;">
                <!-- Avatar / Photo -->
                <div style="text-align: center; flex-shrink: 0; width: 160px;">
                    <div style="width: 150px; height: 180px; border-radius: var(--radius-md); overflow: hidden; background: var(--bg-subtle); border: 2px solid var(--border-color); margin-bottom: 0.75rem;">
                        <?php if (!empty($member['foto_anggota'])): ?>
                            <img src="/private/foto-anggota/<?= e($member['foto_anggota']) ?>" alt="<?= e($member['nama_lengkap']) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null;this.src='<?= asset('images/default-profile.png') ?>';">
                        <?php else: ?>
                            <img src="<?= asset('images/default-profile.png') ?>" alt="Default" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if ($isMaperca): ?>
                            <span class="badge badge-warning">Kader Baru (Maperca)</span>
                        <?php elseif ($member['status_keanggotaan'] === 'Aktif'): ?>
                            <span class="badge badge-success">Anggota Aktif</span>
                        <?php elseif ($member['status_keanggotaan'] === 'Pasif'): ?>
                            <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">Pasif (Jarang Aktif)</span>
                        <?php elseif ($member['status_keanggotaan'] === 'Alumni/Senior'): ?>
                            <span class="badge badge-warning">Senior / Alumni</span>
                        <?php elseif ($member['status_keanggotaan'] === 'Pindah Cabang'): ?>
                            <span class="badge badge-neutral">Pindah Cabang</span>
                        <?php elseif ($member['status_keanggotaan'] === 'Dicabut'): ?>
                            <span class="badge badge-danger">Keanggotaan Dicabut</span>
                        <?php else: ?>
                            <span class="badge badge-neutral"><?= e($member['status_keanggotaan']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Info Table -->
                <div style="flex-grow: 1;">
                    <table class="data-table" style="border: none;">
                        <tbody>
                            <tr>
                                <th style="width: 180px; background: none;">ID / NIM</th>
                                <td>
                                    <span class="badge badge-neutral" style="font-weight: 700; font-size: 0.85rem; margin-right: 6px;">#<?= (int)$member['id'] ?></span>
                                    <code style="font-size: 1rem;"><?= e($member['nim']) ?></code>
                                </td>
                            </tr>
                            <tr>
                                <th style="background: none;">Nama Lengkap</th>
                                <td style="font-weight: 700; font-size: 1.05rem;"><?= e($member['nama_lengkap']) ?></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Jenis Kelamin</th>
                                <td><?= $member['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Tempat, Tanggal Lahir</th>
                                <td>
                                    <?= e($member['tempat_lahir'] ?? '-') ?>, <?= format_date($member['tanggal_lahir'], 'd F Y') ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="background: none;">Perguruan Tinggi</th>
                                <td><strong><?= e($member['perguruan_tinggi']) ?></strong></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Fakultas / Jurusan</th>
                                <td><?= e($member['fakultas'] ?? '-') ?> / <?= e($member['jurusan'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Komisariat</th>
                                <td>
                                    <?php if (!empty($member['komisariat'])): ?>
                                        <span class="badge badge-primary"><?= e($member['komisariat']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted"><?= $isMaperca ? 'Kader Baru' : 'Tidak pernah sama sekali' ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="background: none;">Tahun Maperca</th>
                                <td><?= e($member['tahun_maperca']) ?></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Status Keanggotaan</th>
                                <td>
                                    <?php if ($member['status_keanggotaan'] === 'Aktif'): ?>
                                        <span class="badge badge-success">Aktif (Mahasiswa aktif berkegiatan)</span>
                                    <?php elseif ($member['status_keanggotaan'] === 'Pasif'): ?>
                                        <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">Pasif (Tercatat, tetapi jarang/tidak aktif)</span>
                                    <?php elseif ($member['status_keanggotaan'] === 'Alumni/Senior'): ?>
                                        <span class="badge badge-warning">Alumni / Senior (Sudah wisuda/tamat)</span>
                                    <?php elseif ($member['status_keanggotaan'] === 'Pindah Cabang'): ?>
                                        <span class="badge badge-neutral">Pindah Cabang</span>
                                    <?php elseif ($member['status_keanggotaan'] === 'Dicabut'): ?>
                                        <span class="badge badge-danger">Keanggotaan Dicabut / Diberhentikan</span>
                                    <?php else: ?>
                                        <span class="badge badge-neutral"><?= e($member['status_keanggotaan']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="background: none;">Telepon / WA</th>
                                <td><?= e($member['telepon'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Email</th>
                                <td><?= e($member['email'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Alamat di Padang</th>
                                <td><?= nl2br(e($member['alamat_padang'] ?? '-')) ?></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Alamat Asal</th>
                                <td><?= nl2br(e($member['alamat_asal'] ?? '-')) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
