<?php
/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: frontend/templates/public/ad-art.php
 * Deskripsi: Halaman Konstitusi Resmi (Anggaran Dasar, Anggaran Rumah Tangga,
 *            dan Peraturan Organisasi beserta Penjelasannya).
 * =====================================================================
 */

declare(strict_types=1);

if (!defined('GMKI_SECURE_ACCESS')) {
    http_response_code(403);
    exit('Akses langsung ditolak.');
}
?>

<style>
    /* =====================================================================
     * GMKI CABANG PADANG - AD/ART DESIGN SYSTEM (CLASSIC CHRISTIAN HERITAGE)
     * ===================================================================== */
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800;900&family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap');

    :root {
        --reading-font-size: 1.15rem;
        --ad-navy: #071a2e;
        --ad-blue: #0f3d64;
        --ad-gold: #c59b27;
        --ad-gold-dark: #854d0e;
        --ad-crimson: #7f1d1d;
        --ad-parchment: #fdfbf7;
        --ad-parchment-border: #e2d7c3;
    }

    .font-classical {
        font-family: 'Cinzel', Georgia, 'Times New Roman', serif;
        letter-spacing: 0.04em;
    }

    .font-sacred {
        font-family: 'EB Garamond', Garamond, 'Times New Roman', Georgia, serif;
    }

    html {
        scroll-behavior: smooth;
    }

    /* Reading Progress Bar (Liturgical Gold & Navy) */
    #readingProgressBar {
        position: fixed;
        top: 0;
        left: 0;
        height: 3.5px;
        width: 0%;
        background: linear-gradient(90deg, #c59b27 0%, #0f3d64 45%, #d97706 80%, #c59b27 100%);
        z-index: 9999;
        transition: width 0.1s ease;
        box-shadow: 0 1px 4px rgba(197, 155, 39, 0.4);
    }

    /* Reading Container with Timeless Ecclesiastical Typography & Deep Scalability */
    .ad-reading-container {
        font-family: 'EB Garamond', Garamond, 'Times New Roman', Georgia, serif;
        font-size: var(--reading-font-size);
        line-height: 1.95;
        color: #26221c;
        letter-spacing: 0.01em;
    }

    .ad-reading-container p,
    .ad-reading-container li,
    .ad-reading-container ol,
    .ad-reading-container ul,
    .ad-reading-container td,
    .ad-reading-container th,
    .ad-reading-container blockquote {
        font-size: var(--reading-font-size) !important;
        line-height: 1.85 !important;
    }

    /* Card Styling (Historic Parchment Codex Look) */
    .ad-card {
        position: relative;
        background: #ffffff;
        border: 1.5px solid #e4dac7;
        border-radius: 1.25rem;
        box-shadow: 0 4px 20px -2px rgba(44, 28, 12, 0.05), inset 0 0 0 1px rgba(255, 255, 255, 0.9);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
        scroll-margin-top: 110px;
        overflow: hidden;
    }

    .ad-card:hover {
        transform: translateY(-3px);
        border-color: #c59b27;
        box-shadow: 0 16px 36px -4px rgba(44, 28, 12, 0.1), inset 0 0 0 1px rgba(197, 155, 39, 0.25);
    }

    /* Top Accent Rule per Section (Liturgical Antique Borders) */
    #section-ad .ad-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #071a2e 0%, #0f3d64 35%, #c59b27 70%, #071a2e 100%);
    }

    #section-art .ad-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #78350f 0%, #b45309 35%, #f59e0b 70%, #78350f 100%);
    }

    #section-po .ad-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #312e81 0%, #4338ca 35%, #c59b27 70%, #312e81 100%);
    }

    #section-penjelasan .ad-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #064e3b 0%, #0d9488 35%, #c59b27 70%, #064e3b 100%);
    }

    /* Classical Inscriptional Article Badges */
    #section-ad .ad-card span[class*="bg-[#0f3d64]"] {
        background: linear-gradient(135deg, #071a2e 0%, #0f3d64 100%) !important;
        border: 1px solid rgba(197, 155, 39, 0.6) !important;
        color: #fef3c7 !important;
        box-shadow: 0 2px 6px rgba(10, 38, 63, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2) !important;
        border-radius: 0.5rem;
        font-family: 'Cinzel', Georgia, serif !important;
        letter-spacing: 0.08em;
        font-weight: 700;
    }

    #section-art .ad-card span[class*="bg-amber-600"] {
        background: linear-gradient(135deg, #78350f 0%, #b45309 100%) !important;
        border: 1px solid rgba(251, 191, 36, 0.6) !important;
        color: #fffbeb !important;
        box-shadow: 0 2px 6px rgba(180, 83, 9, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2) !important;
        border-radius: 0.5rem;
        font-family: 'Cinzel', Georgia, serif !important;
        letter-spacing: 0.08em;
        font-weight: 700;
    }

    #section-po .ad-card span[class*="bg-blue-800"] {
        background: linear-gradient(135deg, #312e81 0%, #4338ca 100%) !important;
        border: 1px solid rgba(199, 210, 254, 0.5) !important;
        color: #e0e7ff !important;
        box-shadow: 0 2px 6px rgba(67, 56, 202, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2) !important;
        border-radius: 0.5rem;
        font-family: 'Cinzel', Georgia, serif !important;
        letter-spacing: 0.08em;
        font-weight: 700;
    }

    /* Card Target Highlight */
    .ad-card.highlight-target {
        border-color: #c59b27 !important;
        box-shadow: 0 0 0 4px rgba(197, 155, 39, 0.25), 0 16px 36px -6px rgba(44, 28, 12, 0.16) !important;
        animation: pulseCardTarget 1.5s ease-in-out infinite;
    }

    @keyframes pulseCardTarget {
        0%, 100% {
            transform: translateY(-3px) scale(1);
        }
        50% {
            transform: translateY(-3px) scale(1.006);
        }
    }

    /* Classical Christian Ecclesiastical Charter Styling */
    .parchment-charter {
        background: #fdfbf7 linear-gradient(135deg, #fdf8ee 0%, #ffffff 50%, #faf3e1 100%) !important;
        border: 2px solid #c9a96e !important;
        box-shadow: inset 0 0 0 4px #faf5ea, inset 0 0 0 6px rgba(201, 169, 110, 0.5), 0 10px 30px -4px rgba(44, 24, 8, 0.1) !important;
        position: relative;
    }

    .parchment-charter::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #854d0e 0%, #d97706 25%, #fbbf24 50%, #d97706 75%, #854d0e 100%) !important;
    }

    /* Ecclesiastical Illuminated Initial Drop Cap */
    .dropcap-ecclesiastical::first-letter {
        float: left;
        font-family: 'Cinzel', serif;
        font-size: 3.6rem;
        line-height: 0.85;
        font-weight: 900;
        color: #0f3d64;
        padding: 0.45rem 0.75rem 0.35rem 0.55rem;
        margin-right: 0.85rem;
        margin-top: 0.25rem;
        background: linear-gradient(145deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #c99324;
        border-radius: 0.55rem;
        box-shadow: 0 4px 12px rgba(15, 61, 100, 0.16), inset 0 0 0 2px rgba(255, 255, 255, 0.75);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 640px) {
        .dropcap-ecclesiastical::first-letter {
            font-size: 2.5rem;
            padding: 0.3rem 0.5rem;
            margin-right: 0.6rem;
        }
    }

    /* Classic Christian Cross Divider */
    .classic-cross-divider {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.25rem 0;
        opacity: 0.75;
    }

    .classic-cross-divider .line {
        height: 1px;
        flex: 1;
        background: linear-gradient(90deg, transparent, #c59b27, transparent);
    }

    .classic-cross-divider .cross {
        color: #854d0e;
        font-size: 0.9rem;
        line-height: 1;
        user-select: none;
    }

    /* Theological Covenant Callout Box */
    .theological-covenant-box {
        background: linear-gradient(135deg, #fdfaf2 0%, #fffdfa 50%, #f7f1e1 100%);
        border: 1.5px solid #d4b572;
        box-shadow: inset 0 0 0 2px #fffdf8, 0 4px 14px -2px rgba(133, 77, 14, 0.08);
        border-radius: 1rem;
        position: relative;
    }

    /* Navigation Segmented Control */
    .tab-btn {
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        color: #5d5345;
    }

    .tab-btn:hover {
        color: #0f172a;
        background-color: #ffffff;
    }

    .tab-btn.active {
        background: #0f3d64 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px -1px rgba(15, 61, 100, 0.35) !important;
    }

    .tab-btn.active .tab-count-badge {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.3);
    }

    .tab-count-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 9999px;
        background: #e2e8f0;
        color: #475569;
        border: 1px solid #cbd5e1;
        transition: all 0.2s ease;
    }

    /* Mobile Drawer & TOC Sidebar Styling */
    @media (max-width: 1023px) {
        #tocSidebar.mobile-drawer-open {
            display: flex !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            width: 86vw !important;
            max-width: 380px !important;
            height: 100vh !important;
            max-height: 100vh !important;
            z-index: 99999 !important;
            background-color: #fdfcf9 !important;
            border-right: 2px solid #c9a96e !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45) !important;
            border-radius: 0 1.25rem 1.25rem 0 !important;
            padding: 1.25rem !important;
            animation: slideInTocDrawer 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }

        @keyframes slideInTocDrawer {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
    }

    /* Copy Link Button */
    .ad-card button[onclick^="copyPasalLink"] {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.35rem 0.65rem;
        border-radius: 0.625rem;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .ad-card button[onclick^="copyPasalLink"]:hover {
        background-color: #0f3d64;
        border-color: #0f3d64;
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(15, 61, 100, 0.2);
        transform: translateY(-1px);
    }

    /* TOC Items */
    .custom-toc-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .custom-toc-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-toc-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }

    .custom-toc-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .toc-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.65rem;
        border-radius: 0.5rem;
        transition: all 0.18s ease;
        text-decoration: none;
        color: #475569;
        border-left: 2.5px solid transparent;
    }

    .toc-item:hover {
        background-color: #f8fafc;
        color: #0f3d64;
        transform: translateX(2px);
    }

    .toc-item.active {
        background: linear-gradient(90deg, rgba(15, 61, 100, 0.08) 0%, rgba(15, 61, 100, 0.02) 100%);
        color: #0f3d64;
        font-weight: 700;
        border-left-color: #0f3d64;
    }

    .toc-item-badge {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 4px;
        background: #f1f5f9;
        color: #64748b;
        flex-shrink: 0;
    }

    .toc-item.active .toc-item-badge {
        background: #0f3d64;
        color: #ffffff;
    }

    /* Search Highlights */
    .search-highlight {
        background: linear-gradient(120deg, #fef08a 0%, #fde047 100%);
        color: #713f12;
        padding: 0.1em 0.35em;
        border-radius: 0.25rem;
        font-weight: 700;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* Toast Notification */
    #copyToast {
        position: fixed !important;
        top: 88px !important;
        right: 24px !important;
        z-index: 999999 !important;
        opacity: 0 !important;
        transform: translateY(-12px) !important;
        pointer-events: none !important;
        transition: opacity 0.28s ease, transform 0.28s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    #copyToast.toast-visible {
        opacity: 1 !important;
        transform: translateY(0) !important;
        pointer-events: auto !important;
    }

    /* Print Optimization */
    @media print {

        .site-header,
        .site-footer,
        .no-print,
        .toc-sidebar,
        #readingProgressBar,
        #copyToast,
        #backToTopBtn {
            display: none !important;
        }

        .ad-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            page-break-inside: avoid;
            margin-bottom: 1.5rem !important;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
        }

        .tab-pane {
            display: block !important;
        }
    }
</style>

<!-- Top Reading Progress Indicator -->
<div id="readingProgressBar"></div>

<!-- Modern Toast Notification -->
<div id="copyToast"
    class="flex items-center gap-2.5 px-4 py-3 rounded-2xl bg-slate-900/95 text-white shadow-2xl backdrop-blur-md border border-slate-700/60 text-xs font-semibold no-print">
    <span
        class="flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500 text-white text-[11px] font-bold">✓</span>
    <span id="copyToastText">Tautan pasal berhasil disalin ke papan klip!</span>
</div>

<div class="min-w-full pb-24" style="background-color: #fcfbf7; background-image: radial-gradient(rgba(197, 155, 39, 0.15) 1px, transparent 1px), linear-gradient(to bottom, #fcfbf7 0%, #f7f2e5 50%, #f2ebd9 100%); background-size: 28px 28px, 100% 100%;">
    <!-- Top Hero Section -->
    <section
        class="relative bg-gradient-to-br from-[#051324] via-[#0a263f] to-[#0f3d64] text-white pt-14 pb-20 overflow-hidden shadow-md">
        <!-- Ambient decorative glows & subtle grid -->
        <div
            class="absolute -top-24 left-1/2 -translate-x-1/2 w-[800px] h-[350px] bg-gradient-to-b from-blue-500/15 via-amber-500/10 to-transparent blur-3xl pointer-events-none rounded-full">
        </div>
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none"
            style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 28px 28px;"></div>

        <div class="container relative z-10 px-4 sm:px-6">
            <div class="max-w-4xl mx-auto text-center">

                <!-- Ornamental Header Badge -->
                <div class="mx-auto mb-4 flex items-center justify-center gap-3 animate__animated animate__fadeInDown">
                    <span
                        class="block h-px w-10 sm:w-16 bg-gradient-to-r from-transparent via-amber-400/60 to-amber-400 rounded-full"></span>
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-classical font-extrabold uppercase tracking-widest bg-amber-500/15 text-amber-300 border border-amber-400/30 shadow-sm backdrop-blur-md">
                        <span>✝</span>
                        <span>Corpus Constitutionis • Landasan Yuridis</span>
                        <span>✝</span>
                    </div>
                    <span
                        class="block h-px w-10 sm:w-16 bg-gradient-to-l from-transparent via-amber-400/60 to-amber-400 rounded-full"></span>
                </div>

                <!-- Title (Classical Inscriptional) -->
                <h1
                    class="font-classical text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight mb-3 leading-tight animate__animated animate__fadeIn">
                    Anggaran Dasar &amp; Anggaran Rumah Tangga<br class="hidden sm:inline">
                    <span
                        class="bg-gradient-to-r from-amber-300 via-amber-200 to-amber-400 bg-clip-text text-transparent">GMKI
                        Cabang Padang</span>
                </h1>

                <!-- Christian Ecumenical Motto Ribbon & Tri Panji -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-2 mb-6 animate__animated animate__fadeIn">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-amber-300 border border-amber-400/30 text-xs font-classical font-semibold tracking-wider shadow-sm backdrop-blur-md">
                        <span>✝</span>
                        <span>"Ut Omnes Unum Sint" — Yohanes 17:21</span>
                        <span>✝</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 text-amber-200 border border-amber-400/30 text-xs font-classical font-semibold tracking-wider shadow-sm backdrop-blur-md">
                        <span>☩</span>
                        <span>Tinggi Iman • Tinggi Ilmu • Tinggi Pengabdian</span>
                        <span>☩</span>
                    </div>
                </div>

                <!-- Subtitle -->
                <p
                    class="text-slate-200/90 text-sm sm:text-base md:text-lg max-w-2xl mx-auto mb-8 leading-relaxed font-normal animate__animated animate__fadeIn">
                    Pedoman konstitusional tertinggi penatalayanan, kepemimpinan, dan kaderisasi di Tiga Medan Pelayanan
                    (Gereja, Perguruan Tinggi, dan Masyarakat) Gerakan Mahasiswa Kristen Indonesia.
                </p>

                <!-- Interactive Quick Access 4-Column Grid (Constitutional Pillars) -->
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 max-w-4xl mx-auto mb-10 text-left animate__animated animate__fadeInUp">
                    <!-- Stat 1: Anggaran Dasar -->
                    <div onclick="switchSection('ad'); scrollToReadingContent();"
                        class="cursor-pointer p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/20 hover:border-blue-400/60 hover:-translate-y-1 transition-all duration-300 shadow-lg group">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">📜</span>
                            <span class="text-[10px] font-bold text-blue-200 group-hover:text-amber-300 flex items-center gap-1 transition-colors">Baca ➔</span>
                        </div>
                        <div class="text-xs font-extrabold text-blue-200 uppercase tracking-wider">Hukum Pokok</div>
                        <div class="text-lg sm:text-xl font-black text-white">12 Pasal</div>
                        <div class="text-[11px] text-slate-300 font-medium">Anggaran Dasar (AD)</div>
                    </div>
                    <!-- Stat 2: Anggaran Rumah Tangga -->
                    <div onclick="switchSection('art'); scrollToReadingContent();"
                        class="cursor-pointer p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/20 hover:border-amber-400/60 hover:-translate-y-1 transition-all duration-300 shadow-lg group">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">⚖️</span>
                            <span class="text-[10px] font-bold text-amber-200 group-hover:text-amber-300 flex items-center gap-1 transition-colors">Baca ➔</span>
                        </div>
                        <div class="text-xs font-extrabold text-amber-300 uppercase tracking-wider">Tata Laksana</div>
                        <div class="text-lg sm:text-xl font-black text-white">12 Pasal</div>
                        <div class="text-[11px] text-slate-300 font-medium">Anggaran Rumah Tangga (ART)</div>
                    </div>
                    <!-- Stat 3: Peraturan Organisasi -->
                    <div onclick="switchSection('po'); scrollToReadingContent();"
                        class="cursor-pointer p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/20 hover:border-indigo-400/60 hover:-translate-y-1 transition-all duration-300 shadow-lg group">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">📑</span>
                            <span class="text-[10px] font-bold text-purple-200 group-hover:text-amber-300 flex items-center gap-1 transition-colors">Baca ➔</span>
                        </div>
                        <div class="text-xs font-extrabold text-purple-200 uppercase tracking-wider">Pedoman Teknis</div>
                        <div class="text-lg sm:text-xl font-black text-white">11 Pasal</div>
                        <div class="text-[11px] text-slate-300 font-medium">Peraturan Organisasi (PO)</div>
                    </div>
                    <!-- Stat 4: Penjelasan Resmi PO -->
                    <div onclick="switchSection('penjelasan'); scrollToReadingContent();"
                        class="cursor-pointer p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/20 hover:border-teal-400/60 hover:-translate-y-1 transition-all duration-300 shadow-lg group">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">💡</span>
                            <span class="text-[10px] font-bold text-teal-200 group-hover:text-amber-300 flex items-center gap-1 transition-colors">Baca ➔</span>
                        </div>
                        <div class="text-xs font-extrabold text-teal-200 uppercase tracking-wider">Doktrin Hukum</div>
                        <div class="text-lg sm:text-xl font-black text-white">Penjelasan Resmi</div>
                        <div class="text-[11px] text-slate-300 font-medium">Penafsiran Konstitusi PO</div>
                    </div>
                </div>

                <!-- Floating Interactive Toolbar -->
                <div
                    class="bg-white/95 backdrop-blur-xl p-3.5 sm:p-4 rounded-2xl shadow-2xl border border-[#e4dac7] text-slate-800 max-w-4xl mx-auto flex flex-col sm:flex-row items-center gap-3 no-print">
                    <!-- Search input with shortcut badge -->
                    <div class="relative w-full sm:flex-1">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <?= svg_icon('search', 18) ?>
                        </span>
                        <input type="text" id="adSearchInput"
                            placeholder="Cari pasal, ayat, atau kata kunci konstitusi..."
                            class="w-full pl-10 pr-20 py-2.5 sm:py-3 rounded-xl border border-[#e2d7c3] focus:outline-none focus:ring-2 focus:ring-[#0f3d64] focus:border-transparent text-xs sm:text-sm bg-[#faf8f4] focus:bg-white transition-all shadow-inner placeholder:text-slate-400"
                            autocomplete="off">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 gap-1.5">
                            <button type="button" id="clearSearchBtn"
                                class="hidden text-slate-400 hover:text-slate-600 text-sm p-1"
                                title="Hapus Pencarian">
                                ✕
                            </button>
                            <kbd
                                class="hidden md:inline-flex items-center px-2 py-0.5 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200 rounded-md shadow-xs pointer-events-none">
                                /
                            </kbd>
                        </div>
                    </div>

                    <!-- Enhanced Font Size Accessibility Controls (4-Level Scalability) -->
                    <div class="flex items-center justify-end w-full sm:w-auto shrink-0">
                        <div
                            class="inline-flex items-center rounded-xl border border-[#e2d5bc] bg-[#faf7f0] p-1 shadow-2xs gap-0.5 sm:gap-1">
                            <span
                                class="text-[10px] sm:text-[11px] font-classical text-[#854d0e] font-extrabold px-2 uppercase select-none flex items-center gap-1">
                                <span>🔍</span> Huruf:
                            </span>
                            <button type="button" id="fontDecrease"
                                class="font-size-btn px-2.5 sm:px-3 py-1.5 rounded-lg text-slate-700 hover:text-[#0f3d64] hover:bg-white transition text-xs font-bold"
                                title="Perkecil Ukuran Huruf (A-)">A-</button>
                            <button type="button" id="fontReset"
                                class="font-size-btn active px-3 sm:px-3.5 py-1.5 rounded-lg bg-[#0f3d64] text-amber-200 shadow-xs transition text-xs font-classical font-bold"
                                title="Ukuran Huruf Standar (Normal)">A <span class="hidden md:inline text-[10px] font-normal opacity-85">(Normal)</span></button>
                            <button type="button" id="fontIncrease"
                                class="font-size-btn px-2.5 sm:px-3 py-1.5 rounded-lg text-slate-700 hover:text-[#0f3d64] hover:bg-white transition text-xs font-bold"
                                title="Perbesar Ukuran Huruf (A+)">A+ <span class="hidden md:inline text-[10px] font-normal opacity-85">(Besar)</span></button>
                            <button type="button" id="fontIncreaseMax"
                                class="font-size-btn px-2.5 sm:px-3 py-1.5 rounded-lg text-slate-700 hover:text-[#0f3d64] hover:bg-white transition text-xs font-bold"
                                title="Perbesar Ukuran Huruf Maksimal (A++)">A++ <span class="hidden md:inline text-[10px] font-normal opacity-85">(Ekstra)</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Navigation Tabs & Control Center (Segmented Sticky Navigation) -->
    <div class="sticky top-[72px] z-30 bg-[#fdfcf9]/95 backdrop-blur-md border-b border-[#e5dac6] shadow-xs no-print transition-all">
        <div class="container px-3 sm:px-6">
            <div class="flex items-center justify-between py-2 sm:py-2.5 gap-2 sm:gap-4 flex-wrap sm:flex-nowrap">
                
                <!-- Flexible Responsive Segmented Tabs Container -->
                <div class="flex items-center p-1 sm:p-1.5 rounded-2xl bg-slate-100/95 border border-[#dfd4c0] overflow-x-auto no-scrollbar gap-1 sm:gap-1.5 max-w-full flex-1 shadow-inner">
                    <button type="button" onclick="switchSection('ad')"
                        class="tab-btn active px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-1.5 sm:gap-2 whitespace-nowrap transition-all cursor-pointer"
                        id="tab-ad">
                        <span class="text-sm sm:text-base">📜</span>
                        <span class="hidden md:inline">Anggaran Dasar</span>
                        <span class="md:hidden">AD</span>
                        <span class="tab-count-badge">12 Pasal</span>
                    </button>
                    <button type="button" onclick="switchSection('art')"
                        class="tab-btn px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:text-slate-900 hover:bg-white/70 flex items-center gap-1.5 sm:gap-2 whitespace-nowrap transition-all cursor-pointer"
                        id="tab-art">
                        <span class="text-sm sm:text-base">⚖️</span>
                        <span class="hidden md:inline">Anggaran Rumah Tangga</span>
                        <span class="md:hidden">ART</span>
                        <span class="tab-count-badge">12 Pasal</span>
                    </button>
                    <button type="button" onclick="switchSection('po')"
                        class="tab-btn px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:text-slate-900 hover:bg-white/70 flex items-center gap-1.5 sm:gap-2 whitespace-nowrap transition-all cursor-pointer"
                        id="tab-po">
                        <span class="text-sm sm:text-base">📑</span>
                        <span class="hidden md:inline">Peraturan Organisasi</span>
                        <span class="md:hidden">PO</span>
                        <span class="tab-count-badge">11 Pasal</span>
                    </button>
                    <button type="button" onclick="switchSection('penjelasan')"
                        class="tab-btn px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:text-slate-900 hover:bg-white/70 flex items-center gap-1.5 sm:gap-2 whitespace-nowrap transition-all cursor-pointer"
                        id="tab-penjelasan">
                        <span class="text-sm sm:text-base">💡</span>
                        <span class="hidden md:inline">Penjelasan Resmi PO</span>
                        <span class="md:hidden">Penjelasan</span>
                        <span class="tab-count-badge">Doktrin</span>
                    </button>
                </div>

                <!-- Right Action Bar: TOC Toggle Button (Tombol Hilang-Muncul Daftar Isi) & Search Counter -->
                <div class="flex items-center gap-2 shrink-0">
                    <!-- Match counter banner -->
                    <div id="searchCounter"
                        class="hidden text-xs font-bold text-amber-800 bg-amber-50 px-3 py-1.5 sm:py-2 rounded-xl border border-amber-300/80 shadow-xs whitespace-nowrap animate__animated animate__fadeIn">
                        0 hasil
                    </div>

                    <!-- Tombol Hilang-Muncul Daftar Isi (Toggle Table of Contents) -->
                    <button type="button" id="toggleTocBtn" onclick="toggleToc()"
                        class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-classical font-extrabold transition-all border shadow-xs bg-white hover:bg-amber-50 text-[#0f3d64] border-[#d8c8aa] shrink-0 active:scale-95 cursor-pointer"
                        title="Tampilkan atau Sembunyikan Daftar Isi (Mode Baca Penuh)">
                        <span id="toggleTocIcon" class="text-sm sm:text-base">📖</span>
                        <span id="toggleTocLabel" class="hidden sm:inline">Daftar Isi</span>
                        <span id="toggleTocBadge" class="text-[10px] font-sans px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-900 border border-amber-300/60 font-bold">Muncul</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Backdrop for TOC -->
    <div id="mobileTocBackdrop" onclick="toggleToc(false)"
        class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-[99998] transition-opacity duration-200"></div>

    <!-- Main Content Grid -->
    <div class="container px-4 sm:px-6 pt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Responsive Sticky Table of Contents Sidebar (Desktop & Mobile Drawer) -->
            <aside id="tocSidebar"
                class="hidden lg:flex lg:col-span-3 sticky top-[140px] max-h-[calc(100vh-160px)] flex-col no-print bg-[#fdfcf9] rounded-2xl border border-[#e4dac7] shadow-sm p-4 text-sm overflow-hidden transition-all duration-300">
                <!-- TOC Header -->
                <div class="pb-3 mb-3 border-b border-[#e8dfcf] flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-amber-700 text-xs font-classical select-none">✝</span>
                        <span class="font-classical font-extrabold text-xs uppercase tracking-wider text-[#0a263f]"
                            id="tocHeaderTitle">
                            Daftar Isi AD
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-classical text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300/60"
                            id="tocCountBadge">
                            12 Pasal
                        </span>
                        <!-- Close button (Visible on mobile drawer & quick desktop collapse) -->
                        <button type="button" onclick="toggleToc(false)"
                            class="text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-200/60 transition cursor-pointer"
                            title="Tutup / Sembunyikan Daftar Isi">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- TOC Filter Input -->
                <div class="mb-3">
                    <input type="text" id="tocFilterInput" placeholder="Saring daftar pasal..."
                        class="w-full text-xs px-3 py-1.5 rounded-lg border border-[#e2d5bc] bg-white focus:outline-none focus:ring-1 focus:ring-[#0f3d64] text-slate-700 placeholder:text-slate-400 shadow-2xs">
                </div>

                <!-- Dynamic TOC Container with custom sleek scrollbar -->
                <nav id="tocContainer"
                    class="space-y-1 text-slate-600 text-xs overflow-y-auto pr-1 flex-1 custom-toc-scroll">
                    <!-- Populated by JavaScript according to active section -->
                </nav>

                <!-- Constitutional Hierarchy Widget (Corpus Iuris) -->
                <div
                    class="mt-4 pt-3 border-t border-[#e8dfcf] text-[11px] text-slate-600 bg-gradient-to-b from-[#faf6ee] to-[#f4ede0] p-3 rounded-xl border border-[#dccbb0] shadow-2xs">
                    <div class="font-classical font-bold text-[#0a263f] flex items-center justify-between mb-1.5 pb-1 border-b border-[#e2d7c3]">
                        <span class="flex items-center gap-1.5 text-xs">
                            <span class="text-amber-700">✝</span> Hierarki Hukum GMKI
                        </span>
                        <span class="text-[9px] uppercase tracking-widest text-amber-800 font-semibold italic">Corpus Iuris</span>
                    </div>
                    <ol class="list-decimal list-inside space-y-1 text-[11px] text-[#42392e] leading-snug font-sacred">
                        <li><strong>Anggaran Dasar (AD)</strong> <span class="text-[10px] text-slate-500">— Lex Suprema</span></li>
                        <li><strong>Anggaran Rumah Tangga (ART)</strong></li>
                        <li><strong>Peraturan Organisasi (PO)</strong></li>
                        <li><strong>Ketetapan Kongres / Konpercab</strong></li>
                    </ol>
                </div>
            </aside>

            <!-- Main Legal Body Content -->
            <main class="lg:col-span-9 space-y-8 ad-reading-container transition-all duration-300" id="adContentContainer">

                <!-- ================================================================= -->
                <!-- 1. ANGGARAN DASAR (AD) -->
                <!-- ================================================================= -->
                <div id="section-ad" class="tab-pane space-y-6">

                    <!-- Preambule / Pembukaan Special Card (Classical Christian Parchment Charter) -->
                    <div id="ad-pembukaan"
                        class="ad-card parchment-charter p-6 sm:p-12 relative overflow-hidden"
                        data-searchable>

                        <!-- 4 Corner Classical Cross Motifs -->
                        <div class="absolute top-3.5 left-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>
                        <div class="absolute top-3.5 right-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>
                        <div class="absolute bottom-3.5 left-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>
                        <div class="absolute bottom-3.5 right-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>

                        <!-- Classical Latin Ecumenical Motto Ribbon -->
                        <div class="flex items-center justify-center gap-2 sm:gap-3 py-1.5 px-4 mb-6 rounded-lg bg-amber-950/5 border border-amber-300/50 text-amber-900 text-center shadow-2xs">
                            <span class="text-amber-700 text-xs sm:text-sm">✝</span>
                            <span class="font-classical text-[10px] sm:text-xs font-bold tracking-[0.22em] uppercase">In Nomine Domini • Ut Omnes Unum Sint</span>
                            <span class="text-amber-700 text-xs sm:text-sm">✝</span>
                        </div>

                        <!-- Card Header -->
                        <div class="flex items-center justify-between mb-8 pb-5 border-b-2 border-[#e2d5bc]">
                            <div class="flex items-center gap-3.5">
                                <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-gradient-to-br from-[#0a263f] to-[#0f3d64] text-amber-300 font-classical font-extrabold text-xl shadow-sm border border-amber-400/40">☩</span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-classical text-xs font-extrabold uppercase tracking-[0.16em] text-amber-800">Mukadimah Konstitusi</span>
                                        <span class="hidden sm:inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span class="hidden sm:inline-block font-sacred text-xs font-semibold text-slate-600 italic">Dasar Teologis &amp; Pengutusan GMKI</span>
                                    </div>
                                    <h2 class="font-classical text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#091b2e] tracking-tight mt-0.5">Pembukaan Anggaran Dasar</h2>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pembukaan')"
                                class="text-slate-500 hover:text-slate-800 text-xs flex items-center gap-1 font-semibold"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>

                        <!-- 4 Alinea Teologis Pembukaan (Classic Christian Liturgical Typography) -->
                        <div class="space-y-6">
                            <div class="border-l-2 sm:border-l-3 border-[#c9a96e] pl-4 sm:pl-8 space-y-6 sm:space-y-7 text-[#231e19] font-sacred text-base sm:text-[18px] md:text-[19px] leading-relaxed sm:leading-[2.05] text-left">
                                <!-- Alinea 1: Kristologis & Karya Keselamatan -->
                                <p class="dropcap-ecclesiastical">
                                    <strong>Sesungguhnya Yesus Kristus</strong>, Anak Allah dan Juruselamat, ialah Tuhan
                                    manusia dan alam semesta. Kehadiran-Nya dalam sejarah ialah perbuatan Allah untuk
                                    menebus dan menyelamatkan manusia melalui kematian dan kebangkitan-Nya yang menjadikan
                                    semuanya baru dan sempurna.
                                </p>

                                <!-- Classic Christian Liturgical Cross Divider -->
                                <div class="classic-cross-divider">
                                    <span class="line"></span>
                                    <span class="cross">☩</span>
                                    <span class="line"></span>
                                </div>

                                <!-- Alinea 2: Panggilan Iman & Penatalayanan -->
                                <p>
                                    <strong>Anugerah-Nya</strong> yang dinyatakan dalam karya-Nya memanggil manusia untuk
                                    percaya dan mengucap syukur dalam penatalayanan alam semesta, mewujudkan iman,
                                    pengharapan dan cinta kasih dalam kehidupan sehari-hari.
                                </p>

                                <!-- Classic Christian Liturgical Cross Divider -->
                                <div class="classic-cross-divider">
                                    <span class="line"></span>
                                    <span class="cross">☩</span>
                                    <span class="line"></span>
                                </div>

                                <!-- Alinea 3: Pneumatologis & Gereja yang Esa -->
                                <p>
                                    <strong>Roh Kudus</strong> menghidupkan persekutuan orang beriman selaku gereja yang
                                    Esa, Am dan Rasuli, yang diutus untuk menyampaikan kabar keselamatan dan pembebasan bagi
                                    pembaharuan manusia dan alam semesta.
                                </p>

                                <!-- Classic Christian Liturgical Cross Divider -->
                                <div class="classic-cross-divider">
                                    <span class="line"></span>
                                    <span class="cross">☩</span>
                                    <span class="line"></span>
                                </div>

                                <!-- Alinea 4: Panggilan & Pengutusan di Indonesia -->
                                <p>
                                    <strong>Maka menjadi panggilan dan pengutusan</strong> setiap warga gereja yang
                                    ditempatkan oleh Tuhan di dalam perjalanan sejarah bangsa dan negara Indonesia, untuk
                                    menyatakan kehadiran-Nya dan kehidupan yang bertanggungjawab bersumber pada Alkitab,
                                    yang menyaksikan Yesus Kristus ialah Tuhan dan Juruselamat di dalam keesaan Allah Bapa,
                                    Anak dan Roh Kudus yang mengerjakan keselamatan manusia untuk mewujudkan kesejahteraan
                                    perdamaian, keadilan dan kebenaran di tengah-tengah masyarakat, bangsa dan negara.
                                </p>
                            </div>

                            <!-- Showcase Card: Piagam Kelahiran Gerakan (Historis - 9 Februari 1950) -->
                            <div class="mt-10 rounded-2xl border-2 border-[#c9a96e] bg-[#faf6ee] p-6 sm:p-8 shadow-sm space-y-4 relative">
                                <div class="flex items-center justify-between flex-wrap gap-2 border-b border-[#e2d5bc] pb-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#0f3d64] text-amber-300 font-classical font-bold text-sm shadow-xs">✝</span>
                                        <div>
                                            <h3 class="font-classical font-extrabold text-slate-900 text-sm sm:text-base tracking-wide">Piagam Kelahiran Gerakan</h3>
                                            <p class="font-sacred text-xs text-amber-900 italic">Peleburan Persekutuan Oikumenis Mahasiswa Kristen di Indonesia</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-classical px-3 py-1 rounded-full bg-[#0f3d64] text-amber-300 font-bold text-xs tracking-wider border border-amber-400/40">📅 9 Februari 1950</span>
                                        <span class="font-classical px-2.5 py-1 rounded-md bg-amber-100 text-amber-950 font-bold text-[11px] border border-amber-300">WSCF</span>
                                    </div>
                                </div>

                                <!-- Alur Sejarah Peleburan GMKI -->
                                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap text-xs font-semibold text-slate-700 bg-white/90 p-3 rounded-xl border border-[#e2d5bc] shadow-2xs">
                                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 border border-slate-200">CSV op Java (1932)</span>
                                    <span class="text-amber-600 font-bold text-sm">+</span>
                                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 border border-slate-200">PMKI</span>
                                    <span class="text-amber-600 font-bold text-sm">➔</span>
                                    <span class="px-3 py-1 rounded-md bg-[#0f3d64] text-amber-300 font-extrabold tracking-wide border border-amber-400/30">GMKI (1950)</span>
                                    <span class="text-amber-600 font-bold text-sm">➔</span>
                                    <span class="px-2.5 py-1 rounded-md bg-blue-100 text-blue-900 font-bold border border-blue-200">WSCF Affiliated</span>
                                </div>

                                <p class="font-sacred text-sm sm:text-base text-slate-800 leading-relaxed sm:leading-[1.95]">
                                    Untuk mewujudkan panggilan dan pengutusan dalam kehidupan dan perkembangan Perguruan
                                    Tinggi dan mahasiswa, maka pada tanggal <strong>9 Februari 1950</strong> Mahasiswa
                                    Kristen Indonesia yang melanjutkan usaha <em>Christelijke Studenten Vereeniging op
                                        Java</em> (berdiri 28 Desember 1932 di Kaliurang) bersama-sama dengan
                                    <em>Perhimpunan Mahasiswa Kristen Indonesia</em> meleburkan diri dan berhimpun dalam
                                    satu bentuk persekutuan dengan nama <strong>Gerakan Mahasiswa Kristen Indonesia
                                        (GMKI)</strong>, yang bergabung dalam <em>World Student Christian Federation
                                        (WSCF)</em>.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 1 AD -->
                    <div id="ad-pasal-1" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 1</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Nama, Tempat dan Waktu</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-1')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <ol
                            class="list-decimal list-inside space-y-2 text-[#2c241b] text-base leading-relaxed">
                            <li>Organisasi ini bernama <strong>Gerakan Mahasiswa Kristen Indonesia</strong>, disingkat
                                <strong>GMKI</strong>.
                            </li>
                            <li>Organisasi ini berkedudukan di tempat Pengurus Pusat.</li>
                            <li>Organisasi ini berdiri untuk waktu yang tidak ditentukan.</li>
                        </ol>
                    </div>

                    <!-- Pasal 2 AD (Illuminated Covenant Frame) -->
                    <div id="ad-pasal-2" class="ad-card theological-covenant-box p-6 sm:p-8"
                        data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#dfcfb0]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 2</span>
                                <div>
                                    <div class="font-classical text-[10px] uppercase tracking-widest text-amber-800 font-bold">Pactum Fundamenti</div>
                                    <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Asas Organisasi</h3>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-2')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>

                        <!-- Classical Latin & Indonesian Covenant Banner -->
                        <div
                            class="p-5 sm:p-6 rounded-xl bg-white/95 border-2 border-[#c59b27] text-[#1e1914] text-base sm:text-lg font-sacred font-semibold italic shadow-sm relative overflow-hidden">
                            <div class="absolute top-2 right-3 text-amber-600/30 text-2xl font-classical select-none">✝</div>
                            <p class="leading-relaxed">
                                "Dalam kehidupan bermasyarakat, berbangsa dan bernegara, organisasi ini berasaskan Pancasila
                                sebagai satu-satunya ASAS."
                            </p>
                        </div>
                    </div>

                    <!-- Pasal 3 AD -->
                    <div id="ad-pasal-3" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 3</span>
                                <div>
                                    <div class="font-classical text-[10px] uppercase tracking-widest text-blue-900/70 font-bold">Visio et Missio Dei</div>
                                    <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Visi dan Misi</h3>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-3')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-[#2c241b] text-base leading-relaxed">
                            <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-r from-blue-50/90 via-amber-50/40 to-blue-50/60 border border-[#c59b27]/40 shadow-2xs">
                                <div class="font-classical font-bold text-[#0f3d64] mb-1 flex items-center gap-2 text-sm sm:text-base">
                                    <span class="text-amber-600 text-base">✝</span> 1. Visi Organisasi
                                </div>
                                <p class="text-[#0a263f] font-sacred text-base sm:text-[17px] font-medium leading-relaxed">
                                    Terwujudnya kedamaian, kesejahteraan, keadilan, keutuhan ciptaan dan demokrasi di
                                    Indonesia berdasarkan kasih.
                                </p>
                            </div>
                            <div>
                                <div class="font-classical font-bold text-[#0a263f] mb-3 flex items-center gap-2 text-sm sm:text-base">
                                    <span class="text-amber-600 text-base">☩</span> 2. Misi Organisasi
                                </div>
                                <ul class="space-y-3 pl-1">
                                    <li class="flex items-start gap-3 bg-[#fdfcf9] p-3.5 rounded-xl border border-[#e8dfcf]">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-bold shrink-0 mt-0.5 shadow-2xs">a</span>
                                        <span class="text-[#2c241b] leading-relaxed">Mengajak mahasiswa dan warga Perguruan Tinggi lainnya kepada pengenalan
                                            akan Yesus Kristus selaku Tuhan dan Penebus dan memperdalam iman dalam
                                            kehidupan dan pekerjaan sehari-hari.</span>
                                    </li>
                                    <li class="flex items-start gap-3 bg-[#fdfcf9] p-3.5 rounded-xl border border-[#e8dfcf]">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-bold shrink-0 mt-0.5 shadow-2xs">b</span>
                                        <span class="text-[#2c241b] leading-relaxed">Membina kesadaran selaku warga gereja yang esa di tengah-tengah mahasiswa
                                            dan Perguruan Tinggi dalam kesaksian memperbaharui masyarakat, manusia dan
                                            gereja.</span>
                                    </li>
                                    <li class="flex items-start gap-3 bg-[#fdfcf9] p-3.5 rounded-xl border border-[#e8dfcf]">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-bold shrink-0 mt-0.5 shadow-2xs">c</span>
                                        <span class="text-[#2c241b] leading-relaxed">Mempersiapkan pemimpin dan penggerak yang ahli dan bertanggungjawab dengan
                                            menjalankan panggilan di tengah-tengah masyarakat, negara, gereja, Perguruan
                                            Tinggi, dan mahasiswa, dan menjadi sarana bagi terwujudnya kesejahteraan,
                                            perdamaian, keadilan, kebenaran dan cinta kasih di tengah-tengah manusia dan
                                            alam semesta.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 4 AD -->
                    <div id="ad-pasal-4" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 4</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Usaha</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-4')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-[#2c241b] text-base leading-relaxed">
                            Organisasi ini berusaha mencapai Visi dan Misinya sejalan dengan Asas Organisasi.
                        </p>
                    </div>

                    <!-- Pasal 5 AD (Status dan Bentuk - Bersifat Gerejawi) -->
                    <div id="ad-pasal-5" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 5</span>
                                <div>
                                    <div class="font-classical text-[10px] uppercase tracking-widest text-amber-800 font-bold">Ecclesia & Unitas</div>
                                    <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Status dan Bentuk Organisasi</h3>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-5')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-base">
                            <div class="p-4 sm:p-5 rounded-xl bg-[#fdfcf9] border-2 border-[#d8c7a6] shadow-2xs">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="text-amber-700 font-classical">✝</span>
                                    <strong class="font-classical text-[#0a263f] text-sm sm:text-base">1. Status :</strong>
                                </div>
                                <span class="text-[#2c241b] leading-relaxed">Organisasi ini adalah organisasi yang <strong>bersifat gerejawi</strong> dan
                                    tidak merupakan bagian dari organisasi politik.</span>
                            </div>
                            <div class="p-4 sm:p-5 rounded-xl bg-[#fdfcf9] border-2 border-[#d8c7a6] shadow-2xs">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="text-[#0f3d64] font-classical">🏛️</span>
                                    <strong class="font-classical text-[#0a263f] text-sm sm:text-base">2. Bentuk :</strong>
                                </div>
                                <span class="text-[#2c241b] leading-relaxed">Organisasi ini berbentuk <strong>kesatuan</strong> yang mempunyai
                                    cabang-cabang di kota-kota perguruan tinggi di Indonesia.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 6 AD -->
                    <div id="ad-pasal-6" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 6</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Keanggotaan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-6')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-[#2c241b] text-base leading-relaxed">
                            <p><strong>1.</strong> Yang diterima menjadi anggota ialah mereka yang menerima tujuan serta
                                bersedia menjalankan usaha organisasi.</p>
                            <div>
                                <strong class="font-classical text-[#0a263f] block mb-2 text-sm sm:text-base">2. Anggota terdiri dari :</strong>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs sm:text-sm font-semibold">
                                    <span
                                        class="p-2.5 rounded-lg bg-[#faf7f0] text-center border border-[#e2d5bc] text-[#2c241b]">a.
                                        Anggota Biasa</span>
                                    <span
                                        class="p-2.5 rounded-lg bg-[#faf7f0] text-center border border-[#e2d5bc] text-[#2c241b]">b.
                                        Anggota Luar Biasa</span>
                                    <span
                                        class="p-2.5 rounded-lg bg-[#faf7f0] text-center border border-[#e2d5bc] text-[#2c241b]">c.
                                        Anggota Kehormatan</span>
                                    <span
                                        class="p-2.5 rounded-lg bg-[#faf7f0] text-center border border-[#e2d5bc] text-[#2c241b]">d.
                                        Anggota Penyokong</span>
                                </div>
                            </div>
                            <div>
                                <strong class="font-classical text-[#0a263f] block mb-1 text-sm sm:text-base">3. Hak Anggota :</strong>
                                <ul class="list-disc list-inside space-y-1 text-[#2c241b] pl-2">
                                    <li><strong>Anggota biasa:</strong> mempunyai hak suara, hak memilih dan hak
                                        dipilih.</li>
                                    <li><strong>Anggota luar biasa:</strong> mempunyai hak dipilih dan hak usul.</li>
                                    <li><strong>Anggota kehormatan & penyokong:</strong> mempunyai hak usul.</li>
                                </ul>
                            </div>
                            <div>
                                <strong class="font-classical text-[#0a263f] block mb-1 text-sm sm:text-base">4. Kewajiban Anggota :</strong>
                                <ul class="list-disc list-inside space-y-1 text-[#2c241b] pl-2">
                                    <li>Bertanggungjawab mewujudkan tujuan dan usaha berdasarkan Anggaran Dasar dan
                                        Anggaran Rumah Tangga organisasi.</li>
                                    <li>Bertanggungjawab mewujudkan dan membina persekutuan dalam kehidupan organisasi.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 7 AD -->
                    <div id="ad-pasal-7" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 7</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Alat Perlengkapan Organisasi
                                </h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-7')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-[#2c241b] text-base leading-relaxed">
                            <p><strong>1.</strong> Organisasi ini mempunyai alat perlengkapan yang terdiri dari:
                                <strong>a. Kongres</strong>, <strong>b. Pengurus Pusat</strong>, <strong>c. Konperensi
                                    Cabang</strong>, <strong>d. Badan Pengurus Cabang</strong>.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 rounded-xl bg-[#fdfcf9] border border-[#e2d5bc] space-y-1.5 shadow-2xs">
                                    <div class="font-classical font-bold text-[#0f3d64] flex items-center gap-1.5 text-sm sm:text-base">
                                        <span>🏛️</span> 2. Kongres
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-700">• Kongres adalah badan tertinggi dalam
                                        organisasi.</p>
                                    <p class="text-xs sm:text-sm text-slate-700">• Berlangsung sekurang-kurangnya satu
                                        kali dalam dua tahun.</p>
                                    <p class="text-xs sm:text-sm text-slate-700">• Atas panggilan PP atau permintaan
                                        sekurang-kurangnya 2/3 jumlah cabang.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-[#fdfcf9] border border-[#e2d5bc] space-y-1.5 shadow-2xs">
                                    <div class="font-classical font-bold text-[#0f3d64] flex items-center gap-1.5 text-sm sm:text-base">
                                        <span>👥</span> 3. Pengurus Pusat (PP)
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-700">• Organisasi ini dipimpin oleh Pengurus
                                        Pusat.</p>
                                    <p class="text-xs sm:text-sm text-slate-700">• Pengurus Pusat dipilih oleh Kongres
                                        untuk masa kerja dua tahun.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-[#fdfcf9] border border-[#e2d5bc] space-y-1.5 shadow-2xs">
                                    <div class="font-classical font-bold text-[#0f3d64] flex items-center gap-1.5 text-sm sm:text-base">
                                        <span>🏛️</span> 4. Konperensi Cabang (Konpercab)
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-700">• Badan tertinggi dalam cabang.</p>
                                    <p class="text-xs sm:text-sm text-slate-700">• Berlangsung sekurang-kurangnya satu
                                        kali dalam dua tahun.</p>
                                    <p class="text-xs sm:text-sm text-slate-700">• Atas panggilan BPC atau permintaan
                                        sekurang-kurangnya 2/3 jumlah anggota biasa.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-[#fdfcf9] border border-[#e2d5bc] space-y-1.5 shadow-2xs">
                                    <div class="font-classical font-bold text-[#0f3d64] flex items-center gap-1.5 text-sm sm:text-base">
                                        <span>👥</span> 5. Badan Pengurus Cabang (BPC)
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-700">• Cabang dipimpin oleh Badan Pengurus
                                        Cabang.</p>
                                    <p class="text-xs sm:text-sm text-slate-700">• Dipilih oleh Konperensi Cabang untuk
                                        masa kerja satu atau dua tahun.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 8 AD -->
                    <div id="ad-pasal-8" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 8</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Keputusan Persidangan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-8')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-[#2c241b] text-base leading-relaxed">
                            <p><strong>a.</strong> Keputusan persidangan organisasi ini diambil berdasarkan
                                <strong>musyawarah untuk mufakat</strong> dengan hikmah kebijaksanaan, dan jika
                                diperlukan diambil berdasarkan pemungutan suara terbanyak.
                            </p>
                            <p><strong>b.</strong> Pemungutan suara terbanyak dalam kongres dilakukan dengan
                                <strong>satu cabang satu suara</strong>.
                            </p>
                        </div>
                    </div>

                    <!-- Pasal 9 AD -->
                    <div id="ad-pasal-9" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 9</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Perbendaharaan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-9')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-[#2c241b] text-base leading-relaxed">
                            Perbendaharaan organisasi ini diperoleh dari <strong>iuran anggota</strong>,
                            <strong>sumbangan</strong>, dan <strong>pendapatan lain</strong> yang sesuai dengan asas dan
                            tujuan organisasi.
                        </p>
                    </div>

                    <!-- Pasal 10 AD -->
                    <div id="ad-pasal-10" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 10</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Perubahan Anggaran Dasar</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-10')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-[#2c241b] text-base leading-relaxed">
                            <p><strong>1.</strong> Perubahan Anggaran Dasar Organisasi ini berlaku berdasarkan Keputusan
                                Kongres dengan persetujuan sekurang-kurangnya <strong>tiga perempat (3/4)</strong>
                                jumlah suara utusan yang hadir.</p>
                            <p><strong>2. a.</strong> Usul perubahan Anggaran Dasar dari Cabang sudah disampaikan kepada
                                Pengurus Pusat selambat-lambatnya <strong>empat bulan</strong> sebelum kongres.</p>
                            <p><strong>2. b.</strong> Selanjutnya Pengurus Pusat sudah menyampaikan kepada Cabang-cabang
                                selambat-lambatnya <strong>dua bulan</strong> sebelum kongres.</p>
                        </div>
                    </div>

                    <!-- Pasal 11 AD -->
                    <div id="ad-pasal-11" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 11</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Pembubaran Organisasi</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-11')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-[#2c241b] text-base leading-relaxed">
                            <p><strong>1.</strong> Organisasi ini dibubarkan berdasarkan keputusan Kongres yang khusus
                                berlangsung untuk maksud tersebut yang dihadiri oleh sekurang-kurangnya <strong>tiga
                                    perempat (3/4)</strong> jumlah cabang, serta memperoleh persetujuan
                                sekurang-kurangnya <strong>tiga perempat (3/4)</strong> dari jumlah suara utusan yang
                                hadir.</p>
                            <p><strong>2. a.</strong> Pengurus Pusat memberitahukan kepada cabang-cabang
                                selambat-lambatnya <strong>dua bulan</strong> sebelum kongres khusus tersebut.</p>
                            <p><strong>2. b.</strong> Kongres Khusus memutuskan mengenai hak milik organisasi.</p>
                        </div>
                    </div>

                    <!-- Pasal 12 AD -->
                    <div id="ad-pasal-12" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical font-extrabold shadow-xs">PASAL 12</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">Aturan Tambahan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-12')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-[#2c241b] text-base leading-relaxed">
                            Hal-hal yang belum tercakup dalam Anggaran Dasar ini diatur dalam <strong>Anggaran Rumah
                                Tangga (ART)</strong> dan tidak bertentangan dengan Anggaran Dasar.
                        </p>
                    </div>

                </div>

                <!-- ================================================================= -->
                <!-- 2. ANGGARAN RUMAH TANGGA (ART) -->
                <!-- ================================================================= -->
                <div id="section-art" class="tab-pane hidden space-y-6">

                    <!-- ART Introductory Charter Card (Explaining Function & Relationship of ART) -->
                    <div id="art-intro-charter" class="ad-card parchment-charter p-6 sm:p-10 relative overflow-hidden" data-searchable>
                        <!-- 4 Corner Cross Motifs -->
                        <div class="absolute top-3.5 left-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>
                        <div class="absolute top-3.5 right-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>
                        <div class="absolute bottom-3.5 left-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>
                        <div class="absolute bottom-3.5 right-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>

                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-[#e2d5bc]">
                            <div class="flex items-center gap-3.5">
                                <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-gradient-to-br from-[#78350f] to-[#b45309] text-amber-200 font-classical font-extrabold text-xl shadow-sm border border-amber-400/40">⚖️</span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-classical text-xs font-extrabold uppercase tracking-[0.16em] text-amber-800">Lex Secunda</span>
                                        <span class="hidden sm:inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span class="hidden sm:inline-block font-sacred text-xs font-semibold text-slate-600 italic">Tata Laksana Penatalayanan GMKI</span>
                                    </div>
                                    <h2 class="font-classical text-2xl sm:text-3xl font-extrabold text-[#091b2e] tracking-tight mt-0.5">Anggaran Rumah Tangga (ART)</h2>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-intro-charter')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pengantar ART ini">
                                <span>🔗</span>
                            </button>
                        </div>

                        <!-- Explanatory Narrative -->
                        <div class="font-sacred text-slate-800 space-y-4 text-base sm:text-lg leading-relaxed">
                            <p class="dropcap-ecclesiastical">
                                <strong>Anggaran Rumah Tangga (ART)</strong> adalah instrumen yuridis operasional yang menjabarkan pokok-pokok ketentuan dalam <strong>Anggaran Dasar (AD)</strong> ke dalam tata laksana praktis, prosedur kelembagaan, serta mekanisme penatalayanan di seluruh jenjang kepengurusan Gerakan Mahasiswa Kristen Indonesia.
                            </p>
                            <p>
                                ART mengatur secara mendalam hak dan kewajiban anggota, tata cara persidangan dan pengambilan keputusan dalam Kongres maupun Konperensi Cabang, pembentukan dan pembubaran cabang, pengelolaan perbendaharaan serta Badan Pemeriksa Keuangan (BPK), hingga mekanisme penegakan disiplin organisasi.
                            </p>
                        </div>

                        <!-- Responsive 4-Pillar Grid of ART Core Governance -->
                        <div class="mt-6 pt-5 border-t border-[#e2d5bc] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                            <div class="p-3 bg-white/90 rounded-xl border border-[#e2d5bc] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-amber-900 flex items-center gap-1.5">
                                    <span>👥</span> <span>Keanggotaan</span>
                                </div>
                                <p class="text-slate-600 text-[11px] leading-snug">Anggota Biasa, Luar Biasa, Kehormatan, dan Penyokong.</p>
                            </div>
                            <div class="p-3 bg-white/90 rounded-xl border border-[#e2d5bc] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-amber-900 flex items-center gap-1.5">
                                    <span>🏛️</span> <span>Kepengurusan &amp; Forum</span>
                                </div>
                                <p class="text-slate-600 text-[11px] leading-snug">Kongres, Pengurus Pusat, Konpercab, dan Badan Pengurus Cabang.</p>
                            </div>
                            <div class="p-3 bg-white/90 rounded-xl border border-[#e2d5bc] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-amber-900 flex items-center gap-1.5">
                                    <span>💰</span> <span>Perbendaharaan &amp; BPK</span>
                                </div>
                                <p class="text-slate-600 text-[11px] leading-snug">Iuran, inventaris, dan audit keuangan oleh Badan Pemeriksa Keuangan.</p>
                            </div>
                            <div class="p-3 bg-white/90 rounded-xl border border-[#e2d5bc] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-amber-900 flex items-center gap-1.5">
                                    <span>⚖️</span> <span>Disiplin Organisasi</span>
                                </div>
                                <p class="text-slate-600 text-[11px] leading-snug">Sanksi, peringatan, pemberhentian, serta hak pembelaan diri.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 1 ART (Usaha - Tri Panji GMKI) -->
                    <div id="art-pasal-1" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-classical font-extrabold shadow-xs">ART
                                    PASAL 1</span>
                                <div>
                                    <div class="font-classical text-[10px] uppercase tracking-widest text-amber-800 font-bold">Tri Panji Gerakan</div>
                                    <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">U s a h a</h3>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-1')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <ol
                            class="space-y-4 text-[#2c241b] text-base leading-relaxed list-none pl-0">
                            <li class="p-4 sm:p-5 rounded-xl bg-[#fdfcf9] border-2 border-[#e5dac6] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-[#0f3d64] flex items-center gap-2 text-sm sm:text-base">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-md bg-[#0f3d64] text-amber-200 text-xs font-classical">1</span>
                                    <span>Tinggi Iman &amp; Keesaan Gereja</span>
                                </div>
                                <p class="text-[#2c241b] pl-8 leading-relaxed">Mempertumbuhkan dan memperdalam kehidupan beriman dengan doa, Penelaahan Alkitab,
                                    ibadat, pembinaan persekutuan dan tanggung jawab bagi perkembangan, pembaharuan dan
                                    keesaan gereja yang am.</p>
                            </li>
                            <li class="p-4 sm:p-5 rounded-xl bg-[#fdfcf9] border-2 border-[#e5dac6] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-[#78350f] flex items-center gap-2 text-sm sm:text-base">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-md bg-amber-600 text-white text-xs font-classical">2</span>
                                    <span>Tinggi Ilmu &amp; Panggilan Perguruan Tinggi</span>
                                </div>
                                <p class="text-[#2c241b] pl-8 leading-relaxed">Membina kemajuan studi dan riset untuk mengikuti dan menguasai ilmu pengetahuan,
                                    mewujudkan panggilan Perguruan Tinggi mahasiswa dalam mempersiapkan sarjana dan pemimpin
                                    yang ahli dan bertanggungjawab bagi pembangunan dan pembaruan untuk mencapai
                                    kesejahteraan materil dan spiritual.</p>
                            </li>
                            <li class="p-4 sm:p-5 rounded-xl bg-[#fdfcf9] border-2 border-[#e5dac6] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-[#0a263f] flex items-center gap-2 text-sm sm:text-base">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-md bg-[#0a263f] text-amber-300 text-xs font-classical">3</span>
                                    <span>Tinggi Pengabdian di Tengah Dunia</span>
                                </div>
                                <p class="text-[#2c241b] pl-8 leading-relaxed">Membina pemimpin dan penggerak yang bekerja secara bertanggung jawab terhadap Allah dan
                                    manusia di dalam masyarakat, negara, gereja, perguruan tinggi dan mahasiswa bagi
                                    terwujudunya perdamaian, keadilan, kesejahteraan, kebenaran dan cinta kasih di
                                    tengah-tengah
                                    manusia dan alam semesta.</p>
                            </li>
                        </ol>
                    </div>

                    <!-- Pasal 2 ART -->
                    <div id="art-pasal-2" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 2</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Keanggotaan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-2')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <div>
                                <strong class="text-slate-900 block mb-2">1. Anggota terdiri dari :</strong>
                                <ul class="list-disc list-inside space-y-1 text-slate-700 pl-2">
                                    <li><strong>Anggota Biasa:</strong> yaitu mahasiswa, warga negara Indonesia, yang
                                        sedang mengikuti kuliah pada suatu Perguruan Tinggi di Indonesia sampai dua
                                        tahun sesudah tidak menjadi mahasiswa lagi.</li>
                                    <li><strong>Anggota Luar Biasa:</strong> bekas anggota biasa; atau bekas mahasiswa
                                        dan mahasiswa yang tidak termasuk dalam titik a.</li>
                                    <li><strong>Anggota Kehormatan:</strong> yaitu mereka yang berjasa kepada
                                        organisasi.</li>
                                    <li><strong>Anggota Penyokong:</strong> yaitu mereka yang bersedia membantu
                                        organisasi secara berkala dengan jumlah yang ditentukan oleh Badan Pengurus
                                        Cabang.</li>
                                </ul>
                            </div>
                            <div>
                                <strong class="text-slate-900 block mb-2">2. Penerimaan Anggota :</strong>
                                <ul class="list-disc list-inside space-y-1 text-slate-700 pl-2">
                                    <li>Anggota biasa diterima oleh Badan Pengurus Cabang setelah memenuhi syarat
                                        penerimaan anggota.</li>
                                    <li>Anggota luar biasa diterima oleh Badan Pengurus Cabang setelah memenuhi syarat
                                        penerimaan anggota.
                                    </li>
                                    <li>Anggota kehormatan diangkat oleh Pengurus Pusat atas usul Badan Pengurus Cabang.
                                    </li>
                                    <li>Anggota penyokong diangkat oleh Badan Pengurus Cabang.</li>
                                </ul>
                            </div>
                            <div>
                                <strong class="text-slate-900 block mb-2">3. Pembebasan Keanggotaan Berlaku Karena
                                    :</strong>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs sm:text-sm">
                                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">1. Meninggal dunia.
                                    </div>
                                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">2. Atas
                                        permintaannya sendiri secara tertulis kepada BPC.</div>
                                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">3. Dibebaskan
                                        sementara oleh BPC (berhak membela diri di Konpercab).</div>
                                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">4. Dipecat dengan
                                        keputusan Konpercab (berhak membela diri di Kongres).</div>
                                </div>
                            </div>
                            <p><strong>4. Daftar Anggota:</strong> BPC sudah menyerahkan daftar anggota kepada Pengurus
                                Pusat sekurang-kurangnya 1 kali dalam 2 tahun yang diserahkan selambat-lambatnya 3 bulan
                                sebelum kongres.</p>
                        </div>
                    </div>

                    <!-- Pasal 3 ART -->
                    <div id="art-pasal-3" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 3</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">K o n g r e s</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-3')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong>1.</strong> Kongres berlangsung dengan sah apabila dihadiri oleh
                                sekurang-kurangnya <strong>½ n + 1</strong> jumlah cabang dan sekurang-kurangnya
                                <strong>½ n + 1</strong> dari jumlah seluruh utusan yang telah ditentukan.
                            </p>
                            <p><strong>2.</strong> Utusan-utusan yang menghadiri Kongres mewakili cabang yang BPC-nya
                                sudah dilantik dan disahkan oleh Pengurus Pusat.</p>

                            <!-- Quota table -->
                            <div>
                                <strong class="text-slate-900 block mb-2">3. Jumlah Utusan Cabang yang Menghadiri
                                    Kongres :</strong>
                                <div class="overflow-x-auto rounded-xl border border-slate-200">
                                    <table class="w-full text-left text-xs sm:text-sm">
                                        <thead class="bg-slate-100 text-slate-800 font-bold border-b border-slate-200">
                                            <tr>
                                                <th class="py-2.5 px-4">Jumlah Anggota Cabang</th>
                                                <th class="py-2.5 px-4">Jumlah Utusan Resmi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 text-slate-700">
                                            <tr>
                                                <td class="py-2 px-4">025 - 100 orang anggota</td>
                                                <td class="py-2 px-4 font-bold text-[#0f3d64]">2 orang utusan</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-4">101 - 200 orang anggota</td>
                                                <td class="py-2 px-4 font-bold text-[#0f3d64]">3 orang utusan</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-4">201 - 300 orang anggota</td>
                                                <td class="py-2 px-4 font-bold text-[#0f3d64]">4 orang utusan</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-4">301 - 500 orang anggota</td>
                                                <td class="py-2 px-4 font-bold text-[#0f3d64]">5 orang utusan</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-4">501 - 700 orang anggota</td>
                                                <td class="py-2 px-4 font-bold text-[#0f3d64]">6 orang utusan</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-4">701 - 950 orang anggota</td>
                                                <td class="py-2 px-4 font-bold text-[#0f3d64]">7 orang utusan</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-4">951 - 1250 orang anggota</td>
                                                <td class="py-2 px-4 font-bold text-[#0f3d64]">8 orang utusan</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-4">1251 - 1750 orang anggota</td>
                                                <td class="py-2 px-4 font-bold text-[#0f3d64]">9 orang utusan</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-4">1751 ke atas orang anggota</td>
                                                <td class="py-2 px-4 font-bold text-[#0f3d64]">10 orang utusan</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <p><strong>4.</strong> Kongres dipimpin oleh Majelis Ketua yang terdiri dari utusan-utusan
                                dan unsur Pengurus Pusat yang dipilih oleh Kongres.</p>
                            <p><strong>5. Kongres Bertugas:</strong>
                            <ul class="list-disc list-inside space-y-1 text-slate-700 pl-2">
                                <li>Menetapkan AD/ART</li>
                                <li>Menilai laporan umum Pengurus Pusat</li>
                                <li>Menetapkan garis besar program dan garis besar orgnaisasi, kebijaksanaan umum dan
                                    anggaran pendapatan dan belanja organisasi</li>
                                <li>Memilih Pengurus Pusat</li>
                            </ul>
                            </p>
                        </div>
                    </div>

                    <!-- Pasal 4 ART -->
                    <div id="art-pasal-4" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 4</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Pengurus Pusat (PP)</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-4')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong class="text-slate-900 font-bold">1.</strong> Pengurus Pusat sekurang-kurangnya
                                terdiri dari <strong>5 (lima) orang</strong>, yaitu <strong>Ketua Umum</strong>,
                                <strong>Sekretaris Umum</strong>, <strong>Bendahara Umum</strong>, dan <strong>2 (dua)
                                    orang anggota</strong>.
                            </p>

                            <p><strong class="text-slate-900 font-bold">2.</strong> Anggota Pengurus Pusat adalah
                                <strong>warga negara Indonesia</strong> dan <strong>beragama Kristen</strong>.
                            </p>

                            <p><strong class="text-slate-900 font-bold">3.</strong>
                            <div
                                class="rounded-2xl border border-amber-200/80 bg-gradient-to-br from-amber-50/50 via-white to-amber-50/20 p-4 sm:p-5 shadow-sm">
                                <ul class="space-y-2.5">
                                    <li
                                        class="flex items-start gap-3 bg-white/90 p-3 rounded-xl border border-amber-200/60 shadow-xs hover:border-amber-300 transition-colors">
                                        <span
                                            class="flex-shrink-0 w-6 h-6 rounded-md bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">a</span>
                                        <span class="text-slate-700 leading-relaxed">Pengurus Pusat dipilih oleh Kongres
                                            dengan <strong>sistem pemilihan langsung</strong> dan/atau <strong>pemilihan
                                                formatur</strong>.</span>
                                    </li>
                                    <li
                                        class="flex items-start gap-3 bg-white/90 p-3 rounded-xl border border-amber-200/60 shadow-xs hover:border-amber-300 transition-colors">
                                        <span
                                            class="flex-shrink-0 w-6 h-6 rounded-md bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">b</span>
                                        <span class="text-slate-700 leading-relaxed">Susunan Pengurus Pusat yang
                                            dibentuk oleh formatur harus sudah dikirimkan kepada Cabang-Cabang
                                            selambat-lambatnya <strong>dua bulan sesudah Kongres</strong>.</span>
                                    </li>
                                    <li
                                        class="flex items-start gap-3 bg-white/90 p-3 rounded-xl border border-amber-200/60 shadow-xs hover:border-amber-300 transition-colors">
                                        <span
                                            class="flex-shrink-0 w-6 h-6 rounded-md bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">c</span>
                                        <span class="text-slate-700 leading-relaxed">Selama Pengurus Pusat yang baru
                                            belum terbentuk, maka Pengurus Pusat yang lama <strong>tetap bertanggung
                                                jawab</strong>.</span>
                                    </li>
                                </ul>
                            </div>
                            </p>

                            <p><strong class="text-slate-900 font-bold">4.</strong>
                            <div
                                class="rounded-2xl border border-amber-200/80 bg-gradient-to-br from-amber-50/50 via-white to-amber-50/20 p-4 sm:p-5 shadow-sm">
                                <ul class="space-y-2.5">
                                    <li
                                        class="flex items-start gap-3 bg-white/90 p-3 rounded-xl border border-amber-200/60 shadow-xs hover:border-amber-300 transition-colors">
                                        <span
                                            class="flex-shrink-0 w-6 h-6 rounded-md bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">a</span>
                                        <span class="text-slate-700 leading-relaxed">Pengurus Pusat bertanggung jawab
                                            kepada <strong>Kongres</strong>.</span>
                                    </li>
                                    <li
                                        class="flex items-start gap-3 bg-white/90 p-3 rounded-xl border border-amber-200/60 shadow-xs hover:border-amber-300 transition-colors">
                                        <span
                                            class="flex-shrink-0 w-6 h-6 rounded-md bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">b</span>
                                        <span class="text-slate-700 leading-relaxed">Pengurus Pusat mempersiapkan
                                            <strong>Kongres</strong>.</span>
                                    </li>
                            </div>
                            </p>

                            <p><strong class="text-slate-900 font-bold">5.</strong> <strong>Ketua Umum</strong> dan
                                <strong>Sekretaris Umum</strong> Pengurus Pusat mewakili organisasi ke dalam dan ke
                                luar.
                            </p>

                            <!-- Poin 6: Pembentukan Badan Pembantu -->
                            <p><strong class="text-slate-900 font-bold">6.</strong>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5 shadow-sm">
                                <ul class="space-y-2.5">
                                    <li
                                        class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-colors">
                                        <span
                                            class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">a</span>
                                        <span class="text-slate-700 leading-relaxed">Pengurus Pusat dapat membentuk
                                            badan pembantu yang berupa komisi, <strong> panitia khusus </strong>bagi
                                            kelancaran pekerjaannya. </span>
                                    </li>
                                    <li
                                        class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-colors">
                                        <span
                                            class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">b</span>
                                        <span class="text-slate-700 leading-relaxed">Pengurus Pusat dapat mengangkat dan
                                            membebaskan <strong>anggota dan staf</strong> yang ditempatkan dalam badan
                                            pembantu tersebut.</span>
                                    </li>
                                </ul>
                            </div>
                            </p>

                            <p><strong class="text-slate-900 font-bold">7.</strong> Pengurus Pusat bersidang
                                sekurang-kurangnya <strong>dua kali dalam satu tahun</strong>.</p>

                            <p><strong class="text-slate-900 font-bold">8.</strong> Pergantian Pengurus Pusat harus
                                disertai dengan <strong>serah-terima yang selengkap-lengkapnya</strong>.</p>
                        </div>
                    </div>

                    <!-- Pasal 5 ART -->
                    <div id="art-pasal-5" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 5</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Konperensi Cabang (Konpercab)
                                </h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-5')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong>1.</strong> Konperensi Cabang dipimpin oleh Majelis Ketua yang terdiri dari
                                anggota-anggota yang dipilih oleh Konperensi Cabang.</p>
                            <p><strong>2. Konperensi Cabang bertugas:</strong></p>
                            <ul class="list-disc list-inside pl-3 space-y-1 text-slate-700">
                                <li>Menilai laporan Badan Pengurus Cabang dalam melaksanakan Keputusan Kongres,
                                    Keputusan Pengurus Pusat, dan Keputusan Konperensi Cabang.</li>
                                <li>Menyusun program kerja, menetapkan struktur, kebijaksanaan dan Anggaran Pendapatan
                                    dan Belanja cabang.</li>
                                <li>Menetapkan masa kerja kepengurusan dan memilih Badan Pengurus Cabang. </li>
                            </ul>
                            <p><strong>3.</strong> Konperensi Cabang bertanggungjawab kepada Pengurus Pusat melalui
                                Badan Pengurus Cabang.</p>
                        </div>
                    </div>

                    <!-- Pasal 6 ART -->
                    <div id="art-pasal-6" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 6</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Badan Pengurus Cabang (BPC)</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-6')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong class="text-slate-900 font-bold">1.</strong> Badan Pengurus Cabang
                                sekurang-kurangnya terdiri dari <strong>3 (tiga) orang</strong> yaitu
                                <strong>Ketua</strong>, <strong>Sekretaris</strong>, dan <strong>Bendahara</strong>.
                            </p>

                            <p><strong class="text-slate-900 font-bold">2.</strong> Anggota Badan Pengurus Cabang adalah
                                <strong>warga negara Indonesia</strong> dan <strong>beragama Kristen</strong>.
                            </p>

                            <!-- Poin 3: Mekanisme Pemilihan & Pengesahan BPC -->
                            <div>
                                <p><strong class="text-slate-900 font-bold">3.</strong></p>
                                <div
                                    class="rounded-2xl border border-amber-200/80 bg-gradient-to-br from-amber-50/50 via-white to-amber-50/20 p-4 sm:p-5 shadow-sm mt-2">
                                    <ul class="space-y-2.5">
                                        <li
                                            class="flex items-start gap-3 bg-white/90 p-3 rounded-xl border border-amber-200/60 shadow-xs hover:border-amber-300 transition-colors">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">a</span>
                                            <span class="text-slate-700 leading-relaxed">Badan Pengurus Cabang dipilih
                                                oleh Konperensi Cabang dengan <strong>sistem pemilihan langsung</strong>
                                                dan/atau <strong>formatur</strong>.</span>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white/90 p-3 rounded-xl border border-amber-200/60 shadow-xs hover:border-amber-300 transition-colors">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">b</span>
                                            <span class="text-slate-700 leading-relaxed">Susunan Badan Pengurus Cabang
                                                yang telah terbentuk dilantik dan disahkan oleh Pengurus Pusat dan harus
                                                dikirimkan kepada anggota-anggota selambat-lambatnya <strong>dua bulan
                                                    setelah pemilihan berlangsung</strong>.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Poin 4: Pertanggungjawaban & Persiapan Konpercab -->
                            <div>
                                <p><strong class="text-slate-900 font-bold">4.</strong></p>
                                <div
                                    class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5 shadow-sm mt-2">
                                    <ul class="space-y-2.5">
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-colors">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">a</span>
                                            <span class="text-slate-700 leading-relaxed">Badan Pengurus Cabang
                                                <strong>bertanggung jawab</strong> kepada Konperensi Cabang dan Pengurus
                                                Pusat.</span>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-colors">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5 shadow-2xs">b</span>
                                            <span class="text-slate-700 leading-relaxed">Badan Pengurus Cabang
                                                <strong>mempersiapkan Konperensi Cabang</strong>.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p><strong class="text-slate-900 font-bold">5.</strong> Badan Pengurus Cabang bersidang
                                sekurang-kurangnya <strong>1 (satu) kali dalam 2 (dua) bulan</strong>.</p>

                            <p><strong class="text-slate-900 font-bold">6.</strong> Penggantian BPC harus disertai
                                dengan <strong>serah-terima yang selengkap-lengkapnya</strong>.</p>
                        </div>
                    </div>

                    <!-- Pasal 7 ART -->
                    <div id="art-pasal-7" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 7</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Sahnya Persidangan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-7')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                            Persidangan sah untuk mengambil keputusan apabila jumlah yang hadir sekurang-kurangnya
                            <strong>setengah ditambah satu orang (½ n + 1)</strong> dari seluruh anggota persidangan.
                        </p>
                    </div>

                    <!-- Pasal 8 ART -->
                    <div id="art-pasal-8" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 8</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Pembentukan dan Pembubaran
                                    Cabang</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-8')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong>1.</strong> Dilakukan oleh Pengurus Pusat, diberitahukan kepada cabang-cabang dan
                                dilaporkan kepada Kongres.</p>
                            <p><strong>2. Persyaratan Pembentukan Cabang:</strong></p>
                            <ul class="list-disc list-inside pl-3 space-y-1 text-slate-700">
                                <li>Di kota yang terdapat Perguruan Tinggi.</li>
                                <li>Sekurang-kurangnya terdapat kesediaan 25 orang mahasiswa untuk menjadi anggota.</li>
                                <li>Sudah mendapat bimbingan sekurang-kurangnya 6 bulan dari cabang yang berdekatan.
                                </li>
                            </ul>
                            <p><strong>3. Persyaratan Pembubaran Cabang:</strong> Apabila tidak terdapat lagi Perguruan
                                Tinggi, atau jumlah anggota kurang dari 25 orang (atas sepengetahuan dua cabang
                                berdekatan).</p>
                        </div>
                    </div>

                    <!-- Pasal 9 ART -->
                    <div id="art-pasal-9" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 9</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Perbendaharaan & Badan Pemeriksa
                                    Keuangan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-9')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <ol
                            class="list-decimal list-inside space-y-2 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <li>Anggota diwajibkan membayar iuran atau donasi menurut jumlah yang ditetapkan oleh
                                Kongres.</li>
                            <li>Cabang diwajibkan sekurang-kurangnya 1 kali dalam 4 bulan menyerahkan sebagian dari
                                iuran/donasi ke PP.</li>
                            <li>Kongres membentuk Badan Pemeriksa Keuangan (BPK) beranggotakan wakil cabang-cabang untuk
                                memeriksa keuangan Pengurus Pusat secara berkala.</li>
                        </ol>
                    </div>

                    <!-- Pasal 10 ART: LAMBANG DAN MARS (RICH SHOWCASE LENGKAP SESUAI KONSTITUSI) -->
                    <div id="art-pasal-10"
                        class="ad-card p-6 sm:p-10 border-2 border-[#c59b27] bg-gradient-to-br from-white via-[#fdfbf7] to-amber-50/30"
                        data-searchable>

                        <!-- Classical Latin Ecumenical Motto Ribbon -->
                        <div class="flex items-center justify-center gap-2 sm:gap-3 py-1.5 px-4 mb-6 rounded-lg bg-amber-950/5 border border-amber-300/50 text-amber-900 text-center shadow-2xs">
                            <span class="text-amber-700 text-xs sm:text-sm">✝</span>
                            <span class="font-classical text-[10px] sm:text-xs font-bold tracking-[0.22em] uppercase">Insignia Oecumenica • Ut Omnes Unum Sint</span>
                            <span class="text-amber-700 text-xs sm:text-sm">✝</span>
                        </div>

                        <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-[#dfcfb0]">
                            <div class="flex items-center gap-3.5">
                                <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-gradient-to-br from-[#78350f] via-[#b45309] to-[#d97706] text-amber-200 font-classical font-extrabold text-xl shadow-sm border border-amber-400/40">🛡️</span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-classical text-xs font-extrabold uppercase tracking-[0.16em] text-amber-800">Simbolisme, Heraldik &amp; Musik Liturgis Gerakan</span>
                                    </div>
                                    <h3 class="font-classical text-xl sm:text-2xl md:text-3xl font-extrabold text-[#091b2e] tracking-tight mt-0.5">ART PASAL 10: Lambang dan Mars</h3>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-10')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>

                        <div class="space-y-6 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <!-- Poin 1 & 2: Landasan Pokok Lambang & Mars -->
                            <div class="p-5 rounded-2xl bg-white border border-amber-200/80 shadow-sm space-y-3">
                                <p><strong class="text-slate-900 font-bold">1.</strong> Organisasi ini mempunyai
                                    <strong>lambang dan mars</strong>.
                                </p>
                                <div>
                                    <p><strong class="text-slate-900 font-bold">2.</strong> Lambang organisasi terdiri
                                        dari :</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 mt-2">
                                        <div
                                            class="flex items-center gap-2 p-3 bg-amber-50/70 border border-amber-200/70 rounded-xl text-slate-800 font-semibold text-xs sm:text-sm">
                                            <span
                                                class="w-6 h-6 rounded-md bg-amber-600 text-white font-bold text-xs inline-flex items-center justify-center">a</span>
                                            <span>Bendera</span>
                                        </div>
                                        <div
                                            class="flex items-center gap-2 p-3 bg-amber-50/70 border border-amber-200/70 rounded-xl text-slate-800 font-semibold text-xs sm:text-sm">
                                            <span
                                                class="w-6 h-6 rounded-md bg-amber-600 text-white font-bold text-xs inline-flex items-center justify-center">b</span>
                                            <span>Panji</span>
                                        </div>
                                        <div
                                            class="flex items-center gap-2 p-3 bg-amber-50/70 border border-amber-200/70 rounded-xl text-slate-800 font-semibold text-xs sm:text-sm">
                                            <span
                                                class="w-6 h-6 rounded-md bg-amber-600 text-white font-bold text-xs inline-flex items-center justify-center">c</span>
                                            <span>Topi</span>
                                        </div>
                                        <div
                                            class="flex items-center gap-2 p-3 bg-amber-50/70 border border-amber-200/70 rounded-xl text-slate-800 font-semibold text-xs sm:text-sm">
                                            <span
                                                class="w-6 h-6 rounded-md bg-amber-600 text-white font-bold text-xs inline-flex items-center justify-center">d</span>
                                            <span>Lencana</span>
                                        </div>
                                        <div
                                            class="flex items-center gap-2 p-3 bg-amber-50/70 border border-amber-200/70 rounded-xl text-slate-800 font-semibold text-xs sm:text-sm col-span-2 sm:col-span-1">
                                            <span
                                                class="w-6 h-6 rounded-md bg-amber-600 text-white font-bold text-xs inline-flex items-center justify-center">e</span>
                                            <span>Pita Kepengurusan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Poin 3: Bendera Organisasi -->
                            <div class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-4">
                                <div
                                    class="flex items-center justify-between flex-wrap gap-2 border-b border-slate-100 pb-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-2xl">🚩</span>
                                        <h4 class="text-base sm:text-lg font-bold text-slate-900">3. Bendera Organisasi
                                        </h4>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-900 font-bold text-xs">Biru
                                        Laut • Rasio 3 : 2</span>
                                </div>

                                <!-- Visual Preview Bendera -->
                                <div class="w-full h-12 rounded-xl flex items-center justify-center text-xs font-bold text-white shadow-inner gap-2"
                                    style="background-color: #0f3d64;">
                                    <span>Kain Berwarna Biru Laut</span>
                                    <span class="opacity-75">•</span>
                                    <span>Rasio 3 berbanding 2</span>
                                </div>

                                <div
                                    class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5 shadow-sm space-y-3">
                                    <ul class="space-y-3">
                                        <!-- a -->
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">a</span>
                                            <span class="text-slate-700 leading-relaxed">Dibuat dari kain berwarna
                                                <strong>biru laut</strong>.</span>
                                        </li>

                                        <!-- b -->
                                        <li
                                            class="space-y-2 bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-xs">
                                            <div class="flex items-start gap-3">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">b</span>
                                                <div class="w-full space-y-2">
                                                    <div class="flex items-start gap-2.5 text-xs sm:text-sm">
                                                        <span
                                                            class="px-2 py-0.5 rounded bg-blue-50 text-blue-800 font-bold text-xs">(1)</span>
                                                        <span>Berbentuk <strong>empat persegi panjang</strong> dengan
                                                            perbandingan <strong>tiga berbanding dua (3 :
                                                                2)</strong>.</span>
                                                    </div>
                                                    <div class="flex items-start gap-2.5 text-xs sm:text-sm">
                                                        <span
                                                            class="px-2 py-0.5 rounded bg-blue-50 text-blue-800 font-bold text-xs">(2)</span>
                                                        <span>Ditengah-tengah terdapat <strong>gambar GMKI berwarna
                                                                putih</strong> yang terlihat jelas pada kedua sisinya
                                                            <em>(dengan tulisan terbalik pada salah satu
                                                                sisi)</em>.</span>
                                                    </div>
                                                    <div class="flex items-start gap-2.5 text-xs sm:text-sm">
                                                        <span
                                                            class="px-2 py-0.5 rounded bg-blue-50 text-blue-800 font-bold text-xs">(3)</span>
                                                        <span>Perbandingan tinggi lambang dan lebar bendera adalah
                                                            <strong>satu banding dua (1 : 2)</strong>.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <!-- c -->
                                        <li
                                            class="space-y-2.5 bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-xs">
                                            <div class="flex items-start gap-3">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">c</span>
                                                <div class="w-full space-y-2">
                                                    <p class="leading-relaxed">Dipergunakan dalam upacara resmi baik
                                                        yang bersifat umum, maupun yang bersifat khusus organisasi
                                                        bersama-sama dengan bendera <strong>Merah Putih</strong>.</p>
                                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1">
                                                        <div
                                                            class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs space-y-1">
                                                            <div class="font-bold text-slate-900">(1) Tingkat
                                                                Nasional/Daerah</div>
                                                            <div class="text-slate-600">Bendera umum organisasi (bendera
                                                                GMKI) berukuran <strong class="text-blue-900">270 x 180
                                                                    cm</strong>.</div>
                                                        </div>
                                                        <div
                                                            class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs space-y-1">
                                                            <div class="font-bold text-slate-900">(2) Tingkat Lokal
                                                                (Cabang)</div>
                                                            <div class="text-slate-600">Bendera cabang berukuran <strong
                                                                    class="text-blue-900">135 x 90 cm</strong>.</div>
                                                        </div>
                                                        <div
                                                            class="p-2.5 bg-amber-50/70 border border-amber-200 rounded-lg text-xs space-y-1">
                                                            <div class="font-bold text-amber-900">(3) Bendera Merah
                                                                Putih</div>
                                                            <div class="text-slate-700">Yang dipergunakan bersama-sama
                                                                bendera organisasi harus mempunyai <strong
                                                                    class="text-amber-950">ukuran yang sama</strong>.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Poin 4: Panji Organisasi -->
                            <div class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-4">
                                <div
                                    class="flex items-center justify-between flex-wrap gap-2 border-b border-slate-100 pb-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-2xl">🎏</span>
                                        <h4 class="text-base sm:text-lg font-bold text-slate-900">4. Panji Organisasi
                                        </h4>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-800 font-bold text-xs">Lebar
                                        50 cm • Tinggi 80 cm / 60 cm</span>
                                </div>

                                <!-- Visual Pembagian Warna Panji -->
                                <div>
                                    <div
                                        class="flex h-11 rounded-xl overflow-hidden border border-slate-300 text-xs font-bold text-center shadow-xs">
                                        <div class="w-[30%] bg-slate-400 text-white flex items-center justify-center">
                                            Abu-abu (15 cm)</div>
                                        <div class="w-[40%] bg-[#0a192f] text-white flex items-center justify-center">
                                            Biru Tua (20 cm)</div>
                                        <div class="w-[30%] bg-slate-400 text-white flex items-center justify-center">
                                            Abu-abu (15 cm)</div>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1 text-center">Lebar Total 50 cm •
                                        Rumbai-rumbai bawah & tali pinggir berwarna putih</p>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5 shadow-sm">
                                    <ul class="space-y-2.5">
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">a</span>
                                            <span class="text-slate-700 leading-relaxed">Dibuat dari kain dengan warna
                                                dasar <strong>abu-abu</strong> dan <strong>biru tua
                                                    kehitam-hitaman</strong>.</span>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">b</span>
                                            <span class="text-slate-700 leading-relaxed">Tali pinggir (tepi) panji
                                                dibuat dari kain berwarna <strong>putih</strong>.</span>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">c</span>
                                            <span class="text-slate-700 leading-relaxed">Rumbai-rumbai bawah berwarna
                                                <strong>putih</strong>.</span>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">d</span>
                                            <span class="text-slate-700 leading-relaxed">Lebar panji <strong>50
                                                    cm</strong> dengan perincian: <strong>15 cm abu-abu</strong>,
                                                <strong>20 cm biru tua</strong>, dan <strong>15 cm
                                                    abu-abu</strong>.</span>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">e</span>
                                            <span class="text-slate-700 leading-relaxed">Tinggi panji dari puncak sampai
                                                keujung sudut di tengah <strong>80 cm</strong>, tinggi kedua sisi (tepi)
                                                <strong>60 cm</strong>.</span>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">f</span>
                                            <span class="text-slate-700 leading-relaxed">Tanda salib dan tulisan dibuat
                                                dengan <strong>warna putih</strong>.</span>
                                        </li>
                                        <li
                                            class="space-y-2 bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-xs">
                                            <div class="flex items-start gap-3">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">g</span>
                                                <div class="w-full space-y-2">
                                                    <span class="font-semibold text-slate-800">Ketentuan Penulisan pada
                                                        Panji:</span>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                                        <div
                                                            class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs space-y-1">
                                                            <div class="font-bold text-slate-900">(1) Panji Umum</div>
                                                            <div class="text-slate-600">Bertuliskan huruf
                                                                <strong>GMKI</strong> berwarna putih di <strong>bawah
                                                                    tanda salib</strong>.
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs space-y-1">
                                                            <div class="font-bold text-slate-900">(2) Panji Cabang</div>
                                                            <div class="text-slate-600">Bertuliskan huruf <strong>GMKI
                                                                    di atas salib</strong> dan <strong>nama cabang di
                                                                    bawah tanda salib</strong>.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Poin 5 & 6: Topi & Lencana Organisasi (2 Kolom) -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                                <!-- Poin 5: Topi Organisasi (Baret) -->
                                <div
                                    class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-4">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-2xl">🎖️</span>
                                            <h4 class="text-base sm:text-lg font-bold text-slate-900">5. Topi Organisasi
                                                (Baret)</h4>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 shadow-sm">
                                        <ul class="space-y-2.5">
                                            <li
                                                class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">a</span>
                                                <span
                                                    class="text-slate-700 leading-relaxed text-xs sm:text-sm">Berbentuk
                                                    <strong>bundar (baret)</strong> dengan warna dasar <strong>biru tua
                                                        kehitam-hitaman</strong>.</span>
                                            </li>
                                            <li
                                                class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">b</span>
                                                <span
                                                    class="text-slate-700 leading-relaxed text-xs sm:text-sm">Memanjang
                                                    dari muka ke belakang, ditengah-tengah topi diletakkan kain warna
                                                    <strong>abu-abu</strong> dengan lebar bagian muka <strong>8
                                                        cm</strong> dan lebar bagian belakang <strong>6
                                                        cm</strong>.</span>
                                            </li>
                                            <li
                                                class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">c</span>
                                                <span class="text-slate-700 leading-relaxed text-xs sm:text-sm">Pada
                                                    topi organisasi hanya dapat dikenakan <strong>lencana
                                                        organisasi</strong> yang berbentuk lambang GMKI yang berwarna
                                                    <strong>putih logam, biru tua dan abu-abu</strong>, berukuran
                                                    (tinggi) <strong>4 cm</strong>, pada bagian muka yang berwarna
                                                    abu-abu.</span>
                                            </li>
                                            <li
                                                class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">d</span>
                                                <span
                                                    class="text-slate-700 leading-relaxed text-xs sm:text-sm">Dipergunakan
                                                    dalam <strong>setiap kegiatan organisasi</strong> baik yang bersifat
                                                    umum, maupun yang bersifat khusus organisasi.</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Poin 6: Lencana Organisasi -->
                                <div
                                    class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-4">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-2xl">🛡️</span>
                                            <h4 class="text-base sm:text-lg font-bold text-slate-900">6. Lencana
                                                Organisasi</h4>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 shadow-sm">
                                        <ul class="space-y-2.5">
                                            <li
                                                class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">a</span>
                                                <span
                                                    class="text-slate-700 leading-relaxed text-xs sm:text-sm">Berbentuk
                                                    <strong>perisai (segi lima)</strong> dan dibuat dari
                                                    <strong>logam</strong>.</span>
                                            </li>
                                            <li
                                                class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">b</span>
                                                <span
                                                    class="text-slate-700 leading-relaxed text-xs sm:text-sm">Ditengah-tengah
                                                    terletak <strong>tanda salib berwarna putih logam</strong> diatas
                                                    dasar cat <strong>biru tua</strong>.</span>
                                            </li>
                                            <li
                                                class="space-y-1.5 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                                <div class="flex items-start gap-3">
                                                    <span
                                                        class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">c</span>
                                                    <div class="space-y-1 text-xs sm:text-sm">
                                                        <span class="font-semibold text-slate-800">Tepinya berwarna
                                                            abu-abu dengan :</span>
                                                        <p class="text-slate-600 pl-2">• <strong>(1)</strong> Tulisan
                                                            <strong>GMKI</strong> pada bagian atasnya ;
                                                        </p>
                                                        <p class="text-slate-600 pl-2">• <strong>(2)</strong>
                                                            <strong>Tiga buah garis-garis vertikal</strong> pada setiap
                                                            sayap, dikanan dan kiri, dan garis yang terletak ditengah
                                                            adalah yang terpanjang ;
                                                        </p>
                                                        <p class="text-slate-600 pl-2">• <strong>(3)</strong> Tulisan
                                                            <em>"Ut Omnes Unum Sint"</em> melingkar dari kiri ke kanan,
                                                            yang masing-masing berwarna <strong>putih logam</strong>.
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="space-y-2 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                                <div class="flex items-start gap-3">
                                                    <span
                                                        class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">d</span>
                                                    <div class="w-full space-y-1.5 text-xs sm:text-sm">
                                                        <span class="font-semibold text-slate-800">Terdiri dari tiga
                                                            jenis, yaitu :</span>
                                                        <div
                                                            class="grid grid-cols-3 gap-1.5 text-center text-[11px] font-bold pt-1">
                                                            <div
                                                                class="p-2 bg-blue-50 text-blue-900 rounded-lg border border-blue-200/70">
                                                                <div>(1) Dada</div>
                                                                <div class="text-sm font-extrabold mt-0.5">2,5 cm</div>
                                                            </div>
                                                            <div
                                                                class="p-2 bg-blue-50 text-blue-900 rounded-lg border border-blue-200/70">
                                                                <div>(2) Topi</div>
                                                                <div class="text-sm font-extrabold mt-0.5">4 cm</div>
                                                            </div>
                                                            <div
                                                                class="p-2 bg-blue-50 text-blue-900 rounded-lg border border-blue-200/70">
                                                                <div>(3) Kordon</div>
                                                                <div class="text-sm font-extrabold mt-0.5">8 cm</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="space-y-1.5 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                                <div class="flex items-start gap-3">
                                                    <span
                                                        class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">e</span>
                                                    <div class="space-y-1 text-xs sm:text-sm">
                                                        <span class="font-semibold text-slate-800">Dipergunakan dengan
                                                            ketentuan sebagai berikut :</span>
                                                        <p class="text-slate-600 pl-2">• <strong>(1)</strong> Lencana
                                                            dada dikenakan pada <strong>dada sebelah kiri</strong>.</p>
                                                        <p class="text-slate-600 pl-2">• <strong>(2)</strong> Lencana
                                                            Topi dikenakan pada <strong>baret (topi)</strong>.</p>
                                                        <p class="text-slate-600 pl-2">• <strong>(3)</strong> Lencana
                                                            pita kepengurusan (Kordon) dikenakan pada <strong>pita
                                                                kepengurusan</strong>.</p>
                                                        <p class="text-rose-700 font-semibold pl-2">•
                                                            <strong>(4)</strong> Penggunaan diluar ketentuan ini
                                                            <strong>tidak diperkenankan</strong>.
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>

                            <!-- Poin 7: Pita Kepengurusan (Kordon) Organisasi -->
                            <div class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-4">
                                <div
                                    class="flex items-center justify-between flex-wrap gap-2 border-b border-slate-100 pb-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-2xl">🎗️</span>
                                        <h4 class="text-base sm:text-lg font-bold text-slate-900">7. Pita Kepengurusan
                                            (Kordon) Organisasi</h4>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-800 font-bold text-xs">Panjang
                                        120 cm • Pengurus Pusat & BPC</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Spek Pengurus Pusat -->
                                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-slate-900 text-sm">Pita Kordon Pengurus
                                                Pusat</span>
                                            <span
                                                class="px-2 py-0.5 rounded bg-blue-100 text-blue-900 font-bold text-xs">Lebar
                                                7 cm</span>
                                        </div>
                                        <div
                                            class="flex h-8 rounded-lg overflow-hidden border border-slate-300 text-[11px] font-bold text-center">
                                            <div class="w-1/2 bg-[#0a192f] text-white flex items-center justify-center">
                                                Biru Tua (3,5 cm)</div>
                                            <div class="w-1/2 bg-slate-400 text-white flex items-center justify-center">
                                                Abu-abu (3,5 cm)</div>
                                        </div>
                                        <p class="text-xs text-slate-600">Bagi Pengurus Pusat, warna biru tua terletak
                                            di sebelah dalam.</p>
                                    </div>

                                    <!-- Spek BPC -->
                                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-slate-900 text-sm">Pita Kordon BPC
                                                (Cabang)</span>
                                            <span
                                                class="px-2 py-0.5 rounded bg-amber-100 text-amber-900 font-bold text-xs">Lebar
                                                4,5 cm</span>
                                        </div>
                                        <div
                                            class="flex h-8 rounded-lg overflow-hidden border border-slate-300 text-[11px] font-bold text-center">
                                            <div
                                                class="w-[33.3%] bg-slate-400 text-white flex items-center justify-center">
                                                Abu (1,5cm)</div>
                                            <div
                                                class="w-[33.4%] bg-[#0a192f] text-white flex items-center justify-center">
                                                Biru Tua (1,5cm)</div>
                                            <div
                                                class="w-[33.3%] bg-slate-400 text-white flex items-center justify-center">
                                                Abu (1,5cm)</div>
                                        </div>
                                        <p class="text-xs text-slate-600">Perincian: 1,5 cm abu-abu, 1,5 cm biru tua,
                                            dan 1,5 cm abu-abu.</p>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5 shadow-sm">
                                    <ul class="space-y-2.5">
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">a</span>
                                            <span class="text-slate-700 leading-relaxed text-xs sm:text-sm">Dibuat dari
                                                kain berwarna <strong>biru tua dan abu-abu</strong>.</span>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">b</span>
                                            <span class="text-slate-700 leading-relaxed text-xs sm:text-sm">Lebar pita
                                                (kordon) untuk <strong>Pengurus Pusat: 7 cm</strong>, dengan perincian;
                                                <strong>3,5 cm biru tua</strong> dan <strong>3,5 cm
                                                    abu-abu</strong>.</span>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">c</span>
                                            <span class="text-slate-700 leading-relaxed text-xs sm:text-sm">Lebar pita
                                                kepengurusan (kordon) untuk <strong>Badan Pengurus Cabang: 4,5
                                                    cm</strong> dengan perincian <strong>1,5 cm abu-abu</strong>,
                                                <strong>1,5 cm biru tua</strong>, dan <strong>1,5 cm
                                                    abu-abu</strong>.</span>
                                        </li>
                                        <li
                                            class="space-y-1.5 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <div class="flex items-start gap-3">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-slate-100 text-slate-800 font-bold text-xs inline-flex items-center justify-center mt-0.5">d</span>
                                                <div class="space-y-1.5 text-xs sm:text-sm">
                                                    <p class="text-slate-700 leading-relaxed"><strong>(1)</strong>
                                                        Dipergunakan <strong>melingkari leher</strong> dan pada kedua
                                                        ujungnya diletakkan <strong>lencana pita kepengurusan
                                                            (Kordon)</strong>, berukuran <strong>8 cm</strong> pada
                                                        bagian muka.</p>
                                                    <p class="text-slate-700 leading-relaxed"><strong>(2)</strong> Bagi
                                                        Pengurus Pusat warna <strong>biru tua terletak disebelah
                                                            dalam</strong>.</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li
                                            class="flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-md bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center justify-center mt-0.5">📏</span>
                                            <span class="text-slate-700 leading-relaxed text-xs sm:text-sm"><strong>Panjang
                                                    Pita (Kordon):</strong> <strong>120 cm</strong>.</span>
                                        </li>
                                        <li
                                            class="space-y-1.5 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                                            <div class="flex items-start gap-3">
                                                <span
                                                    class="flex-shrink-0 w-6 h-6 rounded-md bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center justify-center mt-0.5">🏛️</span>
                                                <div class="space-y-1 text-xs sm:text-sm">
                                                    <span class="font-semibold text-slate-800">Dipergunakan Pengurus
                                                        Pusat dan Badan Pengurus Cabang dalam :</span>
                                                    <p class="text-slate-600 pl-2">• <strong>(1)</strong> Upacara resmi
                                                        organisasi atau lembaga lain selaku wakil organisasi.</p>
                                                    <p class="text-slate-600 pl-2">• <strong>(2)</strong> Upacara resmi
                                                        organisasi tingkat lokal (cabang), daerah (regional) maupun
                                                        nasional.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Poin 8 & 9: Mars dan Hymne GMKI (2 Kolom) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Poin 8: Mars GMKI -->
                                <div class="p-5 rounded-2xl bg-white border border-amber-200 shadow-sm space-y-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-2xl">🎵</span>
                                        <h4 class="text-base sm:text-lg font-bold text-slate-900">8. Mars GMKI</h4>
                                    </div>
                                    <p class="text-sm text-slate-700 leading-relaxed">
                                        Mars GMKI adalah lagu <strong>"MARS GMKI"</strong> yang disahkan dalam
                                        <strong>Kongres X GMKI tahun 1965 di Manado</strong>.
                                    </p>
                                    <div
                                        class="p-3 bg-amber-50/70 border border-amber-200/70 rounded-xl text-xs text-amber-900 flex items-center gap-2">
                                        <span class="text-base">ℹ️</span>
                                        <span>Pada waktu menyanyikan Mars GMKI, seluruh hadirin diwajibkan berdiri dalam
                                            sikap sempurna.</span>
                                    </div>
                                </div>

                                <!-- Poin 9: Hymne GMKI -->
                                <div class="p-5 rounded-2xl bg-white border border-blue-200 shadow-sm space-y-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-2xl">🎼</span>
                                        <h4 class="text-base sm:text-lg font-bold text-slate-900">9. Hymne GMKI</h4>
                                    </div>
                                    <p class="text-sm text-slate-700 leading-relaxed">
                                        Hymne GMKI adalah lagu <strong>"HYMNE GMKI"</strong> yang disahkan dalam
                                        <strong>Kongres XXX GMKI tahun 2006 di Kupang</strong>.
                                    </p>
                                    <div
                                        class="p-3 bg-blue-50/70 border border-blue-200/70 rounded-xl text-xs text-blue-900 flex items-center gap-2">
                                        <span class="text-base">ℹ️</span>
                                        <span>Lagu khidmat organisasi yang dinyanyikan dalam upacara dan ibadah resmi
                                            GMKI.</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Logo GMKI yang Sah (Photo 5) -->
                            <div
                                class="p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-amber-50/60 via-white to-blue-50/40 border border-amber-300 shadow-sm space-y-5">
                                <div class="text-center space-y-1">
                                    <span
                                        class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 font-extrabold text-xs tracking-wider uppercase">Dokumen
                                        Konstitusi Resmi</span>
                                    <h4 class="text-lg sm:text-xl font-extrabold text-slate-900">Logo GMKI yang Sah</h4>
                                    <p class="text-xs sm:text-sm text-slate-600 italic">"Logo GMKI yang sah adalah
                                        sebagaimana dibawah ini."</p>
                                </div>

                                <div
                                    class="flex flex-col sm:flex-row items-center justify-center gap-6 p-4 sm:p-6 bg-white rounded-2xl border border-slate-200/90 shadow-inner">
                                    <div
                                        class="flex-shrink-0 p-3 bg-slate-50 rounded-2xl border border-slate-200 shadow-sm">
                                        <img src="<?= asset('images/logo-gmki.png') ?>" alt="Logo GMKI yang Sah"
                                            class="w-32 h-32 sm:w-40 sm:h-40 object-contain drop-shadow-md">
                                    </div>
                                    <div class="space-y-2.5 text-xs sm:text-sm text-slate-700 max-w-lg">
                                        <div
                                            class="font-bold text-slate-900 text-sm sm:text-base border-b border-slate-100 pb-1">
                                            Anatomi Lambang Resmi GMKI:</div>
                                        <ul class="space-y-1.5 list-disc list-inside text-slate-600">
                                            <li><strong>Bentuk Perisai (Segi Lima):</strong> Terbuat dari logam dengan
                                                dasar cat biru tua dan tepi abu-abu.</li>
                                            <li><strong>Tanda Salib Putih Logam:</strong> Terletak tepat di
                                                tengah-tengah sebagai sentrum kesaksian iman kristiani.</li>
                                            <li><strong>Tiga Garis Vertikal Sayap:</strong> Tiga buah garis vertikal
                                                pada sayap kanan dan kiri, dengan garis tengah terpanjang.</li>
                                            <li><strong>Identitas & Semboyan:</strong> Bertuliskan <strong>GMKI</strong>
                                                pada bagian atas dan semboyan Alkitabiah <em>"Ut Omnes Unum Sint"</em>
                                                (Yohanes 17:21) melingkar dari kiri ke kanan.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Pasal 11 ART: TINGKAT KEPUTUSAN ORGANISASI (HIERARCHY PYRAMID) -->
                    <div id="art-pasal-11" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 11</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Tingkat Keputusan Organisasi
                                </h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-11')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-slate-700 text-sm mb-4">
                            Organisasi ini mempunyai urutan tingkat keputusan dari yang tertinggi sampai terendah
                            sebagai berikut (keputusan yang lebih rendah tunduk kepada yang lebih tinggi):
                        </p>

                        <!-- Hierarchy Ladder Diagram -->
                        <div class="space-y-2 max-w-xl mx-auto">
                            <div
                                class="p-3 rounded-xl bg-[#0a263f] text-white flex items-center justify-between font-bold text-sm sm:text-base shadow-sm">
                                <span>1. Anggaran Dasar (AD)</span>
                                <span
                                    class="text-xs bg-amber-400 text-slate-900 px-2 py-0.5 rounded font-extrabold">TERTINGGI</span>
                            </div>
                            <div
                                class="p-3 rounded-xl bg-[#0f3d64] text-white flex items-center justify-between font-bold text-sm sm:text-base shadow-sm ml-2">
                                <span>2. Anggaran Rumah Tangga (ART)</span>
                                <span class="text-xs text-slate-300">Tingkat 2</span>
                            </div>
                            <div
                                class="p-3 rounded-xl bg-[#1e5482] text-white flex items-center justify-between font-bold text-sm sm:text-base shadow-sm ml-4">
                                <span>3. Keputusan Kongres</span>
                                <span class="text-xs text-slate-300">Tingkat 3</span>
                            </div>
                            <div
                                class="p-3 rounded-xl bg-[#2d6fa5] text-white flex items-center justify-between font-bold text-sm sm:text-base shadow-sm ml-6">
                                <span>4. Keputusan Pengurus Pusat</span>
                                <span class="text-xs text-slate-300">Tingkat 4</span>
                            </div>
                            <div
                                class="p-3 rounded-xl bg-[#438bc4] text-white flex items-center justify-between font-bold text-sm sm:text-base shadow-sm ml-8">
                                <span>5. Keputusan Konperensi Cabang</span>
                                <span class="text-xs text-slate-300">Tingkat 5</span>
                            </div>
                            <div
                                class="p-3 rounded-xl bg-[#61a4dc] text-white flex items-center justify-between font-bold text-sm sm:text-base shadow-sm ml-10">
                                <span>6. Keputusan Badan Pengurus Cabang (BPC)</span>
                                <span class="text-xs text-slate-100">Tingkat 6</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 12 ART: PENUTUP -->
                    <div id="art-pasal-12" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8dfcf]">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-classical font-extrabold shadow-xs">ART
                                    PASAL 12</span>
                                <h3 class="font-classical text-lg sm:text-xl font-bold text-[#0a263f] tracking-wide">P e n u t u p</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-12')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-[#2c241b] text-base leading-relaxed mb-6">
                            Hal-hal yang belum tercantum dalam Anggaran Rumah Tangga ini diatur oleh Keputusan Kongres,
                            Keputusan Pengurus Pusat, Keputusan Konperensi Cabang, dan Keputusan Badan Pengurus Cabang.
                        </p>

                        <!-- Classical Ecclesiastical Ratification Charter (Acta Ratificationis) -->
                        <div class="mt-6 p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-[#faf6ee] via-white to-[#f5ecdc] border-2 border-[#c59b27] shadow-md relative overflow-hidden text-center space-y-4">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#0a263f] text-amber-300 border border-amber-400/30 text-xs font-classical font-bold tracking-widest uppercase">
                                <span>✝</span>
                                <span>Acta Ratificationis • Soli Deo Gloria</span>
                                <span>✝</span>
                            </div>

                            <h4 class="font-classical text-lg sm:text-xl font-black text-[#0a263f] tracking-wide">
                                Piagam Pengesahan Konstitusi GMKI
                            </h4>

                            <p class="font-sacred text-base sm:text-lg text-[#2c241b] leading-relaxed max-w-2xl mx-auto italic">
                                "Anggaran Dasar dan Anggaran Rumah Tangga GMKI ini ditetapkan oleh <strong>Kongres Nasional XXIX
                                GMKI</strong> pada tanggal <strong>14 Desember 2004</strong>, di Pematang Siantar, Sumatera Utara."
                            </p>

                            <div class="classic-cross-divider max-w-xs mx-auto">
                                <span class="line"></span>
                                <span class="cross">☩</span>
                                <span class="line"></span>
                            </div>

                            <div class="flex items-center justify-center gap-6 sm:gap-10 text-xs text-slate-700 pt-1 font-classical font-semibold">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-amber-700">📜</span>
                                    <span>12 Pasal AD</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-amber-700">⚖️</span>
                                    <span>12 Pasal ART</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-amber-700">🏛️</span>
                                    <span>Kongres Pematang Siantar</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ================================================================= -->
                <!-- 3. PERATURAN ORGANISASI (PO) -->
                <!-- ================================================================= -->
                <div id="section-po" class="tab-pane hidden space-y-6">

                    <!-- PO Introductory Charter Card (Explaining Function & Relationship of PO) -->
                    <div id="po-intro-charter" class="ad-card parchment-charter p-6 sm:p-10 relative overflow-hidden" data-searchable>
                        <!-- 4 Corner Cross Motifs -->
                        <div class="absolute top-3.5 left-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>
                        <div class="absolute top-3.5 right-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>
                        <div class="absolute bottom-3.5 left-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>
                        <div class="absolute bottom-3.5 right-3.5 text-amber-700/40 text-xs font-classical select-none pointer-events-none">✝</div>

                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-[#e2d5bc]">
                            <div class="flex items-center gap-3.5">
                                <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-gradient-to-br from-[#1e1b4b] to-[#312e81] text-amber-200 font-classical font-extrabold text-xl shadow-sm border border-amber-400/40">📑</span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-classical text-xs font-extrabold uppercase tracking-[0.16em] text-indigo-900">Regula Administrationis</span>
                                        <span class="hidden sm:inline-block w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        <span class="hidden sm:inline-block font-sacred text-xs font-semibold text-slate-600 italic">Pedoman Operasional &amp; Penyeragaman Kerja</span>
                                    </div>
                                    <h2 class="font-classical text-2xl sm:text-3xl font-extrabold text-[#091b2e] tracking-tight mt-0.5">Peraturan Organisasi (PO)</h2>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-intro-charter')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pengantar PO ini">
                                <span>🔗</span>
                            </button>
                        </div>

                        <!-- Explanatory Narrative -->
                        <div class="font-sacred text-slate-800 space-y-4 text-base sm:text-lg leading-relaxed">
                            <p class="dropcap-ecclesiastical">
                                <strong>Peraturan Organisasi (PO)</strong> merupakan ketentuan hukum teknis yang diterbitkan oleh Pengurus Pusat GMKI guna memberikan keseragaman interpretasi (uniformity of interpretation) dan pemerataan tindak kerja aparat organisasi di seluruh Indonesia atas hal-hal yang belum diatur terperinci dalam AD/ART.
                            </p>
                            <p>
                                Melalui Peraturan Organisasi, GMKI menegakkan standardisasi dalam pelaksanaan Masa Perkenalan (Maper), pengelolaan administrasi komisariat, tata upacara resmi, bendera dan lambang organisasi, hingga perwakilan ke luar organisasi demi memelihara ketertiban dan kemurnian visi oikumenis kristiani.
                            </p>
                        </div>

                        <!-- Responsive 4-Pillar Grid of PO Operational Scope -->
                        <div class="mt-6 pt-5 border-t border-[#e2d5bc] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                            <div class="p-3 bg-white/90 rounded-xl border border-[#e2d5bc] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-indigo-900 flex items-center gap-1.5">
                                    <span>🌱</span> <span>Masa Perkenalan (Maper)</span>
                                </div>
                                <p class="text-slate-600 text-[11px] leading-snug">Prosedur baku penerimaan dan penempaan kader baru GMKI.</p>
                            </div>
                            <div class="p-3 bg-white/90 rounded-xl border border-[#e2d5bc] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-indigo-900 flex items-center gap-1.5">
                                    <span>🏛️</span> <span>Komisariat &amp; Rayon</span>
                                </div>
                                <p class="text-slate-600 text-[11px] leading-snug">Tata kerja komisariat di tingkat fakultas dan perguruan tinggi.</p>
                            </div>
                            <div class="p-3 bg-white/90 rounded-xl border border-[#e2d5bc] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-indigo-900 flex items-center gap-1.5">
                                    <span>🎖️</span> <span>Lambang &amp; Tata Upacara</span>
                                </div>
                                <p class="text-slate-600 text-[11px] leading-snug">Protokol resmi bendera, mars, dan upacara organisasi.</p>
                            </div>
                            <div class="p-3 bg-white/90 rounded-xl border border-[#e2d5bc] shadow-2xs space-y-1">
                                <div class="font-classical font-bold text-indigo-900 flex items-center gap-1.5">
                                    <span>🤝</span> <span>Perwakilan Organisasi</span>
                                </div>
                                <p class="text-slate-600 text-[11px] leading-snug">Kewenangan mewakili GMKI di tingkat cabang dan pusat.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 1 PO -->
                    <div id="po-pasal-1" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 1</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Ketentuan Umum</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-1')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <ol
                            class="list-decimal list-inside space-y-2 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <li><strong>Pengertian Peraturan Organisasi:</strong> Peraturan yang mengatur serta mengikat
                                semua anggota dan alat perlengkapan organisasi termasuk mekanisme kerjanya yang belum
                                diatur dalam AD/ART dan Keputusan Kongres.</li>
                            <li><strong>Fungsi Peraturan Organisasi:</strong> Memberikan keseragaman interpretasi
                                terhadap konstitusi organisasi sehingga terwujud pemerataan tindak kerja seluruh aparat
                                organisasi sesuai aturan konstitusi.</li>
                        </ol>
                    </div>

                    <!-- Pasal 2 PO -->
                    <div id="po-pasal-2" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 2</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Keanggotaan & Prosedur
                                    Penerimaan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-2')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <div>
                                <strong class="text-slate-900 block mb-1">1. Anggota Biasa :</strong>
                                <ul class="list-disc list-inside pl-3 space-y-1 text-slate-700">
                                    <li>Diterima oleh BPC melalui <strong>Masa Perkenalan (Maper)</strong>.</li>
                                    <li>Wajib menandatangani formulir kesediaan menerima Visi & Misi serta menjalankan
                                        Usaha Organisasi.</li>
                                    <li>Bila kondisi Cabang belum memungkinkan, Pengurus Pusat dapat mengambil peran
                                        proses penerimaan.</li>
                                    <li>Dapat pindah ke cabang lain dengan menunjukkan Surat Keterangan Pindah dari
                                        Cabang asal.</li>
                                </ul>
                            </div>
                            <div>
                                <strong class="text-slate-900 block mb-1">2. Anggota Luar Biasa :</strong>
                                <p>Bekas Anggota Biasa otomatis menjadi Anggota Luar Biasa. Pendaftar lainnya mengajukan
                                    permohonan tertulis ke BPC.</p>
                            </div>
                            <div>
                                <strong class="text-slate-900 block mb-1">3. Anggota Kehormatan & Penyokong :</strong>
                                <p>WNI tokoh nasional/gerejawi yang berjasa besar diusulkan tertulis oleh BPC ke PP.
                                    Anggota penyokong tidak pernah menjadi anggota biasa dan bantuannya tidak mengikat
                                    organisasi.</p>
                            </div>
                            <div>
                                <strong class="text-slate-900 block mb-1">4. Daftar Anggota :</strong>
                                <p>Wajib diserahkan BPC ke PP sekurang-kurangnya menjelaskan nama anggota, status
                                    kemahasiswaan (asal kampus, jurusan/departemen, fakultas), dan tahun penerimaan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 3 PO -->
                    <div id="po-pasal-3" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 3</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Pengurus Pusat & Tahapan Kongres
                                </h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-3')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-slate-700 text-sm mb-3">Pengurus Pusat bertugas mempersiapkan Kongres dengan
                            tahapan:</p>
                        <ul class="list-disc list-inside space-y-1.5 text-slate-700 text-xs sm:text-sm pl-2">
                            <li>Membentuk dan melantik Panitia Nasional Kongres GMKI.</li>
                            <li>Menyampaikan waktu pelaksanaan & batas daftar anggota selambat-lambatnya <strong>4
                                    bulan</strong> sebelum Kongres.</li>
                            <li>Memanggil Cabang selambat-lambatnya <strong>2 bulan</strong> sebelum Kongres.</li>
                            <li>Mempersiapkan rancangan dokumen & Laporan Umum Pengurus Pusat.</li>
                            <li>Membuka Persidangan Kongres dan memimpin pemilihan Majelis Ketua.</li>
                            <li>Serah Terima PP dilaksanakan selengkap-lengkapnya termasuk inventarisasi kekayaan.</li>
                        </ul>
                    </div>

                    <!-- Pasal 4 PO -->
                    <div id="po-pasal-4" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 4</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Konperensi Cabang & Kuorum</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-4')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong>1.</strong> Konpercab berlangsung sekurang-kurangnya 1 kali dalam 2 tahun.</p>
                            <p><strong>2. Pelaksanaan:</strong> BPC mengundang anggota mendaftar selambat-lambatnya 1
                                bulan sebelum Konpercab. Jumlah peserta sekurang-kurangnya <strong>2/3</strong> dari
                                pendaftar dan hadir <strong>minimal 25 orang</strong>.</p>
                            <p><strong>3. Cabang Berkomisariat:</strong> Sah bila dihadiri sekurang-kurangnya <strong>½
                                    n + 1</strong> jumlah komisariat dan utusan komisariat.</p>
                            <p><strong>4. Perubahan Masa Kerja:</strong> Harus melalui pengkajian objektif dan
                                disepakati <strong>2/3</strong> peserta Konpercab.</p>
                            <p><strong>5. Permintaan Anggota:</strong> Konpercab dapat diselenggarakan atas permintaan
                                anggota apabila BPC menyimpang dari asas/visi/misi atau keputusan organisasi (ditentukan
                                oleh PP).</p>
                        </div>
                    </div>

                    <!-- Pasal 5 PO -->
                    <div id="po-pasal-5" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 5</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Badan Pengurus Cabang,
                                    Pelantikan & Larangan Rangkap Jabatan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-5')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong>1. Pelantikan & Serah Terima:</strong> BPC dilantik oleh Pengurus Pusat (atau
                                mandataris yang ditunjuk). Naskah serah terima ditulis di atas kertas bermeterai
                                ditandatangani unsur demisioner, terpilih, dan saksi.</p>
                            <p><strong>2. Pergantian Antar Waktu (PAW):</strong> Fungsionaris yang meninggal,
                                berhalangan tetap, mengundurkan diri, atau melanggar aturan dapat di-PAW atas
                                persetujuan Pengurus Pusat.</p>
                            <div class="p-3 bg-red-50 rounded-xl border border-red-200 text-red-900 text-xs sm:text-sm">
                                <strong>⚠️ Larangan Rangkap Jabatan:</strong><br>
                                • Seluruh fungsionaris BPC <strong>tidak diperkenankan</strong> rangkap jabatan di dalam
                                organisasi.<br>
                                • Penanggung jawab Cabang (Ketua/Sekretaris) <strong>tidak diperkenankan</strong>
                                rangkap jabatan di luar organisasi.
                            </div>
                            <p><strong>3. CareTaker:</strong> Pengurus Pusat berhak menunjuk Caretaker jika kalender
                                konstitusi berakhir sebelum Konpercab atau BPC menyimpang.</p>
                            <p><strong>4. Pernyataan Sikap:</strong> BPC hanya mengeluarkan sikap dalam ruang lingkup
                                lokal medan layanannya dan wajib dilaporkan ke PP.</p>
                        </div>
                    </div>

                    <!-- Pasal 6 PO -->
                    <div id="po-pasal-6" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 6</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Pembentukan dan Pembubaran
                                    Cabang</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-6')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <ol
                            class="list-decimal list-inside space-y-2 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <li>Mempertimbangkan keberadaan Perguruan Tinggi dan kondisi masyarakat sekitar yang
                                mendukung eksistensi Cabang.</li>
                            <li>Bila mahasiswa di suatu kota sulit mendirikan Cabang mandiri, mereka dapat diterima
                                menjadi anggota Cabang terdekat.</li>
                            <li>Pembentukan dan pembubaran diberitahukan kepada pihak Gereja dan Pemerintah Daerah
                                setempat.</li>
                        </ol>
                    </div>

                    <!-- Pasal 7 PO -->
                    <div id="po-pasal-7" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 7</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Komisariat</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-7')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong>1.</strong> BPC dapat membentuk Komisariat sebagai alat pembinaan dan pelayanan
                                yang membantu BPC memudahkan koordinasi anggota.</p>
                            <p><strong>2.</strong> Pembentukan berdasarkan tempat kuliah dan/atau pengelompokan wilayah
                                serta tempat tinggal.</p>
                            <p><strong>3.</strong> Pengurus Komisariat dilantik dan disahkan oleh BPC.</p>
                            <div
                                class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-xs sm:text-sm">
                                📌 <strong>Batasan Kewenangan:</strong> Pengurus Komisariat <strong>tidak dapat mewakili
                                    organisasi ke luar</strong> dan <strong>tidak diperkenankan menerima anggota secara
                                    mandiri</strong> (tanggung jawab penerimaan tetap pada BPC).
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 8 PO -->
                    <div id="po-pasal-8" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 8</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Tata Penggunaan Lambang & Mars
                                </h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-8')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong>1.</strong> Digunakan dalam upacara resmi bersifat umum (Hari Proklamasi & hari
                                nasional, upacara ekstern) dan khusus (Dies Natalis, Pembukaan/Penutupan Program,
                                Pelantikan/Serah Terima).</p>
                            <p><strong>2. Penempatan Bendera:</strong> Bendera organisasi ditempatkan <strong>di sebelah
                                    kiri</strong> bendera nasional Merah Putih.</p>
                            <p><strong>3. Penempatan Panji:</strong> Panji organisasi ditempatkan di depan mimbar
                                <strong>di antara</strong> bendera GMKI dan bendera nasional.
                            </p>
                            <div
                                class="p-3 bg-blue-50 rounded-xl border border-blue-200 text-blue-900 font-semibold text-xs sm:text-sm">
                                🎵 <strong>Sikap Sempurna:</strong> Pada waktu menyanyikan Mars GMKI, seluruh hadirin
                                <strong>diwajibkan berdiri dalam sikap sempurna</strong>.
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 9 PO: MEKANISME PROTOKOLER UPACARA -->
                    <div id="po-pasal-9"
                        class="ad-card p-6 sm:p-10 border-blue-200 bg-gradient-to-br from-white to-blue-50/20"
                        data-searchable>
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-blue-100">
                            <div class="flex items-center gap-3">
                                <span class="p-2 rounded-lg bg-blue-900 text-white text-xl">📋</span>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-blue-800">Protokoler &
                                        Tata Upacara</span>
                                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">PO PASAL 9: Mekanisme
                                        Protokoler</h3>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-9')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <!-- Urutan Upacara Umum Intern -->
                            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-3">
                                <h4
                                    class="font-bold text-slate-900 flex items-center gap-2 border-b pb-2 text-sm sm:text-base">
                                    <span>🇮🇩</span> 1. Upacara Resmi Umum Intern
                                </h4>
                                <ol class="space-y-2 text-xs sm:text-sm text-slate-700">
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-blue-100 text-blue-900 font-bold flex items-center justify-center text-[11px]">1</span>
                                        Kebaktian</li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-blue-100 text-blue-900 font-bold flex items-center justify-center text-[11px]">2</span>
                                        Lagu Indonesia Raya & Mengheningkan Cipta <em>(berdiri)</em></li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-blue-100 text-blue-900 font-bold flex items-center justify-center text-[11px]">3</span>
                                        Lagu Mars GMKI <em>(berdiri)</em></li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-blue-100 text-blue-900 font-bold flex items-center justify-center text-[11px]">4</span>
                                        Pembacaan Pembukaan AD GMKI <em>(duduk)</em></li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-blue-100 text-blue-900 font-bold flex items-center justify-center text-[11px]">5</span>
                                        Sambutan-sambutan</li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-blue-100 text-blue-900 font-bold flex items-center justify-center text-[11px]">6</span>
                                        Penutup</li>
                                </ol>
                            </div>

                            <!-- Urutan Upacara Khusus -->
                            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-3">
                                <h4
                                    class="font-bold text-slate-900 flex items-center gap-2 border-b pb-2 text-sm sm:text-base">
                                    <span>🏛️</span> 2. Upacara Resmi Khusus Organisasi
                                </h4>
                                <ol class="space-y-2 text-xs sm:text-sm text-slate-700">
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center text-[11px]">1</span>
                                        Kebaktian</li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center text-[11px]">2</span>
                                        Lagu Indonesia Raya & Mengheningkan Cipta <em>(berdiri)</em></li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center text-[11px]">3</span>
                                        Lagu Mars GMKI <em>(berdiri)</em></li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center text-[11px]">4</span>
                                        Pembacaan Pembukaan AD GMKI <em>(duduk)</em></li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center text-[11px]">5</span>
                                        Acara Khusus Organisasi (Pelantikan/Serah Terima)</li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center text-[11px]">6</span>
                                        Pidato Ketua Umum / Ketua Cabang</li>
                                    <li class="flex items-center gap-2"><span
                                            class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center text-[11px]">7</span>
                                        Sambutan-sambutan & Penutup</li>
                                </ol>
                            </div>
                        </div>
                        <div class="mt-4 text-xs text-slate-500 italic">
                            * Upacara resmi organisasi diawali dengan prosesi yang dipimpin oleh PP (tingkat
                            nasional/wilayah) atau BPC (tingkat lokal cabang).
                        </div>
                    </div>

                    <!-- Pasal 10 PO -->
                    <div id="po-pasal-10" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 10</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Hal Mewakili Organisasi</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-10')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <ol
                            class="list-decimal list-inside space-y-2 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <li>Pengurus Pusat mewakili organisasi di tingkat Nasional dan Internasional.</li>
                            <li>Tingkat setaraf propinsi diwakili oleh Koordinator Wilayah dan/atau BPC di bawah
                                koordinasi unsur Pengurus Pusat di wilayah.</li>
                            <li>Bila terdapat lebih dari satu Cabang di propinsi/kabupaten, semua Cabang mempunyai
                                status dan hak yang sama di bawah koordinasi unsur PP di wilayah.</li>
                        </ol>
                    </div>

                    <!-- Pasal 11 PO -->
                    <div id="po-pasal-11" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-blue-800 text-white text-xs font-extrabold">PO
                                    PASAL 11</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">P e n u t u p</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('po-pasal-11')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                            Hal-hal yang belum diatur dalam Peraturan Organisasi ini, akan diatur dalam
                            keputusan-keputusan Pengurus Pusat yang lain, Keputusan Konperensi Cabang dan Keputusan
                            Badan Pengurus Cabang.
                        </p>
                    </div>

                </div>

                <!-- ================================================================= -->
                <!-- 4. PENJELASAN RESMI PERATURAN ORGANISASI -->
                <!-- ================================================================= -->
                <div id="section-penjelasan" class="tab-pane hidden space-y-6">

                    <!-- Bagian I: Penjelasan Umum -->
                    <div id="penjelasan-umum"
                        class="ad-card p-6 sm:p-10 bg-gradient-to-br from-slate-50 via-white to-amber-50/20"
                        data-searchable>
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-200">
                            <span class="p-2.5 rounded-xl bg-slate-900 text-white text-xl">💡</span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Keterangan
                                    Doktrin & Tafsir Yuridis</span>
                                <h3 class="text-2xl font-extrabold text-slate-900">I. Penjelasan Umum Peraturan
                                    Organisasi</h3>
                            </div>
                        </div>

                        <div class="text-slate-700 space-y-4 text-sm sm:text-base leading-relaxed text-justify">
                            <p>
                                Bahwa <strong>Anggaran Dasar / Anggaran Rumah Tangga GMKI</strong> sebagai ketentuan
                                hukum dan tingkat keputusan organisasi tertinggi mendasari seluruh cara kerja anggota
                                maupun alat-alat perlengkapan organisasi dan seluruh tingkat keputusan organisasi dari
                                keputusan kongres, keputusan Pengurus Pusat, keputusan Konperensi Cabang sampai pada
                                keputusan Badan Pengurus Cabang.
                            </p>
                            <p>
                                AD/ART GMKI mengatur hal-hal pokok dan mendasar dalam kehidupan organisasi, baik itu
                                tentang kelembagaan organisasi dan keanggotaan maupun hubungan antara kelembagaan dengan
                                anggota. Namun dalam praktek sering terjadi masalah yang tidak semua pemecahannya dapat
                                diselesaikan hanya berdasarkan AD/ART saja sehingga diperlukan Peraturan Organisasi (PO)
                                untuk memberikan keseragaman interpretasi dan pemerataan tindak kerja.
                            </p>
                            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-sm space-y-2">
                                <strong class="text-slate-900 text-sm block">Landasan Yuridis Penetapan Peraturan
                                    Organisasi :</strong>
                                <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm text-slate-600">
                                    <li>Pasal 11 & Pasal 12 Anggaran Rumah Tangga GMKI</li>
                                    <li>Penjelasan Anggaran Dasar / Anggaran Rumah Tangga GMKI</li>
                                    <li>Keputusan Kongres XXIX Nomor : 009/K-XXIX/GMKI/XII/2004 tentang AD/ART GMKI
                                        (Pematang Siantar)</li>
                                    <li>Keputusan Kongres XXIX Nomor : 011/K-XXIX/GMKI/XII/2004 tentang GBPO dan
                                        Kebijakan Umum 2004-2006</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian II: Penjelasan Pasal demi Pasal -->
                    <div id="penjelasan-pasal" class="ad-card p-6 sm:p-10" data-searchable>
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-200">
                            <span class="p-2.5 rounded-xl bg-[#0f3d64] text-white text-xl">📑</span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#0f3d64]">Rincian
                                    Hermeneutika Hukum</span>
                                <h3 class="text-2xl font-extrabold text-slate-900">II. Penjelasan Pasal Demi Pasal</h3>
                            </div>
                        </div>

                        <div class="space-y-6 text-sm sm:text-base text-slate-700 divide-y divide-slate-100">

                            <div class="pt-4 first:pt-0">
                                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-extrabold">Pasal
                                        1</span>
                                    <span>Ketentuan Umum</span>
                                </h4>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    • Ayat 1: <em>"Anggota"</em> – Juncto AD Pasal 6 dan ART Pasal 2. <em>"Alat
                                        Perlengkapan Organisasi"</em> – Juncto AD Pasal 7. PO ini adalah produk Pengurus
                                    Pusat melalui salah satu keputusannya.<br>
                                    • Ayat 2: <em>"Konstitusi Organisasi"</em> yaitu AD/ART GMKI. <em>"Aparat
                                        Organisasi"</em> yang dimaksud adalah seluruh pengurus (fungsionaris) dan
                                    anggota.
                                </p>
                            </div>

                            <div class="pt-4">
                                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-extrabold">Pasal
                                        2</span>
                                    <span>Keanggotaan</span>
                                </h4>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    • Formulir kesediaan anggota wajib mencantumkan kalimat: <em>"menerima visi dan misi
                                        serta bersedia menjalankan usaha organisasi"</em>.<br>
                                    • Anggota Biasa otomatis menjadi Anggota Luar Biasa saat masa studi/keanggotaan
                                    biasa berakhir.<br>
                                    • Anggota Kehormatan dipilih dari figur yang <strong>tidak pernah menjadi anggota
                                        biasa GMKI</strong> (misal tokoh nasional atau pimpinan gereja/oikumenis) agar
                                    apresiasi objektivitas dan jasa pengabdiannya memiliki kehormatan khusus.
                                </p>
                            </div>

                            <div class="pt-4">
                                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-extrabold">Pasal
                                        3</span>
                                    <span>Pengurus Pusat</span>
                                </h4>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    Cabang penyelenggara Kongres mengajukan komposisi Panitia Nasional Kongres dari
                                    unsur Senior Members/Friends dan Gereja untuk disahkan PP. Waktu panggilan 2 bulan
                                    sebelum kongres berlaku setelah penetapan kuota utusan.
                                </p>
                            </div>

                            <div class="pt-4">
                                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-extrabold">Pasal
                                        4</span>
                                    <span>Konperensi Cabang</span>
                                </h4>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    BPC wajib mengundang seluruh anggota biasa. Pendaftaran yang sah adalah kesediaan
                                    tertulis yang ditandatangani anggota. Untuk evaluasi masa kerja pengurus, Konpercab
                                    dapat membentuk komisi kajian objektif cabang.
                                </p>
                            </div>

                            <div class="pt-4">
                                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-extrabold">Pasal
                                        5</span>
                                    <span>Badan Pengurus Cabang</span>
                                </h4>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    • Jika PP tidak hadir langsung saat pelantikan BPC, PP memberikan surat mandat
                                    kepada Senior Members/Friends atau Pendeta setempat.<br>
                                    • Naskah serah terima bermeterai mencakup inventarisasi kewenangan dan kekayaan
                                    organisasi.<br>
                                    • Usulan Pergantian Antar Waktu (PAW) harus disertai kronologis dan dokumen
                                    pendukung.<br>
                                    • Larangan rangkap jabatan: di dalam organisasi (kecuali badan pembantu ad-hoc) dan
                                    di luar organisasi bagi penanggung jawab cabang (kecuali jabatan fungsional gerejawi
                                    & organisasi intra-universiter).
                                </p>
                            </div>

                            <div class="pt-4">
                                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-extrabold">Pasal
                                        7</span>
                                    <span>Komisariat</span>
                                </h4>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    Komisariat dapat menjadi pelaksana kegiatan Masa Perkenalan (Maper), namun
                                    penanggung jawab yuridis penerimaan anggota tetap berada di tangan BPC (Juncto ART
                                    Pasal 2).
                                </p>
                            </div>

                            <div class="pt-4">
                                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-extrabold">Pasal
                                        8 & 9</span>
                                    <span>Lambang, Mars & Tata Upacara</span>
                                </h4>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    • Kedudukan bendera & panji harus setara dengan organisasi sederajat lainnya.<br>
                                    • Pidato pada upacara khusus organisasi hanya disampaikan oleh Ketua Umum (tingkat
                                    PP) atau Ketua Cabang (tingkat BPC). Pejabat lain menyampaikan dalam bentuk
                                    sambutan.
                                </p>
                            </div>

                            <div class="pt-4">
                                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-extrabold">Pasal
                                        10 & 11</span>
                                    <span>Mewakili Organisasi & Penutup</span>
                                </h4>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    Telah jelas sesuai dengan tata urutan perwakilan dan prinsip koordinasi Pengurus
                                    Pusat di setiap jenjang wilayah pelayanan.
                                </p>
                            </div>

                        </div>
                    </div>

                </div>

            </main>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button type="button" id="backToTopBtn"
    class="fixed bottom-6 right-6 p-3.5 rounded-2xl bg-[#0f3d64] text-white shadow-2xl hover:bg-[#0a263f] hover:scale-105 active:scale-95 transition-all opacity-0 pointer-events-none z-50 no-print border border-white/20"
    title="Kembali ke Atas">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="18 15 12 9 6 15"></polyline>
    </svg>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Tab Switching System
        const sections = ['ad', 'art', 'po', 'penjelasan'];
        const sectionTitles = {
            'ad': 'Daftar Isi AD',
            'art': 'Daftar Isi ART',
            'po': 'Daftar Isi PO',
            'penjelasan': 'Penjelasan PO'
        };

        window.switchSection = function (sectionId) {
            sections.forEach(s => {
                const tabBtn = document.getElementById('tab-' + s);
                const pane = document.getElementById('section-' + s);
                if (s === sectionId) {
                    tabBtn.classList.add('active');
                    tabBtn.classList.remove('text-slate-700', 'hover:bg-white/70');
                    if (pane) pane.classList.remove('hidden');
                } else {
                    tabBtn.classList.remove('active');
                    tabBtn.classList.add('text-slate-700', 'hover:bg-white/70');
                    if (pane) pane.classList.add('hidden');
                }
            });

            // Update TOC for the current section
            buildToc(sectionId);

            // Update URL hash without jumping
            if (history.replaceState) {
                history.replaceState(null, null, '#' + sectionId);
            }
        };

        // Scroll to reading container smoothly
        window.scrollToReadingContent = function () {
            const container = document.getElementById('adContentContainer');
            if (container) {
                const yOffset = -120;
                const y = container.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        };

        // Table of Contents (TOC) Toggle Controller (Hilang-Muncul & Mobile Drawer)
        let isTocVisible = true;
        const savedTocPref = localStorage.getItem('gmki_ad_art_toc_visible');
        if (savedTocPref !== null) {
            isTocVisible = (savedTocPref === 'true');
        }

        const tocSidebar = document.getElementById('tocSidebar');
        const adContentContainer = document.getElementById('adContentContainer');
        const toggleTocBtn = document.getElementById('toggleTocBtn');
        const toggleTocIcon = document.getElementById('toggleTocIcon');
        const toggleTocLabel = document.getElementById('toggleTocLabel');
        const toggleTocBadge = document.getElementById('toggleTocBadge');
        const mobileTocBackdrop = document.getElementById('mobileTocBackdrop');

        window.toggleToc = function (forceState) {
            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                const willOpen = (typeof forceState === 'boolean') ? forceState : (tocSidebar && !tocSidebar.classList.contains('mobile-drawer-open'));
                if (willOpen && tocSidebar) {
                    tocSidebar.classList.remove('hidden');
                    tocSidebar.classList.add('mobile-drawer-open');
                    if (mobileTocBackdrop) {
                        mobileTocBackdrop.classList.remove('hidden');
                        setTimeout(() => mobileTocBackdrop.classList.add('opacity-100'), 10);
                    }
                    document.body.style.overflow = 'hidden';
                } else if (tocSidebar) {
                    tocSidebar.classList.remove('mobile-drawer-open');
                    tocSidebar.classList.add('hidden');
                    if (mobileTocBackdrop) {
                        mobileTocBackdrop.classList.remove('opacity-100');
                        setTimeout(() => mobileTocBackdrop.classList.add('hidden'), 200);
                    }
                    document.body.style.overflow = '';
                }
                return;
            }

            // Desktop Behavior
            if (typeof forceState === 'boolean') {
                isTocVisible = forceState;
            } else {
                isTocVisible = !isTocVisible;
            }

            applyDesktopTocState();
            try {
                localStorage.setItem('gmki_ad_art_toc_visible', isTocVisible);
            } catch (e) {}
        };

        function applyDesktopTocState() {
            if (window.innerWidth < 1024) return;

            if (isTocVisible) {
                if (tocSidebar) {
                    tocSidebar.classList.remove('hidden');
                    tocSidebar.classList.add('lg:flex', 'lg:col-span-3');
                }
                if (adContentContainer) {
                    adContentContainer.classList.remove('lg:col-span-12', 'max-w-5xl', 'mx-auto');
                    adContentContainer.classList.add('lg:col-span-9');
                }
                if (toggleTocIcon) toggleTocIcon.textContent = '📖';
                if (toggleTocLabel) toggleTocLabel.textContent = 'Daftar Isi';
                if (toggleTocBadge) {
                    toggleTocBadge.textContent = 'Muncul';
                    toggleTocBadge.className = 'text-[10px] font-sans px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-900 border border-amber-300/60 font-bold';
                }
            } else {
                if (tocSidebar) {
                    tocSidebar.classList.remove('lg:flex', 'lg:col-span-3');
                    tocSidebar.classList.add('hidden');
                }
                if (adContentContainer) {
                    adContentContainer.classList.remove('lg:col-span-9');
                    adContentContainer.classList.add('lg:col-span-12', 'max-w-5xl', 'mx-auto');
                }
                if (toggleTocIcon) toggleTocIcon.textContent = '📑';
                if (toggleTocLabel) toggleTocLabel.textContent = 'Daftar Isi';
                if (toggleTocBadge) {
                    toggleTocBadge.textContent = 'Tersembunyi';
                    toggleTocBadge.className = 'text-[10px] font-sans px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 border border-slate-300 font-bold';
                }
            }
        }

        // Apply on desktop initial load
        if (window.innerWidth >= 1024) {
            applyDesktopTocState();
        }

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                if (mobileTocBackdrop) {
                    mobileTocBackdrop.classList.add('hidden');
                    mobileTocBackdrop.classList.remove('opacity-100');
                }
                document.body.style.overflow = '';
                if (tocSidebar) tocSidebar.classList.remove('mobile-drawer-open');
                applyDesktopTocState();
            } else {
                if (tocSidebar) {
                    tocSidebar.classList.remove('lg:flex', 'lg:col-span-3');
                    if (!tocSidebar.classList.contains('mobile-drawer-open')) {
                        tocSidebar.classList.add('hidden');
                    }
                }
                if (adContentContainer) {
                    adContentContainer.classList.remove('lg:col-span-9', 'lg:col-span-12', 'max-w-5xl', 'mx-auto');
                }
                if (toggleTocIcon) toggleTocIcon.textContent = '📖';
                if (toggleTocLabel) toggleTocLabel.textContent = 'Daftar Isi';
                if (toggleTocBadge) {
                    toggleTocBadge.textContent = 'Buka';
                    toggleTocBadge.className = 'text-[10px] font-sans px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-900 border border-amber-300/60 font-bold';
                }
            }
        });

        // 2. Build Table of Contents dynamically for active section
        function buildToc(sectionId) {
            const tocHeader = document.getElementById('tocHeaderTitle');
            const tocContainer = document.getElementById('tocContainer');
            const tocBadge = document.getElementById('tocCountBadge');
            const tocFilter = document.getElementById('tocFilterInput');
            if (tocFilter) tocFilter.value = '';

            if (tocHeader) tocHeader.textContent = sectionTitles[sectionId] || 'Daftar Isi';

            tocContainer.innerHTML = '';
            const currentPane = document.getElementById('section-' + sectionId);
            if (!currentPane) return;

            const cards = currentPane.querySelectorAll('.ad-card');
            if (tocBadge) {
                tocBadge.textContent = cards.length + (sectionId === 'penjelasan' ? ' Bagian' : ' Pasal');
            }

            cards.forEach(card => {
                const id = card.id;
                const headingEl = card.querySelector('h2, h3');
                const badgeEl = card.querySelector('span[class*="rounded"]');

                let badgeText = '';
                if (id === 'ad-pembukaan') {
                    badgeText = 'Mukadimah';
                } else if (id === 'art-intro-charter') {
                    badgeText = 'Pengantar ART';
                } else if (id === 'po-intro-charter') {
                    badgeText = 'Pedoman PO';
                } else if (badgeEl) {
                    badgeText = badgeEl.textContent.trim().replace('ART ', '').replace('PO ', '');
                } else {
                    badgeText = 'Pasal';
                }

                const title = headingEl ? headingEl.textContent.trim() : id;

                const a = document.createElement('a');
                a.href = '#' + id;
                a.className = 'toc-item group';
                a.setAttribute('data-target-id', id);
                a.setAttribute('data-toc-title', (badgeText + ' ' + title).toLowerCase());

                a.innerHTML = `
                    <span class="toc-item-badge">${badgeText}</span>
                    <span class="truncate flex-1 group-hover:text-[#0f3d64] transition-colors">${title}</span>
                `;

                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (window.innerWidth < 1024) {
                        window.toggleToc(false);
                    }
                    const target = document.getElementById(id);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                        highlightCard(target);
                    }
                });
                tocContainer.appendChild(a);
            });

            updateTocScrollspy();
        }

        // Filter TOC Items
        const tocFilterInput = document.getElementById('tocFilterInput');
        if (tocFilterInput) {
            tocFilterInput.addEventListener('input', function () {
                const query = this.value.trim().toLowerCase();
                const items = document.querySelectorAll('#tocContainer .toc-item');
                items.forEach(item => {
                    const searchStr = item.getAttribute('data-toc-title') || '';
                    if (!query || searchStr.includes(query)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        function highlightCard(element) {
            document.querySelectorAll('.ad-card').forEach(c => c.classList.remove('highlight-target'));
            element.classList.add('highlight-target');
            setTimeout(() => {
                element.classList.remove('highlight-target');
            }, 3500);
        }

        // 3. Search Engine & Live Filter
        const searchInput = document.getElementById('adSearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const searchCounter = document.getElementById('searchCounter');

        let searchDebounceTimer;
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchDebounceTimer);
                const term = this.value.trim().toLowerCase();

                if (term.length > 0) {
                    clearBtn.classList.remove('hidden');
                    clearBtn.classList.add('flex');
                } else {
                    clearBtn.classList.add('hidden');
                    clearBtn.classList.remove('flex');
                }

                searchDebounceTimer = setTimeout(() => {
                    performSearch(term);
                }, 200);
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                searchInput.value = '';
                clearBtn.classList.add('hidden');
                performSearch('');
                searchInput.focus();
            });
        }

        // Keyboard shortcut '/' to search
        document.addEventListener('keydown', function (e) {
            if (e.key === '/' && document.activeElement !== searchInput && document.activeElement.tagName !== 'INPUT') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            } else if (e.key === 'Escape' && document.activeElement === searchInput) {
                searchInput.blur();
            }
        });

        function performSearch(term) {
            const searchableCards = document.querySelectorAll('[data-searchable]');
            if (!term) {
                searchCounter.classList.add('hidden');
                searchableCards.forEach(card => {
                    card.style.display = '';
                    removeHighlights(card);
                });
                // return to current active section view
                const activeTab = document.querySelector('.tab-btn.active');
                const activeSec = activeTab ? activeTab.id.replace('tab-', '') : 'ad';
                switchSection(activeSec);
                return;
            }

            // Show all sections during search so matches across all categories are visible
            sections.forEach(s => {
                const pane = document.getElementById('section-' + s);
                if (pane) pane.classList.remove('hidden');
            });

            let matchCount = 0;
            let firstMatch = null;

            searchableCards.forEach(card => {
                removeHighlights(card);
                const text = card.textContent.toLowerCase();
                if (text.includes(term)) {
                    card.style.display = '';
                    matchCount++;
                    if (!firstMatch) firstMatch = card;
                    highlightTextInElement(card, term);
                } else {
                    card.style.display = 'none';
                }
            });

            searchCounter.classList.remove('hidden');
            searchCounter.textContent = matchCount + ' pasal / topik cocok';

            if (firstMatch) {
                firstMatch.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function removeHighlights(element) {
            const highlights = element.querySelectorAll('.search-highlight');
            highlights.forEach(h => {
                const parent = h.parentNode;
                parent.replaceChild(document.createTextNode(h.textContent), h);
                parent.normalize();
            });
        }

        function highlightTextInElement(element, term) {
            // Safe DOM text highlight
            const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null, false);
            const textNodes = [];
            let node;
            while (node = walker.nextNode()) {
                if (node.nodeValue.toLowerCase().includes(term) && !node.parentElement.classList.contains('search-highlight')) {
                    textNodes.push(node);
                }
            }

            textNodes.forEach(textNode => {
                const val = textNode.nodeValue;
                const idx = val.toLowerCase().indexOf(term);
                if (idx >= 0) {
                    const span = document.createElement('mark');
                    span.className = 'search-highlight';
                    span.textContent = val.substr(idx, term.length);

                    const after = textNode.splitText(idx);
                    after.nodeValue = after.nodeValue.substr(term.length);
                    textNode.parentNode.insertBefore(span, after);
                }
            });
        }

        // 4. Reading Font Size Controls (Enhanced 4-Level Scalability with Persistence)
        const fontSizes = ['0.95rem', '1.15rem', '1.4rem', '1.75rem'];
        const savedFontIdx = localStorage.getItem('gmki_ad_art_font_idx');
        let currentFontIdx = (savedFontIdx !== null && !isNaN(savedFontIdx)) ? parseInt(savedFontIdx, 10) : 1;
        if (currentFontIdx < 0 || currentFontIdx >= fontSizes.length) currentFontIdx = 1;

        const fontBtnDecrease = document.getElementById('fontDecrease');
        const fontBtnReset = document.getElementById('fontReset');
        const fontBtnIncrease = document.getElementById('fontIncrease');
        const fontBtnIncreaseMax = document.getElementById('fontIncreaseMax');
        const allFontBtns = [fontBtnDecrease, fontBtnReset, fontBtnIncrease, fontBtnIncreaseMax];

        function updateFontSizeButtons() {
            allFontBtns.forEach(b => {
                if (b) {
                    b.classList.remove('active', 'bg-[#0f3d64]', 'text-amber-200', 'shadow-xs');
                    b.classList.add('text-slate-700');
                }
            });
            const activeBtn = allFontBtns[currentFontIdx];
            if (activeBtn) {
                activeBtn.classList.add('active', 'bg-[#0f3d64]', 'text-amber-200', 'shadow-xs');
                activeBtn.classList.remove('text-slate-700');
            }
        }

        if (fontBtnDecrease) {
            fontBtnDecrease.addEventListener('click', function () {
                currentFontIdx = 0;
                applyFontSize();
            });
        }

        if (fontBtnReset) {
            fontBtnReset.addEventListener('click', function () {
                currentFontIdx = 1;
                applyFontSize();
            });
        }

        if (fontBtnIncrease) {
            fontBtnIncrease.addEventListener('click', function () {
                currentFontIdx = 2;
                applyFontSize();
            });
        }

        if (fontBtnIncreaseMax) {
            fontBtnIncreaseMax.addEventListener('click', function () {
                currentFontIdx = 3;
                applyFontSize();
            });
        }

        function applyFontSize() {
            document.documentElement.style.setProperty('--reading-font-size', fontSizes[currentFontIdx]);
            try {
                localStorage.setItem('gmki_ad_art_font_idx', currentFontIdx);
            } catch (e) {}
            updateFontSizeButtons();
        }

        // Initialize saved font size on page load
        applyFontSize();

        // 5. Toast & Copy Pasal Anchor Link Helper
        window.copyPasalLink = function (id, btn) {
            const url = window.location.origin + window.location.pathname + '#' + id;
            const targetCard = document.getElementById(id);
            if (targetCard) highlightCard(targetCard);

            showCopyToast('Tautan pasal berhasil disalin ke papan klip!');

            if (btn) {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = `<span style="color:#10b981;font-weight:bold;">✓</span><span class="hidden sm:inline" style="color:#10b981;font-weight:bold;">Tersalin!</span>`;
                btn.style.borderColor = '#10b981';
                btn.style.backgroundColor = '#ecfdf5';
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.style.borderColor = '';
                    btn.style.backgroundColor = '';
                }, 1800);
            }

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(url).catch(() => fallbackCopy(url));
                } else {
                    fallbackCopy(url);
                }
            } catch (e) {
                fallbackCopy(url);
            }
        };

        function fallbackCopy(text) {
            try {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showCopyToast('Tautan pasal berhasil disalin ke papan klip!');
            } catch (e) {
                prompt('Salin tautan ini:', text);
            }
        }

        function showCopyToast(msg) {
            const toast = document.getElementById('copyToast');
            const text = document.getElementById('copyToastText');
            if (!toast) return;
            if (text) text.textContent = msg;
            toast.classList.add('toast-visible');
            clearTimeout(window._copyToastTimer);
            window._copyToastTimer = setTimeout(() => {
                toast.classList.remove('toast-visible');
            }, 2500);
        }

        // Enhance all Copy Buttons across articles to have SVG, text and click handler
        document.querySelectorAll('.ad-card button[onclick^="copyPasalLink"]').forEach(btn => {
            btn.innerHTML = `
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
            </svg>
            <span class="hidden sm:inline">Salin Link</span>
        `;
            const match = btn.getAttribute('onclick')?.match(/copyPasalLink\(['"]([^'"]+)['"]\)/);
            if (match && match[1]) {
                btn.removeAttribute('onclick');
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.copyPasalLink(match[1], this);
                });
            }
        });

        // 6. Scrollspy & Reading Progress Bar
        const backToTopBtn = document.getElementById('backToTopBtn');
        const pBar = document.getElementById('readingProgressBar');

        function updateTocScrollspy() {
            const activeTab = document.querySelector('.tab-btn.active');
            const activeSec = activeTab ? activeTab.id.replace('tab-', '') : 'ad';
            const currentPane = document.getElementById('section-' + activeSec);
            if (!currentPane) return;

            const cards = currentPane.querySelectorAll('.ad-card');
            let currentCardId = null;
            const scrollPosition = window.scrollY + 160;

            cards.forEach(card => {
                const top = card.offsetTop;
                const height = card.offsetHeight;
                if (scrollPosition >= top && scrollPosition < top + height) {
                    currentCardId = card.id;
                }
            });

            if (currentCardId) {
                document.querySelectorAll('#tocContainer .toc-item').forEach(item => {
                    if (item.getAttribute('data-target-id') === currentCardId) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
        }

        window.addEventListener('scroll', function () {
            // Progress bar
            const winScroll = document.documentElement.scrollTop || document.body.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = height > 0 ? (winScroll / height) * 100 : 0;
            if (pBar) pBar.style.width = scrolled + '%';

            // Back to top button
            if (backToTopBtn) {
                if (window.scrollY > 400) {
                    backToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
                    backToTopBtn.classList.add('opacity-100');
                } else {
                    backToTopBtn.classList.add('opacity-0', 'pointer-events-none');
                    backToTopBtn.classList.remove('opacity-100');
                }
            }

            // Scrollspy
            updateTocScrollspy();
        });

        if (backToTopBtn) {
            backToTopBtn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // 7. Initial Hash Handling
        const hash = window.location.hash.replace('#', '');
        if (hash) {
            if (sections.includes(hash)) {
                switchSection(hash);
            } else if (hash.startsWith('art-')) {
                switchSection('art');
                setTimeout(() => {
                    const el = document.getElementById(hash);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth' });
                        highlightCard(el);
                    }
                }, 300);
            } else if (hash.startsWith('po-')) {
                switchSection('po');
                setTimeout(() => {
                    const el = document.getElementById(hash);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth' });
                        highlightCard(el);
                    }
                }, 300);
            } else if (hash.startsWith('penjelasan-')) {
                switchSection('penjelasan');
                setTimeout(() => {
                    const el = document.getElementById(hash);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth' });
                        highlightCard(el);
                    }
                }, 300);
            } else {
                switchSection('ad');
                setTimeout(() => {
                    const el = document.getElementById(hash);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth' });
                        highlightCard(el);
                    }
                }, 300);
            }
        } else {
            switchSection('ad');
        }
    });
</script>