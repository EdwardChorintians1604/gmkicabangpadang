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
                                <?= e($profile['email'] ?? 'gmkipadang2425@gmail.com') ?>
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

                    <form action="/kontak" method="POST" data-validate>
                        <?= csrf_field() ?>

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

                        <button type="submit" class="btn btn-primary"
                            style="width: 100%; padding: 0.85rem; font-weight: 700; font-size: 0.95rem;">
                            Kirim Pesan ke BPC GMKI Padang
                        </button>
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
</div>

<!-- Leaflet Map Initialization Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof L === 'undefined') {
            console.warn('Leaflet belum dimuat.');
            return;
        }

        // Koordinat Sekretariat BPC GMKI Cabang Padang (Jl. Tanah Beroyo No.2c, Belakang Tangsi, Kec. Padang Barat)
        const lat = -0.958567;
        const lng = 100.357547;

        const map = L.map('mapSekretariat', {
            center: [lat, lng],
            zoom: 17,
            scrollWheelZoom: false // Mencegah scroll halaman terganggu
        });

        // Tile Layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> kontributor | GMKI Padang',
            maxZoom: 19
        }).addTo(map);

        // Marker dengan Popup Interaktif
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
    });
</script>