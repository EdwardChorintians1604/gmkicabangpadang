<?php
$role = $user['role'] ?? '';
$titles = [
    'inventory' => 'Inventaris GMKI',
    'archive' => 'Arsip Surat Penting',
    'finance' => 'Laporan Keuangan Cabang',
    'strategies' => 'Strategi Organisasi',
    'coordination' => 'Ruang Koordinasi BPC',
];
$backPath = match ($role) {
    'admin' => '/admin/dashboard',
    'ketcab' => '/ketcab/dashboard',
    default => '/ruang-kerja',
};
$backLabel = $role === 'admin' ? 'Dashboard Admin' : 'Ruang kerja';
?>
<header class="flex justify-between items-center" style="margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <p class="text-muted" style="margin:0;"><a href="<?= e($backPath) ?>"><?= e($backLabel) ?></a> / <?= e($titles[$section] ?? '') ?></p>
        <h1 style="font-size:1.75rem;font-weight:800;color:var(--primary);margin:.25rem 0;"><?= e($titles[$section] ?? '') ?></h1>
    </div>
</header>

<?php if ($section === 'inventory'): ?>
    <!-- Ringkasan Metrik Inventaris -->
    <div class="metrics-grid" style="margin-bottom: 1.5rem;">
        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Total Jenis Barang</span>
                <span class="metric-value"><?= number_format((int)($summary['total_items'] ?? count($items))) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">
                    Total fisik: <strong><?= number_format((float)($summary['total_qty'] ?? 0), 0, ',', '.') ?></strong> unit
                </span>
            </div>
            <div class="metric-icon-wrap metric-icon-primary">
                📦
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Kondisi Baik</span>
                <span class="metric-value" style="color: #10b981;"><?= number_format((int)($summary['total_baik'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">Siap dipergunakan</span>
            </div>
            <div class="metric-icon-wrap metric-icon-success">
                ✅
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Perlu Perbaikan</span>
                <span class="metric-value" style="color: #f59e0b;"><?= number_format((int)($summary['total_perbaikan'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">Perlu perawatan</span>
            </div>
            <div class="metric-icon-wrap metric-icon-secondary">
                ⚠️
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Kondisi Rusak</span>
                <span class="metric-value" style="color: #ef4444;"><?= number_format((int)($summary['total_rusak'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">Perlu diganti / diafkir</span>
            </div>
            <div class="metric-icon-wrap" style="background:#fee2e2; color:#ef4444;">
                ❌
            </div>
        </div>
    </div>

    <!-- Area Konten Utama Inventaris: Form + Tabel -->
    <div class="grid grid-cols-1 <?= can('inventory.manage') ? 'lg:grid-cols-3' : '' ?> gap-6" style="align-items: start;">
        <?php if (can('inventory.manage')): ?>
            <!-- Kolom Form Tambah/Edit (Kiri) -->
            <div class="card" id="formInventoryCard" style="position: sticky; top: 88px; z-index: 10;">
                <div class="card-body">
                    <div class="flex items-center justify-between" style="margin-bottom: 1rem;">
                        <div>
                            <h2 id="invFormTitle" style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin: 0;">Tambah Inventaris</h2>
                            <p id="invFormSubtitle" class="text-muted" style="font-size: 0.8rem; margin: 0.2rem 0 0;">Catat aset & barang milik cabang</p>
                        </div>
                        <span id="invModeBadge" class="badge badge-primary" style="font-size: 0.75rem;">Mode Baru</span>
                    </div>

                    <form id="inventoryForm" method="post" action="/sekcab/inventaris" data-validate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" id="inv_id" value="">

                        <div class="form-group" style="margin-bottom: 0.85rem;">
                            <label class="form-label" for="inv_name" style="font-size: 0.85rem; font-weight: 600;">Nama Barang <span style="color:#ef4444;">*</span></label>
                            <input class="form-control" id="inv_name" name="name" maxlength="180" placeholder="Contoh: Tiang Bendera GMKI" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 0.85rem;">
                            <label class="form-label" for="inv_category_select" style="font-size: 0.85rem; font-weight: 600;">Kategori <span style="color:#ef4444;">*</span></label>
                            <select id="inv_category_select" class="form-control" onchange="pilihKategoriInv(this.value)" style="margin-bottom: 0.4rem;">
                                <option value="">-- Pilih Kategori Cepat --</option>
                                <option value="Perlengkapan Cabang">Perlengkapan Cabang</option>
                                <option value="Elektronik & Multimedia">Elektronik & Multimedia</option>
                                <option value="Perlengkapan Ibadah">Perlengkapan Ibadah</option>
                                <option value="Furnitur & Kantor">Furnitur & Kantor</option>
                                <option value="Atribut & Bendera">Atribut & Bendera</option>
                                <option value="Dokumen & Arsip">Dokumen & Arsip</option>
                                <option value="Lainnya">Lainnya (Ketik Manual)</option>
                            </select>
                            <input class="form-control" id="inv_category" name="category" maxlength="100" placeholder="Ketik kategori barang..." required>
                        </div>

                        <div class="grid grid-cols-2 gap-3" style="margin-bottom: 0.85rem;">
                            <div class="form-group">
                                <label class="form-label" for="inv_quantity" style="font-size: 0.85rem; font-weight: 600;">Jumlah <span style="color:#ef4444;">*</span></label>
                                <input class="form-control" id="inv_quantity" name="quantity" type="number" min="0" step="0.01" value="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="inv_unit" style="font-size: 0.85rem; font-weight: 600;">Satuan <span style="color:#ef4444;">*</span></label>
                                <input class="form-control" id="inv_unit" name="unit" maxlength="40" value="unit" placeholder="unit, buah, set..." required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0.85rem;">
                            <label class="form-label" for="inv_location" style="font-size: 0.85rem; font-weight: 600;">Lokasi Penyimpanan</label>
                            <input class="form-control" id="inv_location" name="location" maxlength="180" placeholder="Contoh: Sekretariat Cabang / GPdI Karmel">
                        </div>

                        <div class="form-group" style="margin-bottom: 0.85rem;">
                            <label class="form-label" for="inv_condition" style="font-size: 0.85rem; font-weight: 600;">Kondisi Barang <span style="color:#ef4444;">*</span></label>
                            <select class="form-control" id="inv_condition" name="item_condition">
                                <option value="Baik">Baik (Berfungsi Normal)</option>
                                <option value="Perlu Perbaikan">Perlu Perbaikan (Rusak Ringan)</option>
                                <option value="Rusak">Rusak (Tidak Dapat Digunakan)</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.15rem;">
                            <label class="form-label" for="inv_notes" style="font-size: 0.85rem; font-weight: 600;">Catatan / Keterangan</label>
                            <textarea class="form-control" id="inv_notes" name="notes" rows="2" placeholder="Catatan kondisi, instruksi perawatan, dll..."></textarea>
                        </div>

                        <div class="flex gap-2">
                            <button id="btnSubmitInv" class="btn btn-primary" type="submit" style="flex: 1; justify-content: center;">
                                💾 Simpan Inventaris
                            </button>
                            <button id="btnCancelEditInv" class="btn btn-secondary" type="button" onclick="batalEditInv()" style="display: none;">
                                ✖ Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Kolom Tabel Daftar Inventaris (Kanan) -->
        <div class="<?= can('inventory.manage') ? 'lg:col-span-2' : 'col-span-1' ?>">
            <div class="table-card" style="margin-bottom: 1.5rem;">
                <div class="table-header flex items-center justify-between" style="gap: 1rem; flex-wrap: wrap;">
                    <div>
                        <h2 class="table-title" style="margin: 0; font-size: 1.15rem; color: var(--primary);">Daftar Aset & Inventaris</h2>
                        <span class="text-muted" style="font-size: 0.8rem;">Daftar seluruh barang yang tercatat di GMKI Cabang Padang</span>
                    </div>

                    <!-- Toolbar Pencarian & Filter Cepat -->
                    <div class="flex items-center gap-2" style="flex-wrap: wrap;">
                        <div style="position: relative; min-width: 200px;">
                            <input type="text" id="invSearchInput" onkeyup="filterInventoryTable()" placeholder="Cari barang, lokasi..." class="form-control" style="font-size: 0.85rem; padding-left: 2rem; height: 38px;">
                            <span style="position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;">🔍</span>
                        </div>
                        <select id="invConditionFilter" onchange="filterInventoryTable()" class="form-control" style="font-size: 0.85rem; height: 38px; width: auto;">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik">Baik</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table" id="inventoryTable">
                        <thead>
                            <tr>
                                <th style="width: 45px; text-align: center;">No</th>
                                <th>Nama Barang & Kategori</th>
                                <th style="width: 100px;">Jumlah</th>
                                <th>Lokasi</th>
                                <th style="width: 130px; text-align: center;">Kondisi</th>
                                <th>Catatan</th>
                                <?php if (can('inventory.manage')): ?>
                                    <th style="width: 120px; text-align: center;">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php $no = 1; foreach ($items as $item): ?>
                                    <?php
                                        $conditionBadge = match ($item['item_condition']) {
                                            'Baik' => 'badge-success',
                                            'Perlu Perbaikan' => 'badge-warning',
                                            'Rusak' => 'badge-danger',
                                            default => 'badge-neutral',
                                        };
                                        $qtyDisplay = (float)$item['quantity'] == (int)$item['quantity'] 
                                            ? (int)$item['quantity'] 
                                            : number_format((float)$item['quantity'], 2, ',', '.');
                                    ?>
                                    <tr class="inv-row" 
                                        data-id="<?= (int)$item['id'] ?>"
                                        data-name="<?= e($item['name']) ?>"
                                        data-category="<?= e($item['category']) ?>"
                                        data-quantity="<?= e($item['quantity']) ?>"
                                        data-unit="<?= e($item['unit']) ?>"
                                        data-location="<?= e($item['location'] ?? '') ?>"
                                        data-condition="<?= e($item['item_condition']) ?>"
                                        data-notes="<?= e($item['notes'] ?? '') ?>">
                                        <td style="text-align: center; color: var(--text-muted); font-weight: 600;">
                                            <?= $no++ ?>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.2rem;">
                                                <?= e($item['name']) ?>
                                            </div>
                                            <span class="badge badge-primary" style="font-size: 0.725rem; font-weight: 600;">
                                                📁 <?= e($item['category']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-weight: 700; font-size: 0.95rem; color: var(--primary);">
                                                <?= $qtyDisplay ?>
                                            </span>
                                            <span class="text-muted" style="font-size: 0.8rem; margin-left: 0.15rem;">
                                                <?= e($item['unit']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($item['location'])): ?>
                                                <div style="display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.85rem; color: var(--text-main);">
                                                    <span>📍</span> <?= e($item['location']) ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size: 0.8rem; font-style: italic;">Belum ditentukan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="badge <?= $conditionBadge ?>" style="font-size: 0.775rem;">
                                                <?= e($item['item_condition']) ?>
                                            </span>
                                        </td>
                                        <td style="max-width: 220px;">
                                            <?php if (!empty($item['notes'])): ?>
                                                <span style="font-size: 0.825rem; color: var(--text-muted); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= e($item['notes']) ?>">
                                                    <?= e($item['notes']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size: 0.8rem;">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if (can('inventory.manage')): ?>
                                            <td style="text-align: center;">
                                                <div class="flex items-center justify-center gap-1">
                                                    <button type="button" 
                                                            class="btn btn-secondary btn-sm" 
                                                            style="padding: 0.25rem 0.5rem; font-size: 0.8rem;"
                                                            title="Edit data inventaris ini"
                                                            onclick='editInvFromRow(this)'>
                                                        ✏️ Edit
                                                    </button>
                                                    <form method="post" action="/sekcab/inventaris/<?= (int)$item['id'] ?>/delete" data-confirm="Hapus data inventaris '<?= e($item['name']) ?>'?" style="margin: 0; display: inline;">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" type="submit" title="Hapus inventaris">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noDataRow">
                                    <td colspan="<?= can('inventory.manage') ? '7' : '6' ?>" style="text-align: center; padding: 3rem 1.5rem;">
                                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📦</div>
                                        <div style="font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem;">Belum ada data inventaris</div>
                                        <div class="text-muted" style="font-size: 0.875rem;">Silakan gunakan form di samping untuk mulai mencatat aset cabang.</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr id="noMatchRow" style="display: none;">
                                <td colspan="<?= can('inventory.manage') ? '7' : '6' ?>" style="text-align: center; padding: 2rem 1.5rem;">
                                    <div class="text-muted" style="font-size: 0.9rem;">Tidak ditemukan inventaris yang sesuai dengan pencarian.</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($pagination)): ?>
                    <div style="padding: 0.75rem 1rem; border-top: 1px solid var(--border-color);">
                        <?= partial('partials.pagination', $pagination) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Script Interaktif untuk Pencarian, Filter, dan Edit Form -->
    <script>
    function pilihKategoriInv(val) {
        if (!val || val === 'Lainnya') {
            document.getElementById('inv_category').value = '';
            document.getElementById('inv_category').focus();
        } else {
            document.getElementById('inv_category').value = val;
        }
    }

    function editInvFromRow(btn) {
        const row = btn.closest('.inv-row');
        if (!row) return;

        const id = row.getAttribute('data-id');
        const name = row.getAttribute('data-name');
        const category = row.getAttribute('data-category');
        const qty = row.getAttribute('data-quantity');
        const unit = row.getAttribute('data-unit');
        const loc = row.getAttribute('data-location');
        const condition = row.getAttribute('data-condition');
        const notes = row.getAttribute('data-notes');

        document.getElementById('inv_id').value = id;
        document.getElementById('inv_name').value = name;
        document.getElementById('inv_category').value = category;
        document.getElementById('inv_quantity').value = qty;
        document.getElementById('inv_unit').value = unit;
        document.getElementById('inv_location').value = loc;
        document.getElementById('inv_condition').value = condition;
        document.getElementById('inv_notes').value = notes;

        const catSelect = document.getElementById('inv_category_select');
        let matched = false;
        for (let i = 0; i < catSelect.options.length; i++) {
            if (catSelect.options[i].value === category) {
                catSelect.selectedIndex = i;
                matched = true;
                break;
            }
        }
        if (!matched && category) {
            catSelect.value = 'Lainnya';
        }

        document.getElementById('invFormTitle').textContent = 'Edit Inventaris';
        document.getElementById('invFormSubtitle').textContent = 'Memperbarui data: ' + name;
        document.getElementById('invModeBadge').textContent = 'Mode Edit #' + id;
        document.getElementById('invModeBadge').className = 'badge badge-warning';
        document.getElementById('btnSubmitInv').innerHTML = '💾 Simpan Perubahan';
        document.getElementById('btnCancelEditInv').style.display = 'inline-flex';

        const formCard = document.getElementById('formInventoryCard');
        if (formCard) {
            formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            formCard.style.outline = '2px solid #0284c7';
            setTimeout(() => { formCard.style.outline = 'none'; }, 1500);
        }
        document.getElementById('inv_name').focus();
    }

    function batalEditInv() {
        document.getElementById('inv_id').value = '';
        document.getElementById('inventoryForm').reset();
        document.getElementById('inv_quantity').value = '1';
        document.getElementById('inv_unit').value = 'unit';
        document.getElementById('inv_condition').value = 'Baik';

        document.getElementById('invFormTitle').textContent = 'Tambah Inventaris';
        document.getElementById('invFormSubtitle').textContent = 'Catat aset & barang milik cabang';
        document.getElementById('invModeBadge').textContent = 'Mode Baru';
        document.getElementById('invModeBadge').className = 'badge badge-primary';
        document.getElementById('btnSubmitInv').innerHTML = '💾 Simpan Inventaris';
        document.getElementById('btnCancelEditInv').style.display = 'none';
    }

    function filterInventoryTable() {
        const query = (document.getElementById('invSearchInput').value || '').toLowerCase().trim();
        const conditionFilter = (document.getElementById('invConditionFilter').value || '').trim();
        const rows = document.querySelectorAll('#inventoryTable tbody tr.inv-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = (row.getAttribute('data-name') || '').toLowerCase();
            const cat = (row.getAttribute('data-category') || '').toLowerCase();
            const loc = (row.getAttribute('data-location') || '').toLowerCase();
            const notes = (row.getAttribute('data-notes') || '').toLowerCase();
            const cond = row.getAttribute('data-condition') || '';

            const matchesQuery = !query || name.includes(query) || cat.includes(query) || loc.includes(query) || notes.includes(query);
            const matchesCond = !conditionFilter || cond === conditionFilter;

            if (matchesQuery && matchesCond) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noMatchRow = document.getElementById('noMatchRow');
        if (noMatchRow) {
            noMatchRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }
    }
    </script>

<?php elseif ($section === 'archive'): ?>
    <?php if (can('archive.manage')): ?>
        <section class="card" style="margin-bottom:1.25rem;"><div class="card-body">
            <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 1rem;">Simpan surat atau dokumen</h2>
            <p class="text-muted">Berkas bersifat privat, maksimal 10 MB. Format: PDF, Word, Excel, ODT, JPG, PNG.</p>
            <form method="post" action="/sekcab/arsip" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
                <?= csrf_field() ?>
                <label>Judul dokumen<input class="form-control" name="title" maxlength="180" required></label>
                <label>Berkas<input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png" required></label>
                <label style="grid-column:1/-1;">Keterangan<textarea class="form-control" name="description" rows="2"></textarea></label>
                <div><button class="btn btn-primary" type="submit">Unggah ke arsip</button></div>
            </form>
        </div></section>
    <?php endif; ?>
    <div class="grid grid-cols-2 gap-4">
        <?php foreach ($documents as $document): ?><article class="card"><div class="card-body">
            <form method="post" action="/sekcab/arsip" enctype="multipart/form-data">
                <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$document['id'] ?>">
                <label>Judul dokumen<input class="form-control" name="title" maxlength="180" value="<?= e($document['title']) ?>" required></label>
                <label style="display:block;margin-top:.5rem;">Keterangan<textarea class="form-control" name="description" rows="2"><?= e($document['description'] ?? '') ?></textarea></label>
                <label style="display:block;margin-top:.5rem;">Ganti berkas (opsional)<input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png"></label>
                <p class="text-muted"><?= e($document['original_name']) ?> · <?= e($document['nama_lengkap']) ?> · <?= e($document['created_at']) ?></p>
                <div class="flex gap-2"><button class="btn btn-primary btn-sm" type="submit">Simpan perubahan</button><a class="btn btn-outline btn-sm" href="/dokumen/<?= (int)$document['id'] ?>/unduh">Unduh</a></div>
            </form>
            <form method="post" action="/sekcab/arsip/<?= (int)$document['id'] ?>/delete" data-confirm="Hapus dokumen ini?" style="margin-top:.5rem;"><?= csrf_field() ?><button class="btn btn-danger btn-sm" type="submit">Hapus arsip</button></form>
        </div></article><?php endforeach; ?>
        <?php if (!$documents): ?><div class="card"><div class="card-body text-center text-muted">Arsip masih kosong.</div></div><?php endif; ?>
    </div>
    <?= partial('partials.pagination', $pagination) ?>

<?php elseif ($section === 'finance'): ?>
    <section class="card" style="margin-bottom:1.25rem;"><div class="card-body flex justify-between items-center" style="gap:1rem;flex-wrap:wrap;">
        <div><strong>Ekspor laporan untuk rekap</strong><p class="text-muted" style="margin:.25rem 0 0;">Pembuatan CSV berjalan di background agar tidak menahan halaman.</p></div>
        <div class="flex items-center gap-2">
            <a class="btn btn-outline" href="/ruang-kerja/antrian">Lihat antrean</a>
            <form method="post" action="/ruang-kerja/antrian/laporan-keuangan" style="margin:0;">
                <?= csrf_field() ?><button class="btn btn-primary" type="submit">Buat ekspor CSV</button>
            </form>
        </div>
    </div></section>
    <?php if ($role === 'bencab'): ?>
        <section class="card" style="margin-bottom:1.25rem;"><div class="card-body">
            <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 1rem;">Tambah laporan keuangan</h2>
            <form method="post" action="/bencab/laporan" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
                <?= csrf_field() ?>
                <label>Judul laporan<input class="form-control" name="title" maxlength="180" required></label>
                <label>Periode<input class="form-control" name="period" placeholder="Contoh: Triwulan I 2026" maxlength="40" required></label>
                <label>Jumlah (Rp)<input class="form-control" name="amount" type="number" min="0" step="0.01" required></label>
                <label>Berkas laporan<input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png" required></label>
                <label style="grid-column:1/-1;">Ringkasan/keterangan<textarea class="form-control" name="description" rows="2"></textarea></label>
                <div><button class="btn btn-primary" type="submit">Simpan laporan</button></div>
            </form>
        </div></section>
        <p class="text-muted">Anda hanya dapat mengelola laporan yang diunggah oleh akun Anda.</p>
    <?php endif; ?>
    <div class="grid grid-cols-2 gap-4">
        <?php foreach ($documents as $document): ?><article class="card"><div class="card-body">
            <?php if ($role === 'bencab'): ?>
                <div class="flex gap-2">
                <form method="post" action="/bencab/laporan" enctype="multipart/form-data">
                    <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$document['id'] ?>">
                    <label>Judul<input class="form-control" name="title" value="<?= e($document['title']) ?>" required></label>
                    <div class="grid grid-cols-2 gap-3" style="margin-top:.5rem;">
                        <label>Periode<input class="form-control" name="period" value="<?= e($document['period']) ?>" required></label>
                        <label>Jumlah (Rp)<input class="form-control" name="amount" type="number" min="0" step="0.01" value="<?= e($document['amount']) ?>" required></label>
                    </div>
                    <label style="display:block;margin-top:.5rem;">Ganti berkas (opsional)<input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png"></label>
                    <label style="display:block;margin-top:.5rem;">Ringkasan<textarea class="form-control" name="description" rows="2"><?= e($document['description'] ?? '') ?></textarea></label>
                    <p class="text-muted"><a href="/dokumen/<?= (int)$document['id'] ?>/unduh">Unduh berkas saat ini: <?= e($document['original_name']) ?></a></p>
                    <button class="btn btn-primary btn-sm" type="submit">Perbarui</button>
                </form>
                <form method="post" action="/bencab/laporan/<?= (int)$document['id'] ?>/delete" data-confirm="Hapus laporan ini?"><?= csrf_field() ?><button class="btn btn-danger btn-sm" type="submit">Hapus</button></form>
                </div>
            <?php else: ?>
                <h2><?= e($document['title']) ?></h2><p class="text-muted"><?= e($document['period']) ?> · diunggah oleh <?= e($document['nama_lengkap']) ?></p>
                <strong>Rp <?= number_format((float)$document['amount'], 2, ',', '.') ?></strong>
                <?php if (!empty($document['description'])): ?><p><?= nl2br(e($document['description'])) ?></p><?php endif; ?>
                <a class="btn btn-outline btn-sm" href="/dokumen/<?= (int)$document['id'] ?>/unduh">Unduh laporan</a>
            <?php endif; ?>
        </div></article><?php endforeach; ?>
        <?php if (!$documents): ?><div class="card"><div class="card-body text-muted">Belum ada laporan keuangan yang diterbitkan.</div></div><?php endif; ?>
    </div>
    <?= partial('partials.pagination', $pagination) ?>

<?php elseif ($section === 'strategies'): ?>
    <?php if (can('strategy.manage')): ?>
        <section class="card" style="margin-bottom:1.25rem;"><div class="card-body">
            <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 1rem;">Catat arah strategis</h2>
            <form method="post" action="/ketcab/strategi" class="grid grid-cols-2 gap-4">
                <?= csrf_field() ?><label>Judul strategi<input class="form-control" name="title" maxlength="180" required></label>
                <label>Status<select class="form-control" name="status"><option>Rencana</option><option>Berjalan</option><option>Selesai</option></select></label>
                <label>Tanggal target<input class="form-control" name="target_date" type="date"></label>
                <label style="grid-column:1/-1;">Tujuan dan langkah<textarea class="form-control" name="objective" rows="3" required></textarea></label>
                <div><button class="btn btn-primary" type="submit">Simpan strategi</button></div>
            </form>
        </div></section>
    <?php endif; ?>
    <div class="grid grid-cols-2 gap-4">
        <?php foreach ($strategies as $strategy): ?><article class="card"><div class="card-body">
            <?php if (can('strategy.manage')): ?>
                <div class="flex gap-2">
                <form method="post" action="/ketcab/strategi">
                    <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$strategy['id'] ?>">
                    <label>Judul<input class="form-control" name="title" value="<?= e($strategy['title']) ?>" required></label>
                    <div class="grid grid-cols-2 gap-3" style="margin-top:.5rem;"><label>Status<select class="form-control" name="status"><?php foreach (['Rencana', 'Berjalan', 'Selesai'] as $status): ?><option <?= $strategy['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?></select></label>
                    <label>Target<input class="form-control" type="date" name="target_date" value="<?= e($strategy['target_date'] ?? '') ?>"></label></div>
                    <label style="display:block;margin-top:.5rem;">Tujuan dan langkah<textarea class="form-control" name="objective" rows="3" required><?= e($strategy['objective']) ?></textarea></label>
                    <button class="btn btn-primary btn-sm" style="margin-top:.5rem;">Simpan</button>
                </form>
                <form method="post" action="/ketcab/strategi/<?= (int)$strategy['id'] ?>/delete" data-confirm="Hapus catatan strategi ini?"><?= csrf_field() ?><button class="btn btn-danger btn-sm">Hapus</button></form>
                </div>
            <?php else: ?>
                <h2><?= e($strategy['title']) ?></h2><p><?= nl2br(e($strategy['objective'])) ?></p><span class="badge badge-primary"><?= e($strategy['status']) ?></span>
                <?php if ($strategy['target_date']): ?><span class="text-muted">Target <?= e($strategy['target_date']) ?></span><?php endif; ?>
            <?php endif; ?>
        </div></article><?php endforeach; ?>
        <?php if (!$strategies): ?><div class="card"><div class="card-body text-muted">Belum ada strategi yang dicatat.</div></div><?php endif; ?>
    </div>
    <?= partial('partials.pagination', $pagination) ?>

<?php elseif ($section === 'coordination'): ?>
    <?php
    $senderRoleNames = $participants;
    $senderRoleNames['admin'] = 'Sekfung Medko';
    $currentParticipantRole = $role === 'admin' ? 'sekfung_medko' : $role;
    $lastMessage = $messages ? end($messages) : null;
    ?>
    <section class="coordination-chat"
        data-messages-url="<?= e($messagesUrl) ?>"
        data-latest-id="<?= $messages ? (int)end($messages)['id'] : 0 ?>"
        data-oldest-id="<?= $messages ? (int)$messages[0]['id'] : 0 ?>"
        data-has-older="<?= $hasOlderMessages ? 'true' : 'false' ?>">
        <aside class="coordination-chat-list" aria-label="Daftar chat">
            <div class="coordination-list-header">
                <div><strong>Chat</strong><span>GMKI Cabang Padang</span></div>
                <span class="coordination-compose-icon" aria-hidden="true">✎</span>
            </div>
            <div class="coordination-search">
                <span aria-hidden="true">⌕</span>
                <span>Cari percakapan</span>
            </div>
            <div class="coordination-contacts-heading">Percakapan</div>
            <div class="coordination-contact active" aria-current="page">
                <span class="coordination-group-avatar" aria-hidden="true"><span>G</span><i>●</i></span>
                <span class="coordination-contact-copy">
                    <strong>Koordinasi Pengurus BPC</strong>
                    <small><?= $lastMessage ? e($lastMessage['sender_name'] . ': ' . mb_strimwidth((string)$lastMessage['body'], 0, 52, '…')) : 'Ketcab, Sekcab, Bencab, dan Medko' ?></small>
                </span>
                <time><?= $lastMessage ? e(date('H:i', strtotime($lastMessage['created_at']))) : '' ?></time>
            </div>
            <div class="coordination-list-note">
                <strong>Grup bersama</strong>
                <p>Semua pesan koordinasi tampil di percakapan yang sama untuk seluruh pengurus.</p>
            </div>
            <div class="coordination-member-list" aria-label="Anggota grup">
                <span>ANGGOTA GRUP</span>
            <?php foreach ($participants as $participantRole => $participantName): ?>
                <?php $isCurrentUser = $participantRole === $currentParticipantRole; ?>
                <?php $initial = strtoupper(mb_substr($participantName, 0, 1)); ?>
                <div class="coordination-member">
                    <span class="coordination-avatar"><?= e($initial) ?></span>
                    <span><strong><?= e($participantName) ?></strong><small><?= $isCurrentUser ? 'Anda' : 'Anggota' ?></small></span>
                </div>
            <?php endforeach; ?>
            </div>
        </aside>
        <div class="coordination-conversation">
            <header class="coordination-conversation-header">
                <span class="coordination-group-avatar coordination-header-avatar" aria-hidden="true"><span>G</span><i>●</i></span>
                <div><strong>Koordinasi Pengurus BPC</strong><small><?= count($participants) ?> pengurus · Ketcab, Sekcab, Bencab, Medko</small></div>
                <span class="coordination-live" aria-live="polite">Terhubung</span>
            </header>
            <div class="coordination-messages" id="coordinationMessages" aria-live="polite" aria-label="Pesan percakapan">
                <button type="button" class="coordination-load-older" id="coordinationLoadOlder" <?= $hasOlderMessages ? '' : 'hidden' ?>>Muat pesan sebelumnya</button>
                <?php foreach ($messages as $message): ?>
                    <?php $ownMessage = (int)$message['sender_id'] === (int)$currentUserId; ?>
                    <article class="coordination-message <?= $ownMessage ? 'outgoing' : 'incoming' ?>" data-message-id="<?= (int)$message['id'] ?>">
                        <?php if (!$ownMessage): ?><strong class="coordination-sender"><?= e($message['sender_name']) ?> <span>· <?= e($senderRoleNames[$message['sender_role']] ?? $message['sender_role']) ?></span></strong><?php endif; ?>
                        <p><?= nl2br(e($message['body'])) ?></p>
                        <time datetime="<?= e($message['created_at']) ?>"><?= e(date('H:i', strtotime($message['created_at']))) ?></time>
                    </article>
                <?php endforeach; ?>
                <?php if (!$messages): ?>
                    <div class="coordination-welcome" id="coordinationEmpty">
                        <span class="coordination-welcome-icon" aria-hidden="true">G</span>
                        <strong>Koordinasi Pengurus BPC</strong>
                        <p>Ruang bersama untuk Ketcab, Sekcab, Bencab, dan Sekfung Medko.</p>
                        <small>Mulai percakapan dengan mengirim pesan di bawah.</small>
                    </div>
                <?php endif; ?>
            </div>
            <form class="coordination-composer" id="coordinationForm" method="post" data-user-id="<?= (int)$currentUserId ?>" action="<?= e($role === 'admin' ? '/admin/koordinasi' : ($role === 'ketcab' ? '/ketcab/koordinasi' : '/ruang-kerja/koordinasi')) ?>">
                <?= csrf_field() ?>
                <label class="sr-only" for="coordinationBody">Tulis pesan</label>
                <textarea id="coordinationBody" name="body" rows="1" maxlength="5000" placeholder="Ketik pesan" required></textarea>
                <button type="submit" aria-label="Kirim pesan"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m22 2-7 20-4-9-9-4 20-7Z"/><path d="M22 2 11 13"/></svg></button>
                <p class="coordination-error" id="coordinationError" role="alert" hidden></p>
            </form>
        </div>
    </section>
<?php endif; ?>
