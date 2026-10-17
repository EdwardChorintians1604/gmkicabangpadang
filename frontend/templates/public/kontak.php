<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Page Header with Animate.css -->
    <div class="animate__animated animate__fadeInDown" style="margin-bottom: 3rem; text-align: center;">
        <div class="section-tag">Hubungi Kami</div>
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: var(--primary);">Sekretariat GMKI
            Cabang Padang</h1>
        <p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 1rem;">
            Sampaikan pertanyaan, masukan, aspirasi pelayanan, atau informasi pendaftaran kaderisasi kepada BPC GMKI
            Cabang Padang.
        </p>
    </div>

    <!-- 2 Columns: Contact Details & Message Form -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="margin-bottom: 2.5rem;">
        <!-- Contact Info Cards -->
        <div class="animate__animated animate__fadeInLeft">
            <div class="card" style="margin-bottom: 1.5rem; box-shadow: var(--shadow-md);">
                <div class="card-body" style="padding: 2rem;">
                    <div class="flex items-center gap-2"
                        style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                        <img src="<?= asset('images/GMKI-Logos.png') ?>" alt="Logo GMKI"
                            style="width: 32px; height: 32px; object-fit: contain;">
                        <h3 style="font-size: 1.35rem; color: var(--primary); margin: 0;">Pusat Pelayanan & Sekretariat
                        </h3>
                    </div>

                    <div class="flex items-start gap-3" style="margin-bottom: 1.25rem;">
                        <span style="font-size: 1.5rem;">📍</span>
                        <div>
                            <strong>Alamat Sekretariat:</strong>
                            <div class="text-muted" style="font-size: 0.95rem; margin-top: 0.25rem; line-height: 1.5;">
                                <?= nl2br(e($profile['alamat_sekretariat'] ?? 'Jl. Tanah Beroyo No.2c, Belakang Tangsi, Kec. Padang Bar., kodya padang, Sumatera Barat')) ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3" style="margin-bottom: 1.25rem;">
                        <span style="font-size: 1.5rem;">📞</span>
                        <div>
                            <strong>Telepon / WhatsApp Resmi:</strong>
                            <div class="text-muted" style="font-size: 0.95rem; margin-top: 0.25rem;">
                                <?= e($profile['telepon'] ?? '+62 812-3456-7890') ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3" style="margin-bottom: 1.25rem;">
                        <span style="font-size: 1.5rem;">✉️</span>
                        <div>
                            <strong>Email Resmi:</strong>
                            <div class="text-muted" style="font-size: 0.95rem; margin-top: 0.25rem;">
                                <a href="mailto:gmkipadang2425@gmail.com" style="color: inherit; text-decoration: none;">gmkipadang2425@gmail.com</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media Box -->
            <?php
            $igHandle = $profile['instagram'] ?? '@gmki_padang';
            $igUrl = !empty($profile['instagram']) && str_starts_with($profile['instagram'], 'http')
                ? $profile['instagram']
                : "https://www.instagram.com/gmki_padang/";

            $ytHandle = $profile['youtube'] ?? 'GMKI Padang';
            $ytUrl = !empty($profile['youtube']) && str_starts_with($profile['youtube'], 'http')
                ? $profile['youtube']
                : "https://youtube.com/@gmkipadang?si=q3Ljh6ZDhP_ISBDc";

            $ttHandle = $profile['tiktok'] ?? '@gmki.padang';
            $ttUrl = !empty($profile['tiktok']) && str_starts_with($profile['tiktok'], 'http')
                ? $profile['tiktok']
                : "https://www.tiktok.com/@gmki.padang";
            ?>
            <div class="card"
                style="box-shadow: var(--shadow-md); border: 1px solid var(--border-color); overflow: hidden;">
                <div class="card-body" style="padding: 1.75rem;">
                    <div class="flex items-center gap-2"
                        style="margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.85rem;">
                        <span style="font-size: 1.35rem;">🌐</span>
                        <div>
                            <h4 style="font-size: 1.15rem; color: var(--primary); margin: 0; font-weight: 700;">
                                Media Sosial & Komunikasi Resmi
                            </h4>
                            <p class="text-muted" style="font-size: 0.825rem; margin: 0.2rem 0 0;">
                                Ikuti agenda, dokumentasi, dan warta terbaru melalui kanal resmi kami:
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <!-- Instagram -->
                        <a href="<?= e($igUrl) ?>" target="_blank" rel="noopener noreferrer"
                            class="group flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-white hover:border-pink-300 hover:bg-pink-50/40 hover:shadow-md transition-all duration-200"
                            style="text-decoration: none;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                                    style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.13-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                </div>
                                <div>
                                    <div
                                        class="text-sm font-bold text-slate-800 group-hover:text-pink-600 transition-colors">
                                        Instagram</div>
                                    <div class="text-xs text-slate-500 font-medium">
                                        <?= e($profile['instagram'] ?? '@gmki_padang') ?>
                                    </div>
                                </div>
                            </div>
                            <span
                                class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 group-hover:bg-pink-100 group-hover:text-pink-700 transition-colors flex items-center gap-1">
                                Kunjungi
                                <svg class="w-3 h-3 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </span>
                        </a>

                        <!-- YouTube -->
                        <a href="<?= e($ytUrl) ?>" target="_blank" rel="noopener noreferrer"
                            class="group flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-white hover:border-red-300 hover:bg-red-50/40 hover:shadow-md transition-all duration-200"
                            style="text-decoration: none;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                                    style="background-color: #ff0000;">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                    </svg>
                                </div>
                                <div>
                                    <div
                                        class="text-sm font-bold text-slate-800 group-hover:text-red-600 transition-colors">
                                        YouTube</div>
                                    <div class="text-xs text-slate-500 font-medium">
                                        <?= e($profile['youtube'] ?? 'GMKI Padang') ?>
                                    </div>
                                </div>
                            </div>
                            <span
                                class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 group-hover:bg-red-100 group-hover:text-red-700 transition-colors flex items-center gap-1">
                                Kunjungi
                                <svg class="w-3 h-3 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </span>
                        </a>

                        <!-- TikTok -->
                        <a href="<?= e($ttUrl) ?>" target="_blank" rel="noopener noreferrer"
                            class="group flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-white hover:border-slate-400 hover:bg-slate-50 hover:shadow-md transition-all duration-200"
                            style="text-decoration: none;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                                    style="background-color: #010101;">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298 0 .591.044.87.13V9.4a6.33 6.33 0 0 0-.87-.06A6.34 6.34 0 0 0 3.15 15.7 6.34 6.34 0 0 0 9.49 22a6.34 6.34 0 0 0 6.34-6.33V9.22c1.47.93 3.19 1.47 5.04 1.47V7.24a4.84 4.84 0 0 1-1.28-.55z" />
                                    </svg>
                                </div>
                                <div>
                                    <div
                                        class="text-sm font-bold text-slate-800 group-hover:text-slate-900 transition-colors">
                                        TikTok</div>
                                    <div class="text-xs text-slate-500 font-medium">
                                        <?= e($profile['tiktok'] ?? '@gmki.padang') ?>
                                    </div>
                                </div>
                            </div>
                            <span
                                class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 group-hover:bg-slate-200 group-hover:text-slate-900 transition-colors flex items-center gap-1">
                                Kunjungi
                                <svg class="w-3 h-3 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="animate__animated animate__fadeInRight">
            <div class="card" style="box-shadow: var(--shadow-md);">
                <div class="card-body" style="padding: 2rem;">
                    <h3 style="font-size: 1.35rem; color: var(--primary); margin-bottom: 0.5rem;">Kirim Pesan atau
                        Aspirasi</h3>
                    <p class="text-muted" style="font-size: 0.875rem; margin-bottom: 1.5rem;">
                        Silakan isi formulir berikut. Pengurus BPC GMKI Cabang Padang akan merespons pesan Anda
                        secepatnya.
                    </p>

                    <?php
                    $rawPhone = $profile['telepon'] ?? '6281234567890';
                    $cleanWa = preg_replace('/[^0-9]/', '', $rawPhone);
                    if (str_starts_with($cleanWa, '0')) {
                        $cleanWa = '62' . substr($cleanWa, 1);
                    }
                    $waNumber = !empty($cleanWa) ? $cleanWa : '6281234567890';
                    ?>

                    <form id="formKontakPublic" action="/kontak" method="POST" novalidate>
                        <?= csrf_field() ?>

                        <div id="contactFormAlert" style="display: none; margin-bottom: 1rem;" class="animate__animated animate__fadeIn"></div>

                        <div class="form-group">
                            <label class="form-label" for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" class="form-control"
                                placeholder="Contoh: Yeremia Pratama" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Alamat Email atau Nomor WhatsApp</label>
                            <input type="text" id="email" name="email" class="form-control"
                                placeholder="nama@email.com atau 08123456789" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="pesan">Pesan, Masukan, atau Aspirasi</label>
                            <textarea id="pesan" name="pesan" class="form-control" rows="5"
                                placeholder="Tuliskan pesan atau aspirasi Anda di sini..." required></textarea>
                        </div>

                        <button type="submit" id="btnSubmitContact" class="btn btn-primary"
                            style="width: 100%; padding: 0.85rem; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: var(--shadow-sm); transition: transform 0.15s ease;">
                            <span style="font-size: 1.15rem;">✉️</span>
                            <span>Kirim Pesan ke BPC GMKI Padang</span>
                        </button>

                        <!-- Pilihan Kirim Cepat / Direct Integration -->
                        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px dashed var(--border-color); text-align: center;">
                            <div class="text-muted" style="font-size: 0.8rem; font-weight: 600; margin-bottom: 0.65rem;">
                                Terkoneksi langsung ke email resmi:
                            </div>
                            <div class="grid grid-cols-2 gap-2" style="font-size: 0.825rem;">
                                <button type="button" id="btnDirectGmail" class="btn btn-outline flex items-center justify-center gap-1.5"
                                    style="padding: 0.55rem 0.75rem; border-color: #fca5a5; color: #dc2626; background: #fff5f5; font-weight: 600; cursor: pointer;">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/>
                                    </svg>
                                    <span>Gmail Web</span>
                                </button>
                                <button type="button" id="btnDirectMailto" class="btn btn-outline flex items-center justify-center gap-1.5"
                                    style="padding: 0.55rem 0.75rem; border-color: #93c5fd; color: #2563eb; background: #eff6ff; font-weight: 600; cursor: pointer;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Aplikasi Mail</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Leaflet Map Card -->
    <div class="card animate__animated animate__fadeInUp"
        style="overflow: hidden; border: 1px solid var(--border-color); box-shadow: var(--shadow-md);">
        <div class="card-header flex justify-between items-center"
            style="background: var(--bg-subtle); padding: 1.25rem 1.5rem;">
            <div class="flex items-center gap-2">
                <span style="font-size: 1.25rem;">🗺️</span>
                <strong style="color: var(--primary); font-size: 1.05rem;">Peta Interaktif Lokasi Sekretariat (Leaflet &
                    OpenStreetMap)</strong>
            </div>
            <span class="badge badge-info">Belakang Tangsi, Padang Barat</span>
        </div>
        <div id="mapSekretariat" style="height: 380px; width: 100%; z-index: 10;"></div>
    </div>

    <!-- Modal Konfirmasi & Pilihan Pengiriman Email -->
    <div id="emailSenderModal" style="display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 1rem; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px);">
        <div class="animate__animated animate__zoomIn animate__faster" style="background: #ffffff; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 520px; width: 100%; overflow: hidden; border: 1px solid #e2e8f0;">
            <!-- Modal Header -->
            <div style="background: linear-gradient(135deg, #0f3d64 0%, #1e5687 100%); padding: 1.25rem 1.5rem; color: #ffffff; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.6rem;">✉️</span>
                    <div>
                        <h4 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #ffffff;">Kirim Pesan ke Email Resmi</h4>
                        <p style="margin: 0.15rem 0 0; font-size: 0.8rem; color: #cbd5e1;">Tujuan: <strong style="color: #fde047;">gmkipadang2425@gmail.com</strong></p>
                    </div>
                </div>
                <button type="button" id="btnCloseEmailModal" aria-label="Tutup" style="background: transparent; border: none; color: #ffffff; font-size: 1.6rem; cursor: pointer; line-height: 1; padding: 0.25rem;">&times;</button>
            </div>

            <!-- Modal Body -->
            <div style="padding: 1.5rem;">
                <!-- Summary Card -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.85rem;">
                    <div style="font-weight: 700; color: #0f3d64; margin-bottom: 0.25rem;" id="modalPreviewSubject"></div>
                    <div class="text-muted" style="font-size: 0.8rem; margin-bottom: 0.5rem;" id="modalPreviewSender"></div>
                    <div style="white-space: pre-wrap; word-break: break-word; color: #334155; max-height: 100px; overflow-y: auto; background: #ffffff; padding: 0.65rem 0.75rem; border-radius: 0.5rem; border: 1px solid #cbd5e1; font-size: 0.825rem; line-height: 1.45;" id="modalPreviewPesan"></div>
                </div>

                <div style="font-weight: 700; font-size: 0.875rem; margin-bottom: 0.75rem; color: #0f172a;">
                    Pilih Aplikasi untuk Mengirim Pesan:
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    <!-- Option 1: Gmail Web -->
                    <button type="button" id="btnModalGmail" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-radius: 0.75rem; border: 1px solid #fca5a5; background: #fff5f5; text-align: left; cursor: pointer; transition: all 0.2s;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.65rem; background: #dc2626; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);">
                                <svg style="width: 1.25rem; height: 1.25rem;" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 0.925rem; color: #991b1b;">Buka di Gmail Web (Rekomendasi)</div>
                                <div style="font-size: 0.75rem; color: #64748b;">Membuka Google Mail langsung di browser dengan draf siap kirim</div>
                            </div>
                        </div>
                        <span style="font-size: 0.75rem; background: #dc2626; color: white; padding: 3px 10px; border-radius: 9999px; font-weight: 700; white-space: nowrap;">Buka ↗</span>
                    </button>

                    <!-- Option 2: Default Mail Client (Mailto) -->
                    <button type="button" id="btnModalMailto" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-radius: 0.75rem; border: 1px solid #93c5fd; background: #eff6ff; text-align: left; cursor: pointer; transition: all 0.2s;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.65rem; background: #2563eb; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);">
                                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 0.925rem; color: #1e40af;">Aplikasi Email Perangkat</div>
                                <div style="font-size: 0.75rem; color: #64748b;">Membuka Outlook, Thunderbird, atau Apple Mail</div>
                            </div>
                        </div>
                        <span style="font-size: 0.75rem; background: #2563eb; color: white; padding: 3px 10px; border-radius: 9999px; font-weight: 700; white-space: nowrap;">Buka ↗</span>
                    </button>

                    <!-- Option 3: WhatsApp Resmi -->
                    <button type="button" id="btnModalWhatsApp" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-radius: 0.75rem; border: 1px solid #a7f3d0; background: #f0fdf4; text-align: left; cursor: pointer; transition: all 0.2s;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.65rem; background: #059669; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);">
                                <svg style="width: 1.25rem; height: 1.25rem;" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.076-2.121-.497-1.809-.728-2.957-2.58-3.047-2.7-.091-.121-.735-.977-.735-1.86 0-.884.464-1.319.629-1.498.165-.178.36-.223.48-.223.12 0 .24.002.345.006.11.004.258-.042.404.308.15.361.51 1.246.555 1.337.045.09.075.196.015.316-.06.12-.09.196-.18.301-.09.106-.19.236-.271.317-.091.091-.186.19-.08.372.105.182.467.771 1.001 1.247.688.613 1.269.803 1.45.894.18.09.286.076.391-.045.105-.121.45-.526.57-.706.12-.18.24-.15.405-.09.165.06 1.05.496 1.23.586.18.09.3.136.345.211.045.075.045.436-.099.841z"/>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 0.925rem; color: #065f46;">Kirim via WhatsApp Resmi</div>
                                <div style="font-size: 0.75rem; color: #64748b;">Langsung kirim pesan ke kontak WhatsApp BPC GMKI Padang</div>
                            </div>
                        </div>
                        <span style="font-size: 0.75rem; background: #059669; color: white; padding: 3px 10px; border-radius: 9999px; font-weight: 700; white-space: nowrap;">Chat ↗</span>
                    </button>
                </div>

                <div style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center; padding-top: 0.75rem; border-top: 1px solid #f1f5f9;">
                    <button type="button" id="btnCopyMessage" style="background: none; border: none; cursor: pointer; color: #475569; font-size: 0.775rem; font-weight: 600; display: flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.5rem; border-radius: 0.35rem;">
                        📋 <span id="copyBtnText">Salin Teks Pesan</span>
                    </button>
                    <span style="font-size: 0.75rem; color: #059669; font-weight: 600;">
                        ✓ Pesan tercatat di sistem
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet Map & Interactive Contact Email Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Leaflet Map Initialization ---
        if (typeof L !== 'undefined') {
            const lat = -0.958567;
            const lng = 100.357547;

            const map = L.map('mapSekretariat', {
                center: [lat, lng],
                zoom: 17,
                scrollWheelZoom: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> kontributor | GMKI Padang',
                maxZoom: 19
            }).addTo(map);

            const marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(`
                <div style="text-align: center; font-family: sans-serif; padding: 6px;">
                    <strong style="color: #0f3d64; font-size: 14px;">Sekretariat BPC GMKI Cabang Padang</strong>
                    <p style="margin: 4px 0 6px; font-size: 12px; color: #475569;">Jl. Tanah Beroyo No.2c, Belakang Tangsi, Kec. Padang Bar., kodya padang, Sumatera Barat</p>
                    <a href="https://maps.app.goo.gl/qLeJStyXJXK9PHxh9" target="_blank" rel="noopener noreferrer" style="display: inline-block; background: #0f3d64; color: #ffffff; padding: 4px 10px; border-radius: 4px; font-size: 11px; text-decoration: none; font-weight: 600; margin-bottom: 6px;">
                        Buka di Google Maps ↗
                    </a><br>
                    <span style="font-size: 11px; color: #d97706; font-weight: bold;">"Ut Omnes Unum Sint"</span>
                </div>
            `).openPopup();
        }

        // --- 2. Interactive Email Sender Integration ---
        const form = document.getElementById('formKontakPublic');
        if (!form) return;

        const modal = document.getElementById('emailSenderModal');
        const btnCloseModal = document.getElementById('btnCloseEmailModal');
        const alertBox = document.getElementById('contactFormAlert');
        const namaInput = document.getElementById('nama');
        const emailInput = document.getElementById('email');
        const pesanInput = document.getElementById('pesan');

        const OFFICIAL_EMAIL = 'gmkipadang2425@gmail.com';
        const OFFICIAL_WA = '<?= e($waNumber) ?>';

        function buildMessageData() {
            const nama = (namaInput.value || '').trim();
            const contact = (emailInput.value || '').trim();
            const pesan = (pesanInput.value || '').trim();
            const waktu = new Date().toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' });

            const subject = `[Pesan Kontak GMKI Padang] dari ${nama}`;
            const body = `Yth. Pengurus BPC GMKI Cabang Padang,\n\nBerikut pesan/aspirasi yang dikirim melalui formulir kontak Portal GMKI Cabang Padang:\n\n• Nama Pengirim: ${nama}\n• Kontak (Email/WhatsApp): ${contact}\n• Waktu Pengiriman: ${waktu}\n\n--- PESAN / ASPIRASI ---\n${pesan}\n\n--------------------------------------------------\nPesan ini dikirim melalui portal resmi GMKI Cabang Padang (${OFFICIAL_EMAIL}).`;

            const waText = `*Halo BPC GMKI Cabang Padang,*\n\nSaya ingin menyampaikan pesan/aspirasi via Website GMKI:\n\n*Nama:* ${nama}\n*Kontak:* ${contact}\n\n*Pesan:*\n${pesan}`;

            return { nama, contact, pesan, subject, body, waText };
        }

        function validateForm() {
            let isValid = true;
            form.querySelectorAll('.validation-error-msg').forEach(el => el.remove());

            [namaInput, emailInput, pesanInput].forEach(inp => {
                inp.classList.remove('is-invalid');
                inp.style.borderColor = '';
                if (!inp.value.trim()) {
                    isValid = false;
                    inp.classList.add('is-invalid');
                    inp.style.borderColor = '#ef4444';
                    const err = document.createElement('div');
                    err.className = 'validation-error-msg';
                    err.style.color = '#ef4444';
                    err.style.fontSize = '0.75rem';
                    err.style.marginTop = '0.25rem';
                    err.textContent = 'Bagian ini tidak boleh kosong.';
                    inp.parentNode.appendChild(err);
                }
            });
            return isValid;
        }

        function recordAuditAsync() {
            try {
                const formData = new FormData(form);
                formData.append('ajax', '1');
                fetch('/kontak', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).catch(err => console.log('Audit notice:', err));
            } catch (e) {
                console.warn('Silent log capture:', e);
            }
        }

        function showSuccessNotification(appText) {
            if (!alertBox) return;
            alertBox.innerHTML = `
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 0.85rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.65rem;">
                    <span style="font-size: 1.25rem;">✅</span>
                    <div>
                        <strong>Terkoneksi ke Email Resmi!</strong><br>
                        <span>${appText} dengan penerima <strong>${OFFICIAL_EMAIL}</strong>. Pastikan Anda menekan tombol Kirim (Send) pada aplikasi email Anda.</span>
                    </div>
                </div>
            `;
            alertBox.style.display = 'block';
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function openGmail(data) {
            const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(OFFICIAL_EMAIL)}&su=${encodeURIComponent(data.subject)}&body=${encodeURIComponent(data.body)}`;
            window.open(gmailUrl, '_blank', 'noopener,noreferrer');
            showSuccessNotification('Draft Gmail Web berhasil disiapkan dan dibuka');
        }

        function openMailto(data) {
            const mailtoUrl = `mailto:${encodeURIComponent(OFFICIAL_EMAIL)}?subject=${encodeURIComponent(data.subject)}&body=${encodeURIComponent(data.body)}`;
            window.location.href = mailtoUrl;
            showSuccessNotification('Aplikasi email perangkat Anda telah dibuka');
        }

        function openWhatsApp(data) {
            const waUrl = `https://api.whatsapp.com/send?phone=${OFFICIAL_WA}&text=${encodeURIComponent(data.waText)}`;
            window.open(waUrl, '_blank', 'noopener,noreferrer');
            showSuccessNotification('WhatsApp resmi sekretariat telah dibuka');
        }

        function showModal(data) {
            document.getElementById('modalPreviewSubject').textContent = data.subject;
            document.getElementById('modalPreviewSender').textContent = `Pengirim: ${data.nama} • ${data.contact}`;
            document.getElementById('modalPreviewPesan').textContent = data.pesan;
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        // Submit form utama -> Buka Modal Pilihan Pengiriman
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validateForm()) return;

            const data = buildMessageData();
            recordAuditAsync();
            showModal(data);
        });

        // Direct Quick Buttons pada Form
        document.getElementById('btnDirectGmail')?.addEventListener('click', function () {
            if (!validateForm()) return;
            const data = buildMessageData();
            recordAuditAsync();
            openGmail(data);
        });

        document.getElementById('btnDirectMailto')?.addEventListener('click', function () {
            if (!validateForm()) return;
            const data = buildMessageData();
            recordAuditAsync();
            openMailto(data);
        });

        // Tombol di dalam Modal
        document.getElementById('btnModalGmail')?.addEventListener('click', function () {
            const data = buildMessageData();
            closeModal();
            openGmail(data);
        });

        document.getElementById('btnModalMailto')?.addEventListener('click', function () {
            const data = buildMessageData();
            closeModal();
            openMailto(data);
        });

        document.getElementById('btnModalWhatsApp')?.addEventListener('click', function () {
            const data = buildMessageData();
            closeModal();
            openWhatsApp(data);
        });

        btnCloseModal?.addEventListener('click', closeModal);
        modal?.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });

        // Salin Teks
        document.getElementById('btnCopyMessage')?.addEventListener('click', function () {
            const data = buildMessageData();
            const textToCopy = `${data.subject}\n\n${data.body}`;
            navigator.clipboard.writeText(textToCopy).then(() => {
                const copyText = document.getElementById('copyBtnText');
                copyText.textContent = 'Pesan Berhasil Disalin! ✓';
                setTimeout(() => { copyText.textContent = 'Salin Teks Pesan'; }, 2500);
            }).catch(() => {
                alert('Teks pesan siap dikirim:\n\n' + textToCopy);
            });
        });
    });
</script>