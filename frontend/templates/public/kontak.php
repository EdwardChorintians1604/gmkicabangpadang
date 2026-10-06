<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Page Header with Animate.css -->
    <div class="animate__animated animate__fadeInDown" style="margin-bottom: 3rem; text-align: center;">
        <div class="section-tag">Hubungi Kami</div>
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: var(--primary);">Sekretariat GMKI Cabang Padang</h1>
        <p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 1rem;">
            Sampaikan pertanyaan, masukan, aspirasi pelayanan, atau informasi pendaftaran kaderisasi kepada BPC GMKI Cabang Padang.
        </p>
    </div>

    <!-- 2 Columns: Contact Details & Message Form -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="margin-bottom: 2.5rem;">
        <!-- Contact Info Cards -->
        <div class="animate__animated animate__fadeInLeft">
            <div class="card" style="margin-bottom: 1.5rem; box-shadow: var(--shadow-md);">
                <div class="card-body" style="padding: 2rem;">
                    <div class="flex items-center gap-2" style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                        <img src="<?= asset('images/GMKI-Logos.png') ?>" alt="Logo GMKI" style="width: 32px; height: 32px; object-fit: contain;">
                        <h3 style="font-size: 1.35rem; color: var(--primary); margin: 0;">Pusat Pelayanan & Sekretariat</h3>
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
                                <?= e($profile['email'] ?? 'sekretariat@gmkicabangpadang.or.id') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media Box -->
            <div class="card" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border: 1px solid #bfdbfe;">
                <div class="card-body" style="padding: 1.5rem;">
                    <h4 style="font-size: 1.05rem; color: var(--primary); margin-bottom: 0.5rem; font-weight: 700;">Media Sosial & Komunikasi Resmi</h4>
                    <p class="text-muted" style="font-size: 0.875rem; margin-bottom: 1rem;">
                        Ikuti perkembangan agenda, dokumentasi kegiatan, dan warta terbaru melalui kanal resmi kami:
                    </p>
                    <div class="flex gap-2 flex-wrap">
                        <span class="badge badge-primary flex items-center gap-1" style="padding: 6px 12px;">
                            <span>📷 Instagram:</span> <strong><?= e($profile['instagram'] ?? '@gmkicabangpadang') ?></strong>
                        </span>
                        <span class="badge badge-warning flex items-center gap-1" style="padding: 6px 12px;">
                            <span>▶️ YouTube:</span> <strong><?= e($profile['youtube'] ?? 'GMKI Cabang Padang Official') ?></strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="animate__animated animate__fadeInRight">
            <div class="card" style="box-shadow: var(--shadow-md);">
                <div class="card-body" style="padding: 2rem;">
                    <h3 style="font-size: 1.35rem; color: var(--primary); margin-bottom: 0.5rem;">Kirim Pesan atau Aspirasi</h3>
                    <p class="text-muted" style="font-size: 0.875rem; margin-bottom: 1.5rem;">
                        Silakan isi formulir berikut. Pengurus BPC GMKI Cabang Padang akan merespons pesan Anda secepatnya.
                    </p>

                    <form action="/kontak" method="POST" data-validate>
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label class="form-label" for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Yeremia Pratama" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Alamat Email atau Nomor WhatsApp</label>
                            <input type="text" id="email" name="email" class="form-control" placeholder="nama@email.com atau 08123456789" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="pesan">Pesan, Masukan, atau Aspirasi</label>
                            <textarea id="pesan" name="pesan" class="form-control" rows="5" placeholder="Tuliskan pesan atau aspirasi Anda di sini..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-weight: 700; font-size: 0.95rem;">
                            Kirim Pesan ke BPC GMKI Padang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Leaflet Map Card -->
    <div class="card animate__animated animate__fadeInUp" style="overflow: hidden; border: 1px solid var(--border-color); box-shadow: var(--shadow-md);">
        <div class="card-header flex justify-between items-center" style="background: var(--bg-subtle); padding: 1.25rem 1.5rem;">
            <div class="flex items-center gap-2">
                <span style="font-size: 1.25rem;">🗺️</span>
                <strong style="color: var(--primary); font-size: 1.05rem;">Peta Interaktif Lokasi Sekretariat (Leaflet & OpenStreetMap)</strong>
            </div>
            <span class="badge badge-info">Belakang Tangsi, Padang Barat</span>
        </div>
        <div id="mapSekretariat" style="height: 380px; width: 100%; z-index: 10;"></div>
    </div>
</div>

<!-- Leaflet Map Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
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
