<?php
$role = $user['role'] ?? '';
$titles = [
    'inventory' => 'Inventaris GMKI',
    'archive' => 'Buku Agenda & Arsip Persuratan Sekcab',
    'finance' => 'Laporan Keuangan Cabang',
    'strategies' => 'Program Kerja Sekfung',
];
$backPath = match ($role) {
    'admin' => '/admin/dashboard',
    'ketcab' => '/ketcab/dashboard',
    default => '/ruang-kerja',
};
$backLabel = $role === 'admin' ? 'Dashboard Admin' : 'Ruang kerja';
?>
<?php if ($section !== 'archive' && $section !== 'inventory'): ?>
<header class="flex justify-between items-center" style="margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <p class="text-muted" style="margin:0;"><a href="<?= e($backPath) ?>"><?= e($backLabel) ?></a> / <?= e($titles[$section] ?? '') ?></p>
        <h1 style="font-size:1.75rem;font-weight:800;color:var(--primary);margin:.25rem 0;"><?= e($titles[$section] ?? '') ?></h1>
    </div>
</header>
<?php endif; ?>

<?php if ($section === 'inventory'): ?>
    <?php
        $isSekcab = ($role === 'sekcab');
        $totalItemsCount = (int)($summary['total_items'] ?? count($items));
        $totalQty = (float)($summary['total_qty'] ?? 0);
        $totalBaik = (int)($summary['total_baik'] ?? 0);
        $totalPerbaikan = (int)($summary['total_perbaikan'] ?? 0);
        $totalRusak = (int)($summary['total_rusak'] ?? 0);
    ?>
    <div class="ws-page-wrap">
        <!-- 1. Executive Hero Banner Sekcab - Inventaris -->
        <div class="exec-role-banner banner-sekcab">
            <div class="exec-banner-inner">
                <div class="exec-banner-content">
                    <div class="exec-banner-eyebrow">
                        <i class="bi bi-boxes"></i>
                        <span>Tata Kelola Aset &amp; Fasilitas Sekretariat Cabang</span>
                    </div>
                    <h1 class="exec-banner-title">Pangkalan Data Inventaris Aset GMKI</h1>
                    <p class="exec-banner-desc">
                        Pusat pencatatan aset fisik, perlengkapan seremonial, sarana kesekretariatan, dan fasilitas organisasi BPC GMKI Cabang Padang dengan pemantauan kondisi real-time serta pelacakan lokasi penyimpanan.
                    </p>
                    <div class="exec-banner-pills">
                        <span class="exec-banner-pill">
                            <i class="bi bi-box-seam-fill" style="color: #38bdf8;"></i>
                            Total Aset: <code><?= number_format($totalItemsCount) ?> Macam Barang</code>
                        </span>
                        <span class="exec-banner-pill">
                            <i class="bi bi-check2-circle" style="color: #34d399;"></i>
                            Kondisi Baik: <code><?= number_format($totalBaik) ?> Siap Pakai</code>
                        </span>
                        <span class="exec-banner-pill">
                            <i class="bi bi-person-badge" style="color: #fde68a;"></i>
                            Otoritas: <code><?= $isSekcab ? 'Sekretaris Cabang (Sekcab)' : strtoupper(e($role)) ?></code>
                        </span>
                    </div>
                </div>
                <div class="exec-banner-actions">
                    <?php if (can('inventory.manage')): ?>
                        <button type="button" onclick="fokusTambahInventaris()" class="exec-btn exec-btn-gold">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span>+ Tambah Barang</span>
                        </button>
                    <?php endif; ?>
                    <a href="/sekcab/arsip" class="exec-btn exec-btn-sky">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>Arsip Persuratan</span>
                    </a>
                    <a href="/sekcab/berkas" class="exec-btn exec-btn-glass">
                        <i class="bi bi-folder2-open"></i>
                        <span>Direktori Berkas</span>
                    </a>
                    <a href="<?= e($backPath) ?>" class="exec-btn exec-btn-glass">
                        <i class="bi bi-arrow-left"></i>
                        <span><?= e($backLabel) ?></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Ringkasan Metrik Inventaris (4 KPI Cards) -->
        <div class="ws-kpi-grid">
            <div class="ws-kpi-card accent-blue">
                <div>
                    <div class="ws-kpi-top">
                        <div>
                            <div class="ws-kpi-label">Total Jenis Barang</div>
                            <span class="badge badge-primary" style="font-size: 0.66rem; margin-top: 0.25rem;">Aset Terdata</span>
                        </div>
                        <div class="ws-kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                            <i class="bi bi-boxes"></i>
                        </div>
                    </div>
                    <div class="ws-kpi-val"><?= number_format($totalItemsCount) ?></div>
                </div>
                <div class="ws-kpi-sub">
                    <span>Total fisik: <strong><?= number_format($totalQty, 0, ',', '.') ?></strong> unit tercatat</span>
                    <i class="bi bi-box-seam" style="color: #0284c7;"></i>
                </div>
            </div>

            <div class="ws-kpi-card accent-emerald">
                <div>
                    <div class="ws-kpi-top">
                        <div>
                            <div class="ws-kpi-label">Kondisi Baik</div>
                            <span class="badge badge-success" style="font-size: 0.66rem; margin-top: 0.25rem;">Siap Pakai</span>
                        </div>
                        <div class="ws-kpi-icon" style="background: #d1fae5; color: #059669;">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                    <div class="ws-kpi-val" style="color: #059669;"><?= number_format($totalBaik) ?></div>
                </div>
                <div class="ws-kpi-sub">
                    <span>Fasilitas normal &amp; siap beroperasi</span>
                    <i class="bi bi-shield-check" style="color: #059669;"></i>
                </div>
            </div>

            <div class="ws-kpi-card accent-amber">
                <div>
                    <div class="ws-kpi-top">
                        <div>
                            <div class="ws-kpi-label">Perlu Perawatan</div>
                            <span class="badge badge-warning" style="font-size: 0.66rem; margin-top: 0.25rem;">Rusak Ringan</span>
                        </div>
                        <div class="ws-kpi-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="bi bi-tools"></i>
                        </div>
                    </div>
                    <div class="ws-kpi-val" style="color: #d97706;"><?= number_format($totalPerbaikan) ?></div>
                </div>
                <div class="ws-kpi-sub">
                    <span>Memerlukan servis / perbaikan rutin</span>
                    <i class="bi bi-wrench-adjustable" style="color: #d97706;"></i>
                </div>
            </div>

            <div class="ws-kpi-card accent-rose">
                <div>
                    <div class="ws-kpi-top">
                        <div>
                            <div class="ws-kpi-label">Kondisi Rusak / Afkir</div>
                            <span class="badge badge-danger" style="font-size: 0.66rem; margin-top: 0.25rem;">Perlu Tindakan</span>
                        </div>
                        <div class="ws-kpi-icon" style="background: #ffe4e6; color: #e11d48;">
                            <i class="bi bi-exclamation-octagon-fill"></i>
                        </div>
                    </div>
                    <div class="ws-kpi-val" style="color: #e11d48;"><?= number_format($totalRusak) ?></div>
                </div>
                <div class="ws-kpi-sub">
                    <span>Perlu penggantian atau pengafkiran</span>
                    <i class="bi bi-slash-circle" style="color: #e11d48;"></i>
                </div>
            </div>
        </div>

        <!-- 3. Area Konten Utama Inventaris: Form + Tabel -->
        <div class="ws-archive-layout <?= can('inventory.manage') ? '' : 'ws-single-col' ?>">
            <?php if (can('inventory.manage')): ?>
                <!-- Kolom Form Tambah/Edit (Kiri) -->
                <div class="ws-card sticky-form-card" id="formInventoryCard">
                    <div class="ws-card-header">
                        <div class="ws-card-header-left">
                            <div class="ws-card-icon" style="background: #e0f2fe; color: #0284c7;">
                                <i class="bi bi-box-arrow-in-down-right"></i>
                            </div>
                            <div>
                                <h2 id="invFormTitle" class="ws-card-title">Tambah Inventaris</h2>
                                <p id="invFormSubtitle" class="ws-card-subtitle">Catat aset &amp; barang milik cabang</p>
                            </div>
                        </div>
                        <span id="invModeBadge" class="badge badge-primary" style="font-size: 0.72rem; font-weight: 700;">Mode Baru</span>
                    </div>

                    <div class="ws-card-body">
                        <form id="inventoryForm" method="post" action="/sekcab/inventaris" data-validate>
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" id="inv_id" value="">

                            <!-- Nama Barang -->
                            <div class="form-group" style="margin-bottom: 0.95rem;">
                                <label class="form-label" for="inv_name" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                    Nama Barang <span style="color:#ef4444;">*</span>
                                </label>
                                <div style="position: relative;">
                                    <input class="form-control" id="inv_name" name="name" maxlength="180" placeholder="Contoh: Tiang Bendera GMKI, Sound System..." required style="font-size: 0.85rem; padding-left: 2.2rem;">
                                    <i class="bi bi-tag" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem;"></i>
                                </div>
                            </div>

                            <!-- Kategori Barang -->
                            <div class="form-group" style="margin-bottom: 0.95rem;">
                                <label class="form-label" for="inv_category" style="font-size: 0.82rem; font-weight: 700; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                                    <span>Kategori Barang <span style="color:#ef4444;">*</span></span>
                                    <span style="font-size: 0.7rem; color: #64748b; font-weight: normal;">Pilih cepat atau ketik</span>
                                </label>
                                
                                <!-- Category Quick Chips -->
                                <div class="inv-cat-chips" id="invCatChips">
                                    <span class="inv-cat-chip" onclick="pilihKategoriChip('Perlengkapan Cabang', this)">
                                        <i class="bi bi-box-seam"></i> Perlengkapan
                                    </span>
                                    <span class="inv-cat-chip" onclick="pilihKategoriChip('Atribut & Bendera', this)">
                                        <i class="bi bi-flag"></i> Atribut &amp; Bendera
                                    </span>
                                    <span class="inv-cat-chip" onclick="pilihKategoriChip('Elektronik & Multimedia', this)">
                                        <i class="bi bi-display"></i> Elektronik
                                    </span>
                                    <span class="inv-cat-chip" onclick="pilihKategoriChip('Furnitur & Kantor', this)">
                                        <i class="bi bi-lamp"></i> Furnitur
                                    </span>
                                    <span class="inv-cat-chip" onclick="pilihKategoriChip('Perlengkapan Ibadah', this)">
                                        <i class="bi bi-book"></i> Ibadah
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <select id="inv_category_select" class="form-control" onchange="pilihKategoriInv(this.value)" style="font-size: 0.82rem;">
                                        <option value="">-- Pilihan Kategori --</option>
                                        <option value="Perlengkapan Cabang">Perlengkapan Cabang</option>
                                        <option value="Atribut & Bendera">Atribut &amp; Bendera</option>
                                        <option value="Elektronik & Multimedia">Elektronik &amp; Multimedia</option>
                                        <option value="Furnitur & Kantor">Furnitur &amp; Kantor</option>
                                        <option value="Perlengkapan Ibadah">Perlengkapan Ibadah</option>
                                        <option value="Dokumen & Arsip">Dokumen &amp; Arsip</option>
                                        <option value="Lainnya">Lainnya (Ketik Manual)</option>
                                    </select>
                                    <input class="form-control" id="inv_category" name="category" maxlength="100" placeholder="Kategori barang..." required style="font-size: 0.82rem;">
                                </div>
                            </div>

                            <!-- Jumlah & Satuan -->
                            <div class="grid grid-cols-2 gap-3" style="margin-bottom: 0.95rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="inv_quantity" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                        Jumlah <span style="color:#ef4444;">*</span>
                                    </label>
                                    <input class="form-control" id="inv_quantity" name="quantity" type="number" min="0" step="0.01" value="1" required style="font-size: 0.85rem; font-weight: 700;">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="inv_unit" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                        Satuan <span style="color:#ef4444;">*</span>
                                    </label>
                                    <input class="form-control" id="inv_unit" name="unit" maxlength="40" value="unit" placeholder="unit, buah, set..." required style="font-size: 0.85rem;">
                                </div>
                            </div>

                            <!-- Lokasi Penyimpanan -->
                            <div class="form-group" style="margin-bottom: 0.95rem;">
                                <label class="form-label" for="inv_location" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                    Lokasi Penyimpanan
                                </label>
                                <div style="position: relative;">
                                    <input class="form-control" id="inv_location" name="location" maxlength="180" placeholder="Contoh: Sekretariat Cabang / GPdI Karmel / Lemari A" style="font-size: 0.85rem; padding-left: 2.2rem;">
                                    <i class="bi bi-geo-alt" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem;"></i>
                                </div>
                            </div>

                            <!-- Kondisi Barang -->
                            <div class="form-group" style="margin-bottom: 0.95rem;">
                                <label class="form-label" for="inv_condition" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                    Kondisi Fisik Barang <span style="color:#ef4444;">*</span>
                                </label>
                                <select class="form-control" id="inv_condition" name="item_condition" style="font-size: 0.84rem;">
                                    <option value="Baik">🟢 Baik (Berfungsi Normal &amp; Siap Digunakan)</option>
                                    <option value="Perlu Perbaikan">🟡 Perlu Perbaikan (Rusak Ringan / Butuh Perawatan)</option>
                                    <option value="Rusak">🔴 Rusak (Tidak Dapat Digunakan / Butuh Diganti)</option>
                                </select>
                            </div>

                            <!-- Catatan / Keterangan -->
                            <div class="form-group" style="margin-bottom: 1.15rem;">
                                <label class="form-label" for="inv_notes" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                    Catatan / Keterangan Tambahan
                                </label>
                                <textarea class="form-control" id="inv_notes" name="notes" rows="3" placeholder="Tuliskan petunjuk perawatan, asal usul, riwayat perbaikan, atau instruksi peminjaman..." style="font-size: 0.84rem;"></textarea>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex gap-2">
                                <button id="btnSubmitInv" class="btn btn-primary" type="submit" style="flex: 1; justify-content: center; padding: 0.65rem 1rem; font-weight: 700; border-radius: 11px;">
                                    <i class="bi bi-save2-fill"></i>
                                    <span>Simpan Inventaris</span>
                                </button>
                                <button id="btnCancelEditInv" class="btn btn-secondary" type="button" onclick="batalEditInv()" style="display: none; border-radius: 11px; font-weight: 700;">
                                    <i class="bi bi-x-lg"></i>
                                    <span>Batal</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Kolom Tabel Daftar Inventaris (Kanan) -->
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-header-left">
                        <div class="ws-card-icon" style="background: #f0f9ff; color: #0f3d64;">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <div>
                            <h2 class="ws-card-title">Daftar Aset &amp; Inventaris Cabang</h2>
                            <p class="ws-card-subtitle">Pangkalan data seluruh inventaris yang tercatat di BPC GMKI Cabang Padang</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2" style="flex-wrap: wrap;">
                        <span class="badge badge-neutral" style="font-size: 0.74rem; font-weight: 700;" id="invCounterBadge">
                            <i class="bi bi-boxes"></i> <?= number_format(count($items)) ?> Macam Barang
                        </span>
                        <?php if (can('inventory.manage')): ?>
                            <button type="button" onclick="fokusTambahInventaris()" class="btn btn-outline btn-sm" style="font-size: 0.76rem; font-weight: 700; border-radius: 9px;">
                                <i class="bi bi-plus-lg"></i> Tambah Baru
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Toolbar Pencarian & Filter Cepat -->
                <div class="ws-filter-bar">
                    <div class="ws-search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="invSearchInput" onkeyup="filterInventoryTable()" placeholder="Cari nama barang, kategori, lokasi simpan, atau catatan..." class="ws-search-input">
                    </div>
                    <div class="ws-filter-group">
                        <select id="invConditionFilter" onchange="filterInventoryTable()" class="ws-filter-select">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik">Kondisi: Baik</option>
                            <option value="Perlu Perbaikan">Kondisi: Perlu Perbaikan</option>
                            <option value="Rusak">Kondisi: Rusak</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Kategori Cepat (Interactive Pills Bar) -->
                <div style="padding: 0.65rem 1.4rem; background: #ffffff; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                    <span style="font-size: 0.72rem; font-weight: 700; color: #64748b; margin-right: 0.2rem; text-transform: uppercase; letter-spacing: 0.04em;">
                        Kategori:
                    </span>
                    <button type="button" class="quick-pill active" onclick="filterByCategory('', this)">
                        Semua
                    </button>
                    <button type="button" class="quick-pill" onclick="filterByCategory('Perlengkapan Cabang', this)">
                        <i class="bi bi-box-seam"></i> Perlengkapan
                    </button>
                    <button type="button" class="quick-pill" onclick="filterByCategory('Atribut & Bendera', this)">
                        <i class="bi bi-flag"></i> Atribut &amp; Bendera
                    </button>
                    <button type="button" class="quick-pill" onclick="filterByCategory('Elektronik & Multimedia', this)">
                        <i class="bi bi-display"></i> Elektronik
                    </button>
                    <button type="button" class="quick-pill" onclick="filterByCategory('Furnitur & Kantor', this)">
                        <i class="bi bi-lamp"></i> Furnitur
                    </button>
                    <button type="button" class="quick-pill" onclick="filterByCategory('Perlengkapan Ibadah', this)">
                        <i class="bi bi-book"></i> Ibadah
                    </button>
                </div>

                <!-- Tabel Data Inventaris -->
                <div class="ws-table-wrap">
                    <table class="ws-table" id="inventoryTable">
                        <thead>
                            <tr>
                                <th style="width: 45px; text-align: center;">No</th>
                                <th>Nama Barang &amp; Kategori</th>
                                <th style="width: 110px; text-align: center;">Jumlah Fisik</th>
                                <th>Lokasi Penyimpanan</th>
                                <th style="width: 135px; text-align: center;">Kondisi</th>
                                <th>Catatan / Keterangan</th>
                                <?php if (can('inventory.manage')): ?>
                                    <th style="width: 125px; text-align: center;">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php $no = 1; foreach ($items as $item): ?>
                                    <?php
                                        // Condition Pill Styling
                                        $condClass = match ($item['item_condition']) {
                                            'Baik' => 'inv-cond-baik',
                                            'Perlu Perbaikan' => 'inv-cond-perbaikan',
                                            'Rusak' => 'inv-cond-rusak',
                                            default => 'badge-neutral',
                                        };
                                        $condIcon = match ($item['item_condition']) {
                                            'Baik' => 'bi-check-circle-fill',
                                            'Perlu Perbaikan' => 'bi-exclamation-triangle-fill',
                                            'Rusak' => 'bi-x-octagon-fill',
                                            default => 'bi-info-circle',
                                        };

                                        // Category Badge Styling
                                        $catLower = strtolower($item['category'] ?? '');
                                        $catBadgeClass = 'inv-cat-default';
                                        $catIcon = 'bi-tag-fill';
                                        if (str_contains($catLower, 'perlengkapan')) {
                                            $catBadgeClass = 'inv-cat-perlengkapan';
                                            $catIcon = 'bi-box-seam-fill';
                                        } elseif (str_contains($catLower, 'atribut') || str_contains($catLower, 'bendera')) {
                                            $catBadgeClass = 'inv-cat-atribut';
                                            $catIcon = 'bi-flag-fill';
                                        } elseif (str_contains($catLower, 'elektronik') || str_contains($catLower, 'multimedia')) {
                                            $catBadgeClass = 'inv-cat-elektronik';
                                            $catIcon = 'bi-display';
                                        } elseif (str_contains($catLower, 'ibadah')) {
                                            $catBadgeClass = 'inv-cat-ibadah';
                                            $catIcon = 'bi-book-half';
                                        } elseif (str_contains($catLower, 'furnitur') || str_contains($catLower, 'kantor')) {
                                            $catBadgeClass = 'inv-cat-furnitur';
                                            $catIcon = 'bi-lamp';
                                        } elseif (str_contains($catLower, 'dokumen') || str_contains($catLower, 'arsip')) {
                                            $catBadgeClass = 'inv-cat-arsip';
                                            $catIcon = 'bi-archive-fill';
                                        }

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
                                        <td style="text-align: center; color: #94a3b8; font-weight: 700; font-size: 0.82rem;">
                                            <?= $no++ ?>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; color: #0f172a; font-size: 0.92rem; margin-bottom: 0.3rem;">
                                                <?= e($item['name']) ?>
                                            </div>
                                            <span class="inv-cat-badge <?= $catBadgeClass ?>">
                                                <i class="bi <?= $catIcon ?>"></i>
                                                <?= e($item['category']) ?>
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="inv-qty-badge">
                                                <span class="inv-qty-num"><?= $qtyDisplay ?></span>
                                                <span class="inv-qty-unit"><?= e($item['unit']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($item['location'])): ?>
                                                <div class="inv-loc-tag">
                                                    <i class="bi bi-geo-alt-fill" style="color: #0284c7; font-size: 0.78rem;"></i>
                                                    <span><?= e($item['location']) ?></span>
                                                </div>
                                            <?php else: ?>
                                                <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic;">
                                                    <i class="bi bi-geo"></i> Belum ditentukan
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="inv-cond-pill <?= $condClass ?>">
                                                <i class="bi <?= $condIcon ?>"></i>
                                                <?= e($item['item_condition']) ?>
                                            </span>
                                        </td>
                                        <td style="max-width: 240px;">
                                            <?php if (!empty($item['notes'])): ?>
                                                <div style="font-size: 0.8rem; color: #475569; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= e($item['notes']) ?>">
                                                    <?= e($item['notes']) ?>
                                                </div>
                                            <?php else: ?>
                                                <span style="font-size: 0.78rem; color: #94a3b8;">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if (can('inventory.manage')): ?>
                                            <td style="text-align: center;">
                                                <div class="flex items-center justify-center gap-1">
                                                    <button type="button" 
                                                            class="btn btn-outline btn-sm" 
                                                            style="padding: 0.3rem 0.6rem; font-size: 0.76rem; font-weight: 700; border-radius: 8px; color: #0284c7; border-color: #bae6fd;" 
                                                            title="Edit data inventaris ini"
                                                            onclick='editInvFromRow(this)'>
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </button>
                                                    <form method="post" action="/sekcab/inventaris/<?= (int)$item['id'] ?>/delete" data-confirm="Hapus data inventaris '<?= e($item['name']) ?>'?" style="margin: 0; display: inline;">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-danger btn-sm" style="padding: 0.3rem 0.55rem; font-size: 0.76rem; border-radius: 8px;" type="submit" title="Hapus inventaris">
                                                            <i class="bi bi-trash3-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noDataRow">
                                    <td colspan="<?= can('inventory.manage') ? '7' : '6' ?>" style="text-align: center; padding: 3.5rem 1.5rem;">
                                        <div style="width: 54px; height: 54px; border-radius: 14px; background: #e0f2fe; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 0.85rem;">
                                            <i class="bi bi-boxes"></i>
                                        </div>
                                        <div style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin-bottom: 0.35rem;">Belum Ada Data Inventaris</div>
                                        <div style="font-size: 0.85rem; color: #64748b; max-width: 420px; margin: 0 auto 1.25rem;">
                                            Gunakan formulir di samping untuk mulai mencatat fasilitas dan aset fisik milik GMKI Cabang Padang.
                                        </div>
                                        <?php if (can('inventory.manage')): ?>
                                            <button type="button" onclick="fokusTambahInventaris()" class="btn btn-primary btn-sm" style="font-weight: 700; border-radius: 9px; padding: 0.5rem 1rem;">
                                                <i class="bi bi-plus-circle-fill"></i> Tambah Barang Sekarang
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr id="noMatchRow" style="display: none;">
                                <td colspan="<?= can('inventory.manage') ? '7' : '6' ?>" style="text-align: center; padding: 2.5rem 1.5rem;">
                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #f1f5f9; color: #64748b; display: inline-flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 0.65rem;">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 0.25rem;">Tidak Ditemukan Hasil yang Cocok</div>
                                    <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 0.85rem;">Coba periksa kembali kata kunci pencarian atau reset filter kategori.</div>
                                    <button type="button" onclick="resetInventoryFilters()" class="btn btn-outline btn-sm" style="font-size: 0.78rem; font-weight: 700; border-radius: 8px;">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset Semua Filter
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($pagination)): ?>
                    <div style="padding: 0.85rem 1.25rem; border-top: 1px solid #e2e8f0; background: #fafafa;">
                        <?= partial('partials.pagination', $pagination) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Script Interaktif untuk Pencarian, Filter Cepat, Chip, dan Form Edit -->
    <script>
    let activeCategoryFilter = '';

    function fokusTambahInventaris() {
        const formCard = document.getElementById('formInventoryCard');
        if (formCard) {
            formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            formCard.classList.remove('inv-form-glow');
            void formCard.offsetWidth; // trigger reflow
            formCard.classList.add('inv-form-glow');
            setTimeout(() => {
                const nameInput = document.getElementById('inv_name');
                if (nameInput) nameInput.focus();
            }, 300);
        }
    }

    function pilihKategoriChip(val, chipEl) {
        document.querySelectorAll('.inv-cat-chip').forEach(el => el.classList.remove('active'));
        if (chipEl) chipEl.classList.add('active');

        document.getElementById('inv_category').value = val;
        const selectEl = document.getElementById('inv_category_select');
        let matched = false;
        for (let i = 0; i < selectEl.options.length; i++) {
            if (selectEl.options[i].value === val) {
                selectEl.selectedIndex = i;
                matched = true;
                break;
            }
        }
        if (!matched) {
            selectEl.value = 'Lainnya';
        }
    }

    function pilihKategoriInv(val) {
        document.querySelectorAll('.inv-cat-chip').forEach(el => el.classList.remove('active'));
        if (!val || val === 'Lainnya') {
            document.getElementById('inv_category').value = '';
            document.getElementById('inv_category').focus();
        } else {
            document.getElementById('inv_category').value = val;
            // Highlight chip if matches
            document.querySelectorAll('.inv-cat-chip').forEach(el => {
                if (el.textContent.includes(val) || val.includes(el.textContent.trim())) {
                    el.classList.add('active');
                }
            });
        }
    }

    function filterByCategory(cat, btnEl) {
        activeCategoryFilter = cat.toLowerCase().trim();
        document.querySelectorAll('.quick-pill').forEach(el => el.classList.remove('active'));
        if (btnEl) btnEl.classList.add('active');
        filterInventoryTable();
    }

    function resetInventoryFilters() {
        document.getElementById('invSearchInput').value = '';
        document.getElementById('invConditionFilter').value = '';
        activeCategoryFilter = '';
        document.querySelectorAll('.quick-pill').forEach((el, idx) => {
            if (idx === 0) el.classList.add('active');
            else el.classList.remove('active');
        });
        filterInventoryTable();
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

        // Select and Chip sync
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

        document.querySelectorAll('.inv-cat-chip').forEach(el => {
            if (category && (el.textContent.includes(category) || category.includes(el.textContent.trim()))) {
                el.classList.add('active');
            } else {
                el.classList.remove('active');
            }
        });

        document.getElementById('invFormTitle').textContent = 'Edit Data Inventaris';
        document.getElementById('invFormSubtitle').textContent = 'Memperbarui: ' + name;
        document.getElementById('invModeBadge').textContent = 'Mode Edit #' + id;
        document.getElementById('invModeBadge').className = 'badge badge-warning';
        document.getElementById('btnSubmitInv').innerHTML = '<i class="bi bi-check2-circle"></i> <span>Simpan Perubahan</span>';
        document.getElementById('btnCancelEditInv').style.display = 'inline-flex';

        fokusTambahInventaris();
    }

    function batalEditInv() {
        document.getElementById('inv_id').value = '';
        document.getElementById('inventoryForm').reset();
        document.getElementById('inv_quantity').value = '1';
        document.getElementById('inv_unit').value = 'unit';
        document.getElementById('inv_condition').value = 'Baik';
        document.querySelectorAll('.inv-cat-chip').forEach(el => el.classList.remove('active'));

        document.getElementById('invFormTitle').textContent = 'Tambah Inventaris';
        document.getElementById('invFormSubtitle').textContent = 'Catat aset & barang milik cabang';
        document.getElementById('invModeBadge').textContent = 'Mode Baru';
        document.getElementById('invModeBadge').className = 'badge badge-primary';
        document.getElementById('btnSubmitInv').innerHTML = '<i class="bi bi-save2-fill"></i> <span>Simpan Inventaris</span>';
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
            const matchesCat = !activeCategoryFilter || cat.includes(activeCategoryFilter);

            if (matchesQuery && matchesCond && matchesCat) {
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
    <?php
        $isSekcab = ($role === 'sekcab');
        $totalArsipCount = (int)($summary['total_arsip'] ?? count($documents));
        $totalRahasiaCount = (int)($summary['total_rahasia'] ?? 0);
        $totalPentingCount = (int)($summary['total_penting'] ?? 0);
        $totalDuplikasiCount = (int)($summary['total_duplikasi'] ?? count($documents));
    ?>
    <div class="ws-page-wrap">
        <!-- 1. Executive Hero Banner Sekcab -->
        <div class="exec-role-banner banner-sekcab">
            <div class="exec-banner-inner">
                <div>
                    <div class="exec-banner-eyebrow">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>Tata Usaha &amp; Kearsipan Sekretariat Cabang</span>
                    </div>
                    <h1 class="exec-banner-title">Buku Agenda &amp; Arsip Persuratan Sekcab</h1>
                    <p class="exec-banner-desc">
                        Pusat registrasi surat resmi organisasi, klasifikasi kerahasiaan dokumen, serta perlindungan berlapis dengan sistem duplikasi cadangan otomatis berverifikasi <strong>Checksum SHA-256</strong>.
                    </p>
                    <div class="exec-banner-pills">
                        <span class="exec-banner-pill">
                            <i class="bi bi-shield-fill-check" style="color: #34d399;"></i>
                            Redundansi Ganda: <code>Master + Backup Identik</code>
                        </span>
                        <span class="exec-banner-pill">
                            <i class="bi bi-fingerprint" style="color: #38bdf8;"></i>
                            Integritas Berkas: <code>SHA-256 Verified</code>
                        </span>
                        <span class="exec-banner-pill">
                            <i class="bi bi-person-badge" style="color: #fde68a;"></i>
                            Akses: <code><?= $isSekcab ? 'Mode Kelola Sekcab' : 'Mode Pantau (' . strtoupper(e($role)) . ')' ?></code>
                        </span>
                    </div>
                </div>
                <div class="exec-banner-actions">
                    <a href="/sekcab/berkas" class="exec-btn exec-btn-sky">
                        <i class="bi bi-folder2-open"></i>
                        <span>Direktori Berkas</span>
                    </a>
                    <a href="/sekcab/inventaris" class="exec-btn exec-btn-glass">
                        <i class="bi bi-box-seam"></i>
                        <span>Inventaris Cabang</span>
                    </a>
                    <?php if ($role === 'admin'): ?>
                        <a href="/admin/berkas/backup?type=archive" class="exec-btn exec-btn-gold">
                            <i class="bi bi-file-earmark-zip-fill"></i>
                            <span>Backup ZIP</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= e($backPath) ?>" class="exec-btn exec-btn-glass">
                            <i class="bi bi-arrow-left"></i>
                            <span><?= e($backLabel) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 2. Ringkasan Metrik Arsip Persuratan (4 KPI Cards) -->
        <div class="ws-kpi-grid">
            <div class="ws-kpi-card accent-blue">
                <div>
                    <div class="ws-kpi-top">
                        <div>
                            <div class="ws-kpi-label">Total Arsip Terdaftar</div>
                            <span class="badge badge-primary" style="font-size: 0.66rem; margin-top: 0.25rem;">Buku Agenda</span>
                        </div>
                        <div class="ws-kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                            <i class="bi bi-journal-richtext"></i>
                        </div>
                    </div>
                    <div class="ws-kpi-val"><?= number_format($totalArsipCount) ?></div>
                </div>
                <div class="ws-kpi-sub">
                    <span>Dokumen resmi di repositori cabang</span>
                    <i class="bi bi-check2-circle" style="color: #0284c7;"></i>
                </div>
            </div>

            <div class="ws-kpi-card accent-rose">
                <div>
                    <div class="ws-kpi-top">
                        <div>
                            <div class="ws-kpi-label">Arsip Rahasia</div>
                            <span class="badge badge-danger" style="font-size: 0.66rem; margin-top: 0.25rem;">Akses Terbatas</span>
                        </div>
                        <div class="ws-kpi-icon" style="background: #ffe4e6; color: #e11d48;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                    </div>
                    <div class="ws-kpi-val" style="color: #e11d48;"><?= number_format($totalRahasiaCount) ?></div>
                </div>
                <div class="ws-kpi-sub">
                    <span>Klasifikasi kerahasiaan khusus BPC</span>
                    <i class="bi bi-lock-fill" style="color: #e11d48;"></i>
                </div>
            </div>

            <div class="ws-kpi-card accent-amber">
                <div>
                    <div class="ws-kpi-top">
                        <div>
                            <div class="ws-kpi-label">Penting &amp; Segera</div>
                            <span class="badge badge-warning" style="font-size: 0.66rem; margin-top: 0.25rem;">Prioritas Tinggi</span>
                        </div>
                        <div class="ws-kpi-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                    </div>
                    <div class="ws-kpi-val" style="color: #d97706;"><?= number_format($totalPentingCount) ?></div>
                </div>
                <div class="ws-kpi-sub">
                    <span>Memerlukan disposisi / tindak lanjut</span>
                    <i class="bi bi-clock-history" style="color: #d97706;"></i>
                </div>
            </div>

            <div class="ws-kpi-card accent-emerald">
                <div>
                    <div class="ws-kpi-top">
                        <div>
                            <div class="ws-kpi-label">Duplikasi Cadangan</div>
                            <span class="badge badge-success" style="font-size: 0.66rem; margin-top: 0.25rem;">100% Identik</span>
                        </div>
                        <div class="ws-kpi-icon" style="background: #d1fae5; color: #059669;">
                            <i class="bi bi-hdd-network-fill"></i>
                        </div>
                    </div>
                    <div class="ws-kpi-val" style="color: #059669;"><?= number_format($totalDuplikasiCount) ?></div>
                </div>
                <div class="ws-kpi-sub">
                    <span>Salinan redundan terverifikasi SHA-256</span>
                    <i class="bi bi-shield-check" style="color: #059669;"></i>
                </div>
            </div>
        </div>

        <!-- 3. Banner Protokol Keamanan & Redundansi Berkas -->
        <div class="ws-protocol-banner">
            <div class="ws-protocol-left">
                <div class="ws-protocol-icon">
                    <i class="bi bi-shield-shaded"></i>
                </div>
                <div>
                    <h3 class="ws-protocol-title">
                        <span>Protokol Keamanan &amp; Redundansi Duplikasi Arsip Cabang</span>
                        <span class="badge badge-primary" style="font-size: 0.68rem;">Otomatis Aktif</span>
                    </h3>
                    <p class="ws-protocol-desc">
                        Setiap berkas surat digital yang diunggah otomatis disimpan ke dalam <strong>2 brankas terpisah</strong>: <strong>Berkas Asli (Master)</strong> dan <strong>Salinan Duplikasi (Redundant Backup)</strong> dengan validasi <em>checksum SHA-256</em> identik untuk melindungi arsip organisasi dari kerusakan maupun manipulasi data.
                    </p>
                </div>
            </div>
            <div class="ws-protocol-pills">
                <span class="ws-protocol-pill">
                    <i class="bi bi-file-earmark-check-fill" style="color: #0284c7;"></i>
                    Master Storage
                </span>
                <span class="ws-protocol-pill">
                    <i class="bi bi-files" style="color: #059669;"></i>
                    Redundant Vault
                </span>
                <?php if ($role === 'admin'): ?>
                    <a href="/admin/berkas/backup?type=archive" class="ws-protocol-pill" style="background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; text-decoration: none;">
                        <i class="bi bi-cloud-arrow-down-fill"></i>
                        Unduh Paket ZIP Admin
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- 4. Area Konten Utama Persuratan: Form Registrasi + Buku Agenda -->
        <div class="ws-archive-layout <?= $isSekcab ? '' : 'ws-single-col' ?>">
            <?php if ($isSekcab): ?>
                <!-- Kolom Form Registrasi / Edit Arsip (Kiri) -->
                <div class="ws-card sticky-form-card" id="formArchiveCard">
                    <div class="ws-card-header">
                        <div class="ws-card-header-left">
                            <div class="ws-card-icon" style="background: #e0f2fe; color: #0284c7;">
                                <i class="bi bi-file-earmark-plus-fill"></i>
                            </div>
                            <div>
                                <h2 id="arcFormTitle" class="ws-card-title">Registrasi Arsip Surat</h2>
                                <p id="arcFormSubtitle" class="ws-card-subtitle">Simpan dokumen dengan duplikasi otomatis</p>
                            </div>
                        </div>
                        <span id="arcModeBadge" class="badge badge-primary" style="font-size: 0.72rem; font-weight: 700;">Mode Baru</span>
                    </div>

                    <div class="ws-card-body">
                        <form id="archiveForm" method="post" action="/sekcab/arsip" enctype="multipart/form-data" data-validate>
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" id="arc_id" value="">

                            <!-- Judul Surat -->
                            <div class="form-group" style="margin-bottom: 0.95rem;">
                                <label class="form-label" for="arc_title" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                    Judul / Perihal Surat <span style="color:#ef4444;">*</span>
                                </label>
                                <input class="form-control" id="arc_title" name="title" maxlength="180" placeholder="Contoh: SK Kepanitiaan Maper / Surat Undangan BPC" required style="font-size: 0.85rem;">
                            </div>

                            <!-- Tanggal Surat Dimasukkan -->
                            <div class="form-group" style="margin-bottom: 0.95rem;">
                                <label class="form-label" for="arc_letter_date" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                    Tanggal Surat Dimasukkan <span style="color:#ef4444;">*</span>
                                </label>
                                <input class="form-control" id="arc_letter_date" name="letter_date" type="date" value="<?= date('Y-m-d') ?>" required style="font-size: 0.85rem;">
                            </div>

                            <!-- Sifat Surat & Status Surat -->
                            <div class="grid grid-cols-2 gap-3" style="margin-bottom: 0.95rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="arc_nature" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                        Sifat Surat <span style="color:#ef4444;">*</span>
                                    </label>
                                    <select class="form-control" id="arc_nature" name="letter_nature" style="font-size: 0.83rem;">
                                        <option value="Biasa">Biasa (Terbuka)</option>
                                        <option value="Penting">Penting</option>
                                        <option value="Segera">Segera</option>
                                        <option value="Sangat Segera">Sangat Segera</option>
                                        <option value="Rahasia">Rahasia (Terbatas)</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="arc_status" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                        Status Surat <span style="color:#ef4444;">*</span>
                                    </label>
                                    <select class="form-control" id="arc_status" name="status" style="font-size: 0.83rem;">
                                        <option value="Diarsipkan">Diarsipkan</option>
                                        <option value="Menunggu Tindak Lanjut">Menunggu Tindak Lanjut</option>
                                        <option value="Didisposisi">Didisposisi</option>
                                        <option value="Selesai">Selesai</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Berkas Digital Dokumen (Opsional) -->
                            <div class="form-group" style="margin-bottom: 0.95rem;">
                                <label class="form-label" for="arc_document" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                    Unggah Berkas Digital <span class="text-muted" style="font-weight: 500; font-size: 0.76rem;">(Opsional)</span>
                                </label>
                                <div class="ws-upload-zone">
                                    <div class="ws-upload-header">
                                        <div class="ws-upload-icon">
                                            <i class="bi bi-cloud-arrow-up-fill"></i>
                                        </div>
                                        <div style="font-size: 0.76rem; color: #334155; line-height: 1.35;">
                                            <strong>Pilih dokumen surat</strong> untuk dienkripsi &amp; diduplikasi otomatis
                                        </div>
                                    </div>
                                    <input class="form-control" type="file" id="arc_document" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png" style="padding: 0.38rem; font-size: 0.8rem; background: #ffffff;">
                                    <div id="arc_file_help" style="margin-top: 0.5rem; font-size: 0.73rem; color: #475569; line-height: 1.45;">
                                        <i class="bi bi-shield-check" style="color: #059669;"></i>
                                        <em>Jika diunggah, berkas otomatis disalin ke brankas cadangan dengan hash SHA-256 identik.</em>
                                    </div>
                                    <div class="ws-upload-tags">
                                        <span class="ws-upload-tag">PDF</span>
                                        <span class="ws-upload-tag">DOC / DOCX</span>
                                        <span class="ws-upload-tag">XLS / XLSX</span>
                                        <span class="ws-upload-tag">JPG / PNG</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Ringkasan / Keterangan -->
                            <div class="form-group" style="margin-bottom: 1.15rem;">
                                <label class="form-label" for="arc_description" style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">
                                    Ringkasan / Catatan Disposisi
                                </label>
                                <textarea class="form-control" id="arc_description" name="description" rows="3" placeholder="Tuliskan nomor surat, perihal ringkas, tujuan surat, atau catatan tindak lanjut..." style="font-size: 0.84rem;"></textarea>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex gap-2">
                                <button id="btnSubmitArc" class="btn btn-primary" type="submit" style="flex: 1; justify-content: center; padding: 0.65rem 1rem; font-weight: 700; border-radius: 11px;">
                                    <i class="bi bi-hdd-stack-fill"></i>
                                    <span>Simpan Arsip &amp; Duplikasi</span>
                                </button>
                                <button id="btnCancelEditArc" class="btn btn-secondary" type="button" onclick="batalEditArc()" style="display: none; border-radius: 11px; font-weight: 700;">
                                    <i class="bi bi-x-lg"></i>
                                    <span>Batal</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Kolom Tabel Buku Agenda & Verifikasi Duplikasi (Kanan) -->
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-header-left">
                        <div class="ws-card-icon" style="background: #f0f9ff; color: #0f3d64;">
                            <i class="bi bi-table"></i>
                        </div>
                        <div>
                            <h2 class="ws-card-title">Buku Agenda Arsip Surat Cabang</h2>
                            <p class="ws-card-subtitle">Daftar surat resmi beserta status verifikasi berkas asli &amp; salinan cadangan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2" style="flex-wrap: wrap;">
                        <span class="badge badge-neutral" style="font-size: 0.74rem; font-weight: 700;">
                            <i class="bi bi-collection"></i> <?= number_format(count($documents)) ?> Baris Ditampilkan
                        </span>
                        <a href="/sekcab/berkas" class="btn btn-outline btn-sm" style="font-size: 0.76rem; font-weight: 700; border-radius: 9px; text-decoration: none;">
                            <i class="bi bi-search"></i> Pencarian Lanjutan
                        </a>
                    </div>
                </div>

                <!-- Toolbar Pencarian & Filter Cepat -->
                <div class="ws-filter-bar">
                    <div class="ws-search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="arcSearchInput" onkeyup="filterArchiveTable()" placeholder="Cari judul surat, nomor, atau keterangan..." class="ws-search-input">
                    </div>
                    <div class="ws-filter-group">
                        <select id="arcNatureFilter" onchange="filterArchiveTable()" class="ws-filter-select">
                            <option value="">Semua Sifat Surat</option>
                            <option value="Rahasia">Rahasia</option>
                            <option value="Sangat Segera">Sangat Segera</option>
                            <option value="Penting">Penting</option>
                            <option value="Segera">Segera</option>
                            <option value="Biasa">Biasa</option>
                        </select>
                        <select id="arcStatusFilter" onchange="filterArchiveTable()" class="ws-filter-select">
                            <option value="">Semua Status</option>
                            <option value="Diarsipkan">Diarsipkan</option>
                            <option value="Menunggu Tindak Lanjut">Menunggu Tindak Lanjut</option>
                            <option value="Didisposisi">Didisposisi</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="ws-table-wrap">
                    <table class="ws-table" id="archiveTable">
                        <thead>
                            <tr>
                                <th style="width: 48px; text-align: center;">No</th>
                                <th style="min-width: 230px;">Perihal Surat &amp; Keterangan</th>
                                <th style="min-width: 118px; text-align: center;">Tgl Registrasi</th>
                                <th style="min-width: 110px; text-align: center;">Sifat Surat</th>
                                <th style="min-width: 215px; text-align: center;">Brankas Berkas &amp; Duplikasi</th>
                                <th style="min-width: 120px; text-align: center;">Status</th>
                                <th style="width: 105px; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($documents)): ?>
                                <?php $no = 1; foreach ($documents as $doc): ?>
                                    <?php
                                        $nature = $doc['letter_nature'] ?? 'Biasa';
                                        $natureBadge = match ($nature) {
                                            'Rahasia' => 'badge-danger',
                                            'Sangat Segera' => 'badge-danger',
                                            'Penting' => 'badge-warning',
                                            'Segera' => 'badge-primary',
                                            default => 'badge-neutral',
                                        };
                                        $natureIcon = match ($nature) {
                                            'Rahasia' => 'bi-shield-lock-fill',
                                            'Sangat Segera' => 'bi-exclamation-octagon-fill',
                                            'Penting' => 'bi-exclamation-triangle-fill',
                                            'Segera' => 'bi-lightning-charge-fill',
                                            default => 'bi-file-earmark-text',
                                        };
                                        $status = $doc['status'] ?? 'Diarsipkan';
                                        $statusBadge = match ($status) {
                                            'Diarsipkan' => 'badge-success',
                                            'Menunggu Tindak Lanjut' => 'badge-warning',
                                            'Didisposisi' => 'badge-primary',
                                            'Selesai' => 'badge-neutral',
                                            default => 'badge-secondary',
                                        };
                                        $ext = strtoupper(pathinfo($doc['original_name'] ?? '', PATHINFO_EXTENSION));
                                        $hasDuplicate = !empty($doc['backup_stored_name']) || !empty($doc['is_duplicated']);
                                        $shortHash = !empty($doc['file_hash']) ? substr((string)$doc['file_hash'], 0, 10) . '…' : 'SHA-256 OK';
                                    ?>
                                    <tr class="arc-row" 
                                        data-id="<?= (int)$doc['id'] ?>"
                                        data-title="<?= e($doc['title']) ?>"
                                        data-letter-date="<?= e($doc['letter_date'] ?? date('Y-m-d', strtotime($doc['created_at']))) ?>"
                                        data-nature="<?= e($nature) ?>"
                                        data-status="<?= e($status) ?>"
                                        data-description="<?= e($doc['description'] ?? '') ?>"
                                        data-filename="<?= e($doc['original_name']) ?>">
                                        <td style="text-align: center; color: #64748b; font-weight: 700;">
                                            <?= $no++ ?>
                                        </td>
                                        <td>
                                            <div style="font-weight: 800; color: #0f172a; font-size: 0.9rem; margin-bottom: 0.22rem; line-height: 1.35;">
                                                <?= e($doc['title']) ?>
                                            </div>
                                            <?php if (!empty($doc['description'])): ?>
                                                <div style="font-size: 0.77rem; color: #64748b; line-height: 1.45; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= e($doc['description']) ?>">
                                                    <?= e($doc['description']) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($doc['original_name'])): ?>
                                                <div style="margin-top: 0.35rem; font-size: 0.7rem; color: #0284c7; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                    <i class="bi bi-paperclip"></i>
                                                    <span><?= e($doc['original_name']) ?></span>
                                                    <?php if ($ext !== ''): ?>
                                                        <span style="padding: 0.05rem 0.35rem; border-radius: 4px; background: #e0f2fe; color: #0369a1; font-size: 0.63rem; font-weight: 800;"><?= e($ext) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center; white-space: nowrap;">
                                            <div style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.79rem; font-weight: 700; color: #1e293b; background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.25rem 0.6rem; border-radius: 8px;">
                                                <i class="bi bi-calendar3" style="color: #0284c7;"></i>
                                                <span><?= !empty($doc['letter_date']) ? date('d M Y', strtotime($doc['letter_date'])) : date('d M Y', strtotime($doc['created_at'])) ?></span>
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="badge <?= $natureBadge ?>" style="font-size: 0.73rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                <i class="bi <?= $natureIcon ?>"></i>
                                                <span><?= e($nature) ?></span>
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if (!empty($doc['stored_name'])): ?>
                                                <div style="display: flex; flex-direction: column; align-items: center;">
                                                    <div class="ws-doc-pills" style="justify-content: center;">
                                                        <a href="/dokumen/<?= (int)$doc['id'] ?>/unduh" 
                                                           class="ws-doc-pill ws-doc-primary"
                                                           title="Unduh Berkas Asli (Master)">
                                                            <i class="bi bi-download"></i>
                                                            <span>Asli</span>
                                                        </a>
                                                        <?php if ($hasDuplicate): ?>
                                                            <a href="/dokumen/<?= (int)$doc['id'] ?>/unduh?salinan=1" 
                                                               class="ws-doc-pill ws-doc-backup"
                                                               title="Unduh Salinan Duplikasi (Identik 100%)">
                                                                <i class="bi bi-shield-check"></i>
                                                                <span>Duplikasi</span>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="ws-hash-badge" title="Checksum SHA-256: <?= e($doc['file_hash'] ?? 'Terverifikasi Identik') ?>">
                                                        <i class="bi bi-patch-check-fill"></i>
                                                        <span><?= e($shortHash) ?></span>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.3rem;">
                                                    <span class="badge badge-warning" style="font-size: 0.7rem; padding: 0.22rem 0.55rem;">
                                                        <i class="bi bi-file-earmark-excel"></i> Tanpa Lampiran
                                                    </span>
                                                    <?php if ($isSekcab): ?>
                                                        <button type="button" 
                                                                class="ws-icon-btn ws-btn-edit" 
                                                                style="font-size: 0.68rem; padding: 0.2rem 0.5rem; border-style: dashed;" 
                                                                onclick="editArcFromRow(this)"
                                                                title="Unggah berkas untuk surat ini">
                                                            <i class="bi bi-cloud-upload"></i> Unggah
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="badge <?= $statusBadge ?>" style="font-size: 0.73rem; font-weight: 700;">
                                                <?= e($status) ?>
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="ws-action-group">
                                                <?php if ($isSekcab): ?>
                                                    <button type="button" 
                                                            class="ws-icon-btn ws-btn-edit" 
                                                            title="Edit data surat ini"
                                                            onclick="editArcFromRow(this)">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <form method="post" action="/sekcab/arsip/<?= (int)$doc['id'] ?>/delete" data-confirm="Hapus surat '<?= e($doc['title']) ?>' beserta seluruh duplikasi cadangannya?" style="margin: 0; display: inline;">
                                                        <?= csrf_field() ?>
                                                        <button class="ws-icon-btn ws-btn-delete" type="submit" title="Hapus arsip ini">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                <?php elseif (!empty($doc['stored_name'])): ?>
                                                    <a href="/dokumen/<?= (int)$doc['id'] ?>/unduh" class="ws-icon-btn ws-btn-edit" style="text-decoration: none;" title="Unduh Berkas Master">
                                                        <i class="bi bi-cloud-arrow-down-fill"></i>
                                                        <span>Unduh</span>
                                                    </a>
                                                <?php else: ?>
                                                    <span style="font-size: 0.72rem; color: #94a3b8;">—</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noDataArcRow">
                                    <td colspan="7" style="text-align: center; padding: 3.5rem 1.5rem;">
                                        <div style="width: 56px; height: 56px; border-radius: 16px; background: #f0f9ff; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; font-size: 1.65rem; margin-bottom: 0.75rem;">
                                            <i class="bi bi-folder2-open"></i>
                                        </div>
                                        <div style="font-weight: 800; color: #0f172a; font-size: 0.98rem; margin-bottom: 0.25rem;">Belum ada arsip surat yang tersimpan</div>
                                        <div class="text-muted" style="font-size: 0.82rem;">Gunakan formulir registrasi untuk mulai mengarsipkan surat resmi organisasi.</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr id="noMatchArcRow" style="display: none;">
                                <td colspan="7" style="text-align: center; padding: 2.5rem 1.5rem;">
                                    <div style="color: #64748b; font-size: 0.86rem; font-weight: 600;">
                                        <i class="bi bi-search" style="margin-right: 0.35rem;"></i>
                                        Tidak ditemukan arsip persuratan yang sesuai dengan kata kunci atau filter.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($pagination)): ?>
                    <div style="padding: 0.85rem 1.25rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
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
            fileHelp.innerHTML = '<i class="bi bi-file-earmark-check" style="color:#0284c7;"></i> Berkas saat ini: <strong>' + (filename || 'Belum ada lampiran') + '</strong> (Kosongkan jika tidak mengganti berkas. Jika diganti, sistem akan meregenerasi duplikasi otomatis).';
        }

        document.getElementById('btnSubmitArc').innerHTML = '<i class="bi bi-check2-circle"></i> <span>Perbarui Arsip & Duplikasi</span>';
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
            fileHelp.innerHTML = '<i class="bi bi-shield-check" style="color: #059669;"></i> <em>Jika diunggah, berkas otomatis disalin ke brankas cadangan dengan hash SHA-256 identik.</em>';
        }

        document.getElementById('btnSubmitArc').innerHTML = '<i class="bi bi-hdd-stack-fill"></i> <span>Simpan Arsip & Duplikasi</span>';
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
    <div class="grid grid-cols-1 <?= $canManageFinance ? 'lg:grid-cols-12' : '' ?> gap-6 items-start">
        <?php if ($canManageFinance): ?>
            <!-- Form Input / Edit Laporan Keuangan (Bendahara Cabang) -->
            <div class="card sticky-form-card <?= $canManageFinance ? 'lg:col-span-5' : '' ?>" id="formFinanceCard" style="border-top: 3px solid var(--primary);">
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
        <div class="card <?= $canManageFinance ? 'lg:col-span-7' : 'col-span-1' ?>" style="border-top: 3px solid #00a884;">
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
                    <div class="flex items-center gap-2" style="flex-wrap: wrap;">
                        <input type="text" id="finSearchInput" class="form-control" style="flex: 1 1 200px; min-width: 160px;"
                               placeholder="🔍 Cari judul laporan atau catatan..." oninput="filterFinanceTable()">
                        <select id="finPeriodFilter" class="form-control" onchange="filterFinanceTable()" style="flex: 0 1 auto; min-width: 140px;">
                            <option value="">Semua Periode</option>
                            <?php foreach ($uniquePeriods as $up): ?>
                                <option value="<?= e($up) ?>"><?= e($up) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('finSearchInput').value=''; document.getElementById('finPeriodFilter').value=''; filterFinanceTable();" style="white-space: nowrap;">
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Tabel Data Laporan Keuangan -->
                <div class="table-responsive">
                    <table class="data-table" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th class="col-no">#</th>
                                <th style="min-width: 200px;">Laporan &amp; Keterangan</th>
                                <th style="text-align: center; width: 140px; min-width: 120px;">Periode</th>
                                <th style="min-width: 150px;">Berkas Lampiran</th>
                                <th style="text-align: center; width: 140px; min-width: 120px;">Tanggal Terbit</th>
                                <th style="text-align: center; width: 110px; min-width: 100px;">Aksi</th>
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
                                        <td class="col-no" style="color: var(--text-muted); font-weight: 600;">
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
    <?php
    $stats = $stats ?? [
        'total' => count($strategies ?? []),
        'achieved' => 0,
        'not_achieved' => 0,
        'in_progress' => 0,
        'planned' => 0,
    ];
    $availableSekfung = $availableSekfung ?? [];
    $sekfungFilter = $sekfungFilter ?? '';
    $achievedFilter = $achievedFilter ?? '';
    $search = $search ?? '';
    ?>

    <!-- 1. KPI Metric Summary Cards (Proker Sekfung) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="card p-3 sm:p-4 border border-slate-200/80 shadow-xs" style="background:#ffffff;border-radius:12px;">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Proker Terdata</div>
            <div class="text-2xl font-black text-slate-800" style="color:#0f3d64;"><?= (int)$stats['total'] ?></div>
            <div class="text-xs text-slate-500 mt-0.5">Program kerja sekfung BPC</div>
        </div>
        <div class="card p-3 sm:p-4 border border-emerald-200 shadow-xs" style="background:#f0fdf4;border-radius:12px;">
            <div class="text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">Berhasil (Iya)</div>
            <div class="text-2xl font-black text-emerald-800"><?= (int)$stats['achieved'] ?></div>
            <div class="text-xs text-emerald-600 mt-0.5">Terealisasi dengan baik</div>
        </div>
        <div class="card p-3 sm:p-4 border border-rose-200 shadow-xs" style="background:#fff1f2;border-radius:12px;">
            <div class="text-xs font-bold text-rose-700 uppercase tracking-wider mb-1">Belum Berhasil (Tidak)</div>
            <div class="text-2xl font-black text-rose-800"><?= (int)$stats['not_achieved'] ?></div>
            <div class="text-xs text-rose-600 mt-0.5">Evaluasi &amp; kendala</div>
        </div>
        <div class="card p-3 sm:p-4 border border-sky-200 shadow-xs" style="background:#f0f9ff;border-radius:12px;">
            <div class="text-xs font-bold text-sky-700 uppercase tracking-wider mb-1">Proses / Rencana</div>
            <div class="text-2xl font-black text-sky-800"><?= (int)($stats['in_progress'] + $stats['planned']) ?></div>
            <div class="text-xs text-sky-600 mt-0.5">Sedang berjalan / agenda</div>
        </div>
    </div>

    <!-- 2. Action Bar & Filter Bar -->
    <div class="card p-4 border border-slate-200 shadow-xs mb-6" style="background:#ffffff;border-radius:14px;">
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-base sm:text-lg font-black m-0" style="color:#0f3d64;">
                    Daftar &amp; Pemantauan Program Kerja Fungsionaris (Sekfung)
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 m-0 mt-0.5">
                    Ketua Cabang dapat memantau ketercapaian proker, dasar pemikiran, indikator keberhasilan, dan langsung berkoordinasi via WhatsApp.
                </p>
            </div>
            <?php if (can('strategy.manage')): ?>
                <button type="button" class="btn btn-primary btn-sm flex items-center justify-center gap-1.5 font-bold shadow-xs flex-shrink-0" onclick="toggleAddProkerForm()" style="background:#0284c7;border-color:#0369a1;color:#ffffff;padding:0.6rem 1.15rem;border-radius:9px;">
                    <span>+ Tambah Pendataan Proker Baru</span>
                </button>
            <?php endif; ?>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="/ketcab/proker" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-2.5">
            <div>
                <select name="sekfung" class="form-control text-xs" style="height:38px;" onchange="this.form.submit()">
                    <option value="">-- Semua Bidang Sekfung --</option>
                    <?php foreach ($availableSekfung as $sf): ?>
                        <option value="<?= e($sf['jabatan']) ?>" <?= ($sekfungFilter === $sf['jabatan']) ? 'selected' : '' ?>>
                            <?= e($sf['jabatan']) ?> (<?= e($sf['nama']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <select name="achieved" class="form-control text-xs" style="height:38px;" onchange="this.form.submit()">
                    <option value="">-- Status Berhasil (Semua) --</option>
                    <option value="Iya" <?= ($achievedFilter === 'Iya') ? 'selected' : '' ?>>✓ Berhasil (Iya)</option>
                    <option value="Tidak" <?= ($achievedFilter === 'Tidak') ? 'selected' : '' ?>>✗ Belum Berhasil (Tidak)</option>
                    <option value="Proses" <?= ($achievedFilter === 'Proses') ? 'selected' : '' ?>>⏳ Sedang Berjalan</option>
                    <option value="Rencana" <?= ($achievedFilter === 'Rencana') ? 'selected' : '' ?>>📋 Direncanakan</option>
                </select>
            </div>
            <div class="sm:col-span-1 lg:col-span-2 flex gap-2">
                <input type="text" name="q" class="form-control text-xs" placeholder="Cari nama proker, dasar pemikiran, tujuan..." value="<?= e($search) ?>" style="height:38px;">
                <button type="submit" class="btn btn-outline btn-sm font-bold text-xs" style="padding:0 0.85rem;height:38px;color:#0f3d64;">Cari</button>
                <?php if ($sekfungFilter !== '' || $achievedFilter !== '' || $search !== ''): ?>
                    <a href="/ketcab/proker" class="btn btn-outline btn-sm text-xs flex items-center justify-center text-slate-500" style="height:38px;" title="Reset filter">✕</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- 3. Form Input Proker Baru (Collapsible) -->
    <?php if (can('strategy.manage')): ?>
        <section id="formAddProkerContainer" class="card border border-sky-200 shadow-md mb-6" style="display:none;background:#f8fafc;border-radius:14px;overflow:hidden;">
            <div style="padding:1rem 1.25rem;background:linear-gradient(135deg, #0284c7 0%, #0369a1 100%);color:#ffffff;" class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span style="font-size:1.25rem;">📝</span>
                    <h3 class="text-sm sm:text-base font-extrabold text-white m-0">Formulir Pendataan Program Kerja (Proker) Sekfung</h3>
                </div>
                <button type="button" class="text-white hover:text-slate-200 font-bold text-lg" onclick="toggleAddProkerForm()">✕</button>
            </div>
            <div class="p-4 sm:p-5">
                <form method="POST" action="/ketcab/proker" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?= csrf_field() ?>

                    <!-- Pilihan Sekfung Preset -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Pilih Sekfung / Fungsionaris BPC: <span class="text-rose-500">*</span>
                        </label>
                        <select id="selectPresetSekfung" class="form-control text-xs" onchange="applySekfungPreset(this)">
                            <option value="">-- Pilih dari Daftar Sekfung Tersedia --</option>
                            <?php foreach ($availableSekfung as $sf): ?>
                                <option value="<?= e($sf['jabatan']) ?>" data-nama="<?= e($sf['nama']) ?>" data-wa="<?= e($sf['wa']) ?>">
                                    <?= e($sf['jabatan']) ?> - <?= e($sf['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="CUSTOM">-- Masukkan Jabatan Lain Secara Manual --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Jabatan Fungsional (Sekfung): <span class="text-rose-500">*</span>
                        </label>
                        <input id="inputSekfungJabatan" type="text" name="sekfung_jabatan" class="form-control text-xs" placeholder="Contoh: SEKFUNG OR-LITBANG" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Nama Sekfung Penanggung Jawab:
                        </label>
                        <input id="inputSekfungNama" type="text" name="sekfung_name" class="form-control text-xs" placeholder="Nama lengkap sekfung">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Nomor WhatsApp Sekfung (Untuk Chat Langsung): <span class="text-emerald-600 font-semibold">(Aktif WA)</span>
                        </label>
                        <input id="inputSekfungWa" type="text" name="sekfung_wa" class="form-control text-xs" placeholder="Contoh: 081234567890 atau 62812...">
                        <span class="text-slate-400 text-xs">Ketua Cabang dapat langsung klik chat WA sekfung pada tabel.</span>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Nama Program Kerja: <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" class="form-control text-xs" placeholder="Contoh: Masa Perkenalan Calon Anggota (MAPERCA) Semester Genap" maxlength="255" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Jenis Kegiatan:
                        </label>
                        <input type="text" name="activity_type" class="form-control text-xs" list="activityTypeSuggestions" placeholder="Ketik jenis kegiatan (cth: Pelatihan, Diskusi, Aksi Sosial, dll.)">
                        <datalist id="activityTypeSuggestions">
                            <option value="Pelatihan &amp; Kaderisasi">
                            <option value="Diskusi Publik &amp; Seminar">
                            <option value="Ibadah &amp; Kerohanian">
                            <option value="Aksi Sosial &amp; Pengabdian">
                            <option value="Publikasi &amp; Media Komunikasi">
                            <option value="Pengkajian &amp; Litbang">
                            <option value="Advokasi &amp; Kebijakan">
                            <option value="Audiensi &amp; Relasi">
                            <option value="Konsolidasi Organisasi">
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Waktu Pelaksanaan (Keterangan):
                        </label>
                        <select id="selectWaktuPelaksanaan" class="form-control text-xs mb-1" onchange="handleWaktuPreset(this)">
                            <option value="Selama masa bakti">Selama masa bakti (Rutin / Berkala)</option>
                            <option value="1 kali selama masa bakti">1 kali selama masa bakti (Insidental)</option>
                            <option value="CUSTOM">Tulis Keterangan Waktu Khusus...</option>
                        </select>
                        <input id="inputWaktuPelaksanaan" type="text" name="execution_time" class="form-control text-xs" value="Selama masa bakti" placeholder="Keterangan waktu pelaksanaan">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Dasar Pemikiran: <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="dasar_pemikiran" class="form-control text-xs" rows="3" placeholder="Rasionalisasi, latar belakang, dan urgensi diangkatnya program kerja ini..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tujuan Program: <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="objective" class="form-control text-xs" rows="3" placeholder="Tujuan terukur yang hendak dicapai dari program kerja ini..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Indikator Keberhasilan: <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="indicator" class="form-control text-xs" rows="3" placeholder="Tolok ukur kuantitatif / kualitatif keberhasilan pelaksanaan program..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Status Berhasil (Iya / Tidak): <span class="text-rose-500">*</span>
                        </label>
                        <select name="is_achieved" class="form-control text-xs font-bold">
                            <option value="Rencana">📋 Direncanakan (Belum Berjalan)</option>
                            <option value="Proses">⏳ Sedang Berjalan (Proses)</option>
                            <option value="Iya">✅ Berhasil (Iya)</option>
                            <option value="Tidak">❌ Belum Berhasil (Tidak)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Catatan &amp; Evaluasi Ketcab (Opsional):
                        </label>
                        <input type="text" name="notes" class="form-control text-xs" placeholder="Catatan khusus atau arahan Ketua Cabang...">
                    </div>

                    <div class="md:col-span-2 flex items-center justify-end gap-2 pt-2 border-t border-slate-200">
                        <button type="button" class="btn btn-outline btn-sm text-xs" onclick="toggleAddProkerForm()">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm text-xs font-bold" style="background:#0284c7;border-color:#0369a1;color:#ffffff;padding:0.6rem 1.25rem;">
                            💾 Simpan Program Kerja
                        </button>
                    </div>
                </form>
            </div>
        </section>
    <?php endif; ?>

    <!-- 4. Tabel Pemantauan Proker Sekfung -->
    <div class="table-card shadow-sm border border-slate-200/90 mb-6" style="border-radius:14px;overflow:hidden;">
        <div class="table-header flex items-center justify-between" style="background:#f8fafc;padding:0.85rem 1.25rem;">
            <div class="text-xs font-black uppercase tracking-wider" style="color:#0f3d64;">
                Data Program Kerja Fungsional Terdaftar (<?= count($strategies) ?> Ditampilkan)
            </div>
            <div class="text-xs text-slate-500">
                Klik ikon <strong>Chat WA</strong> untuk langsung menghubungi Sekfung bersangkutan.
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table" style="font-size:0.8rem;">
                <thead>
                    <tr style="background:#f1f5f9;color:#334155;">
                        <th style="width:40px;text-align:center;">No</th>
                        <th style="min-width:150px;">Sekfung &amp; No WA</th>
                        <th style="min-width:180px;">Dasar Pemikiran</th>
                        <th style="min-width:170px;">Nama Program</th>
                        <th style="min-width:130px;">Jenis Kegiatan</th>
                        <th style="min-width:170px;">Tujuan</th>
                        <th style="min-width:170px;">Indikator</th>
                        <th style="min-width:130px;">Waktu Pelaksanaan</th>
                        <th style="min-width:120px;text-align:center;">Berhasil (Iya/Tidak)</th>
                        <?php if (can('strategy.manage')): ?>
                            <th style="min-width:85px;text-align:center;">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($strategies)): ?>
                        <?php $no = $pagination['offset'] ?? 0; ?>
                        <?php foreach ($strategies as $proker): ?>
                            <?php 
                            $no++;
                            $sekfungTitle = !empty($proker['sekfung_jabatan']) ? $proker['sekfung_jabatan'] : 'SEKFUNG';
                            $sekfungNama = !empty($proker['sekfung_name']) ? $proker['sekfung_name'] : '-';
                            $rawWa = !empty($proker['sekfung_wa']) ? $proker['sekfung_wa'] : '';
                            $cleanWa = preg_replace('/[^0-9]/', '', $rawWa);
                            if (str_starts_with($cleanWa, '0')) {
                                $cleanWa = '62' . substr($cleanWa, 1);
                            }

                            // Format pesan WhatsApp ramah untuk koordinasi Ketcab -> Sekfung
                            $waMessage = "Halo Rekan {$sekfungNama} ({$sekfungTitle}), saya Ketua Cabang GMKI Padang ingin memantau dan berkoordinasi mengenai Program Kerja: \"{$proker['title']}\". Bagaimana perkembangan pelaksanaannya saat ini? Terima kasih.";
                            $waUrl = !empty($cleanWa) ? "https://api.whatsapp.com/send?phone={$cleanWa}&text=" . urlencode($waMessage) : '#';

                            $achievedStatus = $proker['is_achieved'] ?? 'Rencana';
                            ?>
                            <tr style="vertical-align:top;">
                                <!-- No -->
                                <td style="text-align:center;font-weight:bold;color:#64748b;">
                                    <?= $no ?>
                                </td>

                                <!-- Sekfung & Kontak WA -->
                                <td>
                                    <div class="font-extrabold text-slate-800" style="color:#0f3d64;">
                                        <?= e($sekfungTitle) ?>
                                    </div>
                                    <div class="text-xs text-slate-600 mb-1.5">
                                        <?= e($sekfungNama) ?>
                                    </div>
                                    <?php if (!empty($cleanWa)): ?>
                                        <a href="<?= e($waUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm flex items-center gap-1 font-bold shadow-2xs" style="background:#22c55e;color:#ffffff;border:none;font-size:0.68rem;padding:0.25rem 0.55rem;border-radius:6px;display:inline-flex;" title="Buka WhatsApp untuk berkoordinasi langsung dengan Sekfung mengenai proker ini">
                                            <span>💬 Chat WA Sekfung</span>
                                        </a>
                                        <div class="text-2xs text-slate-400 mt-0.5" style="font-size:0.68rem;">
                                            <?= e($rawWa) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs italic">No WA belum diisi</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Dasar Pemikiran -->
                                <td>
                                    <div class="text-xs text-slate-700 leading-relaxed" style="max-height:140px;overflow-y:auto;">
                                        <?= !empty($proker['dasar_pemikiran']) ? nl2br(e($proker['dasar_pemikiran'])) : '<span class="text-slate-400 italic">Dasar pemikiran belum dirincikan.</span>' ?>
                                    </div>
                                </td>

                                <!-- Nama Program (Kolom Terpisah) -->
                                <td>
                                    <div class="font-black text-slate-800 text-sm leading-snug" style="color:#0f3d64;">
                                        <?= e($proker['title']) ?>
                                    </div>
                                </td>

                                <!-- Jenis Kegiatan (Kolom Terpisah) -->
                                <td>
                                    <?php if (!empty($proker['activity_type'])): ?>
                                        <span class="badge" style="background:rgba(2, 132, 199, 0.1);color:#0284c7;border:1px solid rgba(2, 132, 199, 0.25);font-size:0.72rem;padding:0.25rem 0.5rem;font-weight:700;display:inline-block;">
                                            <?= e($proker['activity_type']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs italic">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Tujuan -->
                                <td>
                                    <div class="text-xs text-slate-700 leading-relaxed" style="max-height:140px;overflow-y:auto;">
                                        <?= !empty($proker['objective']) ? nl2br(e($proker['objective'])) : '<span class="text-slate-400 italic">-</span>' ?>
                                    </div>
                                </td>

                                <!-- Indikator -->
                                <td>
                                    <div class="text-xs text-slate-700 leading-relaxed" style="max-height:140px;overflow-y:auto;">
                                        <?= !empty($proker['indicator']) ? nl2br(e($proker['indicator'])) : '<span class="text-slate-400 italic">-</span>' ?>
                                    </div>
                                </td>

                                <!-- Waktu Pelaksanaan -->
                                <td>
                                    <div class="text-xs font-bold text-slate-800">
                                        <?= !empty($proker['execution_time']) ? e($proker['execution_time']) : 'Selama masa bakti' ?>
                                    </div>
                                    <?php if (!empty($proker['target_date'])): ?>
                                        <div class="text-2xs text-slate-400 mt-1">
                                            Target: <?= format_date($proker['target_date'], 'd M Y') ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <!-- Status Berhasil (Iya / Tidak) -->
                                <td style="text-align:center;">
                                    <?php if ($achievedStatus === 'Iya'): ?>
                                        <span class="badge badge-success" style="font-size:0.75rem;padding:0.35rem 0.65rem;border-radius:999px;">
                                            ✓ Berhasil (Iya)
                                        </span>
                                    <?php elseif ($achievedStatus === 'Tidak'): ?>
                                        <span class="badge badge-danger" style="font-size:0.75rem;padding:0.35rem 0.65rem;border-radius:999px;">
                                            ✗ Belum Berhasil (Tidak)
                                        </span>
                                    <?php elseif ($achievedStatus === 'Proses'): ?>
                                        <span class="badge badge-warning" style="font-size:0.75rem;padding:0.35rem 0.65rem;border-radius:999px;">
                                            ⏳ Sedang Berjalan
                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;font-size:0.75rem;padding:0.35rem 0.65rem;border-radius:999px;">
                                            📋 Rencana
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($proker['notes'])): ?>
                                        <div class="text-2xs text-slate-500 mt-1 italic" title="<?= e($proker['notes']) ?>">
                                            "<?= e(mb_strimwidth($proker['notes'], 0, 30, '...')) ?>"
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <!-- Aksi Edit / Hapus -->
                                <?php if (can('strategy.manage')): ?>
                                    <td style="text-align:center;">
                                        <div class="flex items-center justify-center gap-1">
                                            <button type="button" class="btn btn-outline btn-sm" style="padding:0.25rem 0.45rem;font-size:0.75rem;" title="Edit data program kerja" onclick='openEditProkerModal(<?= json_encode($proker, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                                ✏️
                                            </button>
                                            <form method="POST" action="/ketcab/proker/<?= (int)$proker['id'] ?>/delete" data-confirm="Hapus pendataan program kerja ini?" style="display:inline;margin:0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-danger btn-sm" style="padding:0.25rem 0.45rem;font-size:0.75rem;" title="Hapus proker">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= can('strategy.manage') ? 10 : 9 ?>" class="text-center text-muted" style="padding:3rem 1.5rem;">
                                <div style="font-size:2rem;margin-bottom:0.5rem;">📋</div>
                                <div class="font-bold text-slate-700 text-sm">Belum Ada Program Kerja Sekfung yang Terdata</div>
                                <div class="text-xs text-slate-400 mt-1 max-w-md mx-auto">
                                    Silakan klik tombol <strong>"+ Tambah Pendataan Proker Baru"</strong> di atas untuk memasukkan program kerja para Sekretaris Fungsional BPC.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?= partial('partials.pagination', $pagination) ?>

    <!-- 5. Modal Edit Proker Sekfung -->
    <div id="modalEditProker" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15, 23, 42, 0.65);z-index:9999;backdrop-filter:blur(3px);padding:1.5rem;overflow-y:auto;">
        <div style="max-width:700px;margin:2rem auto;background:#ffffff;border-radius:16px;box-shadow:0 20px 25px -5px rgba(0,0,0,0.3);overflow:hidden;">
            <div style="padding:1.25rem 1.5rem;background:#0f3d64;color:#ffffff;" class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span style="font-size:1.25rem;">✏️</span>
                    <h3 class="text-base font-extrabold text-white m-0">Edit Pendataan Program Kerja Sekfung</h3>
                </div>
                <button type="button" class="text-white hover:text-slate-300 font-bold text-xl" onclick="closeEditProkerModal()">✕</button>
            </div>
            <form method="POST" action="/ketcab/proker" style="padding:1.5rem;">
                <?= csrf_field() ?>
                <input type="hidden" id="editProkerId" name="id" value="0">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan Sekfung: <span class="text-rose-500">*</span></label>
                        <input type="text" id="editSekfungJabatan" name="sekfung_jabatan" class="form-control text-xs" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Sekfung:</label>
                        <input type="text" id="editSekfungName" name="sekfung_name" class="form-control text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp Sekfung:</label>
                        <input type="text" id="editSekfungWa" name="sekfung_wa" class="form-control text-xs" placeholder="0812...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Kegiatan:</label>
                        <input type="text" id="editActivityType" name="activity_type" class="form-control text-xs" list="activityTypeSuggestions" placeholder="Ketik jenis kegiatan...">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Program Kerja: <span class="text-rose-500">*</span></label>
                        <input type="text" id="editTitle" name="title" class="form-control text-xs" maxlength="255" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Dasar Pemikiran: <span class="text-rose-500">*</span></label>
                        <textarea id="editDasarPemikiran" name="dasar_pemikiran" class="form-control text-xs" rows="3" required></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tujuan: <span class="text-rose-500">*</span></label>
                        <textarea id="editObjective" name="objective" class="form-control text-xs" rows="3" required></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Indikator Keberhasilan: <span class="text-rose-500">*</span></label>
                        <textarea id="editIndicator" name="indicator" class="form-control text-xs" rows="3" required></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Pelaksanaan (Keterangan):</label>
                        <input type="text" id="editExecutionTime" name="execution_time" class="form-control text-xs" placeholder="Contoh: Selama masa bakti / 1 kali selama masa bakti">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Berhasil (Iya / Tidak): <span class="text-rose-500">*</span></label>
                        <select id="editIsAchieved" name="is_achieved" class="form-control text-xs font-bold">
                            <option value="Iya">✅ Berhasil (Iya)</option>
                            <option value="Tidak">❌ Belum Berhasil (Tidak)</option>
                            <option value="Proses">⏳ Sedang Berjalan (Proses)</option>
                            <option value="Rencana">📋 Direncanakan (Rencana)</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan &amp; Evaluasi Ketcab:</label>
                        <input type="text" id="editNotes" name="notes" class="form-control text-xs">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 mt-5 pt-3 border-t border-slate-200">
                    <button type="button" class="btn btn-outline btn-sm text-xs" onclick="closeEditProkerModal()">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm text-xs font-bold" style="background:#0f3d64;color:#ffffff;padding:0.6rem 1.25rem;">
                        Simpan Perubahan Proker
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Logika UI Proker Sekfung -->
    <script>
    function toggleAddProkerForm() {
        const c = document.getElementById('formAddProkerContainer');
        if (!c) return;
        if (c.style.display === 'none') {
            c.style.display = 'block';
            c.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            c.style.display = 'none';
        }
    }

    function applySekfungPreset(sel) {
        const opt = sel.options[sel.selectedIndex];
        if (!opt || !opt.value) return;
        const jbtInput = document.getElementById('inputSekfungJabatan');
        const namaInput = document.getElementById('inputSekfungNama');
        const waInput = document.getElementById('inputSekfungWa');

        if (opt.value === 'CUSTOM') {
            jbtInput.value = '';
            namaInput.value = '';
            waInput.value = '';
            jbtInput.focus();
            return;
        }

        jbtInput.value = opt.value;
        namaInput.value = opt.getAttribute('data-nama') || '';
        waInput.value = opt.getAttribute('data-wa') || '';
    }

    function handleWaktuPreset(sel) {
        const input = document.getElementById('inputWaktuPelaksanaan');
        if (sel.value === 'CUSTOM') {
            input.value = '';
            input.focus();
        } else {
            input.value = sel.value;
        }
    }

    function openEditProkerModal(data) {
        document.getElementById('editProkerId').value = data.id || 0;
        document.getElementById('editTitle').value = data.title || '';
        document.getElementById('editSekfungJabatan').value = data.sekfung_jabatan || '';
        document.getElementById('editSekfungName').value = data.sekfung_name || '';
        document.getElementById('editSekfungWa').value = data.sekfung_wa || '';
        document.getElementById('editActivityType').value = data.activity_type || '';
        document.getElementById('editDasarPemikiran').value = data.dasar_pemikiran || '';
        document.getElementById('editObjective').value = data.objective || '';
        document.getElementById('editIndicator').value = data.indicator || '';
        document.getElementById('editExecutionTime').value = data.execution_time || '';
        document.getElementById('editIsAchieved').value = data.is_achieved || 'Rencana';
        document.getElementById('editNotes').value = data.notes || '';

        document.getElementById('modalEditProker').style.display = 'block';
    }

    function closeEditProkerModal() {
        document.getElementById('modalEditProker').style.display = 'none';
    }
    </script>

<?php endif; ?>
