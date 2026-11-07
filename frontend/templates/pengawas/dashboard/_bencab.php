<?php
/**
 * =====================================================================
 * PANEL EKSEKUTIF BENDAHARA CABANG (BENCAB)
 * Fokus: Akuntabilitas Kas, Isolasi Dokumen Keuangan, Manifest SHA-256 & Antrean Ekspor
 * =====================================================================
 */
$totalFinanceDocs = (int) ($counts['finance'] ?? 0);
?>

<div class="exec-panel executive-tab-content">

    <!-- 1. Banner Akuntabilitas Keuangan & Integritas Aset Bencab -->
    <div class="exec-role-banner banner-bencab">
        <div class="exec-banner-inner">
            <div>
                <div class="exec-banner-eyebrow">
                    <i class="bi bi-wallet2" style="color: #6ee7b7;"></i>
                    <span>AKUNTABILITAS KEUANGAN &amp; INTEGRITAS ASET BENCAB</span>
                </div>
                <h2 class="exec-banner-title">Tata Kelola Kas, Isolasi Dokumen &amp; Audit Kriptografis</h2>
                <p class="exec-banner-desc">
                    Menjamin transparansi pembukuan kas cabang, laporan pertanggungjawaban (LPJ) kegiatan, keaslian dokumen digital berbasis ringkasan hash <strong>SHA-256</strong>, serta sinkronisasi pengawasan langsung bersama Ketua Cabang.
                </p>
                <div class="exec-banner-pills">
                    <span class="exec-banner-pill">
                        <i class="bi bi-file-earmark-Lock2-fill" style="color: #6ee7b7;"></i>
                        <span>Proteksi: <code>Write-Isolation Bencab</code></span>
                    </span>
                    <span class="exec-banner-pill">
                        <i class="bi bi-fingerprint" style="color: #fcd34d;"></i>
                        <span>Integritas Berkas: <code>Manifest SHA-256</code></span>
                    </span>
                    <span class="exec-banner-pill">
                        <i class="bi bi-file-earmark-spreadsheet-fill" style="color: #7dd3fc;"></i>
                        <span>Dokumen Terbit: <code><?= number_format($totalFinanceDocs) ?> Berkas</code></span>
                    </span>
                </div>
            </div>

            <div class="exec-banner-actions">
                <a href="/bencab/laporan" class="exec-btn exec-btn-emerald">
                    <i class="bi bi-cash-stack"></i>
                    <span>Laporan Keuangan</span>
                </a>
                <a href="/bencab/berkas" class="exec-btn exec-btn-glass">
                    <i class="bi bi-folder2-open"></i>
                    <span>Direktori Berkas Kas</span>
                </a>
                <a href="/ruang-kerja/antrian" class="exec-btn exec-btn-glass">
                    <i class="bi bi-hourglass-split"></i>
                    <span>Antrean Ekspor CSV</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Tiga Pilar Akuntabilitas & Keamanan Aset Digital (3 Feature Cards) -->
    <div class="exec-grid-3">
        <!-- Pilar 1: Isolasi & Transparansi Laporan Kas -->
        <div class="exec-feature-card accent-emerald">
            <div>
                <div class="exec-feature-top">
                    <div class="exec-feature-icon" style="background: #d1fae5; color: #047857;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <span class="exec-feature-tag" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                        <i class="bi bi-lock-fill"></i> Write-Isolation
                    </span>
                </div>
                <h3 class="exec-feature-title">Isolasi &amp; Transparansi Kas</h3>
                <p class="exec-feature-desc">
                    Hanya Bendahara Cabang yang memiliki otoritas penuh untuk menerbitkan, memperbarui, atau menghapus laporan keuangan, sementara Ketua Cabang dapat memantau secara transparan.
                </p>
            </div>
            <div class="exec-feature-footer">
                <span style="color: #047857;"><i class="bi bi-check2-circle"></i> Otoritas Tunggal Bencab</span>
                <a href="/bencab/laporan" style="color: #0f3d64; text-decoration: none;">Kelola LPJ →</a>
            </div>
        </div>

        <!-- Pilar 2: Manifest SHA-256 & Rotasi Cadangan -->
        <div class="exec-feature-card accent-gold">
            <div>
                <div class="exec-feature-top">
                    <div class="exec-feature-icon" style="background: #fef3c7; color: #b45309;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <span class="exec-feature-tag" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a;">
                        <i class="bi bi-fingerprint"></i> Hash Tervalidasi
                    </span>
                </div>
                <h3 class="exec-feature-title">Manifest Kriptografis SHA-256</h3>
                <p class="exec-feature-desc">
                    Setiap berkas pembukuan (PDF, Excel, Word, &amp; bukti nota) diverifikasi keasliannya menggunakan sidik jari digital SHA-256 dan disimpan bersama salinan cadangan redundan.
                </p>
            </div>
            <div class="exec-feature-footer">
                <span style="color: #b45309;"><i class="bi bi-shield-fill-check"></i> Anti-Manipulasi Berkas</span>
                <a href="/bencab/berkas" style="color: #0f3d64; text-decoration: none;">Cek Berkas →</a>
            </div>
        </div>

        <!-- Pilar 3: Antrean Ekspor & Audit Jejak Transaksi -->
        <div class="exec-feature-card accent-navy">
            <div>
                <div class="exec-feature-top">
                    <div class="exec-feature-icon" style="background: #e0f2fe; color: #0369a1;">
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <span class="exec-feature-tag" style="background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd;">
                        <i class="bi bi-clock-history"></i> Jejak Permanen
                    </span>
                </div>
                <h3 class="exec-feature-title">Rekapitulasi &amp; Audit Transparan</h3>
                <p class="exec-feature-desc">
                    Pembuatan rekapitulasi laporan keuangan CSV diproses melalui sistem antrean latar belakang, dilengkapi pencatatan alamat IP dan cap waktu permanen di sistem audit.
                </p>
            </div>
            <div class="exec-feature-footer">
                <span style="color: #0369a1;"><i class="bi bi-activity"></i> Sinkron Audit Cabang</span>
                <a href="/ruang-kerja/antrian" style="color: #0f3d64; text-decoration: none;">Antrean CSV →</a>
            </div>
        </div>
    </div>

    <!-- 3. Alur Standar Operasional Pembukuan & Pelaporan Bencab -->
    <div class="exec-section-card">
        <div class="exec-section-header">
            <div class="exec-section-title-group">
                <div class="exec-section-icon" style="background: #d1fae5; color: #047857;">
                    <i class="bi bi-diagram-2-fill"></i>
                </div>
                <div>
                    <h3 class="exec-section-title">Alur Tata Kelola &amp; Standar Dokumen Keuangan BPC</h3>
                    <p class="exec-section-subtitle">Tahapan baku pengarsipan laporan kas dan pertanggungjawaban keuangan GMKI Cabang Padang</p>
                </div>
            </div>
            <span class="badge badge-success">Standar Akuntabilitas</span>
        </div>
        <div class="exec-section-body">
            <div class="exec-grid-4">
                <div class="exec-stat-box" style="border-left: 4px solid #10b981; background: #f8fafc;">
                    <div class="exec-stat-top">
                        <span class="exec-stat-label" style="color: #047857;">Tahap 01</span>
                        <i class="bi bi-cloud-arrow-up-fill" style="color: #10b981; font-size: 1.1rem;"></i>
                    </div>
                    <div style="font-size: 0.92rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">Unggah Laporan &amp; Nota</div>
                    <div style="font-size: 0.74rem; color: #64748b; line-height: 1.45;">
                        Format resmi yang didukung: <code>PDF</code>, <code>XLS/XLSX</code>, <code>DOC/DOCX</code>, dan <code>JPG/PNG</code>.
                    </div>
                </div>

                <div class="exec-stat-box" style="border-left: 4px solid #0284c7; background: #f8fafc;">
                    <div class="exec-stat-top">
                        <span class="exec-stat-label" style="color: #0369a1;">Tahap 02</span>
                        <i class="bi bi-shield-lock-fill" style="color: #0284c7; font-size: 1.1rem;"></i>
                    </div>
                    <div style="font-size: 0.92rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">Enkapsulasi &amp; SHA-256</div>
                    <div style="font-size: 0.74rem; color: #64748b; line-height: 1.45;">
                        Berkas disimpan di direktori terisolasi dengan pencatatan sidik jari kriptografis otomatis.
                    </div>
                </div>

                <div class="exec-stat-box" style="border-left: 4px solid #f59e0b; background: #f8fafc;">
                    <div class="exec-stat-top">
                        <span class="exec-stat-label" style="color: #b45309;">Tahap 03</span>
                        <i class="bi bi-eye-fill" style="color: #f59e0b; font-size: 1.1rem;"></i>
                    </div>
                    <div style="font-size: 0.92rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">Tinjauan Ketua Cabang</div>
                    <div style="font-size: 0.74rem; color: #64748b; line-height: 1.45;">
                        Laporan langsung tersedia untuk ditinjau dan diunduh oleh Ketua Cabang secara <em>real-time</em>.
                    </div>
                </div>

                <div class="exec-stat-box" style="border-left: 4px solid #4f46e5; background: #f8fafc;">
                    <div class="exec-stat-top">
                        <span class="exec-stat-label" style="color: #4338ca;">Tahap 04</span>
                        <i class="bi bi-file-earmark-Richtext-fill" style="color: #4f46e5; font-size: 1.1rem;"></i>
                    </div>
                    <div style="font-size: 0.92rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">Ekspor Rekapitulasi</div>
                    <div style="font-size: 0.74rem; color: #64748b; line-height: 1.45;">
                        Cetak rekapitulasi berkas keuangan ke format CSV kapan saja melalui modul antrean kerja.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
