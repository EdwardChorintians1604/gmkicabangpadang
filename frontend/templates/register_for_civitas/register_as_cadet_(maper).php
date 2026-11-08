<!-- Formulir Pendaftaran Calon Anggota Baru (Maperca) -->
<section class="py-10 lg:py-14 bg-slate-50 min-h-screen">
    <div class="container max-w-4xl">
        <!-- Breadcrumb / Header Navigation -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="/register_for_civitas" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-primary mb-2 transition-colors">
                    <i class="bi bi-arrow-left"></i> Kembali ke Portal Pendaftaran
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Pendaftaran Calon Anggota Baru (Maperca)
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Silakan isi biodata lengkap untuk pendaftaran Masa Perkenalan (Maperca) GMKI Cabang Padang.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 shadow-xs">
                <i class="bi bi-mortarboard-fill"></i> Jalur Calon Anggota (Maper)
            </span>
        </div>

        <div class="card rounded-2xl shadow-sm border border-slate-200 overflow-hidden bg-white">
            <!-- Header Card Banner -->
            <div class="bg-gradient-to-r from-[#071e33] via-[#0f3d64] to-[#1e5687] text-white p-6 sm:p-7">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-300 border border-amber-400/40 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="bi bi-mortarboard-fill text-3xl"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-300 mb-1">
                            <i class="bi bi-lightning-charge-fill"></i> Registrasi Mandiri Online
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-white leading-tight">Formulir Peserta Masa Perkenalan (Maperca)</h2>
                        <p class="text-xs text-slate-200 mt-0.5">GMKI Cabang Padang &bull; Medan Layan: Gereja, Perguruan Tinggi, dan Masyarakat</p>
                    </div>
                </div>
            </div>

            <form action="/register_for_civitas/maperca" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6" data-validate>
                <?= csrf_field() ?>

                <!-- Widget Live Generator ID Calon Anggota (Algoritma Superadmin) -->
                <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50/70 border border-blue-200/80">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center">ID</span>
                                <label class="text-xs sm:text-sm font-bold text-slate-800" for="nim">
                                    ID Calon Anggota (Kalkulasi Otomatis)
                                </label>
                            </div>
                            <p class="text-[11px] sm:text-xs text-slate-500 mt-1">
                                Algoritma standar GMKI Padang: <code>[TahunMaper]-[2DigitThnLahir]-[InisialNama]</code>
                            </p>
                        </div>
                        <div class="sm:text-right">
                            <input type="text" id="nim" name="nim" class="form-control font-mono font-bold text-center sm:text-right text-base text-blue-900 bg-white border-blue-300 shadow-xs"
                                placeholder="Contoh: 2025-04-EPS"
                                value="<?= e(old('nim')) ?>" readonly style="max-width: 200px; display: inline-block;">
                        </div>
                    </div>
                </div>

                <!-- Bagian 1: Identitas Pribadi -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-badge"></i> Data Identitas Calon Anggota
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group sm:col-span-2">
                            <label class="form-label font-semibold text-slate-700" for="nama_lengkap">
                                Nama Lengkap Sesuai KTP / Ijazah <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                placeholder="Contoh: Efraim Pratama Siregar"
                                value="<?= e(old('nama_lengkap')) ?>" required>
                            <div class="text-[11px] text-slate-400 mt-1">Inisial nama akan dipakai secara otomatis untuk pembentukan ID Anggota.</div>
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
                            <div class="text-[11px] text-slate-400 mt-1">Tahun lahir menentukan 2 digit angka pada ID Anggota.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="asal_civitas">
                                Kota / Daerah Asal
                            </label>
                            <input type="text" id="asal_civitas" name="asal_civitas" class="form-control"
                                placeholder="Contoh: Mentawai, Solok, Bengkulu, Medan, Nias, Sidikalang..."
                                value="<?= e(old('asal_civitas')) ?>">
                            <div class="text-[11px] text-slate-400 mt-1">Daerah asal domisili keluarga / kampung halaman.</div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Bagian 2: Kontak & Domisili di Padang -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4 flex items-center gap-2">
                        <i class="bi bi-telephone"></i> Kontak &amp; Tempat Tinggal di Padang
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="telepon">
                                Nomor WhatsApp Aktif <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" id="telepon" name="telepon" class="form-control"
                                placeholder="08xxxxxxxxxx"
                                value="<?= e(old('telepon')) ?>" required>
                            <div class="text-[11px] text-slate-400 mt-1">Wajib nomor WhatsApp aktif untuk dimasukkan ke grup koordinasi Maperca oleh panitia.</div>
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
                                Alamat Tempat Tinggal / Kost di Padang
                            </label>
                            <textarea id="alamat_padang" name="alamat_padang" rows="2" class="form-control"
                                placeholder="Jalan, kelurahan, kecamatan di Kota Padang..."><?= e(old('alamat_padang')) ?></textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Bagian 3: Data Akademik Kampus -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4 flex items-center gap-2">
                        <i class="bi bi-mortarboard"></i> Data Perguruan Tinggi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group sm:col-span-3">
                            <label class="form-label font-semibold text-slate-700" for="perguruan_tinggi">
                                Perguruan Tinggi / Kampus di Padang <span class="text-rose-500">*</span>
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
                                <option value="Politeknik ATI Padang">
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
                                placeholder="Contoh: Fakultas Teknik"
                                value="<?= e(old('fakultas')) ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="jurusan">
                                Jurusan / Program Studi
                            </label>
                            <input type="text" id="jurusan" name="jurusan" class="form-control"
                                placeholder="Contoh: Teknik Mesin"
                                value="<?= e(old('jurusan')) ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="tahun_maperca">
                                Tahun Mengikuti Maperca <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="tahun_maperca" name="tahun_maperca" class="form-control font-bold"
                                value="<?= e(old('tahun_maperca', date('Y'))) ?>" min="2000" max="<?= date('Y') + 1 ?>" required>
                            <div class="text-[11px] text-slate-400 mt-1">4 digit awal pada ID Anggota.</div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Bagian 4: Pasfoto & Motivasi -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4 flex items-center gap-2">
                        <i class="bi bi-camera"></i> Pasfoto Diri &amp; Catatan Motivasi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="foto_anggota">
                                Unggah Pasfoto Diri
                            </label>
                            <input type="file" id="foto_anggota" name="foto_anggota" class="form-control" accept="image/jpeg,image/png,image/webp">
                            <div class="text-[11px] text-slate-400 mt-1">
                                Format: JPG, PNG, atau WebP. Ukuran maksimal 3 MB. Disimpan aman dalam storage privat cabang.
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-semibold text-slate-700" for="catatan">
                                Motivasi / Alasan Bergabung ke GMKI
                            </label>
                            <textarea id="catatan" name="catatan" rows="3" class="form-control"
                                placeholder="Tuliskan harapan atau alasan Anda ingin bergabung dalam persekutuan dan kaderisasi GMKI Padang..."><?= e(old('catatan')) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="pt-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
                    <a href="/register_for_civitas" class="btn btn-outline text-slate-600">
                        &larr; Kembali ke Portal
                    </a>
                    <button type="submit" class="btn btn-secondary px-8 py-3.5 font-bold text-white shadow-lg shadow-amber-950/20 inline-flex items-center gap-2">
                        <i class="bi bi-send-check-fill"></i>
                        <span>Kirim Pendaftaran Calon Anggota</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Script Realtime Algoritma ID Anggota Superadmin -->
<script>
(function () {
    const namaInput = document.getElementById('nama_lengkap');
    const tglLahirInput = document.getElementById('tanggal_lahir');
    const thnMaperInput = document.getElementById('tahun_maperca');
    const nimInput = document.getElementById('nim');

    function computePreviewId() {
        if (!nimInput) return;
        const nama = (namaInput ? namaInput.value : '').trim();
        const tgl = (tglLahirInput ? tglLahirInput.value : '').trim();
        const thn = (thnMaperInput ? thnMaperInput.value : '').trim() || new Date().getFullYear().toString();

        if (!nama && !tgl) {
            nimInput.value = thn + '-00-XXX';
            return;
        }

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
        if (!initials) initials = 'XXX';

        nimInput.value = thn + '-' + yy + '-' + initials;
    }

    if (namaInput) namaInput.addEventListener('input', computePreviewId);
    if (tglLahirInput) tglLahirInput.addEventListener('change', computePreviewId);
    if (thnMaperInput) thnMaperInput.addEventListener('input', computePreviewId);
    computePreviewId();
})();
</script>
