<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-body" style="padding: 2.5rem;">
        <div style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">Pengaturan Profil Organisasi</h1>
            <p class="text-muted" style="font-size: 0.875rem;">
                Informasi ini akan ditampilkan pada halaman publik website resmi GMKI Cabang Padang.
            </p>
        </div>

        <form action="/admin/organisasi/profil" method="POST" data-validate>
            <?= csrf_field() ?>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="nama_organisasi">Nama Organisasi</label>
                    <input type="text" id="nama_organisasi" name="nama_organisasi" class="form-control" 
                           value="<?= e($profile['nama_organisasi'] ?? 'GMKI Cabang Padang') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="slogan">Slogan Cabang</label>
                    <input type="text" id="slogan" name="slogan" class="form-control" 
                           value="<?= e($profile['slogan'] ?? 'Ut Omnes Unum Sint - Syalom!') ?>" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="tema_periode">Tema Periode</label>
                    <input type="text" id="tema_periode" name="tema_periode" class="form-control" 
                           value="<?= e($profile['tema_periode'] ?? '') ?>" placeholder="Contoh: Bangkitlah, Menjadi Teranglah!">
                </div>

                <div class="form-group">
                    <label class="form-label" for="sub_tema">Sub-Tema Periode</label>
                    <input type="text" id="sub_tema" name="sub_tema" class="form-control" 
                           value="<?= e($profile['sub_tema'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="sejarah">Sejarah Singkat GMKI Cabang Padang</label>
                <textarea id="sejarah" name="sejarah" class="form-control" rows="5"><?= e($profile['sejarah'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="visi">Visi Cabang</label>
                    <textarea id="visi" name="visi" class="form-control" rows="4"><?= e($profile['visi'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="misi">Misi Cabang</label>
                    <textarea id="misi" name="misi" class="form-control" rows="4"><?= e($profile['misi'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="tri_panji">Tri Panji GMKI</label>
                    <textarea id="tri_panji" name="tri_panji" class="form-control" rows="4"><?= e($profile['tri_panji'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="panca_kegiatan">Panca Kegiatan GMKI</label>
                    <textarea id="panca_kegiatan" name="panca_kegiatan" class="form-control" rows="4"><?= e($profile['panca_kegiatan'] ?? '') ?></textarea>
                </div>
            </div>

            <h3 style="font-size: 1.15rem; margin: 1.5rem 0 1rem; color: var(--primary);">Kontak & Sekretariat</h3>

            <div class="form-group">
                <label class="form-label" for="alamat_sekretariat">Alamat Lengkap Sekretariat</label>
                <textarea id="alamat_sekretariat" name="alamat_sekretariat" class="form-control" rows="2"><?= e($profile['alamat_sekretariat'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label" for="telepon">Telepon / WhatsApp</label>
                    <input type="text" id="telepon" name="telepon" class="form-control" value="<?= e($profile['telepon'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Resmi</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= e($profile['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="instagram">Akun Instagram</label>
                    <input type="text" id="instagram" name="instagram" class="form-control" value="<?= e($profile['instagram'] ?? '') ?>">
                </div>
            </div>

            <div class="flex items-center gap-3" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>
