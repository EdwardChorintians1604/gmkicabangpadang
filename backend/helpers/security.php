<?php

use App\Core\Auth;
use App\Core\Database;

if (!function_exists('sanitize_input')) {
    function sanitize_input(mixed $input): mixed
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = sanitize_input($value);
            }
            return $input;
        }

        if (is_string($input)) {
            return trim(strip_tags($input));
        }

        return $input;
    }
}

if (!function_exists('client_ip')) {
    function client_ip(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}

if (!function_exists('audit_log')) {
    function audit_log(string $action, string $entity, ?string $entityId = null, ?array $details = null): void
    {
        try {
            $user = Auth::user();
            $userId = $user['id'] ?? null;
            $username = $user['username'] ?? 'guest';
            $role = $user['role'] ?? 'guest';

            $sql = "INSERT INTO `audit_logs` (`user_id`, `username`, `role`, `action`, `entity`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`)
                    VALUES (:uid, :uname, :role, :action, :entity, :eid, :details, :ip, :ua, NOW())";

            Database::execute($sql, [
                ':uid' => $userId,
                ':uname' => $username,
                ':role' => $role,
                ':action' => $action,
                ':entity' => $entity,
                ':eid' => $entityId,
                ':details' => $details ? json_encode($details, JSON_UNESCAPED_UNICODE) : null,
                ':ip' => client_ip(),
                ':ua' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255),
            ]);
        } catch (\Throwable $e) {
            error_log("Failed writing audit log: " . $e->getMessage());
        }
    }
}

if (!function_exists('record_threat')) {
    function record_threat(string $threatType, ?string $payload = null, string $severity = 'medium', string $status = 'blocked'): void
    {
        try {
            $ip = client_ip();
            $validStatus = in_array($status, ['detected', 'blocked', 'resolved'], true) ? $status : 'blocked';

            $sql = "INSERT INTO `security_threats` (`threat_type`, `ip_address`, `user_agent`, `payload`, `severity`, `status`, `created_at`)
                    VALUES (:type, :ip, :ua, :payload, :severity, :status, NOW())";

            Database::execute($sql, [
                ':type' => $threatType,
                ':ip' => $ip,
                ':ua' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255),
                ':payload' => $payload ? substr($payload, 0, 1000) : null,
                ':severity' => $severity,
                ':status' => $validStatus,
            ]);

            // Auto-Mitigasi IPS: Kunci otomatis IP eksternal bila melakukan serangan Critical atau berulang (>= 3 kali)
            if (!is_trusted_admin_ip($ip)) {
                if ($severity === 'critical') {
                    blacklist_ip($ip, "Auto-IPS Lockout: {$threatType}", 'AUTO-IPS');
                } else {
                    $row = Database::fetchOne(
                        "SELECT COUNT(*) as total FROM `security_threats` WHERE `ip_address` = :ip AND `created_at` >= DATE_SUB(NOW(), INTERVAL 1 HOUR)",
                        [':ip' => $ip]
                    );
                    if (((int)($row['total'] ?? 0)) >= 3) {
                        blacklist_ip($ip, "Auto-IPS Repeat Offender (>=3 Serangan/Jam): {$threatType}", 'AUTO-IPS');
                    }
                }
            }
        } catch (\Throwable $e) {
            error_log("Failed writing threat log: " . $e->getMessage());
        }
    }
}

if (!function_exists('is_trusted_admin_ip')) {
    /**
     * Pastikan IP milik Superadmin / Pengurus yang pernah berhasil login atau sedang aktif tidak pernah terblokir otomatis
     */
    function is_trusted_admin_ip(string $ip): bool
    {
        $ip = trim($ip);
        if ($ip === '' || in_array($ip, ['127.0.0.1', '::1', 'localhost'], true)) {
            return true;
        }

        $currentUser = Auth::user();
        if ($currentUser && in_array($currentUser['role'] ?? '', ['admin', 'operator', 'ketcab', 'sekcab', 'bencab'], true) && $ip === client_ip()) {
            return true;
        }

        try {
            $adminAudit = Database::fetchOne(
                "SELECT `id` FROM `audit_logs` WHERE `ip_address` = :ip AND `role` = 'admin' LIMIT 1",
                [':ip' => $ip]
            );
            if ($adminAudit) {
                return true;
            }

            $successLogin = Database::fetchOne(
                "SELECT `id` FROM `login_attempts` WHERE `ip_address` = :ip AND `is_success` = 1 LIMIT 1",
                [':ip' => $ip]
            );
            if ($successLogin) {
                return true;
            }
        } catch (\Throwable $e) {
            // Abaikan bila tabel belum siap
        }

        return false;
    }
}

if (!function_exists('security_blacklist_file')) {
    function security_blacklist_file(): string
    {
        $dir = dirname(__DIR__, 2) . '/storage/private/security';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
            @file_put_contents($dir . '/.htaccess', "Require all denied\nDeny from all\n");
        }
        return $dir . '/ip_blacklist.json';
    }
}

if (!function_exists('get_blacklisted_ips')) {
    function get_blacklisted_ips(): array
    {
        $file = security_blacklist_file();
        if (!file_exists($file)) {
            return [];
        }
        $raw = @file_get_contents($file);
        if (!$raw) {
            return [];
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }
}

if (!function_exists('is_ip_blacklisted')) {
    function is_ip_blacklisted(string $ip): bool
    {
        $ip = trim($ip);
        if ($ip === '' || in_array($ip, ['127.0.0.1', '::1', 'localhost'], true)) {
            return false;
        }
        $list = get_blacklisted_ips();
        return isset($list[$ip]);
    }
}

if (!function_exists('blacklist_ip')) {
    function blacklist_ip(string $ip, string $reason = 'Diblokir oleh Sistem Keamanan WAF', string $source = 'ADMIN'): bool
    {
        $ip = trim($ip);
        if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }
        $list = get_blacklisted_ips();
        $hits = isset($list[$ip]['hits']) ? ((int)$list[$ip]['hits'] + 1) : 1;
        $list[$ip] = [
            'ip' => $ip,
            'reason' => substr($reason, 0, 240),
            'source' => $source,
            'hits' => $hits,
            'blocked_at' => $list[$ip]['blocked_at'] ?? date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $file = security_blacklist_file();
        return @file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
    }
}

if (!function_exists('unblock_ip')) {
    function unblock_ip(string $ip): bool
    {
        $ip = trim($ip);
        $list = get_blacklisted_ips();
        if (!isset($list[$ip])) {
            return false;
        }
        unset($list[$ip]);
        $file = security_blacklist_file();
        return @file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
    }
}

if (!function_exists('detect_sql_injection')) {
    /**
     * Memeriksa dan mendeteksi pola serangan SQL Injection berbahaya (Anti-SQLi Shield)
     */
    function detect_sql_injection(mixed $data): ?string
    {
        if (is_array($data)) {
            foreach ($data as $item) {
                $found = detect_sql_injection($item);
                if ($found !== null) {
                    return $found;
                }
            }
            return null;
        }

        if (!is_string($data) || empty($data)) {
            return null;
        }

        $patterns = [
            // Union Based SQL Injection
            '/\bunion\b\s+(all\s+)?\bselect\b/i',
            // Tautology / Boolean based (' OR '1'='1, " OR 1=1, ' OR 'a'='a)
            '/\b(or|and)\b\s+[\'\"]?\w+[\'\"]?\s*=\s*[\'\"]?\w+[\'\"]?/i',
            // Blind / Time-based injection functions
            '/\b(sleep|benchmark)\s*\(\s*\d+\s*\)/i',
            '/\bwaitfor\s+delay\b/i',
            // Information Schema probing
            '/\binformation_schema\b/i',
            // File read/write attempts
            '/\b(load_file|into\s+outfile|into\s+dumpfile)\b/i',
            // Stacked queries with semicolon
            '/;\s*(drop|truncate|alter|delete\s+from|insert\s+into|update)\b/i',
            // SQL comment truncations with quote
            '/[\'\"].*(--|\#|\/\*)/i',
            // Obfuscated hex / char functions
            '/\bchar\s*\(\s*\d+\s*(,\s*\d+\s*)*\)/i',
            '/\bconcat\s*\(\s*.*,\s*0x[0-9a-f]+/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $data)) {
                return substr($data, 0, 200);
            }
        }

        return null;
    }
}

if (!function_exists('detect_rce_payload')) {
    /**
     * Memeriksa dan mendeteksi pola serangan PHP2Shell / RCE (Remote Code Execution)
     */
    function detect_rce_payload(mixed $data): ?string
    {
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (is_string($key)) {
                    $foundKey = detect_rce_payload($key);
                    if ($foundKey !== null) {
                        return $foundKey;
                    }
                }
                $found = detect_rce_payload($item);
                if ($found !== null) {
                    return $found;
                }
            }
            return null;
        }

        if (!is_string($data) || empty($data)) {
            return null;
        }

        // Normalisasi multi-layer URL encoding untuk mencegah bypass %25xx
        $decoded = $data;
        for ($i = 0; $i < 3; $i++) {
            $next = rawurldecode($decoded);
            if ($next === $decoded) {
                break;
            }
            $decoded = $next;
        }

        // Deteksi CVE-2024-4577 (PHP-CGI Argument Injection pada Windows via Soft-Hyphen 0xAD atau -d flags)
        if (
            preg_match('/(?:^|[?&;\s])(?:-|\xAD|%ad)(?:d|n|c|s|w|r|B|F|R|E)\b/i', $data) ||
            preg_match('/\b(?:allow_url_include|allow_url_fopen|auto_prepend_file|auto_append_file|disable_functions|open_basedir)\s*=/i', $decoded)
        ) {
            return substr($decoded, 0, 200);
        }

        $patterns = [
            // Tag PHP mentah yang disuntikkan lewat request parameter / header / cookie
            '/<\?(?:php|=|\s)/i',
            '/<script\s+language\s*=\s*[\'"]?php/i',
            // Eksekusi fungsi OS shell & proses berbahaya
            '/\b(system|passthru|shell_exec|exec|popen|proc_open|pcntl_exec|dl|putenv)\s*\(/i',
            '/\b(eval|assert|base64_decode|gzinflate|gzuncompress|gzdecode|zlib_decode|str_rot13|convert_uudecode)\s*\(/i',
            '/\b(create_function|unserialize|call_user_func|call_user_func_array|register_shutdown_function|register_tick_function)\s*\(/i',
            // Eksekusi preg_replace dengan modifier /e
            '/preg_replace\s*\(\s*[\'"].*\/[a-z]*e/i',
            // PHP Superglobal tampering injection
            '/(\$_POST|\$_GET|\$_COOKIE|\$_REQUEST|\$_FILES|\$_SERVER|\$_ENV|\$GLOBALS)\s*\[/i',
            // Command chaining & shell command execution
            '/[;&|`]\s*(id|whoami|uname\s+-a|cat\s+\/etc\/passwd|wget\s+|curl\s+|nc\s+-e|bash\s+-i|powershell|cmd\.exe|certutil|bitsadmin)\b/i',
            // Web Shell probe filenames
            '/\b(wso|alfa|b374k|c99|r57|c100|shell|webshell|uploader|marvins|mini|pws|cmd|bypass|root|indoxploit|marijuana|gecko|adminer|phpinfo)\.php[0-9]?\b/i',
            // PHP Stream Wrappers (LFI/RFI/Phar Deserialization RCE)
            '/\b(php:\/\/input|php:\/\/filter|php:\/\/fd|php:\/\/memory|php:\/\/temp|data:\/\/text|phar:\/\/|zip:\/\/|compress\.zlib:\/\/|expect:\/\/|glob:\/\/)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $decoded)) {
                return substr($decoded, 0, 200);
            }
        }

        return null;
    }
}

if (!function_exists('detect_lfi_traversal')) {
    /**
     * Memeriksa dan mendeteksi pola Directory Traversal / Local File Inclusion
     */
    function detect_lfi_traversal(mixed $data): ?string
    {
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (is_string($key)) {
                    $foundKey = detect_lfi_traversal($key);
                    if ($foundKey !== null) {
                        return $foundKey;
                    }
                }
                $found = detect_lfi_traversal($item);
                if ($found !== null) {
                    return $found;
                }
            }
            return null;
        }

        if (!is_string($data) || empty($data)) {
            return null;
        }

        $decoded = $data;
        for ($i = 0; $i < 3; $i++) {
            $next = rawurldecode($decoded);
            if ($next === $decoded) {
                break;
            }
            $decoded = $next;
        }

        if (str_contains($data, "\0") || str_contains(strtolower($data), '%00')) {
            return 'Null-byte injection terdeteksi';
        }

        $patterns = [
            '/\.\.\/|\.\.\\\\|\.\.%2f|\.\.%5c/i',
            '/(\/etc\/passwd|\/etc\/shadow|\/proc\/self\/environ|win\.ini|boot\.ini|php\.ini|\.env|\.git\/config)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $decoded)) {
                return substr($decoded, 0, 200);
            }
        }

        return null;
    }
}

if (!function_exists('scan_content_for_php_code')) {
    /**
     * Memindai SELURUH isi fisik file mentah dengan Sliding-Window Chunking
     * untuk mendeteksi kode PHP terselubung / Web Shell (Anti-PHP2Shell),
     * termasuk payload yang disembunyikan di akhir berkas berukuran besar (>512 KB).
     */
    function scan_content_for_php_code(string $filePath): ?string
    {
        $normalizedPath = str_replace('\\', '/', $filePath);
        if (!file_exists($normalizedPath) || !is_file($normalizedPath)) {
            return null;
        }

        $handle = @fopen($normalizedPath, 'rb');
        if (!$handle) {
            return null;
        }

        $dangerousSignatures = [
            'eval(' => 'Fungsi eval()',
            'base64_decode(' => 'Fungsi base64_decode() terselubung',
            'system(' => 'Fungsi system()',
            'passthru(' => 'Fungsi passthru()',
            'shell_exec(' => 'Fungsi shell_exec()',
            'exec(' => 'Fungsi exec()',
            'popen(' => 'Fungsi popen()',
            'proc_open(' => 'Fungsi proc_open()',
            'pcntl_exec(' => 'Fungsi pcntl_exec()',
            'assert(' => 'Fungsi assert() dinamis',
            'create_function(' => 'Fungsi create_function()',
            'call_user_func(' => 'Fungsi call_user_func()',
            'call_user_func_array(' => 'Fungsi call_user_func_array()',
            'gzinflate(' => 'Kompresi gzinflate() obfuscator',
            'gzuncompress(' => 'Kompresi gzuncompress() obfuscator',
            'gzdecode(' => 'Kompresi gzdecode() obfuscator',
            'str_rot13(' => 'Enkripsi str_rot13() obfuscator',
            'phar://' => 'Stream wrapper phar:// deserialization',
            'php://input' => 'Stream wrapper php://input',
            'php://filter' => 'Stream wrapper php://filter',
            'move_uploaded_file(' => 'Fungsi uploader move_uploaded_file()',
            'file_put_contents(' => 'Fungsi penulisan file_put_contents()',
        ];

        $chunkSize = 262144; // 256 KB per iterasi
        $overlapSize = 512;  // Overlap 512 byte agar tag di perbatasan chunk tidak terlewat
        $tail = '';

        while (!feof($handle)) {
            $chunk = fread($handle, $chunkSize);
            if ($chunk === false || $chunk === '') {
                break;
            }

            $buffer = $tail . $chunk;
            $lowerBuffer = strtolower($buffer);

            // 1. Tag PHP standar <?php (Binary-safe 5 bytes)
            if (str_contains($lowerBuffer, '<?php')) {
                fclose($handle);
                return 'Terdeteksi tag pembuka PHP (<?php)';
            }

            // 2. Tag script PHP kuno (<script language=php)
            if (str_contains($lowerBuffer, '<script language="php"') || str_contains($lowerBuffer, "<script language='php'") || str_contains($lowerBuffer, '<script language=php')) {
                fclose($handle);
                return 'Terdeteksi tag script PHP kuno (<script language=php)';
            }

            // 3. Tag PHP pendek (<?= atau <? ) yang diikuti kode PHP valid
            if (preg_match('/<\?(?:php|\s*=\s*|\s+)(?:[\$a-z_][a-z0-9_]*\s*\(|\$|_get|_post|_request|_cookie|_files|_server|`)/i', $buffer)) {
                fclose($handle);
                return 'Terdeteksi kode PHP pendek terselubung (<?= atau <? )';
            }

            // 4. Fungsi eksekusi remote shell dalam string
            foreach ($dangerousSignatures as $sig => $label) {
                if (str_contains($lowerBuffer, $sig)) {
                    fclose($handle);
                    return "Terdeteksi signature berbahaya: {$label}";
                }
            }

            $tail = strlen($buffer) > $overlapSize ? substr($buffer, -$overlapSize) : $buffer;
        }

        fclose($handle);
        return null;
    }
}

if (!function_exists('detect_xss_payload')) {
    /**
     * Memeriksa dan mendeteksi pola serangan Cross-Site Scripting (XSS) & DOM Injection
     */
    function detect_xss_payload(mixed $data): ?string
    {
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (is_string($key)) {
                    $foundKey = detect_xss_payload($key);
                    if ($foundKey !== null) {
                        return $foundKey;
                    }
                }
                $found = detect_xss_payload($item);
                if ($found !== null) {
                    return $found;
                }
            }
            return null;
        }

        if (!is_string($data) || empty($data)) {
            return null;
        }

        $decoded = $data;
        for ($i = 0; $i < 2; $i++) {
            $next = rawurldecode($decoded);
            if ($next === $decoded) {
                break;
            }
            $decoded = $next;
        }

        $patterns = [
            '/<\s*script\b[^>]*>/i',
            '/javascript\s*:/i',
            '/vbscript\s*:/i',
            '/\bon(error|load|mouseover|focus|blur|click|dblclick|submit|change|keyup|keydown|toggle|animationstart)\s*=\s*[\'"]?[^\'">\s]+/i',
            '/<\s*(iframe|object|embed|applet|meta|base)\b[^>]*>/i',
            '/<\s*svg\b[^>]*\bon\w+\s*=/i',
            '/\bdocument\s*\.\s*(cookie|domain|write)\b/i',
            '/\bwindow\s*\.\s*location\b/i',
            '/\bString\.fromCharCode\s*\(/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $decoded)) {
                return substr($decoded, 0, 200);
            }
        }

        return null;
    }
}

if (!function_exists('detect_ssrf_xxe_payload')) {
    /**
     * Memeriksa pola serangan SSRF (Server-Side Request Forgery) & XXE (XML External Entity)
     */
    function detect_ssrf_xxe_payload(mixed $data): ?string
    {
        if (is_array($data)) {
            foreach ($data as $item) {
                $found = detect_ssrf_xxe_payload($item);
                if ($found !== null) {
                    return $found;
                }
            }
            return null;
        }

        if (!is_string($data) || empty($data)) {
            return null;
        }

        $decoded = rawurldecode($data);

        $patterns = [
            '/<!ENTITY\s+/i',
            '/<!DOCTYPE[^>]+SYSTEM\s+[\'"]/i',
            '/\b(169\.254\.169\.254|metadata\.google\.internal|100\.100\.100\.200)\b/i',
            '/\b(gopher|dict|ldap|ldaps|tftp|file|netdoc):\/\//i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $decoded)) {
                return substr($decoded, 0, 200);
            }
        }

        return null;
    }
}

if (!function_exists('detect_scanner_probe')) {
    /**
     * Mendeteksi pemindaian bot kerentanan otomatis (SQLMap, Nikto, Nuclei, Acunetix)
     * serta percobaan akses ke berkas/jalur honeypot terlarang (.env, .git, wp-admin, phpmyadmin, phpunit)
     */
    function detect_scanner_probe(string $uri, string $userAgent = ''): ?array
    {
        $cleanUri = strtolower(rawurldecode($uri));
        $cleanUa = strtolower($userAgent);

        // 1. Deteksi User-Agent Scanner Otomatis
        $scannerSignatures = [
            'sqlmap' => 'Automated SQLMap Exploit Scanner',
            'nikto' => 'Nikto Web Vulnerability Scanner',
            'acunetix' => 'Acunetix Security Scanner',
            'nuclei' => 'ProjectDiscovery Nuclei Scanner',
            'nessus' => 'Nessus Vulnerability Scanner',
            'dirbuster' => 'DirBuster Directory Brute-Forcer',
            'gobuster' => 'GoBuster Directory Brute-Forcer',
            'wpscan' => 'WPScan Exploit Probe',
            'masscan' => 'Masscan Port/Web Probe',
            'zgrab' => 'ZGrab Exploitation Bot',
            'havij' => 'Havij Automated SQLi Tool',
        ];

        foreach ($scannerSignatures as $sig => $label) {
            if ($cleanUa !== '' && str_contains($cleanUa, $sig)) {
                return [
                    'type' => "Vulnerability Scanner Bot ({$label})",
                    'payload' => "User-Agent: {$userAgent} | URI: {$uri}",
                ];
            }
        }

        // 2. Deteksi Honeypot & Probe Jalur Sensitif
        $honeypotPatterns = [
            '#(?:^|/)(\.env|\.env\.backup|\.env\.local|\.env\.prod|\.git/config|\.git/head|\.svn|\.DS_Store)(?:$|[/?])#i' => 'Sensitive Dotfile / Credential Probe (.env / .git)',
            '#(?:^|/)(wp-admin|wp-login\.php|xmlrpc\.php|wp-config\.php|wp-includes)(?:$|[/?])#i' => 'CMS Exploit Bot Probe (WordPress Target)',
            '#(?:^|/)(phpmyadmin|pma|myadmin|mysqladmin|adminer\.php)(?:$|[/?])#i' => 'Database Admin Backdoor Probe (phpMyAdmin/Adminer)',
            '#(?:^|/)(vendor/phpunit|eval-stdin\.php|cgi-bin/|actuator/env|_ignition/execute-solution)(?:$|[/?])#i' => 'Known CVE Exploit Probe (PHPUnit / Ignition RCE)',
        ];

        foreach ($honeypotPatterns as $regex => $threatLabel) {
            if (preg_match($regex, $cleanUri)) {
                return [
                    'type' => $threatLabel,
                    'payload' => substr($uri, 0, 240),
                ];
            }
        }

        return null;
    }
}



