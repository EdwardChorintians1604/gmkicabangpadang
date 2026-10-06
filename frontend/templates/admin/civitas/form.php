<?php $isMaperca = ($kelompok ?? 'anggota') === 'maperca'; ?>
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-body" style="padding: 2.5rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                    <?php if ($isEdit): ?>
                        Edit Data: <?= e($member['nama_lengkap']) ?>
                    <?php else: ?>
                        <?= $isMaperca ? 'Pendaftaran Anggota Baru' : 'Tambah Anggota / Civitas' ?>
                    <?php endif; ?>
                </h1>
                <p class="text-muted" style="font-size: 0.875rem;">
                    <?= $isMaperca
                        ? 'Isi data dasar anggota baru. Komisariat ditentukan saat pelantikan menjadi anggota.'
                        : 'Pastikan informasi keanggotaan dan kaderisasi diisi dengan akurat.' ?>
                </p>
            </div>
            <a href="<?= e($basePath) ?>" class="btn btn-outline btn-sm">&larr; Kembali</a>
        </div>

        <form action="<?= $isEdit ? e($basePath) . '/' . $member['id'] : e($basePath) ?>" method="POST" enctype="multipart/form-data" data-validate>
            <?= csrf_field() ?>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="nim">
                        ID Anggota / NIM
                        <span style="font-weight: normal; font-size: 0.78rem; color: var(--text-muted);">(Bisa ID angka berapapun atau NIM)</span>
                    </label>
                    <input type="text" id="nim" name="nim" class="form-control" 
                           placeholder="Berapapun angka ID atau NIM (contoh: 1, 12, 100)" 
                           value="<?= e($isEdit ? ($member['nim'] ?? $member['id']) : old('nim')) ?>">
                    <div class="form-help" style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                        Boleh diisi angka ID berapapun, NIM, atau kosongkan untuk otomatis generate ID.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" 
                           placeholder="<?= $isMaperca ? 'Nama lengkap anggota baru' : 'Nama lengkap civitas' ?>" 
                           value="<?= e($isEdit ? $member['nama_lengkap'] : old('nama_lengkap')) ?>" required>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                    <?php $jk = $isEdit ? $member['jenis_kelamin'] : old('jenis_kelamin', 'L'); ?>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                        <option value="L" <?= ($jk === 'L') ? 'selected' : '' ?>>Laki-laki (L)</option>
                        <option value="P" <?= ($jk === 'P') ? 'selected' : '' ?>>Perempuan (P)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" 
                           value="<?= e($isEdit ? $member['tempat_lahir'] : old('tempat_lahir')) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" 
                           value="<?= e($isEdit ? $member['tanggal_lahir'] : old('tanggal_lahir')) ?>">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="telepon">Nomor WhatsApp / HP</label>
                    <input type="text" id="telepon" name="telepon" class="form-control" 
                           placeholder="08xxxxxxxxxx" 
                           value="<?= e($isEdit ? $member['telepon'] : old('telepon')) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="nama@email.com" 
                           value="<?= e($isEdit ? $member['email'] : old('email')) ?>">
                </div>
            </div>

            <h3 style="font-size: 1.15rem; margin: 1.5rem 0 1rem; color: var(--primary);">Informasi Keanggotaan</h3>

            <div class="grid grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label" for="perguruan_tinggi">Perguruan Tinggi</label>
                    <input type="text" id="perguruan_tinggi" name="perguruan_tinggi" class="form-control" 
                           placeholder="Contoh: Universitas Andalas" 
                           value="<?= e($isEdit ? $member['perguruan_tinggi'] : old('perguruan_tinggi', 'Universitas Andalas')) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="fakultas">Fakultas</label>
                    <input type="text" id="fakultas" name="fakultas" class="form-control" 
                           value="<?= e($isEdit ? $member['fakultas'] : old('fakultas')) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="jurusan">Jurusan / Program Studi</label>
                    <input type="text" id="jurusan" name="jurusan" class="form-control" 
                           value="<?= e($isEdit ? $member['jurusan'] : old('jurusan')) ?>">
                </div>
            </div>

            <?php if (!$isMaperca): ?>
            <?php
            $isAnggotaKom = $isEdit
                ? (isset($member['anggota_komisariat']) ? (int)$member['anggota_komisariat'] : (!empty($member['komisariat']) ? 1 : 0))
                : (old('anggota_komisariat') !== null ? (int)old('anggota_komisariat') : 0);
            $komCurrent = $isEdit ? ($member['komisariat'] ?? '') : old('komisariat', '');
            ?>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="anggota_komisariat">Apakah pernah menjadi anggota komisariat?</label>
                    <select id="anggota_komisariat" name="anggota_komisariat" class="form-control" required>
                        <option value="0" <?= $isAnggotaKom === 0 ? 'selected' : '' ?>>Tidak pernah sama sekali</option>
                        <option value="1" <?= $isAnggotaKom === 1 ? 'selected' : '' ?>>Ya, anggota komisariat</option>
                    </select>
                </div>

                <div class="form-group" id="komisariat-wrapper" style="<?= $isAnggotaKom === 1 ? '' : 'display:none;' ?>">
                    <label class="form-label" for="komisariat">Pilih Komisariat</label>
                    <select id="komisariat" name="komisariat" class="form-control">
                        <option value="">-- Pilih Komisariat --</option>
                        <?php foreach (($komisariatNames ?? []) as $kn): ?>
                            <option value="<?= e($kn) ?>" <?= $komCurrent === $kn ? 'selected' : '' ?>><?= e($kn) ?></option>
                        <?php endforeach; ?>
                        <?php if ($komCurrent !== '' && !in_array($komCurrent, $komisariatNames ?? [], true)): ?>
                            <option value="<?= e($komCurrent) ?>" selected><?= e($komCurrent) ?></option>
                        <?php endif; ?>
                    </select>
                    <div class="form-help">Pilih komisariat yang ada.</div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="tahun_maperca">Tahun Maperca</label>
                    <input type="number" id="tahun_maperca" name="tahun_maperca" class="form-control" 
                           placeholder="Contoh: 2024"
                           value="<?= e($isEdit ? ($member['tahun_maperca'] ?? '') : old('tahun_maperca', date('Y'))) ?>" required>
                    <div class="form-help">Tahun saat pertama kali mengikuti Maperca.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status_keanggotaan">Status Keanggotaan</label>
                    <?php $sk = $isEdit ? $member['status_keanggotaan'] : old('status_keanggotaan', 'Aktif'); ?>
                    <select id="status_keanggotaan" name="status_keanggotaan" class="form-control" required>
                        <option value="Aktif" <?= ($sk === 'Aktif') ? 'selected' : '' ?>>Aktif &mdash; Mahasiswa aktif berkegiatan</option>
                        <option value="Pasif" <?= ($sk === 'Pasif' || $sk === 'Nonaktif') ? 'selected' : '' ?>>Pasif &mdash; Tercatat anggota, tetapi jarang/tidak aktif</option>
                        <option value="Alumni/Senior" <?= ($sk === 'Alumni/Senior') ? 'selected' : '' ?>>Alumni / Senior &mdash; Sudah wisuda / tamat kuliah</option>
                        <option value="Pindah Cabang" <?= ($sk === 'Pindah Cabang') ? 'selected' : '' ?>>Pindah Cabang &mdash; Mutasi ke cabang kota lain</option>
                        <option value="Dicabut" <?= ($sk === 'Dicabut') ? 'selected' : '' ?>>Dicabut &mdash; Keanggotaan dicabut / diberhentikan</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="tingkat_kaderisasi" value="<?= e($isEdit ? ($member['tingkat_kaderisasi'] ?? 'Anggota') : 'Anggota') ?>">
            <?php else: ?>
                <input type="hidden" name="status_keanggotaan" value="<?= e($isEdit ? $member['status_keanggotaan'] : old('status_keanggotaan', 'Aktif')) ?>">
            <?php endif; ?>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="alamat_padang">Alamat Domisili di Padang</label>
                    <textarea id="alamat_padang" name="alamat_padang" class="form-control" rows="2"><?= e($isEdit ? $member['alamat_padang'] : old('alamat_padang')) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat_asal">Alamat Daerah Asal</label>
                    <textarea id="alamat_asal" name="alamat_asal" class="form-control" rows="2"><?= e($isEdit ? $member['alamat_asal'] : old('alamat_asal')) ?></textarea>
                </div>
            </div>

            <h3 style="font-size: 1.15rem; margin: 1.5rem 0 1rem; color: var(--primary);">Foto Diri</h3>
            <p class="text-muted" style="font-size: 0.8125rem; margin-bottom: 1rem;">
                Foto disimpan secara aman di folder penyimpanan privat (storage/private/) dan hanya dapat dibuka oleh pengguna yang login.
            </p>

            <div class="form-group">
                <label class="form-label" for="foto_anggota">Foto Diri</label>
                <input type="file" id="foto_anggota" name="foto_anggota" class="form-control" accept="image/*">
                <div class="form-help">JPG, PNG, atau WebP. Maksimal 3 MB.</div>
                <?php if ($isEdit && !empty($member['foto_anggota'])): ?>
                    <div style="margin-top: 0.5rem; font-size: 0.8125rem;">
                        <a href="/private/foto-anggota/<?= e($member['foto_anggota']) ?>" target="_blank" class="btn btn-outline btn-sm">
                            🖼️ Lihat Foto Saat Ini
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="catatan">Catatan Tambahan</label>
                <textarea id="catatan" name="catatan" class="form-control" rows="2" placeholder="Catatan khusus kaderisasi atau keterangan keaktifan..."><?= e($isEdit ? $member['catatan'] : old('catatan')) ?></textarea>
            </div>

            <div class="flex items-center gap-3" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <?php if ($isEdit): ?>
                        Simpan Perubahan
                    <?php else: ?>
                        <?= $isMaperca ? 'Daftarkan Anggota Baru' : 'Tambahkan Anggota' ?>
                    <?php endif; ?>
                </button>
                <a href="<?= e($basePath) ?>" class="btn btn-outline">Batal</a>
            </div>
        </form>

        <?php if (!$isMaperca): ?>
        <script>
            (function() {
                const anggotaKomisariatSelect = document.getElementById('anggota_komisariat');
                const komisariatWrapper = document.getElementById('komisariat-wrapper');
                const komisariatSelect = document.getElementById('komisariat');

                if (anggotaKomisariatSelect && komisariatWrapper) {
                    function toggleKomisariat() {
                        if (anggotaKomisariatSelect.value === '1') {
                            komisariatWrapper.style.display = '';
                            if (komisariatSelect) komisariatSelect.required = true;
                        } else {
                            komisariatWrapper.style.display = 'none';
                            if (komisariatSelect) {
                                komisariatSelect.required = false;
                                komisariatSelect.value = '';
                            }
                        }
                    }
                    anggotaKomisariatSelect.addEventListener('change', toggleKomisariat);
                    toggleKomisariat();
                }
            })();
        </script>
        <?php endif; ?>
    </div>
</div>
