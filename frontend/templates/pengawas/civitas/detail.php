<div style="max-width: 900px; margin: 0 auto;">
    <!-- Header -->
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div class="flex items-center gap-2" style="font-size: 0.875rem; margin-bottom: 0.25rem;">
                <a href="/pengawas/civitas" class="text-muted">&larr; Kembali ke Pantauan Civitas</a>
            </div>
            <div class="flex items-center gap-2">
                <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin: 0;">
                    Profil Civitas: <?= e($member['nama_lengkap']) ?>
                </h1>
                <span class="badge badge-warning">BACA-SAJA</span>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="btn btn-secondary btn-sm flex items-center gap-1">
                <?= svg_icon('printer', 14) ?>
                <span>Cetak Profil</span>
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
                                <th style="width: 180px; background: none;">NIM / ID Anggota</th>
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
                                <td><?= e($member['fakultas'] ?? '-') ?> / <?= e($member['jurusan'] ?? '-') ?> (Angkatan <?= e($member['angkatan'] ?? '-') ?>)</td>
                            </tr>
                            <tr>
                                <th style="background: none;">Komisariat</th>
                                <td><span class="badge badge-info"><?= e($member['komisariat']) ?></span></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Tahun Maperca</th>
                                <td><strong><?= e($member['tahun_maperca'] ?? '-') ?></strong></td>
                            </tr>
                            <tr>
                                <th style="background: none;">Jenjang Kaderisasi</th>
                                <td>
                                    <div class="flex items-center gap-1" style="flex-wrap: wrap;">
                                        <span class="badge badge-success">Maperca (<?= e($member['tahun_maperca'] ?? '-') ?>)</span>
                                        <?php if (!empty($member['tahun_ktb'])): ?>
                                            <span class="badge badge-info">KTB (<?= e($member['tahun_ktb']) ?>)</span>
                                        <?php endif; ?>
                                        <?php if (!empty($member['tahun_lk1'])): ?>
                                            <span class="badge badge-warning">LK-1 (<?= e($member['tahun_lk1']) ?>)</span>
                                        <?php endif; ?>
                                        <?php if (!empty($member['tahun_lk2'])): ?>
                                            <span class="badge badge-primary">LK-2 (<?= e($member['tahun_lk2']) ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="background: none;">Status KTA Canva</th>
                                <td>
                                    <?php if (!empty($member['kta_file'])): ?>
                                        <a href="/private/kta/<?= e($member['kta_file']) ?>" target="_blank" class="btn btn-outline btn-sm flex items-center gap-1" style="display: inline-flex;">
                                            <?= svg_icon('download', 14) ?>
                                            <span>Lihat KTA Digital</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.875rem;">Belum ada KTA terunggah</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kontak & Alamat -->
    <div class="grid grid-cols-2 gap-4" style="margin-bottom: 2rem;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="font-size: 1rem;">Informasi Kontak</h3>
            </div>
            <div class="card-body">
                <p><strong>Nomor WhatsApp:</strong> <?= e($member['no_hp'] ?? '-') ?></p>
                <p><strong>Alamat Email:</strong> <?= e($member['email'] ?? '-') ?></p>
                <p><strong>Akun Media Sosial:</strong> <?= e($member['media_sosial'] ?? '-') ?></p>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="font-size: 1rem;">Alamat Domisili</h3>
            </div>
            <div class="card-body">
                <p><strong>Alamat di Padang:</strong> <?= e($member['alamat_padang'] ?? '-') ?></p>
                <p><strong>Alamat Asal:</strong> <?= e($member['alamat_asal'] ?? '-') ?></p>
                <p><strong>Nama Gereja / Denominasi:</strong> <?= e($member['gereja_asal'] ?? '-') ?></p>
            </div>
        </div>
    </div>
</div>
