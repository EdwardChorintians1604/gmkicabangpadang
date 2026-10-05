<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-body" style="padding: 2.5rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                    <?= $isEdit ? 'Edit Pengguna: ' . e($user['username']) : 'Tambah Pengguna Baru' ?>
                </h1>
                <p class="text-muted" style="font-size: 0.875rem;">
                    Tentukan nama, email, dan hak akses (role) pengguna.
                </p>
            </div>
            <a href="/admin/akun" class="btn btn-outline btn-sm">&larr; Kembali</a>
        </div>

        <form action="<?= $isEdit ? '/admin/akun/' . $user['id'] : '/admin/akun' ?>" method="POST" data-validate>
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" 
                       placeholder="Contoh: yeremia_bpc" 
                       value="<?= e($isEdit ? $user['username'] : old('username')) ?>" 
                       <?= $isEdit ? 'readonly style="background:var(--bg-subtle);"' : 'required' ?>>
                <?php if ($isEdit): ?>
                    <div class="form-help">Username tidak dapat diubah setelah dibuat.</div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" 
                       placeholder="Nama lengkap pejabat atau operator" 
                       value="<?= e($isEdit ? $user['nama_lengkap'] : old('nama_lengkap')) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       placeholder="email@gmkicabangpadang.or.id" 
                       value="<?= e($isEdit ? $user['email'] : old('email')) ?>" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="role">Hak Akses (Role)</label>
                    <?php $currRole = $isEdit ? $user['role'] : old('role', 'operator'); ?>
                    <select id="role" name="role" class="form-control" required>
                        <option value="operator" <?= ($currRole === 'operator') ? 'selected' : '' ?>>Operator Data & Warta</option>
                        <option value="pengawas" <?= ($currRole === 'pengawas') ? 'selected' : '' ?>>Majelis Pengawas (MPPC)</option>
                        <option value="admin" <?= ($currRole === 'admin') ? 'selected' : '' ?>>Administrator Penuh</option>
                    </select>
                </div>

                <?php if ($isEdit): ?>
                    <div class="form-group">
                        <label class="form-label" for="status">Status Akun</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="aktif" <?= ($user['status'] === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= ($user['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktifkan</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Kata Sandi <?= $isEdit ? '(Biarkan kosong jika tidak diubah)' : '' ?></label>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="<?= $isEdit ? 'Masukkan kata sandi baru jika ingin mengganti' : 'Minimal 8 karakter' ?>" 
                       <?= $isEdit ? '' : 'required minlength="8"' ?>>
            </div>

            <div class="flex items-center gap-3" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Simpan Perubahan Pengguna' : 'Buat Pengguna Baru' ?>
                </button>
                <a href="/admin/akun" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
