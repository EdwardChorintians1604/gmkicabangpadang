<div style="max-width: 900px; margin: 0 auto;">
    <!-- Header -->
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <div>
            <div class="flex items-center gap-2" style="font-size: 0.875rem; margin-bottom: 0.25rem;">
                <a href="/admin/civitas" class="text-muted">&larr; Kembali ke Data Civitas</a>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">
                Profil Civitas: <?= e($member['nama_lengkap']) ?>
            </h1>
        </div>
        <div class="flex gap-2">
            <?php if (can('civitas.update')): ?>
                <a href="/admin/civitas/<?= $member['id'] ?>/edit" class="btn btn-outline btn-sm">
                    ✏️ Edit Data
                </a>
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
                            <img src="/private/foto-anggota/<?= e($member['foto_anggota']) ?>" alt="<?= e($member['nama_lengkap']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <img src="<?= asset('images/default-profile.png') ?>" alt="Default" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if ($member['status_keanggotaan'] === 'Aktif'): ?>
                            <span class="badge badge-success">Anggota Aktif</span>
                        <?php elseif ($member['status_keanggotaan'] === 'Alumni/Senior'): ?>
                            <span class="badge badge-warning">Senior / Alumni</span>
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
                                <th style="width: 180px; background: none;">NIM</th>
                                <td><code style="font-size: 1rem;"><?= e($member['nim']) ?></code></td>
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
                                <td><?= isset($member['anggota_komisariat']) && (int)$member['anggota_komisariat'] === 0 ? 'Bukan anggota komisariat' : e($member['komisariat'] ?? 'Nama komisariat belum diisi') ?></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Tahun Maperca</th>
                                <td><?= e($member['tahun_maperca']) ?></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Tingkat Kaderisasi</th>
                                <td><span class="badge badge-warning"><?= e($member['tingkat_kaderisasi']) ?></span></td>
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

    <!-- KTA Card Section -->
    <div class="card">
        <div class="card-body">
            <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem; color: var(--primary);">Kartu Tanda Anggota (KTA)</h3>
            <?php if (!empty($member['file_kta'])): ?>
                <div class="flex items-center justify-between" style="padding: 1rem; background: var(--bg-subtle); border-radius: var(--radius-md);">
                    <div class="flex items-center gap-3">
                        <span style="font-size: 2rem;">🪪</span>
                        <div>
                            <strong>Berkas Desain KTA Civitas</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">File: <?= e($member['file_kta']) ?></div>
                        </div>
                    </div>
                    <div>
                        <a href="/private/kta/<?= e($member['file_kta']) ?>" target="_blank" class="btn btn-primary btn-sm">
                            Unduh / Buka KTA
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-muted" style="font-size: 0.875rem;">
                    Belum ada berkas KTA yang diunggah untuk civitas ini. Anda dapat mengunggah file KTA hasil desain Canva melalui menu edit data.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
