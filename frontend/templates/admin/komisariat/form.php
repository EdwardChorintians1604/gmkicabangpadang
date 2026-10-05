<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-body" style="padding: 2.5rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                <?= $isEdit ? 'Edit Komisariat: ' . e($item['nama']) : 'Tambah Komisariat' ?>
            </h1>
            <a href="/admin/komisariat" class="btn btn-outline btn-sm">&larr; Kembali</a>
        </div>

        <form action="<?= $isEdit ? '/admin/komisariat/' . (int)$item['id'] : '/admin/komisariat' ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="nama">Nama Komisariat</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Komisariat UNAND"
                       value="<?= e($isEdit ? $item['nama'] : old('nama')) ?>" required>
                <?php if ($isEdit): ?>
                    <div class="form-help">Jika nama diubah, nama komisariat pada seluruh anggotanya ikut diperbarui.</div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="perguruan_tinggi">Perguruan Tinggi (opsional)</label>
                <input type="text" id="perguruan_tinggi" name="perguruan_tinggi" class="form-control"
                       value="<?= e($isEdit ? ($item['perguruan_tinggi'] ?? '') : old('perguruan_tinggi')) ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="keterangan">Keterangan (opsional)</label>
                <textarea id="keterangan" name="keterangan" class="form-control" rows="3"><?= e($isEdit ? ($item['keterangan'] ?? '') : old('keterangan')) ?></textarea>
            </div>

            <div class="flex items-center gap-3" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Komisariat' ?></button>
                <a href="/admin/komisariat" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
