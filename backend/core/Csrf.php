<?php

namespace App\Core;

class Csrf
{
    protected const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        Session::start();
        $token = Session::get(self::SESSION_KEY);

        if (!$token || !is_string($token)) {
            $token = bin2hex(random_bytes(32));
            Session::set(self::SESSION_KEY, $token);
        }

        return $token;
    }

    public static function field(): string
    {
        $token = self::token();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function validate(?string $token): bool
    {
        Session::start();
        $sessionToken = Session::get(self::SESSION_KEY);

        if (!$sessionToken || !$token) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function refresh(): string
    {
        Session::start();
        $newToken = bin2hex(random_bytes(32));
        Session::set(self::SESSION_KEY, $newToken);
        return $newToken;
    }
}
