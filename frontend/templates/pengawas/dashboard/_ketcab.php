<?php
/**
 * Tab Pemantauan: KETUA CABANG (KETCAB)
 * Fokus: Kebijakan Strategis, Visi Organisasi, Tri Panji GMKI, dan Soliditas Kepengurusan BPC
 */
?>
<div class="executive-tab-content">
    <div class="card" style="margin-bottom: 1.5rem; background: linear-gradient(135deg, #092c4a 0%, #0f3d64 100%); color: #ffffff; border: none;">
        <div class="card-body" style="padding: 1.75rem 2rem;">
            <div class="flex items-center justify-between" style="flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="badge" style="background: rgba(245, 158, 11, 0.25); color: #fde68a; border: 1px solid rgba(245, 158, 11, 0.4); margin-bottom: 0.5rem;">AMANAT KETUA CABANG</span>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #ffffff; margin: 0 0 0.5rem 0;">Arah Kebijakan & Tri Panji GMKI Padang</h2>
                    <p style="color: #cbd5e1; margin: 0; max-width: 750px; font-size: 0.92rem; line-height: 1.6;">
                        Memastikan kesinambungan perarakan di tiga medan layan: <strong>Gereja, Perguruan Tinggi, dan Masyarakat</strong> melalui kaderisasi yang berakar pada iman Kristiani dan berwawasan kebangsaan.
                    </p>
                </div>
                <div class="text-left sm:text-right mt-2 sm:mt-0">
                    <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Status Kepengurusan BPC</div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: #38bdf8;">Periode <?= e($profile['tema_periode'] ?? '2024-2026') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6" style="margin-bottom: 1.5rem;">
        <div class="card" style="border-top: 4px solid var(--primary);">
            <div class="card-body">
                <div class="flex items-center gap-2" style="margin-bottom: 0.75rem;">
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: rgba(15, 61, 100, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800;">1</div>
                    <h4 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--primary);">Tinggi Iman</h4>
                </div>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0;">
                    Pembinaan spiritualitas melalui ibadah persekutuan rutin, pemahaman firman yang kontekstual, serta keteladanan moral Kristiani di kampus dan masyarakat.
                </p>
            </div>
        </div>

        <div class="card" style="border-top: 4px solid var(--secondary);">
            <div class="card-body">
                <div class="flex items-center gap-2" style="margin-bottom: 0.75rem;">
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: rgba(217, 119, 6, 0.1); color: var(--secondary); display: flex; align-items: center; justify-content: center; font-weight: 800;">2</div>
                    <h4 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--secondary);">Tinggi Ilmu</h4>
                </div>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0;">
                    Pengembangan daya nalar kritis, riset akademis, diskusi isu kebangsaan dan kedaerahan, serta kesiapan menyongsong kepemimpinan masa depan.
                </p>
            </div>
        </div>

        <div class="card" style="border-top: 4px solid #10b981;">
            <div class="card-body">
                <div class="flex items-center gap-2" style="margin-bottom: 0.75rem;">
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-weight: 800;">3</div>
                    <h4 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #10b981);">Tinggi Pengabdian</h4>
                </div>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0;">
                    Aksi nyata advokasi sosial, pembelaan kaum tertindas, bakti sosial masyarakat di Padang, dan kepekaan terhadap lingkungan hidup.
                </p>
            </div>
        </div>
    </div>

    <!-- Struktur Pimpinan Harian BPC -->
    <div class="card">
        <div class="card-header flex items-center justify-between" style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border);">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--primary);">Badan Pengurus Cabang (BPC) Aktif</h3>
                <span class="text-muted" style="font-size: 0.8rem;">Struktur pimpinan operasional dan fungsionaris cabang</span>
            </div>
            <a href="/pengawas/organisasi/struktur" class="btn btn-outline btn-sm">Lihat Seluruh Struktur &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Nama Pengurus</th>
                        <th>Jabatan</th>
                        <th>Periode</th>
                        <th>Kontak</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($structure)): ?>
                        <?php foreach (array_slice($structure, 0, 6) as $p): ?>
                            <tr>
                                <td><strong>#<?= (int)$p['urutan'] ?></strong></td>
                                <td><strong><?= e($p['nama']) ?></strong></td>
                                <td><span class="badge badge-primary"><?= e($p['jabatan']) ?></span></td>
                                <td class="text-muted"><?= e($p['periode'] ?? '-') ?></td>
                                <td><code><?= e($p['telepon'] ?? '-') ?></code></td>
                                <td><span class="badge badge-success">Aktif</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted" style="padding: 2rem;">Belum ada data pengurus BPC.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
