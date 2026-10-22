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
 * GMKI CABANG PADANG - AD/ART DESIGN SYSTEM
 * ===================================================================== */
    :root {
        --reading-font-size: 1rem;
        --ad-navy: #0a263f;
        --ad-blue: #0f3d64;
        --ad-gold: #d97706;
    }

    html {
        scroll-behavior: smooth;
    }

    /* Reading Progress Bar */
    #readingProgressBar {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0%;
        background: linear-gradient(90deg, #d97706 0%, #2563eb 50%, #10b981 100%);
        z-index: 9999;
        transition: width 0.1s ease;
    }

    /* Reading Container */
    .ad-reading-container {
        font-size: var(--reading-font-size);
        line-height: 1.85;
        color: #334155;
    }

    /* Card Styling */
    .ad-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04), 0 2px 4px -1px rgba(15, 23, 42, 0.02);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
        scroll-margin-top: 110px;
        overflow: hidden;
    }

    .ad-card:hover {
        transform: translateY(-3px);
        border-color: #cbd5e1;
        box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.03);
    }

    /* Top Accent Bar per Section */
    #section-ad .ad-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3.5px;
        background: linear-gradient(90deg, #0f3d64 0%, #2563eb 50%, #38bdf8 100%);
    }

    #section-art .ad-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3.5px;
        background: linear-gradient(90deg, #d97706 0%, #f59e0b 50%, #fbbf24 100%);
    }

    #section-po .ad-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3.5px;
        background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 50%, #c084fc 100%);
    }

    #section-penjelasan .ad-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3.5px;
        background: linear-gradient(90deg, #0d9488 0%, #10b981 50%, #34d399 100%);
    }

    /* Pasal Badge Styling */
    #section-ad .ad-card span[class*="bg-[#0f3d64]"] {
        background: linear-gradient(135deg, #0a263f 0%, #0f3d64 100%) !important;
        box-shadow: 0 2px 6px rgba(15, 61, 100, 0.25);
        border-radius: 0.5rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        letter-spacing: 0.05em;
    }

    #section-art .ad-card span[class*="bg-amber-600"] {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
        box-shadow: 0 2px 6px rgba(217, 119, 6, 0.25);
        border-radius: 0.5rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        letter-spacing: 0.05em;
    }

    #section-po .ad-card span[class*="bg-blue-800"] {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%) !important;
        box-shadow: 0 2px 6px rgba(99, 102, 241, 0.25);
        border-radius: 0.5rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        letter-spacing: 0.05em;
    }

    /* Card Target Highlight */
    .ad-card.highlight-target {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.25), 0 16px 36px -6px rgba(15, 23, 42, 0.12) !important;
        animation: pulseCardTarget 1.5s ease-in-out infinite;
    }

    @keyframes pulseCardTarget {

        0%,
        100% {
            transform: translateY(-3px) scale(1);
        }

        50% {
            transform: translateY(-3px) scale(1.006);
        }
    }

    /* Preambule Special Card */
    #ad-pembukaan {
        background: linear-gradient(135deg, #fffdf8 0%, #ffffff 50%, rgba(254, 243, 199, 0.25) 100%) !important;
        border: 1.5px solid #fde68a !important;
    }

    #ad-pembukaan::before {
        background: linear-gradient(90deg, #d97706 0%, #fbbf24 50%, #d97706 100%) !important;
        height: 4.5px !important;
    }

    /* Navigation Segmented Control */
    .tab-btn {
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        color: #64748b;
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

    /* Quick Topic Chips */
    .quick-chip {
        transition: all 0.18s ease;
    }

    .quick-chip.active {
        background: #0f3d64 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(15, 61, 100, 0.25);
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

<div class="bg-gradient-to-b from-slate-50 via-slate-100/60 to-slate-100 min-w-full pb-20">
    <!-- Top Hero Section -->
    <section
        class="relative bg-gradient-to-br from-[#06192e] via-[#0a263f] to-[#0f3d64] text-white pt-14 pb-20 overflow-hidden shadow-md">
        <!-- Ambient decorative glows & subtle grid -->
        <div
            class="absolute -top-24 left-1/2 -translate-x-1/2 w-[800px] h-[350px] bg-gradient-to-b from-blue-500/15 via-amber-500/10 to-transparent blur-3xl pointer-events-none rounded-full">
        </div>
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none"
            style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 28px 28px;"></div>

        <div class="container relative z-10 px-4 sm:px-6">
            <div class="max-w-4xl mx-auto text-center">

                <!-- Ornamental Header Badge -->
                <div class="mx-auto mb-5 flex items-center justify-center gap-3 animate__animated animate__fadeInDown">
                    <span
                        class="block h-px w-10 sm:w-16 bg-gradient-to-r from-transparent via-amber-400/60 to-amber-400 rounded-full"></span>
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest bg-amber-500/15 text-amber-300 border border-amber-400/30 shadow-sm backdrop-blur-md">
                        <span>⚖️</span>
                        <span>Konstitusi &amp; Landasan Yuridis Organisasi</span>
                    </div>
                    <span
                        class="block h-px w-10 sm:w-16 bg-gradient-to-l from-transparent via-amber-400/60 to-amber-400 rounded-full"></span>
                </div>

                <!-- Title -->
                <h1
                    class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight mb-4 leading-tight animate__animated animate__fadeIn">
                    Anggaran Dasar &amp; Anggaran Rumah Tangga<br class="hidden sm:inline">
                    <span
                        class="bg-gradient-to-r from-amber-300 via-amber-200 to-amber-400 bg-clip-text text-transparent">GMKI
                        Cabang Padang</span>
                </h1>

                <!-- Subtitle -->
                <p
                    class="text-slate-200/90 text-sm sm:text-base md:text-lg max-w-2xl mx-auto mb-8 leading-relaxed font-normal animate__animated animate__fadeIn">
                    Pedoman konstitusional tertinggi penatalayanan, kepemimpinan, dan kaderisasi di Tiga Medan Pelayanan
                    (Gereja, Perguruan Tinggi, dan Masyarakat) Gerakan Mahasiswa Kristen Indonesia.
                </p>

                <!-- Quick Stats 4-Column Grid -->
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 max-w-4xl mx-auto mb-10 text-left animate__animated animate__fadeInUp">
                    <!-- Stat 1 -->
                    <div
                        class="p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/15 hover:border-blue-400/40 transition-all duration-300 shadow-lg group">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">📜</span>
                            <span class="text-xs font-extrabold text-blue-200 uppercase tracking-wider">Hukum
                                Pokok</span>
                        </div>
                        <div class="text-lg sm:text-xl font-black text-white">12 Pasal</div>
                        <div class="text-[11px] text-slate-300 font-medium">Anggaran Dasar (AD)</div>
                    </div>
                    <!-- Stat 2 -->
                    <div
                        class="p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/15 hover:border-amber-400/40 transition-all duration-300 shadow-lg group">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">⚖️</span>
                            <span class="text-xs font-extrabold text-amber-300 uppercase tracking-wider">Tata
                                Laksana</span>
                        </div>
                        <div class="text-lg sm:text-xl font-black text-white">12 Pasal</div>
                        <div class="text-[11px] text-slate-300 font-medium">Anggaran Rumah Tangga</div>
                    </div>
                    <!-- Stat 3 -->
                    <div
                        class="p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/15 hover:border-indigo-400/40 transition-all duration-300 shadow-lg group">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">📑</span>
                            <span class="text-xs font-extrabold text-purple-200 uppercase tracking-wider">Pedoman
                                Teknis</span>
                        </div>
                        <div class="text-lg sm:text-xl font-black text-white">11 Pasal</div>
                        <div class="text-[11px] text-slate-300 font-medium">Peraturan Organisasi</div>
                    </div>
                </div>

                <!-- Floating Interactive Toolbar -->
                <div
                    class="bg-white/95 backdrop-blur-xl p-3.5 sm:p-5 rounded-2xl shadow-2xl border border-white/60 text-slate-800 max-w-4xl mx-auto flex flex-col gap-3 no-print">
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <!-- Search input with shortcut badge -->
                        <div class="relative w-full sm:flex-1">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <?= svg_icon('search', 18) ?>
                            </span>
                            <input type="text" id="adSearchInput"
                                placeholder="Cari pasal, ayat, atau kata kunci (cth: keanggotaan, maper, kongres, konpercab)..."
                                class="w-full pl-10 pr-20 py-2.5 sm:py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#0f3d64] focus:border-transparent text-xs sm:text-sm bg-slate-50 focus:bg-white transition-all shadow-inner placeholder:text-slate-400"
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

                        <!-- Reading Tools Controls -->
                        <div class="flex items-center justify-between w-full sm:w-auto gap-2">
                            <!-- Font Size Controls with visual pills -->
                            <div
                                class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-100 p-1 text-xs font-bold text-slate-600 shadow-inner">
                                <span
                                    class="text-[10px] font-semibold text-slate-400 px-2 uppercase hidden lg:inline">Ukuran:</span>
                                <button type="button" id="fontDecrease"
                                    class="font-size-btn px-2.5 py-1.5 rounded-lg text-slate-600 hover:bg-white hover:text-slate-900 transition"
                                    title="Perkecil Ukuran Teks (A-)">A-</button>
                                <button type="button" id="fontReset"
                                    class="font-size-btn active px-2.5 py-1.5 rounded-lg bg-white text-slate-900 shadow-xs transition"
                                    title="Ukuran Normal (A)">A</button>
                                <button type="button" id="fontIncrease"
                                    class="font-size-btn px-2.5 py-1.5 rounded-lg text-slate-600 hover:bg-white hover:text-slate-900 transition"
                                    title="Perbesar Ukuran Teks (A+)">A+</button>
                            </div>

                            <!-- Print Action -->
                            <button type="button" onclick="window.print()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-xs hover:shadow-sm transition"
                                title="Cetak atau Simpan PDF">
                                <span>🖨️</span>
                                <span class="hidden sm:inline">Cetak / PDF</span>
                            </button>
                        </div>
                    </div>

                    <!-- Quick Filter Chips -->
                    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pt-1 text-xs">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider shrink-0 mr-1">Topik
                            Cepat:</span>
                        <button type="button" onclick="quickFilter('')"
                            class="quick-chip active px-2.5 py-1 rounded-lg bg-[#0f3d64] text-white font-medium hover:opacity-90 transition whitespace-nowrap">✨
                            Semua</button>
                        <button type="button" onclick="quickFilter('keanggotaan')"
                            class="quick-chip px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition whitespace-nowrap">Keanggotaan</button>
                        <button type="button" onclick="quickFilter('asas')"
                            class="quick-chip px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition whitespace-nowrap">Asas
                            &amp; Visi</button>
                        <button type="button" onclick="quickFilter('bpc')"
                            class="quick-chip px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition whitespace-nowrap">Badan
                            Pengurus Cabang</button>
                        <button type="button" onclick="quickFilter('konpercab')"
                            class="quick-chip px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition whitespace-nowrap">Konperensi
                            Cabang</button>
                        <button type="button" onclick="quickFilter('maper')"
                            class="quick-chip px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition whitespace-nowrap">Maper</button>
                        <button type="button" onclick="quickFilter('disiplin')"
                            class="quick-chip px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition whitespace-nowrap">Disiplin
                            Organisasi</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Navigation Tabs (Segmented Sticky Navigation) -->
    <div class="sticky top-[72px] z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs no-print">
        <div class="container px-4 sm:px-6">
            <div class="flex items-center justify-between py-2.5 gap-3">
                <!-- Segmented Control Container -->
                <div
                    class="inline-flex p-1.5 rounded-2xl bg-slate-100/90 border border-slate-200/80 overflow-x-auto no-scrollbar gap-1.5 max-w-full">
                    <button type="button" onclick="switchSection('ad')"
                        class="tab-btn active px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 whitespace-nowrap transition-all"
                        id="tab-ad">
                        <span>📜</span>
                        <span>Anggaran Dasar</span>
                        <span class="tab-count-badge">12 Pasal</span>
                    </button>
                    <button type="button" onclick="switchSection('art')"
                        class="tab-btn px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-white/60 flex items-center gap-2 whitespace-nowrap transition-all"
                        id="tab-art">
                        <span>⚖️</span>
                        <span>Anggaran Rumah Tangga</span>
                        <span class="tab-count-badge">12 Pasal</span>
                    </button>
                    <button type="button" onclick="switchSection('po')"
                        class="tab-btn px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-white/60 flex items-center gap-2 whitespace-nowrap transition-all"
                        id="tab-po">
                        <span>📑</span>
                        <span>Peraturan Organisasi</span>
                        <span class="tab-count-badge">11 Pasal</span>
                    </button>
                    <button type="button" onclick="switchSection('penjelasan')"
                        class="tab-btn px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-white/60 flex items-center gap-2 whitespace-nowrap transition-all"
                        id="tab-penjelasan">
                        <span>💡</span>
                        <span>Penjelasan Resmi PO</span>
                        <span class="tab-count-badge">Doktrin</span>
                    </button>
                </div>

                <!-- Match counter banner -->
                <div id="searchCounter"
                    class="hidden text-xs font-bold text-amber-800 bg-amber-50 px-3 py-2 rounded-xl border border-amber-300/80 shadow-xs whitespace-nowrap animate__animated animate__fadeIn">
                    0 hasil ditemukan
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="container px-4 sm:px-6 pt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Upgraded Sticky Table of Contents Sidebar (Desktop) -->
            <aside
                class="hidden lg:flex lg:col-span-3 sticky top-[140px] max-h-[calc(100vh-160px)] flex-col no-print bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 text-sm overflow-hidden">
                <!-- TOC Header -->
                <div class="pb-3 mb-3 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        <span class="font-extrabold text-xs uppercase tracking-wider text-slate-800"
                            id="tocHeaderTitle">
                            Daftar Isi AD
                        </span>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500"
                        id="tocCountBadge">
                        12 Pasal
                    </span>
                </div>

                <!-- TOC Filter Input -->
                <div class="mb-3">
                    <input type="text" id="tocFilterInput" placeholder="Saring daftar pasal..."
                        class="w-full text-xs px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#0f3d64] text-slate-700 placeholder:text-slate-400">
                </div>

                <!-- Dynamic TOC Container with custom sleek scrollbar -->
                <nav id="tocContainer"
                    class="space-y-1 text-slate-600 text-xs overflow-y-auto pr-1 flex-1 custom-toc-scroll">
                    <!-- Populated by JavaScript according to active section -->
                </nav>

                <!-- Constitutional Hierarchy Widget -->
                <div
                    class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500 bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/60">
                    <div class="font-bold text-slate-700 flex items-center gap-1 mb-1">
                        <span>🏛️</span> Hierarki Hukum GMKI:
                    </div>
                    <ol class="list-decimal list-inside space-y-0.5 text-[10px] text-slate-500 leading-tight">
                        <li>Anggaran Dasar (AD)</li>
                        <li>Anggaran Rumah Tangga (ART)</li>
                        <li>Peraturan Organisasi (PO)</li>
                        <li>Ketetapan Kongres / Konpercab</li>
                    </ol>
                </div>
            </aside>

            <!-- Main Legal Body Content -->
            <main class="lg:col-span-9 space-y-8 ad-reading-container" id="adContentContainer">

                <!-- ================================================================= -->
                <!-- 1. ANGGARAN DASAR (AD) -->
                <!-- ================================================================= -->
                <div id="section-ad" class="tab-pane space-y-6">

                    <!-- Preambule / Pembukaan Special Card -->
                    <div id="ad-pembukaan"
                        class="ad-card bg-gradient-to-br from-amber-50/50 via-white to-slate-50 border-amber-200/70 p-6 sm:p-10 relative overflow-hidden"
                        data-searchable>
                        <div
                            class="absolute -top-12 -right-12 w-48 h-48 bg-amber-400/10 rounded-full blur-3xl pointer-events-none">
                        </div>

                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-amber-200/50">
                            <span class="p-2.5 rounded-xl bg-amber-100 text-amber-800 text-xl">📜</span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Mukadimah
                                    Konstitusi</span>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Pembukaan Anggaran Dasar
                                </h2>
                            </div>
                        </div>

                        <div
                            class="text-slate-800 space-y-5 text-sm sm:text-base leading-relaxed text-justify font-serif">
                            <p
                                class="first-letter:text-4xl first-letter:font-bold first-letter:text-[#0f3d64] first-letter:mr-1 first-letter:float-left">
                                <strong>Sesungguhnya Yesus Kristus</strong>, Anak Allah dan Juruselamat, ialah Tuhan
                                manusia dan alam semesta. Kehadiran-Nya dalam sejarah ialah perbuatan Allah untuk
                                menebus dan menyelamatkan manusia melalui kematian dan kebangkitan-Nya yang menjadikan
                                semuanya baru dan sempurna.
                            </p>
                            <p>
                                <strong>Anugerah-Nya</strong> yang dinyatakan dalam karya-Nya memanggil manusia untuk
                                percaya dan mengucap syukur dalam penatalayanan alam semesta, mewujudkan iman,
                                pengharapan dan cinta kasih dalam kehidupan sehari-hari.
                            </p>
                            <p>
                                <strong>Roh Kudus</strong> menghidupkan persekutuan orang beriman selaku gereja yang
                                Esa, Am dan Rasuli, yang diutus untuk menyampaikan kabar keselamatan dan pembebasan bagi
                                pembaharuan manusia dan alam semesta.
                            </p>
                            <p>
                                <strong>Maka menjadi panggilan dan pengutusan</strong> setiap warga gereja yang
                                ditempatkan oleh Tuhan di dalam perjalanan sejarah bangsa dan negara Indonesia, untuk
                                menyatakan kehadiran-Nya dan kehidupan yang bertanggungjawab bersumber pada Alkitab,
                                yang menyaksikan Yesus Kristus ialah Tuhan dan Juruselamat di dalam keesaan Allah Bapa,
                                Anak dan Roh Kudus yang mengerjakan keselamatan manusia untuk mewujudkan kesejahteraan
                                perdamaian, keadilan dan kebenaran di tengah-tengah masyarakat, bangsa dan negara.
                            </p>
                            <div
                                class="p-4 sm:p-5 rounded-xl bg-amber-100/60 border border-amber-300/60 font-sans text-xs sm:text-sm text-slate-900 leading-relaxed mt-4">
                                <strong>Kelahiran Gerakan:</strong><br>
                                Untuk mewujudkan panggilan dan pengutusan dalam kehidupan dan perkembangan Perguruan
                                Tinggi dan mahasiswa, maka pada tanggal <strong>9 Februari 1950</strong> Mahasiswa
                                Kristen Indonesia yang melanjutkan usaha <em>Christelijke Studenten Vereeniging op
                                    Java</em> (berdiri 28 Desember 1932 di Kaliurang) bersama-sama dengan
                                <em>Perhimpunan Mahasiswa Kristen Indonesia</em> meleburkan diri dan berhimpun dalam
                                satu bentuk persekutuan dengan nama <strong>Gerakan Mahasiswa Kristen Indonesia
                                    (GMKI)</strong>, yang bergabung dalam <em>World Student Christian Federation
                                    (WSCF)</em>.
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 1 AD -->
                    <div id="ad-pasal-1" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    1</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Nama, Tempat dan Waktu</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-1')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <ol
                            class="list-decimal list-inside space-y-2 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <li>Organisasi ini bernama <strong>Gerakan Mahasiswa Kristen Indonesia</strong>, disingkat
                                <strong>GMKI</strong>.
                            </li>
                            <li>Organisasi ini berkedudukan di tempat Pengurus Pusat.</li>
                            <li>Organisasi ini berdiri untuk waktu yang tidak ditentukan.</li>
                        </ol>
                    </div>

                    <!-- Pasal 2 AD -->
                    <div id="ad-pasal-2" class="ad-card p-6 sm:p-8 bg-gradient-to-r from-white via-slate-50 to-white"
                        data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    2</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Asas Organisasi</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-2')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div
                            class="p-4 rounded-xl bg-slate-100 border-l-4 border-amber-500 text-slate-900 text-sm sm:text-base font-semibold italic">
                            "Dalam kehidupan bermasyarakat, berbangsa dan bernegara, organisasi ini berasaskan Pancasila
                            sebagai satu-satunya ASAS."
                        </div>
                    </div>

                    <!-- Pasal 3 AD -->
                    <div id="ad-pasal-3" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    3</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Visi dan Misi</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-3')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-100">
                                <div class="font-bold text-blue-900 mb-1 flex items-center gap-1.5">
                                    <span>🎯</span> 1. Visi Organisasi
                                </div>
                                <p class="text-blue-950 font-medium">
                                    Terwujudnya kedamaian, kesejahteraan, keadilan, keutuhan ciptaan dan demokrasi di
                                    Indonesia berdasarkan kasih.
                                </p>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 mb-2 flex items-center gap-1.5">
                                    <span>⚡</span> 2. Misi Organisasi
                                </div>
                                <ul class="space-y-2.5 pl-2">
                                    <li class="flex items-start gap-2.5">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-200 text-slate-800 text-xs font-bold shrink-0 mt-0.5">a</span>
                                        <span>Mengajak mahasiswa dan warga Perguruan Tinggi lainnya kepada pengenalan
                                            akan Yesus Kristus selaku Tuhan dan Penebus dan memperdalam iman dalam
                                            kehidupan dan pekerjaan sehari-hari.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-200 text-slate-800 text-xs font-bold shrink-0 mt-0.5">b</span>
                                        <span>Membina kesadaran selaku warga gereja yang esa di tengah-tengah mahasiswa
                                            dan Perguruan Tinggi dalam kesaksian memperbaharui masyarakat, manusia dan
                                            gereja.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-200 text-slate-800 text-xs font-bold shrink-0 mt-0.5">c</span>
                                        <span>Mempersiapkan pemimpin dan penggerak yang ahli dan bertanggungjawab dengan
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
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    4</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Usaha</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-4')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                            Organisasi ini berusaha mencapai Visi dan Misinya sejalan dengan Asas Organisasi.
                        </p>
                    </div>

                    <!-- Pasal 5 AD -->
                    <div id="ad-pasal-5" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    5</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Status dan Bentuk Organisasi
                                </h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-5')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm sm:text-base">
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <strong class="block text-slate-900 mb-1">1. Status :</strong>
                                <span class="text-slate-700">Organisasi ini adalah organisasi yang bersifat gerejawi dan
                                    tidak merupakan bagian dari organisasi politik.</span>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <strong class="block text-slate-900 mb-1">2. Bentuk :</strong>
                                <span class="text-slate-700">Organisasi ini berbentuk kesatuan yang mempunyai
                                    cabang-cabang di kota-kota perguruan tinggi di Indonesia.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 6 AD -->
                    <div id="ad-pasal-6" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    6</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Keanggotaan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-6')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong>1.</strong> Yang diterima menjadi anggota ialah mereka yang menerima tujuan serta
                                bersedia menjalankan usaha organisasi.</p>
                            <div>
                                <strong class="text-slate-900 block mb-2">2. Anggota terdiri dari :</strong>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs sm:text-sm font-semibold">
                                    <span
                                        class="p-2.5 rounded-lg bg-slate-100 text-center border border-slate-200 text-slate-800">a.
                                        Anggota Biasa</span>
                                    <span
                                        class="p-2.5 rounded-lg bg-slate-100 text-center border border-slate-200 text-slate-800">b.
                                        Anggota Luar Biasa</span>
                                    <span
                                        class="p-2.5 rounded-lg bg-slate-100 text-center border border-slate-200 text-slate-800">c.
                                        Anggota Kehormatan</span>
                                    <span
                                        class="p-2.5 rounded-lg bg-slate-100 text-center border border-slate-200 text-slate-800">d.
                                        Anggota Penyokong</span>
                                </div>
                            </div>
                            <div>
                                <strong class="text-slate-900 block mb-1">3. Hak Anggota :</strong>
                                <ul class="list-disc list-inside space-y-1 text-slate-700 pl-2">
                                    <li><strong>Anggota biasa:</strong> mempunyai hak suara, hak memilih dan hak
                                        dipilih.</li>
                                    <li><strong>Anggota luar biasa:</strong> mempunyai hak dipilih dan hak usul.</li>
                                    <li><strong>Anggota kehormatan & penyokong:</strong> mempunyai hak usul.</li>
                                </ul>
                            </div>
                            <div>
                                <strong class="text-slate-900 block mb-1">4. Kewajiban Anggota :</strong>
                                <ul class="list-disc list-inside space-y-1 text-slate-700 pl-2">
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
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    7</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Alat Perlengkapan Organisasi
                                </h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-7')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <p><strong>1.</strong> Organisasi ini mempunyai alat perlengkapan yang terdiri dari:
                                <strong>a. Kongres</strong>, <strong>b. Pengurus Pusat</strong>, <strong>c. Konperensi
                                    Cabang</strong>, <strong>d. Badan Pengurus Cabang</strong>.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                                    <div class="font-bold text-[#0f3d64] flex items-center gap-1.5">
                                        <span>🏛️</span> 2. Kongres
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600">• Kongres adalah badan tertinggi dalam
                                        organisasi.</p>
                                    <p class="text-xs sm:text-sm text-slate-600">• Berlangsung sekurang-kurangnya satu
                                        kali dalam dua tahun.</p>
                                    <p class="text-xs sm:text-sm text-slate-600">• Atas panggilan PP atau permintaan
                                        sekurang-kurangnya 2/3 jumlah cabang.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                                    <div class="font-bold text-[#0f3d64] flex items-center gap-1.5">
                                        <span>👥</span> 3. Pengurus Pusat (PP)
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600">• Organisasi ini dipimpin oleh Pengurus
                                        Pusat.</p>
                                    <p class="text-xs sm:text-sm text-slate-600">• Pengurus Pusat dipilih oleh Kongres
                                        untuk masa kerja dua tahun.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                                    <div class="font-bold text-[#0f3d64] flex items-center gap-1.5">
                                        <span>🏛️</span> 4. Konperensi Cabang (Konpercab)
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600">• Badan tertinggi dalam cabang.</p>
                                    <p class="text-xs sm:text-sm text-slate-600">• Berlangsung sekurang-kurangnya satu
                                        kali dalam dua tahun.</p>
                                    <p class="text-xs sm:text-sm text-slate-600">• Atas panggilan BPC atau permintaan
                                        sekurang-kurangnya 2/3 jumlah anggota biasa.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                                    <div class="font-bold text-[#0f3d64] flex items-center gap-1.5">
                                        <span>👥</span> 5. Badan Pengurus Cabang (BPC)
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600">• Cabang dipimpin oleh Badan Pengurus
                                        Cabang.</p>
                                    <p class="text-xs sm:text-sm text-slate-600">• Dipilih oleh Konperensi Cabang untuk
                                        masa kerja satu atau dua tahun.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pasal 8 AD -->
                    <div id="ad-pasal-8" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    8</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Keputusan Persidangan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-8')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
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
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    9</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Perbendaharaan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-9')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                            Perbendaharaan organisasi ini diperoleh dari <strong>iuran anggota</strong>,
                            <strong>sumbangan</strong>, dan <strong>pendapatan lain</strong> yang sesuai dengan asas dan
                            tujuan organisasi.
                        </p>
                    </div>

                    <!-- Pasal 10 AD -->
                    <div id="ad-pasal-10" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    10</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Perubahan Anggaran Dasar</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-10')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
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
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    11</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Pembubaran Organisasi</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-11')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <div class="space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
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
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-[#0f3d64] text-white text-xs font-extrabold">PASAL
                                    12</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Aturan Tambahan</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('ad-pasal-12')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                            Hal-hal yang belum tercakup dalam Anggaran Dasar ini diatur dalam <strong>Anggaran Rumah
                                Tangga (ART)</strong> dan tidak bertentangan dengan Anggaran Dasar.
                        </p>
                    </div>

                </div>

                <!-- ================================================================= -->
                <!-- 2. ANGGARAN RUMAH TANGGA (ART) -->
                <!-- ================================================================= -->
                <div id="section-art" class="tab-pane hidden space-y-6">

                    <!-- Pasal 1 ART -->
                    <div id="art-pasal-1" class="ad-card p-6 sm:p-8" data-searchable>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 1</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">U s a h a</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-1')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <ol
                            class="list-decimal list-inside space-y-3 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <li>Mempertumbuhkan dan memperdalam kehidupan beriman dengan doa, Penelaahan Alkitab,
                                ibadat, pembinaan persekutuan dan tanggung jawab bagi perkembangan, pembaharuan dan
                                keesaan gereja yang am.</li>
                            <li>Membina kemajuan studi dan riset untuk mengikuti dan menguasai ilmu pengetahuan,
                                mewujudkan panggilan Perguruan Tinggi mahasiswa dalam mempersiapkan sarjana dan pemimpin
                                yang ahli dan bertanggungjawab bagi pembangunan dan pembaruan untuk mencapai
                                kesejahteraan materiel dan spirituil.</li>
                            <li>Mempersiapkan pemimpin dan penggerak ahli dan bertanggungjawab terhadap Allah dan
                                manusia di dalam masyarakat, negara, gereja, Perguruan Tinggi dan mahasiswa bagi
                                terwujudnya perdamaian, keadilan, kesejahteraan, kebenaran dan cinta kasih di
                                tengah-tengah manusia dan alam semesta.</li>
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
                                <ul class="space-y-2 text-slate-700 pl-3 border-l-2 border-slate-200">
                                    <li><strong>• Anggota Biasa:</strong> yaitu mahasiswa, warga negara Indonesia, yang
                                        sedang mengikuti kuliah pada suatu Perguruan Tinggi di Indonesia sampai dua
                                        tahun sesudah tidak menjadi mahasiswa lagi.</li>
                                    <li><strong>• Anggota Luar Biasa:</strong> bekas anggota biasa; atau bekas mahasiswa
                                        dan mahasiswa yang tidak termasuk dalam poin di atas.</li>
                                    <li><strong>• Anggota Kehormatan:</strong> yaitu mereka yang berjasa kepada
                                        organisasi.</li>
                                    <li><strong>• Anggota Penyokong:</strong> yaitu mereka yang bersedia membantu
                                        organisasi secara berkala dengan jumlah yang ditentukan oleh Badan Pengurus
                                        Cabang.</li>
                                </ul>
                            </div>
                            <div>
                                <strong class="text-slate-900 block mb-2">2. Penerimaan Anggota :</strong>
                                <ul class="list-disc list-inside space-y-1 text-slate-700 pl-2">
                                    <li>Anggota biasa & luar biasa diterima oleh Badan Pengurus Cabang setelah memenuhi
                                        syarat penerimaan.</li>
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
                            <p><strong>5. Tugas Kongres:</strong> Menetapkan AD/ART; Menilai laporan umum Pengurus
                                Pusat; Menetapkan GBPO & Kebijakan Umum serta Anggaran Pendapatan & Belanja; Memilih
                                Pengurus Pusat.</p>
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
                        <ol
                            class="list-decimal list-inside space-y-2 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <li>Pengurus pusat sekurang-kurangnya terdiri dari 5 orang, yaitu Ketua Umum, Sekretaris
                                Umum, Bendahara Umum, dan 2 orang anggota.</li>
                            <li>Anggota Pengurus Pusat adalah warga negara Indonesia dan beragama Kristen.</li>
                            <li>Dipilih oleh Kongres dengan sistem pemilihan langsung dan/atau pemilihan formatur.</li>
                            <li>Bertanggungjawab kepada Kongres dan mempersiapkan Kongres berikutnya.</li>
                            <li>Ketua Umum dan Sekretaris Umum Pengurus Pusat mewakili organisasi ke dalam dan ke luar.
                            </li>
                            <li>Dapat membentuk dan membubarkan badan pembantu (komisi, panitia khusus) dan mengangkat
                                staf.</li>
                            <li>Pengurus Pusat bersidang sekurang-kurangnya dua kali dalam satu tahun.</li>
                            <li>Pergantian Pengurus Pusat harus disertai dengan serah-terima yang selengkap-lengkapnya.
                            </li>
                        </ol>
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
                                <li>Memilih Badan Pengurus Cabang.</li>
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
                        <ol
                            class="list-decimal list-inside space-y-2 text-slate-700 text-sm sm:text-base leading-relaxed">
                            <li>Badan Pengurus Cabang sekurang-kurangnya terdiri dari 3 orang yaitu Ketua, Sekretaris,
                                dan Bendahara.</li>
                            <li>Anggota Badan Pengurus Cabang adalah warga negara Indonesia dan beragama Kristen.</li>
                            <li>Dipilih oleh Konpercab secara langsung atau formatur, dilantik dan disahkan oleh
                                Pengurus Pusat selambat-lambatnya 2 bulan setelah pemilihan.</li>
                            <li>Bertanggungjawab kepada Konperensi Cabang dan Pengurus Pusat, serta mempersiapkan
                                Konperensi Cabang.</li>
                            <li>Badan Pengurus Cabang bersidang sekurang-kurangnya 1 kali dalam 2 bulan.</li>
                            <li>Penggantian BPC harus disertai serah-terima selengkap-lengkapnya.</li>
                        </ol>
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

                    <!-- Pasal 10 ART: LAMBANG DAN MARS (RICH SHOWCASE) -->
                    <div id="art-pasal-10"
                        class="ad-card p-6 sm:p-10 border-amber-300/80 bg-gradient-to-br from-white via-slate-50 to-amber-50/20"
                        data-searchable>
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-amber-200">
                            <div class="flex items-center gap-3">
                                <span class="p-2 rounded-lg bg-amber-500 text-white text-xl">🛡️</span>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Atribut &
                                        Simbolisme Sakral</span>
                                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">ART PASAL 10: Lambang
                                        dan Mars</h3>
                                </div>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-10')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>

                        <!-- Grid Showcase of Attributes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-700">

                            <!-- Bendera Card -->
                            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-3">
                                <div class="flex items-center gap-2 font-bold text-slate-900 text-base">
                                    <span class="text-xl">🚩</span>
                                    <span>1. Bendera Organisasi</span>
                                </div>
                                <div class="w-full h-10 rounded-lg flex items-center justify-center text-xs font-bold text-white shadow-inner"
                                    style="background-color: #0f3d64;">
                                    Warna Biru Laut (Perbandingan 3 : 2)
                                </div>
                                <ul class="text-xs sm:text-sm space-y-1.5 text-slate-600 list-disc list-inside">
                                    <li>Kain berwarna <strong>biru laut</strong>, empat persegi panjang (rasio 3 : 2).
                                    </li>
                                    <li>Di tengah terdapat gambar lambang GMKI berwarna putih terlihat di kedua sisi.
                                        Perbandingan tinggi lambang dan lebar bendera adalah 1 : 2.</li>
                                    <li><strong>Tingkat Nasional/Regional:</strong> Ukuran 270 x 180 cm.</li>
                                    <li><strong>Tingkat Lokal (Cabang):</strong> Ukuran 135 x 90 cm.</li>
                                    <li>Bila dikibarkan dengan Merah Putih, ukuran harus sama.</li>
                                </ul>
                            </div>

                            <!-- Panji Card -->
                            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-3">
                                <div class="flex items-center gap-2 font-bold text-slate-900 text-base">
                                    <span class="text-xl">🎏</span>
                                    <span>2. Panji Organisasi</span>
                                </div>
                                <div
                                    class="flex h-10 rounded-lg overflow-hidden border border-slate-300 text-[11px] font-bold text-center">
                                    <div class="w-[30%] bg-slate-400 text-white flex items-center justify-center">
                                        Abu-abu (15cm)</div>
                                    <div class="w-[40%] bg-[#0a192f] text-white flex items-center justify-center">Biru
                                        Tua (20cm)</div>
                                    <div class="w-[30%] bg-slate-400 text-white flex items-center justify-center">
                                        Abu-abu (15cm)</div>
                                </div>
                                <ul class="text-xs sm:text-sm space-y-1.5 text-slate-600 list-disc list-inside">
                                    <li>Lebar total 50 cm (15cm abu-abu, 20cm biru tua, 15cm abu-abu).</li>
                                    <li>Tinggi puncak ke sudut tengah 80 cm, sisi tepi 60 cm. Rumbai bawah putih.</li>
                                    <li><strong>Panji Umum:</strong> Tulisan huruf GMKI putih di bawah tanda salib.</li>
                                    <li><strong>Panji Cabang:</strong> Huruf GMKI di atas salib, nama cabang di bawah
                                        salib.</li>
                                </ul>
                            </div>

                            <!-- Topi Baret Card -->
                            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-3">
                                <div class="flex items-center gap-2 font-bold text-slate-900 text-base">
                                    <span class="text-xl">🎖️</span>
                                    <span>3. Topi Organisasi (Baret)</span>
                                </div>
                                <ul class="text-xs sm:text-sm space-y-1.5 text-slate-600 list-disc list-inside">
                                    <li>Berbentuk bundar (baret) berwarna dasar <strong>biru tua
                                            kehitam-hitaman</strong>.</li>
                                    <li>Memanjang dari muka ke belakang dilekatkan kain berwarna
                                        <strong>abu-abu</strong> (lebar depan 8 cm, belakang 6 cm).
                                    </li>
                                    <li>Dikenakan <strong>lencana organisasi tinggi 4 cm</strong> pada bagian muka kain
                                        abu-abu.</li>
                                    <li>Dipergunakan dalam setiap kegiatan organisasi baik umum maupun khusus.</li>
                                </ul>
                            </div>

                            <!-- Lencana & Kordon Card -->
                            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-3">
                                <div class="flex items-center gap-2 font-bold text-slate-900 text-base">
                                    <span class="text-xl">🎗️</span>
                                    <span>4. Lencana & Kordon Kepengurusan</span>
                                </div>
                                <div class="space-y-2 text-xs sm:text-sm text-slate-600">
                                    <p><strong>Lencana:</strong> Perisai segi lima logam. Salib putih logam, dasar cat
                                        biru tua, topi abu-abu, tulisan GMKI, 3 garis vertikal tiap sayap, melingkar
                                        tulisan <em>"Ut Omnes Unum Sint"</em>.</p>
                                    <div class="grid grid-cols-3 gap-1.5 text-center text-[11px] font-bold">
                                        <div class="p-2 bg-slate-100 rounded">Dada: 2,5 cm</div>
                                        <div class="p-2 bg-slate-100 rounded">Baret: 4 cm</div>
                                        <div class="p-2 bg-slate-100 rounded">Kordon: 8 cm</div>
                                    </div>
                                    <p><strong>Pita Kordon (Panjang 120 cm):</strong></p>
                                    <p>• <strong>Pengurus Pusat:</strong> Lebar 7 cm (3,5 cm biru tua & 3,5 cm abu-abu).
                                    </p>
                                    <p>• <strong>BPC:</strong> Lebar 4,5 cm (1,5 cm abu-abu, 1,5 cm biru tua, 1,5 cm
                                        abu-abu).</p>
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
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-xs font-extrabold">ART
                                    PASAL 12</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900">P e n u t u p</h3>
                            </div>
                            <button type="button" onclick="copyPasalLink('art-pasal-12')"
                                class="text-slate-400 hover:text-slate-600 text-xs flex items-center gap-1"
                                title="Salin tautan pasal ini">
                                <span>🔗</span>
                            </button>
                        </div>
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed mb-4">
                            Hal-hal yang belum tercantum dalam Anggaran Rumah Tangga ini diatur oleh Keputusan Kongres,
                            Keputusan Pengurus Pusat, Keputusan Konperensi Cabang, dan Keputusan Badan Pengurus Cabang.
                        </p>
                        <div class="p-3.5 bg-slate-100 rounded-xl text-xs sm:text-sm text-slate-600 font-medium">
                            📌 <em>Anggaran Dasar dan Anggaran Rumah Tangga GMKI ini ditetapkan oleh Kongres Nasional XX
                                GMKI pada tanggal 23 Oktober 1986 di Palangka Raya, Kalimantan Tengah.</em>
                        </div>
                    </div>

                </div>

                <!-- ================================================================= -->
                <!-- 3. PERATURAN ORGANISASI (PO) -->
                <!-- ================================================================= -->
                <div id="section-po" class="tab-pane hidden space-y-6">

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
                    tabBtn.classList.remove('text-slate-600', 'hover:bg-white/60');
                    if (pane) pane.classList.remove('hidden');
                } else {
                    tabBtn.classList.remove('active');
                    tabBtn.classList.add('text-slate-600', 'hover:bg-white/60');
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
                updateQuickChips('');
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

        // Quick Filter Topic Chips
        window.quickFilter = function (term) {
            if (searchInput) {
                searchInput.value = term;
                if (term) {
                    clearBtn.classList.remove('hidden');
                    clearBtn.classList.add('flex');
                } else {
                    clearBtn.classList.add('hidden');
                    clearBtn.classList.remove('flex');
                }
            }
            updateQuickChips(term);
            performSearch(term.toLowerCase());
        };

        function updateQuickChips(term) {
            document.querySelectorAll('.quick-chip').forEach(chip => {
                if (term === '' && chip.textContent.includes('Semua')) {
                    chip.classList.add('active');
                } else if (term !== '' && chip.textContent.toLowerCase().includes(term.toLowerCase())) {
                    chip.classList.add('active');
                } else {
                    chip.classList.remove('active');
                }
            });
        }

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

        // 4. Reading Font Size Controls
        const fontSizes = ['0.875rem', '1rem', '1.125rem'];
        let currentFontIdx = 1;
        const fontBtnDecrease = document.getElementById('fontDecrease');
        const fontBtnReset = document.getElementById('fontReset');
        const fontBtnIncrease = document.getElementById('fontIncrease');

        function updateFontSizeButtons() {
            [fontBtnDecrease, fontBtnReset, fontBtnIncrease].forEach(b => {
                if (b) b.classList.remove('active', 'bg-white', 'text-slate-900', 'shadow-xs');
            });
            if (currentFontIdx === 0 && fontBtnDecrease) {
                fontBtnDecrease.classList.add('active', 'bg-white', 'text-slate-900', 'shadow-xs');
            } else if (currentFontIdx === 1 && fontBtnReset) {
                fontBtnReset.classList.add('active', 'bg-white', 'text-slate-900', 'shadow-xs');
            } else if (currentFontIdx === 2 && fontBtnIncrease) {
                fontBtnIncrease.classList.add('active', 'bg-white', 'text-slate-900', 'shadow-xs');
            }
        }

        if (fontBtnIncrease) {
            fontBtnIncrease.addEventListener('click', function () {
                if (currentFontIdx < fontSizes.length - 1) {
                    currentFontIdx++;
                    applyFontSize();
                }
            });
        }

        if (fontBtnDecrease) {
            fontBtnDecrease.addEventListener('click', function () {
                if (currentFontIdx > 0) {
                    currentFontIdx--;
                    applyFontSize();
                }
            });
        }

        if (fontBtnReset) {
            fontBtnReset.addEventListener('click', function () {
                currentFontIdx = 1;
                applyFontSize();
            });
        }

        function applyFontSize() {
            document.documentElement.style.setProperty('--reading-font-size', fontSizes[currentFontIdx]);
            updateFontSizeButtons();
        }

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