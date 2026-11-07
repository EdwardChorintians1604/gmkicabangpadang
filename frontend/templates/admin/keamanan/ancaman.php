<?php
$summary = $summary ?? [
    'total' => $total ?? 0,
    'critical' => 0,
    'blocked' => 0,
    'detected' => 0,
    'resolved' => 0,
    'recent_24h' => 0,
    'failed_logins_24h' => 0,
    'blacklisted_count' => 0,
    'blacklisted_ips' => [],
    'top_ips' => [],
];
$securityScore = (int)($securityScore ?? 100);
$openGaps = (int)($openGaps ?? 0);
$blacklistedIps = $summary['blacklisted_ips'] ?? [];
$topIps = $summary['top_ips'] ?? [];
$searchQuery = $searchQuery ?? '';
$selectedSeverity = $selectedSeverity ?? '';
$selectedStatus = $selectedStatus ?? '';
?>

<div>
    <!-- Navigasi Tab Keamanan -->
    <div class="sec-nav-tabs">
        <a href="/admin/keamanan/audit-log" class="sec-nav-tab">
            <span>Jejak Audit Data</span>
        </a>
        <a href="/admin/keamanan/akses-log" class="sec-nav-tab">
            <span>Log Akses Pengunjung</span>
        </a>
        <a href="/admin/keamanan/ancaman" class="sec-nav-tab active">
            <span>Deteksi Ancaman &amp; Perisai Sistem</span>
        </a>
        <a href="/admin/keamanan/backup" class="sec-nav-tab">
            <span>Backup &amp; Pemulihan</span>
        </a>
    </div>

    <!-- Header Sederhana & Bersih -->
    <div class="sec-clean-header">
        <div>
            <h1 class="sec-clean-title">
                <span>Keamanan &amp; Deteksi Ancaman</span>
                <span class="badge <?= $openGaps === 0 ? 'badge-success' : 'badge-warning' ?>" style="font-size: 0.72rem; font-weight: 600;">
                    <?= $openGaps === 0 ? "Sistem Aman ({$securityScore}%)" : "{$openGaps} Celah Perlu Ditutup" ?>
                </span>
            </h1>
            <p class="sec-clean-subtitle">
                Pemantauan firewall (WAF), pencegatan eksploitasi otomatis, dan perbaikan celah konfigurasi.
            </p>
        </div>

        <div class="sec-clean-actions">
            <form action="/admin/keamanan/ancaman/deep-scan" method="POST" style="margin: 0;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline btn-sm" style="height: 36px; border-radius: 8px; font-weight: 600;">
                    Pindai Sistem
                </button>
            </form>

            <form action="/admin/keamanan/ancaman/auto-fix" method="POST" style="margin: 0;">
                <?= csrf_field() ?>
                <input type="hidden" name="target" value="all">
                <button type="submit" class="btn btn-primary btn-sm" style="height: 36px; border-radius: 8px; font-weight: 600;">
                    Perbaiki &amp; Tutup Celah
                </button>
            </form>
        </div>
    </div>

    <!-- Ringkasan Statistik Minimalis -->
    <div class="sec-clean-stats">
        <div class="sec-stat-item">
            <div class="sec-stat-label">Kesehatan Perisai</div>
            <div class="sec-stat-value"><?= $securityScore ?>%</div>
            <div class="sec-stat-note"><?= count($healthChecks) - $openGaps ?> dari <?= count($healthChecks) ?> lapisan aktif</div>
        </div>

        <div class="sec-stat-item">
            <div class="sec-stat-label">Total Ancaman Tercatat</div>
            <div class="sec-stat-value"><?= number_format((int)$summary['total']) ?></div>
            <div class="sec-stat-note"><?= number_format((int)$summary['recent_24h']) ?> kejadian dalam 24 jam</div>
        </div>

        <div class="sec-stat-item">
            <div class="sec-stat-label">IP Diblokir (Firewall)</div>
            <div class="sec-stat-value"><?= number_format((int)$summary['blacklisted_count']) ?></div>
            <div class="sec-stat-note">Daftar blokir otomatis &amp; manual</div>
        </div>

        <div class="sec-stat-item">
            <div class="sec-stat-label">Gagal Login (24 Jam)</div>
            <div class="sec-stat-value"><?= number_format((int)$summary['failed_logins_24h']) ?></div>
            <div class="sec-stat-note">Batas maks. 5x per 15 menit</div>
        </div>
    </div>

    <!-- Panel Status Lapisan Proteksi & Blokir IP (2 Kolom Rapi) -->
    <div class="sec-clean-split">
        <!-- Kiri: Daftar Periksa 8 Lapisan Keamanan -->
        <div class="sec-panel-box">
            <div class="sec-panel-head">
                <h2 class="sec-panel-title">Status Lapisan Proteksi Server</h2>
                <span style="font-size: 0.75rem; color: #64748b;">
                    <?= $openGaps === 0 ? 'Seluruh proteksi optimal' : "{$openGaps} item perlu tindakan" ?>
                </span>
            </div>
            <div>
                <?php foreach ($healthChecks as $check): ?>
                    <?php
                        $st = $check['status'] ?? 'ok';
                        $dotClass = match ($st) {
                            'ok' => 'ok',
                            'danger' => 'err',
                            default => 'warn',
                        };
                    ?>
                    <div class="sec-check-row">
                        <div class="sec-check-left">
                            <span class="sec-dot <?= $dotClass ?>"></span>
                            <div>
                                <div class="sec-check-name"><?= e($check['title']) ?></div>
                                <div class="sec-check-desc"><?= e($check['description']) ?></div>
                            </div>
                        </div>
                        <div style="flex-shrink: 0;">
                            <?php if (!empty($check['fix_target'])): ?>
                                <form action="/admin/keamanan/ancaman/auto-fix" method="POST" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="target" value="<?= e($check['fix_target']) ?>">
                                    <button type="submit" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 0.25rem 0.65rem; border-radius: 6px; font-weight: 600;">
                                        Tutup Celah
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="font-size: 0.74rem; font-weight: 600; color: #059669;">Aman</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Kanan: Kontrol Blokir IP -->
        <div class="sec-panel-box">
            <div class="sec-panel-head">
                <h2 class="sec-panel-title">Daftar Blokir IP (IPS)</h2>
                <span style="font-size: 0.75rem; color: #64748b;"><?= count($blacklistedIps) ?> IP terkunci</span>
            </div>
            <div style="padding: 1rem 1.25rem;">
                <form action="/admin/keamanan/ancaman/block-ip" method="POST" style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <?= csrf_field() ?>
                    <input type="text" name="ip_address" class="form-control" placeholder="Alamat IP (cth: 103.x.x.x)" required style="flex: 1; font-size: 0.8rem; height: 36px;">
                    <input type="hidden" name="reason" value="Diblokir manual oleh Admin">
                    <button type="submit" class="btn btn-outline btn-sm" style="height: 36px; padding: 0 0.85rem; font-weight: 600; white-space: nowrap;">
                        Blokir IP
                    </button>
                </form>

                <?php if (!empty($blacklistedIps)): ?>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; max-height: 220px; overflow-y: auto;">
                        <?php foreach ($blacklistedIps as $ipKey => $item): ?>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.55rem 0.75rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <div style="min-width: 0;">
                                    <div class="sec-ip-text"><?= e($item['ip'] ?? $ipKey) ?></div>
                                    <div style="font-size: 0.7rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px;">
                                        <?= e($item['reason'] ?? 'Diblokir WAF') ?>
                                    </div>
                                </div>
                                <form action="/admin/keamanan/ancaman/unblock-ip" method="POST" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="ip_address" value="<?= e($item['ip'] ?? $ipKey) ?>">
                                    <button type="submit" class="btn btn-outline btn-sm" style="font-size: 0.7rem; padding: 0.2rem 0.55rem;">
                                        Buka
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="font-size: 0.78rem; color: #64748b; padding: 0.75rem 0; border-top: 1px solid #f1f5f9;">
                        Belum ada IP yang masuk daftar blokir. Sistem otomatis memblokir IP eksternal saat mendeteksi serangan kritis.
                    </div>
                <?php endif; ?>

                <?php if (!empty($topIps)): ?>
                    <div style="margin-top: 1rem; padding-top: 0.85rem; border-top: 1px solid #f1f5f9;">
                        <div style="font-size: 0.74rem; font-weight: 700; color: #475569; margin-bottom: 0.5rem;">Sumber Percobaan Terbanyak</div>
                        <?php foreach (array_slice($topIps, 0, 3) as $attacker): ?>
                            <?php
                                $attIp = (string)($attacker['ip_address'] ?? '');
                                $isAlreadyBlocked = isset($blacklistedIps[$attIp]);
                                $isLocal = in_array($attIp, ['127.0.0.1', '::1', 'localhost'], true);
                            ?>
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.76rem; padding: 0.35rem 0;">
                                <div>
                                    <span class="sec-ip-text"><?= e($attIp) ?></span>
                                    <span style="color: #64748b; margin-left: 0.35rem;">(<?= (int)($attacker['hit_count'] ?? 0) ?>x)</span>
                                </div>
                                <?php if ($isAlreadyBlocked): ?>
                                    <span style="font-size: 0.7rem; color: #dc2626; font-weight: 600;">Diblokir</span>
                                <?php elseif (!$isLocal): ?>
                                    <form action="/admin/keamanan/ancaman/block-ip" method="POST" style="margin: 0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="ip_address" value="<?= e($attIp) ?>">
                                        <input type="hidden" name="reason" value="Agresi <?= (int)($attacker['hit_count'] ?? 1) ?>x">
                                        <button type="submit" style="background: none; border: none; color: #dc2626; font-size: 0.72rem; font-weight: 600; cursor: pointer; padding: 0;">
                                            Blokir
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size: 0.7rem; color: #94a3b8;">Lokal</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Ancaman -->
    <div class="sec-panel-box">
        <div class="sec-panel-head">
            <div>
                <h2 class="sec-panel-title">Riwayat Deteksi Ancaman</h2>
            </div>

            <form method="GET" action="/admin/keamanan/ancaman" style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin: 0;">
                <input type="text" name="q" value="<?= e($searchQuery) ?>" placeholder="Cari IP atau jenis ancaman..." class="form-control" style="font-size: 0.78rem; height: 34px; width: 200px;">
                <select name="severity" class="form-control" onchange="this.form.submit()" style="font-size: 0.78rem; height: 34px; width: auto;">
                    <option value="">Semua Level</option>
                    <option value="critical" <?= $selectedSeverity === 'critical' ? 'selected' : '' ?>>Critical</option>
                    <option value="high" <?= $selectedSeverity === 'high' ? 'selected' : '' ?>>High</option>
                    <option value="medium" <?= $selectedSeverity === 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="low" <?= $selectedSeverity === 'low' ? 'selected' : '' ?>>Low</option>
                </select>
                <select name="status" class="form-control" onchange="this.form.submit()" style="font-size: 0.78rem; height: 34px; width: auto;">
                    <option value="">Semua Status</option>
                    <option value="blocked" <?= $selectedStatus === 'blocked' ? 'selected' : '' ?>>Diblokir</option>
                    <option value="detected" <?= $selectedStatus === 'detected' ? 'selected' : '' ?>>Terdeteksi</option>
                    <option value="resolved" <?= $selectedStatus === 'resolved' ? 'selected' : '' ?>>Selesai</option>
                </select>
                <button type="submit" class="btn btn-outline btn-sm" style="height: 34px;">Cari</button>
                <?php if ($searchQuery !== '' || $selectedSeverity !== '' || $selectedStatus !== ''): ?>
                    <a href="/admin/keamanan/ancaman" class="btn btn-outline btn-sm" style="height: 34px; display: inline-flex; align-items: center; text-decoration: none;">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="ws-table-wrap">
            <table class="ws-table">
                <thead>
                    <tr>
                        <th style="width: 145px;">Waktu</th>
                        <th>Jenis Ancaman</th>
                        <th style="width: 130px;">Alamat IP</th>
                        <th style="width: 270px;">Payload</th>
                        <th style="width: 110px;">Status</th>
                        <th style="width: 120px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($threats)): ?>
                        <?php foreach ($threats as $t): ?>
                            <?php
                                $sev = strtolower((string)($t['severity'] ?? 'medium'));
                                $st = strtolower((string)($t['status'] ?? 'detected'));
                                $rowIp = trim((string)($t['ip_address'] ?? ''));
                            ?>
                            <tr>
                                <td style="white-space: nowrap; font-size: 0.76rem; color: #64748b;">
                                    <?= format_date($t['created_at'], 'd M Y H:i') ?>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1e293b; font-size: 0.82rem;">
                                        <?= e($t['threat_type']) ?>
                                    </div>
                                    <div style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase;">
                                        Level: <?= e($sev) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="sec-ip-text"><?= e($rowIp) ?></span>
                                </td>
                                <td>
                                    <code class="sec-code-inline" title="<?= e($t['payload'] ?? '-') ?>"><?= e($t['payload'] ?? '-') ?></code>
                                </td>
                                <td>
                                    <?php if ($st === 'resolved'): ?>
                                        <span style="font-size: 0.74rem; color: #059669; font-weight: 600;">Selesai</span>
                                    <?php elseif ($st === 'blocked'): ?>
                                        <span style="font-size: 0.74rem; color: #dc2626; font-weight: 600;">Diblokir</span>
                                    <?php else: ?>
                                        <span style="font-size: 0.74rem; color: #d97706; font-weight: 600;">Terdeteksi</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <?php if ($st !== 'resolved'): ?>
                                        <form action="/admin/keamanan/ancaman/<?= (int)$t['id'] ?>/resolve" method="POST" style="margin: 0; display: inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 0.2rem 0.55rem;">
                                                Selesai
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="/admin/keamanan/ancaman/<?= (int)$t['id'] ?>/delete" method="POST" style="margin: 0; display: inline;" data-confirm="Hapus catatan ini?">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 0.2rem 0.5rem; color: #64748b;">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2.5rem 1rem; color: #64748b; font-size: 0.82rem;">
                                Tidak ada catatan ancaman yang ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <div style="padding: 0.85rem 1.25rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                <div style="font-size: 0.76rem; color: #64748b;">
                    Halaman <?= (int)$currentPage ?> dari <?= (int)$totalPages ?> (Total <?= number_format((int)$total) ?> catatan)
                </div>
                <div class="pagination-pages" style="display: flex; gap: 0.3rem; flex-wrap: wrap;">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php
                            $qParams = array_filter([
                                'page' => $i,
                                'q' => $searchQuery,
                                'severity' => $selectedSeverity,
                                'status' => $selectedStatus,
                            ]);
                        ?>
                        <a href="/admin/keamanan/ancaman?<?= http_build_query($qParams) ?>" class="page-link <?= ($i === (int)$currentPage) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
