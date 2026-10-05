<div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">Statistik & Analisis Civitas</h1>
        <p class="text-muted" style="font-size: 0.875rem;">
            Visualisasi data persebaran komisariat, perguruan tinggi, gender, dan pertumbuhan kaderisasi.
        </p>
    </div>
    <div>
        <a href="/admin/civitas/export" class="btn btn-outline btn-sm">📥 Ekspor Seluruh Data</a>
    </div>
</div>

<!-- Metrics Cards -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Total Civitas Terdata</span>
            <span class="metric-value"><?= (int)($summary['total_civitas'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-primary">👥</div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Anggota Aktif</span>
            <span class="metric-value"><?= (int)($summary['active_civitas'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-success">🟢</div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Senior / Alumni</span>
            <span class="metric-value"><?= (int)($summary['alumni_civitas'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-secondary">🎓</div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <span class="metric-label">Total Komisariat</span>
            <span class="metric-value"><?= (int)($summary['total_komisariat'] ?? 0) ?></span>
        </div>
        <div class="metric-icon-wrap metric-icon-info">🏛️</div>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-2 gap-6" style="margin-bottom: 2rem;">
    <!-- Chart 1: Persebaran Komisariat -->
    <div class="card">
        <div class="table-header">
            <div class="table-title">Civitas per Komisariat Kampus</div>
        </div>
        <div class="card-body">
            <canvas id="chartKomisariat" height="150"></canvas>
        </div>
    </div>

    <!-- Chart 2: Rasio Gender -->
    <div class="card">
        <div class="table-header">
            <div class="table-title">Komposisi Gender Anggota</div>
        </div>
        <div class="card-body flex justify-center items-center" style="min-height: 250px;">
            <div style="width: 100%; max-width: 240px;">
                <canvas id="chartGender"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 3: Pertumbuhan Kader Maperca -->
    <div class="card">
        <div class="table-header">
            <div class="table-title">Tren Penerimaan Kader per Tahun Maperca</div>
        </div>
        <div class="card-body">
            <canvas id="chartMaperca" height="150"></canvas>
        </div>
    </div>

    <!-- Chart 4: Sebaran Kampus -->
    <div class="card">
        <div class="table-header">
            <div class="table-title">Sebaran Mahasiswa per Perguruan Tinggi</div>
        </div>
        <div class="card-body">
            <canvas id="chartPT" height="150"></canvas>
        </div>
    </div>
</div>

<script>
    window.chartDataPayload = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
</script>
