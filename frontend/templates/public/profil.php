<div class="container py-8 sm:py-12">
    <!-- Page Header with Animate.css -->
    <div class="animate__animated animate__fadeInDown mb-12 text-center">
        <div class="section-tag uppercase text-amber-600 font-bold tracking-wider text-xs mb-2">Mengenal Lebih Dekat</div>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">Profil & Sejarah Cabang</h1>
        <p class="text-slate-600 max-w-2xl mx-auto text-sm sm:text-base font-medium">
            <?= e($profile['slogan'] ?? 'Ut Omnes Unum Sint — Supaya mereka semua menjadi satu') ?>
        </p>
    </div>

    <!-- Sejarah Card -->
    <div class="card rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 mb-10 overflow-hidden bg-white animate__animated animate__fadeInUp">
        <div class="card-body p-6 sm:p-10">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <span class="text-3xl">📜</span>
                <h2 class="text-2xl font-bold text-slate-900">Sejarah GMKI Cabang Padang</h2>
            </div>
            <div class="text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
                <?= nl2br(e($profile['sejarah'] ?? 'Sejarah berdirinya GMKI Cabang Padang.')) ?>
            </div>
        </div>
    </div>

    <!-- Visi & Misi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 animate__animated animate__fadeInUp">
        <div class="card rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 bg-white" style="border-top: 5px solid var(--primary);">
            <div class="card-body p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🎯</span>
                    <h3 class="text-xl font-bold text-slate-900" style="color: var(--primary);">Visi Organisasi</h3>
                </div>
                <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                    <?= nl2br(e($profile['visi'] ?? '')) ?>
                </p>
            </div>
        </div>

        <div class="card rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 bg-white" style="border-top: 5px solid var(--secondary);">
            <div class="card-body p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">⚡</span>
                    <h3 class="text-xl font-bold text-slate-900" style="color: var(--secondary);">Misi Organisasi</h3>
                </div>
                <div class="text-slate-700 text-sm sm:text-base leading-relaxed">
                    <?= nl2br(e($profile['misi'] ?? '')) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tri Panji & Panca Kegiatan Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 animate__animated animate__fadeInUp">
        <div class="card rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 bg-white">
            <div class="card-body p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🚩</span>
                    <h3 class="text-xl font-bold text-slate-900">Tri Panji GMKI</h3>
                </div>
                <div class="text-slate-700 text-sm sm:text-base leading-relaxed font-medium">
                    <?= nl2br(e($profile['tri_panji'] ?? "1. Tinggi Iman\n2. Tinggi Ilmu\n3. Tinggi Pengabdian")) ?>
                </div>
            </div>
        </div>

        <div class="card rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 bg-white">
            <div class="card-body p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">⭐</span>
                    <h3 class="text-xl font-bold text-slate-900">Panca Kegiatan GMKI</h3>
                </div>
                <div class="text-slate-700 text-sm sm:text-base leading-relaxed font-medium">
                    <?= nl2br(e($profile['panca_kegiatan'] ?? "1. Berdoa / Beribadah\n2. Belajar\n3. Bersaksi\n4. Bersosialisasi\n5. Berjuang")) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiga Medan Layan -->
    <div class="card rounded-3xl shadow-xl overflow-hidden mb-12 animate__animated animate__fadeInUp" style="background: linear-gradient(135deg, #071e33 0%, #0f3d64 60%, #1e5687 100%); color: #ffffff;">
        <div class="card-body p-6 sm:p-10">
            <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-300 text-center mb-8">Tiga Medan Layan GMKI</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors">
                    <div class="text-4xl mb-3">⛪</div>
                    <h4 class="text-lg font-bold text-white mb-2">Gereja</h4>
                    <p class="text-slate-200 text-xs sm:text-sm leading-relaxed">Menjadi pelayan oikoumene yang mempererat persekutuan tubuh Kristus.</p>
                </div>
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors">
                    <div class="text-4xl mb-3">🏫</div>
                    <h4 class="text-lg font-bold text-white mb-2">Perguruan Tinggi</h4>
                    <p class="text-slate-200 text-xs sm:text-sm leading-relaxed">Wadah kader intelektual kritis, berprestasi, dan menjunjung kebenaran ilmiah.</p>
                </div>
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors">
                    <div class="text-4xl mb-3">🌏</div>
                    <h4 class="text-lg font-bold text-white mb-2">Masyarakat</h4>
                    <p class="text-slate-200 text-xs sm:text-sm leading-relaxed">Garam dan terang dunia yang memperjuangkan keadilan sosial dan kemanusiaan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lokasi & Peta Leaflet Pelayanan GMKI Padang -->
    <div class="card rounded-2xl shadow-md overflow-hidden bg-white border border-slate-200 animate__animated animate__fadeInUp">
        <div class="p-6 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xl">📍</span>
                    <h3 class="text-lg font-bold text-slate-900">Pusat Sekretariat Cabang (Peta Interaktif Leaflet)</h3>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Tarandam, Kec. Padang Timur, Kota Padang, Sumatera Barat
                </p>
            </div>
            <a href="/kontak" class="btn btn-primary btn-sm flex items-center gap-1">
                <span>Rute & Kontak Lengkap</span>
                &rarr;
            </a>
        </div>
        <div id="mapProfil" style="height: 320px; width: 100%; z-index: 10;"></div>
    </div>
</div>

<!-- Script Leaflet Profil -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof L === 'undefined') return;

    const lat = -0.9471;
    const lng = 100.3686;

    const mapProfil = L.map('mapProfil', {
        center: [lat, lng],
        zoom: 15,
        scrollWheelZoom: false
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap | GMKI Cabang Padang',
        maxZoom: 19
    }).addTo(mapProfil);

    const marker = L.marker([lat, lng]).addTo(mapProfil);
    marker.bindPopup(`
        <div style="text-align: center; font-family: sans-serif; padding: 4px;">
            <strong style="color: #0f3d64;">BPC GMKI Cabang Padang</strong><br>
            <span style="font-size: 11px; color: #64748b;">Sekretariat Tarandam, Padang Timur</span>
        </div>
    `).openPopup();
});
</script>
