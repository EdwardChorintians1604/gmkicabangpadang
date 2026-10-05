<?php

namespace App\Core;

class Auth
{
    protected const SESSION_USER_ID = '_auth_user_id';
    protected const SESSION_USER_DATA = '_auth_user_data';

    public static function check(): bool
    {
        Session::start();
        return Session::has(self::SESSION_USER_ID);
    }

    public static function guest(): bool
    {
        return !self::check();
    }

    public static function id(): ?int
    {
        Session::start();
        return Session::get(self::SESSION_USER_ID);
    }

    public static function user(): ?array
    {
        Session::start();
        return Session::get(self::SESSION_USER_DATA);
    }

    public static function role(): ?string
    {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function login(array $user): void
    {
        Session::start();
        Session::regenerate(true);
        Session::set(self::SESSION_USER_ID, (int)$user['id']);

        // Hilangkan password dari session data
        unset($user['password']);
        Session::set(self::SESSION_USER_DATA, $user);
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function updateSessionData(array $userData): void
    {
        unset($userData['password']);
        Session::set(self::SESSION_USER_DATA, $userData);
    }
}
