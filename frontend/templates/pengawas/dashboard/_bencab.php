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
                    <a href="/pengawas/pemantauan/backup" class="btn btn-outline btn-sm inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl font-bold shadow-sm hover:shadow transition-all">
                        <i class="bi bi-database-check"></i>
                        <span>Status Cadangan Basis Data</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiga Pilar Akuntabilitas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6" style="margin-bottom: 1.5rem;">
        <div class="card rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition-all">
            <div class="card-body text-center p-5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-3.5 border border-emerald-100">
                    <i class="bi bi-database-fill-gear"></i>
                </div>
                <h4 style="margin: 0 0 0.25rem 0; font-size: 1rem; font-weight: 700;">Rotasi Cadangan</h4>
                <p style="font-size: 0.82rem; color: #64748b; line-height: 1.5; margin: 0 0 1rem 0;">
                    Sistem otomatis merotasi dan menyimpan 3 arsip berkas <code>.sql.gz</code> terbaru untuk memitigasi kehilangan data.
                </p>
                <span class="badge badge-success inline-flex items-center gap-1">
                    <i class="bi bi-check2-circle"></i> 3 Arsip Tersedia
                </span>
            </div>
        </div>

        <div class="card rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition-all">
            <div class="card-body text-center p-5">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-2xl mx-auto mb-3.5 border border-amber-100">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h4 style="margin: 0 0 0.25rem 0; font-size: 1rem; font-weight: 700;">Manifest SHA-256</h4>
                <p style="font-size: 0.82rem; color: #64748b; line-height: 1.5; margin: 0 0 1rem 0;">
                    Setiap berkas unggahan publik &amp; privat dicatat ke <code>manifest.csv</code> dengan hash kriptografis untuk mendeteksi manipulasi.
                </p>
                <span class="badge badge-warning inline-flex items-center gap-1">
                    <i class="bi bi-fingerprint"></i> Tervalidasi Hash
                </span>
            </div>
        </div>

        <div class="card rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition-all">
            <div class="card-body text-center p-5">
                <div class="w-12 h-12 rounded-2xl bg-sky-50 text-[#0f3d64] flex items-center justify-center text-2xl mx-auto mb-3.5 border border-sky-100">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h4 style="margin: 0 0 0.25rem 0; font-size: 1rem; font-weight: 700;">Audit Transparansi</h4>
                <p style="font-size: 0.82rem; color: #64748b; line-height: 1.5; margin: 0 0 1rem 0;">
                    Semua pembuatan, pembaruan, dan penghapusan data tercatat secara permanen dengan IP Address dan cap waktu.
                </p>
                <a href="/pengawas/pemantauan/audit-log" class="btn btn-outline btn-sm inline-flex items-center gap-1 font-bold rounded-xl px-3 py-1.5">
                    <span>Buka Audit Log</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
