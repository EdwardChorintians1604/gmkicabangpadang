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

