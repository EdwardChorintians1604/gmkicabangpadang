<!-- Formulir Registrasi & Pemutakhiran Civitas Terdaftar (Sesuai Format Gambar) -->
<section class="py-10 lg:py-14 bg-slate-50 min-h-screen">
    <div class="container max-w-7xl">
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
                    Silakan isi biodata lengkap untuk pemutakhiran data civitas pada pangkalan data resmi BPC GMKI Cabang Padang.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-300 shadow-xs">
                <i class="bi bi-person-vcard-fill"></i> Jalur Civitas Resmi
            </span>
        </div>

        <!-- Layout 2 Kolom: Kiri Formulir Input, Kanan Live Pratinjau Kartu Sesuai Gambar -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Kiri (7 cols): Formulir Input Terstruktur Sesuai Urutan Gambar -->
            <div class="lg:col-span-7">
                <div class="card rounded-2xl shadow-sm border border-slate-200 overflow-hidden bg-white">
                    <div class="bg-gradient-to-r from-[#071e33] via-[#0d3659] to-[#1b5680] text-white p-5 sm:p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-300 border border-sky-400/40 flex items-center justify-center flex-shrink-0 shadow-inner">
                                <i class="bi bi-person-check-fill text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-white">Formulir Data Civitas GMKI Padang</h2>
                                <p class="text-xs text-slate-200">Pastikan seluruh data diisi dengan akurat sesuai identitas resmi.</p>
                            </div>
                        </div>
                    </div>

                    <form action="/register_for_civitas/member" method="POST" enctype="multipart/form-data" class="p-6 sm:p-7 space-y-5" data-validate>
                        <?= csrf_field() ?>

                        <!-- 1. ID ANGGOTA / CIVITAS (Otomatis / Manual) -->
                        <div class="p-4 rounded-xl bg-sky-50/70 border border-sky-200">
                            <label class="form-label font-bold text-xs uppercase tracking-wider text-sky-900 block mb-1" for="nim">
                                1. ID Anggota / Civitas <span class="text-slate-400 font-normal text-[11px]">(Otomatis: [TahunMaper]-[ThnLahir]-[Inisial])</span>
                            </label>
                            <input type="text" id="nim" name="nim" class="form-control font-mono font-bold text-base text-[#0f3d64] bg-white border-sky-300 shadow-xs"
                                placeholder="Contoh: 2025-05-ABP"
                                value="<?= e(old('nim')) ?>">
                            <div class="text-[11px] text-slate-500 mt-1">
                                Jika belum memiliki ID tetap, kode ini dibentuk otomatis dari <strong>Tahun Maper</strong> + <strong>Tahun Lahir</strong> + <strong>Inisial Nama</strong>.
                            </div>
                        </div>

                        <!-- 2. NAMA LENGKAP -->
                        <div class="form-group">
                            <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="nama_lengkap">
                                2. Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control font-semibold text-slate-900"
                                placeholder="Contoh: Anisa Betsaida Pasaribu"
                                value="<?= e(old('nama_lengkap')) ?>" required>
                        </div>

                        <!-- 3. JENIS KELAMIN -->
                        <div class="form-group">
                            <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="jenis_kelamin">
                                3. Jenis Kelamin <span class="text-rose-500">*</span>
                            </label>
                            <?php $jk = old('jenis_kelamin', 'P'); ?>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control font-medium" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="P" <?= ($jk === 'P') ? 'selected' : '' ?>>Perempuan</option>
                                <option value="L" <?= ($jk === 'L') ? 'selected' : '' ?>>Laki-laki</option>
                            </select>
                        </div>

                        <!-- 4. TEMPAT, TANGGAL LAHIR -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="tempat_lahir">
                                    4a. Tempat Lahir <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                    placeholder="Contoh: Pematangsiantar"
                                    value="<?= e(old('tempat_lahir')) ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="tanggal_lahir">
                                    4b. Tanggal Lahir <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                                    value="<?= e(old('tanggal_lahir')) ?>" required>
                            </div>
                        </div>

                        <!-- 5. PERGURUAN TINGGI -->
                        <div class="form-group">
                            <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="perguruan_tinggi">
                                5. Perguruan Tinggi <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="perguruan_tinggi" name="perguruan_tinggi" class="form-control font-bold text-slate-900"
                                list="pt-list" placeholder="Contoh: Universitas Negeri Padang"
                                value="<?= e(old('perguruan_tinggi', 'Universitas Negeri Padang')) ?>" required>
                            <datalist id="pt-list">
                                <option value="Universitas Negeri Padang">
                                <option value="Universitas Andalas">
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

                        <!-- 6. FAKULTAS / JURUSAN -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="fakultas">
                                    6a. Fakultas <span class="text-slate-400 font-normal">(Contoh: FISIP)</span>
                                </label>
                                <input type="text" id="fakultas" name="fakultas" class="form-control"
                                    placeholder="Contoh: FISIP"
                                    value="<?= e(old('fakultas')) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="jurusan">
                                    6b. Jurusan / Program Studi <span class="text-slate-400 font-normal">(Contoh: Pendidikan Matematika)</span>
                                </label>
                                <input type="text" id="jurusan" name="jurusan" class="form-control"
                                    placeholder="Contoh: Pendidikan Matematika"
                                    value="<?= e(old('jurusan')) ?>">
                            </div>
                        </div>

                        <!-- 7. KOMISARIAT -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-800 block mb-1" for="anggota_komisariat">
                                7. Status Keanggotaan Komisariat <span class="text-rose-500">*</span>
                            </label>
                            <?php $isAnggotaKom = (int)old('anggota_komisariat', 0); ?>
                            <select id="anggota_komisariat" name="anggota_komisariat" class="form-control" required>
                                <option value="0" <?= $isAnggotaKom === 0 ? 'selected' : '' ?>>Tidak pernah sama sekali</option>
                                <option value="1" <?= $isAnggotaKom === 1 ? 'selected' : '' ?>>Ya, anggota komisariat</option>
                            </select>

                            <div id="komisariat-wrapper" style="<?= $isAnggotaKom === 1 ? '' : 'display:none;' ?>">
                                <label class="form-label font-semibold text-xs text-slate-700" for="komisariat">
                                    Pilih Nama Komisariat Kampus <span class="text-rose-500">*</span>
                                </label>
                                <select id="komisariat" name="komisariat" class="form-control">
                                    <option value="">-- Pilih Komisariat Terdaftar --</option>
                                    <?php foreach (($komisariatNames ?? []) as $kn): ?>
                                        <option value="<?= e($kn) ?>" <?= (old('komisariat') === $kn) ? 'selected' : '' ?>><?= e($kn) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- 8. TAHUN MAPERCA & 9. STATUS KEANGGOTAAN -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="tahun_maperca">
                                    8. Tahun Maperca <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" id="tahun_maperca" name="tahun_maperca" class="form-control font-bold"
                                    placeholder="Contoh: 2025"
                                    value="<?= e(old('tahun_maperca', date('Y'))) ?>" min="1990" max="<?= date('Y') ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="status_keanggotaan">
                                    9. Status Keanggotaan <span class="text-rose-500">*</span>
                                </label>
                                <?php $sk = old('status_keanggotaan', 'Aktif'); ?>
                                <select id="status_keanggotaan" name="status_keanggotaan" class="form-control font-semibold" required>
                                    <option value="Aktif" <?= ($sk === 'Aktif') ? 'selected' : '' ?>>Aktif (Mahasiswa aktif berkegiatan)</option>
                                    <option value="Pasif" <?= ($sk === 'Pasif') ? 'selected' : '' ?>>Pasif (Tercatat anggota, tetapi jarang/tidak aktif)</option>
                                    <option value="Alumni" <?= ($sk === 'Alumni') ? 'selected' : '' ?>>Alumni (Sudah wisuda / tamat kuliah)</option>
                                    <option value="Senior" <?= ($sk === 'Senior') ? 'selected' : '' ?>>Senior (Anggota senior sesuai ketentuan organisasi)</option>
                                </select>
                            </div>
                        </div>

                        <!-- 10. TELEPON / WA & 11. EMAIL -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="telepon">
                                    10. Telepon / WA <span class="text-rose-500">*</span>
                                </label>
                                <input type="tel" id="telepon" name="telepon" class="form-control font-mono"
                                    placeholder="Contoh: 082134670990"
                                    value="<?= e(old('telepon')) ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="email">
                                    11. Email <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Contoh: anisabetsaida90@gmail.com"
                                    value="<?= e(old('email')) ?>" required>
                            </div>
                        </div>

                        <!-- 12. ALAMAT DI PADANG -->
                        <div class="form-group">
                            <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="alamat_padang">
                                12. Alamat di Padang
                            </label>
                            <textarea id="alamat_padang" name="alamat_padang" rows="2" class="form-control"
                                placeholder="Contoh: Jln. Dr. Soetomo..."><?= e(old('alamat_padang')) ?></textarea>
                        </div>

                        <!-- 13. ASAL CIVITAS -->
                        <div class="form-group">
                            <label class="form-label font-bold text-xs uppercase tracking-wider text-slate-700" for="asal_civitas">
                                13. Asal Civitas <span class="text-slate-400 font-normal">(Kota / Daerah Asal)</span>
                            </label>
                            <input type="text" id="asal_civitas" name="asal_civitas" class="form-control"
                                placeholder="Contoh: Pematangsiantar, Mentawai, Bengkulu, Solok, Medan, Sidikalang, Jakarta..."
                                value="<?= e(old('asal_civitas')) ?>">
                            <div class="text-[11px] text-slate-400 mt-1">Jika dikosongkan, sistem akan otomatis menggunakan kota tempat lahir.</div>
                        </div>

                        <!-- 14. PASFOTO & KETERANGAN (Opsional) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-200">
                            <div class="form-group">
                                <label class="form-label font-semibold text-xs text-slate-700" for="foto_anggota">
                                    Unggah Pasfoto Diri (Opsional)
                                </label>
                                <input type="file" id="foto_anggota" name="foto_anggota" class="form-control" accept="image/jpeg,image/png,image/webp">
                                <div class="text-[10px] text-slate-400 mt-1">JPG, PNG, atau WebP. Maks 3 MB. Disimpan di private storage.</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label font-semibold text-xs text-slate-700" for="catatan">
                                    Catatan Tambahan (Opsional)
                                </label>
                                <input type="text" id="catatan" name="catatan" class="form-control"
                                    placeholder="Catatan keaktifan atau keterangan khusus..."
                                    value="<?= e(old('catatan')) ?>">
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="pt-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
                            <a href="/register_for_civitas" class="btn btn-outline text-slate-600">
                                &larr; Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-8 py-3.5 font-bold text-white shadow-lg shadow-blue-950/20 inline-flex items-center gap-2">
                                <i class="bi bi-check2-circle"></i>
                                <span>Kirim Pemutakhiran Data Civitas</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan (5 cols): Live Interactive Card Preview (PERSIS SEPERTI GAMBAR DI SCREENSHOT) -->
            <div class="lg:col-span-5 sticky top-24">
                <div class="card rounded-2xl shadow-sm border border-slate-200 overflow-hidden bg-white">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs font-bold text-amber-300">
                            <i class="bi bi-eye-fill"></i>
                            <span>Pratinjau Profil Civitas (Live)</span>
                        </div>
                        <span class="text-[10px] text-slate-400">Persis Sesuai Basis Data</span>
                    </div>

                    <div class="p-5 sm:p-6">
                        <!-- Badge Status (Persis Gambar: Pojok Kiri Atas) -->
                        <div class="mb-3">
                            <span id="preview-badge-status" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span id="badge-text">Anggota Aktif</span>
                            </span>
                        </div>

                        <!-- Tabel Detail Pratinjau (Persis Sama dengan Screenshot User) -->
                        <div class="border border-slate-200 rounded-xl overflow-hidden text-xs">
                            <table class="w-full text-left divide-y divide-slate-100">
                                <tbody class="divide-y divide-slate-100">
                                    <!-- Baris 1: ID ANGGOTA / CIVITAS -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 w-2/5 bg-slate-50/70">
                                            ID ANGGOTA / CIVITAS
                                        </th>
                                        <td class="py-2.5 px-3">
                                            <code id="preview-nim" class="font-bold text-[#0f3d64] bg-[#eef4f9] px-2 py-0.5 rounded border border-[#d4e2ed] text-xs">
                                                <?= e(old('nim', '2025-05-ABP')) ?>
                                            </code>
                                        </td>
                                    </tr>

                                    <!-- Baris 2: NAMA LENGKAP -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            NAMA LENGKAP
                                        </th>
                                        <td id="preview-nama" class="py-2.5 px-3 font-bold text-slate-900 text-sm">
                                            <?= e(old('nama_lengkap', 'Anisa Betsaida Pasaribu')) ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 3: JENIS KELAMIN -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            JENIS KELAMIN
                                        </th>
                                        <td id="preview-jk" class="py-2.5 px-3 text-slate-700">
                                            <?= (old('jenis_kelamin') === 'L') ? 'Laki-laki' : 'Perempuan' ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 4: TEMPAT, TANGGAL LAHIR -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            TEMPAT, TANGGAL LAHIR
                                        </th>
                                        <td id="preview-ttl" class="py-2.5 px-3 text-slate-700">
                                            <?= e(old('tempat_lahir', 'Pematangsiantar')) ?>, <?= old('tanggal_lahir') ? format_date(old('tanggal_lahir'), 'd F Y') : '14 April 2005' ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 5: PERGURUAN TINGGI -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            PERGURUAN TINGGI
                                        </th>
                                        <td id="preview-pt" class="py-2.5 px-3 font-bold text-slate-900">
                                            <?= e(old('perguruan_tinggi', 'Universitas Negeri Padang')) ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 6: FAKULTAS / JURUSAN -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            FAKULTAS / JURUSAN
                                        </th>
                                        <td id="preview-fak-jur" class="py-2.5 px-3 text-slate-700">
                                            <?= e(old('fakultas', 'FISIP')) ?> / <?= e(old('jurusan', 'Pendidikan Matematika')) ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 7: KOMISARIAT -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            KOMISARIAT
                                        </th>
                                        <td id="preview-komisariat" class="py-2.5 px-3 text-slate-700">
                                            <?= !empty(old('komisariat')) ? e(old('komisariat')) : 'Tidak pernah sama sekali' ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 8: TAHUN MAPERCA -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            TAHUN MAPERCA
                                        </th>
                                        <td id="preview-thn-maper" class="py-2.5 px-3 text-slate-800 font-semibold">
                                            <?= e(old('tahun_maperca', '2025')) ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 9: STATUS KEANGGOTAAN -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            STATUS KEANGGOTAAN
                                        </th>
                                        <td id="preview-status-keanggotaan" class="py-2.5 px-3">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                Aktif (Mahasiswa aktif berkegiatan)
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Baris 10: TELEPON / WA -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            TELEPON / WA
                                        </th>
                                        <td id="preview-telepon" class="py-2.5 px-3 font-mono text-slate-800">
                                            <?= e(old('telepon', '082134670990')) ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 11: EMAIL -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            EMAIL
                                        </th>
                                        <td id="preview-email" class="py-2.5 px-3 text-slate-800 break-all">
                                            <?= e(old('email', 'anisabetsaida90@gmail.com')) ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 12: ALAMAT DI PADANG -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            ALAMAT DI PADANG
                                        </th>
                                        <td id="preview-alamat" class="py-2.5 px-3 text-slate-800">
                                            <?= e(old('alamat_padang', 'Jln. Dr. Soetomo')) ?>
                                        </td>
                                    </tr>

                                    <!-- Baris 13: ASAL CIVITAS -->
                                    <tr>
                                        <th class="py-2.5 px-3 font-bold uppercase text-[10px] text-slate-500 bg-slate-50/70">
                                            ASAL CIVITAS
                                        </th>
                                        <td id="preview-asal" class="py-2.5 px-3 text-slate-800 font-medium">
                                            <?= e(old('asal_civitas', 'Pematangsiantar')) ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 p-3 rounded-lg bg-sky-50 text-sky-900 text-[11px] flex items-center gap-2 border border-sky-200">
                            <i class="bi bi-info-circle-fill text-sky-600 flex-shrink-0"></i>
                            <span>Setelah formulir dikirim, bukti pendaftaran resmi di atas dapat langsung dicetak atau diunduh.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script Sinkronisasi Real-Time Form ke Live Card Preview -->
<script>
(function () {
    const namaInput = document.getElementById('nama_lengkap');
    const jkSelect = document.getElementById('jenis_kelamin');
    const tglLahirInput = document.getElementById('tanggal_lahir');
    const tmpLahirInput = document.getElementById('tempat_lahir');
    const ptInput = document.getElementById('perguruan_tinggi');
    const fakInput = document.getElementById('fakultas');
    const jurInput = document.getElementById('jurusan');
    const thnMaperInput = document.getElementById('tahun_maperca');
    const statusSelect = document.getElementById('status_keanggotaan');
    const telInput = document.getElementById('telepon');
    const emailInput = document.getElementById('email');
    const almtInput = document.getElementById('alamat_padang');
    const asalInput = document.getElementById('asal_civitas');
    const nimInput = document.getElementById('nim');
    let userEditedNim = false;

    // Elemen Live Preview
    const pNim = document.getElementById('preview-nim');
    const pNama = document.getElementById('preview-nama');
    const pJk = document.getElementById('preview-jk');
    const pTtl = document.getElementById('preview-ttl');
    const pPt = document.getElementById('preview-pt');
    const pFakJur = document.getElementById('preview-fak-jur');
    const pKom = document.getElementById('preview-komisariat');
    const pThnMaper = document.getElementById('preview-thn-maper');
    const pStatus = document.getElementById('preview-status-keanggotaan');
    const pBadgeText = document.getElementById('badge-text');
    const pBadge = document.getElementById('preview-badge-status');
    const pTel = document.getElementById('preview-telepon');
    const pEmail = document.getElementById('preview-email');
    const pAlmt = document.getElementById('preview-alamat');
    const pAsal = document.getElementById('preview-asal');

    if (nimInput && nimInput.value.trim() !== '') {
        userEditedNim = true;
    }

    if (nimInput) {
        nimInput.addEventListener('input', function () {
            if (nimInput.value.trim() !== '') {
                userEditedNim = true;
                if (pNim) pNim.textContent = nimInput.value.trim();
            } else {
                userEditedNim = false;
                updateAll();
            }
        });
    }

    function formatTglIndo(isoDate) {
        if (!isoDate) return '';
        const parts = isoDate.split('-');
        if (parts.length !== 3) return isoDate;
        const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const bIdx = parseInt(parts[1], 10) - 1;
        return parseInt(parts[2], 10) + ' ' + (bulan[bIdx] || '') + ' ' + parts[0];
    }

    function updateAll() {
        const nama = (namaInput ? namaInput.value : '').trim();
        const tgl = (tglLahirInput ? tglLahirInput.value : '').trim();
        const tmp = (tmpLahirInput ? tmpLahirInput.value : '').trim();
        const thn = (thnMaperInput ? thnMaperInput.value : '').trim() || new Date().getFullYear().toString();
        const pt = (ptInput ? ptInput.value : '').trim() || 'Universitas Negeri Padang';
        const fak = (fakInput ? fakInput.value : '').trim();
        const jur = (jurInput ? jurInput.value : '').trim();
        const jk = jkSelect ? jkSelect.value : 'P';
        const tel = (telInput ? telInput.value : '').trim() || '-';
        const email = (emailInput ? emailInput.value : '').trim() || '-';
        const almt = (almtInput ? almtInput.value : '').trim() || '-';
        const asal = (asalInput ? asalInput.value : '').trim() || tmp || '-';

        // Hitung ID Otomatis
        if (!userEditedNim && nimInput) {
            let yy = '00';
            const matchYear = tgl.match(/^(\d{4})/);
            if (matchYear) yy = matchYear[1].slice(-2);

            let initials = '';
            if (nama) {
                nama.split(/[\s\-]+/).forEach(function (word) {
                    const clean = word.replace(/[^a-zA-Z]/g, '');
                    if (clean.length > 0) initials += clean.charAt(0).toUpperCase();
                });
            }
            if (!initials) initials = 'ABP';

            const computed = thn + '-' + yy + '-' + initials;
            nimInput.value = computed;
            if (pNim) pNim.textContent = computed;
        } else if (pNim && nimInput) {
            pNim.textContent = nimInput.value || thn + '-05-ABP';
        }

        if (pNama) pNama.textContent = nama || 'Anisa Betsaida Pasaribu';
        if (pJk) pJk.textContent = jk === 'L' ? 'Laki-laki' : 'Perempuan';

        const tglFormatted = formatTglIndo(tgl);
        if (pTtl) {
            if (tmp && tglFormatted) pTtl.textContent = tmp + ', ' + tglFormatted;
            else if (tmp) pTtl.textContent = tmp;
            else if (tglFormatted) pTtl.textContent = tglFormatted;
            else pTtl.textContent = 'Pematangsiantar, 14 April 2005';
        }

        if (pPt) pPt.textContent = pt;
        if (pFakJur) {
            const f = fak || 'FISIP';
            const j = jur || 'Pendidikan Matematika';
            pFakJur.textContent = f + ' / ' + j;
        }

        if (pThnMaper) pThnMaper.textContent = thn;
        if (pTel) pTel.textContent = tel;
        if (pEmail) pEmail.textContent = email;
        if (pAlmt) pAlmt.textContent = almt;
        if (pAsal) pAsal.textContent = asal;

        // Status Keanggotaan
        const sk = statusSelect ? statusSelect.value : 'Aktif';
        let skLabel = 'Aktif (Mahasiswa aktif berkegiatan)';
        let badgeColor = 'bg-emerald-50 text-emerald-800 border-emerald-200';
        let dotColor = 'bg-emerald-500';
        let shortStatus = 'Anggota Aktif';

        if (sk === 'Pasif') {
            skLabel = 'Pasif (Tercatat anggota, tetapi jarang/tidak aktif)';
            badgeColor = 'bg-amber-50 text-amber-800 border-amber-200';
            dotColor = 'bg-amber-500';
            shortStatus = 'Pasif (Jarang Aktif)';
        } else if (sk === 'Alumni') {
            skLabel = 'Alumni (Sudah wisuda / tamat kuliah)';
            badgeColor = 'bg-blue-50 text-blue-800 border-blue-200';
            dotColor = 'bg-blue-500';
            shortStatus = 'Alumni Cabang';
        } else if (sk === 'Senior') {
            skLabel = 'Senior (Anggota senior sesuai ketentuan organisasi)';
            badgeColor = 'bg-purple-50 text-purple-800 border-purple-200';
            dotColor = 'bg-purple-500';
            shortStatus = 'Senior Cabang';
        }

        if (pStatus) {
            pStatus.innerHTML = '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold ' + badgeColor + ' border">' + skLabel + '</span>';
        }
        if (pBadgeText) pBadgeText.textContent = shortStatus;
        if (pBadge) pBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold ' + badgeColor + ' border';
    }

    // Pasang Event Listeners
    [namaInput, tmpLahirInput, ptInput, fakInput, jurInput, thnMaperInput, telInput, emailInput, almtInput, asalInput].forEach(function (el) {
        if (el) el.addEventListener('input', updateAll);
    });
    [jkSelect, tglLahirInput, statusSelect].forEach(function (el) {
        if (el) el.addEventListener('change', updateAll);
    });

    // Komisariat Handler
    const anggotaKomisariatSelect = document.getElementById('anggota_komisariat');
    const komisariatWrapper = document.getElementById('komisariat-wrapper');
    const komisariatSelect = document.getElementById('komisariat');

    function updateKomisariat() {
        if (!anggotaKomisariatSelect) return;
        if (anggotaKomisariatSelect.value === '1') {
            if (komisariatWrapper) komisariatWrapper.style.display = '';
            if (komisariatSelect) {
                komisariatSelect.required = true;
                const komVal = komisariatSelect.value.trim();
                if (pKom) pKom.textContent = komVal || 'Pilih Komisariat';
            }
        } else {
            if (komisariatWrapper) komisariatWrapper.style.display = 'none';
            if (komisariatSelect) {
                komisariatSelect.required = false;
                komisariatSelect.value = '';
            }
            if (pKom) pKom.textContent = 'Tidak pernah sama sekali';
        }
    }

    if (anggotaKomisariatSelect) {
        anggotaKomisariatSelect.addEventListener('change', updateKomisariat);
    }
    if (komisariatSelect) {
        komisariatSelect.addEventListener('change', function () {
            if (pKom) pKom.textContent = komisariatSelect.value.trim() || 'Tidak pernah sama sekali';
        });
    }

    updateAll();
    updateKomisariat();
})();
</script>
