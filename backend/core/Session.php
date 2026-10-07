<?php

namespace App\Core;

class Session
{
    protected static bool $started = false;

    public static function start(): void
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $security = require dirname(__DIR__) . '/config/security.php';
        $idleTimeout = max(60, (int)($security['session_idle_timeout'] ?? 900));
        $absoluteTimeout = max($idleTimeout, (int)($security['session_absolute_timeout'] ?? 28800));
        if (session_status() === PHP_SESSION_NONE) {
            if (session_save_path() === '') {
                $sessionPath = dirname(__DIR__, 2)
                    . DIRECTORY_SEPARATOR . 'storage'
                    . DIRECTORY_SEPARATOR . 'sessions';

                if (!is_dir($sessionPath) || !is_writable($sessionPath)) {
                    throw new \RuntimeException('Session storage must exist and be writable: ' . $sessionPath);
                }

                if (session_save_path($sessionPath) === false) {
                    throw new \RuntimeException('Unable to configure session storage: ' . $sessionPath);
                }
            }

            ini_set('session.use_only_cookies', '1');
            ini_set('session.use_strict_mode', '1');

            session_set_cookie_params([
                // Session cookie: hapus saat browser ditutup, jangan simpan kredensial di disk.
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
        }

        // Pertahanan server-side: cookie yang dicuri atau dipulihkan browser tetap
        // kehilangan otorisasi setelah idle/masa hidup maksimum tercapai.
        $now = time();
        $createdAt = (int)($_SESSION['_session_created_at'] ?? $now);
        $lastActivity = (int)($_SESSION['_last_activity'] ?? $now);
        if (($now - $lastActivity) > $idleTimeout || ($now - $createdAt) > $absoluteTimeout) {
            $_SESSION = [];
            session_destroy();
            session_id('');
            session_start();
            $createdAt = $now;
        }
        $_SESSION['_session_created_at'] = $createdAt;
        $_SESSION['_last_activity'] = $now;

        self::$started = true;

        // Manage flash data lifetime
        if (!isset($_SESSION['_flash_old'])) {
            $_SESSION['_flash_old'] = [];
        }
        if (!isset($_SESSION['_flash_next'])) {
            $_SESSION['_flash_next'] = [];
        }

        // Clear aged flash messages from previous cycle
        $_SESSION['_flash_old'] = $_SESSION['_flash_next'];
        $_SESSION['_flash_next'] = [];
    }

    public static function regenerate(bool $deleteOldSession = true): void
    {
        self::start();
        if (!headers_sent()) {
            session_regenerate_id($deleteOldSession);
        }
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        self::$started = false;
    }

    public static function flash(string $key, mixed $value): void
    {
        self::start();
        $_SESSION['_flash_next'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION['_flash_old'][$key] ?? $_SESSION['_flash_next'][$key] ?? $default;
    }

    public static function hasFlash(string $key): bool
    {
        self::start();
        return isset($_SESSION['_flash_old'][$key]) || isset($_SESSION['_flash_next'][$key]);
    }

    public static function setOld(array $data): void
    {
        self::flash('_old_input', $data);
    }

    public static function old(string $key, mixed $default = null): mixed
    {
        $old = self::getFlash('_old_input', []);
        return $old[$key] ?? $default;
    }
}
