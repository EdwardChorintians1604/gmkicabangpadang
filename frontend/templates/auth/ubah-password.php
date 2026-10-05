<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div class="card-body">
            <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Perbarui Kata Sandi</h2>
            <p class="text-muted" style="font-size: 0.875rem; margin-bottom: 1.5rem;">
                Pastikan Anda menggunakan kata sandi yang kuat dengan kombinasi huruf besar, huruf kecil, angka, dan simbol.
            </p>

            <form action="/ubah-password" method="POST" data-validate>
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="old_password">Kata Sandi Saat Ini</label>
                    <input type="password" id="old_password" name="old_password" class="form-control" 
                           placeholder="Masukkan kata sandi lama Anda" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password">Kata Sandi Baru</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" 
                           placeholder="Minimal 8 karakter" minlength="8" required>
                    <div class="form-help">Minimal 8 karakter kombinasi.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password_confirmation">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" 
                           placeholder="Ulangi kata sandi baru" minlength="8" required>
                </div>

                <div class="flex items-center gap-3" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        Simpan Kata Sandi Baru
                    </button>
                    <a href="/dashboard" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
