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
    function record_threat(string $threatType, ?string $payload = null, string $severity = 'medium'): void
    {
        try {
            $sql = "INSERT INTO `security_threats` (`threat_type`, `ip_address`, `user_agent`, `payload`, `severity`, `status`, `created_at`)
                    VALUES (:type, :ip, :ua, :payload, :severity, 'detected', NOW())";

            Database::execute($sql, [
                ':type' => $threatType,
                ':ip' => client_ip(),
                ':ua' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255),
                ':payload' => $payload ? substr($payload, 0, 1000) : null,
                ':severity' => $severity,
            ]);
        } catch (\Throwable $e) {
            error_log("Failed writing threat log: " . $e->getMessage());
        }
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


