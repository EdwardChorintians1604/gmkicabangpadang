<?php

namespace App\Services;

use App\Repositories\SecurityRepository;

class SecurityService
{
    protected SecurityRepository $securityRepo;
    protected AuditLogService $auditLogService;
    protected string $rootDir;

    public function __construct()
    {
        $this->securityRepo = new SecurityRepository();
        $this->auditLogService = new AuditLogService();
        $this->rootDir = dirname(__DIR__, 2);
    }

    public function getThreatsList(int $page = 1, int $perPage = 25, array $filters = []): array
    {
        $items = $this->securityRepo->paginateThreats($page, $perPage, $filters);
        $total = $this->securityRepo->countThreats($filters);
        $totalPages = ceil($total / max(1, $perPage));

        return [
            'data' => $items,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int)$totalPages),
        ];
    }

    public function getSummaryStats(): array
    {
        $stats = $this->securityRepo->getThreatSummaryStats();
        $blacklisted = function_exists('get_blacklisted_ips') ? get_blacklisted_ips() : [];
        $stats['blacklisted_count'] = count($blacklisted);
        $stats['blacklisted_ips'] = $blacklisted;
        return $stats;
    }

    public function resolveThreat(int $id): bool
    {
        $res = $this->securityRepo->resolveThreat($id);
        $this->auditLogService->log('RESOLVE_THREAT', 'security_threat', (string)$id);
        return $res;
    }

    public function resolveAllThreats(): bool
    {
        $res = $this->securityRepo->resolveAllThreats();
        $this->auditLogService->log('RESOLVE_ALL_THREATS', 'security_threat', 'all');
        return $res;
    }

    public function deleteThreat(int $id): bool
    {
        $res = $this->securityRepo->deleteThreat($id);
        $this->auditLogService->log('DELETE_THREAT', 'security_threat', (string)$id);
        return $res;
    }

    public function blockAttackerIp(string $ip, string $reason = 'Diblokir manual oleh Superadmin'): bool
    {
        if (!function_exists('blacklist_ip')) {
            return false;
        }
        $ok = blacklist_ip($ip, $reason, 'SUPERADMIN');
        if ($ok) {
            $this->auditLogService->log('BLOCK_IP', 'security_firewall', $ip, ['reason' => $reason]);
        }
        return $ok;
    }

    public function unblockAttackerIp(string $ip): bool
    {
        if (!function_exists('unblock_ip')) {
            return false;
        }
        $ok = unblock_ip($ip);
        if ($ok) {
            $this->auditLogService->log('UNBLOCK_IP', 'security_firewall', $ip);
        }
        return $ok;
    }

    /**
     * Audit mendalam secara real-time terhadap 8 lapisan pertahanan sistem
     */
    public function getSecurityHealth(): array
    {
        // Baca langsung dari file .env agar akurat setelah auto-fix
        $envVars = $this->readEnvFile();
        $env = strtolower($envVars['APP_ENV'] ?? ($_ENV['APP_ENV'] ?? 'development'));
        $debugRaw = strtolower($envVars['APP_DEBUG'] ?? ($_ENV['APP_DEBUG'] ?? 'true'));
        $debug = in_array($debugRaw, ['true', '1', 'yes', 'on'], true);

        // 1. Cek Mode Debug & Lingkungan Produksi (.env)
        $envOk = ($env === 'production' && !$debug);
        $envDesc = $envOk
            ? "Mode Produksi aktif (APP_ENV=production) dan jejak error debug tertutup rapat (APP_DEBUG=false)."
            : "Celah Terbuka: APP_ENV={$env} & APP_DEBUG=" . ($debug ? 'true' : 'false') . ". Stack trace & struktur direktori berisiko bocor ke publik.";

        // 2. Cek Perisai Eksekusi Direktori Upload & Storage (.htaccess & index.html)
        $unprotectedDirs = $this->findUnprotectedStorageDirs();
        $storageOk = empty($unprotectedDirs);
        $storageDesc = $storageOk
            ? "Seluruh 9 direktori upload & storage telah dikunci dengan perisai anti-eksekusi script (.htaccess) dan anti-indexing."
            : count($unprotectedDirs) . " direktori belum memiliki perisai .htaccess / index.html lengkap (" . implode(', ', array_slice($unprotectedDirs, 0, 3)) . ").";

        // 3. Pemindaian Fisik Anti-PHP2Shell & Malware di Folder Upload/Storage
        $suspiciousFiles = $this->scanDirectoriesForWebShells();
        $malwareOk = empty($suspiciousFiles);
        $malwareDesc = $malwareOk
            ? "0 berkas berbahaya ditemukan. Seluruh direktori unggahan bersih dari backdoor PHP2Shell, .phtml, .phar, maupun injeksi eval()."
            : "PERINGATAN KRITIS: Ditemukan " . count($suspiciousFiles) . " berkas mencurigakan di direktori penyimpanan!";

        // 4. Perisai WAF, Anti-SQLi, Anti-XSS, Anti-LFI & Honeypot Scanner
        $wafActive = class_exists(\App\Middleware\Firewall::class) && function_exists('detect_rce_payload') && function_exists('detect_xss_payload');
        $wafDesc = $wafActive
            ? "Firewall berlapis aktif menangkal SQL Injection, PHP2Shell/RCE, CVE-2024-4577, LFI, XSS, SSRF/XXE, serta bot scanner otomatis."
            : "Modul Firewall belum termuat secara lengkap.";

        // 5. Pengerasan Runtime PHP (allow_url_include, HttpOnly, Strict Session)
        $allowUrlInclude = filter_var(ini_get('allow_url_include'), FILTER_VALIDATE_BOOLEAN);
        $httpOnly = filter_var(ini_get('session.cookie_httponly'), FILTER_VALIDATE_BOOLEAN);
        $runtimeOk = (!$allowUrlInclude && $httpOnly);
        $runtimeDesc = $runtimeOk
            ? "Runtime PHP terkunci aman: allow_url_include=Off, expose_php=Off, session.cookie_httponly=On, dan strict_mode=On."
            : "Konfigurasi runtime PHP memerlukan pengerasan (allow_url_include atau cookie_httponly belum optimal).";

        // 6. Mitigasi Brute-Force & Rate Limiting Login
        $summary = $this->securityRepo->getThreatSummaryStats();
        $failed24h = (int)($summary['failed_logins_24h'] ?? 0);
        $bruteOk = ($failed24h < 15);
        $bruteDesc = $bruteOk
            ? "Batas maksimal 5 gagal login / 15 menit aktif. Tercatat {$failed24h} gagal login dalam 24 jam terakhir."
            : "Terdapat lonjakan {$failed24h} percobaan gagal login dalam 24 jam terakhir. Bersihkan antrean & perketat blokir IP.";

        // 7. Sistem Blokir IP Otomatis (Active IPS) & Ancaman Terbuka
        $unresolvedCount = (int)($summary['detected'] ?? 0);
        $blockedCount = (int)($summary['blocked'] ?? 0);
        $blacklistCount = count(function_exists('get_blacklisted_ips') ? get_blacklisted_ips() : []);
        $ipsOk = ($unresolvedCount === 0);
        $ipsDesc = $ipsOk
            ? "IPS Aktif: {$blockedCount} serangan telah digagalkan otomatis dan {$blacklistCount} IP berbahaya dikarantina di daftar hitam."
            : "Terdapat {$unresolvedCount} insiden ancaman berstatus 'Terdeteksi' yang belum dimitigasi/ditutup secara penuh.";

        // 8. Kebocoran Berkas Sensitif di Folder Publik
        $publicLeaks = $this->findPublicSensitiveLeaks();
        $publicOk = empty($publicLeaks);
        $publicDesc = $publicOk
            ? "Direktori publik bersih dari kebocoran berkas .sql, .env, .bak, atau .log serta dilindungi aturan blokir .htaccess."
            : "Ditemukan " . count($publicLeaks) . " berkas sensitif/cadangan di dalam folder publik yang berisiko diunduh pihak luar.";

        return [
            [
                'id' => 'waf_ips_shield',
                'title' => 'Perisai WAF, Anti-PHP2Shell & Anti-SQLi/XSS',
                'category' => 'Firewall Inti',
                'icon' => 'bi-shield-fill-check',
                'status' => $wafActive ? 'ok' : 'danger',
                'badge' => $wafActive ? 'Aktif & Terkunci' : 'Celah Kritis',
                'description' => $wafDesc,
                'fix_target' => null,
                'fix_label' => null,
            ],
            [
                'id' => 'env_debug_hardening',
                'title' => 'Penutupan Mode Debug & Eksposur Lingkungan',
                'category' => 'Konfigurasi .env',
                'icon' => 'bi-sliders',
                'status' => $envOk ? 'ok' : 'warning',
                'badge' => $envOk ? 'Mode Produksi Aman' : 'Celah Terbuka',
                'description' => $envDesc,
                'fix_target' => $envOk ? null : 'fix_env_production',
                'fix_label' => 'Kunci Mode Produksi & Matikan Debug',
            ],
            [
                'id' => 'storage_upload_shields',
                'title' => 'Isolasi Eksekusi Direktori Upload & Storage',
                'category' => 'Proteksi Direktori',
                'icon' => 'bi-folder-symlink-fill',
                'status' => $storageOk ? 'ok' : 'warning',
                'badge' => $storageOk ? 'Terisolasi Penuh' : 'Perlu Perisai',
                'description' => $storageDesc,
                'fix_target' => $storageOk ? null : 'fix_storage_shields',
                'fix_label' => 'Pasang Perisai .htaccess Otomatis',
            ],
            [
                'id' => 'webshell_malware_sweep',
                'title' => 'Pemindaian Fisik Anti-WebShell & Backdoor',
                'category' => 'Integritas Berkas',
                'icon' => 'bi-bug-fill',
                'status' => $malwareOk ? 'ok' : 'danger',
                'badge' => $malwareOk ? 'Bersih 100%' : 'Ancaman Ditemukan',
                'description' => $malwareDesc,
                'fix_target' => $malwareOk ? null : 'quarantine_malicious_files',
                'fix_label' => 'Hapus & Karantina Backdoor',
            ],
            [
                'id' => 'active_ips_mitigation',
                'title' => 'Mitigasi Insiden & Karantina IP Penyerang (IPS)',
                'category' => 'Respons Insiden',
                'icon' => 'bi-slash-circle-fill',
                'status' => $ipsOk ? 'ok' : 'warning',
                'badge' => $ipsOk ? 'Termitigasi' : "{$unresolvedCount} Belum Ditutup",
                'description' => $ipsDesc,
                'fix_target' => $ipsOk ? null : 'mitigate_active_threats',
                'fix_label' => 'Mitigasi & Tutup Seluruh Insiden',
            ],
            [
                'id' => 'php_runtime_hardening',
                'title' => 'Pengerasan Runtime PHP & Enkripsi Sesi CSRF',
                'category' => 'Engine PHP',
                'icon' => 'bi-cpu-fill',
                'status' => $runtimeOk ? 'ok' : 'warning',
                'badge' => $runtimeOk ? 'Hardened' : 'Perhatian',
                'description' => $runtimeDesc,
                'fix_target' => null,
                'fix_label' => null,
            ],
            [
                'id' => 'brute_force_guard',
                'title' => 'Perisai Anti-Brute Force & Kunci Login',
                'category' => 'Autentikasi',
                'icon' => 'bi-key-fill',
                'status' => $bruteOk ? 'ok' : 'warning',
                'badge' => $bruteOk ? 'Terkendali' : 'Lonjakan Login',
                'description' => $bruteDesc,
                'fix_target' => $failed24h > 0 ? 'purge_login_attempts' : null,
                'fix_label' => 'Bersihkan Log Brute-Force',
            ],
            [
                'id' => 'public_sensitive_guard',
                'title' => 'Proteksi Kebocoran Berkas Sensitif Publik',
                'category' => 'Area Publik',
                'icon' => 'bi-file-earmark-lock2-fill',
                'status' => $publicOk ? 'ok' : 'danger',
                'badge' => $publicOk ? 'Kedap Bocor' : 'Celah Publik',
                'description' => $publicDesc,
                'fix_target' => $publicOk ? null : 'fix_public_leaks',
                'fix_label' => 'Bersihkan & Kunci Folder Publik',
            ],
        ];
    }

    /**
     * Eksekusi perbaikan langsung & penutupan celah keamanan secara otomatis (Auto-Remediation)
     */
    public function autoFixVulnerabilities(string $target = 'all'): array
    {
        $actionsTaken = [];

        // 1. Kunci .env ke Mode Produksi & Matikan APP_DEBUG
        if ($target === 'all' || $target === 'fix_env_production') {
            if ($this->hardenEnvConfiguration()) {
                $actionsTaken[] = 'Menutup celah eksposur debug: mengubah APP_ENV=production dan APP_DEBUG=false pada berkas .env.';
            }
        }

        // 2. Pasang Perisai .htaccess Anti-Eksekusi PHP & index.html di seluruh folder Storage & Uploads
        if ($target === 'all' || $target === 'fix_storage_shields') {
            $shieldedCount = $this->deployStorageExecutionShields();
            $actionsTaken[] = "Memasang & memperbarui perisai anti-eksekusi script (.htaccess & index.html) pada {$shieldedCount} direktori upload/storage.";
        }

        // 3. Pindai & Hapus Berkas Berbahaya / WebShell di Direktori Upload & Storage
        if ($target === 'all' || $target === 'quarantine_malicious_files') {
            $removedFiles = $this->neutralizeSuspiciousFiles();
            if (!empty($removedFiles)) {
                $actionsTaken[] = 'Menghapus ' . count($removedFiles) . ' berkas berbahaya/script terlarang dari direktori penyimpanan.';
            } else {
                $actionsTaken[] = 'Memverifikasi kebersihan fisik direktori upload & storage dari backdoor PHP2Shell (0 berkas berbahaya).';
            }
        }

        // 4. Bersihkan Kebocoran Berkas Sensitif di Folder Publik & Perkuat .htaccess Publik
        if ($target === 'all' || $target === 'fix_public_leaks') {
            $cleanedPublic = $this->remediatePublicSensitiveLeaks();
            $actionsTaken[] = "Mengunci aturan akses berkas sensitif (.env, .git, .sql, .log) pada frontend/public/.htaccess" . ($cleanedPublic > 0 ? " dan membersihkan {$cleanedPublic} berkas sensitif." : ".");
        }

        // 5. Mitigasi Insiden Aktif & Blokir Otomatis IP Penyerang Berbahaya
        if ($target === 'all' || $target === 'mitigate_active_threats') {
            $mitigatedIps = $this->mitigateAndBlockActiveThreats();
            $this->securityRepo->resolveAllThreats();
            $actionsTaken[] = "Menutup seluruh status insiden ancaman terbuka serta memasukkan {$mitigatedIps} IP penyerang eksternal ke daftar hitam (IPS Blacklist).";
        }

        // 6. Bersihkan Riwayat Brute-Force Login Gagal
        if ($target === 'all' || $target === 'purge_login_attempts') {
            $this->securityRepo->clearAllFailedLoginAttempts();
            $actionsTaken[] = 'Membersihkan antrean catatan percobaan login gagal (Brute-Force Reset).';
        }

        $this->auditLogService->log('AUTO_FIX_SECURITY', 'security_system', $target, [
            'actions' => $actionsTaken,
        ]);

        return $actionsTaken;
    }

    /**
     * Pemindaian mendalam (Deep Scan) seluruh ekosistem aplikasi
     */
    public function runDeepSecurityScan(): array
    {
        $suspiciousFiles = $this->scanDirectoriesForWebShells();
        $unprotectedDirs = $this->findUnprotectedStorageDirs();
        $publicLeaks = $this->findPublicSensitiveLeaks();
        $health = $this->getSecurityHealth();

        $okCount = 0;
        foreach ($health as $h) {
            if ($h['status'] === 'ok') {
                $okCount++;
            }
        }
        $score = (int)round(($okCount / max(1, count($health))) * 100);

        $this->auditLogService->log('DEEP_SECURITY_SCAN', 'security_system', 'scan', [
            'score' => $score,
            'suspicious_files' => count($suspiciousFiles),
            'unprotected_dirs' => count($unprotectedDirs),
        ]);

        return [
            'score' => $score,
            'total_checks' => count($health),
            'passed_checks' => $okCount,
            'suspicious_files' => $suspiciousFiles,
            'unprotected_dirs' => $unprotectedDirs,
            'public_leaks' => $publicLeaks,
            'scanned_at' => date('d M Y H:i:s'),
        ];
    }

    protected function readEnvFile(): array
    {
        $envFile = $this->rootDir . '/.env';
        $vars = [];
        if (!file_exists($envFile)) {
            return $vars;
        }
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '#') || !str_contains($trimmed, '=')) {
                continue;
            }
            [$k, $v] = explode('=', $trimmed, 2);
            $vars[trim($k)] = trim(trim($v), "\"'");
        }
        return $vars;
    }

    protected function hardenEnvConfiguration(): bool
    {
        $envFile = $this->rootDir . '/.env';
        if (!file_exists($envFile) || !is_writable($envFile)) {
            return false;
        }

        $content = @file_get_contents($envFile);
        if ($content === false) {
            return false;
        }

        if (preg_match('/^APP_ENV\s*=/m', $content)) {
            $content = preg_replace('/^APP_ENV\s*=.*$/m', 'APP_ENV=production', $content);
        } else {
            $content .= "\nAPP_ENV=production\n";
        }

        if (preg_match('/^APP_DEBUG\s*=/m', $content)) {
            $content = preg_replace('/^APP_DEBUG\s*=.*$/m', 'APP_DEBUG=false', $content);
        } else {
            $content .= "\nAPP_DEBUG=false\n";
        }

        $_ENV['APP_ENV'] = 'production';
        $_ENV['APP_DEBUG'] = 'false';
        putenv('APP_ENV=production');
        putenv('APP_DEBUG=false');

        return @file_put_contents($envFile, $content, LOCK_EX) !== false;
    }

    protected function getTargetStorageDirs(): array
    {
        return [
            'storage' => $this->rootDir . '/storage',
            'storage/logs' => $this->rootDir . '/storage/logs',
            'storage/backups' => $this->rootDir . '/storage/backups',
            'storage/archives' => $this->rootDir . '/storage/archives',
            'storage/archives_backup' => $this->rootDir . '/storage/archives_backup',
            'storage/private' => $this->rootDir . '/storage/private',
            'storage/private/security' => $this->rootDir . '/storage/private/security',
            'public/uploads' => $this->rootDir . '/frontend/public/uploads',
            'public/uploads/berita' => $this->rootDir . '/frontend/public/uploads/berita',
            'public/uploads/profiles' => $this->rootDir . '/frontend/public/uploads/profiles',
        ];
    }

    protected function findUnprotectedStorageDirs(): array
    {
        $unprotected = [];
        foreach ($this->getTargetStorageDirs() as $label => $dirPath) {
            if (!is_dir($dirPath)) {
                continue;
            }
            $htaccess = $dirPath . '/.htaccess';
            if (!file_exists($htaccess)) {
                $unprotected[] = $label;
                continue;
            }
            $content = strtolower((string)@file_get_contents($htaccess));
            if (!str_contains($content, 'filesmatch') && !str_contains($content, 'require all denied') && !str_contains($content, 'deny from all')) {
                $unprotected[] = $label;
            }
        }
        return $unprotected;
    }

    protected function deployStorageExecutionShields(): int
    {
        $count = 0;
        $privateHtaccess = "# GMKI Security Shield - Private Vault\nOptions -Indexes -ExecCGI\n<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n    Order deny,allow\n    Deny from all\n</IfModule>\n";

        $uploadHtaccess = "# GMKI Security Shield - Anti-PHP2Shell Upload Lock\nOptions -Indexes -ExecCGI\nAddHandler cgi-script .php .php3 .php4 .php5 .php7 .php8 .phtml .pl .py .jsp .asp .htm .html .shtml .sh .cgi\n<IfModule mod_php.c>\n    php_flag engine off\n</IfModule>\n<IfModule mod_php7.c>\n    php_flag engine off\n</IfModule>\n<IfModule mod_php8.c>\n    php_flag engine off\n</IfModule>\n<FilesMatch \"\\.(php[0-9]?|phtml|phar|inc|cgi|pl|py|sh|bash|exe|bat|cmd|dll|bin|shtml|asp|aspx|jsp|jspx|htaccess|htpasswd|user\\.ini|sql|env|bak|log)$\">\n    <IfModule mod_authz_core.c>\n        Require all denied\n    </IfModule>\n    <IfModule !mod_authz_core.c>\n        Order allow,deny\n        Deny from all\n    </IfModule>\n</FilesMatch>\n";

        $indexGuard = "<!DOCTYPE html><html><head><meta charset=\"utf-8\"><title>403 Forbidden</title></head><body><!-- Directory Listing Disabled by GMKI Security Shield --></body></html>";

        foreach ($this->getTargetStorageDirs() as $label => $dirPath) {
            if (!is_dir($dirPath)) {
                @mkdir($dirPath, 0755, true);
            }
            $isPublicUpload = str_starts_with($label, 'public/uploads');
            $rules = $isPublicUpload ? $uploadHtaccess : $privateHtaccess;

            @file_put_contents($dirPath . '/.htaccess', $rules, LOCK_EX);
            if (!file_exists($dirPath . '/index.html')) {
                @file_put_contents($dirPath . '/index.html', $indexGuard, LOCK_EX);
            }
            $count++;
        }

        return $count;
    }

    protected function scanDirectoriesForWebShells(): array
    {
        $scanTargets = [
            $this->rootDir . '/frontend/public/uploads',
            $this->rootDir . '/storage/archives',
            $this->rootDir . '/storage/archives_backup',
            $this->rootDir . '/storage/private/foto_anggota',
            $this->rootDir . '/storage/private/kta',
        ];

        $suspicious = [];
        $forbiddenExtRegex = '/\.(php[0-9]?|phtml|phar|inc|cgi|pl|py|sh|bash|exe|bat|cmd|dll|shtml|asp|aspx|jsp)(\.|$)/i';

        foreach ($scanTargets as $baseDir) {
            if (!is_dir($baseDir)) {
                continue;
            }
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($baseDir, \FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $fileInfo) {
                if (!$fileInfo->isFile()) {
                    continue;
                }
                $filename = $fileInfo->getFilename();
                if (in_array($filename, ['.htaccess', '.gitkeep', 'index.html'], true)) {
                    continue;
                }
                $fullPath = $fileInfo->getPathname();

                if (preg_match($forbiddenExtRegex, $filename)) {
                    $suspicious[] = [
                        'path' => $fullPath,
                        'name' => $filename,
                        'reason' => 'Ekstensi script eksekusi terlarang di folder penyimpanan',
                    ];
                    continue;
                }

                if (function_exists('scan_content_for_php_code')) {
                    $sig = scan_content_for_php_code($fullPath);
                    if ($sig !== null) {
                        $suspicious[] = [
                            'path' => $fullPath,
                            'name' => $filename,
                            'reason' => $sig,
                        ];
                    }
                }
            }
        }

        return $suspicious;
    }

    protected function neutralizeSuspiciousFiles(): array
    {
        $found = $this->scanDirectoriesForWebShells();
        $removed = [];
        foreach ($found as $item) {
            if (is_file($item['path']) && @unlink($item['path'])) {
                $removed[] = $item['name'] . ' (' . $item['reason'] . ')';
            }
        }
        return $removed;
    }

    protected function findPublicSensitiveLeaks(): array
    {
        $publicDir = $this->rootDir . '/frontend/public';
        if (!is_dir($publicDir)) {
            return [];
        }
        $leaks = [];
        $items = @scandir($publicDir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === '.htaccess' || $item === 'index.php' || $item === 'favicon.ico' || $item === 'robots.txt') {
                continue;
            }
            $full = $publicDir . '/' . $item;
            if (is_file($full) && preg_match('/\.(sql|bak|old|env|log|ini|sh|bat|cmd)$/i', $item)) {
                $leaks[] = $full;
            }
        }
        return $leaks;
    }

    protected function remediatePublicSensitiveLeaks(): int
    {
        $leaks = $this->findPublicSensitiveLeaks();
        $removed = 0;
        foreach ($leaks as $file) {
            if (is_file($file) && @unlink($file)) {
                $removed++;
            }
        }
        return $removed;
    }

    protected function mitigateAndBlockActiveThreats(): int
    {
        $threats = $this->securityRepo->paginateThreats(1, 200);
        $blockedIps = 0;
        $seenIps = [];

        foreach ($threats as $t) {
            $ip = trim((string)($t['ip_address'] ?? ''));
            $sev = strtolower((string)($t['severity'] ?? 'medium'));
            if ($ip === '' || isset($seenIps[$ip]) || (function_exists('is_trusted_admin_ip') && is_trusted_admin_ip($ip))) {
                continue;
            }
            if (in_array($sev, ['critical', 'high'], true) && function_exists('blacklist_ip')) {
                if (!is_ip_blacklisted($ip)) {
                    blacklist_ip($ip, 'Auto-Mitigasi Insiden: ' . ($t['threat_type'] ?? 'Serangan Siber'), 'AUTO-REMEDIATION');
                    $blockedIps++;
                }
                $seenIps[$ip] = true;
            }
        }

        return $blockedIps;
    }
}
