<!-- Formulir Registrasi & Pemutakhiran Civitas Terdaftar -->
<section class="py-10 lg:py-14 bg-slate-50 min-h-screen">
    <div class="container max-w-4xl">
        <!-- Breadcrumb / Header Navigation -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="/register_for_civitas" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-primary mb-2 transition-colors">
                    <i class="bi bi-arrow-left"></i> Kembali ke Portal Pendaftaran
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Registrasi &amp; Pemutakhiran Civitas Cabang
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Bagi anggota sah yang telah dilantik atau alumni cabang untuk validasi data keanggotaan BPC GMKI Padang.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-300 shadow-xs">
                <i class="bi bi-person-vcard-fill"></i> Jalur Civitas Resmi
            </span>
        </div>

        <div class="card rounded-2xl shadow-sm border border-slate-200 overflow-hidden bg-white">
            <!-- Header Card Banner -->
            <div class="bg-gradient-to-r from-[#071e33] via-[#0d3659] to-[#1b5680] text-white p-6 sm:p-7">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-sky-500/20 text-sky-300 border border-sky-400/40 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="bi bi-person-check-fill text-3xl"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 text-xs font-semibold text-sky-300 mb-1">
                            <i class="bi bi-database-check"></i> Integrasi Pangkalan Data Cabang
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-white leading-tight">Formulir Pemutakhiran Anggota &amp; Civitas</h2>
                        <p class="text-xs text-slate-200 mt-0.5">GMKI Cabang Padang &bull; Pangkalan Data Resmi Pengurus BPC</p>
                    </div>
                </div>
            </div>

            <form action="/register_for_civitas/member" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6" data-validate>
                <?= csrf_field() ?>

                <!-- Widget ID Anggota / Civitas (Algoritma Superadmin) -->
                <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-sky-50 to-blue-50/70 border border-sky-200/80">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-sky-700 text-white text-xs font-bold flex items-center justify-center">ID</span>
                                <label class="text-xs sm:text-sm font-bold text-slate-800" for="nim">
                                    ID Anggota / Civitas
                                </label>
                            </div>
                            <p class="text-[11px] sm:text-xs text-slate-500 mt-1">
                                Jika sudah memiliki ID Anggota tetap (misal: <code>2023-02-DKH</code>), Anda dapat memasukkannya langsung. Jika belum, sistem akan menghitungnya otomatis.
                            </p>
                        </div>
                        <div class="sm:text-right">
                            <input type="text" id="nim" name="nim" class="form-control font-mono font-bold text-center sm:text-right text-base text-sky-950 bg-white border-sky-300 shadow-xs"
                                placeholder="Contoh: 2023-02-DKH"
                                value="<?= e(old('nim')) ?>" style="max-width: 220px; display: inline-block;">
                        </div>
                    </div>
                </div>

                <!-- Bagian 1: Identitas Civitas -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-sky-700 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-badge"></i> Data Identitas Civitas
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group sm:col-span-2">
                            <label class="form-label font-semibold text-slate-700" for="nama_lengkap">
                                Nama Lengkap Sesuai KTP / Ijazah <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                placeholder="Contoh: Daniel Kristian Hutapea"
                                value="<?= e(old('nama_lengkap')) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="jenis_kelamin">
                                Jenis Kelamin <span class="text-rose-500">*</span>
                            </label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" <?= (old('jenis_kelamin') === 'L') ? 'selected' : '' ?>>Laki-laki (L)</option>
                                <option value="P" <?= (old('jenis_kelamin') === 'P') ? 'selected' : '' ?>>Perempuan (P)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="tempat_lahir">
                                Tempat Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                placeholder="Kota / Kabupaten tempat lahir"
                                value="<?= e(old('tempat_lahir')) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="tanggal_lahir">
                                Tanggal Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                                value="<?= e(old('tanggal_lahir')) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="asal_civitas">
                                Kota / Daerah Asal
                            </label>
                            <input type="text" id="asal_civitas" name="asal_civitas" class="form-control"
                                placeholder="Contoh: Mentawai, Bengkulu, Solok, Medan, Sidikalang, Jakarta..."
                                value="<?= e(old('asal_civitas')) ?>">
                            <div class="text-[11px] text-slate-400 mt-1">Kota atau daerah asal civitas / anggota.</div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Bagian 2: Kontak & Domisili -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-sky-700 mb-4 flex items-center gap-2">
                        <i class="bi bi-telephone"></i> Kontak &amp; Domisili di Padang
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="telepon">
                                Nomor WhatsApp / HP <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" id="telepon" name="telepon" class="form-control"
                                placeholder="08xxxxxxxxxx"
                                value="<?= e(old('telepon')) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="email">
                                Alamat Email Aktif <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="nama@email.com"
                                value="<?= e(old('email')) ?>" required>
                        </div>

                        <div class="form-group sm:col-span-2">
                            <label class="form-label font-semibold text-slate-700" for="alamat_padang">
                                Alamat Tempat Tinggal di Padang
                            </label>
                            <textarea id="alamat_padang" name="alamat_padang" rows="2" class="form-control"
                                placeholder="Jalan, kelurahan, kecamatan di Kota Padang..."><?= e(old('alamat_padang')) ?></textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Bagian 3: Keanggotaan & Kaderisasi (Sesuai Skema Superadmin) -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-sky-700 mb-4 flex items-center gap-2">
                        <i class="bi bi-award"></i> Status Keanggotaan &amp; Komisariat
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="tahun_maperca">
                                Tahun Pelantikan Maperca <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="tahun_maperca" name="tahun_maperca" class="form-control font-bold"
                                value="<?= e(old('tahun_maperca', date('Y') - 1)) ?>" min="1990" max="<?= date('Y') ?>" required>
                            <div class="text-[11px] text-slate-400 mt-1">Tahun saat pertama kali dilantik pada Maperca.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="status_keanggotaan">
                                Status Keanggotaan <span class="text-rose-500">*</span>
                            </label>
                            <?php $sk = old('status_keanggotaan', 'Aktif'); ?>
                            <select id="status_keanggotaan" name="status_keanggotaan" class="form-control" required>
                                <option value="Aktif" <?= ($sk === 'Aktif') ? 'selected' : '' ?>>Aktif &mdash; Mahasiswa aktif berkegiatan</option>
                                <option value="Pasif" <?= ($sk === 'Pasif') ? 'selected' : '' ?>>Pasif &mdash; Jarang / tidak aktif</option>
                                <option value="Alumni" <?= ($sk === 'Alumni') ? 'selected' : '' ?>>Alumni &mdash; Sudah wisuda / tamat kuliah</option>
                                <option value="Senior" <?= ($sk === 'Senior') ? 'selected' : '' ?>>Senior &mdash; Anggota senior organisasi</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="tingkat_kaderisasi">
                                Jenjang Kaderisasi
                            </label>
                            <?php $tk = old('tingkat_kaderisasi', 'Anggota'); ?>
                            <select id="tingkat_kaderisasi" name="tingkat_kaderisasi" class="form-control">
                                <option value="Anggota" <?= ($tk === 'Anggota') ? 'selected' : '' ?>>Anggota Biasa (Maperca)</option>
                                <option value="KK" <?= ($tk === 'KK') ? 'selected' : '' ?>>Kader Khusus (LK I / LK II)</option>
                                <option value="Alumni" <?= ($tk === 'Alumni') ? 'selected' : '' ?>>Alumni Cabang</option>
                            </select>
                        </div>
                    </div>

                    <!-- Keanggotaan Komisariat (Algoritma Dinamis Superadmin) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="anggota_komisariat">
                                Apakah pernah menjadi anggota komisariat? <span class="text-rose-500">*</span>
                            </label>
                            <?php $isAnggotaKom = (int)old('anggota_komisariat', 0); ?>
                            <select id="anggota_komisariat" name="anggota_komisariat" class="form-control" required>
                                <option value="0" <?= $isAnggotaKom === 0 ? 'selected' : '' ?>>Tidak pernah sama sekali</option>
                                <option value="1" <?= $isAnggotaKom === 1 ? 'selected' : '' ?>>Ya, anggota komisariat</option>
                            </select>
                        </div>

                        <div class="form-group" id="komisariat-wrapper" style="<?= $isAnggotaKom === 1 ? '' : 'display:none;' ?>">
                            <label class="form-label font-semibold text-slate-700" for="komisariat">
                                Pilih Komisariat Kampus <span class="text-rose-500">*</span>
                            </label>
                            <select id="komisariat" name="komisariat" class="form-control">
                                <option value="">-- Pilih Komisariat Terdaftar --</option>
                                <?php foreach (($komisariatNames ?? []) as $kn): ?>
                                    <option value="<?= e($kn) ?>" <?= (old('komisariat') === $kn) ? 'selected' : '' ?>><?= e($kn) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-[11px] text-slate-400 mt-1">Daftar komisariat aktif resmi BPC GMKI Cabang Padang.</div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Bagian 4: Akademik Perguruan Tinggi -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-sky-700 mb-4 flex items-center gap-2">
                        <i class="bi bi-mortarboard"></i> Data Perguruan Tinggi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group sm:col-span-3">
                            <label class="form-label font-semibold text-slate-700" for="perguruan_tinggi">
                                Perguruan Tinggi / Kampus <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="perguruan_tinggi" name="perguruan_tinggi" class="form-control"
                                list="pt-list" placeholder="Ketik atau pilih kampus (Contoh: Universitas Andalas)"
                                value="<?= e(old('perguruan_tinggi', 'Universitas Andalas')) ?>" required>
                            <datalist id="pt-list">
                                <option value="Universitas Andalas">
                                <option value="Universitas Negeri Padang">
                                <option value="Universitas Bung Hatta">
                                <option value="UIN Imam Bonjol Padang">
                                <option value="UPI YPTK Padang">
                                <option value="Politeknik Negeri Padang">
                                <option value="Universitas Dharma Andalas">
                                <option value="Universitas Baiturrahmah">
                                <?php foreach (($perguruanTinggiList ?? []) as $pt): ?>
                                    <option value="<?= e($pt) ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="fakultas">
                                Fakultas
                            </label>
                            <input type="text" id="fakultas" name="fakultas" class="form-control"
                                placeholder="Contoh: Fakultas Hukum"
                                value="<?= e(old('fakultas')) ?>">
                        </div>

                        <div class="form-group sm:col-span-2">
                            <label class="form-label font-semibold text-slate-700" for="jurusan">
                                Jurusan / Program Studi
                            </label>
                            <input type="text" id="jurusan" name="jurusan" class="form-control"
                                placeholder="Contoh: Ilmu Hukum"
                                value="<?= e(old('jurusan')) ?>">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Bagian 5: Foto & Keterangan -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-sky-700 mb-4 flex items-center gap-2">
                        <i class="bi bi-camera"></i> Foto Diri &amp; Catatan Tambahan
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="foto_anggota">
                                Unggah Foto Profil Civitas
                            </label>
                            <input type="file" id="foto_anggota" name="foto_anggota" class="form-control" accept="image/jpeg,image/png,image/webp">
                            <div class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, atau WebP. Maks 3 MB. Disimpan di private storage terproteksi.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="catatan">
                                Catatan Khusus / Riwayat Tambahan
                            </label>
                            <textarea id="catatan" name="catatan" rows="3" class="form-control"
                                placeholder="Keterangan tambahan seputar keanggotaan, kepengurusan, atau riwayat kaderisasi..."><?= e(old('catatan')) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="pt-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
                    <a href="/register_for_civitas" class="btn btn-outline text-slate-600">
                        &larr; Kembali ke Portal
                    </a>
                    <button type="submit" class="btn btn-primary px-8 py-3.5 font-bold text-white shadow-lg shadow-blue-950/20 inline-flex items-center gap-2">
                        <i class="bi bi-check2-circle"></i>
                        <span>Kirim Pemutakhiran Data Civitas</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Script Realtime Algoritma & Dynamic Toggle Superadmin -->
<script>
(function () {
    const namaInput = document.getElementById('nama_lengkap');
    const tglLahirInput = document.getElementById('tanggal_lahir');
    const thnMaperInput = document.getElementById('tahun_maperca');
    const nimInput = document.getElementById('nim');
    let userEditedNim = false;

    if (nimInput && nimInput.value.trim() !== '') {
        userEditedNim = true;
    }

    if (nimInput) {
        nimInput.addEventListener('input', function () {
            if (nimInput.value.trim() !== '') {
                userEditedNim = true;
            } else {
                userEditedNim = false;
                computePreviewId();
            }
        });
    }

    function computePreviewId() {
        if (!nimInput || userEditedNim) return;
        const nama = (namaInput ? namaInput.value : '').trim();
        const tgl = (tglLahirInput ? tglLahirInput.value : '').trim();
        const thn = (thnMaperInput ? thnMaperInput.value : '').trim() || new Date().getFullYear().toString();

        if (!nama && !tgl) return;

        let yy = '00';
        const matchYear = tgl.match(/^(\d{4})/);
        if (matchYear) {
            yy = matchYear[1].slice(-2);
        }

        let initials = '';
        if (nama) {
            nama.split(/[\s\-]+/).forEach(function (word) {
                const clean = word.replace(/[^a-zA-Z]/g, '');
                if (clean.length > 0) {
                    initials += clean.charAt(0).toUpperCase();
                }
            });
        }
        if (!initials) initials = 'XX';

        nimInput.value = thn + '-' + yy + '-' + initials;
    }

    if (namaInput) namaInput.addEventListener('input', computePreviewId);
    if (tglLahirInput) tglLahirInput.addEventListener('change', computePreviewId);
    if (thnMaperInput) thnMaperInput.addEventListener('input', computePreviewId);
    computePreviewId();

    // Toggle Komisariat Wrapper (Persis Logika Superadmin)
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
