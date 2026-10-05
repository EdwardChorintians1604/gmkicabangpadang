<?php
/**
 * Tab Pemantauan: BENDAHARA CABANG (BENCAB)
 * Fokus: Akuntabilitas Logistik, Integritas Berkas, Pengawasan Cadangan Data & Audit Keamanan
 */
?>
<div class="executive-tab-content">
    <div class="card" style="margin-bottom: 1.5rem; background: #ffffff; border-left: 4px solid #10b981;">
        <div class="card-body" style="padding: 1.5rem 1.75rem;">
            <div class="flex items-center justify-between" style="flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="margin: 0 0 0.35rem 0; font-size: 1.25rem; font-weight: 800; color: var(--primary);">Pantauan Integritas Aset Digital & Audit</h3>
                    <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
                        Pengawasan berkala terhadap pencadangan data cabang, keaslian dokumen digital dengan ringkasan hash SHA-256, dan jejak aktivitas pengurus.
                    </p>
                </div>
                <div>
                    <a href="/pengawas/pemantauan/backup" class="btn btn-outline btn-sm flex items-center gap-1">
                        <?= svg_icon('backup', 15) ?>
                        <span>Status Cadangan Basis Data</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiga Pilar Akuntabilitas -->
    <div class="grid grid-cols-3 gap-6" style="margin-bottom: 1.5rem;">
        <div class="card">
            <div class="card-body text-center" style="padding: 1.5rem;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <?= svg_icon('database', 24) ?>
                </div>
                <h4 style="margin: 0 0 0.25rem 0; font-size: 1rem; font-weight: 700;">Rotasi Cadangan</h4>
                <p style="font-size: 0.82rem; color: #64748b; line-height: 1.5; margin: 0 0 1rem 0;">
                    Sistem otomatis merotasi dan menyimpan 3 arsip berkas <code>.sql.gz</code> terbaru untuk memitigasi kehilangan data.
                </p>
                <span class="badge badge-success">3 Arsip Tersedia</span>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center" style="padding: 1.5rem;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(217, 119, 6, 0.1); color: var(--secondary); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <?= svg_icon('shield', 24) ?>
                </div>
                <h4 style="margin: 0 0 0.25rem 0; font-size: 1rem; font-weight: 700;">Manifest SHA-256</h4>
                <p style="font-size: 0.82rem; color: #64748b; line-height: 1.5; margin: 0 0 1rem 0;">
                    Setiap berkas unggahan publik & privat dicatat ke <code>manifest.csv</code> dengan hash kriptografis untuk mendeteksi manipulasi.
                </p>
                <span class="badge badge-warning">Tervalidasi Hash</span>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center" style="padding: 1.5rem;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(15, 61, 100, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <?= svg_icon('lock', 24) ?>
                </div>
                <h4 style="margin: 0 0 0.25rem 0; font-size: 1rem; font-weight: 700;">Audit Transparansi</h4>
                <p style="font-size: 0.82rem; color: #64748b; line-height: 1.5; margin: 0 0 1rem 0;">
                    Semua pembuatan, pembaruan, dan penghapusan data tercatat secara permanen dengan IP Address dan cap waktu.
                </p>
                <a href="/pengawas/pemantauan/audit-log" class="btn btn-outline btn-sm">Buka Audit Log &rarr;</a>
            </div>
        </div>
    </div>
</div>
