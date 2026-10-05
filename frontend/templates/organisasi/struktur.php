<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Struktur Kepengurusan BPC</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Kelola daftar fungsionaris Badan Pengurus Cabang GMKI Cabang Padang.
        </p>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">
    <!-- Form Tambah / Edit Pengurus -->
    <div class="card" style="height: fit-content;">
        <div class="card-body">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem; color: var(--primary);">Tambah / Perbarui Pengurus</h3>
            
            <form action="/admin/organisasi/struktur" method="POST" enctype="multipart/form-data" data-validate>
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="officer_id" value="">

                <div class="form-group">
                    <label class="form-label" for="nama">Nama Lengkap & Gelar</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Yeremia Pratama, S.T." required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="jabatan">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" class="form-control" placeholder="Contoh: Ketua Cabang" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="bidang">Bidang / Departemen</label>
                    <select id="bidang" name="bidang" class="form-control">
                        <option value="Badan Pengurus Harian">Badan Pengurus Harian (BPH)</option>
                        <option value="Bidang Organisasi">Bidang Organisasi</option>
                        <option value="Bidang Kaderisasi">Bidang Kaderisasi & Kerohanian</option>
                        <option value="Bidang Akpel">Bidang Aksi & Pelayanan</option>
                        <option value="Bidang Media & Komunikasi">Bidang Media & Komunikasi</option>
                        <option value="Biro Khusus">Biro Khusus</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label" for="periode">Periode</label>
                        <input type="text" id="periode" name="periode" class="form-control" value="2024-2026">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="urutan">Nomor Urut</label>
                        <input type="number" id="urutan" name="urutan" class="form-control" value="1">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="foto">Foto Formal Pengurus</label>
                    <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                    <div class="form-help">Format JPG/PNG/WebP, maksimal 4MB.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status_aktif">Status Aktif</label>
                    <select id="status_aktif" name="status_aktif" class="form-control">
                        <option value="1">Aktif Menjabat</option>
                        <option value="0">Demisioner / Nonaktif</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Simpan Pengurus
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Pengurus -->
    <div class="table-card" style="grid-column: span 2;">
        <div class="table-header">
            <div class="table-title">Daftar Pengurus Terdaftar</div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Urut</th>
                        <th>Foto</th>
                        <th>Nama & Jabatan</th>
                        <th>Bidang</th>
                        <th>Periode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($structure)): ?>
                        <?php foreach ($structure as $item): ?>
                            <tr>
                                <td><strong>#<?= (int)$item['urutan'] ?></strong></td>
                                <td>
                                    <?php if (!empty($item['foto'])): ?>
                                        <img src="/uploads/organisasi/<?= e($item['foto']) ?>" alt="<?= e($item['nama']) ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="<?= asset('images/default-profile.png') ?>" alt="Default" style="width: 40px; height: 40px; border-radius: 50%;">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="font-weight: 700;"><?= e($item['nama']) ?></div>
                                    <div style="font-size: 0.75rem; color: var(--secondary); font-weight: 600;"><?= e($item['jabatan']) ?></div>
                                </td>
                                <td><span class="badge badge-neutral"><?= e($item['bidang']) ?></span></td>
                                <td class="text-muted"><?= e($item['periode']) ?></td>
                                <td>
                                    <form action="/admin/organisasi/struktur/<?= $item['id'] ?>/delete" method="POST" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus data pengurus <?= e($item['nama']) ?>?">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 2rem;">Belum ada pengurus yang ditambahkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
