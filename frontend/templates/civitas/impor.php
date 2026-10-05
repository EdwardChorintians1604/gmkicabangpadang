<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body" style="padding: 2.5rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">Impor Data Civitas via CSV</h1>
                <p class="text-muted" style="font-size: 0.875rem;">
                    Unggah berkas CSV untuk memasukkan atau memperbarui data anggota secara massal.
                </p>
            </div>
            <a href="/admin/civitas" class="btn btn-outline btn-sm">&larr; Kembali</a>
        </div>

        <!-- Panduan Format CSV -->
        <div class="card" style="background: var(--bg-subtle); margin-bottom: 2rem; border-left: 4px solid var(--primary);">
            <div class="card-body" style="padding: 1.25rem;">
                <h4 style="font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--primary);">Petunjuk Format Kolom CSV:</h4>
                <p style="font-size: 0.8125rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                    Baris pertama berkas CSV harus berupa header kolom dengan susunan berikut:
                </p>
                <code style="display:block; background:#ffffff; padding:0.75rem; border-radius:var(--radius-sm); font-size:0.75rem; overflow-x:auto;">
                    nim,nama_lengkap,jenis_kelamin,tempat_lahir,tanggal_lahir,telepon,email,perguruan_tinggi,fakultas,jurusan,komisariat,tahun_maperca,tingkat_kaderisasi,status_keanggotaan,alamat_padang,alamat_asal
                </code>
                <ul style="font-size: 0.8125rem; margin-top: 0.75rem; padding-left: 1.25rem; color: var(--text-muted); line-height: 1.5;">
                    <li><strong>nim</strong> dan <strong>nama_lengkap</strong> wajib terisi.</li>
                    <li><strong>jenis_kelamin</strong> diisi <code>L</code> atau <code>P</code>.</li>
                    <li>Jika NIM sudah ada di database, data anggota terkait akan diperbarui otomatis (update).</li>
                </ul>
            </div>
        </div>

        <!-- Form Upload -->
        <form action="/admin/civitas/impor" method="POST" enctype="multipart/form-data" data-validate>
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="file_csv">Pilih Berkas CSV</label>
                <input type="file" id="file_csv" name="file_csv" class="form-control" accept=".csv,text/csv" required>
                <div class="form-help">Pilih berkas dengan ekstensi .csv (UTF-8 dipisahkan dengan koma).</div>
            </div>

            <div class="flex items-center gap-3" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    Mulai Proses Impor
                </button>
                <a href="/admin/civitas" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
