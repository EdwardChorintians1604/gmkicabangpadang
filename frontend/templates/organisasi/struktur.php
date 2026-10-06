<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Struktur Kepengurusan Cabang</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Kelola daftar fungsionaris dan jabatan pengurus GMKI Cabang Padang.
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah / Edit Pengurus (CRUD) -->
    <div class="card" style="height: fit-content;">
        <div class="card-body">
            <h3 id="formTitle" style="font-size: 1.15rem; margin-bottom: 0.25rem; color: var(--primary);">Tambah Pengurus Baru</h3>
            <p id="formSubtitle" class="text-muted" style="font-size: 0.8rem; margin-bottom: 1.25rem;">
                Pilih atau masukkan nama jabatan, lalu simpan.
            </p>
            
            <form id="formStruktur" action="/admin/organisasi/struktur" method="POST" enctype="multipart/form-data" data-validate>
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="officer_id" value="">

                <!-- Nama Lengkap -->
                <div class="form-group">
                    <label class="form-label" for="nama">Nama Lengkap & Gelar <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Yeremia Pratama, S.T." required>
                </div>

                <!-- Pilihan & Input Jabatan -->
                <div class="form-group">
                    <label class="form-label" for="jabatan_select">Pilih Jabatan Cepat</label>
                    <select id="jabatan_select" class="form-control" onchange="pilihJabatan(this.value)">
                        <!-- Diisi otomatis oleh JavaScript -->
                    </select>
                    <div style="margin-top: 0.5rem;">
                        <label class="form-label" for="jabatan" style="font-size: 0.8rem; color: var(--text-muted);">
                            Nama Jabatan (Dapat disesuaikan / diketik langsung): <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="text" id="jabatan" name="jabatan" class="form-control" placeholder="Contoh: Ketua Cabang atau Kabid OR" required>
                    </div>
                </div>

                <!-- Periode & Nomor Urut -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label" for="periode">Periode</label>
                        <input type="text" id="periode" name="periode" class="form-control" value="2024-2026" placeholder="2024-2026">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="urutan">Nomor Urut Tampil</label>
                        <input type="number" id="urutan" name="urutan" class="form-control" value="1" min="1">
                    </div>
                </div>

                <!-- Foto Formal -->
                <div class="form-group">
                    <label class="form-label" for="foto">Foto Formal Pengurus</label>
                    <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                    <div class="form-help">Format JPG/PNG/WebP, maksimal 4MB. Kosongkan jika tidak diubah.</div>
                </div>

                <!-- Status Aktif -->
                <div class="form-group">
                    <label class="form-label" for="status_aktif">Status Aktif</label>
                    <select id="status_aktif" name="status_aktif" class="form-control">
                        <option value="1">Aktif Menjabat</option>
                        <option value="0">Demisioner / Nonaktif</option>
                    </select>
                </div>

                <!-- Tombol Submit & Batal -->
                <div class="flex gap-2" style="margin-top: 1.5rem;">
                    <button type="submit" id="btnSubmit" class="btn btn-primary" style="flex: 1; justify-content: center;">
                        <span id="btnSubmitIcon">➕</span> <span id="btnSubmitText">Simpan Pengurus</span>
                    </button>
                    <button type="button" id="btnCancel" class="btn btn-outline" style="display: none;" onclick="cancelEdit()">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Pengurus Terdaftar (CRUD) -->
    <div class="table-card" style="grid-column: span 2;">
        <div class="table-header flex justify-between items-center" style="flex-wrap: wrap; gap: 0.75rem; padding: 1.25rem 1.5rem;">
            <div>
                <div class="table-title">Daftar Pengurus Cabang</div>
                <div class="text-muted" style="font-size: 0.8rem;">Klik tombol <strong>Edit</strong> untuk memperbarui atau <strong>Hapus</strong> untuk menghapus.</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table" id="tablePengurus">
                <thead>
                    <tr>
                        <th style="width: 60px;">Urut</th>
                        <th style="width: 60px;">Foto</th>
                        <th>Nama & Jabatan</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th style="width: 140px; text-align: center;">Aksi (CRUD)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($structure)): ?>
                        <?php foreach ($structure as $item): ?>
                            <tr id="row-<?= $item['id'] ?>">
                                <td><strong>#<?= (int)$item['urutan'] ?></strong></td>
                                <td>
                                    <?php if (!empty($item['foto'])): ?>
                                        <img src="/uploads/organisasi/<?= e($item['foto']) ?>" alt="<?= e($item['nama']) ?>" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
                                    <?php else: ?>
                                        <img src="<?= asset('images/default-profile.png') ?>" alt="Default" style="width: 42px; height: 42px; border-radius: 50%; border: 2px solid var(--border-color);">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="font-weight: 700; font-size: 0.95rem; color: var(--text-main);"><?= e($item['nama']) ?></div>
                                    <div class="mt-0.5">
                                        <span style="font-size: 0.82rem; color: var(--secondary); font-weight: 700;"><?= e($item['jabatan']) ?></span>
                                    </div>
                                </td>
                                <td class="text-muted" style="font-size: 0.85rem;"><?= e($item['periode']) ?></td>
                                <td>
                                    <?php if ((int)($item['status_aktif'] ?? 1) === 1): ?>
                                        <span class="badge badge-success" style="font-size: 0.7rem;">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-neutral" style="font-size: 0.7rem;">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" class="btn btn-outline btn-sm" onclick='editOfficer(<?= json_encode($item, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Edit data pengurus">
                                            ✏️ Edit
                                        </button>
                                        <form action="/admin/organisasi/struktur/<?= $item['id'] ?>/delete" method="POST" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus data pengurus <?= e($item['nama']) ?>?" title="Hapus pengurus">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="emptyRow">
                            <td colspan="6" class="text-center text-muted" style="padding: 3rem 1rem;">
                                Belum ada data pengurus yang ditambahkan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Daftar rekomendasi jabatan resmi (GMKI Cabang Padang)
const daftarJabatan = [
    "Ketua Cabang",
    "Sekretaris Cabang",
    "Bendahara Cabang",
    "Kabid OR (Kepala Bidang)",
    "KABID PKK",
    "KABID AKSPEL",
    "SEKFUNG OR-LITBANG",
    "SEKFUNG MEDKO",
    "SEKFUNG Pemberdayaan Perempuan",
    "SEKFUNG AKSPEL",
    "SEKFUNG EKRAF",
    "SEKFUNG KADER",
    "SEKFUNG KEROHANIAN",
    "-- Ketik Jabatan Kustom --"
];

function initJabatanOptions(currentJabatan = '') {
    const select = document.getElementById('jabatan_select');
    select.innerHTML = '<option value="">-- Pilih Jabatan Rekomendasi --</option>';
    let matched = false;

    daftarJabatan.forEach(j => {
        const opt = document.createElement('option');
        opt.value = j;
        opt.textContent = j;
        if (currentJabatan && j.toLowerCase() === currentJabatan.toLowerCase()) {
            opt.selected = true;
            matched = true;
        }
        select.appendChild(opt);
    });

    if (currentJabatan && !matched) {
        select.value = '-- Ketik Jabatan Kustom --';
    }
}

function pilihJabatan(val) {
    const input = document.getElementById('jabatan');
    if (!val || val === '-- Ketik Jabatan Kustom --') {
        input.value = '';
        input.focus();
    } else {
        input.value = val;
    }
}

function editOfficer(data) {
    document.getElementById('officer_id').value = data.id;
    document.getElementById('nama').value = data.nama || '';

    // Sinkronisasi opsi jabatan
    initJabatanOptions(data.jabatan);
    document.getElementById('jabatan').value = data.jabatan || '';

    document.getElementById('periode').value = data.periode || '2024-2026';
    document.getElementById('urutan').value = data.urutan || 1;
    document.getElementById('status_aktif').value = (data.status_aktif !== undefined && data.status_aktif !== null) ? data.status_aktif : 1;

    // Update UI Form ke Mode Edit
    document.getElementById('formTitle').innerHTML = '✏️ Edit Pengurus';
    document.getElementById('formSubtitle').innerHTML = 'Mengubah data pengurus: <strong style="color:var(--primary);">' + (data.nama || '') + '</strong>';
    document.getElementById('btnSubmitText').textContent = 'Perbarui Pengurus';
    document.getElementById('btnSubmitIcon').textContent = '💾';
    document.getElementById('btnCancel').style.display = 'inline-block';

    // Highlight row yang sedang diedit
    document.querySelectorAll('tbody tr').forEach(r => r.style.background = '');
    const activeRow = document.getElementById('row-' + data.id);
    if (activeRow) {
        activeRow.style.background = '#eff6ff';
    }

    // Scroll mulus ke form
    document.getElementById('formStruktur').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function cancelEdit() {
    document.getElementById('officer_id').value = '';
    document.getElementById('formStruktur').reset();
    initJabatanOptions();
    document.getElementById('jabatan').value = '';
    document.getElementById('periode').value = '2024-2026';
    document.getElementById('urutan').value = 1;
    document.getElementById('status_aktif').value = '1';

    // Reset UI Form ke Mode Tambah
    document.getElementById('formTitle').textContent = 'Tambah Pengurus Baru';
    document.getElementById('formSubtitle').textContent = 'Pilih atau masukkan nama jabatan, lalu simpan.';
    document.getElementById('btnSubmitText').textContent = 'Simpan Pengurus';
    document.getElementById('btnSubmitIcon').textContent = '➕';
    document.getElementById('btnCancel').style.display = 'none';

    document.querySelectorAll('tbody tr').forEach(r => r.style.background = '');
}

document.addEventListener('DOMContentLoaded', function() {
    initJabatanOptions();
});
</script>
