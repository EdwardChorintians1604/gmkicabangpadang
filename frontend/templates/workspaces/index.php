<?php
$role = $user['role'] ?? '';
$titles = [
    'inventory' => 'Inventaris GMKI',
    'archive' => 'Buku Agenda & Arsip Persuratan Sekcab',
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
    <!-- Banner Peringatan Keamanan & Duplikasi Dokumen untuk Sekcab -->
    <div style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.08) 0%, rgba(59, 130, 246, 0.08) 100%); border: 1.5px solid #3b82f6; border-left: 5px solid #ef4444; border-radius: 10px; padding: 1.15rem 1.35rem; margin-bottom: 1.5rem;">
        <div class="flex items-start gap-3">
            <div style="font-size: 1.75rem; line-height: 1;">🛡️</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 0.35rem; font-size: 1.05rem; font-weight: 800; color: #1e3a8a; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <span>Protokol Keamanan & Perlindungan Duplikasi Arsip Cabang</span>
                    <span class="badge badge-danger" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Wajib Sekcab</span>
                </h3>
                <p style="margin: 0; font-size: 0.85rem; line-height: 1.5; color: #334155;">
                    <strong>Peringatan Integritas:</strong> Untuk menutup celah keamanan dari berbagai potensi serangan (seperti <em>SQL Injection</em>, peretasan <em>Brute Force</em>, atau manipulasi database), sistem <strong>mewajibkan dan secara otomatis menjalankan duplikasi berkas ganda</strong>. Setiap berkas ditandai dengan jelas antara <strong>Berkas Asli (Master)</strong> dan <strong>Salinan Duplikasi (Redundant Backup)</strong> dengan isi yang <strong>100% identik tanpa perbedaan</strong> (diverifikasi melalui <em>checksum SHA-256</em>). Selalu periksa sifat surat guna menentukan tingkat kerahasiaan dokumen penting organisasi.
                </p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Metrik Arsip Persuratan -->
    <div class="metrics-grid" style="margin-bottom: 1.5rem;">
        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Total Arsip Terdaftar</span>
                <span class="metric-value"><?= number_format((int)($summary['total_arsip'] ?? count($documents))) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">
                    Dokumen resmi di repositori
                </span>
            </div>
            <div class="metric-icon-wrap metric-icon-primary">
                📁
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Arsip Rahasia</span>
                <span class="metric-value" style="color: #ef4444;"><?= number_format((int)($summary['total_rahasia'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">Kerahasiaan khusus & terbatas</span>
            </div>
            <div class="metric-icon-wrap" style="background:#fee2e2; color:#ef4444;">
                🔒
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Surat Penting & Segera</span>
                <span class="metric-value" style="color: #f59e0b;"><?= number_format((int)($summary['total_penting'] ?? 0)) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">Perlu prioritas tindak lanjut</span>
            </div>
            <div class="metric-icon-wrap metric-icon-secondary">
                ⚠️
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Duplikasi Cadangan Aktif</span>
                <span class="metric-value" style="color: #10b981;"><?= number_format((int)($summary['total_duplikasi'] ?? count($documents))) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">Tersimpan ganda & SHA-256 match</span>
            </div>
            <div class="metric-icon-wrap metric-icon-success">
                🛡️
            </div>
        </div>
    </div>

    <!-- Area Konten Utama Persuratan: Form Registrasi + Buku Agenda -->
    <?php $isSekcab = ($role === 'sekcab'); ?>
    <div class="grid grid-cols-1 <?= $isSekcab ? 'lg:grid-cols-3' : '' ?> gap-6" style="align-items: start;">
        <?php if ($isSekcab): ?>
            <!-- Kolom Form Registrasi / Edit Arsip (Kiri) -->
            <div class="card" id="formArchiveCard" style="position: sticky; top: 88px; z-index: 10;">
                <div class="card-body">
                    <div class="flex items-center justify-between" style="margin-bottom: 1rem;">
                        <div>
                            <h2 id="arcFormTitle" style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin: 0;">Registrasi Arsip Surat</h2>
                            <p id="arcFormSubtitle" class="text-muted" style="font-size: 0.8rem; margin: 0.2rem 0 0;">Simpan dokumen dengan duplikasi otomatis</p>
                        </div>
                        <span id="arcModeBadge" class="badge badge-primary" style="font-size: 0.75rem;">Mode Baru</span>
                    </div>

                    <form id="archiveForm" method="post" action="/sekcab/arsip" enctype="multipart/form-data" data-validate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" id="arc_id" value="">

                        <!-- Judul Surat -->
                        <div class="form-group" style="margin-bottom: 0.85rem;">
                            <label class="form-label" for="arc_title" style="font-size: 0.85rem; font-weight: 600;">Judul Surat / Dokumen <span style="color:#ef4444;">*</span></label>
                            <input class="form-control" id="arc_title" name="title" maxlength="180" placeholder="Contoh: Surat Rekomendasi Beasiswa Kader / SK BPC" required>
                        </div>

                        <!-- Tanggal Surat Dimasukkan -->
                        <div class="form-group" style="margin-bottom: 0.85rem;">
                            <label class="form-label" for="arc_letter_date" style="font-size: 0.85rem; font-weight: 600;">Tanggal Surat Dimasukkan <span style="color:#ef4444;">*</span></label>
                            <input class="form-control" id="arc_letter_date" name="letter_date" type="date" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Sifat Surat & Status Surat -->
                        <div class="grid grid-cols-2 gap-3" style="margin-bottom: 0.85rem;">
                            <div class="form-group">
                                <label class="form-label" for="arc_nature" style="font-size: 0.85rem; font-weight: 600;">Sifat Surat <span style="color:#ef4444;">*</span></label>
                                <select class="form-control" id="arc_nature" name="letter_nature" style="font-size: 0.85rem;">
                                    <option value="Biasa">Biasa (Terbuka)</option>
                                    <option value="Penting">Penting</option>
                                    <option value="Segera">Segera</option>
                                    <option value="Sangat Segera">Sangat Segera</option>
                                    <option value="Rahasia">🔒 Rahasia (Terbatas)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="arc_status" style="font-size: 0.85rem; font-weight: 600;">Status Surat <span style="color:#ef4444;">*</span></label>
                                <select class="form-control" id="arc_status" name="status" style="font-size: 0.85rem;">
                                    <option value="Diarsipkan">Diarsipkan</option>
                                    <option value="Menunggu Tindak Lanjut">Menunggu Tindak Lanjut</option>
                                    <option value="Didisposisi">Didisposisi</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>

                        <!-- Berkas Digital Dokumen (Opsional) -->
                        <div class="form-group" style="margin-bottom: 0.85rem;">
                            <label class="form-label" for="arc_document" style="font-size: 0.85rem; font-weight: 600;">
                                Berkas Surat Digital <span class="text-muted" style="font-weight: 400; font-size: 0.8rem;">(Opsional)</span>
                            </label>
                            <input class="form-control" type="file" id="arc_document" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png" style="padding: 0.4rem;">
                            <div id="arc_file_help" style="margin-top: 0.35rem; font-size: 0.75rem; color: var(--text-muted);">
                                🛡️ <em>Opsional. Jika diunggah, sistem otomatis menduplikasi berkas ke brankas cadangan dengan hash identik (SHA-256).</em>
                            </div>
                        </div>

                        <!-- Ringkasan / Keterangan -->
                        <div class="form-group" style="margin-bottom: 1.15rem;">
                            <label class="form-label" for="arc_description" style="font-size: 0.85rem; font-weight: 600;">Ringkasan / Keterangan Surat</label>
                            <textarea class="form-control" id="arc_description" name="description" rows="2" placeholder="Catatan perihal, alasan kerahasiaan, atau instruksi penanganan..."></textarea>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex gap-2">
                            <button id="btnSubmitArc" class="btn btn-primary" type="submit" style="flex: 1; justify-content: center;">
                                💾 Simpan Arsip & Buat Duplikasi
                            </button>
                            <button id="btnCancelEditArc" class="btn btn-secondary" type="button" onclick="batalEditArc()" style="display: none;">
                                ✖ Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Kolom Tabel Buku Agenda & Verifikasi Duplikasi (Kanan) -->
        <div class="<?= $isSekcab ? 'lg:col-span-2' : 'col-span-1' ?>">
            <?php if ($role === 'admin'): ?>
                <div class="card" style="margin-bottom: 1.25rem; border-left: 4px solid #3b82f6; background: #eff6ff;">
                    <div class="card-body flex items-center justify-between" style="padding: 1rem 1.25rem; gap: 1rem; flex-wrap: wrap;">
                        <div>
                            <div style="font-weight: 700; color: #1e40af; font-size: 0.95rem; display: flex; align-items: center; gap: 0.4rem;">
                                <span>👁️ Mode Pantauan Administrator (Read-Only)</span>
                            </div>
                            <p style="margin: 0.25rem 0 0; font-size: 0.825rem; color: #1e3a8a;">
                                Buku agenda dan registrasi surat dikelola penuh oleh <strong>Sekretaris Cabang (Sekcab)</strong>. Tugas Admin adalah memantau dan mencadangkan data berkas secara berkala.
                            </p>
                        </div>
                        <div>
                            <a href="/admin/berkas/backup?type=archive" class="btn btn-primary btn-sm flex items-center gap-1" style="font-size: 0.8rem; font-weight: 700; text-decoration: none;">
                                <span>📦 Unduh Backup Seluruh Arsip (ZIP)</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="table-card" style="margin-bottom: 1.5rem;">
                <div class="table-header flex items-center justify-between" style="gap: 1rem; flex-wrap: wrap;">
                    <div>
                        <h2 class="table-title" style="margin: 0; font-size: 1.15rem; color: var(--primary);">Buku Agenda Arsip Surat Cabang</h2>
                        <span class="text-muted" style="font-size: 0.8rem;">Daftar surat resmi terdaftar beserta status verifikasi duplikasi redundan</span>
                    </div>

                    <!-- Toolbar Pencarian & Filter Cepat -->
                    <div class="flex items-center gap-2" style="flex-wrap: wrap;">
                        <div style="position: relative; min-width: 220px;">
                            <input type="text" id="arcSearchInput" onkeyup="filterArchiveTable()" placeholder="Cari judul surat atau keterangan..." class="form-control" style="font-size: 0.85rem; padding-left: 2rem; height: 38px;">
                            <span style="position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;">🔍</span>
                        </div>
                        <select id="arcNatureFilter" onchange="filterArchiveTable()" class="form-control" style="font-size: 0.85rem; height: 38px; width: auto;">
                            <option value="">Semua Sifat Surat</option>
                            <option value="Rahasia">🔒 Rahasia</option>
                            <option value="Sangat Segera">Sangat Segera</option>
                            <option value="Penting">Penting</option>
                            <option value="Segera">Segera</option>
                            <option value="Biasa">Biasa</option>
                        </select>
                        <select id="arcStatusFilter" onchange="filterArchiveTable()" class="form-control" style="font-size: 0.85rem; height: 38px; width: auto;">
                            <option value="">Semua Status</option>
                            <option value="Diarsipkan">Diarsipkan</option>
                            <option value="Menunggu Tindak Lanjut">Menunggu Tindak Lanjut</option>
                            <option value="Didisposisi">Didisposisi</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table" id="archiveTable">
                        <thead>
                            <tr>
                                <th style="width: 45px; text-align: center;">No</th>
                                <th style="min-width: 220px;">Judul Surat & Keterangan</th>
                                <th style="min-width: 110px; text-align: center;">Tanggal Dimasukkan</th>
                                <th style="min-width: 100px; text-align: center;">Sifat Surat</th>
                                <th style="min-width: 200px; text-align: center;">Marking & Duplikasi Berkas</th>
                                <th style="min-width: 110px; text-align: center;">Status</th>
                                <th style="width: 90px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($documents)): ?>
                                <?php $no = 1; foreach ($documents as $doc): ?>
                                    <?php
                                        $nature = $doc['letter_nature'] ?? 'Biasa';
                                        $isSecret = in_array($nature, ['Rahasia', 'Sangat Segera'], true);
                                        $natureBadge = match ($nature) {
                                            'Rahasia' => 'badge-danger',
                                            'Sangat Segera' => 'badge-danger',
                                            'Penting' => 'badge-warning',
                                            'Segera' => 'badge-primary',
                                            default => 'badge-neutral',
                                        };
                                        $natureIcon = match ($nature) {
                                            'Rahasia' => '🔒',
                                            'Sangat Segera' => '🚨',
                                            'Penting' => '⚠️',
                                            'Segera' => '⚡',
                                            default => '📄',
                                        };
                                        $status = $doc['status'] ?? 'Diarsipkan';
                                        $statusBadge = match ($status) {
                                            'Diarsipkan' => 'badge-success',
                                            'Menunggu Tindak Lanjut' => 'badge-warning',
                                            'Didisposisi' => 'badge-primary',
                                            'Selesai' => 'badge-neutral',
                                            default => 'badge-secondary',
                                        };
                                        $ext = strtolower(pathinfo($doc['original_name'] ?? '', PATHINFO_EXTENSION));
                                        $fileIcon = match ($ext) {
                                            'pdf' => '📄',
                                            'doc', 'docx' => '📝',
                                            'xls', 'xlsx' => '📊',
                                            'jpg', 'jpeg', 'png' => '🖼️',
                                            default => '📎',
                                        };
                                        $hasDuplicate = !empty($doc['backup_stored_name']) || !empty($doc['is_duplicated']);
                                    ?>
                                    <tr class="arc-row" 
                                        data-id="<?= (int)$doc['id'] ?>"
                                        data-title="<?= e($doc['title']) ?>"
                                        data-letter-date="<?= e($doc['letter_date'] ?? date('Y-m-d', strtotime($doc['created_at']))) ?>"
                                        data-nature="<?= e($nature) ?>"
                                        data-status="<?= e($status) ?>"
                                        data-description="<?= e($doc['description'] ?? '') ?>"
                                        data-filename="<?= e($doc['original_name']) ?>">
                                        <td style="text-align: center; color: var(--text-muted); font-weight: 600;">
                                            <?= $no++ ?>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem; line-height: 1.35;">
                                                <?= e($doc['title']) ?>
                                            </div>
                                            <?php if (!empty($doc['description'])): ?>
                                                <div style="font-size: 0.8rem; color: var(--text-muted); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= e($doc['description']) ?>">
                                                    <?= e($doc['description']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-main);">
                                                📅 <?= !empty($doc['letter_date']) ? date('d M Y', strtotime($doc['letter_date'])) : date('d M Y', strtotime($doc['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="badge <?= $natureBadge ?>" style="font-size: 0.775rem; font-weight: 700; gap: 0.25rem;">
                                                <?= $natureIcon ?> <?= e($nature) ?>
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if (!empty($doc['stored_name'])): ?>
                                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.35rem;">
                                                    <!-- Marking Asli & Duplikasi dengan tombol unduh masing-masing -->
                                                    <div class="flex items-center justify-center gap-1" style="flex-wrap: wrap;">
                                                        <a href="/dokumen/<?= (int)$doc['id'] ?>/unduh" 
                                                           class="btn btn-outline btn-sm" 
                                                           style="padding: 0.2rem 0.45rem; font-size: 0.75rem; border-color: #3b82f6; color: #1d4ed8; font-weight: 600;"
                                                           title="Unduh Berkas Asli (Master)">
                                                            ⭐ Asli
                                                        </a>
                                                        <?php if ($hasDuplicate): ?>
                                                            <a href="/dokumen/<?= (int)$doc['id'] ?>/unduh?salinan=1" 
                                                               class="btn btn-outline btn-sm" 
                                                               style="padding: 0.2rem 0.45rem; font-size: 0.75rem; border-color: #10b981; color: #047857; font-weight: 600;"
                                                               title="Unduh Salinan Duplikasi (Identik 100%)">
                                                                🛡️ Duplikasi
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="badge badge-success" style="font-size: 0.7rem; padding: 0.15rem 0.4rem; font-weight: 600;">
                                                        ✓ SHA-256 Identik
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.25rem;">
                                                    <span class="badge badge-warning" style="font-size: 0.72rem; padding: 0.2rem 0.45rem;">
                                                        📄 Belum ada berkas
                                                    </span>
                                                    <?php if ($isSekcab): ?>
                                                        <button type="button" 
                                                                class="btn btn-outline btn-sm" 
                                                                style="padding: 0.15rem 0.45rem; font-size: 0.7rem; border-style: dashed;" 
                                                                onclick="editArcFromRow(this)"
                                                                title="Unggah berkas untuk surat ini">
                                                            + Unggah Berkas
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="badge <?= $statusBadge ?>" style="font-size: 0.775rem;">
                                                <?= e($status) ?>
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="flex items-center justify-center gap-1">
                                                <?php if ($isSekcab): ?>
                                                    <button type="button" 
                                                            class="btn btn-secondary btn-sm" 
                                                            style="padding: 0.25rem 0.5rem; font-size: 0.8rem;"
                                                            title="Edit data surat ini"
                                                            onclick='editArcFromRow(this)'>
                                                        ✏️
                                                    </button>
                                                    <form method="post" action="/sekcab/arsip/<?= (int)$doc['id'] ?>/delete" data-confirm="Hapus surat '<?= e($doc['title']) ?>' beserta seluruh duplikasi cadangannya?" style="margin: 0; display: inline;">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" type="submit" title="Hapus arsip ini">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <a href="/dokumen/<?= (int)$doc['id'] ?>/unduh" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" title="Unduh Berkas Master">
                                                        ⬇️
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noDataArcRow">
                                    <td colspan="7" style="text-align: center; padding: 3rem 1.5rem;">
                                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🗂️</div>
                                        <div style="font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem;">Belum ada arsip surat yang tersimpan</div>
                                        <div class="text-muted" style="font-size: 0.875rem;">Gunakan form di samping untuk mulai meregistrasi arsip surat baru.</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr id="noMatchArcRow" style="display: none;">
                                <td colspan="7" style="text-align: center; padding: 2rem 1.5rem;">
                                    <div class="text-muted" style="font-size: 0.9rem;">Tidak ditemukan arsip persuratan yang sesuai dengan filter pencarian.</div>
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

    <!-- Script Interaktif untuk Pencarian, Filter, dan Edit Form Arsip -->
    <script>
    function editArcFromRow(btn) {
        const row = btn.closest('.arc-row');
        if (!row) return;

        const id = row.getAttribute('data-id');
        const title = row.getAttribute('data-title');
        const letterDate = row.getAttribute('data-letter-date');
        const nature = row.getAttribute('data-nature');
        const status = row.getAttribute('data-status');
        const description = row.getAttribute('data-description');
        const filename = row.getAttribute('data-filename');

        document.getElementById('arc_id').value = id;
        document.getElementById('arc_title').value = title;
        document.getElementById('arc_letter_date').value = letterDate;
        document.getElementById('arc_nature').value = nature;
        document.getElementById('arc_status').value = status;
        document.getElementById('arc_description').value = description;

        // Tampilan Mode Edit
        document.getElementById('arcFormTitle').innerText = 'Edit Arsip Surat';
        document.getElementById('arcFormSubtitle').innerText = 'Perbarui data surat atau unggah ulang berkas asli';
        
        const modeBadge = document.getElementById('arcModeBadge');
        modeBadge.className = 'badge badge-warning';
        modeBadge.innerText = 'Mode Edit #' + id;

        // Berkas bersifat opsional saat edit
        const docInput = document.getElementById('arc_document');
        docInput.removeAttribute('required');
        const reqMarker = document.getElementById('arc_file_required_marker');
        if (reqMarker) reqMarker.style.display = 'none';

        const fileHelp = document.getElementById('arc_file_help');
        if (fileHelp) {
            fileHelp.innerHTML = 'Berkas saat ini: <strong>' + (filename || '-') + '</strong> (Kosongkan jika tidak mengganti berkas. Jika diganti, sistem akan meregenerasi duplikasi otomatis).';
        }

        document.getElementById('btnSubmitArc').innerHTML = '💾 Perbarui Arsip & Duplikasi';
        document.getElementById('btnCancelEditArc').style.display = 'inline-flex';

        // Scroll halus ke form jika di perangkat mobile
        const formCard = document.getElementById('formArchiveCard');
        if (formCard) {
            formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function batalEditArc() {
        const form = document.getElementById('archiveForm');
        if (form) form.reset();

        document.getElementById('arc_id').value = '';
        document.getElementById('arcFormTitle').innerText = 'Registrasi Arsip Surat';
        document.getElementById('arcFormSubtitle').innerText = 'Simpan dokumen dengan duplikasi otomatis';

        const modeBadge = document.getElementById('arcModeBadge');
        modeBadge.className = 'badge badge-primary';
        modeBadge.innerText = 'Mode Baru';

        const docInput = document.getElementById('arc_document');
        if (docInput) {
            docInput.removeAttribute('required');
        }

        const fileHelp = document.getElementById('arc_file_help');
        if (fileHelp) {
            fileHelp.innerHTML = '🛡️ <em>Opsional. Jika diunggah, sistem otomatis menduplikasi berkas ke brankas cadangan dengan hash identik (SHA-256).</em>';
        }

        document.getElementById('btnSubmitArc').innerHTML = '💾 Simpan Arsip & Buat Duplikasi';
        document.getElementById('btnCancelEditArc').style.display = 'none';
    }

    function filterArchiveTable() {
        const query = (document.getElementById('arcSearchInput')?.value || '').toLowerCase().trim();
        const natureFilter = (document.getElementById('arcNatureFilter')?.value || '').trim();
        const statFilter = (document.getElementById('arcStatusFilter')?.value || '').trim();

        const rows = document.querySelectorAll('.arc-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const title = (row.getAttribute('data-title') || '').toLowerCase();
            const desc = (row.getAttribute('data-description') || '').toLowerCase();
            const rowNature = row.getAttribute('data-nature') || '';
            const rowStat = row.getAttribute('data-status') || '';

            const matchQuery = !query || title.includes(query) || desc.includes(query);
            const matchNature = !natureFilter || rowNature === natureFilter;
            const matchStatus = !statFilter || rowStat === statFilter;

            if (matchQuery && matchNature && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noMatchRow = document.getElementById('noMatchArcRow');
        if (noMatchRow) {
            noMatchRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }
    }
    </script>



<?php elseif ($section === 'finance'): ?>
    <?php
    $totalDocs = count($documents);
    $latestPeriod = !empty($documents[0]['period']) ? $documents[0]['period'] : '-';
    $canManageFinance = ($role === 'bencab');
    $uniquePeriods = [];
    foreach ($documents as $d) {
        if (!empty($d['period']) && !in_array($d['period'], $uniquePeriods, true)) {
            $uniquePeriods[] = $d['period'];
        }
    }
    ?>

    <!-- 1. Ringkasan Metrik Keuangan Cabang -->
    <div class="metrics-grid" style="margin-bottom: 1.5rem;">
        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Total Laporan Keuangan</span>
                <span class="metric-value"><?= number_format((int)($pagination['total'] ?? $totalDocs)) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">
                    Dokumen pembukuan resmi BPC
                </span>
            </div>
            <div class="metric-icon-wrap metric-icon-primary">
                💰
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Periode Terkini</span>
                <span class="metric-value" style="font-size: 1.25rem; word-break: break-word;"><?= e($latestPeriod) ?></span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">
                    Laporan mutakhir yang diarsipkan
                </span>
            </div>
            <div class="metric-icon-wrap metric-icon-secondary">
                📅
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Format Dokumen</span>
                <span class="metric-value" style="font-size: 1.25rem;">PDF, Excel, Word</span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">
                    Tersimpan aman &amp; siap diunduh
                </span>
            </div>
            <div class="metric-icon-wrap metric-icon-success">
                📑
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <span class="metric-label">Status Tata Kelola</span>
                <span class="metric-value" style="font-size: 1.25rem; color: #008f72;">Terbuka &amp; Teruji</span>
                <span class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">
                    Sinkron dengan Pengawasan Ketcab
                </span>
            </div>
            <div class="metric-icon-wrap metric-icon-info">
                ⚖️
            </div>
        </div>
    </div>

    <!-- 2. Banner Ekspor Rekap Keuangan -->
    <div class="card" style="margin-bottom: 1.5rem; background: linear-gradient(to right, #ffffff, #f8fafc); border-left: 4px solid var(--primary);">
        <div class="card-body flex justify-between items-center" style="gap: 1rem; flex-wrap: wrap; padding: 1.25rem 1.5rem;">
            <div>
                <div style="font-weight: 750; font-size: 1rem; color: var(--primary); display: flex; align-items: center; gap: 0.5rem;">
                    <span>📊</span>
                    <span>Ekspor &amp; Rekapitulasi Pembukuan Keuangan</span>
                </div>
                <p class="text-muted" style="margin: 0.25rem 0 0; font-size: 0.85rem;">
                    Ekspor seluruh rekapitulasi laporan keuangan ke format CSV diproses di latar belakang agar tidak memperlambat respon aplikasi.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a class="btn btn-outline btn-sm" href="/ruang-kerja/antrian" style="display: inline-flex; align-items: center; gap: 0.35rem;">
                    ⏱️ <span>Lihat Antrean Ekspor</span>
                </a>
                <form method="post" action="/ruang-kerja/antrian/laporan-keuangan" style="margin: 0;">
                    <?= csrf_field() ?>
                    <button class="btn btn-primary btn-sm" type="submit" style="display: inline-flex; align-items: center; gap: 0.35rem;">
                        📥 <span>Buat Ekspor CSV</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Grid Kerja Laporan Keuangan (Form & Tabel Data) -->
    <div style="display: grid; <?= $canManageFinance ? 'grid-template-columns: minmax(320px, 390px) minmax(0, 1fr);' : 'grid-template-columns: 1fr;' ?> gap: 1.5rem; align-items: start;">
        <?php if ($canManageFinance): ?>
            <!-- Form Input / Edit Laporan Keuangan (Bendahara Cabang) -->
            <div class="card" id="formFinanceCard" style="position: sticky; top: 1.5rem; border-top: 3px solid var(--primary);">
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="flex justify-between items-center" style="margin-bottom: 1rem;">
                        <div>
                            <h2 id="finFormTitle" style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 0;">
                                Tambah Laporan Keuangan
                            </h2>
                            <p id="finFormSubtitle" class="text-muted" style="font-size: 0.8rem; margin: 0.2rem 0 0;">
                                Unggah berkas pembukuan, pertanggungjawaban kas, atau laporan kepanitiaan.
                            </p>
                        </div>
                        <span id="finModeBadge" class="badge badge-primary" style="font-size: 0.75rem;">
                            Mode Baru
                        </span>
                    </div>

                    <form id="financeForm" method="post" action="/bencab/laporan" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" id="fin_id" value="">

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label" for="fin_title" style="font-weight: 600; font-size: 0.875rem;">
                                Judul Laporan <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" id="fin_title" name="title" class="form-control" maxlength="180" 
                                   placeholder="Contoh: LPJ Konfercab, Kas Triwulan I" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label" for="fin_period" style="font-weight: 600; font-size: 0.875rem;">
                                Periode Anggaran / Kegiatan <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" id="fin_period" name="period" class="form-control" maxlength="40" 
                                   placeholder="Contoh: Triwulan I 2026 atau 2026" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label" for="fin_document" style="font-weight: 600; font-size: 0.875rem;">
                                Berkas Dokumen <span id="fin_file_required_marker" style="color: #ef4444;">*</span>
                            </label>
                            <input type="file" id="fin_document" name="document" class="form-control" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png" required>
                            <div id="fin_file_help" class="form-help" style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.35rem;">
                                📁 <em>Format PDF, Excel (.xlsx/.xls), Word, atau Gambar kwitansi. Maksimal 10 MB.</em>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label class="form-label" for="fin_description" style="font-weight: 600; font-size: 0.875rem;">
                                Ringkasan / Keterangan Kas
                            </label>
                            <textarea id="fin_description" name="description" class="form-control" rows="3" 
                                      placeholder="Catatan saldo kas, rincian penerimaan/pengeluaran pokok..."></textarea>
                        </div>

                        <div class="flex items-center gap-2" style="margin-top: 1.25rem;">
                            <button type="submit" id="btnSubmitFin" class="btn btn-primary" style="flex: 1; justify-content: center;">
                                💾 Simpan Laporan
                            </button>
                            <button type="button" id="btnCancelEditFin" class="btn btn-outline" style="display: none;" onclick="batalEditFin()">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Daftar Laporan Keuangan (Tabel Resmi BPC) -->
        <div class="card" style="border-top: 3px solid #00a884;">
            <div class="card-body" style="padding: 1.5rem;">
                <div class="flex justify-between items-center" style="margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin: 0;">
                            Daftar Arsip Laporan Keuangan
                        </h2>
                        <p class="text-muted" style="font-size: 0.85rem; margin: 0.25rem 0 0;">
                            <?= $canManageFinance
                                ? 'Seluruh berkas laporan keuangan yang Anda terbitkan untuk transparansi cabang.'
                                : 'Akses pemantauan berkas laporan pertanggungjawaban keuangan resmi BPC.' ?>
                        </p>
                    </div>
                </div>

                <!-- Filter & Pencarian Cepat -->
                <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 0.85rem 1rem; margin-bottom: 1.25rem;">
                    <div style="display: grid; grid-template-columns: minmax(180px, 1fr) auto auto; gap: 0.75rem; align-items: center;">
                        <input type="text" id="finSearchInput" class="form-control" 
                               placeholder="🔍 Cari judul laporan atau catatan..." oninput="filterFinanceTable()">
                        <select id="finPeriodFilter" class="form-control" onchange="filterFinanceTable()" style="min-width: 150px;">
                            <option value="">Semua Periode</option>
                            <?php foreach ($uniquePeriods as $up): ?>
                                <option value="<?= e($up) ?>"><?= e($up) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('finSearchInput').value=''; document.getElementById('finPeriodFilter').value=''; filterFinanceTable();">
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Tabel Data Laporan Keuangan -->
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">#</th>
                                <th>Laporan &amp; Keterangan</th>
                                <th style="text-align: center; width: 140px;">Periode</th>
                                <th>Berkas Lampiran</th>
                                <th style="text-align: center; width: 140px;">Tanggal Terbit</th>
                                <th style="text-align: center; width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($documents)): ?>
                                <?php $no = 1; foreach ($documents as $doc): ?>
                                    <?php
                                        $ext = strtolower(pathinfo($doc['original_name'] ?? '', PATHINFO_EXTENSION));
                                        $fileIcon = match ($ext) {
                                            'pdf' => '📄',
                                            'doc', 'docx' => '📝',
                                            'xls', 'xlsx' => '📊',
                                            'jpg', 'jpeg', 'png' => '🖼️',
                                            default => '📎',
                                        };
                                        $badgeExt = match ($ext) {
                                            'pdf' => 'badge-danger',
                                            'xls', 'xlsx' => 'badge-success',
                                            'doc', 'docx' => 'badge-primary',
                                            default => 'badge-neutral',
                                        };
                                    ?>
                                    <tr class="fin-row"
                                        data-id="<?= (int)$doc['id'] ?>"
                                        data-title="<?= e($doc['title']) ?>"
                                        data-period="<?= e($doc['period'] ?? '') ?>"
                                        data-description="<?= e($doc['description'] ?? '') ?>"
                                        data-filename="<?= e($doc['original_name'] ?? '') ?>">
                                        <td style="text-align: center; color: var(--text-muted); font-weight: 600;">
                                            <?= $no++ ?>
                                        </td>
                                        <td>
                                            <div style="font-weight: 750; color: var(--primary); font-size: 0.95rem; line-height: 1.35; margin-bottom: 0.25rem;">
                                                <?= e($doc['title']) ?>
                                            </div>
                                            <?php if (!empty($doc['description'])): ?>
                                                <div style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= e($doc['description']) ?>">
                                                    <?= e($doc['description']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-weight: 700; font-size: 0.78rem;">
                                                📅 <?= e($doc['period']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($doc['stored_name'])): ?>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="badge <?= $badgeExt ?>" style="font-size: 0.7rem; text-transform: uppercase;">
                                                        <?= e($ext ?: 'FILE') ?>
                                                    </span>
                                                    <a href="/dokumen/<?= (int)$doc['id'] ?>/unduh" 
                                                       style="font-size: 0.85rem; font-weight: 600; color: var(--primary); text-decoration: underline; text-underline-offset: 2px;"
                                                       title="Unduh Berkas: <?= e($doc['original_name']) ?>">
                                                        <?= e(mb_strimwidth($doc['original_name'], 0, 24, '...')) ?>
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size: 0.8rem;">Tanpa Berkas Digital</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-main);">
                                                <?= date('d M Y', strtotime($doc['created_at'])) ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.72rem;">
                                                <?= e($doc['nama_lengkap'] ?? 'Bencab') ?>
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="flex items-center justify-center gap-1">
                                                <a href="/dokumen/<?= (int)$doc['id'] ?>/unduh" 
                                                   class="btn btn-outline btn-sm" 
                                                   style="padding: 0.25rem 0.55rem; font-size: 0.8rem;" 
                                                   title="Unduh Dokumen">
                                                    ⬇️
                                                </a>
                                                <?php if ($canManageFinance): ?>
                                                    <button type="button" 
                                                            class="btn btn-secondary btn-sm" 
                                                            style="padding: 0.25rem 0.55rem; font-size: 0.8rem;" 
                                                            title="Edit Laporan Keuangan"
                                                            onclick="editFinFromRow(this)">
                                                        ✏️
                                                    </button>
                                                    <form method="post" action="/bencab/laporan/<?= (int)$doc['id'] ?>/delete" 
                                                          data-confirm="Hapus laporan keuangan '<?= e($doc['title']) ?>'?" 
                                                          style="margin: 0; display: inline;">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-danger btn-sm" 
                                                                style="padding: 0.25rem 0.55rem; font-size: 0.8rem;" 
                                                                type="submit" 
                                                                title="Hapus Laporan">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noDataFinRow">
                                    <td colspan="6" style="text-align: center; padding: 3.5rem 1.5rem;">
                                        <div style="font-size: 2.75rem; margin-bottom: 0.5rem;">💰</div>
                                        <div style="font-weight: 750; color: var(--text-main); font-size: 1.05rem; margin-bottom: 0.25rem;">
                                            Belum ada laporan keuangan yang diterbitkan
                                        </div>
                                        <div class="text-muted" style="font-size: 0.875rem;">
                                            <?= $canManageFinance
                                                ? 'Gunakan form di samping untuk mulai mengunggah laporan kas atau pertanggungjawaban resmi.'
                                                : 'Bendahara Cabang belum mengunggah dokumen laporan keuangan.' ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr id="noMatchFinRow" style="display: none;">
                                <td colspan="6" style="text-align: center; padding: 2rem 1.5rem;">
                                    <div class="text-muted" style="font-size: 0.9rem;">
                                        Tidak ditemukan laporan keuangan yang sesuai dengan pencarian.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($pagination)): ?>
                    <div style="padding: 0.75rem 1rem; border-top: 1px solid var(--border-color); margin-top: 1rem;">
                        <?= partial('partials.pagination', $pagination) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Script Interaktif untuk Pencarian, Filter, dan Mode Edit Laporan Keuangan -->
    <script>
    function editFinFromRow(btn) {
        const row = btn.closest('.fin-row');
        if (!row) return;

        const id = row.getAttribute('data-id');
        const title = row.getAttribute('data-title');
        const period = row.getAttribute('data-period');
        const description = row.getAttribute('data-description');
        const filename = row.getAttribute('data-filename');

        document.getElementById('fin_id').value = id;
        document.getElementById('fin_title').value = title;
        document.getElementById('fin_period').value = period;
        document.getElementById('fin_description').value = description;

        // Tampilan Mode Edit
        document.getElementById('finFormTitle').innerText = 'Edit Laporan Keuangan';
        document.getElementById('finFormSubtitle').innerText = 'Perbarui judul, periode, catatan, atau ganti berkas lampiran';

        const modeBadge = document.getElementById('finModeBadge');
        modeBadge.className = 'badge badge-warning';
        modeBadge.innerText = 'Mode Edit #' + id;

        // Berkas bersifat opsional saat edit
        const docInput = document.getElementById('fin_document');
        docInput.removeAttribute('required');
        const reqMarker = document.getElementById('fin_file_required_marker');
        if (reqMarker) reqMarker.style.display = 'none';

        const fileHelp = document.getElementById('fin_file_help');
        if (fileHelp) {
            fileHelp.innerHTML = 'Berkas saat ini: <strong>' + (filename || '-') + '</strong><br><span class="text-muted">(Kosongkan jika tidak ingin mengganti berkas saat ini)</span>';
        }

        document.getElementById('btnSubmitFin').innerHTML = '💾 Perbarui Laporan';
        document.getElementById('btnCancelEditFin').style.display = 'inline-flex';

        // Scroll halus ke form jika di perangkat mobile
        const formCard = document.getElementById('formFinanceCard');
        if (formCard) {
            formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function batalEditFin() {
        const form = document.getElementById('financeForm');
        if (form) form.reset();

        document.getElementById('fin_id').value = '';
        document.getElementById('finFormTitle').innerText = 'Tambah Laporan Keuangan';
        document.getElementById('finFormSubtitle').innerText = 'Unggah berkas pembukuan, pertanggungjawaban kas, atau laporan kepanitiaan.';

        const modeBadge = document.getElementById('finModeBadge');
        modeBadge.className = 'badge badge-primary';
        modeBadge.innerText = 'Mode Baru';

        const docInput = document.getElementById('fin_document');
        if (docInput) {
            docInput.setAttribute('required', 'required');
        }

        const reqMarker = document.getElementById('fin_file_required_marker');
        if (reqMarker) reqMarker.style.display = 'inline';

        const fileHelp = document.getElementById('fin_file_help');
        if (fileHelp) {
            fileHelp.innerHTML = '📁 <em>Format PDF, Excel (.xlsx/.xls), Word, atau Gambar kwitansi. Maksimal 10 MB.</em>';
        }

        document.getElementById('btnSubmitFin').innerHTML = '💾 Simpan Laporan';
        document.getElementById('btnCancelEditFin').style.display = 'none';
    }

    function filterFinanceTable() {
        const query = (document.getElementById('finSearchInput')?.value || '').toLowerCase().trim();
        const periodFilter = (document.getElementById('finPeriodFilter')?.value || '').trim();

        const rows = document.querySelectorAll('.fin-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const title = (row.getAttribute('data-title') || '').toLowerCase();
            const desc = (row.getAttribute('data-description') || '').toLowerCase();
            const rowPeriod = row.getAttribute('data-period') || '';

            const matchQuery = !query || title.includes(query) || desc.includes(query);
            const matchPeriod = !periodFilter || rowPeriod === periodFilter;

            if (matchQuery && matchPeriod) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noMatchRow = document.getElementById('noMatchFinRow');
        if (noMatchRow) {
            noMatchRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }
    }
    </script>

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
                <div>
                    <strong>Koordinasi Pengurus BPC</strong>
                    <small><?= count($participants) ?> pengurus · Ketcab, Sekcab, Bencab, Medko</small>
                </div>
                <div class="coordination-header-actions">
                    <button type="button" class="coordination-action-btn" id="coordinationSoundToggle" title="Nyalakan/Matikan Suara Pesan">
                        <span class="coordination-action-icon" id="soundIcon">🔊</span>
                        <span class="coordination-action-text">Suara</span>
                    </button>
                    <button type="button" class="coordination-action-btn" id="coordinationNotifyToggle" title="Aktifkan Notifikasi Popup Browser">
                        <span class="coordination-action-icon" id="notifyIcon">🔔</span>
                        <span class="coordination-action-text" id="notifyText">Notifikasi</span>
                    </button>
                    <span class="coordination-live" id="coordinationLiveStatus" aria-live="polite">
                        <span class="coordination-live-dot"></span>
                        <span class="coordination-live-label">Real-time</span>
                    </span>
                </div>
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
