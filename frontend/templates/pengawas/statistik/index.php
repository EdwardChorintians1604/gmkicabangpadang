<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div class="flex items-center gap-2" style="margin-bottom: 0.35rem;">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin: 0;">Analitik & Statistik Civitas</h1>
            <span class="badge badge-warning">BACA-SAJA</span>
        </div>
        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
            Pemantauan visual demografi kader, sebaran kampus perguruan tinggi, komposisi gender, dan tren kaderisasi GMKI Cabang Padang.
        </p>
    </div>
    <div class="flex gap-2">
        <a href="/pengawas/civitas/export" class="btn btn-secondary btn-sm flex items-center gap-1">
            <?= svg_icon('download', 14) ?>
            <span>Unduh Laporan Lengkap (CSV)</span>
        </a>
        <button onclick="window.print()" class="btn btn-outline btn-sm flex items-center gap-1">
            <?= svg_icon('printer', 14) ?>
            <span>Cetak Grafik</span>
        </button>
    </div>
</div>

<!-- Metrics Cards Using Stat-Card Partial -->
<div class="metrics-grid">
    <?= partial('stat-card', [
        'title' => 'Total Civitas Terdata',
        'value' => (int)($summary['total_civitas'] ?? 0),
        'icon' => 'users',
        'variant' => 'primary',
        'subtext' => 'Kader & Alumni di Database'
    ]) ?>

    <?= partial('stat-card', [
        'title' => 'Anggota Aktif',
        'value' => (int)($summary['active_civitas'] ?? 0),
        'icon' => 'check',
        'variant' => 'success',
        'subtext' => 'Mahasiswa aktif kuliah'
    ]) ?>

    <?= partial('stat-card', [
        'title' => 'Senior / Alumni',
        'value' => (int)($summary['alumni_civitas'] ?? 0),
        'icon' => 'award',
        'variant' => 'secondary',
        'subtext' => 'Civitas Pasca Kampus'
    ]) ?>

    <?= partial('stat-card', [
        'title' => 'Total Komisariat',
        'value' => (int)($summary['total_komisariat'] ?? 0),
        'icon' => 'building',
        'variant' => 'info',
        'subtext' => 'Basis Pelayanan Kampus'
    ]) ?>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate__animated animate__fadeIn">
    <!-- Chart 1: Persebaran Komisariat -->
    <div class="card rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden bg-white">
        <div class="table-header p-5 border-b border-slate-100">
            <div class="table-title font-bold text-slate-800">Civitas per Komisariat Kampus</div>
        </div>
        <div class="card-body p-5">
            <canvas id="chartKomisariat" height="180"></canvas>
        </div>
    </div>

    <!-- Chart 2: Rasio Gender -->
    <div class="card rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden bg-white">
        <div class="table-header p-5 border-b border-slate-100">
            <div class="table-title font-bold text-slate-800">Komposisi Gender Anggota</div>
        </div>
        <div class="card-body p-5 flex justify-center items-center" style="min-height: 250px;">
            <div style="width: 100%; max-width: 260px;">
                <canvas id="chartGender"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 3: Pertumbuhan Kader Maperca -->
    <div class="card rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden bg-white">
        <div class="table-header p-5 border-b border-slate-100">
            <div class="table-title font-bold text-slate-800">Tren Penerimaan Kader per Tahun Maperca</div>
        </div>
        <div class="card-body p-5">
            <canvas id="chartMaperca" height="180"></canvas>
        </div>
    </div>

    <!-- Chart 4: Sebaran Kampus -->
    <div class="card rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden bg-white">
        <div class="table-header p-5 border-b border-slate-100">
            <div class="table-title font-bold text-slate-800">Sebaran Mahasiswa per Perguruan Tinggi</div>
        </div>
        <div class="card-body p-5">
            <canvas id="chartPT" height="180"></canvas>
        </div>
    </div>
</div>

<script>
    window.chartDataPayload = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
</script>
