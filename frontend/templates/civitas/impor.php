<?php
/**
 * Template Impor Data Civitas / Maperca Massal (CSV & Excel XLSX)
 */
$isMaperca = ($kelompok ?? 'anggota') === 'maperca';
$base = $basePath ?? ($isMaperca ? '/admin/maperca' : '/admin/civitas');
$judul = $isMaperca ? 'Impor Data Calon Kader Baru (Maperca)' : 'Impor Data Civitas / Anggota';
$deskripsi = $isMaperca
    ? 'Unggah berkas Excel (.xlsx) atau CSV untuk mendaftarkan calon kader baru Maperca secara massal.'
    : 'Unggah berkas Excel (.xlsx) atau CSV untuk memasukkan atau memperbarui data anggota & civitas secara massal.';
?>

<div class="card" style="max-width: 860px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
    <div class="card-body" style="padding: 2.5rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div class="flex items-center gap-2" style="margin-bottom: 0.25rem;">
                    <span class="badge"
                        style="background: rgba(13, 71, 161, 0.1); color: var(--primary); font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">
                        <?= $isMaperca ? 'Modul Maperca' : 'Modul Civitas' ?>
                    </span>
                    <span class="badge"
                        style="background: #e0f2fe; color: #0369a1; font-weight: 600; font-size: 0.75rem;">
                        Support .XLSX & .CSV
                    </span>
                </div>
                <h1 style="font-size: 1.625rem; font-weight: 800; color: var(--primary); margin: 0;">
                    <?= e($judul) ?>
                </h1>
                <p class="text-muted" style="font-size: 0.875rem; margin-top: 0.35rem; margin-bottom: 0;">
                    <?= e($deskripsi) ?>
                </p>
            </div>
            <a href="<?= e($base) ?>" class="btn btn-outline btn-sm">&larr; Kembali ke Daftar</a>
        </div>

        <!-- Kartu Unduh Template -->
        <div class="card"
            style="background: #f8fafc; border: 1px dashed #cbd5e1; margin-bottom: 2rem; border-radius: var(--radius-md);">
            <div class="card-body" style="padding: 1.25rem;">
                <div class="flex justify-between items-center" style="flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                            📥 Unduh Contoh Format Berkas (Template)
                        </div>
                        <div style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem;">
                            Gunakan template resmi untuk mencegah kesalahan urutan kolom saat pengunggahan.
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="<?= e($base) ?>/template?format=xlsx" class="btn btn-primary btn-sm"
                            style="display: inline-flex; align-items: center; gap: 0.35rem;">
                            <span>📊</span> Unduh Excel (.xlsx)
                        </a>
                        <a href="<?= e($base) ?>/template?format=csv" class="btn btn-outline btn-sm"
                            style="display: inline-flex; align-items: center; gap: 0.35rem;">
                            <span>📄</span> Unduh CSV (.csv)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panduan Format Kolom -->
        <div class="card"
            style="background: var(--bg-subtle, #f8f9fa); margin-bottom: 2rem; border-left: 4px solid var(--primary);">
            <div class="card-body" style="padding: 1.25rem;">
                <h4 style="font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--primary); font-weight: 700;">
                    Petunjuk Format Kolom (Header):
                </h4>
                <p style="font-size: 0.8125rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                    Baris pertama berkas Excel atau CSV harus berupa judul kolom (case-insensitive):
                </p>
                <div
                    style="background:#ffffff; padding:0.75rem; border-radius:var(--radius-sm); font-size:0.75rem; overflow-x:auto; border: 1px solid #e2e8f0; font-family: monospace; color: #334155;">
                    <?php if ($isMaperca): ?>
                    ID / NIM, Nama Lengkap, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Telepon / WA, Email, Perguruan Tinggi, Fakultas, Jurusan, Tahun Maperca, Status Keanggotaan, Alamat Padang, Alamat Asal
                    <?php else: ?>
                    ID / NIM, Nama Lengkap, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Telepon / WA, Email, Perguruan Tinggi, Fakultas, Jurusan, Komisariat, Tahun Maperca, Tingkat Kaderisasi, Status Keanggotaan, Alamat Padang, Alamat Asal
                    <?php endif; ?>
                </div>
                <ul
                    style="font-size: 0.8125rem; margin-top: 0.75rem; padding-left: 1.25rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 0;">
                    <li><strong>Nama Lengkap:</strong> Wajib diisi pada setiap baris.</li>
                    <li><strong>ID / NIM Fleksibel:</strong> Berapapun ID atau NIM yang dimasukkan (misal
                        <code>1</code>, <code>12</code>, <code>100</code>, atau NIM kampus) otomatis terdeteksi. Jika
                        kosong, sistem otomatis membuatkan ID urut unik.</li>
                    <li><strong>Jenis Kelamin:</strong> Diisi <code>L</code> (Laki-laki / Pria) atau <code>P</code>
                        (Perempuan / Wanita).</li>
                    <li><strong>Tanggal Lahir:</strong> Format teks tanggal standar (<code>YYYY-MM-DD</code> atau
                        <code>DD/MM/YYYY</code>) maupun format cell tanggal Excel bawaan didukung otomatis.</li>
                    <?php if ($isMaperca): ?>
                        <li><strong>Khusus Calon Kader Baru (Maperca):</strong> Pendaftar kader baru belum terafiliasi dengan komisariat tertentu sampai mereka resmi dilantik, sehingga kolom Komisariat dan Tingkat Kaderisasi tidak perlu diisi (otomatis diatur sebagai kader baru).</li>
                    <?php else: ?>
                        <li><strong>Tingkat Kaderisasi:</strong> Pilihan: <code>Anggota</code>, <code>KK</code>, atau
                            <code>Alumni</code> (default <code>Anggota</code>).</li>
                    <?php endif; ?>
                    <li><strong>Pembaruan Otomatis (Upsert):</strong> Jika ID atau NIM sudah tercatat di sistem, data
                        akan diperbarui (update) tanpa menduplikasi data.</li>
                </ul>
            </div>
        </div>

        <!-- Form Upload -->
        <form action="<?= e($base) ?>/impor" method="POST" enctype="multipart/form-data" data-validate>
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="file_impor" style="font-weight: 700;">
                    Pilih Berkas Excel (.xlsx) atau CSV (.csv)
                </label>
                <input type="file" id="file_impor" name="file_impor" class="form-control"
                    accept=".xlsx,.xls,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,application/vnd.ms-excel"
                    required style="padding: 0.65rem;">
                <div class="form-help" style="font-size: 0.8125rem; color: #64748b; margin-top: 0.35rem;">
                    Mendukung berkas Microsoft Excel (<strong>.xlsx</strong> / <strong>.xls</strong>) dan berkas
                    <strong>.csv</strong> (pemisah koma <code>,</code> atau titik-koma <code>;</code> dengan enkripsi
                    UTF-8).
                </div>
            </div>

            <div class="flex items-center gap-3" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.75rem; font-weight: 600;">
                    🚀 Mulai Proses Impor
                </button>
                <a href="<?= e($base) ?>" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>