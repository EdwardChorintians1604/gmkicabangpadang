<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-body" style="padding: 2.5rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                    <?= $isEdit ? 'Edit Berita: ' . e($article['judul']) : 'Tulis Berita Baru' ?>
                </h1>
                <p class="text-muted" style="font-size: 0.875rem;">
                    Lengkapi judul, kategori, dan isi tulisan warta kegiatan cabang.
                </p>
            </div>
            <a href="/admin/berita" class="btn btn-outline btn-sm">&larr; Kembali</a>
        </div>

        <form action="<?= $isEdit ? '/admin/berita/' . $article['id'] : '/admin/berita' ?>" method="POST" enctype="multipart/form-data" data-validate>
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="judul">Judul Berita</label>
                <input type="text" id="judul" name="judul" class="form-control" 
                       placeholder="Masukkan judul berita yang menarik" 
                       value="<?= e($isEdit ? $article['judul'] : old('judul')) ?>" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="kategori">Kategori</label>
                    <select id="kategori" name="kategori" class="form-control" required>
                        <?php 
                            $currKat = $isEdit ? $article['kategori'] : old('kategori', 'Warta Cabang'); 
                        ?>
                        <option value="Warta Cabang" <?= ($currKat === 'Warta Cabang') ? 'selected' : '' ?>>Warta Cabang</option>
                        <option value="Kaderisasi" <?= ($currKat === 'Kaderisasi') ? 'selected' : '' ?>>Kaderisasi</option>
                        <option value="Opini" <?= ($currKat === 'Opini') ? 'selected' : '' ?>>Opini Kader</option>
                        <option value="Kegiatan" <?= ($currKat === 'Kegiatan') ? 'selected' : '' ?>>Aksi & Pelayanan</option>
                        <option value="Pengumuman" <?= ($currKat === 'Pengumuman') ? 'selected' : '' ?>>Pengumuman</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status Publikasi</label>
                    <select id="status" name="status" class="form-control" required>
                        <?php 
                            $currStat = $isEdit ? $article['status'] : old('status', 'published'); 
                        ?>
                        <option value="published" <?= ($currStat === 'published') ? 'selected' : '' ?>>Terbitkan Sekarang</option>
                        <option value="draft" <?= ($currStat === 'draft') ? 'selected' : '' ?>>Simpan Sebagai Draft</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="gambar_sampul">Gambar Sampul (Cover Image)</label>
                <input type="file" id="gambar_sampul" name="gambar_sampul" class="form-control" accept="image/*" data-preview="cover_preview">
                <div class="form-help">Format JPG, PNG, atau WebP. Maksimal 4 MB.</div>
                
                <div style="margin-top: 1rem;">
                    <?php if ($isEdit && !empty($article['gambar_sampul'])): ?>
                        <img id="cover_preview" src="/uploads/berita/<?= e($article['gambar_sampul']) ?>" alt="Preview" style="max-height: 200px; border-radius: var(--radius-md);">
                    <?php else: ?>
                        <img id="cover_preview" src="#" alt="Preview" style="display:none; max-height: 200px; border-radius: var(--radius-md);">
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="ringkasan">Ringkasan Berita</label>
                <textarea id="ringkasan" name="ringkasan" class="form-control" rows="2" placeholder="Ringkasan singkat yang akan muncul di kartu artikel..."><?= e($isEdit ? $article['ringkasan'] : old('ringkasan')) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="konten">Konten Berita Lengkap</label>
                <textarea id="konten" name="konten" class="form-control" rows="12" placeholder="Tuliskan berita lengkap di sini (dukungan format paragraf HTML)..." required><?= e($isEdit ? $article['konten'] : old('konten')) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="penulis_nama">Nama Penulis / Editor</label>
                <input type="text" id="penulis_nama" name="penulis_nama" class="form-control" 
                       value="<?= e($isEdit ? ($article['penulis_nama'] ?? '') : old('penulis_nama', auth()['nama_lengkap'] ?? 'BPC GMKI Padang')) ?>">
            </div>

            <div class="flex items-center gap-3" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Simpan Perubahan Berita' : 'Terbitkan Berita' ?>
                </button>
                <a href="/admin/berita" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
